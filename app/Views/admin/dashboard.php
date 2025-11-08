<?php /** @var array $metrics */ ?>
<section class="section">
    <div class="container">
        <h1 class="section-title"><?= e(t('admin.dashboard', $lang)) ?></h1>
        
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 style="font-size: 3rem; font-weight: 700; color: var(--primary);"><?= format_number($metrics['items']) ?></h3>
                        <p class="text-muted"><?= e(t('admin.items', $lang)) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 style="font-size: 3rem; font-weight: 700; color: var(--success);"><?= format_number($metrics['downloads']) ?></h3>
                        <p class="text-muted"><?= e(t('item.downloads', $lang)) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 style="font-size: 3rem; font-weight: 700; color: var(--info);"><?= format_number($metrics['users']) ?></h3>
                        <p class="text-muted"><?= e(t('admin.users', $lang)) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 style="font-size: 3rem; font-weight: 700; color: var(--warning);"><?= format_number($metrics['comments']) ?></h3>
                        <p class="text-muted"><?= e(t('admin.comments', $lang)) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h2 class="section-title">Quick Actions</h2>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="<?= url('/admin/items') ?>" class="btn btn-primary"><?= e(t('admin.items', $lang)) ?></a>
                    <a href="<?= url('/admin/categories') ?>" class="btn btn-outline"><?= e(t('admin.categories', $lang)) ?></a>
                    <a href="<?= url('/admin/tags') ?>" class="btn btn-outline"><?= e(t('admin.tags', $lang)) ?></a>
                    <a href="<?= url('/admin/comments') ?>" class="btn btn-outline"><?= e(t('admin.comments', $lang)) ?></a>
                    <a href="<?= url('/admin/banners') ?>" class="btn btn-outline"><?= e(t('admin.banners', $lang)) ?></a>
                </div>
            </div>
        </div>
    </div>
</section>
