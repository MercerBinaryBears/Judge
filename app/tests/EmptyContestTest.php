<?php

class EmptyContestTest extends TestCase {

	public function setUp() {
		parent::setUp();

		// I'm assuming there is a single contest that is current, which may not be the case
		// TODO: make this test more robust, by not relying on the database seed
		$this->contest = Contest::current()->first();
		$this->old_starts_at = $this->contest->starts_at;
		$this->contest->starts_at = strtotime('now +1 days');
		$this->contest->save();
	}

	public function tearDown() {
		$this->contest->starts_at = $this->old_starts_at;
		$this->contest->save();
	}

	/*
	 * just test that the query doesn't die for these
	 */

	public function testCurrentContestFailGracefully()
	{
		Contest::current();
	}
}