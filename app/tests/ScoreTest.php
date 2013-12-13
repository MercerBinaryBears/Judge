<?php

use \Mockery;
use Illuminate\Database\Eloquent\Collection as Collection;

class ScoreTest extends TestCase {

	protected function createCollection($class_name, $models) {
		$ary = array();
		foreach($models as $m) {
			$model_instance = new $class_name;
			$model_instance->unguard();
			$model_instance->fill($m);
			$ary[] = $model_instance;
		}
		return Collection::make($ary);
	}

	public function setUp() {
		parent::setUp();

		// some constants
		$this->correct_solution_state_id = 1;

		// mock the repositories	
		$this->solution_repository = Mockery::mock('SolutionRepository');
		App::instance('SolutionRepository', $this->solution_repository);

		$this->solution_state_repository = Mockery::mock('SolutionStateRepository');
		$this->solution_state_repository
			->shouldReceive('firstCorrectId')
			->zeroOrMoreTimes()
			->andReturn(1);
		App::instance('SolutionStateRepository', $this->solution_state_repository);

		$this->contest_repository = Mockery::mock('ContestRepository');
		$raw_problems = array(
			array('id' => 1),
			array('id' => 2)
		);
		$this->contest_repository
			->shouldReceive('problemsForContest')
			->zeroOrMoreTimes()
			->andReturn( $this->createCollection('Problem', $raw_problems));
		App::instance('ContestRepository', $this->contest_repository);

		$this->user = new User;
	}

	public function testProblemsSolvedOnlyCountsProblemsInASolvedState() {
		$ary = array(
			array('problem_id' => 1, 'solution_state_id' => $this->correct_solution_state_id),
			array('problem_id' => 2, 'solution_state_id' => $this->correct_solution_state_id+1)
		);

		$this->solution_repository
			->shouldReceive('forUserInContest')
			->zeroOrMoreTimes()
			->andReturn( $this->createCollection('Solution', $ary) );

		$this->assertEquals(1, $this->user->problemsSolved()); 
	}

	public function testProblemsSolvedOnlyCountsASingleProblemOnce() {
		$ary = array(
			array('problem_id' => 1, 'solution_state_id' => $this->correct_solution_state_id),
			array('problem_id' => 1, 'solution_state_id' => $this->correct_solution_state_id+1)
		);

		$this->solution_repository
			->shouldReceive('forUserInContest')
			->zeroOrMoreTimes()
			->andReturn( $this->createCollection('Solution', $ary) );

		$this->assertEquals(1, $this->user->problemsSolved());

	}

	/*
	 * Tests 
	 * - Calculation of the number of incorrect submissions for a single problem
	 * - Calculation of the score for a single problem is correct
	 *    - Incorrect submissions with 0 correct give 0 penalty points
	 *    - n Incorrect submissions with k correct gives n penalty points, plus submission time
	 * - Calculation of the score for an entire problem set is correct
	 *    - Sum of all penalty points for every problem
	 */
}
