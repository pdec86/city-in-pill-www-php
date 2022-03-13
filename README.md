# city-in-pill-www-php
City in Pill - PHP sample

After fetching source code from GIT You should go to the directory, where code was fetched.

In terminal run following commands:

```
composer install
```
- to install dependecies
  <br />
  <br />
```
php bin/console cache:clear
```
- to clear application cache
  <br />
  <br />
```
php bin/console --env=test doctrine:database:create
```
- to create test database
  <br />
  <br />
```
php bin/console --env=test doctrine:schema:create
```
- to create tables/columns in test database
  <br />
  <br />
```
php bin/console doctrine:database:create
```
- to create database (1)
  <br />
  <br />
```
php bin/console doctrine:migrations:migrate
```
- to create tables (1)
  <br />
  <br />
```
php bin/phpunit or php vendor/bin/phpunit
```
- to trigger tests written in project (1)
  <br />
  <br />
```
php vendor/bin/behat
```
- to trigger Behat tests
  <br />
  <br />
```
docker-compose build
```
- to build Docker environment
  <br />
  <br />
```
docker-compose up -d
```
- for starting application
  <br />
  <br />
```
docker-compose exec php bin/console doctrine:database:create
```
- to create database
  <br />
  <br />
```
docker-compose exec php bin/console doctrine:migrations:migrate
```
- to perform database migration
  <br />
  <br />
```
docker-compose stop
``` 
- for stopping application
<br />
<br />

(1) Before running the command, make sure you have set properly DATABASE_URL in .env and/or .env.test
