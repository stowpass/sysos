#!/bin/sh

# Install dependencies
composer --working-dir=/application install

# Fix permissions to Docker
chown -R 1000:1000 /application/vendor
chown -R www-data:www-data /application/public/cvs

# Run FastCGI
echo "Running FastCGI..."

php-fpm
