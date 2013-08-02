<?php
use \LaravelBook\Ardent\Ardent as Ardent;

class SolutionState extends Ardent {
	public static $rules = array(
		'name'=>'required'
		);

	public function solutions() {
		return $this->hasMany('Solution');
	}
}