<?php
declare(strict_types=1);

namespace App\Services;

use App\Container;

final class SeoService
{
    public function __construct(private string $lang)
    {
    }

    public function meta(array $data = []): array
    {
        $defaults = [
            'title' => Container::config('app.name', 'JustWindows'),
            'description' => 'JustWindows — каталог сборок Windows и программ с поддержкой macOS. Актуальные версии, фильтры, рейтинги и скачивания.',
            'keywords' => 'Windows, macOS, программы, каталог, сборки',
            'image' => asset('img/og-default.jpg'),
            'url' => Container::config('app.url'),
            'type' => 'website',
        ];

        return array_merge($defaults, $data);
    }

    public function hreflang(string $path): array
    {
        $langs = Container::config('app.supported_langs', ['ru', 'uk', 'en', 'pl']);
        $host = rtrim(Container::config('app.url'), '/');
        
        $tags = [];
        foreach ($langs as $lang) {
            $tags[] = [
                'lang' => $lang,
                'href' => $host.'/'.$lang.'/'.ltrim($path, '/')
            ];
        }
        $tags[] = [
            'lang' => 'x-default',
            'href' => $host.'/'.Container::config('app.default_lang').'/'.ltrim($path, '/')
        ];
        
        return $tags;
    }

    public function canonical(string $path): string
    {
        $host = rtrim(Container::config('app.url'), '/');
        return $host.'/'.$this->lang.'/'.ltrim($path, '/');
    }

    public static function breadcrumb(array $items): array
    {
        $list = [];
        $position = 1;
        foreach ($items as $item) {
            $list[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $item['name'],
                'item' => $item['url'],
            ];
        }
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $list,
        ];
    }
}
