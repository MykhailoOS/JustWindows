<?php /** @var array $items */ ?>
<section class="section">
    <div class="container">
        <div class="d-flex justify-between align-center mb-3">
            <h1 class="section-title" style="font-size:2rem;"><?= e(t('admin.items', $lang)) ?></h1>
            <a href="#" class="btn btn-primary">New Item</a>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th><?= e(t('catalog.type', $lang)) ?></th>
                            <th><?= e(t('nav.catalog', $lang)) ?></th>
                            <th><?= e(t('item.version', $lang)) ?></th>
                            <th><?= e(t('item.downloads', $lang)) ?></th>
                            <th><?= e(t('item.views', $lang)) ?></th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= (int) $item['id'] ?></td>
                                <td><?= ucfirst($item['type']) ?></td>
                                <td>
                                    <strong><?= e($item['title']) ?></strong><br>
                                    <small class="text-muted">Slug: <?= e($item['slug']) ?></small>
                                </td>
                                <td><?= e($item['version'] ?? '') ?></td>
                                <td><?= format_number($item['downloads']) ?></td>
                                <td><?= format_number($item['views']) ?></td>
                                <td>
                                    <?php if ($item['is_published']): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-outline">Edit</a>
                                        <button class="btn btn-sm btn-outline">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
