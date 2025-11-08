<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\SeoService;

final class ProfileController extends Controller
{
    public function show(string $name): void
    {
        $db = \App\Container::db();
        $stmt = $db->prepare('
            SELECT u.id, u.display_name, u.role, u.created_at,
                up.avatar_path, up.device_info, up.bio, up.location, up.website
            FROM users u
            LEFT JOIN user_profiles up ON up.user_id = u.id
            WHERE u.display_name = ?
            LIMIT 1
        ');
        $stmt->execute([$name]);
        $profile = $stmt->fetch();

        if (!$profile) {
            http_response_code(404);
            echo \App\View::render('errors/404.php', ['lang' => $this->lang]);
            return;
        }

        $stmt = $db->prepare('
            SELECT i.*, it.title
            FROM items i
            LEFT JOIN item_translations it ON it.item_id = i.id AND it.lang = ?
            WHERE i.created_by = ?
            ORDER BY i.published_at DESC
            LIMIT 10
        ');
        $stmt->execute([$this->lang, $profile['id']]);
        $items = $stmt->fetchAll();

        $seo = new SeoService($this->lang);
        $meta = $seo->meta([
            'title' => $profile['display_name'].' — '.config('app.name'),
            'description' => truncate(strip_tags((string) $profile['bio']), 150),
        ]);

        $this->render('profile.php', compact('profile', 'items', 'meta'));
    }
}
