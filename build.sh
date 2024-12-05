#!/usr/bin/env sh
set -e

git fetch
git reset --hard "@{u}"
git clean -fd
composer install --classmap-authoritative --no-interaction --no-ansi --no-dev
composer clear-cache
bun install --frozen-lockfile
bun x update-browserslist-db@latest
bun run build
php artisan optimize:clear
php artisan event:cache
php artisan config:cache
php artisan route:clear
php artisan breadcrumbs:cache
php artisan route:cache
php artisan migrate --force
