#!/bin/bash

set -e

chown -R www-data:www-data ./storage/
chown -R www-data:www-data ./bootstrap/cache/

composer install --no-interaction
composer dump-autoload

npm install

php artisan key:generate
php artisan migrate --force
php artisan create:roles
php artisan cache:clear
php artisan generate:rest_api_documentation

exec "$@"
