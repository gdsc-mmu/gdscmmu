<#
.SYNOPSIS
    Setup and Start Script for GDGoC MMU Portal (Laravel)
.DESCRIPTION
    Installs PHP and Node dependencies, prepares .env, builds assets,
    runs migrations, and starts the Laravel local development server.
#>

$ErrorActionPreference = "Stop"

Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "   GDGoC MMU Portal - Automated Setup & Start Script      " -ForegroundColor Cyan
Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host ""

# 1. Check prerequisites
Write-Host "[1/8] Checking prerequisites..." -ForegroundColor Yellow
if (-not (Get-Command php -ErrorAction SilentlyContinue)) {
    Write-Host "Error: PHP is not installed or not in PATH." -ForegroundColor Red
    exit 1
}
if (-not (Get-Command composer -ErrorAction SilentlyContinue)) {
    Write-Host "Error: Composer is not installed or not in PATH." -ForegroundColor Red
    exit 1
}
if (-not (Get-Command npm -ErrorAction SilentlyContinue)) {
    Write-Host "Warning: NPM is not installed or not in PATH. Frontend assets might not build." -ForegroundColor Yellow
}
Write-Host "  -> PHP, Composer, and NPM found." -ForegroundColor Green

# 2. Setup .env file
Write-Host "[2/8] Checking environment configuration (.env)..." -ForegroundColor Yellow
if (-not (Test-Path ".env")) {
    if (Test-Path ".env.example") {
        Copy-Item ".env.example" ".env"
        Write-Host "  -> Created .env from .env.example." -ForegroundColor Green
    } else {
        Write-Host "  -> Warning: Neither .env nor .env.example found." -ForegroundColor Red
    }
} else {
    Write-Host "  -> .env already exists." -ForegroundColor Green
}

# 3. Install Composer dependencies
Write-Host "[3/8] Installing Composer dependencies..." -ForegroundColor Yellow
composer install --no-interaction --prefer-dist
if ($LASTEXITCODE -ne 0) {
    Write-Host "Error: composer install failed." -ForegroundColor Red
    exit $LASTEXITCODE
}
Write-Host "  -> Composer packages installed." -ForegroundColor Green

# 4. Generate Application Key if not set
Write-Host "[4/8] Checking application key..." -ForegroundColor Yellow
$envContent = Get-Content ".env" -Raw -ErrorAction SilentlyContinue
if ($envContent -notmatch "APP_KEY=base64:.+") {
    Write-Host "  -> Generating application key..." -ForegroundColor Cyan
    php artisan key:generate --force
} else {
    Write-Host "  -> APP_KEY is already set." -ForegroundColor Green
}

# 5. Install NPM dependencies & build frontend assets
Write-Host "[5/8] Installing NPM packages and building assets..." -ForegroundColor Yellow
if (Get-Command npm -ErrorAction SilentlyContinue) {
    npm install --silent
    npm run build
    Write-Host "  -> Frontend assets compiled with Vite." -ForegroundColor Green
} else {
    Write-Host "  -> Skipped NPM build (NPM not found)." -ForegroundColor Gray
}

# 6. Ensure Storage link and SQLite file if applicable
Write-Host "[6/8] Preparing storage & database..." -ForegroundColor Yellow
# If SQLite is configured in .env, ensure database file exists
if ($envContent -match "DB_CONNECTION=sqlite") {
    if (-not (Test-Path "database")) {
        New-Item -ItemType Directory -Path "database" -Force | Out-Null
    }
    if (-not (Test-Path "database/database.sqlite")) {
        New-Item -ItemType File -Path "database/database.sqlite" -Force | Out-Null
        Write-Host "  -> Created database/database.sqlite." -ForegroundColor Green
    }
}

php artisan storage:link --quiet 2>$null
Write-Host "  -> Storage symlink verified." -ForegroundColor Green

# 7. Run database migrations
Write-Host "[7/8] Running database migrations..." -ForegroundColor Yellow
try {
    php artisan migrate --force
    Write-Host "  -> Migrations up to date." -ForegroundColor Green
} catch {
    Write-Host "  -> Note: Migrations skipped or encountered a connection issue (check DB credentials in .env)." -ForegroundColor Yellow
}

# Clear any cached configuration for clean local execution
php artisan config:clear --quiet
php artisan route:clear --quiet
php artisan view:clear --quiet

# 8. Start development server
Write-Host ""
Write-Host "==========================================================" -ForegroundColor Green
Write-Host "  Setup completed successfully! Starting Laravel server... " -ForegroundColor Green
Write-Host "  App URL: http://127.0.0.1:8000                          " -ForegroundColor Cyan
Write-Host "  Press Ctrl+C to stop the server                         " -ForegroundColor Gray
Write-Host "==========================================================" -ForegroundColor Green
Write-Host ""

php artisan serve --host=127.0.0.1 --port=8000
