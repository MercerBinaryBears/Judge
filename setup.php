<?php
	// Make sure the database exists
	`touch app/database/production.sqlite`;
	`php artisan migrate`;
?>
