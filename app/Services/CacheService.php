<?php
declare(strict_types=1);

namespace App\Services;

final class CacheService
{
    private static string $cacheDir = __DIR__.'/../../storage/cache/';

    public static function get(string $key): mixed
    {
        if (!\App\Container::config('cache.enabled', true)) {
            return null;
        }

        $file = self::$cacheDir.md5($key).'.cache';
        if (!file_exists($file)) {
            return null;
        }

        $data = unserialize((string) file_get_contents($file));
        if ($data['expires'] < time()) {
            unlink($file);
            return null;
        }

        return $data['value'];
    }

    public static function set(string $key, mixed $value, ?int $ttl = null): void
    {
        if (!\App\Container::config('cache.enabled', true)) {
            return;
        }

        $ttl ??= \App\Container::config('cache.ttl', 600);
        $file = self::$cacheDir.md5($key).'.cache';

        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0755, true);
        }

        $data = [
            'value' => $value,
            'expires' => time() + $ttl,
        ];

        file_put_contents($file, serialize($data), LOCK_EX);
    }

    public static function forget(string $key): void
    {
        $file = self::$cacheDir.md5($key).'.cache';
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public static function clear(): void
    {
        $files = glob(self::$cacheDir.'*.cache');
        if ($files) {
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
}
