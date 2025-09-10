#!/bin/bash

# Wait for database
echo "🔄 Waiting for database connection..."
sleep 10

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run migrations
echo "🗃️ Running database migrations..."
php artisan migrate --force

# Start the server
echo "🚀 Starting Laravel server..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
