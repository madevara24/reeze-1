#!/usr/bin/env bash

composer dump-autoload -o
php artisan clear-compiled
php artisan cache:clear
php artisan route:clear 
php artisan view:clear
php artisan cache:clear 
php artisan config:clear