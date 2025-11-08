<?php /** @var array $banners */ ?>
<section class="section">
    <div class="container">
        <div class="d-flex justify-between align-center mb-3">
            <h1 class="section-title" style="font-size:2rem;"><?= e(t('admin.banners', $lang)) ?></h1>
            <a href="#" class="btn btn-primary">New Banner</a>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Language</th>
                            <th>Title</th>
                            <th>Active</th>
                            <th>Dates</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($banners as $banner): ?>
                            <tr>
                                <td><?= (int) $banner['id'] ?></td>
                                <td><img src="<?= e($banner['image_path']) ?>" alt="" style="width:80px;height:40px;object-fit:cover;border-radius:8px;"></td>
                                <td><?= strtoupper($banner['lang'] ?? '') ?></td>
                                <td><?= e($banner['title']) ?></td>
                                <td><?= $banner['is_active'] ? 'Yes' : 'No' ?></td>
                                <td><?= format_date($banner['start_date']) ?> — <?= format_date($banner['end_date']) ?></td>
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
