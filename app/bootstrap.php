<?php
declare(strict_types=1);

require __DIR__.'/helpers.php';
require __DIR__.'/i18n.php';

$envFile = dirname(__DIR__).'/.env';
if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if ($value !== '' && (($value[0] === '"' && str_ends_with($value, '"')) || ($value[0] === "'" && str_ends_with($value, "'")))) {
            $value = substr($value, 1, -1);
        }
        if ($name === '') {
            continue;
        }
        $_ENV[$name] = $value;
        putenv($name.'='.$value);
    }
}

$config = require __DIR__.'/config.php';

date_default_timezone_set($config['app']['timezone']);

// Autoloader
spl_autoload_register(static function ($class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__.'/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = $baseDir.str_replace('\\', '/', $relative).'.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Database connection (PDO)
$pdo = null;
try {
    $dsn = sprintf(
        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
        $config['db']['host'],
        $config['db']['port'],
        $config['db']['name'],
        $config['db']['charset']
    );
    $pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Throwable $e) {
    error_log('DB connection failed: '.$e->getMessage());
}

App\Container::init($config, $pdo);
