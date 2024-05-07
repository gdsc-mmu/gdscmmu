#!/usr/bin/env bash

echo "Running composer install..."
composer install --no-dev --working-dir=/var/www/html

echo "Running migrations..."
php artisan migrate --force
php artisan storage:link
