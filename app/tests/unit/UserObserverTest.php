<?php

use Judge\Models\User;
use Judge\Observers\UserObserver;

class UserObserverTest extends TestCase
{
    public function testSaving()
    {
        $user = new User([
            'username' => 'test',
            'password' => 'password'
        ]);

        $observer = new UserObserver();
        $observer->saving($user);

        $this->assertNotNull($user->api_key);
        $this->assertNotEquals('password', $user->password);
    }
}
