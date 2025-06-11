# Schoox Courses

This is a project written in Laravel that implements a REST API, which can be used to create, list, modify and delete Courses.
The project uses Postgresql.

## Docker Structure

The project consists of 4 docker containers:

1. web: the nginx web server
2. php-fpm: the php server container
3. workspace: contains the composer and artisan executables
4. postgres: the Postgres database container

## Installation

Make sure you have Docker installed in your system.

Run the following commands:

1. `cp .env.example .env`. Modify any environment variables you need, namely add your UID (user ID) and GID (group ID) to avoid permission issues
2. `docker compose up --build -d`, which will create the necessary docker containers

The migrations and application key generation are automatically run on docker startup, but they can also be run with the `docker compose exec workspace php artisan migrate` (for migrations) and `docker compose exec workspace php artisan key:generate` commands.

## Tests

To run the test suite, execute the following command:

```
docker compose exec workspace php artisan test
```
