#!/usr/bin/env bash
# Exit on error
set -o errexit

# Install Composer dependencies
composer install --no-dev --no-interaction --optimize-autoloader

# Run database migrations FIRST
# Migrations will read the environment variables directly
php artisan migrate --force

# Clear and cache the configuration LAST
# This caches the correct production database config for the running app
php artisan optimize:clear
php artisan config:cache