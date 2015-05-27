<?php

use Illuminate\Support\Facades\App;
use Laracasts\TestDummy\Factory;

class DbMessageRepositoryTest extends DbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->repo = App::make('Judge\Repositories\MessageRepository');
    }

    public function testAllGlobalWithNoMatches()
    {
        Factory::create('message', ['is_global' => false]);
        $this->assertCount(0, $this->repo->allGlobal());
    }

    public function testAllGlobalWithMatches()
    {
        Factory::create('message', ['is_global' => true]);
        $this->assertCount(1, $this->repo->allGlobal());
    }

    public function testUnrespondedWithNoMatches()
    {
        // the message by default has a responder
        Factory::create('message', ['is_global' => false]);
        $this->assertCount(0, $this->repo->unresponded());
    }

    public function testUnrespondedWithMatches()
    {
        // Create a message with no responder
        Factory::create('message', ['is_global' => false, 'responder_id' => null]);
        $this->assertCount(1, $this->repo->unresponded());
    }
}
