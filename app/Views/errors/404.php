<!DOCTYPE html>
<html lang="<?= e($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page Not Found</title>
    <link href="/public/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container text-center" style="padding-top: 10vh;">
        <h1 style="font-size: 6rem; font-weight: 700; color: var(--text-muted);">404</h1>
        <h2 style="font-size: 2rem; margin-bottom: 1rem;">Page Not Found</h2>
        <p class="text-muted mb-4">The page you're looking for doesn't exist or has been moved.</p>
        <a href="<?= url('/', $lang) ?>" class="btn btn-primary">Go Home</a>
    </div>
</body>
</html>
