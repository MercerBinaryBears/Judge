<?php

class Contest extends Base {
	public static $rules = array(
		'name' => 'required',
		'starts_at' => 'required',
		);

	public function problems() {
		return $this->hasMany('Problem');
	}

	public function scopeCurrent($query) {
		return $query->where('starts_at', '<=', date('Y-m-d H:i:s', strtotime('now')))
			->orderBy('starts_at', 'desc');
	}
}