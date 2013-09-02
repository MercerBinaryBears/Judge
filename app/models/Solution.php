<?php

use Carbon\Carbon;

class Solution extends Base {
	/**
	 * The validation rules for a solution
	 */
	public static $rules = array(
		'problem_id' => 'required',
		'user_id' => 'required',
		'solution_code' => 'required',
		'language_id' => 'required',
		'solution_state_id' => 'required',
		);

	/**
	 * The set of attributes that can be mass-assigned onto a solution via
	 * $solution->fill($input_array);
	 */
	protected $fillable = array('problem_id', 'user_id', 'solution_code', 'solution_language', 'solution_state_id');

	/**
	 * Enables soft deleting on the model.
	 *
	 * @var bool
	 */
	protected $softDelete = true;

	/**
	 * Gets the problem that this solution solves
	 */
	public function problem() {
		return $this->belongsTo('Problem');
	}

	/**
	 * Gets the user that submitted this solution
	 */
	public function user() {
		return $this->belongsTo('User');
	}

	/**
	 * Gets the current solution state of this problem
	 */
	public function solutionState() {
		return $this->belongsTo('SolutionState');
	}

	/**
	 * Gets the judge that claimed this problem
	 */
	public function claimingJudge() {
		return $this->belongsTo('User', 'claiming_judge_id');
	}

	/**
	 * Gets the language this problem was submitted in
	 */
	public function language() {
		return $this->belongsTo('Language');
	}

	/**
	 * Overrides the getter for the created_at field, so that
	 * it formats well on the admin
	 */
	// public function getCreatedAtAttribute($value) {
	// 	if(!is_numeric($value)) {
	// 		$value = strtotime($value);
	// 	}

	// 	$contest_start_time = $this->problem->contest->starts_at;
	// 	if(!is_numeric($contest_start_time)) {
	// 		$contest_start_time = strtotime($contest_start_time);
	// 	}
	// 	return Carbon::createFromTimestamp($value)
	// 		->diffForHumans(Carbon::createFromTimestamp($contest_start_time))
	// 		. ' contest start time';
	// }

	/**
	 * Gets the solutions for the current contest
	 */
	public function scopeForCurrentContest($query) {
		$problems = Problem::forCurrentContest()->get();
		return $query->whereIn('problem_id', $problems->modelKeys())->orderBy('created_at');
	}

	/**
	 * Gets the unjudged problems for this contest
	 */
	public function scopeUnjudged($query) {
		$unjudged_state = SolutionState::pending();
		return $query->where('solution_state_id', $unjudged_state->id);
	}

	/**
	 * Gets the unclaimed problems for this contest
	 */
	public function scopeUnclaimed($query) {
		return $query->whereNull('claiming_judge_id');
	}

	/**
	 * Determines if the current user can alter a solution (claim it,
	 * unclaim it, edit, or update it)
	 */
	public function canBeAltered() {
		$user = Sentry::getUser();

		// check if the user is logged in
		if($user == null) {
			return false;
		}

		// if the user is not a judge or an admin, they can't edit
		if(!$user->judge && !$user->admin) {
			return false;
		}

		return $this->claiming_judge_id == null || $this->ownedByCurrentUser();
	}

	/**
	 * Checks if the current user owns this solution at this time
	 */
	public function ownedByCurrentUser() {
		return $this->claiming_judge_id == Sentry::getUser()->id;
	}

	/**
	 * Claims a problem for the current logged in user
	 */
	public function claim() {

		// check that the user can alter the problem first
		if(!$this->canBeAltered()) {
			return false;
		}

		// the user can alter this problem, so update the claiming judge
		$this->claiming_judge_id = Sentry::getUser()->id;

		// make the update, and return the result
		return $this->save();
	}

	/**
	 * Unclaims a solution, if the judge has permission
	 */
	public function unclaim() {

		// check that the judge has permission
		if(!$this->canBeAltered()) {
			return false;
		}

		// wipe the judge from the claiming judge
		$this->claiming_judge_id = null;

		// make the update and return the result
		return $this->save();
	}
}