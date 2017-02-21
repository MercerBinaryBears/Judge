<?php

use Illuminate\Support\Facades\App;
use Laracasts\TestDummy\Factory;
use Judge\Models\Contest;

class DbUserRepositoryTest extends DbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->repo = App::make('Judge\Repositories\UserRepository');
    }

    public function testForContestWithPassedContest()
    {
        $team = Factory::create('team');
        $contest = Factory::create('contest');
        
        $contest->users()->attach($team->id);

        $found_contest = Contest::find($contest->id);

        $this->assertCount(1, $this->repo->forContest($found_contest));
    }

    public function testForContestWithNullContest()
    {
        $team = Factory::create('team');
        $contest = Factory::create('contest');
        
        $contest->users()->attach($team->id);

        $this->assertCount(1, $this->repo->forContest());
    }

    public function testForMissingContest()
    {
        $result = $this->repo->forContest();

        $this->assertInstanceOf('Illuminate\Support\Collection', $result);
        $this->assertCount(0, $result);
    }
}
