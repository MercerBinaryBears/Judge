<?php

use Carbon\Carbon;
use Judge\Models\User;
use Judge\Models\Contest;
use Judge\Models\Problem;
use Judge\Models\Solution;
use Judge\Models\SolutionState;
use Judge\Repositories\SolutionRepository;
use Laracasts\TestDummy\Factory;

class DbSolutionRepositoryTest extends DbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->repo = \App::make('Judge\Repositories\SolutionRepository');
    }

    public function testFind()
    {
        $solution = Factory::create('solution');
        $this->assertNotNull($this->repo->find($solution->id));
    }

    public function testJudgeableForContestWithEmptyContest()
    {
        $contest = Factory::create('contest');

        $this->assertCount(0, $this->repo->judgeableForContest($contest));
    }

    public function testJudgeableForContest()
    {
        Factory::create('solution', [
            'solution_state_id' => SolutionState::wherePending(true)->first()->id,
        ]);

        $this->assertCount(1, $this->repo->judgeableForContest());
    }

    public function testClaimedByJudgeInEmptyContest()
    {
        $judge = Factory::create('judge');
        $contest = Factory::create('contest');
        $solution = Factory::create('solution', ['claiming_judge_id' => $judge->id]);

        $this->assertCount(0, $this->repo->claimedByJudgeInContest($judge, $contest));
    }

    public function testClaimedByJudgeInContest()
    {
        $judge = Factory::create('judge');
        Factory::create('solution', ['claiming_judge_id' => $judge->id]);

        $this->assertCount(1, $this->repo->claimedByJudgeInContest($judge));
    }

    public function testForUserInEmptyContest()
    {
        $contest = Factory::create('contest');
        $user = Factory::create('team');

        $this->assertCount(0, $this->repo->forUserInContest($user, $contest));
    }

    public function testForUserInContest()
    {
        $solution = Factory::create('solution');
        $contest = $solution->problem->contest;
        $user = $solution->user;

        $this->assertCount(1, $this->repo->forUserInContest($user, $contest));
    }

    public function testForUserInDefaultContest()
    {
        $solution = Factory::create('solution');
        $user = $solution->user;

        $this->assertCount(1, $this->repo->forUserInContest($user));
    }

    public function testHasCorrectSolutionFromUser()
    {
        $solution = Factory::create('solution', [
            'solution_state_id' => SolutionState::whereIsCorrect(true)->firstOrFail()->id
        ]);

        $this->assertTrue($this->repo->hasCorrectSolutionFromUser($solution->user, $solution->problem));
    }

    public function testHasCorrectSolutionFromUserWithNoMatch()
    {
        $solution = Factory::create('solution', [
            'solution_state_id' => SolutionState::wherePending(true)->firstOrFail()->id
        ]);

        $this->assertFalse($this->repo->hasCorrectSolutionFromUser($solution->user, $solution->problem));
    }
}
