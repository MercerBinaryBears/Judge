<?php

class EloquentProblemRepository implements ProblemRepository {
	public function getSelectBoxData() {
		return Problem::forCurrentContest()
			->orderBy('name')
			->lists('name', 'id');
	}

	public function allIds() {
		return Problem::forCurrentContest()
			->lists('id');
	}

	public function forContest(Contest $c) {
		return Problem::whereContestId($c->id)->get();
	}
}
