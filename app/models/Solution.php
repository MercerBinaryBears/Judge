<?php

use Carbon\Carbon;

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

	public function claiming_judge() {
		return $this->belongsTo('User', 'claiming_judge_id');
	}

	/**
	 * Parse filename and store file contents into solution code
	 * @param string $filename
	 */
	public function setSolutionCodeAttribute($filename) {
		$filename = "/tmp/$filename";
		// parse file and store file contents rather than filename
		list($original, $ext, $file_contents, $tmp_path) = Base::unpackFile($filename, true);
		$this->attributes['solution_code'] = $file_contents;
		$this->attributes['solution_language'] = $ext;
	}

	public function getCreatedAtAttribute($value) {
		if(!is_numeric($value)) {
			$value = strtotime($value);
		}

		$contest_start_time = $this->problem->contest->starts_at;
		if(!is_numeric($contest_start_time)) {
			$contest_start_time = strtotime($contest_start_time);
		}
		return Carbon::createFromTimestamp($value)
			->diffForHumans(Carbon::createFromTimestamp($contest_start_time))
			. ' contest start time';
	}

	public function scopeForCurrentContest($query) {
		$problems = Problem::forCurrentContest()->get();
		return $query->whereIn('problem_id', $problems->modelKeys())->orderBy('created_at');
	}

	public function scopeUnjudged($query) {
		$unjudged_state = SolutionState::where('name','LIKE', '%Judging%')->first();
		return $query->where('solution_state_id', $unjudged_state->id);
	}

	public function scopeUnclaimed($query) {
		return $query->whereNull('claiming_judge_id');
	}
}