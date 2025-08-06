#!/usr/bin/env bash
# Exit on error
set -o errexit

# Install dependencies
composer install --no-dev --no-interaction --optimize-autoloader

# Run database migrations
php artisan migrate --force

# Clear caches
php artisan optimize:clear