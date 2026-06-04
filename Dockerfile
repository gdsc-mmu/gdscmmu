FROM richarvey/nginx-php-fpm:3.1.6

COPY . .

RUN apk update

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install npm and build assets
RUN apk add --no-cache npm
RUN npm install
RUN npm run build

# Create minimal .env for build time
RUN echo "APP_NAME=Laravel" > .env && \
    echo "APP_ENV=production" >> .env && \
    echo "APP_KEY=" >> .env && \
    echo "APP_DEBUG=false" >> .env && \
    echo "APP_URL=http://localhost" >> .env && \
    echo "APP_DEBUG=true" >> .env && \
    echo "DB_CONNECTION=sqlite" >> .env && \
    echo "DB_DATABASE=/var/www/html/database/database.sqlite" >> .env

# Generate app key
RUN php artisan key:generate --force

# --- SQLite setup ---
RUN mkdir -p database
RUN touch database/database.sqlite
RUN chown -R www-data:www-data /var/www/html/database

# Run migrations
RUN php artisan migrate --seed --force


CMD ["/start.sh"]