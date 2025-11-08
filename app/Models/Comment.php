<?php
declare(strict_types=1);

namespace App\Models;

final class Comment extends Model
{
    public function getByItem(int $itemId): array
    {
        $stmt = $this->db->prepare('
            SELECT c.*, 
                u.display_name, u.role,
                up.avatar_path
            FROM comments c
            INNER JOIN users u ON u.id = c.user_id
            LEFT JOIN user_profiles up ON up.user_id = u.id
            WHERE c.item_id = ? AND c.is_deleted = 0 AND c.parent_id IS NULL
            ORDER BY c.created_at DESC
        ');
        $stmt->execute([$itemId]);
        $comments = $stmt->fetchAll();

        foreach ($comments as &$comment) {
            $comment['replies'] = $this->getReplies($comment['id']);
        }

        return $comments;
    }

    public function getReplies(int $parentId): array
    {
        $stmt = $this->db->prepare('
            SELECT c.*, 
                u.display_name, u.role,
                up.avatar_path
            FROM comments c
            INNER JOIN users u ON u.id = c.user_id
            LEFT JOIN user_profiles up ON up.user_id = u.id
            WHERE c.parent_id = ? AND c.is_deleted = 0
            ORDER BY c.created_at ASC
        ');
        $stmt->execute([$parentId]);
        return $stmt->fetchAll();
    }

    public function create(int $itemId, int $userId, string $body, ?int $parentId = null): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO comments (item_id, user_id, parent_id, body)
            VALUES (?, ?, ?, ?)
        ');
        $stmt->execute([$itemId, $userId, $parentId, $body]);
        return (int) $this->db->lastInsertId();
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE comments SET is_deleted = 1 WHERE id = ?');
        $stmt->execute([$id]);
    }
}
