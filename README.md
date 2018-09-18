## The Photo Blog Application based on Laravel 5 and Vue.js 2 + Prerender

### Tech Stack

Docker 17.10, Docker Compose 1.14, NGINX 1.15, MySQL 5.7, Redis 4, PHP 7.2, Laravel 5.7, Node.js 10, Vue.js 2.5.

### Installation

Please make sure you have installed and running [Docker](https://docs.docker.com/) and [Docker Compose](https://docs.docker.com/compose/install/) on the host machine as far as the following GNU make commands will rely on your setup.

Run the command (within the project root directory) to initialize file system structure.

```
make init
```

Run the command (within the project root directory) to start Docker containers in the **development** mode.

```
make start-dev
```

Run the command (within the project root directory) to configure the application and create the administrator user.

```
make configure
```

Open the [http://localhost:8080/sign-in](http://localhost:8080/sign-in) link in a browser and sign in with a newly created administrator user account.

### Exposed Resources

* [http://localhost:8080](http://localhost:8080) - The application;
* [http://localhost:8081](http://localhost:8081) - REST API documentation;
* [http://localhost:8083](http://localhost:8083) - MailDev mailbox.

### Useful Commands

Fetch the backend application's log.

```bash
docker exec -it pb-app bash -c "tail -n 1000 -f ./storage/logs/laravel.log"
```

Fetch the database log.

```bash
docker exec -it pb-mysql bash -c "tail -n 1000 -f /var/log/mysql/general.log"
```

### Tests

Run the command to execute the backend application tests.

```
docker exec -it pb-app bash -c "./vendor/bin/phpunit"
```
