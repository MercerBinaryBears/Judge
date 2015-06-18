<?php

use Carbon\Carbon;
use Judge\Models\User;

class UserTest extends TestCase
{
    public function testGetAuthIdentifier()
    {
        $user = new User();
        $user->id = 1;
        $this->assertEquals(1, $user->getAuthIdentifier());
    }

    public function testGetAuthPassword()
    {
        $user = new User();
        $user->password = 'asdf';
        $this->assertEquals('asdf', $user->getAuthPassword());
    }

    public function testContests()
    {
        $user = new User();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $user->contests());
    }

    public function testProblems()
    {
        $user = new User();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $user->solutions());
    }

    public function testGenerateApiKey()
    {
        $user = new User();
        $result = $user->generateApiKey(60);
        $this->assertEquals(60, strlen($result));
    }

    public function testGetSetRememberToken()
    {
        $user = new User();
        $user->setRememberToken('ABC');
        $this->assertEquals('ABC', $user->getRememberToken());
    }

    public function testGetRememberTokenName()
    {
        $user = new User();
        $this->assertEquals('remember_token', $user->getRememberTokenName());
    }

    public function testGetReminderEmail()
    {
        $user = new User();
        $user->email = 'EMAIL';
        $this->assertEquals('EMAIL', $user->getReminderEmail());
    }

    public function testSentMessages()
    {
        $user = new User();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $user->sentMessages());
    }
}
