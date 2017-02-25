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
        Factory::create('message');
        $this->assertCount(0, $this->repo->allGlobal());
    }

    public function testAllGlobalWithMatches()
    {
        Factory::create('global_message');
        $this->assertCount(1, $this->repo->allGlobal());
    }

    public function testAllGlobalForCorrectSorting()
    {
        $contest = Factory::create('contest');
        $message_1 = Factory::create('global_message', [
            'created_at' => Carbon::now()->subDay(),
            'contest_id' => $contest->id
        ]);
        $message_2 = Factory::create('global_message', [
            'created_at' => Carbon::now(),
            'contest_id' => $contest->id
        ]);

        $results = $this->repo->allGlobal();

        $this->assertCount(2, $results);

        // Results should appear in reverse chronological order
        $this->assertEquals($message_2->id, $results[0]->id);
        $this->assertEquals($message_1->id, $results[1]->id);
    }

    public function testAllGlobalForDifferentContest()
    {
        $message_1 = Factory::create('global_message');

        $otherContest = Factory::create('contest');

        $results = $this->repo->allGlobal($otherContest);

        $this->assertCount(0, $results);
    }

    public function testAllGlobalWithMissingContest()
    {
        $results = $this->repo->allGlobal();

        $this->assertCount(0, $results);
    }

    public function testUnrespondedWithNoMatches()
    {
        // the message by default has a responder
        Factory::create('message');
        $this->assertCount(0, $this->repo->unresponded());
    }

    public function testUnrespondedWithMatches()
    {
        // Create a message with no responder
        $message = Factory::create('message', ['responder_id' => null]);
        $this->assertCount(1, $this->repo->unresponded());
    }

    public function testUnrespondedForCorrectSorting()
    {
        $contest = Factory::create('contest');
        $message_1 = Factory::create('message', ['responder_id' => null, 'created_at' => Carbon::now()->subDay(), 'contest_id' => $contest->id]);
        $message_2 = Factory::create('message', ['responder_id' => null, 'created_at' => Carbon::now(), 'contest_id' => $contest->id]);

        $results = $this->repo->unresponded();

        // Results should appear in chronological order
        $this->assertEquals($message_1->id, $results[0]->id);
        $this->assertEquals($message_2->id, $results[1]->id);
    }

    public function testUnrespondedForOtherContest()
    {
        $message_1 = Factory::create('message', ['responder_id' => null]);

        $otherContest = Factory::create('contest');

        $results = $this->repo->unresponded($otherContest);

        $this->assertCount(0, $results);
    }
}
