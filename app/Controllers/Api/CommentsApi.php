<?php
declare(strict_types=1);

namespace App\Controllers\Api;

use App\Controllers\Controller;
use App\Models\Comment;
use App\Services\RateLimitService;

final class CommentsApi extends Controller
{
    public function dispatch(string $method): void
    {
        return match ($method) {
            'GET' => $this->poll(),
            'POST' => $this->store(),
            default => $this->json(['error' => 'Method not allowed'], 405),
        };
    }

    private function poll(): void
    {
        $itemId = (int) ($_GET['item_id'] ?? 0);
        $sinceId = (int) ($_GET['since_id'] ?? 0);
        $commentModel = new Comment();

        $db = \App\Container::db();
        $stmt = $db->prepare('
            SELECT c.*, 
                u.display_name, u.role,
                up.avatar_path
            FROM comments c
            INNER JOIN users u ON u.id = c.user_id
            LEFT JOIN user_profiles up ON up.user_id = u.id
            WHERE c.item_id = ? AND c.id > ? AND c.is_deleted = 0
            ORDER BY c.created_at ASC
        ');
        $stmt->execute([$itemId, $sinceId]);
        $comments = $stmt->fetchAll();

        $this->json(['comments' => $comments]);
    }

    private function store(): void
    {
        if (!auth()) {
            $this->json(['error' => 'Unauthorized'], 401);
        }

        if (!csrf_verify($_POST['_token'] ?? null)) {
            $this->json(['error' => 'Invalid CSRF token'], 422);
        }

        $itemId = (int) ($_POST['item_id'] ?? 0);
        $body = trim((string) ($_POST['body'] ?? ''));
        $parentId = isset($_POST['parent_id']) ? (int) $_POST['parent_id'] : null;

        if (!$itemId || $body === '') {
            $this->json(['error' => 'Invalid payload'], 422);
        }

        RateLimitService::check('comment', user()['id']);

        $commentModel = new Comment();
        $commentId = $commentModel->create($itemId, user()['id'], $body, $parentId);

        $this->json(['success' => true, 'id' => $commentId]);
    }
}
