<?php

class SolutionState extends Base {
	public static $rules = array(
		'name'=>'required'
		);

	public function solutions() {
		return $this->hasMany('Solution');
	}
}