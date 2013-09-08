<?php

class ApiTest extends TestCase {
	public function setUp() {
		parent::setUp();

		// We have to re-run boot in-order for model events to rerun
		User::boot();

		// add two judges
		$this->name1 = User::generateApiKey();
		$this->name2 = User::generateApiKey();
		$this->judge1 = $this->createJudge($this->name1);
		$this->judge2 = $this->createJudge($this->name2);

		// get the solution we want to work with
		// we'll just attempt with the first solution in the database,
		// which we assume to be a seed value anyways
		// TODO: Make this more robust instead of ASSUMING!
		$this->solution = Solution::firstOrFail();

		// update the state to non-judged...
		$this->resetSolution();
		$this->assertNull($this->solution->claiming_judge_id, 'A judge has already claimed this problem, although we reset it!');
		$this->assertEquals(SolutionState::pending()->id, $this->solution->solution_state_id, 'Problem is not in a pending state');

		Route::enableFilters();
	}

	public function tearDown() {
		$this->judge1->delete();
		$this->judge2->delete();

		// unset everything
		$things_to_unset = array('name1', 'name2', 'judge1', 'judge2', 'solution', 'key1', 'key2');
		foreach($things_to_unset as $key) {
			$this->$key = null;
		}
	}

	/**
	 * Creates a temporary judge
	 *
	 * @param string $name The judge's username
	 * @return User The judge user that was created
	 */
	protected function createJudge($name) {
		$user = new User();
		$user->username = $name;
		$user->password = 'password';
		$user->admin = false;
		$user->judge = true;
		$user->team = false;
		$user->save();

		return $user;
	}

	/**
	 * Re-queries the current solution, to update the fields (if they've
	 * been updated via a query)
	 */
	protected function findSolution() {
		$this->solution = Solution::find($this->solution->id);
	}

	/**
	 * Resets a solution back to its unjudged state, with no claiming judge
	 */
	protected function resetSolution() {
		$this->findSolution();
		$this->solution->solution_state_id = SolutionState::pending()->id;
		$this->solution->claiming_judge_id = null;
		$this->solution->save();
		$this->findSolution();
	}

	/**
	 * Calls a named route, and asserts that a non-200-block error code was provided
	 */
	protected function assertStatusCodeForRoute($method, $route, $params, $query, $expected_code) {
		try {
			$this->route($method, $route, $params, $query);

			$this->fail("Expected $expected_code got 200");
		}
		catch(Symfony\Component\HttpKernel\Exception\HttpException $e) {
			$this->assertEquals($expected_code, $e->getStatusCode());
		}
	}

	/**
	 * Tests that we get a 200 on a route with a valid token, and a 401 with no token
	 */
	public function testCanPing() {
		// check that we get blocked
		$this->assertStatusCodeForRoute('GET', 'api_ping', array(), array(), 401);

		// check that we are allowed through
		$this->route('GET', 'api_ping', array(), array('api_key'=>$this->judge1->api_key));
	}

	/**
	 * Tests that once a single judge claims a problem, a second judge cannot claim it.
	 */
	public function testClaim()
	{
		// login as judge 1 and claim the submission
		$this->route('GET', 'api_claim', array($this->solution->id), array('api_key'=>$this->judge1->api_key));
		$this->assertResponseOk();

		// // login as judge 2 and attempt to claim again
		$this->assertStatusCodeForRoute('GET', 'api_claim', array($this->solution->id), array('api_key'=>$this->judge2->api_key), 403);
	}

	/**
	 * Test that when a judge cancels a problem, the claiming judge for a solution is set to null again
	 * Also, make sure that only the claiming judge can edit this problem
	 */
	public function testUnclaim() {
		// Login as judge 1 and claim the solution
		$this->route('GET', 'api_claim', array($this->solution->id), array('api_key'=>$this->judge1->api_key));

		// Now login as judge 2 and attempt to unclaim on behalf of 1
		$this->assertStatusCodeForRoute('GET', 'api_unclaim', array($this->solution->id), array('api_key'=>$this->judge2->api_key), 403);

		// check that the claiming judge is still judge 1
		$this->findSolution();
		$this->assertEquals($this->judge1->id, $this->solution->claiming_judge_id, "Judge 2 succesfully unclaimed judge 1 from the solution");

		// now let judge 1 unclaim
		$this->route('GET', 'api_unclaim', array($this->solution->id), array('api_key'=>$this->judge1->api_key));

		// verify that there is now no claiming judge
		$this->findSolution();
		$this->assertNull($this->solution->claiming_judge_id, "Judge 1 did not successfully unclaim his problem");
	}

	/**
	 * Tests correct permissions on update
	 */
	public function testUpdate() {

		$judge1_data = array(
			'api_key' => $this->judge1->api_key,
			'solution_state_id' => 1
			);
		$judge2_data = array(
			'api_key' => $this->judge2->api_key,
			'solution_state_id' => 2
			);

		// make sure they provide an api key
		$this->assertStatusCodeForRoute('POST', 'api_update', array($this->solution->id), array('solution_state_id'=>1), 401);

		// check that they can't claim a problem that hasn't been claimed
		$this->assertStatusCodeForRoute('POST', 'api_update', array($this->solution->id), $judge1_data, 403);

		// claim it and then make sure we can update
		$this->route('GET', 'api_claim', array($this->solution->id), $judge1_data);
		$this->route('POST', 'api_update', array($this->solution->id), $judge1_data);
		$this->findSolution();
		$this->assertEquals($this->solution->solution_state_id, 1);

		// make sure judge 2 can't claim it
		$this->assertStatusCodeForRoute('POST', 'api_update', array($this->solution->id), $judge2_data, 403);
		$this->findSolution();
		$this->assertEquals($this->solution->solution_state_id, 1);

		// now, let judge 1 update again, and make sure everything persists
		$judge1_data['solution_state_id'] = 3;
		$this->route('POST', 'api_update', array($this->solution->id), $judge1_data);
		$this->findSolution();
		$this->assertEquals($this->solution->solution_state_id, 3);
	}
}