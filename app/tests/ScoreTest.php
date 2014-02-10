<?php

use \Mockery;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as Collection;

class ScoreTest extends TestCase {

	public function setUp() {
		parent::setUp();

		// some constants
		$this->correct_solution_state_id = 1;

		// mock the repositories	
		$this->solution_repository = Mockery::mock('SolutionRepository');
		App::instance('SolutionRepository', $this->solution_repository);

		$this->mockSolutionStates();

		$this->mockContest();

		$this->user = new User;

		// create a fake problem to use throughout the tests
		$this->problem = new Problem();
		$this->problem->unguard();
		$this->problem->id = 1;
	}

	public function testSolvedProblemDoesntCountUnsolved() {
		$this->mockSolutions(
			1, -1, '2013-01-01 01:02:00',
			1, -1, '2013-01-01 01:02:00'
		);
		
		$this->assertEquals(0, $this->user->solvedProblem($this->problem)); 
	}

	public function testProblemsSolvedOnlyCountsASingleProblemOnce() {
		$this->mockSolutions(
			1, $this->correct_solution_state_id, '2013-01-01 01:02:00',
			1, $this->correct_solution_state_id, '2013-01-01 01:02:00'
		);

		$this->assertEquals(1, $this->user->solvedProblem($this->problem));
	}

	public function testProblemsSolvedCountsEveryProblem() {
		$this->mockSolutions(
			1, $this->correct_solution_state_id, '2013-01-01 01:02:00',
			2, $this->correct_solution_state_id, '2013-01-01 01:02:00'
		);

		$this->assertEquals(2, $this->user->problemsSolved());
	}

	public function testIncorrectSubmissionsForProblemCountsOneIfOne() {
		$this->mockSolutions(
			1, $this->correct_solution_state_id, '2013-01-01 01:02:00',
			1, -1, '2013-01-01 00:00:00'
		);

		$this->assertEquals(1, $this->user->incorrectSubmissionCountForProblem($this->problem));
	}

	public function testIncorrectSubmissionForProblemCountsNoneIfNone() {
		$this->mockSolutions(
			1, $this->correct_solution_state_id, '2013-01-01 01:02:00',
			1, $this->correct_solution_state_id, '2013-01-01 00:00:00'
		);

		$this->assertEquals(0, $this->user->incorrectSubmissionCountForProblem($this->problem));
	}

	public function testIncorrectSubmissionsForProblemCountsTwoIfTwo() {
		$this->mockSolutions(
			1, -1, '2013-01-01 01:02:00',
			1, -1, '2013-01-01 00:00:00'
		);

		$this->assertEquals(2, $this->user->incorrectSubmissionCountForProblem($this->problem));
		
	}

	public function testEarliestCorrect() {
		$this->mockSolutions(
			1, $this->correct_solution_state_id, '2013-01-01 01:02:00',
			1, $this->correct_solution_state_id, '2013-01-01 00:00:00'
		);

		$solution = $this->user->earliestCorrectSolutionForProblem($this->problem);

		$this->assertEquals('00:00:00', $solution->created_at->format('H:i:s'));
	}

	public function testEarliestCorrectSelectsCorrectProblem() {
		$this->mockSolutions(
			1, $this->correct_solution_state_id, '2013-01-01 01:02:00',
			2, $this->correct_solution_state_id, '2013-01-01 00:00:00'
		);

		$solution = $this->user->earliestCorrectSolutionForProblem($this->problem);

		$this->assertEquals('01:02:00', $solution->created_at->format('H:i:s'));
	}

	public function testScoreForNoCorrects() {

		// mock out the current contest query as well
		$contest = new Contest();
		$contest->starts_at = new Carbon('2013-01-01 00:00:00');
		$this->contest_repository->shouldReceive('firstCurrent')->once()->andReturn($contest);

		$this->mockSolutions(
			1, -1, '2013-01-01 00:00:00',
			1, -1, '2013-01-01 00:00:00'
		);

		$this->assertEquals(0, $this->user->pointsForProblem($this->problem));

	}
	
	public function testScoreForCorrectProblem() {

		// mock out the current contest query as well
		$contest = new Contest();
		$contest->starts_at = new Carbon('2013-01-01 00:00:00');
		$this->contest_repository->shouldReceive('firstCurrent')->once()->andReturn($contest);

		$this->mockSolutions(
			1, -1, '2013-01-01 00:00:00',
			1, $this->correct_solution_state_id, '2013-01-01 01:02:00'
		);

		$this->assertEquals(20+62, $this->user->pointsForProblem($this->problem));

	}

	public function testTotalScore() {

		// mock out the score per problem function
		$this->user = Mockery::mock('User[pointsForProblem]');
		$this->user->shouldReceive('pointsForProblem')->twice()->andReturn(10);

		$this->assertEquals(20, $this->user->totalPoints(new Contest));
	}

	/**
	 * Helper function to mock the solution repository
	 */
	protected function mockSolutions($problem_id_1, $solution_state_id_1, $created_at_1, $problem_id_2, $solution_state_id_2, $created_at_2) {
		$ary = array(
			array(
				'problem_id' => $problem_id_1, 
				'solution_state_id' => $solution_state_id_1,
				'created_at' => new Carbon($created_at_1)
			),
			array(
				'problem_id' => $problem_id_2, 
				'solution_state_id' => $solution_state_id_2,
				'created_at' => new Carbon($created_at_2) 
			)
		);

		$this->solution_repository
			->shouldReceive('forUserInContest')
			->zeroOrMoreTimes()
			->andReturn( $this->createCollection('Solution', $ary) );
	}

	/**
	 * Creates a mock of the solution state repository
	 */
	protected function mockSolutionStates() {
		$this->solution_state_repository = Mockery::mock('SolutionStateRepository');
		$this->solution_state_repository
			->shouldReceive('firstCorrectId')
			->zeroOrMoreTimes()
			->andReturn(1);
		App::instance('SolutionStateRepository', $this->solution_state_repository);
	}

	/**
	 * Creates a mock of the contest repository
	 */
	protected function mockContest() {
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
	
	}
	
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
}
