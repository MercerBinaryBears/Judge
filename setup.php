<?php
// create Laravel autoloader
echo `php artisan optimize`;

// Make sure the database exists
echo `touch app/database/production.sqlite`;

// Migrate
echo `php artisan migrate`;

// Publish assets
echo `php artisan asset:publish`;

// zip up the judge client
echo `rm -f public/judge_client.zip && cd app/library/scripts && zip -r ../../../public/judge_client.zip *`;
?>
