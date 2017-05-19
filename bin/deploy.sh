#!/bin/bash

root_path="$PWD/.."

is_prod_mode() {
    if [ "$1" == "prod" ]; then
        return 0
    else
        return 1
    fi
}

printf_pwd() {
    printf "$PWD\n"
}

printf_step_header() {
    printf "\n>> $1\n"
}

step_update_sources() {
    printf_step_header "Updating Sources" &&
    cd "$root_path" &&
    printf_pwd &&
    git pull
}

step_update_backend_dependencies() {
    printf_step_header "Updating Backend Application Dependencies" &&
    cd "$root_path/backend" &&
    printf_pwd &&
    composer install && composer dump-autoload
}

step_migrate_database() {
    printf_step_header "Migrating Database" &&
    cd "$root_path/backend" &&
    printf_pwd &&
    php artisan migrate --force
}

step_run_backend_tests() {
    printf_step_header "Running Backend Tests" &&
    cd "$root_path/backend" &&
    printf_pwd &&
    ./vendor/bin/phpunit
}

step_publish_backend_application() {
    printf_step_header "Publishing Backend Application" &&
    cd "$root_path" &&
    printf_pwd &&
    mkdir dist >> /dev/null 2>&1 || rm -r dist/backend >> /dev/null 2>&1
    #
    rsync -avq --exclude="storage" backend/ dist/backend &&
    # Generate storage symlinks.
    ln -s "$root_path/backend/storage" dist/backend/storage &&
    ln -s "$root_path/backend/storage/app/public" dist/backend/public/storage
}

step_generate_rest_api_documentation() {
    printf_step_header "Generating REST API Documentation" &&
    cd "$root_path/backend" &&
    printf_pwd &&
    php artisan generate:rest_api_documentation
}

step_publish_rest_api_documentation() {
    printf_step_header "Publishing REST API Documentation" &&
    cd "$root_path" &&
    printf_pwd &&
    mkdir dist >> /dev/null 2>&1 || rm -r dist/rest_api_documentation >> /dev/null 2>&1
    #
    rsync -avq docs/rest_api/dist/ dist/rest_api_documentation
}

step_restart_backend_application() {
    printf_step_header "Restarting Backend Application" &&
    cd "$root_path" &&
    printf_pwd &&
    sudo systemctl restart nginx php7.0-fpm
}

step_update_frontend_application() {
    printf_step_header "Updating Frontend Application Dependencies" &&
    cd "$root_path/frontend" &&
    printf_pwd &&
    yarn install
}

step_build_frontend_application() {
    printf_step_header "Building Frontend Application" &&
    cd "$root_path/frontend" &&
    printf_pwd &&
    if is_prod_mode $1; then
        npm run build:prod
    else
        npm run build
    fi
}

step_publish_frontend_application() {
    printf_step_header "Publishing Frontend Application" &&
    cd "$root_path" &&
    printf_pwd &&
    mkdir dist >> /dev/null 2>&1 || rm -r dist/frontend >> /dev/null 2>&1
    #
    rsync -avq frontend/ dist/frontend
}

step_restart_frontend_application() {
    printf_step_header "Restarting Frontend Application" &&
    cd "$root_path/dist/frontend" &&
    printf_pwd &&
    pm2 restart dist/server.js
}

########################################################################################################################

step_update_sources &&
step_update_backend_dependencies &&
step_run_backend_tests &&
step_migrate_database &&
step_publish_backend_application &&
step_generate_rest_api_documentation &&
step_publish_rest_api_documentation &&
step_restart_backend_application &&
step_update_frontend_application &&
step_build_frontend_application $1 &&
step_publish_frontend_application &&
step_restart_frontend_application
