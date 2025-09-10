#!/bin/bash

# Wait for database to be ready
echo "🔄 Waiting for database connection..."
for i in {1..30}; do
    if php artisan migrate:status --no-interaction 2>/dev/null; then
        echo "✅ Database connection successful!"
        break
    fi
    echo "⏳ Attempt $i/30 - Database not ready, waiting 2 seconds..."
    sleep 2
done

# Check if we can connect to database
if ! php artisan migrate:status --no-interaction 2>/dev/null; then
    echo "❌ Failed to connect to database after 60 seconds"
    echo "Database variables:"
    echo "DB_HOST: ${MYSQLHOST}"
    echo "DB_PORT: ${MYSQLPORT}"
    echo "DB_DATABASE: ${MYSQLDATABASE}"
    echo "DB_USERNAME: ${MYSQLUSER}"
    exit 1
fi

# Clear Laravel caches
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Run migrations
echo "🗃️ Running database migrations..."
php artisan migrate --force

# Create storage link if not exists
echo "🔗 Creating storage link..."
php artisan storage:link || echo "Storage link already exists"

# Seed database (optional)
if [ "$SEED_DATABASE" = "true" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force
fi

# Start the Laravel server
echo "🚀 Starting Laravel server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
