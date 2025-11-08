-- Sample Seed Data for JustWindows Platform
-- This script provides initial demo data for testing

SET NAMES utf8mb4;

-- Insert demo users
INSERT INTO users (id, email, password_hash, display_name, role, is_email_verified) VALUES
(1, 'admin@justwindows.com', '$argon2id$v=19$m=65536,t=4,p=1$c2FsdHNhbHRzYWx0c2FsdA$qZkhzrx7Z9T5vDkEzFjrD+3lH3kGpxk8YvGqK/HhZ5o', 'Administrator', 'admin', 1),
(2, 'user@justwindows.com', '$argon2id$v=19$m=65536,t=4,p=1$c2FsdHNhbHRzYWx0c2FsdA$qZkhzrx7Z9T5vDkEzFjrD+3lH3kGpxk8YvGqK/HhZ5o', 'Demo User', 'user', 1);

-- Insert user profiles
INSERT INTO user_profiles (user_id, avatar_path, device_info, bio) VALUES
(1, '/public/assets/img/avatar-admin.png', 'Windows 11 Pro x64', 'Platform administrator'),
(2, '/public/assets/img/avatar-user.png', 'macOS Sonoma ARM64', 'Regular user');

-- Insert categories
INSERT INTO categories (id, slug, sort) VALUES
(1, 'operating-systems', 1),
(2, 'multimedia', 2),
(3, 'productivity', 3),
(4, 'utilities', 4),
(5, 'security', 5);

-- Insert category translations (RU)
INSERT INTO category_translations (category_id, lang, title) VALUES
(1, 'ru', 'Операционные системы'),
(2, 'ru', 'Мультимедиа'),
(3, 'ru', 'Продуктивность'),
(4, 'ru', 'Утилиты'),
(5, 'ru', 'Безопасность');

-- Insert category translations (EN)
INSERT INTO category_translations (category_id, lang, title) VALUES
(1, 'en', 'Operating Systems'),
(2, 'en', 'Multimedia'),
(3, 'en', 'Productivity'),
(4, 'en', 'Utilities'),
(5, 'en', 'Security');

-- Insert category translations (UK)
INSERT INTO category_translations (category_id, lang, title) VALUES
(1, 'uk', 'Операційні системи'),
(2, 'uk', 'Мультимедіа'),
(3, 'uk', 'Продуктивність'),
(4, 'uk', 'Утиліти'),
(5, 'uk', 'Безпека');

-- Insert category translations (PL)
INSERT INTO category_translations (category_id, lang, title) VALUES
(1, 'pl', 'Systemy operacyjne'),
(2, 'pl', 'Multimedia'),
(3, 'pl', 'Produktywność'),
(4, 'pl', 'Narzędzia'),
(5, 'pl', 'Bezpieczeństwo');

-- Insert tags
INSERT INTO tags (id, slug) VALUES
(1, 'windows-11'),
(2, 'windows-10'),
(3, 'free'),
(4, 'opensource'),
(5, 'popular');

-- Insert tag translations (RU)
INSERT INTO tag_translations (tag_id, lang, title) VALUES
(1, 'ru', 'Windows 11'),
(2, 'ru', 'Windows 10'),
(3, 'ru', 'Бесплатно'),
(4, 'ru', 'Open Source'),
(5, 'ru', 'Популярное');

-- Insert tag translations (EN)
INSERT INTO tag_translations (tag_id, lang, title) VALUES
(1, 'en', 'Windows 11'),
(2, 'en', 'Windows 10'),
(3, 'en', 'Free'),
(4, 'en', 'Open Source'),
(5, 'en', 'Popular');

-- Insert sample items
INSERT INTO items (id, type, slug, os, architecture, license, category_id, created_by, published_at, is_published, is_featured, downloads, views, rating, rating_count) VALUES
(1, 'build', 'windows-11-pro-22h2', 'windows', 'x64', 'free', 1, 1, NOW(), 1, 1, 15234, 48920, 4.7, 342),
(2, 'build', 'windows-10-ltsc-2021', 'windows', 'x64', 'free', 1, 1, NOW(), 1, 1, 23451, 62341, 4.9, 521),
(3, 'program', 'vlc-media-player', 'cross-platform', 'universal', 'opensource', 2, 1, NOW(), 1, 1, 45678, 120450, 4.8, 1203);

