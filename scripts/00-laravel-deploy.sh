#!/usr/bin/env bash
echo "Running composer"
composer install --no-dev --working-dir=/var/www/html

echo "Storage link"
php artisan storage:link

echo "Clearing caches..."
php artisan optimize:clear

echo "Caching config..."
php artisan config:cache

echo "Running migrations..."
php artisan migrate:fresh --seed

echo "Caching routes..."
php artisan route:cache

echo "Deploy done"