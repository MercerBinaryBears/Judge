<?php

use Carbon\Carbon as Carbon;

class TempContestTableSeeder extends Seeder {

	/**
	 * Creates a temporary contest, with some problems and potential solutions
	 */
	public function run()
	{
		// random sizes for everything
		$problem_count = rand(3,10); 
		$team_count = rand(3,10);
		$judge_count = rand(2,4);
		$solution_count = rand(2, $problem_count * $team_count * 2);

		// arrays to hold temps
		$contest = null;
		$teams = [];
		$judges = [];
		$problems = [];


		/*
		 * create the contest and store it as $this->contest.
		 */
		$this->createContest();

		// generate teams
		for($i=1; $i <= $team_count; $i++) {
			$teams[] = $this->createTeam($i);
		}	

		// generate judges
		for($i=1; $i <= $judge_count; $i++) {
			$judges[] = $this->createJudge($i);
		}

		// generate problems
		for($i=1; $i<= $problem_count; $i++) {
			$problems[] = $this->createProblem($i);	
		}
		
		// solution language
		$language_id = Language::where('name','Python')->first()->id;

		// solution states
		$solution_states = SolutionState::all();

		// create some solutions
		for($i=1; $i <= $solution_count; $i++) {
			// choose a user at random
			$user_id = $teams[ rand(0, $team_count - 1) ]->id;

			// choose a problem at random
			$problem_id = $problems[ rand(0, $problem_count -1) ]->id;

			// choose a time offset from contest_start
			$submission_offset = rand(5, 60*5);

			// choose a solution state
			$solution_state_id = $solution_states[ rand(0, $solution_states->count()-1) ]->id;

			// choose a judge
			$judge_id = $judges[ rand(0, $judge_count - 1) ]->id;

			// create a solution
			$this->createSolution($user_id, $problem_id, $language_id, 
				$judge_id, $solution_state_id, $submission_offset);
		}

	}

	public function createContest() {
		$this->contest_start = Carbon::now()->subDay();
		$this->contest_end = Carbon::now()->subDay()->addHours(rand(3,5));

		$this->contest = Contest::create(array(
			'name' => 'Contest on ' . $this->contest_start->format('m/d/Y'),
			'starts_at' => $this->contest_start->toDateTimeString(),
			'ends_at' => $this->contest_end->toDateTimeString()
		));
	}

	public function createTeam($i) {
		return $this->createUser($i, 'team', true);	
	}

	public function createJudge($i) {
		return $this->createUser($i, 'judge', false, true);
	}

	private function createUser($i, $prefix, $team = false, $judge = false) {
		$u = new User();
		$u->username = "${prefix}_$i";
		$u->password = Hash::make("password");
		$u->admin = false;
		$u->judge = $judge;
		$u->team = $team;
		$u->created_at = Carbon::now()->toDateTimeString();
		$u->updated_at = Carbon::now()->toDateTimeString();

		$this->saveOrErr($u);

		// attach the user to the current contest
		DB::table('contest_user')->insert(array(
			'user_id' => $u->id,
			'contest_id' => $this->contest->id,
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		));

		return $u;
	}

	public function createProblem($i) {
		$p = new Problem();
		$p->name = "Problem $i";
		$p->contest_id = $this->contest->id;
		$p->judging_input = 'INPUT';
		$p->judging_output = 'OUTPUT';
		$p->created_at = Carbon::now()->toDateTimeString();
		$p->updated_at = Carbon::now()->toDateTimeString();

		return $this->saveOrErr($p);
	}

	public function createSolution($user_id, $problem_id, $language_id, $judge_id, $solution_state_id, $solution_offset) {
		$s = new Solution();
		$s->problem_id = $problem_id;
		$s->user_id = $user_id;
		$s->solution_code = 'hello world';
		$s->language_id = $language_id;
		$s->solution_filename = 'filename';
		$s->solution_state_id = $solution_state_id; 

		if($solution_state_id != 7) {
			$s->claiming_judge_id = $judge_id;
		}

		// calculate the start time from the contest time
		$submission_time = (new Carbon($this->contest_start))->addMinutes($solution_offset);

		$s->created_at = $submission_time->toDateTimeString();
		$s->updated_at = $submission_time->toDateTimeString();

		return $this->saveOrErr($s);
	}

	private function saveOrErr($model, $message='Invalid Model') {
			if($model->save()) {
					return $model;
			}
			else {
					throw new Exception($message . ' ' . $model->errors()->toJson());
			}
        }
}
