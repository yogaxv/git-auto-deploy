#!/bin/sh
# activate maintenance mode
php artisan down

# update source code
git pull

# update PHP dependencies
composer install --no-interaction --no-dev --prefer-dist

# --no-interaction Do not ask any interactive question
# --no-dev  Disables installation of require-dev packages.
# --prefer-dist  Forces installation from package dist even for dev versions.
# update database
php artisan migrate --force

#clear config and cache config
php artisan config:cache

#clear all application cache
php artisan cache:clear

#clear all route cache
php artisan route:clear

# --force  Required to run when in production.
# stop maintenance mode
php artisan up