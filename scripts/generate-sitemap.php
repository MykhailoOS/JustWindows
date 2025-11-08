<?php
declare(strict_types=1);

require __DIR__.'/../app/bootstrap.php';

$baseUrl = rtrim(config('app.url'), '/');
$langs = config('app.supported_langs', ['ru', 'uk', 'en', 'pl']);
$db = db();

$urls = [];
$now = date('c');

foreach ($langs as $lang) {
    $urls[] = [
        'loc' => "$baseUrl/$lang/",
        'lastmod' => $now,
        'changefreq' => 'daily',
        'priority' => '1.0',
    ];

    $urls[] = [
        'loc' => "$baseUrl/$lang/catalog",
        'lastmod' => $now,
        'changefreq' => 'daily',
        'priority' => '0.9',
    ];
}

if ($db) {
    $stmt = $db->query('SELECT slug, updated_at FROM items WHERE is_published = 1 AND published_at <= NOW()');
    $items = $stmt->fetchAll();

    foreach ($items as $item) {
        foreach ($langs as $lang) {
            $urls[] = [
                'loc' => "$baseUrl/$lang/item/{$item['slug']}",
                'lastmod' => date('c', strtotime($item['updated_at'] ?? $item['created_at'] ?? $now)),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }
    }
}

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>');
foreach ($urls as $url) {
    $urlElement = $xml->addChild('url');
    $urlElement->addChild('loc', htmlspecialchars($url['loc'], ENT_QUOTES));
    $urlElement->addChild('lastmod', $url['lastmod']);
    $urlElement->addChild('changefreq', $url['changefreq']);
    $urlElement->addChild('priority', $url['priority']);
}

$file = __DIR__.'/../public/sitemap.xml';
$xml->asXML($file);

echo "Sitemap generated at $file\n";
