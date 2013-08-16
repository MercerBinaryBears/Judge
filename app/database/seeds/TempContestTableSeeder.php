<?php

class TempContestTableSeeder extends Seeder {

	/**
	 * Creates a temporary contest, with some problems and potential solutions
	 */
	public function run()
	{
		// the contest
		$contest1 = $this->createContest();
		$contest2 = $this->createContest();

		// problems
		$problem1 = $this->createProblem('Problem 1', $contest1);
		$problem2 = $this->createProblem('Problem 2', $contest1);

		// users
		$team1 = $this->createTeam('Team 1', array($contest1->id, $contest2->id));
		$team2 = $this->createTeam('Team 2', array($contest1->id, $contest2->id));

		// solution language
		$language = Language::where('name','Python')->first();

		// some solutions
		$this->createSolution($team1, $problem1, $language);
		$this->createSolution($team1, $problem2, $language);
		$this->createSolution($team1, $problem1, $language);
		$this->createSolution($team2, $problem2, $language);
		$this->createSolution($team1, $problem2, $language);
	}

	private function writeTmp($filename='test.py', $path='/tmp/', $contents='Hello world') {
		$fh = fopen($path . '/' . $filename, "w");
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
		$judging_input_filename = $problem->generateRandomString();
		$this->writeTmp($judging_input_filename, storage_path() . "/" . 'judging_input/', 'INPUT');
		$problem->judging_input = $judging_input_filename;

		$judging_output_filename = $problem->generateRandomString();
		$this->writeTmp($judging_output_filename, storage_path() . "/" . 'judging_output/', 'OUTPUT');
		$problem->judging_output = $judging_output_filename;

		return $this->saveOrErr($problem, 'Invalid Problem');
	}

	private function createTeam($username = 'Team A', array $contest_ids) {
		$user = new User();
		$user->username = $username;
		$user->password = 'secret';
		$user->admin = false;
		$user->judge = false;
		$user->team = true;
		$user->contests()->sync($contest_ids);
		return $this->saveOrErr($user, 'Invalid User');
	}

	private function createSolution($team, $problem, $language) {
		$judging = SolutionState::pending();

		$solution = new Solution();

		// write the solution code to a file
		$solution->solution_code = $solution->generateRandomString();
		$this->writeTmp($solution->solution_code, storage_path() . '/solution_code/', "print 'Hello World'");

		$solution->user_id = $team->id;
		$solution->problem_id = $problem->id;
		$solution->solution_filename = 'test.py';
		$solution->language_id = $language->id;
		$solution->solution_state_id = $judging->id;
		return $this->saveOrErr($solution, 'Invalid Solution');
	}
}
