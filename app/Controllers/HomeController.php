<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\Item;
use App\Models\Banner;
use App\Models\Category;
use App\Services\SeoService;

final class HomeController extends Controller
{
    public function index(): void
    {
        $bannerModel = new Banner();
        $itemModel = new Item();
        $categoryModel = new Category();
        $seo = new SeoService($this->lang);

        $banners = $bannerModel->active($this->lang);
        $featured = $itemModel->getFeatured($this->lang, 6);
        $categories = $categoryModel->all($this->lang);

        $meta = $seo->meta([
            'title' => t('home.title', $this->lang).' — '.config('app.name'),
            'description' => t('home.description', $this->lang),
        ]);

        $this->render('home.php', compact('banners', 'featured', 'categories', 'meta'));
    }
}
