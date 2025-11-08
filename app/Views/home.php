<?php /** @var array $banners $featured $categories */ ?>
<section class="section pt-0">
    <div class="container">
        <?php if (!empty($banners)): ?>
            <div class="hero-slider">
                <?php foreach ($banners as $index => $banner): ?>
                    <div class="hero-slide<?= $index === 0 ? ' active' : '' ?>" style="background-image:url('<?= e($banner['image_path']) ?>')">
                        <div class="hero-content">
                            <h1 class="hero-title"><?= e($banner['title'] ?? t('home.hero_title', $lang)) ?></h1>
                            <p class="hero-subtitle"><?= e($banner['subtitle'] ?? t('home.hero_subtitle', $lang)) ?></p>
                            <?php if (!empty($banner['target_url'])): ?>
                                <a class="hero-cta" href="<?= e($banner['target_url']) ?>"><?= e($banner['cta_label'] ?? t('catalog.title', $lang)) ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="hero-dots">
                    <?php foreach ($banners as $index => $banner): ?>
                        <button class="hero-dot<?= $index === 0 ? ' active' : '' ?>" data-index="<?= $index ?>" aria-label="Slide <?= $index + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="skeleton skeleton-card"></div>
        <?php endif; ?>

        <div class="section">
            <div class="d-flex justify-between align-center mb-3">
                <h2 class="section-title"><?= e(t('home.featured', $lang)) ?></h2>
                <a class="btn btn-outline" href="<?= url('/catalog?sort=popular') ?>"><?= e(t('catalog.sort_popular', $lang)) ?></a>
            </div>
            <div class="card-grid">
                <?php foreach ($featured as $item): ?>
                    <?php require __DIR__.'/partials/card.php'; ?>
                <?php endforeach; ?>
                <?php if (empty($featured)): ?>
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <?php require __DIR__.'/partials/card_skeleton.php'; ?>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title"><?= e(t('home.categories', $lang)) ?></h2>
            <div class="card-grid">
                <?php foreach ($categories as $category): ?>
                    <a class="card" href="<?= url('/catalog/'.$category['slug']) ?>">
                        <div class="card-body">
                            <h3 class="card-title"><?= e($category['title']) ?></h3>
                            <p class="card-desc"><?= e(t('catalog.description', $lang)) ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
