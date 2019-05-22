# Authentication with Laravel JWT
A simple test project for user authentication with Laravel and JWT

## Build status
[![Build Status](https://img.shields.io/badge/build-developing-blue.svg)]()

## Tech/framework used
- Laravel 5.8
- tymon/jwt-auth

## Features
- Full features of authentication module
- [x] Register
- [x] Login, logout
- [x] Reset password
- [x] Get user's infos

### Bugs
Something will happen, whatever!

## Installation
- Clone this project and cd to the cloned folder
- Change the .env configurations
- Run the following commands:
```cmd
composer update

php artisan migrate
```
- Add the service provider to the providers array in the config/app.php config file as follows:
```php
'providers' => [

...

Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
]
```
- Publish JWT package's config:
```cmd
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```
- Generate secure key:
```cmd
php artisan jwt:secret
```

## API Reference
For more informations about JWTAuth, visit [jwt-auth](https://jwt-auth.readthedocs.io/en/develop)

## Tests
1. Registering a new user:
``` POST domain/api/v1/auth/register ```
> Form data: name, email, password
> Returns: a JSON includes access_token if ok
2. Logging in:
``` POST domain/api/v1/auth/login ```
> Form data: email, password
> Returns: a JSON includes access_token if ok
3. Retrieving user's infos:
``` GET domain/api/v1/auth/me ```
> Headers: Bearer Token
> Returns: a JSON includes user's data if ok
4. Logging out:
``` POST domain/api/v1/auth/logout ```
> Headers: Bearer Token
> Returns: a JSON with ok message
5. Reseting password:
``` POST domain/api/v1/auth/reset-password ```
> Form data: password, password_confirmation
> Headers: Bearer Token
> Returns: a JSON includes new access_token if ok
6. Refreshing token:
``` POST domain/api/v1/auth/refresh ```
> Form data: password, password_confirmation
> Headers: Bearer Token
> Returns: a JSON includes new access_token if ok

## License
The MIT License

Copyright (c) [Phạm Trung Kiên]()

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.