# create Laravel autoloader
php artisan optimize

# Make sure the database exists
touch app/database/production.sqlite

# Migrate
php artisan migrate

# Publish assets
php artisan asset:publish

# zip up the judge client
rm -f public/judge_client.zip && cd app/library/scripts && zip -r ../../../public/judge_client.zip *
