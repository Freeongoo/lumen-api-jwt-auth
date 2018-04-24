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

## Getting Started

### Private access

We have private access to the list of users, you can check by typing in the browser:<br/>
http://mydomain.loc/users<br/><br/>
And you see fail message:<br/>
`"error": "Token not provided."`

### Authentication

1. Select any user from database (email, password)
2. Got to postman (https://www.getpostman.com/)
3. Create POST request to url: https://mydomain.loc/auth/lohin with params:
    - email 
    - password 
4. Return token, save it
5. Create GET request to url: https://mydomain.loc/users with param:
    - token (obtained with successful login)
6. See result: list of users

## Built With

* [Lumen](https://lumen.laravel.com/)
* [JWT](https://jwt.io/)