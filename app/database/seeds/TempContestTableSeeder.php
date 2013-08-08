<?php

class TempContestTableSeeder extends Seeder {

	/**
	 * Creates a temporary contest, with some problems and potential solutions
	 */
	public function run()
	{
		// the contest
		$contest = $this->createContest();

		// problems
		$problem1 = $this->createProblem('Problem 1', $contest);
		$problem2 = $this->createProblem('Problem 2', $contest);

		// users
		$team1 = $this->createTeam('Team 1');
		$team2 = $this->createTeam('Team 2');

		// some solutions
		$this->createSolution($team1, $problem1);
		$this->createSolution($team1, $problem2);
		$this->createSolution($team1, $problem1);
		$this->createSolution($team2, $problem2);
		$this->createSolution($team1, $problem2);
	}

	private function writeTmp($path='test.py', $contents='Hello world') {
		$fh = fopen("/tmp/$path", "w");
		fwrite($fh, $contents);
		fclose($fh);
	}

	private function saveOrErr($model, $message='Invalid Model') {
		if($model->save()) {
			return $model;
		}
		else {
			throw new Exception($message . ' ' . $model->errors()->toJson());
		}
	}

	private function createContest() {
		$contest = new Contest();
		$contest->name = "Contest on " . date("H_m_s",strtotime('now'));
		$contest->starts_at = strtotime('now');
		$contest->ends_at = strtotime('now +3 days');
		return $this->saveOrErr($contest, 'Invalid Contest');
	}

	private function createProblem($problem_name, $contest) {
		// problems
		$problem = new Problem();
		$problem->name = $problem_name;
		$problem->contest_id = $contest->id;

		// judging data
		$this->writeTmp();
		$problem->judging_input = 'test.py';
		$this->writeTmp();
		$problem->judging_output = 'test.py';

		return $this->saveOrErr($problem, 'Invalid Problem');
	}

	private function createTeam($username = 'Team A') {
		$user = new User();
		$user->username = $username;
		$user->password = 'secret';
		$user->admin = false;
		$user->judge = false;
		$user->team = true;
		return $this->saveOrErr($user, 'Invalid User');
	}

	private function createSolution($team, $problem) {
		$this->writeTmp('test.py', 'Hello World');
		$judging = SolutionState::where('name', 'LIKE', '%Judging%')->first();

		$solution = new Solution();
		$solution->user_id = $team->id;
		$solution->problem_id = $problem->id;
		$solution->solution_filename = 'test.py';
		$solution->solution_code = 'test.py';
		$solution->solution_language = 'py';
		$solution->solution_state_id = $judging->id;
		return $this->saveOrErr($solution, 'Invalid Solution');
	}
}