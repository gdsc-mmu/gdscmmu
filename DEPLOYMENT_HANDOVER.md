# 📘 Developer Handover & Production Deployment Playbook

This document is the official technical handover guide for incoming developers and the software engineering team maintaining the **GDGoC MMU Web Portal**.

---

## 🏛 System Architecture Overview

```
                      +------------------------------------------+
                      |         GitHub Repository                |
                      |   (Branch: production / development)     |
                      +--------------------+---------------------+
                                           |
                                  Automatic Webhook
                                           |
                                           v
+-----------------------+     +--------------------------+     +-------------------------+
| GitHub Actions Cron   |     |    Render Cloud Host     |     |   Supabase Cloud        |
|  - Daily Bevy Sync    +---->+  - Docker (Nginx+PHP8.2) +<--->+  - PostgreSQL (Pooler)  |
|  - 00:00 UTC (8am MYT)|     |  - Laravel 10 App        |     |  - Storage: team_avatars|
+-----------------------+     +------------+-------------+     +-------------------------+
                                           |
                                           v
                              +--------------------------+
                              |   Public Web & Admin     |
                              |  - Public Portal Pages   |
                              |  - Member Status Checker |
                              |  - Bevy Event Catalog    |
                              |  - Admin Command Center  |
                              +--------------------------+
```

### 1. Technology Stack
* **Backend Framework**: Laravel 10 (PHP 8.2)
* **Web Server**: Nginx with PHP-FPM (`richarvey/nginx-php-fpm:3.1.6` Docker runtime)
* **Frontend Assets**: Vite, Bootstrap 5, FontAwesome 4.7, Alpine.js, AOS animations
* **Database**: Supabase Cloud PostgreSQL (Session Pooler: `aws-0-ap-south-1.pooler.supabase.com:5432`)
* **File Storage**: Supabase Storage Bucket (`team_avatars`) for team member profile photos
* **External Integration**: Bevy API (Google Developer Groups community platform, Chapter 2886)
* **Automation**: GitHub Actions serverless cron (`.github/workflows/daily-bevy-sync.yml`)

---

## 🚀 Production Deployment on Render

The live production application runs as a Docker Web Service on **Render**.

* **Live Production URL**: `https://gdscmmu-prod.onrender.com`
* **Connected Repository**: `https://github.com/ronylee11/gdscmmu`
* **Production Deployment Branch**: `production`

