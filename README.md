```bash
composer create-project laravel/laravel^12.0 srs_project
php artisan migrate
php artisan migrate:fresh

php artisan make:middleware AdminRedirect
php artisan make:middleware AdminAuthenticate

php artisan make:controller LoginController
php artisan make:view login


php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan session:clear
php artisan optimize:clear


//builtin auth
composer require laravel/ui
php artisan ui bootstrap --auth
npm install && npm run dev





//IDE helper
composer require --dev barryvdh/laravel-ide-helper
php artisan ide-helper:generate

```