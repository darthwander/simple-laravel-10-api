#!/bin/bash

php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan event:clear
php artisan clear-compiled
php artisan optimize:clear

echo "All caches have been cleared!"
