<?php
declare(strict_types=1);

namespace App\Services;

use App\Container;

final class I18nService
{
    public static function determineLanguage(string $requestedLang, array $supported): string
    {
        if (in_array($requestedLang, $supported, true)) {
            return $requestedLang;
        }

        $cookieLang = $_COOKIE['lang'] ?? null;
        if ($cookieLang && in_array($cookieLang, $supported, true)) {
            return $cookieLang;
        }

        $accept = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        if ($accept) {
            $langs = array_map(static fn ($l) => substr($l, 0, 2), explode(',', $accept));
            foreach ($langs as $lang) {
                if (in_array($lang, $supported, true)) {
                    return $lang;
                }
            }
        }

        return Container::config('app.default_lang', 'ru');
    }

    public static function setLanguage(string $lang): void
    {
        setcookie('lang', $lang, time() + 3600 * 24 * 30, '/', '', isset($_SERVER['HTTPS']), true);
        Container::setLang($lang);
    }
}
