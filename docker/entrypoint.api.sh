#!/usr/bin/env bash
set -e

echo "Running database migrations..."
php artisan migrate --force

echo "Generating Swagger documentation..."
php artisan l5-swagger:generate || true

echo "Starting server..."
exec "$@"
