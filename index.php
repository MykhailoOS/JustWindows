<?php
declare(strict_types=1);

session_start();

require __DIR__.'/app/bootstrap.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$segments = array_values(array_filter(explode('/', $uri)));

$supportedLangs = config('app.supported_langs', ['ru', 'uk', 'en', 'pl']);
$lang = $segments[0] ?? config('app.default_lang');
if (!in_array($lang, $supportedLangs, true)) {
    $lang = config('app.default_lang');
} else {
    array_shift($segments);
}

\App\Container::setLang($lang);

$path = '/'.implode('/', $segments);

switch (true) {
    case $path === '/' || $path === '':
        (new \App\Controllers\HomeController($lang))->index();
        break;
    case str_starts_with($path, '/catalog'):
        (new \App\Controllers\CatalogController($lang))->handle($path, $_GET);
        break;
    case str_starts_with($path, '/item/'):
        $slug = substr($path, strlen('/item/'));
        (new \App\Controllers\ItemController($lang))->show($slug);
        break;
    case str_starts_with($path, '/tag/'):
        $slug = substr($path, strlen('/tag/'));
        (new \App\Controllers\TagController($lang))->show($slug);
        break;
    case str_starts_with($path, '/profile/'):
        $name = substr($path, strlen('/profile/'));
        (new \App\Controllers\ProfileController($lang))->show($name);
        break;
    case str_starts_with($path, '/admin'):
        (new \App\Controllers\AdminController($lang))->dispatch($path, $method);
        break;
    case $path === '/api/comments':
        (new \App\Controllers\Api\CommentsApi($lang))->dispatch($method);
        break;
    case $path === '/download':
        (new \App\Controllers\DownloadController())->serve($_GET);
        break;
    default:
        http_response_code(404);
        echo \App\View::render('errors/404.php', compact('lang'));
}
