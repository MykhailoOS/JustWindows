<?php
declare(strict_types=1);

namespace App\Services;

use App\Container;

final class RateLimitService
{
    public static function check(string $action, mixed $identifier): void
    {
        $db = Container::db();
        if (!$db) {
            return;
        }

        $config = Container::config('rate_limit.'.$action);
        if (!$config) {
            return;
        }

        $key = $action.':'.$identifier;
        $stmt = $db->prepare('
            SELECT * FROM rate_limits
            WHERE identifier = ? AND action = ?
            LIMIT 1
        ');
        $stmt->execute([$key, $action]);
        $record = $stmt->fetch();

        $now = time();

        if (!$record) {
            $stmt = $db->prepare('
                INSERT INTO rate_limits (identifier, action, attempts, reset_at)
                VALUES (?, ?, 1, ?)
            ');
            $stmt->execute([$key, $action, $now + $config['decay']]);
            return;
        }

        if ($now > strtotime($record['reset_at'])) {
            $stmt = $db->prepare('
                UPDATE rate_limits 
                SET attempts = 1, reset_at = ?
                WHERE id = ?
            ');
            $stmt->execute([$now + $config['decay'], $record['id']]);
            return;
        }

        if ($record['attempts'] >= $config['attempts']) {
            http_response_code(429);
            json_response(['error' => 'Too many attempts'], 429);
        }

        $stmt = $db->prepare('
            UPDATE rate_limits 
            SET attempts = attempts + 1
            WHERE id = ?
        ');
        $stmt->execute([$record['id']]);
    }
}
