<?php

use Judge\Models\Contest;

class ContestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->contest = new Contest();
    }

    public function testMessages()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $this->contest->messages());
    }
    
    public function testProblems()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $this->contest->problems());
    }

    public function testUsers()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $this->contest->users());
    }
}
