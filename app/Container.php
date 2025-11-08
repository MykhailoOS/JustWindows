<?php
declare(strict_types=1);

namespace App;

final class Container
{
    private static array $config = [];
    private static ?PDO $pdo = null;
    private static string $lang = 'ru';

    public static function init(array $config, ?PDO $pdo): void
    {
        self::$config = $config;
        self::$pdo = $pdo;
    }

    public static function config(string $key, mixed $default = null): mixed
    {
        $segments = explode('.', $key);
        $value = self::$config;

        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }

    public static function db(): ?PDO
    {
        return self::$pdo;
    }

    public static function lang(): string
    {
        return self::$lang;
    }

    public static function setLang(string $lang): void
    {
        self::$lang = $lang;
    }
}
