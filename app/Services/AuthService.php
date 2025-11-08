<?php
declare(strict_types=1);

namespace App\Services;

use PDO;

final class AuthService
{
    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        if (!isset($_SESSION['user_data'])) {
            $db = \App\Container::db();
            if (!$db) {
                return null;
            }

            $stmt = $db->prepare('
                SELECT u.*, up.avatar_path, up.device_info, up.bio
                FROM users u
                LEFT JOIN user_profiles up ON up.user_id = u.id
                WHERE u.id = ?
            ');
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();

            if (!$user) {
                self::logout();
                return null;
            }

            $_SESSION['user_data'] = $user;
        }

        return $_SESSION['user_data'];
    }

    public static function login(string $email, string $password): bool
    {
        $db = \App\Container::db();
        if (!$db) {
            return false;
        }

        $stmt = $db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        if ($user['is_banned']) {
            return false;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_data'] = $user;

        self::createSession($user['id']);

        return true;
    }

    public static function logout(): void
    {
        if (isset($_SESSION['user_id'])) {
            self::destroySession($_SESSION['user_id']);
        }

        session_destroy();
        $_SESSION = [];
    }

    private static function createSession(int $userId): void
    {
        $db = \App\Container::db();
        if (!$db) {
            return;
        }

        $sessionId = session_id();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $expiresAt = date('Y-m-d H:i:s', time() + \App\Container::config('security.session_lifetime', 86400 * 30));

        $stmt = $db->prepare('
            INSERT INTO sessions (id, user_id, ip, user_agent, expires_at)
            VALUES (?, ?, INET6_ATON(?), ?, ?)
            ON DUPLICATE KEY UPDATE expires_at = ?
        ');
        $stmt->execute([$sessionId, $userId, $ip, $userAgent, $expiresAt, $expiresAt]);
    }

    private static function destroySession(int $userId): void
    {
        $db = \App\Container::db();
        if (!$db) {
            return;
        }

        $sessionId = session_id();
        $stmt = $db->prepare('DELETE FROM sessions WHERE id = ? AND user_id = ?');
        $stmt->execute([$sessionId, $userId]);
    }
}
