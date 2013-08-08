<?php

class SolutionState extends Base {
	public static $rules = array(
		'name'=>'required'
		);

	public function solutions() {
		return $this->hasMany('Solution');
	}

	public static function pending() {
		return static::where('pending', true)->firstOrFail();
	}
}