# Tarot Diary Backend API

This is a Laravel 11-based backend API project for a Tarot Diary app, enabling users to register, authenticate, draw tarot cards, and write daily entries.

## 🔧 Tech Stack

- Laravel 11
- PHP 8.3
- MySQL
- JWT Authentication
- Laravel Sanctum
- Docker + Laravel Sail
- RESTful API Architecture

## 🗂 Project Structure

- `app/Services`: Core business logic (e.g., AuthService, TarotDiaryService)
- `app/Http/Controllers`: Handle endpoints for login, register, diary actions
- `database/migrations`: Schema and relational structures
- `database/seeders`: Tarot data imported from CSV

## ✨ Key Features

- ✅ User register / login / logout / token refresh
- ✅ Email verification
- ✅ Google OAuth login supported
- ✅ Add / update / list tarot diary entries
- ✅ Draw tarot cards and view card meanings

## ⚙️ Getting Started

```bash
# Install dependencies
composer install
npm install

# Copy environment settings
cp .env.example .env

# Generate app key
php artisan key:generate

# Start containers via Sail
./vendor/bin/sail up -d

# Run database migrations and seeders
php artisan migrate --seed
```

## 🔮 Tarot Draw & Diary Flow

1. Authenticate and obtain JWT token
2. Call `/api/tarot/draw` to draw a card
3. Submit diary entry with `/api/diary/store`
4. View past entries via `/api/diary/list`
