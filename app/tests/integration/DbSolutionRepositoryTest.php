<?php

use Carbon\Carbon;
use Judge\Models\User;
use Judge\Models\Contest;
use Judge\Models\Problem;
use Judge\Models\Solution;
use Judge\Models\SolutionState;
use Judge\Repositories\SolutionRepository;

class DbSolutionRepositoryTest extends DbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->repo = \App::make('Judge\Repositories\SolutionRepository');
    }

    protected function stubProblem()
    {
        $contest = Contest::create([
            'name' => 'test contest',
            'starts_at' => Carbon::yesterday(),
            'ends_at' => Carbon::tomorrow()
        ]);

        return Problem::create([
            'name' => 'test problem',
            'contest_id' => $contest->id,
            'judging_input' => 'asdf',
            'judging_output' => 'asdf',
        ]);
    }

    public function testHasCorrectSolutionFromUser()
    {
        $problem = $this->stubProblem();

        $solution = Solution::create([
            'problem_id' => $problem->id,
            'user_id' => User::first()->id,
            'solution_code' => 'asdf',
            'language_id' => 1,
            'solution_filename' => 'asf',
            'solution_state_id' => SolutionState::whereIsCorrect(true)->firstOrFail()->id
        ]);

        $this->assertTrue($this->repo->hasCorrectSolutionFromUser(User::first(), $problem));
    }

    public function testHasCorrectSolutionFromUserWithNoMatch()
    {
        $problem = $this->stubProblem();

        $solution = Solution::create([
            'problem_id' => $problem->id,
            'user_id' => User::first()->id,
            'solution_code' => 'asdf',
            'solution_filename' => 'asdf',
            'language_id' => 1,
            'solution_state_id' => SolutionState::wherePending(true)->firstOrFail()->id + 1
        ]);

        $this->assertFalse($this->repo->hasCorrectSolutionFromUser(User::first(), $problem));
    }
}
