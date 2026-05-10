#!/bin/bash

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Start Apache in foreground
echo "Starting Apache..."
exec "$@"
