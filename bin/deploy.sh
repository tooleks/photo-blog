#!/bin/bash

root_path="$PWD/.."

is_production_mode() {
    if [ "$1" == "production" ]; then
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

step_run_backend_tests() {
    printf_step_header "Running Backend Tests" &&
    cd "$root_path/backend" &&
    printf_pwd &&
    ./vendor/bin/phpunit
}

step_generate_rest_api_documentation() {
    printf_step_header "Generating REST API Documentation" &&
    cd "$root_path/backend" &&
    printf_pwd &&
    php artisan generate:rest_api_documentation
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
    if is_production_mode $1; then
        npm run build:production
    else
        npm run build
    fi
}

step_stop_backend_application() {
    printf_step_header "Stopping Backend Application" &&
    cd "$root_path" &&
    printf_pwd &&
    sudo systemctl stop php7.0-fpm
}

step_migrate_database() {
    printf_step_header "Migrating Database" &&
    cd "$root_path/backend" &&
    printf_pwd &&
    php artisan migrate --force
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

step_publish_rest_api_documentation() {
    printf_step_header "Publishing REST API Documentation" &&
    cd "$root_path" &&
    printf_pwd &&
    mkdir dist >> /dev/null 2>&1 || rm -r dist/rest_api_documentation >> /dev/null 2>&1
    #
    rsync -avq docs/rest_api/dist/ dist/rest_api_documentation
}

step_flush_cache() {
    printf_step_header "Flush Cache" &&
    cd "$root_path" &&
    redis-cli flushAll
}

step_start_backend_application() {
    printf_step_header "Starting Backend Application" &&
    cd "$root_path" &&
    printf_pwd &&
    sudo systemctl start php7.0-fpm
}

step_stop_frontend_application() {
    printf_step_header "Stopping Frontend Application" &&
    cd "$root_path/dist/frontend" &&
    printf_pwd &&
    pm2 stop dist/server.js
}

step_publish_frontend_application() {
    printf_step_header "Publishing Frontend Application" &&
    cd "$root_path" &&
    printf_pwd &&
    mkdir dist >> /dev/null 2>&1 || rm -r dist/frontend >> /dev/null 2>&1
    #
    rsync -avq frontend/ dist/frontend
}

step_start_frontend_application() {
    printf_step_header "Starting Frontend Application" &&
    cd "$root_path/dist/frontend" &&
    printf_pwd &&
    pm2 start dist/server.js
}

########################################################################################################################

step_update_sources &&
step_update_backend_dependencies &&
step_run_backend_tests &&
step_generate_rest_api_documentation &&
step_update_frontend_application &&
step_build_frontend_application $1 &&
step_stop_backend_application &&
step_migrate_database &&
step_publish_backend_application &&
step_publish_rest_api_documentation &&
step_flush_cache &&
step_start_backend_application &&
step_stop_frontend_application &&
step_publish_frontend_application &&
step_start_frontend_application
