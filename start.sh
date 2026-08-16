#!/bin/bash

# Clear cache and optimize
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations automatically
php artisan migrate --force

# Start Apache in foreground
apache2-foreground
