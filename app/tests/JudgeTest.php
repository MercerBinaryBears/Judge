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
		$solution_state = SolutionState::pending();
		$this->solution->solution_state_id = $solution_state->id;
		$this->solution->claiming_judge_id = null;
		$this->solution->save();
	}

	public function tearDown() {
		// TODO: figure out a way to not have to use
		// user_groups in Sentry, so we can just call $this->judge1->delete()
		DB::table('users')->where('username', 'LIKE', 'judge%')->delete();
	}

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

	private function updateSolution() {
		$this->solution = Solution::find($this->solution->id);
	}

	public function testAllowOnlyOneClaimer()
	{
		// make sure the solution has no claimer
		$this->updateSolution();
		$this->assertNull($this->solution->claiming_judge_id, 'A judge has already claimed this problem');


		// login as judge 1 and claim the submission
		Sentry::login($this->judge1, false);
		$this->route('GET', 'edit_solution', array($this->solution->id));
		$this->assertResponseOk();
		$this->updateSolution();
		$this->assertEquals($this->judge1->id, $this->solution->claiming_judge_id, 'Judge did not successfully claim this problem');

		// login as judge 2 and attempt to claim again
		Sentry::login($this->judge2, false);
		$this->route('GET', 'edit_solution', array($this->solution->id));
		$this->assertRedirectedToRoute('judge_index');
		$this->updateSolution();
		$this->assertEquals($this->judge1->id, $this->solution->claiming_judge_id, 'Judge was able to claim already claimed problem');
	}

	private function attemptUpdate($solution_state_id) {
		$parameters = array(
			'solution_state_id' => $solution_state_id,
			);
		$this->route('POST', 'update_solution', array($this->solution->id), $parameters);
	}

	public function testAllowOnlyOneEditter() {
		Sentry::login($this->judge1, false);
		$this->attemptUpdate(1);
		Sentry::login($this->judge2, false);
		$this->attemptUpdate(2);

		// make sure the judge2's update didn't carry through
		$this->solution = Solution::find($this->solution->id);
		$this->assertEquals(1, $this->solution->solution_state_id, "A Judge was able to update an already claimed problem");
	}

}