## Photo Blog Application based on Laravel 5 and Angular 4

### Tech Stack

Docker 17.06, NGINX 1.12, MySQL 5.7, Redis 3.2, PHP 7.1, Laravel 5.4, Node.js 8.1, Angular 4.0.

### Installation

Run the following command (within the `./` directory) to create environment files.

```
cp ./backend/.env.example ./backend/.env && \
cp ./frontend/.env.example ./frontend/.env && \
cp ./docker-compose.yml.example ./docker-compose.yml
```

Run the following command (within the `./` directory) to create public storage.

```
cd ./backend/public/ && \
ln -s ../storage/app/public storage
```

Run the following command (within the `./` directory) to start the docker containers and build the application.

```
docker-compose up
```

Run the following command to create the administrator user.
```
docker exec -it photo-blog-backend bash -c "php artisan create:administrator_user"
```

Run following commands to create the encryption keys needed to generate secure access tokens.
```
docker exec -it photo-blog-backend bash -c "php artisan passport:install" && \
docker exec -it photo-blog-backend bash -c "chown -R www-data:www-data storage"
```

Open the [http://localhost:8080/signin](http://localhost:8080/signin) link in a browser to signin with a newly created administrator account.

### Tests

Run the following command to execute the backend application tests.
```
docker exec -it photo-blog-backend bash -c "./vendor/bin/phpunit"
```
