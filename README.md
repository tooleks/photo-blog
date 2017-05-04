## Photo Blog Application based on Laravel 5 and Angular 4

### Backend

#### Development Configuration

NGINX 1.10, MySQL 5.7, PHP 7.0 (ext-openssl, ext-pdo, ext-mbstring, ext-tokenizer, ext-xml, ext-gd), Laravel 5.4, Node.js 7.5, Angular 4.0.

#### Installation

Create the `./backend/.env` file from the example `./backend/.env.example`. Setup database connection credentials.

Run the following command (within the `./backend` directory) to install application dependencies:

```
composer install
```

Run the following commands to configure application:

```
php artisan config:app
php artisan create:administrator_user
```

Add the following line to your crontab to setup scheduler (replace `/path/to/artisan` with a real path):

```
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

#### REST API Documentation

Run the following command (within the `./backend` directory) to install development dependencies:

```
npm install -g api-doc
```

Run the following command (within the `./backend` directory) to generate REST API documentation:

```
php artisan create:api_documentation
```

This command will create `./docs/api/dist` directory. Open the `./docs/api/dist/index.html` file in your favorite web browser to show the REST API documentation.

#### Tests

Run the following command (within the `./backend` directory) to run tests:

```
./vendor/bin/phpunit
```

### Frontend

#### Installation

Run the following command (within the `./frontend` directory) to install application dependencies:

```
npm install
```

Create the `./frontend/env.ts` file from the example `./frontend/env.ts.example`. Setup API connection credentials.

Run the following command (within the `./frontend` directory) to start a web-server.

```
npm run start
```
