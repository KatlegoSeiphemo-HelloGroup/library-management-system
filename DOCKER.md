# Docker Setup

This document explains how the Library System is containerized and how the web application communicates with its MySQL database.

## Overview

The application consists of two Docker services:

1. **`web`** — a PHP 8.3 application running on Apache.
2. **`mysql`** — a MySQL 8.0 database containing the library system's data.

Docker Compose starts both services and creates a private network so that the PHP application can communicate with MySQL using the hostname `mysql`.

```text
                         Docker Compose
┌─────────────────────────────────────────────────────────┐
│                                                         │
│   ┌───────────────────────┐                             │
│   │        web            │                             │
│   │                       │                             │
│   │ PHP 8.3 + Apache      │                             │
│   │                       │                             │
│   │ Container:            │                             │
│   │ library-system        │                             │
│   │                       │                             │
│   │ Port: 80              │                             │
│   └───────────┬───────────┘                             │
│               │                                         │
│               │ MySQL connection                        │
│               │ host: mysql                             │
│               ▼                                         │
│   ┌───────────────────────┐                             │
│   │        mysql          │                             │
│   │                       │                             │
│   │ MySQL 8.0             │                             │
│   │                       │                             │
│   │ Container:            │                             │
│   │ library-mysql        │                             │
│   └───────────┬───────────┘                             │
│               │                                         │
│               ▼                                         │
│          mysql_data                                    │
│          persistent volume                             │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

---

# Project Structure

The Docker setup expects a structure similar to:

```text
library-system/
│
├── Dockerfile
├── docker-compose.yml
├── .env
│
├── src/
│   ├── index.php
│   ├── ...
│
└── database/
    ├── create_tables.sql
    └── insert_data.sql
```

Each part has a specific purpose:

| File/Directory               | Purpose                                 |
| ---------------------------- | --------------------------------------- |
| `Dockerfile`                 | Builds the PHP/Apache application image |
| `docker-compose.yml`         | Defines and connects the containers     |
| `.env`                       | Stores database configuration           |
| `src/`                       | Contains the PHP application            |
| `database/create_tables.sql` | Creates the database tables             |
| `database/insert_data.sql`   | Inserts initial data                    |

---

# Docker Compose

The application is defined using Docker Compose.

The Compose file contains two services:

```yaml
services:
  web:
    ...
  
  mysql:
    ...
```

These services are automatically placed on the same Docker network.

This allows the PHP application to communicate with MySQL using:

```text
mysql
```

rather than:

```text
localhost
```

---

# Web Service

The web application is defined as:

```yaml
web:
  build: .
  container_name: library-system
  ports:
    - "8080:80"
```

## Building the Image

```yaml
build: .
```

This tells Docker Compose to build the image using the `Dockerfile` in the current directory.

The Dockerfile is responsible for creating the PHP/Apache environment.

---

## Container Name

```yaml
container_name: library-system
```

The running container is named:

```text
library-system
```

You can see it with:

```bash
docker ps
```

---

## Port Mapping

```yaml
ports:
  - "8080:80"
```

This maps port `8080` on the host machine to port `80` inside the container.

```text
Host machine                 Container
────────────────────────────────────────
localhost:8080  ───────────► Apache:80
```

Therefore, after starting the application, the web application can be accessed through:

```text
http://localhost:8080
```

Apache listens on port `80` inside the container.

---

# PHP and Apache Dockerfile

The web container is built using:

```dockerfile
FROM php:8.3-apache

LABEL authors="katlego.seiphemo"

WORKDIR /var/www/html

RUN docker-php-ext-install mysqli

COPY src/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
```

## PHP 8.3 + Apache

The base image is:

```dockerfile
FROM php:8.3-apache
```

This provides:

* PHP 8.3
* Apache
* PHP configured to work with Apache

The application therefore does not need a separate Apache container.

---

# Working Directory

```dockerfile
WORKDIR /var/www/html
```

This is Apache's default document root.

The PHP application is therefore placed inside:

```text
/var/www/html
```

---

# MySQL Extension

The Dockerfile installs the PHP MySQLi extension:

```dockerfile
RUN docker-php-ext-install mysqli
```

This is important because the PHP application uses MySQL.

Without the `mysqli` extension, PHP would not be able to use functions such as:

```php
mysqli_connect()
```

to connect to the database.

---

# Copying the Application

```dockerfile
COPY src/ /var/www/html/
```

Everything inside the local `src/` directory is copied into the Apache document root.

For example:

```text
Local machine:

src/
├── index.php
├── books.php
└── users.php
```

becomes:

```text
Container:

/var/www/html/
├── index.php
├── books.php
└── users.php
```

Apache then serves these PHP files.

---

# File Permissions

The Dockerfile sets the ownership and permissions:

```dockerfile
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
```

`www-data` is the user used by Apache inside the container.

This ensures that Apache has appropriate access to the application files.

---

# Apache Port

The Dockerfile contains:

```dockerfile
EXPOSE 80
```

This documents that Apache listens on port `80` inside the container.

Docker Compose then maps that port to port `8080` on the host:

```yaml
ports:
  - "8080:80"
