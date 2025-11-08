<?php /** @var array $categories */ ?>
<section class="section">
    <div class="container">
        <div class="d-flex justify-between align-center mb-3">
            <h1 class="section-title" style="font-size:2rem;"><?= e(t('admin.categories', $lang)) ?></h1>
            <a href="#" class="btn btn-primary">New Category</a>
        </div>
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-3">Categories are grouped by language. Create translations for all supported languages.</p>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Slug</th>
                            <th>Language</th>
                            <th>Title</th>
                            <th>Sort</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= (int) $category['id'] ?></td>
                                <td><?= e($category['slug']) ?></td>
                                <td><?= strtoupper($category['lang'] ?? '') ?></td>
                                <td><?= e($category['title']) ?></td>
                                <td><?= (int) $category['sort'] ?></td>
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
