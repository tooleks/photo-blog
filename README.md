## The Photo Blog Application based on Laravel 5 and Vue.js 2 + Prerender

### Tech Stack

Docker 17.10, Docker Compose 1.14, NGINX 1.15, MySQL 5.7, Redis 4, PHP 7.3, Laravel 5.7, Node.js 10, Vue.js 2.5.

### Installation

Please make sure you have installed and running [Docker](https://docs.docker.com/) and [Docker Compose](https://docs.docker.com/compose/install/) on the host machine as far as the following commands will rely on your setup.

Run the command (within the project root directory) to initialize the project.

```
make initialization
```

Run the command (within the project root directory) to start Docker containers in the **development** mode.

```
make start-dev
```

**Tip:** `Ctrl`+`C` interrupts the process.

Run the command (in another terminal window, within the project root directory) to configure the application and create the administrator user.

```
make configuration
```

Open the [localhost:8080/sign-in](http://localhost:8080/sign-in) link in a browser and sign in with a newly created administrator user account.

### Exposed Resources

| Resource Location                         | Description |
|:------------------------------------------|:-------------|
| [localhost:8080](http://localhost:8080)   | The photo blog application. |
| [localhost:8081](http://localhost:8081)   | Documentation for RESTful API of the application. |
| [localhost:8083](http://localhost:8083)   | SMTP Server + Web Interface for viewing and testing emails during development. |

### Other Commands

| Command Signature  | Description |
|:-------------------|:-------------|
| `make watch-dev`   | Automatically recompile assets when Webpack detects a change. |
| `make test`        | Execute the application tests. |
| `make app-logs`    | Fetch the application logs. |
| `make mysql-logs`  | Fetch MySQL logs. |

**Tip:** Take a look at `./Makefile` file content to discover all available commands.
