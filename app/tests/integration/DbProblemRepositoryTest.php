<?php

use Illuminate\Support\Facades\App;
use Laracasts\TestDummy\Factory;

class DbProblemRepositoryTest extends DbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->repo = App::make('Judge\Repositories\ProblemRepository');
    }

    public function testForContest()
    {
        Factory::create('problem');

        $this->assertCount(1, $this->repo->forContest());
    }

    public function testForContestWithPassedContest()
    {
        Factory::create('problem');
        $contest = Factory::create('contest');

        $this->assertCount(0, $this->repo->forContest($contest));
    }
}
