<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Item;
use App\Models\ItemVersion;
use App\Models\Comment;
use App\Services\SeoService;

final class ItemController extends Controller
{
    public function show(string $slug): void
    {
        $itemModel = new Item();
        $item = $itemModel->findBySlug($slug, $this->lang);

        if (!$item) {
            http_response_code(404);
            echo \App\View::render('errors/404.php', ['lang' => $this->lang]);
            return;
        }

        $itemModel->incrementViews((int) $item['id']);

        $versionModel = new ItemVersion();
        $versions = $versionModel->getByItem((int) $item['id']);

        $commentModel = new Comment();
        $comments = $commentModel->getByItem((int) $item['id']);

        $similar = $itemModel->getSimilar((int) $item['id'], (int) $item['category_id'], $this->lang, 6);

        $seo = new SeoService($this->lang);
        $meta = $seo->meta([
            'title' => ($item['meta_title'] ?: $item['title']).' — '.config('app.name'),
            'description' => $item['meta_description'] ?: $item['short_desc'],
            'type' => 'article',
        ]);

        $breadcrumb = SeoService::breadcrumb([
            ['name' => t('nav.home', $this->lang), 'url' => url('/', $this->lang)],
            ['name' => t('nav.catalog', $this->lang), 'url' => url('/catalog', $this->lang)],
            ['name' => $item['category_title'], 'url' => url('/catalog/'.$item['category_slug'], $this->lang)],
            ['name' => $item['title'], 'url' => url('/item/'.$item['slug'], $this->lang)],
        ]);

        $this->render('item.php', compact('item', 'versions', 'comments', 'similar', 'meta', 'breadcrumb'));
    }
}
