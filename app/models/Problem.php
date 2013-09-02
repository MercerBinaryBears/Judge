<?php

class Problem extends Base {
	public static $rules = array(
		'name' => 'required',
		'contest_id' => 'required',
		'judging_input' => 'required',
		'judging_output' => 'required'
		);

	/**
	 * Enables soft deleting on the model.
	 *
	 * @var bool
	 */
	protected $softDelete = true;

	/**
	 * Gets the contest that this problem belongs to
	 */
	public function contest() {
		return $this->belongsTo('Contest');
	}

	/**
	 * Gets all solutions for this problem
	 */
	public function solutions() {
		return $this->hasMany('Solution');
	}

	/**
	 * Gets the problems for the current contest
	 */
	public function scopeForCurrentContest($query) {
		$contests = Contest::current()->first();
		if($contests == null) {
			return Problem::where('id', null);
		}
		return $query->where('contest_id', $contests->id)->orderBy('created_at');
	}
}