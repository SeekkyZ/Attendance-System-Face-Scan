#!/bin/bash

echo "🚀 Starting deployment process..."

# Clear all caches
echo "📦 Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run migrations
echo "🗃️ Running migrations..."
php artisan migrate --force

# Cache configurations for production
echo "⚡ Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔒 Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

echo "✅ Deployment completed successfully!"
