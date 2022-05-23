<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="250">
    </a>
</p>

# Laravel 9 Datatables
simple CRUD project created with laravel v9 with datatables

## Installation

* clone this project
* Create .env file `cp .env.example .env`
* edit config database and mail in .env file
* Install composer package `composer install` or `composer update`
* Install npm package `npm install` or `npm update`
* Run laravel Mix `npm run dev` or `npm run production`
* create key and create storage
```
php artisan key:generate
php artisan storage:link
```
* for optimize server production `composer run-script optimize-prod`
* for optimize server development `composer run-script optimize-dev`
* run Migration and Seeder `php artisan migrate:fresh --seed`
* run server with php artisan
```
php artisan serve --port=8080
```
* run server with php native
```
php -S localhost:8080 -t public
```
* done, just try run your project in browser to `http://127.0.0.1:8080`
* **nginx server is recommended**

## Function List

* [x] User CRUD
* [x] Laravel Migrate and Seeder
* [x] Laravel Validation
* [x] DataTables ([yajra](https://github.com/yajra/laravel-datatables))
* [x] Bootstrap 5
* [x] Fortawesome 6