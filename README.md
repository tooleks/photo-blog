## Photo Blog Application based on Laravel 5.3 and Angular 2.0

### Backend

#### Development Configuration

Apache 2.4, MySQL 5.7, Node.js 4.2, PHP 7.0, Laravel 5.3.

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

Run the following commands (within the `./backend` directory) to install development dependencies:

```
npm install
npm install -g gulp api-doc
```

Run the following command (within the `./backend` directory) to generate REST API documentation:

```
gulp generate-api-doc
```

This command will create `./docs/api/dist` directory. Open the `./docs/api/dist/index.html` file in your favorite web browser to show the REST API documentation.

### Frontend

#### Installation

Run the following command (within the `./frontend` directory) to install application dependencies:

```
npm install
```

Create the `./frontend/env.ts` file from the example `./frontend/env.ts.example`. Setup API connection credentials.

Run the following command (within the `./frontend` directory) to start dev server:

```
npm start
```
