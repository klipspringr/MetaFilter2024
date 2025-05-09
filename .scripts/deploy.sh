#!/bin/bash
set -e

echo "Starting deployment..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down --render="errors::maintenance") || true

# Pull the latest version of the app
git pull origin main

# Install composer dependencies
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Install npm dependencies
npm install

# Clear the old cache
php artisan clear-compiled

# Recreate cache
php artisan optimize

# Recreate Filament cache
php artisan filament:optimize

# Compile npm assets
npm run build

# Run database migrations
php artisan migrate --force

# Exit maintenance mode
php artisan up

echo "Deployment complete"
