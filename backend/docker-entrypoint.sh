#!/bin/bash

set -e

if [ "$DOCKER_REBUILD" == "1" ]; then
    composer install --no-interaction
    composer dump-autoload
    php artisan storage:link
    php artisan migrate --force
    php artisan create:roles
    php artisan cache:clear
    php artisan generate:rest_api_documentation
fi

exec "$@"
