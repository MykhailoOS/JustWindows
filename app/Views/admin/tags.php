<?php /** @var array $tags */ ?>
<section class="section">
    <div class="container">
        <div class="d-flex justify-between align-center mb-3">
            <h1 class="section-title" style="font-size:2rem;"><?= e(t('admin.tags', $lang)) ?></h1>
            <a href="#" class="btn btn-primary">New Tag</a>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Slug</th>
                            <th>Language</th>
                            <th>Title</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tags as $tag): ?>
                            <tr>
                                <td><?= (int) $tag['id'] ?></td>
                                <td><?= e($tag['slug']) ?></td>
                                <td><?= strtoupper($tag['lang'] ?? '') ?></td>
                                <td><?= e($tag['title']) ?></td>
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
