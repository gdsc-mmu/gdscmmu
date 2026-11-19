# 🚀 GDGoC MMU Web Portal

The official web portal for **Google Developer Groups on Campus (GDGoC) Multimedia University** (formerly GDSC MMU).

Built with **Laravel 10**, **PHP 8.2**, **PostgreSQL (Supabase)** / **SQLite**, and **Vite**.

---

## ⚡ Quick Start (Automated Setup)

We provide automated setup scripts that verify prerequisites, install all PHP and Node dependencies, prepare your `.env`, compile frontend assets, run database migrations, and launch the server in one go:

### On Windows (PowerShell):
```powershell
.\start.ps1
```
*(If PowerShell asks about execution policy, run: `powershell -ExecutionPolicy Bypass -File .\start.ps1`)*

### On Windows (Command Prompt / Double-Click):
```cmd
start.bat
```
*(or double-click `start.bat` in File Explorer)*

### On Linux / macOS / Git Bash:
```bash
chmod +x start.sh
./start.sh
```

Once initialized, open **`http://127.0.0.1:8000`** in your browser.

---

## 🛠 Manual Installation

If you prefer to configure and run each step manually:

### 1. Prerequisites
- **PHP** >= 8.1 (with `pdo_pgsql`, `pdo_sqlite`, `curl`, and `gd` extensions enabled)
- **Composer** (PHP Package Manager)
- **Node.js** & **NPM** (for Vite frontend compilation)
- **Git**

### 2. Clone Repository
```bash
git clone https://github.com/gdsc-mmu/gdscmmu.git
cd gdscmmu
```

### 3. Install Dependencies
```bash
# Install PHP vendor packages
composer install

# Install frontend JavaScript & CSS packages
npm install
```

### 4. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` to configure your database connection (see [Database Configuration](#-database-configuration) below).

### 5. Build Assets & Prepare Storage
```bash
# Compile frontend assets with Vite
npm run build

# Create public storage symlink
php artisan storage:link
```

### 6. Run Migrations
```bash
php artisan migrate --force
```

### 7. Start the Development Server
```bash
php artisan serve
```

The portal will be live at `http://127.0.0.1:8000`.

---

## 🗄 Database Configuration

### Option A: Cloud PostgreSQL (Supabase - Recommended)
Configure your `.env` to connect to Supabase:
```env
DB_CONNECTION=pgsql
DB_HOST=aws-0-ap-south-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.<your-project-ref>
DB_PASSWORD=<your-db-password>
DB_SSLMODE=require
```

### Option B: Local SQLite
For quick local development without an external database:
```env
DB_CONNECTION=sqlite
DB_DATABASE=C:\path\to\database\database.sqlite
```
*(Ensure the file `database/database.sqlite` exists)*

---

## 🌐 Public Pages & User Endpoints

| Endpoint | Method | Description |
| :--- | :--- | :--- |
| `/` | `GET` | **Home Page** — Welcome hero, club overview, and featured highlights |
| `/#membership` | `GET` / `POST` | **Membership Status Checker** — Verify active membership using Student ID or Student Email |
| `/events` | `GET` | **Events Directory** — Explore upcoming and past GDGoC workshops, hackathons, and tech talks |
| `/events/{id}` | `GET` | **Event Details** — Full event agenda, description, banner, and direct Bevy RSVP links |
| `/team` | `GET` | **Meet the Team** — Department showcase featuring executive committee and core leads |
| `/news` | `GET` | **Tech Newsletters** — Tech trends, articles, and community stories across AI, Software Engineering, and Cybersecurity |
| `/news/{id}` | `GET` | **Article Reader** — Full article reader view with rich text and media attachments |

---

## 🌟 Key Features

* **Membership Status Checker**:
  Students and members can instantly verify their official GDGoC MMU membership status and active cohort term directly from the home page.
* **Automated Bevy Event Synchronization**:
  - Real-time event catalog integrated directly with Google Developer Groups platform (`gdg.community.dev` Chapter 2886).
  - Chronological event timeline (latest to earliest) with high-definition Cloudinary banners and detailed agendas.
  - One-click **RSVP on Bevy** linking attendees to official event registrations.
* **Core Team & Department Showcase**:
  - Interactive departmental roster highlighting team leads and committee members.
  - Fast, responsive profile pictures powered by **Supabase Cloud Storage**.
* **Tech Newsletters & Community Stories**:
  - Curated articles exploring modern tech domains (Artificial Intelligence, Software Engineering, Cybersecurity).
  - Engaging reader view for students and tech enthusiasts.

---

## 📘 Developer Handover & Production Deployment

For incoming maintainers and developers taking over this repository, refer to the complete [Developer Handover & Production Deployment Playbook](DEPLOYMENT_HANDOVER.md) covering:
- Render Docker web service configuration and auto-deployment.
- Supabase PostgreSQL (Session Pooler) and Supabase Storage bucket (`team_avatars`) setup.
- Automated Bevy event synchronization and GitHub Actions cron maintenance.
- Production environment variable checklist and common troubleshooting SOPs.

---

## 📄 License

This project is open-source software licensed under the [MIT license](LICENSE).
