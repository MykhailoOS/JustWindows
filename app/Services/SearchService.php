<?php
declare(strict_types=1);

namespace App\Services;

final class SearchService
{
    public function search(string $query, string $lang, int $limit = 20): array
    {
        $db = \App\Container::db();
        if (!$db) {
            return [];
        }

        $stmt = $db->prepare('
            SELECT i.*, it.title, it.short_desc,
                MATCH(it.title, it.short_desc, it.description) AGAINST(? IN NATURAL LANGUAGE MODE) AS relevance
            FROM item_translations it
            INNER JOIN items i ON i.id = it.item_id
            WHERE it.lang = ?
                AND i.is_published = 1
                AND i.published_at <= NOW()
                AND MATCH(it.title, it.short_desc, it.description) AGAINST(? IN NATURAL LANGUAGE MODE)
            ORDER BY relevance DESC
            LIMIT ?
        ');
        $stmt->execute([$query, $lang, $query, $limit]);
        $items = $stmt->fetchAll();

        return array_map(function ($item) use ($query) {
            $item['highlight'] = $this->highlight($item['short_desc'], $query);
            return $item;
        }, $items);
    }

    private function highlight(string $text, string $query): string
    {
        $words = preg_split('/\s+/', $query);
        foreach ($words as $word) {
            if ($word !== '') {
                $text = preg_replace('/('.preg_quote($word, '/').')/iu', '<mark>$1</mark>', $text);
            }
        }
        return $text;
    }
}
