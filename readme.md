# Example JWT authentication for Lumen API

Simple way create API

## Installation

1. Cope config file: `cp .env.example .env`
2. Config .env:
    - Set param "APP_KEY" with random string (32 characters long)
    - Set param "JWT_SECRET" with random string too (used for JWT, recommended 32 characters long)
    - Config database config (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
3. Run install dependencies: `composer install`
4. Run migration database: `php artisan migrate`
5. Insert data to DB
    - `php artisan db:seed` (insert users in database, see database/factories/ModelFactory.php)

Documentation for the framework can be found on the [Lumen website](http://lumen.laravel.com/docs).
