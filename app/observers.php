<?php
// Set api keys + hash passwords for new/updated users
Judge\Models\User::observe(new Judge\Observers\UserObserver());
