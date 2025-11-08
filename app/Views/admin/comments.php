<?php /** @var array $comments */ ?>
<section class="section">
    <div class="container">
        <h1 class="section-title" style="font-size:2rem;"><?= e(t('admin.comments', $lang)) ?></h1>
        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Item</th>
                            <th>Body</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comments as $comment): ?>
                            <tr>
                                <td><?= (int) $comment['id'] ?></td>
                                <td><?= e($comment['display_name']) ?></td>
                                <td><?= e($comment['slug']) ?></td>
                                <td><?= e(truncate($comment['body'], 80)) ?></td>
                                <td><?= format_datetime($comment['created_at']) ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
