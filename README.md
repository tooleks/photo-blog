## The Photo Blog Application based on Laravel 5 and Vue.js 2 + Prerender

### Tech Stack

Docker 17.10, NGINX 1.12, MySQL 5.7, Redis 3.2, PHP 7.2, Laravel 5.5, Node.js 8.9, Vue.js 2.5.

### Installation

Run the following command (within the `./` directory) to create public storage.

```
cd ./app/public/ && \
ln -s ../storage/app/public storage
```

Run the following command (within the `./` directory) to start the docker containers and build the application for **development** environment.

```
docker-compose --file ./docker-compose.dev.yml up --build
```

Run following commands to create the encryption keys needed to generate secure access tokens.
```
docker exec -it photo-blog-app bash -c "php artisan passport:install" && \
docker exec -it photo-blog-app bash -c "chown -R www-data:www-data storage"
```

Run the following command to create the administrator user.
```
docker exec -it photo-blog-app bash -c "php artisan create:administrator_user"
```

Open the [http://localhost:8080/sign-in](http://localhost:8080/sign-in) link in a browser to signin with a newly created administrator account.

### Useful Commands

Generate the Laravel application's REST API documentation.

```bash
docker exec -it photo-blog-app bash -c "php artisan generate:rest_api_documentation"
```

Fetch the application's log.

```bash
docker exec -it photo-blog-app bash -c "tail -n 1000 -f ./storage/logs/laravel.log"
```

Fetch the MySQL log.

```bash
docker exec -it photo-blog-mysql bash -c "tail -n 1000 -f /var/log/mysql/general.log"
```

### Tests

Run the following command to execute the backend application tests.
```
docker exec -it photo-blog-app bash -c "./vendor/bin/phpunit"
```

### Production

Run the following command (within the `./` directory) to start the docker containers and build the application for **production** environment.

```
docker-compose --file ./docker-compose.prod.yml up --build -d
```
