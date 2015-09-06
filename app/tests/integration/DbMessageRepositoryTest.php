<?php

use Carbon\Carbon;
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

    public function testAllGlobalForCorrectSorting()
    {
        $message_1 = Factory::create('message', ['is_global' => true, 'created_at' => Carbon::now()->subDay()]);
        $message_2 = Factory::create('message', ['is_global' => true, 'created_at' => Carbon::now()]);

        $results = $this->repo->allGlobal();

        // Results should appear in reverse chronological order
        $this->assertEquals($message_2, $results[0]);
        $this->assertEquals($message_1, $results[1]);
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

    public function testUnrespondedForCorrectSorting()
    {
        $message_1 = Factory::create('message', ['is_global' => false, 'responder_id' => null, 'created_at' => Carbon::now()->subDay()]);
        $message_2 = Factory::create('message', ['is_global' => false, 'responder_id' => null, 'created_at' => Carbon::now()]);

        $results = $this->repo->unresponded();

        // Results should appear in reverse chronological order
        $this->assertEquals($message_1->id, $results[0]->id);
        $this->assertEquals($message_2->id, $results[1]->id);
    }
}
