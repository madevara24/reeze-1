cd ../var/www/reeze
git pull origin master

# Make sure everything up-to-date
composer install
php artisan migrate
./script/clear_cache.sh