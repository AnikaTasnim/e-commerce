# Usama Storefront

A Laravel 12, Blade-based storefront for a Bangladesh-based fashion shop. Product pages include the full billing form and order summary; COD orders, product variants, stock, and shipping options are stored in the database.

## Requirements

- PHP 8.2 or newer
- Composer

## Run locally

```powershell
composer install
php artisan key:generate
if (-not (Test-Path database/database.sqlite)) { New-Item database/database.sqlite -ItemType File | Out-Null }
php artisan migrate --seed
php artisan serve
```

Open the local URL printed by Artisan. For Apache/XAMPP, set the virtual host document root to this project's `public` directory.

The product catalog lives in `products`, `product_images`, and `product_variants`. A product page and its order form share one URL; submitting the form validates stock and prices, stores the order and item snapshots, and returns to that page with an order confirmation.

Shipping rates are editable at `/admin/shipping-options`. To provision the first administrator, set `ADMIN_NAME`, `ADMIN_EMAIL`, and a strong `ADMIN_PASSWORD` in `.env`, then run `php artisan db:seed`. These credentials are only used to create a hashed admin account. The current seed catalog is sample data; replace it with the store's product information before launch.

You can use MySQL instead of the default SQLite database by setting `DB_CONNECTION=mysql` and the MySQL connection values in `.env` before migrating.
