<?php /** @var array $item $versions $comments $similar */ ?>
<section class="section">
    <div class="container">
        <nav class="breadcrumb">
            <a href="<?= url('/') ?>"><?= e(t('nav.home', $lang)) ?></a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/catalog') ?>"><?= e(t('nav.catalog', $lang)) ?></a>
            <span class="breadcrumb-separator">/</span>
            <a href="<?= url('/catalog/'.$item['category_slug']) ?>"><?= e($item['category_title']) ?></a>
            <span class="breadcrumb-separator">/</span>
            <span><?= e($item['title']) ?></span>
        </nav>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-image" style="background-image:url('<?= e($item['cover_image'] ?? '/public/assets/img/placeholder.png') ?>')"></div>
                    <div class="card-body">
                        <h1 class="card-title"><?= e($item['title']) ?></h1>
                        <p class="text-muted mb-3"><?= e($item['short_desc']) ?></p>
                        <div class="mb-2"><strong><?= e(t('item.version', $lang)) ?>:</strong> <?= e($item['current_version'] ?? $item['version'] ?? '') ?></div>
                        <div class="mb-2"><strong><?= e(t('item.os', $lang)) ?>:</strong> <?= strtoupper($item['os']) ?></div>
                        <div class="mb-2"><strong><?= e(t('item.architecture', $lang)) ?>:</strong> <?= strtoupper($item['architecture']) ?></div>
                        <div class="mb-2"><strong><?= e(t('item.license', $lang)) ?>:</strong> <?= ucfirst($item['license']) ?></div>
                        <div class="mb-3">
                            <strong><?= e(t('item.rating', $lang)) ?>:</strong> <?= stars((float) $item['rating']) ?> (<?= $item['rating_count'] ?? 0 ?>)
                        </div>
                        <div class="d-flex gap-2 mb-3">
                            <span class="card-meta-item"><i class="bi bi-download"></i> <?= format_number($item['downloads'] ?? 0) ?> <?= e(t('item.downloads', $lang)) ?></span>
                            <span class="card-meta-item"><i class="bi bi-eye"></i> <?= format_number($item['views'] ?? 0) ?> <?= e(t('item.views', $lang)) ?></span>
                        </div>
                        <a class="btn btn-primary w-100" href="/download?file_id=<?= $item['primary_file_id'] ?? '' ?>">
                            <i class="bi bi-arrow-down-circle"></i> <?= e(t('item.download', $lang)) ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="itemTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                                    <?= e(t('item.description', $lang)) ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="versions-tab" data-bs-toggle="tab" data-bs-target="#versions" type="button" role="tab">
                                    <?= e(t('item.versions', $lang)) ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="requirements-tab" data-bs-toggle="tab" data-bs-target="#requirements" type="button" role="tab">
                                    <?= e(t('item.requirements', $lang)) ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="changelog-tab" data-bs-toggle="tab" data-bs-target="#changelog" type="button" role="tab">
                                    <?= e(t('item.changelog', $lang)) ?>
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content mt-3" id="itemTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel">
                                <?= $item['description'] ?>
                            </div>
                            <div class="tab-pane fade" id="versions" role="tabpanel">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><?= e(t('item.version', $lang)) ?></th>
                                            <th><?= e(t('item.changelog', $lang)) ?></th>
                                            <th><?= e(t('item.download', $lang)) ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($versions as $version): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= e($version['version']) ?></strong><br>
                                                    <small class="text-muted"><?= format_date($version['release_date']) ?></small>
                                                </td>
                                                <td><?= nl2br(e($version['changelog'] ?? '')) ?></td>
                                                <td>
                                                    <?php if (!empty($version['files'])): ?>
                                                        <?php foreach ($version['files'] as $file): ?>
                                                            <a class="btn btn-outline btn-sm d-block mb-2" href="/download?file_id=<?= $file['id'] ?>">
                                                                <?= e($file['file_name']) ?> (<?= round(($file['file_size'] ?? 0) / (1024 * 1024), 1) ?> MB)
                                                            </a>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <small class="text-muted">No files</small>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="requirements" role="tabpanel">
                                <?= nl2br(e($item['system_requirements'] ?? '')) ?>
                            </div>
                            <div class="tab-pane fade" id="changelog" role="tabpanel">
                                <?= nl2br(e($item['changelog'] ?? '')) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <h2><?= e(t('item.comments', $lang)) ?></h2>
                        <div data-comments-poll data-item-id="<?= (int) $item['id'] ?>" data-last-id="<?= $comments ? max(array_column($comments, 'id')) : 0 ?>">
                            <?php foreach ($comments as $comment): ?>
                                <?php require __DIR__.'/partials/comment.php'; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php if (auth()): ?>
                            <form method="POST" action="/api/comments" class="mt-3">
                                <input type="hidden" name="<?= e(config('security.csrf_token_name')) ?>" value="<?= csrf_token() ?>">
                                <input type="hidden" name="item_id" value="<?= (int) $item['id'] ?>">
                                <textarea class="form-control mb-2" name="body" rows="3" placeholder="<?= e(t('comment.placeholder', $lang)) ?>" required></textarea>
                                <button type="submit" class="btn btn-primary"><?= e(t('comment.submit', $lang)) ?></button>
                            </form>
                        <?php else: ?>
                            <p class="text-muted mt-3"><?= e(t('nav.login', $lang)) ?> <?= e(t('comment.add', $lang)) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h2><?= e(t('item.similar', $lang)) ?></h2>
                        <div class="card-grid mt-3">
                            <?php foreach ($similar as $similarItem): ?>
                                <?php $item = $similarItem; require __DIR__.'/partials/card.php'; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
