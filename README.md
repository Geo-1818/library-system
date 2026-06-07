# Library Management System

A web-based Library Management System built with Laravel 12. The system allows administrators and users to manage books, borrowing records, returns, authentication, and other library-related operations.

---

# Live Deployment

**Production URL**

https://library-system-production-ec4f.up.railway.app/

---

# Features

* User Authentication
* Role-Based Access Control
* Book Management
* Borrowing and Returning System
* Dashboard Analytics
* Laravel Sanctum API Authentication
* Database Migration Support
* Responsive User Interface

---

# System Requirements

Before running the project, install the following:

* PHP 8.2 or higher
* Composer
* MySQL or SQLite
* Node.js (v18+ recommended)
* Git

---

# Local Installation

## 1. Clone the Repository

```bash
git clone <repository-url>
cd library-system
```

Or download and extract the ZIP file.

---

## 2. Install PHP Dependencies

```bash
composer install
```

---

## 3. Install Frontend Dependencies

```bash
npm install
```

---

## 4. Create Environment File

Windows:

```bash
copy .env.example .env
```

Linux/macOS:

```bash
cp .env.example .env
```

---

## 5. Configure Database

Open the `.env` file and configure your database.

### MySQL Example

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_system
DB_USERNAME=root
DB_PASSWORD=
```

### SQLite Example

```env
DB_CONNECTION=sqlite
```

Create the SQLite database file:

```bash
touch database/database.sqlite
```

---

## 6. Generate Application Key

```bash
php artisan key:generate
```

---

## 7. Run Database Migrations

```bash
php artisan migrate
```

If seeders exist:

```bash
php artisan migrate --seed
```

---

## 8. Build Frontend Assets

Development Mode:

```bash
npm run dev
```

Production Build:

```bash
npm run build
```

---

# Running the Application

Start Laravel's development server:

```bash
php artisan serve
```

Application URL:

```text
http://127.0.0.1:8000
```

---

# Development Workflow

Open two terminals.

### Terminal 1

```bash
php artisan serve
```

### Terminal 2

```bash
npm run dev
```

---

# API Authentication

This project uses Laravel Sanctum.

Generate an API token:

```php
$user->createToken('API Token')->plainTextToken;
```

Use the token in API requests:

```http
Authorization: Bearer YOUR_TOKEN
```

---

# Useful Laravel Commands

## Check Migration Status

```bash
php artisan migrate:status
```

## View Registered Routes

```bash
php artisan route:list
```

## Clear Application Cache

```bash
php artisan optimize:clear
```

## Create Storage Link

```bash
php artisan storage:link
```

## Run Tests

```bash
php artisan test
```

---

# Railway Deployment Guide

This project is deployed on Railway.

Production URL:

https://library-system-production-ec4f.up.railway.app/

---

## Step 1: Push Project to GitHub

Initialize Git:

```bash
git init
git add .
git commit -m "Initial commit"
```

Connect your repository:

```bash
git remote add origin https://github.com/yourusername/library-system.git
git branch -M main
git push -u origin main
```

---

## Step 2: Create Railway Account

1. Go to https://railway.app
2. Sign in using GitHub.
3. Authorize Railway access.

---

## Step 3: Create Railway Project

1. Click **New Project**
2. Select **Deploy from GitHub Repo**
3. Choose your repository
4. Railway will automatically detect Laravel and start deployment

---

## Step 4: Create MySQL Database

1. Click **New Service**
2. Select **MySQL**
3. Wait for Railway to create the database
4. Copy the generated credentials

---

## Step 5: Configure Environment Variables

Open:

```text
Project → Service → Variables
```

Add:

```env
APP_NAME="Library System"
APP_ENV=production
APP_DEBUG=false

APP_URL=https://library-system-production-ec4f.up.railway.app

LOG_CHANNEL=stderr

DB_CONNECTION=mysql
DB_HOST=YOUR_DATABASE_HOST
DB_PORT=3306
DB_DATABASE=YOUR_DATABASE_NAME
DB_USERNAME=YOUR_DATABASE_USERNAME
DB_PASSWORD=YOUR_DATABASE_PASSWORD

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

---

## Step 6: Generate APP_KEY

Locally run:

```bash
php artisan key:generate --show
```

Copy the generated key and add it to Railway:

```env
APP_KEY=base64:YOUR_GENERATED_KEY
```

---

## Step 7: Run Database Migrations

After deployment:

```bash
php artisan migrate --force
```

If seeders exist:

```bash
php artisan migrate --seed --force
```

---

## Step 8: Generate Public Domain

1. Open Railway Service
2. Go to **Settings**
3. Open **Networking**
4. Click **Generate Domain**

Your application will be accessible online.

---

# Updating the Railway Deployment

After making changes locally:

```bash
git add .
git commit -m "Update application"
git push origin main
```

Railway automatically redeploys every time new commits are pushed to GitHub.

---

# Viewing Railway Logs

Open:

```text
Project → Service → Deployments → View Logs
```

Or use Railway CLI:

```bash
railway logs
```

---

# Troubleshooting

## APP_KEY Missing

Generate a key:

```bash
php artisan key:generate --show
```

Add it to Railway Variables and redeploy.

---

## Database Connection Error

Verify:

```env
DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD
```

match the credentials provided by Railway.

---

## Migration Errors

```bash
php artisan migrate:fresh --seed
```

---

## Clear All Caches

```bash
php artisan optimize:clear
```

---

# Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   └── Middleware/

database/
├── migrations/
├── factories/
└── seeders/

resources/
├── views/
├── css/
└── js/

routes/
├── web.php
└── api.php
```

---

# Technologies Used

* Laravel 12
* PHP 8.2+
* Laravel Sanctum
* MySQL
* Blade Templates
* Vite
* Tailwind CSS
* JavaScript
* Railway

---

# Author
Ian Sangalang
Shaira Mae Rodolfo
Heart Laroya

Developed as a Library Management System using Laravel Framework.

Current Production Deployment:

https://library-system-production-ec4f.up.railway.app/

