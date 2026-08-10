#!/bin/bash
set -e

echo "Waiting for MySQL to be ready..."
until mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASS" "$DB_NAME" -e "SELECT 1" > /dev/null 2>&1; do
  echo "  MySQL not ready yet. Retrying in 3 seconds..."
  sleep 3
done

echo "MySQL is ready! Running database migrations..."
php spark migrate --no-interaction

echo "Starting Apache web server..."
exec apache2-foreground
