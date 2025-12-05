#!/bin/sh
set -e

# Create log directories
mkdir -p /var/log/nginx
mkdir -p /var/log/supervisor

# Cache Laravel configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations if DATABASE_URL is set
if [ -n "$DATABASE_URL" ] || [ -n "$DB_CONNECTION" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Generate coding languages data
php artisan languages:generate || true

# Start Supervisor (which manages nginx, php-fpm, and queue workers)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
