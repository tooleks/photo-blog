## The Photo Blog Application based on Laravel 5 and Vue.js 2 + Prerender

### Tech Stack

- Docker 17.10
- Docker Compose 1.14
- NGINX 1.15
- MySQL 5.7
- Redis 4
- PHP-FPM 7.3
- Laravel 5.8
- Node.js 10
- Vue.js 2.6

### Installation

Please make sure you have installed and running [Docker](https://docs.docker.com/) and [Docker Compose](https://docs.docker.com/compose/install/) on the host machine as far as the following commands will rely on your setup.

Run the command (within the project root directory) to initialize the application.

```
make init
```

Run the command (within the project root directory) to start Docker containers.

```
make start
```

**Tip:** `Ctrl`+`C` interrupts the process.

Run the command (in another terminal window, within the project root directory) to perform post-initialization steps.

```
make post-init
```

**Tip:** Run `make help` to list all available commands.

### Browsing

* [http://localhost:8080](http://localhost:8080) - Web Application
* [http://localhost:8081](http://localhost:8081) - REST API Documentation
* [http://localhost:8083](http://localhost:8083) - SMTP Server Web Interface
