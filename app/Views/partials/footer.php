<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3>JustWindows</h3>
                <p><?= e(t('home.description', $lang)) ?></p>
            </div>
            <div class="footer-section">
                <h3><?= e(t('nav.catalog', $lang)) ?></h3>
                <ul class="footer-links">
                    <li><a href="<?= url('/catalog?os=windows') ?>">Windows</a></li>
                    <li><a href="<?= url('/catalog?os=macos') ?>">macOS</a></li>
                    <li><a href="<?= url('/catalog?type=program') ?>"><?= e(t('catalog.type_program', $lang)) ?></a></li>
                    <li><a href="<?= url('/catalog?type=build') ?>"><?= e(t('catalog.type_build', $lang)) ?></a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3><?= e(t('nav.about', $lang)) ?></h3>
                <ul class="footer-links">
                    <li><a href="<?= url('/about') ?>"><?= e(t('nav.about', $lang)) ?></a></li>
                    <li><a href="<?= url('/contact') ?>"><?= e(t('nav.contact', $lang)) ?></a></li>
                    <li><a href="<?= url('/terms') ?>">Terms</a></li>
                    <li><a href="<?= url('/privacy') ?>">Privacy</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3><?= e(t('nav.admin', $lang)) ?></h3>
                <ul class="footer-links">
                    <?php if (auth() && is_admin()): ?>
                        <li><a href="<?= url('/admin') ?>"><?= e(t('admin.dashboard', $lang)) ?></a></li>
                        <li><a href="<?= url('/admin/items') ?>"><?= e(t('admin.items', $lang)) ?></a></li>
                        <li><a href="<?= url('/admin/categories') ?>"><?= e(t('admin.categories', $lang)) ?></a></li>
                        <li><a href="<?= url('/admin/comments') ?>"><?= e(t('admin.comments', $lang)) ?></a></li>
                    <?php else: ?>
                        <li><a href="<?= url('/login') ?>"><?= e(t('nav.login', $lang)) ?></a></li>
                        <li><a href="<?= url('/register') ?>"><?= e(t('nav.register', $lang)) ?></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> JustWindows. All rights reserved.</p>
        </div>
    </div>
</footer>
