<?php

use Carbon\Carbon;

class Solution extends Base {
	public static $rules = array(
		'problem_id' => 'required',
		'user_id' => 'required',
		'solution_code' => 'required',
		'solution_language' => 'required',
		'solution_state_id' => 'required',
		);

	protected $fillable = array('problem_id', 'user_id', 'solution_code', 'solution_language', 'solution_state_id');

	public function problem() {
		return $this->belongsTo('Problem');
	}

	public function user() {
		return $this->belongsTo('User');
	}

	public function solutionState() {
		return $this->belongsTo('SolutionState');
	}

	public function claimingJudge() {
		return $this->belongsTo('User', 'claiming_judge_id');
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