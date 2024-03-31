# GDSCMMU

## Pre-requisite

You will need PHP, Apache, MySQL, phpMyAdmin, composer to run this program <br/>
Get XAMPP stack package to eliminate all the hassles <br/>
[https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html)
[How to install laravel]

## Build instructions

[Get composer](https://getcomposer.org/download/)

```
git clone https://github.com/ronylee11/gdscmmu
cd gdscmmu
git submodule init
git submodule update
composer install
cp .env.example .env
php artisan migrate:refresh --seed 
php artisan storage:link
```

## How to Run the Program

Edit .env file that you've just copied <br/>
Replace the following environment variables according to ur locally installed MySQL (or XAMPP)

```
DB_USERNAME=
DB_PASSWORD=
```

In terminal

```
php artisan serve
```

Now you can view the page in [http://localhost:8000](http://localhost:8000)
