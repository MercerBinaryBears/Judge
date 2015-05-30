<?php namespace Judge\Observers;

use \Hash;
use Judge\Models\User;

class UserObserver
{
    public function saving(User $user)
    {
        if (!$user->api_key) {
            $user->api_key = User::generateApiKey();
        }

        if (Hash::needsRehash($user->password)) {
            $user->password = Hash::make($user->password);
        }
    }
}
