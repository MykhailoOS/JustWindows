<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\SeoService;

final class AdminController extends Controller
{
    public function dispatch(string $path, string $method): void
    {
        if (!auth() || !is_admin()) {
            http_response_code(403);
            echo \App\View::render('errors/403.php', ['lang' => $this->lang]);
            return;
        }

        $segments = array_values(array_filter(explode('/', trim($path, '/'))));
        array_shift($segments); // remove admin
        $section = $segments[0] ?? 'dashboard';

        return match ($section) {
            'items' => $this->items($method),
            'categories' => $this->categories($method),
            'tags' => $this->tags($method),
            'comments' => $this->comments($method),
            'banners' => $this->banners($method),
            default => $this->dashboard(),
        };
    }

    private function dashboard(): void
    {
        $db = \App\Container::db();

        $metrics = [
            'items' => (int) $db->query('SELECT COUNT(*) FROM items')->fetchColumn(),
            'downloads' => (int) $db->query('SELECT IFNULL(SUM(downloads),0) FROM items')->fetchColumn(),
            'users' => (int) $db->query('SELECT COUNT(*) FROM users')->fetchColumn(),
            'comments' => (int) $db->query('SELECT COUNT(*) FROM comments')->fetchColumn(),
        ];

        $seo = new SeoService($this->lang);
        $meta = $seo->meta([
            'title' => t('admin.dashboard', $this->lang),
        ]);

        $this->render('admin/dashboard.php', compact('metrics', 'meta'));
    }

    private function items(string $method): void
    {
        $db = \App\Container::db();
        if ($method === 'POST') {
            // Handle create/update logic placeholder
            if (!csrf_verify($_POST['_token'] ?? null)) {
                http_response_code(422);
                echo 'Invalid CSRF token';
                return;
            }
            // Implementation left for future extension
            redirect('/'.$this->lang.'/admin/items');
        }

        $stmt = $db->prepare('
            SELECT i.*, u.display_name as author, it.title
            FROM items i
            LEFT JOIN users u ON u.id = i.created_by
            LEFT JOIN item_translations it ON it.item_id = i.id AND it.lang = ?
            ORDER BY i.created_at DESC
            LIMIT 50
        ');
        $stmt->execute([$this->lang]);
        $items = $stmt->fetchAll();

        $meta = (new SeoService($this->lang))->meta([
            'title' => t('admin.items', $this->lang),
        ]);

        $this->render('admin/items.php', compact('items', 'meta'));
    }

    private function categories(string $method): void
    {
        $db = \App\Container::db();
        if ($method === 'POST') {
            if (!csrf_verify($_POST['_token'] ?? null)) {
                http_response_code(422);
                echo 'Invalid CSRF token';
                return;
            }
            // placeholder for CRUD operations
            redirect('/'.$this->lang.'/admin/categories');
        }

        $stmt = $db->prepare('
            SELECT c.*, ct.lang, ct.title
            FROM categories c
            LEFT JOIN category_translations ct ON ct.category_id = c.id
            ORDER BY c.sort ASC
        ');
        $stmt->execute();
        $categories = $stmt->fetchAll();

        $meta = (new SeoService($this->lang))->meta([
            'title' => t('admin.categories', $this->lang),
        ]);

        $this->render('admin/categories.php', compact('categories', 'meta'));
    }

    private function tags(string $method): void
    {
        $db = \App\Container::db();
        if ($method === 'POST') {
            if (!csrf_verify($_POST['_token'] ?? null)) {
                http_response_code(422);
                echo 'Invalid CSRF token';
                return;
            }
            redirect('/'.$this->lang.'/admin/tags');
        }

        $stmt = $db->prepare('
            SELECT t.*, tt.lang, tt.title
            FROM tags t
            LEFT JOIN tag_translations tt ON tt.tag_id = t.id
            ORDER BY t.id DESC
        ');
        $stmt->execute();
        $tags = $stmt->fetchAll();

        $meta = (new SeoService($this->lang))->meta([
            'title' => t('admin.tags', $this->lang),
        ]);

        $this->render('admin/tags.php', compact('tags', 'meta'));
    }

    private function comments(string $method): void
    {
        $db = \App\Container::db();
        if ($method === 'POST') {
            if (!csrf_verify($_POST['_token'] ?? null)) {
                http_response_code(422);
                echo 'Invalid CSRF token';
                return;
            }
            redirect('/'.$this->lang.'/admin/comments');
        }

        $stmt = $db->prepare('
            SELECT c.*, u.display_name, i.slug
            FROM comments c
            LEFT JOIN users u ON u.id = c.user_id
            LEFT JOIN items i ON i.id = c.item_id
            ORDER BY c.created_at DESC
            LIMIT 100
        ');
        $stmt->execute();
        $comments = $stmt->fetchAll();

        $meta = (new SeoService($this->lang))->meta([
            'title' => t('admin.comments', $this->lang),
        ]);

        $this->render('admin/comments.php', compact('comments', 'meta'));
    }

    private function banners(string $method): void
    {
        $db = \App\Container::db();
        if ($method === 'POST') {
            if (!csrf_verify($_POST['_token'] ?? null)) {
                http_response_code(422);
                echo 'Invalid CSRF token';
                return;
            }
            redirect('/'.$this->lang.'/admin/banners');
        }

        $stmt = $db->prepare('
            SELECT b.*, bt.lang, bt.title
            FROM banners b
            LEFT JOIN banner_translations bt ON bt.banner_id = b.id
            ORDER BY b.sort_order ASC
        ');
        $stmt->execute();
        $banners = $stmt->fetchAll();

        $meta = (new SeoService($this->lang))->meta([
            'title' => t('admin.banners', $this->lang),
        ]);

        $this->render('admin/banners.php', compact('banners', 'meta'));
    }
}
