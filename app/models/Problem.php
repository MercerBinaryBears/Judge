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

	public function setJudgingInputAttribute($filename) {
		$filename = "/tmp/$filename";
		list($original, $ext, $file_contents, $tmp_path) = Base::unpackFile($filename, true);
		Log::debug("FILENAME: $filename, FILE CONTENTS:\n$file_contents");
		$this->attributes['judging_input'] = $file_contents;
	}

	public function setJudgingOutputAttribute($filename) {
		$filename = "/tmp/$filename";
		list($original, $ext, $file_contents, $tmp_path) = Base::unpackFile($filename, true);
		$this->attributes['judging_output'] = $file_contents;
	}

	public static function forCurrentContest() {
		$contests = Contest::current()->first();
		return Problem::where('contest_id', $contests->id)->orderBy('created_at');
	}
}