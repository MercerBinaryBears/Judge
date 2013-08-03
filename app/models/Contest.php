<?php

class Contest extends Base {
	public static $rules = array(
		'name' => 'required',
		'starts_at' => 'required',
		);

	public function problems() {
		return $this->hasMany('Problem');
	}
}