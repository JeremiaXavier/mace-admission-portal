#!/bin/bash
set -e

echo "Waiting for MySQL to be ready..."
until php /var/www/html/wait_for_db.php; do
  echo "  MySQL not ready yet. Retrying in 3 seconds..."
  sleep 3
done

echo "MySQL is ready! Running database migrations..."
php spark migrate --no-interaction

echo "Starting Apache web server..."
exec apache2-foreground
