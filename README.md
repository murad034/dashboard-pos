# IMREKE DashBoard Laravel 8 package

Start a Laravel 8 project with the AdminLTE installed.

<img src="public/img/config/dashboard.png">

# Installation

1) Create database.
2) Clone repository `git clone https://github.com/murad034/dashboard_pos.git`
3) Copy `.env.example` to `.env`
4) Set valid database credentials of env variables `DB_DATABASE`, `DB_HOST`, `DB_PORT`, `DB_USERNAME`, and `DB_PASSWORD`
5) Run `composer install`
6) Create symbolic link for AdminLTE (Run the commands as an administrator)

- windows example:
```php
mklink /d "C:\xampp\htdocs\laravel-adminlte\public\assets\adminlte" "C:\xampp\htdocs\laravel-adminlte\vendor\almasaeed2010\adminlte"
```

 - Linux example:    
    
```php
sudo ln -vs /home/{USERID}/web/vendor/almasaeed2010/adminlte /home/{USERID}/web/public/assets/adminlte
```
7) Run
```php
php artisan migrate
```
```php
php artisan db:seed
```
```php
php artisan key:generate
```
```php
php artisan serve
```
8) chmod -R 777 public/uploads/web-info/
9) Login: `help@ausittechdirect.com.au` Password: `#Au5T3chGR0up#`
