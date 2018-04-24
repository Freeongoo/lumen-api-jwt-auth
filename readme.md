# Example JWT authentication for Lumen API

Simple way create API

## Installation

1. cp .env.example .env
2. Config .env:
    - Set param "APP_KEY" with random string (32 characters long)
    - Set param "JWT_SECRET" with random string too (used for JWT, recommended 32 characters long)
    - Config database config (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
3. Run migration database: php artisan migrate
4. 

Documentation for the framework can be found on the [Lumen website](http://lumen.laravel.com/docs).
