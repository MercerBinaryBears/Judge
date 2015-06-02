<?php

use Judge\Models\User;
use Laracasts\TestDummy\Factory;

class DbUserTest extends DbTestCase
{
    public function testCreatingEvent()
    {
        $user = Factory::create('team', ['password' => 'PASSWORD', 'api_key' => '']);

        $this->assertNotEquals('PASSWORD', $user->password);
    }
}
