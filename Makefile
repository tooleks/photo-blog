init:
	cp ./app/.env.example ./app/.env
	cd ./app/public && ln -s ../storage/app/public storage

dependencies:
	docker run --rm --mount "type=bind,source=$(PWD)/app,target=/app" -w "/app" node:10 npm install
	docker run --rm --mount "type=bind,source=$(PWD)/app,target=/app" -w "/app" composer:1.7 install \
		--ignore-platform-reqs \
		--no-interaction \
		--no-plugins \
		--no-scripts \
		--prefer-dist

configure:
	docker exec -it pb-app bash -c "php artisan passport:install"
	docker exec -it pb-app bash -c "chown -R www-data:www-data ./storage"
	docker exec -it pb-app bash -c "php artisan create:administrator_user"