-- Insert item translations (RU)
INSERT INTO item_translations (item_id, lang, title, short_desc, description, meta_title, meta_description) VALUES
(1, 'ru', 'Windows 11 Pro 22H2', 'Последняя версия Windows 11 Pro с обновлением 22H2', 'Чистая установка Windows 11 Pro 22H2 со всеми последними обновлениями безопасности и функциями. Включает Microsoft Edge, Windows Defender и базовые приложения.\n\nОсобенности:\n- Оптимизация производительности\n- Обновления безопасности\n- Поддержка TPM 2.0\n- Современный интерфейс', 'Скачать Windows 11 Pro 22H2', 'Последняя версия Windows 11 Pro 22H2 для загрузки с актуальными обновлениями'),
(2, 'ru', 'Windows 10 LTSC 2021', 'Стабильная долгосрочная версия Windows 10', 'Windows 10 Enterprise LTSC 2021 — долгосрочная версия с поддержкой до 2032 года. Идеально подходит для корпоративного использования.\n\nПреимущества:\n- Отсутствие ненужных приложений\n- Долгосрочная поддержка\n- Максимальная стабильность\n- Оптимизация ресурсов', 'Windows 10 LTSC 2021 скачать', 'Корпоративная версия Windows 10 LTSC 2021 с долгосрочной поддержкой'),
(3, 'ru', 'VLC Media Player', 'Универсальный медиаплеер с открытым исходным кодом', 'VLC — мощный мультиформатный медиаплеер с открытым исходным кодом. Поддерживает практически все аудио и видео форматы без необходимости установки дополнительных кодеков.\n\nВозможности:\n- Поддержка всех популярных форматов\n- Потоковое воспроизведение\n- Конвертация видео\n- Кроссплатформенность', 'Скачать VLC Media Player', 'Бесплатный медиаплеер VLC с поддержкой всех форматов');

-- Insert item translations (EN)
INSERT INTO item_translations (item_id, lang, title, short_desc, description, meta_title, meta_description) VALUES
(1, 'en', 'Windows 11 Pro 22H2', 'Latest Windows 11 Pro with 22H2 update', 'Clean installation of Windows 11 Pro 22H2 with all the latest security updates and features. Includes Microsoft Edge, Windows Defender and essential apps.\n\nFeatures:\n- Performance optimization\n- Security updates\n- TPM 2.0 support\n- Modern interface', 'Download Windows 11 Pro 22H2', 'Latest Windows 11 Pro 22H2 version for download with updates'),
(2, 'en', 'Windows 10 LTSC 2021', 'Stable long-term Windows 10 version', 'Windows 10 Enterprise LTSC 2021 — long-term support version with updates until 2032. Perfect for enterprise use.\n\nBenefits:\n- No unnecessary apps\n- Long-term support\n- Maximum stability\n- Resource optimization', 'Download Windows 10 LTSC 2021', 'Enterprise Windows 10 LTSC 2021 with long-term support'),
(3, 'en', 'VLC Media Player', 'Universal open source media player', 'VLC — powerful multi-format media player with open source code. Supports almost all audio and video formats without additional codecs.\n\nCapabilities:\n- Support for all popular formats\n- Streaming playback\n- Video conversion\n- Cross-platform', 'Download VLC Media Player', 'Free VLC media player with support for all formats');

-- Insert item tags
INSERT INTO item_tags (item_id, tag_id) VALUES
(1, 1), (1, 5),
(2, 2), (2, 5),
(3, 4), (3, 5);

-- Insert item versions
INSERT INTO item_versions (id, item_id, version, release_date, min_os_version) VALUES
(1, 1, '22621.2215', '2023-09-15', 'UEFI with TPM 2.0'),
(2, 2, '19044.3570', '2023-10-10', 'BIOS/UEFI'),
(3, 3, '3.0.18', '2023-10-20', 'Windows 7+, macOS 10.10+');

-- Insert sample banners
INSERT INTO banners (id, image_path, target_url, target_type, sort_order, is_active) VALUES
(1, '/public/assets/img/banner1.jpg', '/ru/item/windows-11-pro-22h2', 'item', 1, 1),
(2, '/public/assets/img/banner2.jpg', '/ru/catalog', 'internal', 2, 1);

-- Insert banner translations (RU)
INSERT INTO banner_translations (banner_id, lang, title, subtitle, cta_label) VALUES
(1, 'ru', 'Windows 11 Pro 22H2', 'Новейшая версия с улучшениями производительности', 'Подробнее'),
(2, 'ru', 'Каталог программ', 'Более 1000 проверенных программ и сборок', 'Перейти');

-- Insert banner translations (EN)
INSERT INTO banner_translations (banner_id, lang, title, subtitle, cta_label) VALUES
(1, 'en', 'Windows 11 Pro 22H2', 'Latest version with performance improvements', 'Learn More'),
(2, 'en', 'Program Catalog', 'Over 1000 verified programs and builds', 'Browse');

-- Insert sample comments
INSERT INTO comments (item_id, user_id, body) VALUES
(1, 2, 'Отличная сборка! Все работает стабильно, рекомендую!'),
(1, 1, 'Спасибо за отзыв! Мы стараемся поддерживать актуальность версий.'),
(2, 2, 'LTSC — лучшая версия Windows для работы. Никакого лишнего ПО.');

-- Insert settings
INSERT INTO settings (`key`, value, type) VALUES
('site_name', 'JustWindows', 'string'),
('items_per_page', '24', 'integer'),
('enable_comments', '1', 'boolean'),
('enable_ratings', '1', 'boolean');
