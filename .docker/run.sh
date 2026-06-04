#!/bin/bash

# Clear any configurations cached during the Docker build stage
php artisan config:clear
php artisan route:clear

# Force run migrations in production mode
echo "Executing database migrations..."
php artisan migrate --force