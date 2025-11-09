<?php /** @var array $profile $items */ ?>
<section class="section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="comment-avatar mb-3" style="margin:0 auto; width:96px; height:96px; background-image:url('<?= e($profile['avatar_path'] ?? '/public/assets/img/avatar-placeholder.svg') ?>')"></div>
                        <h1 class="section-title" style="font-size:1.5rem; margin-bottom:0;"><?= e($profile['display_name']) ?></h1>
                        <p class="text-muted mt-1"><?= ucfirst($profile['role']) ?></p>
                        <?php if (!empty($profile['device_info'])): ?>
                            <p class="mb-2"><i class="bi bi-laptop"></i> <?= e($profile['device_info']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($profile['location'])): ?>
                            <p class="mb-2"><i class="bi bi-geo-alt"></i> <?= e($profile['location']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($profile['website'])): ?>
                            <p class="mb-2"><a href="<?= e($profile['website']) ?>" target="_blank" rel="noreferrer">Website</a></p>
                        <?php endif; ?>
                        <p class="text-muted mt-2">Регистрация: <?= format_date($profile['created_at']) ?></p>
                    </div>
                </div>
                <?php if (!empty($profile['bio'])): ?>
                    <div class="card mt-3">
                        <div class="card-body">
                            <h3 class="mb-2">Bio</h3>
                            <p><?= nl2br(e($profile['bio'])) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="section-title" style="font-size:1.5rem;">Последние публикации</h2>
                        <div class="card-grid mt-3">
                            <?php foreach ($items as $item): ?>
                                <?php require __DIR__.'/partials/card.php'; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php if (empty($items)): ?>
                            <p class="text-muted mt-3">Пока нет публикаций.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
