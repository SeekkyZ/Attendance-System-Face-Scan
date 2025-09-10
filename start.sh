#!/bin/bash
set -e

echo "🚀 Starting Laravel application deployment..."

# Wait for database connection
echo "🔄 Waiting for database connection..."
max_attempts=30
attempt=1

while [ $attempt -le $max_attempts ]; do
    if php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connected!';" 2>/dev/null; then
        echo "✅ Database connection successful!"
        break
    else
        echo "⏳ Waiting for database... (attempt $attempt/$max_attempts)"
        sleep 2
        ((attempt++))
    fi
done

if [ $attempt -gt $max_attempts ]; then
    echo "❌ Database connection failed after $max_attempts attempts"
    exit 1
fi

# Clear caches
echo "🧹 Clearing caches..."
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan cache:clear || true

# Run migrations
echo "🗃️ Running database migrations..."
php artisan migrate --force

# Cache configurations for production
echo "⚡ Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if not exists
echo "🔗 Creating storage link..."
php artisan storage:link || true

# Start the server
echo "🚀 Starting Laravel server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
