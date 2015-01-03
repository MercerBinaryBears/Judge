<?php

use Judge\Models\ContestSummary\ContestSummary;

class ContestSummaryTest extends TestCase {

	public function setUp() {
		parent::setUp();

		// create two dummy contest summaries
		$this->createDummy('summary1');
		$this->createDummy('summary2');
	}

	protected function createDummy($dummy_name) {
		$this->$dummy_name = new ContestSummary();
		$this->$dummy_name->user = new User();
		$this->$dummy_name->user->username = '';
		$this->$dummy_name->penalty_points = 0;
		$this->$dummy_name->problems_solved = 0;
	}

	public function testProblemsSolvedTakesPrecedence() {
		$this->summary2->penalty_points = 0;
		$this->summary2->problems_solved = 1;

		$this->assertEquals(1, ContestSummary::compare($this->summary1, $this->summary2));

		$this->summary1->penalty_points = 20;
		$this->summary1->problems_solved = 1;
		$this->summary2->penalty_points = 0;
		$this->summary2->problems_solved = 0;

		$this->assertEquals(-1, ContestSummary::compare($this->summary1, $this->summary2));
	}

	public function testPenaltyPointsTakesSecondPrecedence() {
		$this->summary1->problems_solved = 1;
		$this->summary2->problems_solved = 1;
		$this->summary1->penalty_points = 10;
		$this->summary2->penalty_points = 20;

		$this->assertEquals(-1, ContestSummary::compare($this->summary1, $this->summary2));
		
		$this->summary1->penalty_points = 20;
		$this->summary2->penalty_points = 10;

		$this->assertEquals(1, ContestSummary::compare($this->summary1, $this->summary2));
	}

	public function testUsernameIsThirdPrecedence() {
		$this->summary1->user->username = 'Apple';
		$this->summary2->user->username = 'Banana';

		$this->assertEquals(-1, ContestSummary::compare($this->summary1, $this->summary2));
		
		$this->summary1->user->username = 'Banana';
		$this->summary2->user->username = 'Apple';

		$this->assertEquals(1, ContestSummary::compare($this->summary1, $this->summary2));

	}
}
