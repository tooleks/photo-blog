init:
	cp ./app/.env.example ./app/.env
	cd ./app/public && ln -s ../storage/app/public storage

app/node_modules: ./app/package.json ./app/package-lock.json
	docker run --rm --mount "type=bind,source=$(PWD)/app,target=/app" -w "/app" node:10 npm install

app/vendor: ./app/composer.json ./app/composer.lock
	docker run --rm --mount "type=bind,source=$(PWD)/app,target=/app" -w "/app" composer:1.7 install \
		--ignore-platform-reqs \
		--no-interaction \
		--no-plugins \
		--no-scripts \
		--prefer-dist

build:
	docker build -t "pb-app" ./app
	docker build -t "pb-app-queue" ./app-queue
	docker build -t "pb-app-scheduler" ./app-scheduler
	docker build -t "pb-nginx" ./nginx
	docker build -t "pb-prerender" ./prerender

configure:
	docker exec -it pb-app bash -c "php artisan passport:install"
	docker exec -it pb-app bash -c "chown -R www-data:www-data storage"
	docker exec -it pb-app bash -c "php artisan create:administrator_user"

start-dev: build app/node_modules app/vendor
	docker-compose --file ./docker-compose.dev.yml up

up-prod: build
	docker-compose --file ./docker-compose.prod.yml up -d

down-prod:
	docker-compose --file ./docker-compose.prod.yml down