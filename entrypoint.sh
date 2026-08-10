#!/bin/bash

# Give the MySQL container a few seconds to initialize
echo "Waiting for database to initialize..."
sleep 10

# Run CodeIgniter migrations automatically
echo "Running database migrations..."
php spark migrate

# Start Apache in the foreground
echo "Starting Apache web server..."
exec apache2-foreground
