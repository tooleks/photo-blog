## Photo Blog Application based on Laravel 5 and Angular 4

## Tech Stack

Docker 17.06, NGINX 1.12, MySQL 5.7, Redis 3.2, PHP 7.1, Laravel 5.4, Node.js 8.1, Angular 4.0.

### Startup

Create the `./backend/.env` file from the example `./backend/.env.example`.

Create the `./frontend/.env` file from the example `./frontend/.env.example`.

Create the `./docker-compose.yml` file from the example `./docker-compose.yml.example`.

Run the following command (within the `./` directory).

```
COMPOSE_HTTP_TIMEOUT=10000 docker-compose up -d
```

Open the [http://localhost:8080](http://localhost:8080) link in a browser.
