<?php

use \Mockery;
use Carbon\Carbon;
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

	public function testSolvedProblemDoesntCountUnsolved() {
		$ary = array(
			array('problem_id' => 1, 'solution_state_id' => -1),
		);

		$this->solution_repository
			->shouldReceive('forUserInContest')
			->zeroOrMoreTimes()
			->andReturn( $this->createCollection('Solution', $ary) );

		$p = new Problem();
		$p->unguard();
		$p->id = 1;

		$this->assertEquals(0, $this->user->solvedProblem($p)); 
	}

	public function testProblemsSolvedOnlyCountsASingleProblemOnce() {
		$ary = array(
			array('problem_id' => 1, 'solution_state_id' => $this->correct_solution_state_id),
			array('problem_id' => 1, 'solution_state_id' => $this->correct_solution_state_id)
		);

		$this->solution_repository
			->shouldReceive('forUserInContest')
			->zeroOrMoreTimes()
			->andReturn( $this->createCollection('Solution', $ary) );

		$p = new Problem();
		$p->unguard();
		$p->id = 1;

		$this->assertEquals(1, $this->user->solvedProblem($p));
	}

	public function testProblemsSolvedCountsEveryProblem() {
		$ary = array(
			array('problem_id' => 1, 'solution_state_id' => $this->correct_solution_state_id),
			array('problem_id' => 2, 'solution_state_id' => $this->correct_solution_state_id)
		);
		$this->solution_repository
			->shouldReceive('forUserInContest')
			->zeroOrMoreTimes()
			->andReturn( $this->createCollection('Solution', $ary) );

		$this->assertEquals(2, $this->user->problemsSolved());
	}

	public function testIncorrectSubmissionsForProblem() {
		$ary = array(
			array('problem_id' => 1, 'solution_state_id' => $this->correct_solution_state_id),
			array('problem_id' => 1, 'solution_state_id' => $this->correct_solution_state_id+1)
		);

		$this->solution_repository
			->shouldReceive('forUserInContest')
			->zeroOrMoreTimes()
			->andReturn( $this->createCollection('Solution', $ary) );

		$p = new Problem();
		$p->unguard();
		$p->id = 1;

		$this->assertEquals(1, $this->user->incorrectSubmissionCountForProblem($p));
	}

	public function testEarliestCorrect() {
		$ary = array(
			array(
				'problem_id' => 1, 
				'solution_state_id' => $this->correct_solution_state_id,
				'created_at' => new Carbon('2013-01-01 01:02:00')
			),
			array(
				'problem_id' => 1, 
				'solution_state_id' => $this->correct_solution_state_id,
				'created_at' => new Carbon('2013-01-01 00:00:00')
			),
		);

		$this->solution_repository
			->shouldReceive('forUserInContest')
			->zeroOrMoreTimes()
			->andReturn( $this->createCollection('Solution', $ary) );

		$p = new Problem();
		$p->unguard();
		$p->id = 1;

		$solution = $this->user->earliestCorrectSolutionForProblem($p);

		$this->assertEquals('00:00:00', $solution->created_at->format('H:i:s'));
	}

	public function testScoreForNoCorrects() {

		// mock out the current contest query as well
		$contest = new Contest();
		$contest->starts_at = new Carbon('2013-01-01 00:00:00');
		$this->contest_repository->shouldReceive('firstCurrent')->once()->andReturn($contest);

		$ary = array(
			array(
				'problem_id' => 1, 
				'solution_state_id' => -1,
				'created_at' => new Carbon('2013-01-01 00:00:00')
			),
			array(
				'problem_id' => 1, 
				'solution_state_id' => -1,
				'created_at' => '2013-01-01 01:02:00' 
			)
		);

		$this->solution_repository
			->shouldReceive('forUserInContest')
			->zeroOrMoreTimes()
			->andReturn( $this->createCollection('Solution', $ary) );

		$p = new Problem();
		$p->unguard();
		$p->id = 1;

		$this->assertEquals(0, $this->user->pointsForProblem($p));

	}
	
	public function testScoreForCorrectProblem() {

		// mock out the current contest query as well
		$contest = new Contest();
		$contest->starts_at = new Carbon('2013-01-01 00:00:00');
		$this->contest_repository->shouldReceive('firstCurrent')->once()->andReturn($contest);

		$ary = array(
			array(
				'problem_id' => 1, 
				'solution_state_id' => -1,
				'created_at' => new Carbon('2013-01-01 00:00:00')
			),
			array(
				'problem_id' => 1, 
				'solution_state_id' => $this->correct_solution_state_id,
				'created_at' => '2013-01-01 01:02:00' 
			)
		);

		$this->solution_repository
			->shouldReceive('forUserInContest')
			->zeroOrMoreTimes()
			->andReturn( $this->createCollection('Solution', $ary) );

		$p = new Problem();
		$p->unguard();
		$p->id = 1;

		$this->assertEquals(20+62, $this->user->pointsForProblem($p));

	}

	/*
	 * Tests 
	 * - Calculation of the score for an entire problem set is correct
	 *    - Sum of all penalty points for every problem
	 */
}
