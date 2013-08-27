<?php

use Carbon\Carbon as Carbon;

class EmptyContestTest extends TestCase {

	public function setUp() {
		parent::setUp();

		// Update any active contests to be not-started
		$this->old_start_times = array();
		$this->contests = array();
		foreach(Contest::current()->get() as $contest) {
			$this->contests[] = $contest;
			$this->old_start_times[] = $contest->starts_at;
			$contest->starts_at = Carbon::now()->addDay()->format('Y-m-d H:i:s');
			$contest->save();
		}

		// I'm assuming that there actually is a contest. This may be too brittle
		$this->contest = Contest::current()->first();
	}

	public function tearDown() {
		for($i=0; $i<count($this->contests); $i++) {
			$this->contests[$i]->starts_at = $this->old_start_times[$i];
			$this->contests[$i]->save();
		}
	}

	/*
	 * just test that the query doesn't die for these
	 */

	public function testCurrentContestFailGracefully()
	{
		$result = Contest::current()->get();
		$this->assertTrue($result->count() == 0);
	}

	public function testCurrentProblemsFailGracefully()
	{
		Problem::forCurrentContest()->get();
	}

	public function testCurrentSolutionsFailGracefully() {
		Solution::forCurrentContest()->get();
	}
}