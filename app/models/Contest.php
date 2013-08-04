<?php

class Contest extends Base {
	public static $rules = array(
		'name' => 'required',
		'starts_at' => 'required',
		);

	public function problems() {
		return $this->hasMany('Problem');
	}

	public static function current() {
		return self::where('starts_at', '<=', date('Y-m-d H:i:s', strtotime('now')))
			->orderBy('starts_at', 'desc');
	}
}