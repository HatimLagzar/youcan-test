# Setup

```
composer install

cp .env.example .env
php artisan key:generate
php artisan storage:link

// Make sure you have the correct database options in .env before running this command
php artisan migrate

npm install
```

# Init Session

```
php artisan serv
npx mix watch
```

# CLI

## Product

```
php artisan product:create
php artisan product:delete
```

## Category

```
php artisan category:create
php artisan category:delete
```
