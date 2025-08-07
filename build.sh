#!/usr/bin/env bash
# Exit on error
set -o errexit

# Install Composer dependencies
composer install --no-dev --no-interaction --optimize-autoloader

# Create a fresh cache of the configuration
php artisan config:cache

# Run database migrations
# Migrations will now use the freshly cached config
php artisan migrate --force