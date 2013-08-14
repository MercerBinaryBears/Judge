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
        return $this->belongsToMany('Contest')->withPivot('contest_user');
    }

	/**
	 * Gets all of the solutions submitted by a user
	 */
	public function solutions() {
		return $this->hasMany('Solution');
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