```

---

# Starting Apache

The container starts Apache using:

```dockerfile
CMD ["apache2-foreground"]
```

This keeps Apache running in the foreground, which allows Docker to manage the container correctly.

---

# MySQL Service

The database is defined as:

```yaml
mysql:
  image: mysql:8.0
  container_name: library-mysql
```

The service uses the official MySQL 8.0 image.

The container is named:

```text
library-mysql
```

---

# Database Configuration

MySQL receives its configuration through environment variables:

```yaml
environment:
  MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
  MYSQL_DATABASE: ${MYSQL_DATABASE}
```

The values are loaded from the `.env` file.

For example:

```text
MYSQL_ROOT_PASSWORD=your_password
MYSQL_DATABASE=library
```

The `.env` file should not normally be committed to source control if it contains real passwords.

A `.env.example` file can be used to document the required variables without exposing credentials.

---

# Database Initialization

The MySQL service mounts two SQL files:

```yaml
volumes:
  - ./database/create_tables.sql:/docker-entrypoint-initdb.d/01-create_tables.sql
  - ./database/insert_data.sql:/docker-entrypoint-initdb.d/02-insert_data.sql
```

MySQL automatically executes SQL files placed in:

```text
/docker-entrypoint-initdb.d/
```

when the database is initialized for the first time.

The files are prefixed with numbers to make their intended order clear:

```text
01-create_tables.sql
02-insert_data.sql
```

The expected process is:

```text
MySQL starts
     │
     ▼
Create database
     │
     ▼
01-create_tables.sql
     │
     ▼
Create tables
     │
     ▼
02-insert_data.sql
     │
     ▼
Insert initial data
     │
     ▼
Database ready
```

This means the tables are created before the initial data is inserted.

---

# Important: SQL Files Run Only on Initial Database Creation

The initialization scripts are intended for a **new MySQL data directory**.

If the `mysql_data` volume already contains a database, MySQL will not normally rerun these initialization scripts.

Therefore, if you modify:

```text
create_tables.sql
```

or:

```text
insert_data.sql
```

and want to initialize the database from scratch again, you need to remove the existing volume.

For example:

```bash
docker compose down -v
```

Then start the application again:

```bash
docker compose up
```

**Warning:** `docker compose down -v` deletes the database volume and therefore removes the data stored in it.

---

# Persistent Database Storage

The Compose file defines:

```yaml
volumes:
  - mysql_data:/var/lib/mysql
```

This creates a named Docker volume:

```text
mysql_data
```

MySQL stores its database files inside:

```text
/var/lib/mysql
```

The volume ensures that the database data survives when the MySQL container is stopped or recreated.

```text
MySQL Container
       │
       ▼
/var/lib/mysql
       │
       ▼
mysql_data volume
```

Therefore:

```bash
docker compose down
```

does not remove the database data.

However:

```bash
docker compose down -v
```

does remove the named volume and its data.

---

# Database Health Check

The MySQL service has a health check:

```yaml
healthcheck:
  test: ["CMD", "mysqladmin", "ping", "-h", "localhost", "-u", "root", "-p${MYSQL_ROOT_PASSWORD}"]
  interval: 5s
  timeout: 5s
  retries: 10
```

Docker uses `mysqladmin ping` to determine whether MySQL is ready to accept connections.

The check is performed every 5 seconds.

A failed check can take up to 5 seconds before timing out, and Docker allows up to 10 failed attempts.

---

# Web Depends on MySQL

The web service contains:

```yaml
depends_on:
  mysql:
    condition: service_healthy
```

This is important.

It tells Docker Compose that the PHP application should wait until MySQL is healthy before starting the web container.

The startup sequence is therefore:

```text
                docker compose up
                        │
                        ▼
                  Start MySQL
                        │
                        ▼
                 MySQL initializes
                        │
                        ▼
              Run SQL initialization
                        │
                        ▼
                Health check passes
                        │
                        ▼
                  Start PHP/Apache
                        │
                        ▼
                Library System ready
```

This reduces the chance of the PHP application attempting to connect to MySQL before MySQL is ready.

---

# Connecting PHP to MySQL

The web service receives the database configuration:

```yaml
environment:
  DB_HOST: mysql
  DB_USER: root
  DB_PASSWORD: ${MYSQL_ROOT_PASSWORD}
  DB_NAME: ${MYSQL_DATABASE}
