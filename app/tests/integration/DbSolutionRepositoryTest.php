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

    public function testJudgeableForContestForCorrectSorting()
    {
        $pending_state = SolutionState::wherePending(true)->first()->id;

        $problem = Factory::create('problem');

        $solution_1 = Factory::create('solution', [
            'problem_id' => $problem->id, 
            'solution_state_id' => $pending_state, 
            'created_at' => Carbon::now()->subHour()
        ]);

        $solution_2 = Factory::create('solution', [
            'problem_id' => $problem->id,
            'solution_state_id' => $pending_state,
        ]);

        $results = $this->repo->judgeableForContest();

        $this->assertCount(2, $results);

        // Judged solutions should be in chronological order
        $this->assertEquals($solution_1->id, $results[0]->id);
        $this->assertEquals($solution_2->id, $results[1]->id);
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

    public function testClaimedByJudgeInContestForCorrectSorting()
    {
        $judge = Factory::create('judge');

        $solution_1 = Factory::create('solution', [
            'claiming_judge_id' => $judge->id, 
            'created_at' => Carbon::now()->subHour()
        ]);

        $solution_2 = Factory::create('solution', [
            'claiming_judge_id' => $judge->id, 
        ]);

        $results = $this->repo->claimedByJudgeInContest();

        $this->assertCount(2, $results);

        // Claimed solutions should be in reverse chronological order
        $this->assertEquals($solution_1->id, $results[1]->id);
        $this->assertEquals($solution_2->id, $results[0]->id);
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

    public function testForUserInContestForCorrectSorting()
    {
        $pending_state = SolutionState::wherePending(true)->first()->id;

        $problem = Factory::create('problem');
        $user = Factory::create('team');

        $solution_1 = Factory::create('solution', [
            'problem_id' => $problem->id, 
            'solution_state_id' => $pending_state, 
            'created_at' => Carbon::now()->subHour(),
            'user_id' => $user->id,
        ]);

        $solution_2 = Factory::create('solution', [
            'problem_id' => $problem->id,
            'solution_state_id' => $pending_state,
            'user_id' => $user->id,
        ]);

        $results = $this->repo->forUserInContest($user, $problem->contest);

        $this->assertCount(2, $results);

        // Judged solutions should be in chronological order
        $this->assertEquals($solution_1->id, $results[0]->id);
        $this->assertEquals($solution_2->id, $results[1]->id);
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

    public function testIncorrectSubmissionCountWithIncorrectSubmission()
    {
        $solution = Factory::create('solution', [
            'solution_state_id' => SolutionState::whereIsCorrect(false)->first()->id
        ]);

        $this->assertEquals(1, $this->repo->incorrectSubmissionCountFromUserFromProblem($solution->user, $solution->problem));
    }

    public function testIncorrectSubmissionCountWithPending()
    {
        $solution = Factory::create('solution', [
            'solution_state_id' => SolutionState::wherePending(true)->first()->id
        ]);

        $this->assertEquals(0, $this->repo->incorrectSubmissionCountFromUserFromProblem($solution->user, $solution->problem));
    }

    public function testIncorrectSubmissionCountWithNoIncorrect()
    {
        $solution = Factory::create('solution', [
            'solution_state_id' => SolutionState::whereIsCorrect(true)->first()->id
        ]);

        $this->assertEquals(0, $this->repo->incorrectSubmissionCountFromUserFromProblem($solution->user, $solution->problem));
    }

    public function testEarliestCorrectSolutionFromUserForProblemWithMultipleMatches()
    {
        $team = Factory::create('team');
        $problem = Factory::create('problem');
        $firstSolution = Factory::create('solution', [
            'problem_id' => $problem->id,
            'user_id' => $team->id,
            'solution_state_id' => SolutionState::whereIsCorrect(true)->first()->id,
            'created_at' => Carbon::now()->subMinute()
        ]);
        $secondSolution = Factory::create('solution', [
            'problem_id' => $problem->id,
            'user_id' => $team->id,
            'solution_state_id' => SolutionState::whereIsCorrect(true)->first()->id,
            'created_at' => Carbon::now()
        ]);

        $this->assertEquals($firstSolution->id, $this->repo->earliestCorrectSolutionFromUserForProblem($team, $problem)->id);
    }

    public function testEarliestCorrectSolutionFromUserForProblemWithNoCorrect()
    {
        $solution = Factory::create('solution', [
            'solution_state_id' => SolutionState::whereIsCorrect(false)->first()->id
        ]);

        $this->assertNull($this->repo->earliestCorrectSolutionFromUserForProblem($solution->user, $solution->problem));
    }
}
