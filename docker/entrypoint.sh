#!/bin/sh
set -e

echo "Starting Pantry Manager initialization..."

cd /var/www/html

# Create .env file if it doesn't exist
if [ ! -f .env ]; then
    echo "Creating .env file from .env.example..."
    cp .env.example .env

    # Generate application key
    php artisan key:generate --no-interaction
fi

# Ensure database directory exists
mkdir -p database

# Create SQLite database if it doesn't exist
if [ ! -f database/database.sqlite ]; then
    echo "Creating SQLite database..."
    touch database/database.sqlite
fi

# Ensure storage directories exist
echo "Setting up storage directories..."
mkdir -p storage/framework/sessions \
         storage/framework/views \
         storage/framework/cache/data \
         storage/logs \
         storage/app/public \
         bootstrap/cache

# Set permissions (running as root before supervisord starts)
chown -R www-data:www-data storage bootstrap/cache database .env
chmod -R 775 storage bootstrap/cache database
chmod 664 .env

# Run database migrations
echo "Running database migrations..."
su-exec www-data php artisan migrate --force --no-interaction

# Create storage link if it doesn't exist
if [ ! -L public/storage ]; then
    echo "Creating storage symlink..."
    su-exec www-data php artisan storage:link --no-interaction
fi

# Clear and cache configuration
echo "Optimizing application..."
su-exec www-data php artisan config:cache --no-interaction
su-exec www-data php artisan route:cache --no-interaction
su-exec www-data php artisan view:cache --no-interaction

echo "Initialization complete! Starting services..."

# Execute the main command (supervisord will run as root, but services run as www-data)
exec "$@"
