#!/usr/bin/env bash
# ==========================================================
#   GDGoC MMU Portal - Automated Setup & Start Script (Bash)
# ==========================================================
set -e

echo "=========================================================="
echo "   GDGoC MMU Portal - Automated Setup & Start Script      "
echo "=========================================================="
echo ""

# 1. Check prerequisites
echo "[1/7] Checking prerequisites..."
command -v php >/dev/null 2>&1 || { echo >&2 "Error: PHP is not installed."; exit 1; }
command -v composer >/dev/null 2>&1 || { echo >&2 "Error: Composer is not installed."; exit 1; }

# 2. Setup .env
echo "[2/7] Checking .env configuration..."
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo "  -> Created .env from .env.example"
    fi
fi

# 3. Install Composer dependencies
echo "[3/7] Installing PHP dependencies via Composer..."
composer install --no-interaction --prefer-dist

# 4. Generate app key if needed
echo "[4/7] Generating application key..."
php artisan key:generate --force

# 5. Install NPM & build assets
echo "[5/7] Installing frontend dependencies & building assets..."
if command -v npm >/dev/null 2>&1; then
    npm install
    npm run build
else
    echo "  -> NPM not found, skipping frontend build."
fi

# 6. Database & Storage setup
echo "[6/7] Preparing database and storage..."
php artisan storage:link --quiet || true
php artisan migrate --force || echo "  -> Note: Migrations skipped or database unreachable."

# 7. Clear caches
echo "[7/7] Clearing previous configuration caches..."
php artisan config:clear --quiet || true
php artisan route:clear --quiet || true
php artisan view:clear --quiet || true

echo ""
echo "=========================================================="
echo "  Setup Complete! Starting Laravel development server...  "
echo "  App URL: http://127.0.0.1:8000                          "
echo "  Press Ctrl+C to stop the server                         "
echo "=========================================================="
echo ""

php artisan serve --host=0.0.0.0 --port=8000
