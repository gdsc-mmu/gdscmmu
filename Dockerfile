FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

RUN apk update

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install npm and build assets
RUN apk add --no-cache npm
RUN npm install
RUN npm run build

# --- SQLite setup ---
RUN mkdir -p database
RUN touch database/database.sqlite
RUN chown -R www-data:www-data /var/www/html/database

# Run migrations
RUN php artisan migrate --force
# --------------------

CMD ["/start.sh"]