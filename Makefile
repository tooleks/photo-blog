initialization: dependencies
	cp -n ./app/.env.example ./app/.env
	ln -sfn ../storage/app/public app/public/storage

dependencies:
	docker run --rm --mount "type=bind,source=$(PWD)/app,target=/app" -w "/app" node:10 npm install
	docker run --rm --mount "type=bind,source=$(PWD)/app,target=/app" -w "/app" composer:1.7 install \
		--ignore-platform-reqs \
		--no-interaction \
		--no-plugins \
		--no-scripts \
		--prefer-dist

configuration:
	docker exec -it pb-app bash -c "chown -R www-data:www-data ./storage"
	docker exec -it pb-app bash -c "php artisan key:generate"
	docker exec -it pb-app bash -c "php artisan package:discover"
	docker exec -it pb-app bash -c "php artisan migrate"
	docker exec -it pb-app bash -c "php artisan passport:install"
	docker exec -it pb-app bash -c "php artisan create:roles"
	docker exec -it pb-app bash -c "npm run prod"
	docker exec -it pb-app bash -c "php artisan generate:rest_api_documentation"
	docker exec -it pb-app bash -c "php artisan create:administrator_user"

start-dev:
	docker-compose --file ./docker-compose.development.yml up

up-prod:
	docker-compose --file ./docker-compose.production.yml up --build -d

down-prod:
	docker-compose --file ./docker-compose.production.yml down