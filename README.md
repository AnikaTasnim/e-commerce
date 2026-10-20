# Usama Storefront

A Laravel 12 storefront frontend for a Bangladesh-based fashion shop. The current catalog and checkout are demonstration-only; product and order persistence and the admin panel are later milestones.

## Requirements

- PHP 8.2 or newer
- Composer

## Run locally

```powershell
composer install
php artisan key:generate
php artisan serve
```

Open the local URL printed by Artisan. For Apache/XAMPP, set the virtual host document root to this project's `public` directory.

The storefront route is handled by `StorefrontController`, with its page in `resources/views/storefront/index.blade.php`. Styles and browser interactions are in `public/css` and `public/js`. Demo catalog data currently lives in the controller and is passed to Blade; checkout intentionally does not submit or persist customer information.
