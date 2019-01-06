init: install-dependencies
	cp -n ./docker-compose.development.yml ./docker-compose.override.yml
	cp -n ./app/.env.example ./app/.env
	ln -sfn ../storage/app/public app/public/storage

install-dependencies:
	docker run --rm -v "${PWD}/app:/app" -w "/app" node:10 npm install
	docker run --rm -v "${PWD}/app:/app" -w "/app" composer:1.8 install \
		--ignore-platform-reqs \
		--no-interaction \
		--no-plugins \
		--no-scripts \
		--prefer-dist

update-dependencies:
	docker run --rm -v "${PWD}/app:/app" -w "/app" node:10 npm update
	docker run --rm -v "${PWD}/app:/app" -w "/app" composer:1.8 update \
		--ignore-platform-reqs \
		--no-interaction \
		--no-plugins \
		--no-scripts \
		--prefer-dist

config:
	docker exec -it pb-app bash -c "chown -R www-data:www-data ./storage"
	docker exec -it pb-app bash -c "php artisan key:generate"
	docker exec -it pb-app bash -c "php artisan package:discover"
	docker exec -it pb-app bash -c "php artisan migrate"
	docker exec -it pb-app bash -c "php artisan passport:install"
	docker exec -it pb-app bash -c "php artisan create:roles"
	docker exec -it pb-app bash -c "npm run prod"
	docker exec -it pb-app bash -c "php artisan generate:rest_api_documentation"
	docker exec -it pb-app bash -c "php artisan create:administrator_user"

build:
	docker-compose build

rebuild:
	docker-compose build --no-cache

start:
	docker-compose up

start-daemon:
	docker-compose up -d

stop-daemon:
	docker-compose down

test:
	docker exec -it pb-app bash -c "./vendor/bin/phpunit"

app-watch:
	docker exec -it pb-app bash -c "npm run watch"

app-logs:
	docker exec -it pb-app bash -c "tail -n 1000 -f ./storage/logs/laravel.log"

mysql-logs:
	docker exec -it pb-mysql bash -c "tail -n 1000 -f /var/log/mysql/general.log"