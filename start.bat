@echo off
title GDGoC MMU Portal - Starter
echo ==========================================================
echo    GDGoC MMU Portal - Automated Setup ^& Start Script
echo ==========================================================
echo.

where php >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] PHP is not installed or not in PATH.
    pause
    exit /b 1
)

where composer >nul 2>nul
if %errorlevel% neq 0 (
    echo [ERROR] Composer is not installed or not in PATH.
    pause
    exit /b 1
)

echo [1/7] Checking .env configuration...
if not exist ".env" (
    if exist ".env.example" (
        copy .env.example .env >nul
        echo   -^> Created .env from .env.example
    )
) else (
    echo   -^> .env exists.
)

echo [2/7] Installing PHP dependencies via Composer...
call composer install --no-interaction --prefer-dist
if %errorlevel% neq 0 (
    echo [ERROR] Composer install failed.
    pause
    exit /b %errorlevel%
)

echo [3/7] Generating application key...
call php artisan key:generate --force

echo [4/7] Installing NPM packages ^& building assets...
where npm >nul 2>nul
if %errorlevel% equ 0 (
    call npm install
    call npm run build
) else (
    echo   -^> NPM not found, skipping asset compilation.
)

echo [5/7] Verifying storage link...
call php artisan storage:link >nul 2>nul

echo [6/7] Running database migrations...
call php artisan migrate --force

echo [7/7] Clearing previous configuration caches...
call php artisan config:clear >nul 2>nul
call php artisan route:clear >nul 2>nul
call php artisan view:clear >nul 2>nul

echo.
echo ==========================================================
echo   Setup Complete! Starting Laravel development server...
echo   App URL: http://127.0.0.1:8000
echo   Press Ctrl+C to stop the server
echo ==========================================================
echo.

php artisan serve --host=127.0.0.1 --port=8000
