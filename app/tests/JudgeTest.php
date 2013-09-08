<?php

class JudgeTest extends TestCase {

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
		$this->judge1->delete();
		$this->judge2->delete();
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
	 * Attempts to update the current solution to the passed solution state
	 * id
	 */
	protected function attemptUpdate($solution_state_id) {
		$parameters = array(
			'solution_state_id' => $solution_state_id,
			);
		$this->route('POST', 'update_solution', array($this->solution->id), $parameters);
	}

	/**
	 * Tests that once a single judge claims a problem, a second judge cannot claim it.
	 */
	public function testAllowOnlyOneClaimer()
	{
		// login as judge 1 and claim the submission
		Auth::login($this->judge1);
		$this->route('GET', 'edit_solution', array($this->solution->id));
		$this->assertResponseOk();

		// login as judge 2 and attempt to claim again
		Auth::login($this->judge2);
		$this->route('GET', 'edit_solution', array($this->solution->id));
		$this->assertRedirectedToRoute('judge_index');
	}

	/**
	 * Tests that only the claiming judge can actually make an edit to a solution once he/she has claimed it
	 */
	public function testAllowOnlyOneEditter() {
		// login and claim the problem
		Auth::login($this->judge1);
		$this->solution->claiming_judge_id = $this->judge1->id;
		$this->solution->save();
		$this->attemptUpdate(1);

		// assert that the update carried through
		$this->findSolution();
		$this->assertEquals(1, $this->solution->solution_state_id, "The Judge was unable to update a problem");

		Auth::login($this->judge2);
		$this->attemptUpdate(2);

		// make sure the judge2's update didn't carry through
		$this->solution = Solution::find($this->solution->id);
		$this->assertEquals(1, $this->solution->solution_state_id, "A Judge was able to update an already claimed problem");
	}

	/**
	 * Tests that only the claiming judge for a problem can go back and edit it
	 */
	public function testAllowJudgeToReeditSolution() {
		// login as judge 1 and claim the submission
		Auth::login($this->judge1);
		$this->route('GET', 'edit_solution', array($this->solution->id));
		$this->assertResponseOk();

		// now revisit that page, as the same judge and verify that I didn't get redirected
		Auth::login($this->judge1);
		$this->route('GET', 'edit_solution', array($this->solution->id));
		$this->assertResponseOk('Even though judge has claimed this problem, they still cannot edit it');
	}

	/**
	 * Test that when a judge cancels a problem, the claiming judge for a solution is set to null again
	 * Also, make sure that only the claiming judge can edit this problem
	 */
	public function testUnclaimSolution() {
		// Login as judge 1 and claim the solution
		Auth::login($this->judge1);
		$this->route('GET', 'edit_solution', array($this->solution->id));

		// Now login as judge 2 and attempt to unclaim on behalf of 1
		Auth::login($this->judge2);
		$this->route('POST', 'unclaim_solution', array($this->solution->id));

		// check that the claiming judge is still judge 1
		$this->findSolution();
		$this->assertEquals($this->judge1->id, $this->solution->claiming_judge_id, "Judge 2 succesfully unclaimed judge 1 from the solution");

		// now let judge 1 unclaim
		Auth::login($this->judge1);
		$this->route('POST', 'unclaim_solution', array($this->solution->id));

		// verify that there is now no claiming judge
		$this->findSolution();
		$this->assertNull($this->solution->claiming_judge_id, "Judge 1 did not successfully unclaim his problem");
	}

}