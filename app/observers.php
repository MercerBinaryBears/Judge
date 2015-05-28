<?php
// Set api keys + hash passwords for new/updated users
Judge\Models\User::observer(new Judge\Observers\UserObserver());
