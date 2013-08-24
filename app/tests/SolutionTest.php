<?php

class SolutionTest extends TestCase {

	public function setUp() {
		parent::setUp();

		// add two judges
		$this->judge1 = $this->createJudge('judge1');
		$this->judge2 = $this->createJudge('judge2');

		// get the solution we want to work with
		// we'll just attempt with the first solution in the database,
		// which we assume to be a seed value anyways
		// TODO: Make this more robust instead of ASSUMING!
		$this->solution = Solution::firstOrFail();

		// update the state to non-judged...
		$this->resetSolution();
		$this->assertNull($this->solution->claiming_judge_id, 'A judge has already claimed this problem, although we reset it!');
		$this->assertEquals(SolutionState::pending()->id, $this->solution->solution_state_id, 'Problem is not in a pending state');
	}

	public function tearDown() {
		// TODO: figure out a way to not have to use
		// user_groups in Sentry, so we can just call $this->judge1->delete()
		DB::table('users')->where('username', 'LIKE', 'judge%')->delete();
	}

	/**
	 * Creates a temporary judge
	 *
	 * @param string $name The judge's username
	 * @return User The judge user that was created
	 */
	private function createJudge($name) {
		Sentry::getUserProvider()->create(array(
			'username'=>$name,
			'password'=>'password',
			'admin'=>false,
			'judge'=>true,
			'team'=>false,
			));
		return Sentry::getUserProvider()->findByLogin($name);
	}

	/**
	 * Re-queries the current solution, to update the fields (if they've
	 * been updated via a query)
	 */
	private function findSolution() {
		$this->solution = Solution::find($this->solution->id);
	}

	/**
	 * Resets a solution back to its unjudged state, with no claiming judge
	 */
	private function resetSolution() {
		$this->findSolution();
		$this->solution->solution_state_id = SolutionState::pending()->id;
		$this->solution->claiming_judge_id = null;
		$this->solution->save();
		$this->findSolution();
	}

	public function testJudgeClaimsOnlyUnclaimedOrOwned() {
		Sentry::login($this->judge1, false);

		// judge1 should be able to alter unclaimed
		$this->assertTrue($this->solution->canBeAltered(), 'Judge cannot claim unclaimed problem');

		// manually claim
		$this->solution->claiming_judge_id = $this->judge1->id;

		// judge1 should be able to alter ones he owns
		$this->assertTrue($this->solution->canBeAltered(), 'Judge cannot claim owned problem');

		// judge2 should not be able to alter someone else's problem
		Sentry::login($this->judge2, false);
		$this->assertFalse($this->solution->canBeAltered(), 'Judge can claim problem that is previously owned');
	}

	/**
	 * Tests that once a single judge claims a problem, a second judge cannot claim it.
	 */
	public function testClaim()
	{
		// login as judge 1 and claim the submission
		Sentry::login($this->judge1, false);
		$this->assertTrue($this->solution->claim(), 'Judge 1 could not claim an available problem');
		$this->assertEquals($this->solution->claiming_judge_id, $this->judge1->id, 'Claiming did not update to judges id');

		// login as judge 2 and attempt to claim again
		Sentry::login($this->judge2, false);
		$this->assertFalse($this->solution->claim(), 'Judge 2 was able to claim an already claimed problem');
		$this->assertEquals($this->solution->claiming_judge_id, $this->judge1->id, 'Claim changed judges id when it should not have');
	}

	/**
	 * Test that when a judge cancels a problem, the claiming judge for a solution is set to null again
	 * Also, make sure that only the claiming judge can edit this problem
	 */
	public function testUnclaim() {
		// Login as judge 1 and claim the solution
		Sentry::login($this->judge1, false);
		$this->assertTrue($this->solution->claim(), 'Judge 1 could not claim unclaimed problem');

		// Now login as judge 2 and attempt to unclaim on behalf of 1
		Sentry::login($this->judge2, false);
		$this->assertFalse($this->solution->unclaim(), 'Judge 2 claimed problem that was already owned');
		$this->assertEquals($this->solution->claiming_judge_id, $this->judge1->id, 'Judge 2 successfully unclaimed for Judge 1');

		// now let judge 1 unclaim
		Sentry::login($this->judge1, false);
		$this->assertTrue($this->solution->unclaim(), 'Judge 1 could not unclaim his problem');
		$this->assertNull($this->solution->claiming_judge_id, "Judge 1 did not successfully unclaim his problem");
	}

}