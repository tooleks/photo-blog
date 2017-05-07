#!/bin/bash

root_path="$PWD/.."

isProdMode() {
    if [ "$1" == "prod" ]; then
        return 0
    else
        return 1
    fi
}

printfPWD() {
    printf "$PWD\n"
}

printfStepHeader() {
    printf ">> $1\n"
}

printfStepFooter() {
    printf "\n\n"
}

printfStepHeader "Updating Sources"
cd "$root_path"
printfPWD
git pull
printfStepFooter

printfStepHeader "Updating Backend Application Dependencies"
cd "$root_path/backend"
printfPWD
composer install
composer dump-autoload
printfStepFooter

printfStepHeader "Migrating Database"
cd "$root_path/backend"
printfPWD
php artisan migrate --force
printfStepFooter

printfStepHeader "Generating REST API Documentation"
cd "$root_path/backend"
printfPWD
php artisan generate:rest_api_documentation
printfStepFooter

if isProdMode $1; then
    printfStepHeader "Restarting Backend Application"
    cd "$root_path"
    printfPWD
    sudo systemctl restart nginx php7.0-fpm
    printfStepFooter
fi

printfStepHeader "Updating Frontend Application Dependencies"
cd "$root_path/frontend"
printfPWD
yarn install
printfStepFooter

printfStepHeader "Building Frontend Application"
cd "$root_path/frontend"
printfPWD
if isProdMode $1; then
    npm run build:prod
else
    npm run build
fi
printfStepFooter

printfStepHeader "Publishing Frontend Application"
cd "$root_path/frontend"
printfPWD
rm -r public
cp -r dist public
printfStepFooter

if isProdMode $1; then
    printfStepHeader "Restarting Frontend Application"
    cd "$root_path/frontend/public"
    printfPWD
    pm2 restart server.js
    printfStepFooter
fi
