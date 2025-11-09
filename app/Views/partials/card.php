<?php /** @var array $item */ ?>
<a class="card" href="<?= url('/item/'.$item['slug']) ?>">
    <div class="card-image" style="background-image:url('<?= e($item['cover_image'] ?? '/public/assets/img/placeholder.svg') ?>')">
        <?php if (!empty($item['is_verified'])): ?>
            <span class="card-badge verified"><i class="bi bi-patch-check-fill"></i> <?= e(t('item.verified', $lang)) ?></span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <h3 class="card-title"><?= e($item['title']) ?></h3>
        <p class="card-desc"><?= e($item['short_desc'] ?? '') ?></p>
        <div class="card-meta">
            <span class="card-meta-item"><i class="bi bi-laptop"></i> <?= strtoupper($item['os']) ?></span>
            <span class="card-meta-item"><i class="bi bi-cpu"></i> <?= strtoupper($item['architecture']) ?></span>
        </div>
        <div class="card-footer">
            <div class="card-stats">
                <span class="card-stat"><i class="bi bi-download"></i> <?= format_number($item['downloads'] ?? 0) ?></span>
                <span class="card-stat"><i class="bi bi-eye"></i> <?= format_number($item['views'] ?? 0) ?></span>
            </div>
            <div class="card-rating">
                <?= stars((float) ($item['rating'] ?? 0), false) ?>
            </div>
        </div>
    </div>
</a>
