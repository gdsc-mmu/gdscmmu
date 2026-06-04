# Use base image for container
FROM richarvey/nginx-php-fpm:3.1.6

# Copy all application code into your Docker container
COPY . .

RUN apk update

# Install the `npm` package
RUN apk add --no-cache npm

# Install NPM dependencies
RUN npm install

# Build Vite assets
RUN npm run build

# --- FIX FOR SQLITE ---
# 1. Ensure the database directory exists
RUN mkdir -p database

# 2. Create an empty sqlite file if it doesn't exist
RUN touch database/database.sqlite

# 3. Give Nginx/PHP-FPM (www-data) ownership permissions to read/write the DB
RUN chown -R www-data:www-data /var/www/html/database
# ----------------------

CMD ["/start.sh"]

