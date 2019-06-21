# https://docs.docker.com/compose/reference/envvars/#compose_project_name
COMPOSE_PROJECT_NAME = photoblog

.DEFAULT_GOAL := help

.PHONY: help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

init: install-deps ## Initialize application
	cp -n ./docker-compose.development.yml ./docker-compose.override.yml
	cp -n ./app/.env.example ./app/.env
	ln -sfn ../storage/app/public app/public/storage

post-init: ## Peform post-initialization steps
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "chown -R www-data:www-data ./storage"
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "chown -R www-data:www-data ./bootstrap/cache"
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "php artisan key:generate"
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "php artisan package:discover"
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "php artisan migrate"
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "php artisan passport:install"
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "php artisan create:roles"
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "npm run prod"
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "php artisan generate:rest-api-documentation"
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "php artisan create:administrator-user"

install-deps: ## Install application dependencies on host machine
	docker run --rm -v "${PWD}/app:/app" -w "/app" node:10 npm install
	docker run --rm -v "${PWD}/app:/app" -w "/app" composer:1.8 install \
		--ignore-platform-reqs \
		--no-interaction \
		--no-plugins \
		--no-scripts \
		--prefer-dist

update-deps: ## Update application dependencies on host machine
	docker run --rm -v "${PWD}/app:/app" -w "/app" node:10 npm update
	docker run --rm -v "${PWD}/app:/app" -w "/app" composer:1.8 update \
		--ignore-platform-reqs \
		--no-interaction \
		--no-plugins \
		--no-scripts \
		--prefer-dist

build: ## Build Docker containers
	docker-compose -p ${COMPOSE_PROJECT_NAME} build

rebuild: ## Rebuild Docker containers
	docker-compose -p ${COMPOSE_PROJECT_NAME} build --no-cache

start: ## Start Docker containers
	docker-compose -p ${COMPOSE_PROJECT_NAME} up

start-daemon: ## Start Docker containers in background mode
	docker-compose -p ${COMPOSE_PROJECT_NAME} up -d

stop-daemon: ## Stop Docker containers in background mode
	docker-compose -p ${COMPOSE_PROJECT_NAME} down

test: ## Run application tests
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "./vendor/bin/phpunit"

app-watch: ## Watch frontend application
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "npm run watch"

app-logs: ## Watch application logs
	docker exec -it ${COMPOSE_PROJECT_NAME}_app_1 bash -c "tail -n 1000 -f ./storage/logs/laravel.log"

mysql-logs: ## Watch database logs
	docker exec -it ${COMPOSE_PROJECT_NAME}_mysql_1 bash -c "tail -n 1000 -f /var/log/mysql/general.log"
