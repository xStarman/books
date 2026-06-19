#!/usr/bin/env bash
set -e

echo "Running database migrations..."
php artisan migrate --force

echo "Starting server..."
exec "$@"
