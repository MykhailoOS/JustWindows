<?php /** @var array $items $categories $filters $page $pages */ ?>
<section class="section">
    <div class="container">
        <h1 class="section-title"><?= e(t('catalog.title', $lang)) ?></h1>
        
        <div class="row">
            <div class="col-md-3">
                <div class="filters">
                    <h3><?= e(t('catalog.filters', $lang)) ?></h3>
                    <form method="GET" action="<?= url('/catalog') ?>">
                        <div class="filter-group">
                            <label class="filter-label"><?= e(t('catalog.os', $lang)) ?></label>
                            <select class="filter-select" name="os">
                                <option value="">—</option>
                                <option value="windows" <?= ($filters['os'] ?? '') === 'windows' ? 'selected' : '' ?>>Windows</option>
                                <option value="macos" <?= ($filters['os'] ?? '') === 'macos' ? 'selected' : '' ?>>macOS</option>
                                <option value="linux" <?= ($filters['os'] ?? '') === 'linux' ? 'selected' : '' ?>>Linux</option>
                                <option value="cross-platform" <?= ($filters['os'] ?? '') === 'cross-platform' ? 'selected' : '' ?>>Cross-platform</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label"><?= e(t('catalog.architecture', $lang)) ?></label>
                            <select class="filter-select" name="architecture">
                                <option value="">—</option>
                                <option value="x64" <?= ($filters['architecture'] ?? '') === 'x64' ? 'selected' : '' ?>>x64</option>
                                <option value="x86" <?= ($filters['architecture'] ?? '') === 'x86' ? 'selected' : '' ?>>x86</option>
                                <option value="arm64" <?= ($filters['architecture'] ?? '') === 'arm64' ? 'selected' : '' ?>>ARM64</option>
                                <option value="universal" <?= ($filters['architecture'] ?? '') === 'universal' ? 'selected' : '' ?>>Universal</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label"><?= e(t('catalog.license', $lang)) ?></label>
                            <select class="filter-select" name="license">
                                <option value="">—</option>
                                <option value="free" <?= ($filters['license'] ?? '') === 'free' ? 'selected' : '' ?>>Free</option>
                                <option value="opensource" <?= ($filters['license'] ?? '') === 'opensource' ? 'selected' : '' ?>>Open Source</option>
                                <option value="trial" <?= ($filters['license'] ?? '') === 'trial' ? 'selected' : '' ?>>Trial</option>
                                <option value="commercial" <?= ($filters['license'] ?? '') === 'commercial' ? 'selected' : '' ?>>Commercial</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label"><?= e(t('catalog.type', $lang)) ?></label>
                            <select class="filter-select" name="type">
                                <option value="">—</option>
                                <option value="program" <?= ($filters['type'] ?? '') === 'program' ? 'selected' : '' ?>><?= e(t('catalog.type_program', $lang)) ?></option>
                                <option value="build" <?= ($filters['type'] ?? '') === 'build' ? 'selected' : '' ?>><?= e(t('catalog.type_build', $lang)) ?></option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label"><?= e(t('catalog.sort', $lang)) ?></label>
                            <select class="filter-select" name="sort">
                                <option value="popular" <?= ($filters['sort'] ?? '') === 'popular' ? 'selected' : '' ?>><?= e(t('catalog.sort_popular', $lang)) ?></option>
                                <option value="new" <?= ($filters['sort'] ?? '') === 'new' ? 'selected' : '' ?>><?= e(t('catalog.sort_new', $lang)) ?></option>
                                <option value="rating" <?= ($filters['sort'] ?? '') === 'rating' ? 'selected' : '' ?>><?= e(t('catalog.sort_rating', $lang)) ?></option>
                                <option value="downloads" <?= ($filters['sort'] ?? '') === 'downloads' ? 'selected' : '' ?>><?= e(t('catalog.sort_downloads', $lang)) ?></option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100"><?= e(t('catalog.apply_filters', $lang)) ?></button>
                        <a href="<?= url('/catalog') ?>" class="btn btn-outline w-100 mt-2"><?= e(t('catalog.reset_filters', $lang)) ?></a>
                    </form>
                </div>
            </div>

            <div class="col-md-9">
                <?php if (!empty($currentCategory)): ?>
                    <h2 class="mb-3"><?= e($currentCategory['title']) ?></h2>
                <?php endif; ?>

                <p class="text-muted mb-3">
                    <?= e(t('catalog.showing', $lang, [
                        'from' => (($page - 1) * ($perPage ?? 24)) + 1,
                        'to' => min($page * ($perPage ?? 24), $total ?? 0),
                        'total' => $total ?? 0
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
                                <?php
                                $query = array_merge($_GET, ['page' => $p]);
                                $queryString = http_build_query($query);
                                ?>
                                <a href="<?= url('/catalog?'.$queryString) ?>"><?= $p ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
