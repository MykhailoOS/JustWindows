<?php
declare(strict_types=1);

return [
    'db' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'justwindows',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
    
    'app' => [
        'name' => 'JustWindows',
        'url' => getenv('APP_URL') ?: 'http://localhost',
        'timezone' => 'UTC',
        'default_lang' => 'ru',
        'supported_langs' => ['ru', 'uk', 'en', 'pl'],
    ],
    
    'security' => [
        'password_algo' => PASSWORD_ARGON2ID,
        'session_lifetime' => 86400 * 30, // 30 days
        'csrf_token_name' => '_token',
    ],
    
    'pagination' => [
        'per_page' => 24,
        'max_per_page' => 100,
    ],
    
    'upload' => [
        'max_size' => 2 * 1024 * 1024 * 1024, // 2GB
        'allowed_extensions' => ['exe', 'msi', 'zip', '7z', 'rar', 'iso', 'dmg', 'pkg'],
        'allowed_mimes' => [
            'application/x-msdownload',
            'application/x-msi',
            'application/zip',
            'application/x-7z-compressed',
            'application/x-rar-compressed',
            'application/x-iso9660-image',
            'application/octet-stream',
        ],
    ],
    
    'cache' => [
        'enabled' => true,
        'ttl' => 600, // 10 minutes
    ],
    
    'mail' => [
        'host' => getenv('MAIL_HOST') ?: 'smtp.mailtrap.io',
        'port' => getenv('MAIL_PORT') ?: 2525,
        'user' => getenv('MAIL_USER') ?: '',
        'pass' => getenv('MAIL_PASS') ?: '',
        'from' => getenv('MAIL_FROM') ?: 'noreply@justwindows.com',
        'from_name' => getenv('MAIL_FROM_NAME') ?: 'JustWindows',
    ],
    
    'rate_limit' => [
        'login' => ['attempts' => 5, 'decay' => 900],
        'register' => ['attempts' => 3, 'decay' => 3600],
        'comment' => ['attempts' => 10, 'decay' => 600],
        'download' => ['attempts' => 20, 'decay' => 60],
    ],
];
