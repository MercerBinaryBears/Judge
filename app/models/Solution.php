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

	/**
	 * Parse filename and store file contents into solution code
	 * @param string $filename
	 */
	public function setSolutionCodeAttribute($filename) {
		// parse file and store file contents rather than filename
		list($original, $ext, $file_contents, $tmp_path) = Base::unpackFile($filename, true);
		$this->attributes['solution_code'] = $file_contents;
		$this->attributes['solution_language'] = $ext;
	}
}