#!/usr/bin/env bash
# Exit on error
set -o errexit

# Install Composer dependencies
composer install --no-dev --no-interaction --optimize-autoloader

# Langsung jalankan migrasi
php artisan migrate --force