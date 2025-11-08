<?php
declare(strict_types=1);

namespace App;

final class I18n
{
    private static array $dict = [];

    public static function translate(string $key, string $lang, array $vars = []): string
    {
        if (!isset(self::$dict[$lang])) {
            $path = __DIR__.'/../lang/'.$lang.'.json';
            if (!file_exists($path)) {
                self::$dict[$lang] = [];
            } else {
                self::$dict[$lang] = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
            }
        }

        $value = self::$dict[$lang];
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                $value = $key;
                break;
            }
            $value = $value[$segment];
        }

        if (!is_string($value)) {
            $value = $key;
        }

        foreach ($vars as $k => $v) {
            $value = str_replace('{'.$k.'}', (string) $v, $value);
        }

        return $value;
    }
}

function t(string $key, ?string $lang = null, array $vars = []): string
{
    $lang ??= Container::lang();
    return I18n::translate($key, $lang, $vars);
}
