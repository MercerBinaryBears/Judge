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

	public static function pending() {
		return static::where('pending', true)->firstOrFail();
	}
}
