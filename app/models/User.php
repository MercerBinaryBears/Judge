<?php

use Cartalyst\Sentry\Hashing\NativeHasher;

class User extends Base {

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
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
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
		if( $num_solutions = Solution::where('problem_id', $problem->id)
			->where('user_id', $this->id)
			->where('solution_state_id', $solved_state_id)->get()->count() > 0 ) {
			$points += ($num_solutions - 1) * 20 + Contest::find($problem->contest_id)->starts_at->diffInMinutes();
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
	 * Hashes the password with the Sentry Hasher, so that the admin
	 * also hashes the password when it saves, even though its not using
	 * Sentry
	 * @param string $value the unhashed password
	 */
	public function setPasswordAttribute($value) {
		$hasher = new NativeHasher();
		if ( empty($this->original['password']) || $value != $this->original['password'] ) {
			$this->attributes['password'] = $hasher->hash($value);
		}
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
}
