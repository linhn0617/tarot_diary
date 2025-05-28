# Tarot Diary Backend Project

A Laravel 11-based backend API service designed for tarot card drawing and diary logging. This project features user authentication, Google OAuth integration, JWT-based API protection, email verification, and Docker-based development.

## Features

- 📧 Email verification during registration
- 🔐 JWT authentication with token refresh
- 🃏 Random tarot card drawing with diary support
- 🌗 Tarot upright/reversed message generation
- 👤 User profile management and updates
- 🔗 Google OAuth social login
- 🧾 RESTful API architecture
- ⚙️ Laravel Sail + Docker environment
- 🚀 CI/CD with GitHub Actions + EC2 auto-deploy

## Installation

### Prerequisite

1. Make sure to install [Docker Desktop](https://docs.docker.com/desktop/) or [OrbStack](https://orbstack.dev/) first
2. Ensure that port 80 is not already in use on your local machine

### Installation and Startup Steps

1. Clone the repository
2. Copy the environment configuration file
```
cp .env.example .env
```
3. Edit the .env file to configure MySQL username and password
```
DB_DATABASE=happy_partner_blog
DB_USERNAME=laravel
# password is required
DB_PASSWORD=password
```
4. If you are a Linux user, run the following command first (Mac users can skip this step).
```
# Create the docker group (most distributions create it automatically during installation)
sudo groupadd docker 2>/dev/null || true

# Add < your user > to the docker group
sudo usermod -aG docker <your User>
```
5. Install dependencies
```
composer install
```
If Composer is not installed on your machine, please run the following command
```
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```
6. Launch environment
```
./vendor/bin/sail up
```
7. Generate the application key
```
./vendor/bin/sail artisan key:generate
```
8. Run migration and seeders:
```
sail artisan migrate --seed
```
9. File Storage
```
./vendor/bin/sail artisan storage:link
```
