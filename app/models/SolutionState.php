<?php

class SolutionState extends Base {
	/**
	 * The validation rules for a solution state
	 */
	public static $rules = array(
		'name'=>'required'
		);

	/**
	 * Gets all of the solutions with a give solution state
	 */
	public function solutions() {
		return $this->hasMany('Solution');
	}

	/**
	 * Gets the solution state in the database representing a
	 * solution still being judged
	 */
	public static function pending() {
		return static::where('pending', true)->firstOrFail();
	}
}