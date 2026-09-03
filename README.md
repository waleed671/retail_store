# Local Retail Store — Laravel E-Commerce Platform

A working Laravel 10 e-commerce application for a local Pakistani retail store: product
catalog, cart, checkout (Cash on Delivery + bank transfer), customer accounts, order
tracking, reviews, wishlist, and an admin panel (products, categories, orders, customers,
dashboard).

Tailwind is loaded via CDN — there's **no Node/npm build step**. You only need PHP,
Composer, and a MySQL database to run this.

## What's included (core MVP)

- Product catalog with categories, search, filters, sorting, pagination
- Session-based cart (works for guests, persists through checkout)
- Checkout with COD (primary, per market research) and manual bank transfer
- Customer accounts: register/login, profile, password change, order history, cancel order
- Product reviews & ratings, wishlist
- Admin panel (`/admin`, requires an admin-role user): dashboard with sales/stock stats,
  full product & category CRUD (with image upload), order management with status updates,
  customer list
- Stock is decremented on order and restored on cancellation

## What's intentionally out of scope for this build

The original strategic plan describes an 8-month, enterprise-grade roadmap. This build
covers Phase 1–2 (foundation + core features) as real, working code. Left as **stubs or
manual processes** for a later phase, since they need real merchant accounts/credentials
you'll need to obtain yourself:

- **JazzCash / EasyPaisa** online payment gateways — config placeholders exist in
  `config/services.php`; wire up their SDKs once you have merchant credentials.
- **TCS courier API integration** — orders currently ship with manual status updates from
  the admin panel; swap in TCS's tracking API when you have an account.
- Advanced BI/financial reporting (P&L, tax reports, ABC inventory analysis), loyalty
  points, WhatsApp/live-chat widgets, SMS notifications — the dashboard covers the
  essentials (daily orders, monthly revenue, low stock, top sellers) but not the full
  enterprise reporting suite.
- Email notifications are wired into Laravel's mail system conceptually (order events)
  but no notification classes are pre-built — add `Mail`/`Notification` classes as needed.

## Requirements

- PHP 8.1+
- Composer
- MySQL 5.7+ / MariaDB (or edit `.env` for PostgreSQL/SQLite)
- A web server (Nginx/Apache) for production, or `php artisan serve` for local testing

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials (`DB_DATABASE`, `DB_USERNAME`,
`DB_PASSWORD`), plus the `STORE_*` values (phone, WhatsApp, bank details for the manual
bank-transfer option) at the bottom of the file.

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Visit `http://localhost:8000`.

### Demo accounts (from the seeder)

| Role     | Email                 | Password  |
|----------|------------------------|-----------|
| Admin    | admin@store.pk         | password  |
| Customer | customer@example.com   | password  |

**Change these immediately** (or delete the seeded users) before going live.

## Deploying to production

1. On your server: `composer install --optimize-autoloader --no-dev`
2. Set `.env`: `APP_ENV=production`, `APP_DEBUG=false`, a real `APP_URL`, and production
   DB credentials.
3. `php artisan migrate --force` (and `--seed` only if you want the demo data — otherwise
   create your own admin user via `php artisan tinker` or a custom seeder).
4. `php artisan storage:link`
5. `php artisan config:cache && php artisan route:cache && php artisan view:cache`
6. Point your web server's document root at the `public/` folder (not the project root).
7. Serve over HTTPS — required for any future payment gateway integration and for
   customer trust generally.
8. Set up a real mail driver in `.env` (`MAIL_MAILER=smtp` with your provider) if you add
   email notifications.
9. Set up scheduled backups for the database and the `storage/app/public` uploads folder.

Most Pakistani shared-hosting providers (Hostinger, GoDaddy PK, etc.) support this
directly — just make sure PHP 8.1+ is selected and the document root points at `public/`.
For a VPS, a standard Nginx + PHP-FPM + MySQL stack works with the same steps above.

## Project structure notes

- **Cart** (`app/Services/Cart.php`) is session-based — no `cart_items` DB table, so guest
  carts work out of the box and there's nothing to clean up. It's cleared automatically
  after checkout.
- **Admin access** is gated by `role = 'admin'` on the `users` table
  (`app/Http/Middleware/EnsureUserIsAdmin.php`) rather than a separate login — one login
  system for both customers and staff, simpler to reason about for a small store.
- Product/category images are stored on the `public` disk
  (`storage/app/public`, symlinked to `public/storage`). For production at scale, switch
  `FILESYSTEM_DISK` to an S3-compatible disk and add the driver.

## Next steps worth prioritizing

1. Get a JazzCash or EasyPaisa merchant account and wire it into checkout — COD-only
   caps your addressable market given rising digital payment adoption.
2. Add order confirmation emails/SMS — right now customers only see status on the
   `/orders` page.
3. Add a proper test suite before this handles real transactions.
