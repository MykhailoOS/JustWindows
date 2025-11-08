<!DOCTYPE html>
<html lang="<?= e($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title><?= e($meta['title'] ?? config('app.name')) ?></title>
    <meta name="description" content="<?= e($meta['description'] ?? '') ?>">
    <meta name="keywords" content="<?= e($meta['keywords'] ?? '') ?>">
    
    <link rel="canonical" href="<?= e($meta['url'] ?? '') ?>">
    
    <?php if (isset($hreflang)): ?>
        <?php foreach ($hreflang as $tag): ?>
            <link rel="alternate" hreflang="<?= e($tag['lang']) ?>" href="<?= e($tag['href']) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <meta property="og:type" content="<?= e($meta['type'] ?? 'website') ?>">
    <meta property="og:title" content="<?= e($meta['title'] ?? '') ?>">
    <meta property="og:description" content="<?= e($meta['description'] ?? '') ?>">
    <meta property="og:image" content="<?= e($meta['image'] ?? '') ?>">
    <meta property="og:url" content="<?= e($meta['url'] ?? '') ?>">
    <meta property="og:site_name" content="<?= e(config('app.name')) ?>">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($meta['title'] ?? '') ?>">
    <meta name="twitter:description" content="<?= e($meta['description'] ?? '') ?>">
    <meta name="twitter:image" content="<?= e($meta['image'] ?? '') ?>">
    
    <?php if (isset($breadcrumb)): ?>
        <script type="application/ld+json">
        <?= json_encode($breadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
        </script>
    <?php endif; ?>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/public/assets/css/style.css" rel="stylesheet">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
</head>
<body>
    <?php require __DIR__.'/../partials/header.php'; ?>
    
    <main class="main">
        <?= $content ?>
    </main>
    
    <?php require __DIR__.'/../partials/footer.php'; ?>
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/public/assets/js/app.js"></script>
</body>
</html>
