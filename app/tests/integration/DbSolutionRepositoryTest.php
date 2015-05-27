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
