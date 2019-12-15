cd ../var/www/reeze
git pull origin master

# Make sure everything up-to-date
composer install
php artisan migrate
./scripts/clear_cache.sh