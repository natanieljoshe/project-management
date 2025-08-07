#!/usr/bin/env bash
# Exit on error
set -o errexit

# HANYA install dependencies
composer install --no-dev --no-interaction --optimize-autoloader