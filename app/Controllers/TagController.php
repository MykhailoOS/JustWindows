<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\SeoService;

final class TagController extends Controller
{
    public function show(string $slug): void
    {
        $db = \App\Container::db();
        
        $stmt = $db->prepare('
            SELECT t.*, tt.title
            FROM tags t
            LEFT JOIN tag_translations tt ON tt.tag_id = t.id AND tt.lang = ?
            WHERE t.slug = ?
            LIMIT 1
        ');
        $stmt->execute([$this->lang, $slug]);
        $tag = $stmt->fetch();

        if (!$tag) {
            http_response_code(404);
            echo \App\View::render('errors/404.php', ['lang' => $this->lang]);
            return;
        }

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 24;
        $offset = ($page - 1) * $perPage;

        $stmt = $db->prepare('
            SELECT i.*, it.title, it.short_desc
            FROM items i
            INNER JOIN item_tags itag ON itag.item_id = i.id
            LEFT JOIN item_translations it ON it.item_id = i.id AND it.lang = ?
            WHERE itag.tag_id = ? AND i.is_published = 1 AND i.published_at <= NOW()
            ORDER BY i.published_at DESC
            LIMIT ? OFFSET ?
        ');
        $stmt->execute([$this->lang, $tag['id'], $perPage, $offset]);
        $items = $stmt->fetchAll();

        $stmt = $db->prepare('
            SELECT COUNT(*) 
            FROM items i
            INNER JOIN item_tags itag ON itag.item_id = i.id
            WHERE itag.tag_id = ? AND i.is_published = 1 AND i.published_at <= NOW()
        ');
        $stmt->execute([$tag['id']]);
        $total = (int) $stmt->fetchColumn();

        $seo = new SeoService($this->lang);
        $meta = $seo->meta([
            'title' => $tag['title'].' — '.config('app.name'),
            'description' => t('tag.description', $this->lang, ['tag' => $tag['title']]),
        ]);

        $pages = (int) ceil($total / $perPage);

        $this->render('tag.php', compact('tag', 'items', 'meta', 'page', 'pages', 'total'));
    }
}
