#!/usr/bin/env bash
set -o errexit

# Hapus paksa cache yang mungkin ada
rm -f bootstrap/cache/config.php

# Buat cache baru dengan variabel dari Railway
php artisan config:cache

# Jalankan migrasi dengan config yang sudah benar
php artisan migrate --force