#!/bin/bash

set -e

if [ "$REBUILD_ON_STARTUP" == "1" ]; then
    composer install --no-interaction
    composer dump-autoload
    php artisan key:generate
    php artisan migrate --force
    php artisan create:roles
    php artisan cache:clear
    php artisan generate:rest_api_documentation
fi

exec "$@"
