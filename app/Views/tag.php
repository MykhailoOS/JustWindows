<?php /** @var array $tag $items $page $pages $total */ ?>
<section class="section">
    <div class="container">
        <h1 class="section-title"><?= e($tag['title']) ?></h1>
        <p class="section-subtitle"><?= e(t('tag.description', $lang, ['tag' => $tag['title']])) ?></p>
        
        <p class="text-muted mb-3">
            <?= e(t('catalog.showing', $lang, [
                'from' => (($page - 1) * 24) + 1,
                'to' => min($page * 24, $total),
                'total' => $total
            ])) ?>
        </p>

        <div class="card-grid">
            <?php foreach ($items as $item): ?>
                <?php require __DIR__.'/partials/card.php'; ?>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <p class="text-center"><?= e(t('catalog.no_results', $lang)) ?></p>
            <?php endif; ?>
        </div>

        <?php if ($pages > 1): ?>
            <div class="pagination">
                <?php for ($p = 1; $p <= $pages; $p++): ?>
                    <?php if ($p == $page): ?>
                        <span class="active"><?= $p ?></span>
                    <?php else: ?>
                        <a href="<?= url('/tag/'.$tag['slug'].'?page='.$p) ?>"><?= $p ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
