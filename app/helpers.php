<?php
declare(strict_types=1);

/**
 * Escape HTML
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Get configuration value
 */
function config(string $key, mixed $default = null): mixed
{
    return App\Container::config($key, $default);
}

/**
 * Get database connection
 */
function db(): ?PDO
{
    return App\Container::db();
}

/**
 * Get current user
 */
function user(): ?array
{
    return App\Services\AuthService::user();
}

/**
 * Check if user is authenticated
 */
function auth(): bool
{
    return App\Services\AuthService::check();
}

/**
 * Check if user has role
 */
function has_role(string $role): bool
{
    $u = user();
    return $u && $u['role'] === $role;
}

/**
 * Check if user is admin
 */
function is_admin(): bool
{
    return has_role('admin') || has_role('moderator');
}

/**
 * Generate CSRF token
 */
function csrf_token(): string
{
    if (!isset($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

/**
 * Verify CSRF token
 */
function csrf_verify(?string $token): bool
{
    return $token !== null && hash_equals($_SESSION['_csrf_token'] ?? '', $token);
}

/**
 * Redirect to URL
 */
function redirect(string $url, int $code = 302): never
{
    header("Location: $url", true, $code);
    exit;
}

/**
 * Generate URL with language prefix
 */
function url(string $path, ?string $lang = null): string
{
    $lang = $lang ?? App\Container::lang();
    $base = rtrim(config('app.url'), '/');
    $path = ltrim($path, '/');
    return "$base/$lang/$path";
}

/**
 * Get asset URL
 */
function asset(string $path): string
{
    $base = rtrim(config('app.url'), '/');
    $path = ltrim($path, '/');
    return "$base/public/assets/$path";
}

/**
 * Format number
 */
function format_number(int|float $num): string
{
    if ($num >= 1000000) {
        return round($num / 1000000, 1).'M';
    }
    if ($num >= 1000) {
        return round($num / 1000, 1).'K';
    }
    return (string) $num;
}

/**
 * Format date
 */
function format_date(?string $date, ?string $lang = null): string
{
    if (!$date) {
        return '';
    }
    $lang = $lang ?? App\Container::lang();
    $timestamp = strtotime($date);
    return date('d.m.Y', $timestamp);
}

/**
 * Format datetime
 */
function format_datetime(?string $datetime, ?string $lang = null): string
{
    if (!$datetime) {
        return '';
    }
    $lang = $lang ?? App\Container::lang();
    $timestamp = strtotime($datetime);
    return date('d.m.Y H:i', $timestamp);
}

/**
 * Time ago
 */
function time_ago(?string $datetime, ?string $lang = null): string
{
    if (!$datetime) {
        return '';
    }
    $lang = $lang ?? App\Container::lang();
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return t('time.just_now', $lang);
    }
    if ($diff < 3600) {
        $m = floor($diff / 60);
        return t('time.minutes_ago', $lang, ['count' => $m]);
    }
    if ($diff < 86400) {
        $h = floor($diff / 3600);
        return t('time.hours_ago', $lang, ['count' => $h]);
    }
    if ($diff < 2592000) {
        $d = floor($diff / 86400);
        return t('time.days_ago', $lang, ['count' => $d]);
    }
    return format_date($datetime, $lang);
}

/**
 * Star rating HTML
 */
function stars(float $rating, bool $showNumber = true): string
{
    $fullStars = floor($rating);
    $hasHalf = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($hasHalf ? 1 : 0);
    
    $html = '<span class="stars">';
    for ($i = 0; $i < $fullStars; $i++) {
        $html .= '<i class="bi bi-star-fill"></i>';
    }
    if ($hasHalf) {
        $html .= '<i class="bi bi-star-half"></i>';
    }
    for ($i = 0; $i < $emptyStars; $i++) {
        $html .= '<i class="bi bi-star"></i>';
    }
    $html .= '</span>';
    
    if ($showNumber) {
        $html .= ' <span class="rating-number">'.number_format($rating, 1).'</span>';
    }
    
    return $html;
}

/**
 * Truncate text
 */
function truncate(string $text, int $length = 100, string $suffix = '...'): string
{
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length).$suffix;
}

/**
 * Slugify string
 */
function slugify(string $text): string
{
    $text = transliterate($text);
    $text = preg_replace('/[^a-z0-9\-]/', '-', strtolower($text));
    $text = preg_replace('/-+/', '-', $text);
    return trim($text, '-');
}

/**
 * Transliterate Cyrillic to Latin
 */
function transliterate(string $text): string
{
    $map = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
        'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
        'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D',
        'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I',
        'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
        'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'Ts', 'Ч' => 'Ch',
        'Ш' => 'Sh', 'Щ' => 'Sch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '',
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        'і' => 'i', 'ї' => 'yi', 'є' => 'ye',
        'І' => 'I', 'Ї' => 'Yi', 'Є' => 'Ye',
    ];
    return strtr($text, $map);
}

/**
 * JSON response
 */
function json_response(mixed $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
