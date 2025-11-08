<?php
$navItems = [
    ['label' => t('nav.home', $lang), 'href' => url('/')],
    ['label' => t('nav.catalog', $lang), 'href' => url('/catalog')],
    ['label' => t('nav.about', $lang), 'href' => url('/about')],
    ['label' => t('nav.contact', $lang), 'href' => url('/contact')],
];
?>
<header class="header">
    <div class="header-container">
        <a class="logo" href="<?= url('/') ?>">JustWindows</a>
        <nav class="nav">
            <ul class="nav-menu">
                <?php foreach ($navItems as $item): ?>
                    <li><a class="nav-link" href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <div class="d-flex gap-2 align-center">
            <div class="dropdown">
                <button class="btn btn-outline dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?= strtoupper($lang) ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <?php foreach (config('app.supported_langs', []) as $supported): ?>
                        <li><a class="dropdown-item" href="<?= '/'.$supported.'/'.implode('/', array_slice(array_values(array_filter(explode('/', trim($_SERVER['REQUEST_URI'], '/')))), 1)) ?>"><?= strtoupper($supported) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php if (auth()): ?>
                <a class="btn btn-primary" href="<?= url('/profile/'.user()['display_name']) ?>"><?= e(t('nav.profile', $lang)) ?></a>
            <?php else: ?>
                <a class="btn btn-primary" href="<?= url('/login') ?>"><?= e(t('nav.login', $lang)) ?></a>
            <?php endif; ?>
            <button class="burger" aria-label="Toggle navigation">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <div class="mobile-overlay"></div>
    <div class="mobile-menu">
        <button class="mobile-menu-close" aria-label="Close navigation">&times;</button>
        <ul class="mobile-nav-list">
            <?php foreach ($navItems as $item): ?>
                <li><a class="mobile-nav-link" href="<?= e($item['href']) ?>"><?= e($item['label']) ?></a></li>
            <?php endforeach; ?>
            <li class="mt-3">
                <?php if (auth()): ?>
                    <a class="btn btn-primary d-block" href="<?= url('/profile/'.user()['display_name']) ?>"><?= e(t('nav.profile', $lang)) ?></a>
                <?php else: ?>
                    <a class="btn btn-primary d-block" href="<?= url('/login') ?>"><?= e(t('nav.login', $lang)) ?></a>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</header>
