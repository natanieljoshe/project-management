#!/usr/bin/env bash
set -o errexit

composer install --no-dev --no-interaction --optimize-autoloader

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan migrate --force