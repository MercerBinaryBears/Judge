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
	 * Gets all of the contests that a user is participating in
	 */
	public function contests() {
		return $this->belongsToMany('Contest');
	}

	/**
	 * Scores the user's solution for this problem
	 *
	 * @param p the problem to score
	 * @return int the number of points
	 */
	public function pointsForProblem($problem) {
		$points = 0;
		$solved_state_id = SolutionState::where('is_correct', true)->first()->id;
		if( $num_solutions = Solution::where('problem_id', $problem->id)
			->where('user_id', $user->id)
			->where('solved_state_id', $solved_state_id)->get()->count() > 0 ) {
			$points += ($num_solutions - 1) * 20 + Contest::current()->starts_at->diffInMinutes();
		}
		return $points;
	}

	/**
	 * Gets all of the solutions submitted by a user
	 */
	public function solutions() {
		return $this->hasMany('Solution');
	}

	/**
	 * Scores the solutions submitted by a user for each
	 * contest it's participating in.
	 *
	 * @return int the total number of points for this user
	 */
	public function totalPoints() {
		$points = 0;
		foreach(Contest::current()->problems as $problem) {
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

}
