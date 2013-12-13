<?php

use Illuminate\Auth\UserInterface as UserInterface;
use Carbon\Carbon as Carbon;

class User extends Base implements UserInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Enables soft deleting on the model.
	 *
	 * @var bool
	 */
	protected $softDelete = true;

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->id;
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Gets the contests that a user is participating in.
	 */
	public function contests() {
		return $this->belongsToMany('Contest');
	}

	/**
	 * Scores the user's solution for this problem.
	 * 20 pts added for each incorrect solution, plus 1 pt
	 * for each additional minute since contest start time.
	 *
	 * @param problem $problem the problem to score
	 * @return int the number of points
	 */
	public function pointsForProblem($problem) {
		$points = 0;
		$solved_state_id = SolutionState::where('is_correct', true)->first()->id;

		// the total number of submissions for this problem
		$total_submissions = Solution::where('problem_id', $problem->id)
			->where('user_id', $this->id)
			->count();

		// did they solve it
		$did_solve = Solution::where('problem_id', $problem->id)
			->where('user_id', $this->id)
			->where('solution_state_id', $solved_state_id)
			->count() > 0;

		if( $did_solve ) {

			$contest_start = new Carbon($problem->contest->starts_at);

			$correct_start = Solution::where('problem_id', $problem->id)
				->where('user_id', $this->id)
				->where('solution_state_id', $solved_state_id)
				->first()
				->created_at;

			$correct_start = new Carbon($correct_start);

			$points += ($total_submissions - 1) * 20 + $contest_start->diffInMinutes(new Carbon($correct_start));
		}
		return $points;
	}

	/**
	 * Gets all of the solutions submitted by a user.
	 */
	public function solutions() {
		return $this->hasMany('Solution');
	}

	/**
	 * Scores the solutions submitted by a user for a
	 * given contest.
	 *
	 * @param contest $contest the contest to score points on
	 * @return int the total number of points for this user
	 */
	public function totalPoints($contest) {
		$points = 0;
		foreach($contest->problems as $problem) {
			$points += $this->pointsForProblem($problem);
		}
		return $points;
	}

	/**
	 * Generates a random API key for a user. VERY low chance of non-uniqueness
	 *
	 * @param The string length for the key. Default is 20
	 * @return string
	 */
	public static function generateApiKey($length=20) {
		$time = microtime(true) * 10000;

		// reverse the string, so we get most commonly changing bit first
		// which makes the tokens easier to distinguish
		$s =  strrev(sprintf('%x', $time));

		// append random numbers on until we reach our length
		while(strlen($s) < $length) {
			$s .= sprintf('%x', rand());
		}

		// trim off the excess
		return substr($s, 0, $length);
	}

	/**
	 * Startup code for the model. We can register some events to the model here.
	 */
	public static function boot() {
		parent::boot();

		// before a user is about to be created, create an api key for that user
		User::creating(function($user){
			$user->api_key = User::generateApiKey();
		});
	}

	/**
	 * Provides a summary of the given contest for this user
	 *
	 * @param Contest $contest The contest to summarize
	 * @return array The summary data
	 */
	public function contestSummary($contest) {
		$summary = array();

		$summary['username'] = $this->username;

		$summary['score'] = $this->totalPoints($contest);

		$summary['problems_solved'] = $this->problemsSolved($contest);

		$summary['problem_info'] = array();

		foreach($contest->problems as $problem) {
			$problem_info = array();
			$problem_info['points_for_problem'] = $this->pointsForProblem($problem);
			$problem_info['num_submissions'] = $this->solutions()->where('problem_id', $problem->id)->count();

			$summary['problem_info'][] = $problem_info;
		}
		return $summary;
	}

	/*
	 * Calculates the number of problems solved for user in a provide
	 * contest. If no contest is provided, defaults to the most recent current
	 * contest.
	 *
	 * @param Contest $contest
	 * @return int
	 */
	public function problemsSolved(Contest $c = null) {
		$problems = $this->cachedProblems($c);
		$solutions = $this->cachedSolutions($c);

		/*
		 * Loop over every problem, checking if there is a solution
		 * that is solved
		 */
		$solved_states = array();
		$solved_state_id = App::make('SolutionStateRepository')->firstCorrectId();
		foreach($problems as $problem) {
			$solved_states[$problem->id] = 0;
			foreach($solutions as $solution) {
				if($solution->problem_id == $problem->id && $solution->solution_state_id == $solved_state_id) {
					$solved_states[$problem->id] = 1;
				}
			}
		}

		// now sum up the solved states
		return array_sum($solved_states);	
	}

	protected function cachedProblems(Contest $c = null) {
		if(isset($this->cached_problems)) {
			return $this->cached_problems;
		}

		$this->cached_problems = App::make('ContestRepository')->problemsForContest($c);

		return $this->cached_problems;
	}

	protected function cachedSolutions(Contest $c = null) {
		if(isset($this->cached_solutions)) {
			return $this->cached_solutions;
		}

		$this->cached_solutions = App::make('SolutionRepository')->forUserInContest($this, $c);

		return $this->cached_solutions;
	}
}