### Step 1: Create or Connect Web Service on Render
1. Log in to [Render Dashboard](https://dashboard.render.com).
2. Click **New +** → **Web Service**.
3. Connect your GitHub repository (`gdscmmu`).
4. Set the following service parameters:
   - **Name**: `gdscmmu-prod` (or your choice)
   - **Region**: Singapore (`Southeast Asia (Singapore)`) or closest to Malaysia
   - **Branch**: `production`
   - **Runtime**: `Docker`
   - **Dockerfile Path**: `./Dockerfile`
   - **Instance Type**: Free or Starter

### Step 2: Configure Environment Variables in Render
Under the **Environment** tab of your Render Web Service, add the following environment variables:

| Variable | Recommended Production Value | Description |
| :--- | :--- | :--- |
| `APP_NAME` | `GDGoC MMU` | Application name displayed in emails and headers |
| `APP_ENV` | `production` | Laravel production environment mode |
| `APP_KEY` | `base64:...` | Generate using `php artisan key:generate --show` |
| `APP_DEBUG` | `false` | Disable debug backtraces for security |
| `APP_URL` | `https://gdscmmu-prod.onrender.com` | Base URL of your deployed application |
| `LOG_CHANNEL` | `stderr` | Pipe Laravel logs directly to Render's live log viewer |
| `WEBROOT` | `/var/www/html/public` | Points Nginx to Laravel's public directory |
| `DB_CONNECTION` | `pgsql` | PostgreSQL database connection |
| `DB_HOST` | `aws-0-ap-south-1.pooler.supabase.com` | **Crucial**: Use Supabase Session Pooler host (IPv4) |
| `DB_PORT` | `5432` | PostgreSQL port |
| `DB_DATABASE` | `postgres` | Default Supabase database name |
| `DB_USERNAME` | `postgres.<your-project-ref>` | Supabase database pooler username |
| `DB_PASSWORD` | `<your-supabase-db-password>` | Supabase database password |
| `DB_SSLMODE` | `require` | Mandate SSL encryption for database connection |
| `SUPABASE_URL` | `https://<your-project-ref>.supabase.co` | Supabase project REST endpoint |
| `SUPABASE_KEY` | `<your-supabase-anon-or-service-key>` | API key for Supabase Storage uploads |
| `SUPABASE_BUCKET`| `team_avatars` | Bucket name for team member photo uploads |
| `CRON_SECRET` | `mmu_gdg_cron_secret_2026` | Authorization token for GitHub Actions cron webhook |

> [!IMPORTANT]
> **Render IPv6 vs IPv4 Warning**: Direct Supabase database hosts (`db.<ref>.supabase.co`) resolve exclusively to IPv6. Render containers operate on IPv4 and will fail with `Network unreachable` if connected directly. **Always use the Supabase Pooler host** (`aws-0-ap-south-1.pooler.supabase.com`).

### Step 3: Automated Deploy & Health Check Workflow
We provide an automated GitHub Actions deployment workflow at [`.github/workflows/deploy-render.yml`](.github/workflows/deploy-render.yml):
1. In Render Dashboard: **Settings → Deploy Hook → Copy Deploy Hook URL**.
2. In GitHub Repository: **Settings → Secrets and variables → Actions → New repository secret**:
   - **Name**: `RENDER_DEPLOY_HOOK_URL`
   - **Value**: *(Paste Render Deploy Hook URL)*
3. Whenever code is merged or pushed to `production`, GitHub Actions triggers Render, polls container build health, and fails with alerts if the build/migration breaks.

---

## 🗄 Supabase Setup & Configuration

### 1. Database Connection
* Ensure your Supabase project is deployed in region `ap-south-1` (or region closest to Render).
* Find your pooler connection parameters under **Supabase Dashboard → Project Settings → Database → Connection Pooling**.
* Use mode **Session** on port `5432`.

### 2. Storage Bucket (`team_avatars`)
Team member photos uploaded via the admin portal (`/admin/team`) are stored in Supabase Storage.
1. In Supabase Dashboard, go to **Storage** → Click **New Bucket**.
2. **Bucket name**: `team_avatars`
3. Set bucket to **Public**.
4. Set **Row Level Security (RLS)** policy:
   - Click **Policies** under `team_avatars`.
   - Add policy with expression:
     ```sql
     (bucket_id = 'team_avatars'::text)
     ```
   - Enable **SELECT** (for public photo viewing) and **INSERT/UPDATE** (for uploads).

---

## ⚡ Automated Event Synchronization (Bevy + GitHub Actions)

The portal automatically synchronizes upcoming and past events from the official Google Developer Groups platform (`gdg.community.dev` Chapter 2886).

### 1. How It Works
- The command `php artisan bevy:sync-events` calls Google's public Bevy API, downloads high-res Cloudinary banners, and inserts/updates events in PostgreSQL without duplicating records (`bevy_id` deduplication).
- A secured webhook endpoint is available at:
  ```
  POST https://gdscmmu-prod.onrender.com/api/cron/sync-bevy?key=<CRON_SECRET>
  ```
- Also accessible via fallback route `GET|POST /cron/sync-bevy`.

### 2. GitHub Actions Scheduled Workflow
- The workflow file is located at [`.github/workflows/daily-bevy-sync.yml`](.github/workflows/daily-bevy-sync.yml).
- Automatically triggers daily at **00:00 UTC** (**8:00 AM MYT**).
- Can be manually dispatched anytime from GitHub: **Actions → Daily Bevy Events Sync → Run workflow**.
- To configure or update secrets: Go to GitHub repository **Settings → Secrets and variables → Actions**:
  - `APP_URL`: `https://gdscmmu-prod.onrender.com`
  - `CRON_SECRET`: Matching token from your `.env`

---

## 💻 Local Development Setup

For any developer working on this codebase locally:

### Option A: Automated 1-Click Launchers
* **Windows (PowerShell)**:
  ```powershell
  .\start.ps1
  ```
* **Windows (Command Prompt / Double Click)**:
  ```cmd
  start.bat
  ```
* **macOS / Linux**:
  ```bash
  chmod +x start.sh
  ./start.sh
  ```
These scripts automatically check PHP/Composer/Node prerequisites, provision `.env`, run `composer install`, `npm install`, compile Vite assets, execute database migrations, and launch `php artisan serve`.

### Option B: Local SQLite Development
If developing without internet or cloud database access:
1. In `.env`:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```
2. Create sqlite database and migrate:
   ```bash
   touch database/database.sqlite
   php artisan migrate --force
   ```

---

## 🛠 Common Maintenance SOPs

### 1. Adding / Updating Database Columns in Production
* Render runs `php artisan migrate --force` automatically during container startup.
* When altering existing column data types (such as `VARCHAR` to `TEXT`), **do not use Doctrine DBAL methods** (like `$table->text('column')->change()`), as Doctrine DBAL is not installed by default in minimal production images.
* **Always use native PostgreSQL raw SQL**:
  ```php
  DB::statement('ALTER TABLE tablename ALTER COLUMN columnname TYPE TEXT;');
  ```

### 2. Re-syncing Bevy Events Manually
If a new event was just published on Bevy and needs to appear immediately:
1. Go to `/admin/events` in the admin portal.
2. Click the **"Sync with Bevy"** button.
3. Alternatively, trigger the GitHub Actions workflow or run `php artisan bevy:sync-events` locally.

### 3. Member Management & Cohort Rollover
* Under `/admin/members`, use **Manage Terms** to archive old terms (e.g. `2023/2024`) when a new academic year begins. Archived members remain in historical records but are hidden from the active public search.
* Use **Import Excel** to batch upload new member sheets. The system automatically detects arbitrary headers and prevents duplicate records.

---

## 📞 Support & Handover Contacts

* **Organization**: Google Developer Groups on Campus (GDGoC) Multimedia University
* **Platform Lead**: Raziq Din (`ajiqdin646@gmail.com`)
* **Repository**: `https://github.com/ronylee11/gdscmmu`
* **License**: MIT License
