<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Container;
use App\View;

abstract class Controller
{
    protected string $lang;

    public function __construct(string $lang)
    {
        $this->lang = $lang;
    }

    protected function render(string $template, array $data = [], ?string $layout = 'layouts/main.php'): void
    {
        $data['lang'] = $this->lang;
        echo View::render($template, $data, $layout);
    }

    protected function redirect(string $path): void
    {
        $url = sprintf('/%s/%s', $this->lang, ltrim($path, '/'));
        header('Location: '.$url);
        exit;
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function config(string $key, mixed $default = null): mixed
    {
        return Container::config($key, $default);
    }
}
