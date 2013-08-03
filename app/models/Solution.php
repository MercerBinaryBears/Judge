<?php

class Solution extends Base {
	public static $rules = array(
		'problem_id' => 'required',
		'user_id' => 'required',
		'solution_code' => 'required',
		'solution_language' => 'required'
		);

	public function problem() {
		return $this->belongsTo('Problem');
	}

	public function user() {
		return $this->belongsTo('User');
	}

	public function solution_state() {
		return $this->belongsTo('SolutionState');
	}
}