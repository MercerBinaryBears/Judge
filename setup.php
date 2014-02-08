<?php
// create Laravel autoloader
echo `php artisan optimize`;

// Make sure the database exists
echo `touch app/database/production.sqlite`;

// Migrate
echo `php artisan migrate`;

// Publish assets
echo `php artisan asset:publish`;
?>