```

The important value is:

```text
DB_HOST=mysql
```

Inside Docker Compose, the service name:

```text
mysql
```

acts as the hostname for the database container.

Therefore, PHP should connect to:

```text
Host: mysql
Port: 3306
User: root
Password: value of MYSQL_ROOT_PASSWORD
Database: value of MYSQL_DATABASE
```

For example, PHP might use:

```php
$connection = mysqli_connect(
    getenv('DB_HOST'),
    getenv('DB_USER'),
    getenv('DB_PASSWORD'),
    getenv('DB_NAME')
);
```

The exact connection code depends on how the application is implemented.

---

# Why the Database Host Is `mysql`

It is important not to configure PHP to connect to:

```text
localhost
```

when running inside Docker.

Inside the `web` container:

```text
localhost
```

refers to the **web container itself**.

The MySQL server is running in a different container.

Docker Compose provides internal DNS, allowing the web container to find MySQL through the service name:

```text
mysql
```

Therefore:

```text
PHP
 │
 │ DB_HOST=mysql
 ▼
MySQL container
```

---

# Complete Application Flow

The complete application works as follows:

```text
User
 │
 │ http://localhost:8080
 ▼
┌──────────────────────────────┐
│       web container          │
│                              │
│     Apache + PHP 8.3        │
│                              │
│     library-system           │
└──────────────┬───────────────┘
               │
               │ MySQL connection
               │
               │ DB_HOST=mysql
               ▼
┌──────────────────────────────┐
│      MySQL 8.0 container     │
│                              │
│      library-mysql           │
│                              │
│      Database                │
│      ├── tables              │
│      └── library data        │
└──────────────┬───────────────┘
               │
               ▼
          mysql_data
        Docker volume
```

---

# Running the Application

## 1. Configure Environment Variables

Create a `.env` file in the same directory as `docker-compose.yml`.

For example:

```env
MYSQL_ROOT_PASSWORD=your_password
MYSQL_DATABASE=library
```

Use a secure password for the actual application.

---

## 2. Build and Start the Containers

Run:

```bash
docker compose up --build
```

The `--build` option tells Docker Compose to rebuild the PHP application image.

Alternatively, run the containers in the background:

```bash
docker compose up --build -d
```

---

## 3. Access the Application

Once the containers are running, open:

```text
http://localhost:8080
```

The request is routed as:

```text
localhost:8080
       │
       ▼
Host port 8080
       │
       ▼
Container port 80
       │
       ▼
Apache
       │
       ▼
PHP application
```

---

# Checking Container Status

Use:

```bash
docker compose ps
```

You should see:

```text
library-system
library-mysql
```

The MySQL container should eventually report as healthy.

---

# Viewing Logs

To view the web application logs:

```bash
docker compose logs web
```

To follow the logs:

```bash
docker compose logs -f web
```

To view MySQL logs:

```bash
docker compose logs mysql
```

To view both:

```bash
docker compose logs -f
```

---

# Stopping the Application

Stop the containers with:

```bash
docker compose down
```

The containers are removed, but the `mysql_data` volume remains.

This means the database data is preserved.

To start the application again:

```bash
docker compose up
```

---

# Resetting the Database

If you want to completely recreate the database and rerun the SQL initialization scripts:

```bash
docker compose down -v
docker compose up --build
```

The `-v` option removes the `mysql_data` volume.

**Warning:** This permanently removes the data stored in that Docker volume.

After the volume is removed, MySQL will initialize the database again and execute:

```text
database/create_tables.sql
database/insert_data.sql
```

---

# Troubleshooting

## PHP Cannot Connect to MySQL

Check that MySQL is running and healthy:

```bash
docker compose ps
```

Then inspect the MySQL logs:

```bash
docker compose logs mysql
```

Make sure the PHP container has:

```text
DB_HOST=mysql
```

and not:

```text
DB_HOST=localhost
```

---

## Database Tables Are Missing

If the SQL initialization files were changed after the database was already created, they may not automatically execute again.

Reset the database:

```bash
docker compose down -v
docker compose up
```

Remember that this deletes the existing database data.

---

## Application Does Not Load on Port 8080

Check the web container:

```bash
docker compose ps
```

Then check its logs:

```bash
docker compose logs web
```

Also make sure port `8080` is not already being used by another application.

The mapping is:

```text
8080:80
```

which means:

```text
Host:      8080
Container: 80
```

---

# Summary

The Library System uses Docker Compose to run the PHP application and its MySQL database together.

### Web container

```text
PHP 8.3
   +
Apache
   +
mysqli
```

The application is available from the host at:

```text
http://localhost:8080
```

### Database container

```text
MySQL 8.0
   +
library database
   +
persistent mysql_data volume
```

### Communication

The PHP application connects to MySQL using:

```text
DB_HOST=mysql
```

Docker Compose handles the networking between the two containers.

### Startup

```text
Docker Compose
      │
      ├── MySQL starts
      │
      ├── Database is initialized
      │
      ├── MySQL health check passes
      │
      └── PHP/Apache starts
                │
                ▼
        Library System
```

The result is a self-contained development environment where the PHP application, Apache web server, MySQL database, database initialization scripts, and persistent database storage are all managed through Docker Compose.
