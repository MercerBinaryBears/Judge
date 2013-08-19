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
	 * Gets all of the solutions submitted by a user
	 */
	public function solutions() {
		return $this->hasMany('Solution');
	}

	/** 
	 * Scores the solutions submitted by a user
	 * for each contest it's participating in
	 * 
	 * @return array the array with index: problem_id and value: score
	 */
	public function score() {
		$solved_state_id = SolutionState::where('is_correct', true)->first()->id;
		$incorrect_state_id = SolutionState::where('is_correct', false)->where('pending', false)->first()->id;
		$this_id = $this->id;
		$scores = array();

		foreach( Contest::current()->problems as $problem ) {

    			// check that they actually solved it
			$solutions = Solution::where('problem_id', $problem->id)->where('user_id', $this_id);
    			if( Solution::where('solution_state_id', $solved_state_id)->get()->count() > 0 ) {

				// calculate score
				$incorrect_subs = $solutions->where(solution_state_id, $incorrect_state_id)->get()->count();
        			$scores[$problem->id] = ($incorrect_subs * 20) + Contest::current()->starts_at->diffInMinutes();
    			}
		}
		return $scores;
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
