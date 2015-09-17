<?php

use Judge\Repositories\ContestRepository;
use Laracasts\TestDummy\Factory;

class DbContestRepositoryTest extends DbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->repo = new ContestRepository();
    }

    public function testProblemsForContestWithNoContest()
    {
        $this->assertCount(0, $this->repo->problemsForContest());
    }

    public function testTeamsForContestWithNoTeams()
    {
        $contest = Factory::create('contest');
        $user = Factory::create('judge');
        $contest->users()->attach($user->id);
        $this->assertCount(0, $this->repo->teamsForContest());
    }

    public function testTeamsForContestWithTeams()
    {
        $contest = Factory::create('contest');
        $user = Factory::create('team');
        $contest->users()->attach($user->id);
        $this->assertCount(1, $this->repo->teamsForContest());
    }
}
