<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Services\SeoService;
use App\Services\CacheService;

final class CatalogController extends Controller
{
    public function handle(string $path, array $query): void
    {
        $segments = array_values(array_filter(explode('/', trim($path, '/'))));
        array_shift($segments); // remove "catalog"
        $categorySlug = $segments[0] ?? null;

        $filters = [
            'os' => $query['os'] ?? null,
            'architecture' => $query['architecture'] ?? null,
            'license' => $query['license'] ?? null,
            'type' => $query['type'] ?? null,
            'sort' => $query['sort'] ?? 'popular',
            'category' => $categorySlug,
        ];

        $page = max(1, (int) ($query['page'] ?? 1));
        $perPage = min(config('pagination.max_per_page', 100), config('pagination.per_page', 24));
        $offset = ($page - 1) * $perPage;

        $cacheKey = 'catalog:'.md5(json_encode([$filters, $page, $this->lang]));
        $data = CacheService::get($cacheKey);

        if (!$data) {
            $itemModel = new Item();
            $categoryModel = new Category();

            $items = $itemModel->getAll($filters, $this->lang, $perPage, $offset);
            $total = $itemModel->count($filters);
            $categories = $categoryModel->all($this->lang);
            $currentCategory = $categorySlug ? $categoryModel->findBySlug($categorySlug, $this->lang) : null;

            $data = compact('items', 'total', 'categories', 'currentCategory');
            CacheService::set($cacheKey, $data);
        }

        $meta = (new SeoService($this->lang))->meta([
            'title' => t('catalog.title', $this->lang),
            'description' => t('catalog.description', $this->lang),
        ]);

        $pages = (int) ceil(($data['total'] ?? 0) / $perPage);

        $this->render('catalog.php', [
            'meta' => $meta,
            'items' => $data['items'] ?? [],
            'categories' => $data['categories'] ?? [],
            'currentCategory' => $data['currentCategory'] ?? null,
            'filters' => $filters,
            'page' => $page,
            'pages' => $pages,
            'perPage' => $perPage,
            'total' => $data['total'] ?? 0,
        ]);
    }
}
