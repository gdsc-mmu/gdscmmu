#!/usr/bin/env bash
# Exit on error
set -o errexit

# Install dependencies
composer install --no-dev --optimize-autoloader

# Create the SQLite file if it doesn't exist
mkdir -p database
touch database/database.sqlite

# Run migrations and cache configs for speed
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Compile frontend assets (if using Vite/Mix)
npm install
npm run build