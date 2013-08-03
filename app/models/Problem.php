<?php

class Problem extends Base {
	public static $rules = array(
		'name' => 'required',
		'contest_id' => 'required',
		'judging_input' => 'required',
		'judging_output' => 'required'
		);

	public function contest() {
		return $this->belongsTo('Contest');
	}

	public function solutions() {
		return $this->hasMany('Solution');
	}

}