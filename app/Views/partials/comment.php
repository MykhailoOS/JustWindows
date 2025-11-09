<?php /** @var array $comment */ ?>
<div class="comment" id="comment-<?= (int) $comment['id'] ?>">
    <div class="comment-avatar" style="background-image:url('<?= e($comment['avatar_path'] ?? '/public/assets/img/avatar-placeholder.svg') ?>')"></div>
    <div class="comment-content">
        <div class="comment-header">
            <span class="comment-author"><?= e($comment['display_name']) ?></span>
            <?php if ($comment['role'] === 'admin'): ?>
                <span class="card-badge admin">admin</span>
            <?php endif; ?>
            <span class="comment-time"><?= time_ago($comment['created_at'], $lang) ?></span>
        </div>
        <div class="comment-body"><?= nl2br(e($comment['body'])) ?></div>
        <div class="comment-actions">
            <?php if (auth()): ?>
                <span class="comment-action comment-reply-btn" data-target="#reply-<?= (int) $comment['id'] ?>"><?= e(t('comment.reply', $lang)) ?></span>
            <?php endif; ?>
        </div>
        <?php if (auth()): ?>
            <form method="POST" action="/api/comments" class="mt-2 d-none" id="reply-<?= (int) $comment['id'] ?>">
                <input type="hidden" name="<?= e(config('security.csrf_token_name')) ?>" value="<?= csrf_token() ?>">
                <input type="hidden" name="item_id" value="<?= (int) $comment['item_id'] ?>">
                <input type="hidden" name="parent_id" value="<?= (int) $comment['id'] ?>">
                <textarea class="form-control mb-2" name="body" rows="2" placeholder="<?= e(t('comment.placeholder', $lang)) ?>" required></textarea>
                <button type="submit" class="btn btn-primary btn-sm"><?= e(t('comment.submit', $lang)) ?></button>
                <button type="button" class="btn btn-secondary btn-sm comment-reply-btn" data-target="#reply-<?= (int) $comment['id'] ?>"><?= e(t('comment.cancel', $lang)) ?></button>
            </form>
        <?php endif; ?>
        <?php if (!empty($comment['replies'])): ?>
            <div class="comment-replies">
                <?php foreach ($comment['replies'] as $reply): ?>
                    <?php $comment = $reply; require __DIR__.'/comment.php'; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
