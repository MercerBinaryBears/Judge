<?php
use \LaravelBook\Ardent\Ardent as Ardent;

class Contest extends Ardent {
	public static $rules = array(
		'name' => 'required',
		'starts_at' => 'required',
		);

	public function problems() {
		return $this->hasMany('Problem');
	}
}