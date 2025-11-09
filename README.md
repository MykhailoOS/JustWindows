# JustWindows Platform

JustWindows — минималистичная платформа-каталог для сборок Windows и программ с поддержкой macOS. Репозиторий содержит готовую инфраструктуру для фронтенда и бэкенда, шаблоны страниц, i18n и схемы БД.

## Архитектура

```
Browser (Bootstrap 5 + jQuery)
       | AJAX / SSE
       v
index.php (Front Controller, Router)
 -> Controllers (Home, Catalog, Item, Profile, Comments, Admin, Download)
 -> Services (Auth, Upload, Search, SEO, I18n, RateLimit)
 -> Models (User, Item, Version, File, Comment, Category, Tag, Banner)
 -> Views (PHP templates)
 -> Storage: MySQL (InnoDB, FULLTEXT) + /storage (files outside web-root)
```

## Технологический стек

- **Back-end:** PHP 8.2+, PDO, чистый PHP-темплейтинг
- **Front-end:** Bootstrap 5, CSS custom properties, jQuery, IntersectionObserver, skeleton loaders
- **База данных:** MySQL 8.0 (InnoDB, FULLTEXT), миграция `scripts/database.sql`
- **Реал-тайм:** AJAX long-polling / SSE с готовым API (подготовка к Workerman/Swoole)
- **Безопасность:** CSRF, XSS, rate limiting, Argon2id, приватное хранение файлов
- **SEO:** ЧПУ, canonical, hreflang, Open Graph/Twitter, schema.org (SoftwareApplication/BreadcrumbList), sitemap/robots

## Структура каталогов

```
/index.php               # фронт-контроллер
/.htaccess               # Apache rewrite -> index.php
/app/
  bootstrap.php          # автозагрузка, PDO, контейнер
  config.php             # конфигурация приложения
  helpers.php            # вспомогательные функции
  i18n.php               # система переводов
  Container.php          # DI контейнер
  View.php               # рендер шаблонов
  Controllers/
    HomeController.php
    CatalogController.php
    ItemController.php
    ProfileController.php
    TagController.php
    AdminController.php
    DownloadController.php
    Api/CommentsApi.php
  Models/
    Model.php
    Item.php
    ItemVersion.php
    ItemFile.php
    Category.php
    Comment.php
    Banner.php
  Services/
    AuthService.php
    I18nService.php
    CacheService.php
    SeoService.php
    SearchService.php
    RateLimitService.php
/lang/                   # JSON переводы: ru, uk, en, pl
/public/
  assets/
    css/
    js/
    img/
  uploads_public/        # безопасные публичные файлы
/storage/
  files/                 # приватные загрузки (выдача через DownloadController)
  cache/                 # файловый кеш
  logs/
/scripts/
  database.sql           # полная схема БД
```

## Страницы

- **Главная:** hero-слайдер, поиск, skeleton-карточки, “Лучшее”, категории
- **Каталог:** фильтры OS/архитектура/лицензия/тип, сортировка, пагинация, карточки
- **Карточка продукта:** версия, архитектура, лицензия, rating, changelog, screenshots, schema.org, комментарии с тредами, “Download”
- **Профиль:** имя, аватар, устройство, био, активность
- **Админ-панель:** дашборд, управление публикациями, версиями, файлами, категориями/тегами, баннерами, комментариями, статистика

## Реал-тайм комментарии

1. **Long-polling (готово):** `/api/comments?item_id=&since_id=`
2. **SSE (план):** отправка `text/event-stream`
3. **WebSocket (target):** Workerman/Swoole, реакция/typing

## I18n

- URL-префикс: `/ru/`, `/uk/`, `/en/`, `/pl/`
- JSON переводы в `lang/*.json`
- Переключатель языка с cookie + hreflang
- Переводы контента в таблицах `*_translations`

## SEO и перфоманс

- Чистые URL и canonical ссылки
- Open Graph, Twitter Cards, schema.org (SoftwareApplication/BreadcrumbList/AggregateRating)
- Lazy-load изображений, skeleton loaders, WebP/AVIF (готовность), preconnect
- LCP < 2.5s за счёт оптимизированных активов и кеширования

## Безопасность

- Password hashing: Argon2id
- CSRF токены, rate limiting, логирование скачиваний, audit log
- Файлы вне web-root (`/storage/files`) + валидация загрузок
- Сессии в БД, HttpOnly, SameSite=Lax

## Запуск

1. Скопируйте файл `.env.example` в `.env` и при необходимости обновите значения (по умолчанию прописаны production-параметры InfinityFree).
2. Установите зависимости (Composer — опционально, например для HTMLPurifier или PHPMailer).
3. Импортируйте схему БД `scripts/database.sql` в `if0_39948852_just_windows` (или своё название, указанное в `.env`).
4. Настройте виртуальный хост/домен на корень репозитория, чтобы `index.php` был фронт-контроллером.
5. Проверьте права на директории `/storage/*` и `public/uploads_public/` — они должны быть доступны PHP, но закрыты от прямого веб-доступа.

## Лицензия

MIT (можно изменить под бизнес-требования)
