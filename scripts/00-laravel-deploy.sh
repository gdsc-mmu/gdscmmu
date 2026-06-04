#!/usr/bin/env bash

echo "Running composer install..."
composer install --no-dev --working-dir=/var/www/html

echo "Clearing cached config..."
php artisan optimize:clear

echo "Ensuring SQLite database file exists..."
mkdir -p /var/www/html/database
touch /var/www/html/database/database.sqlite

echo "Running migrations..."
php artisan migrate --force

php artisan storage:link
