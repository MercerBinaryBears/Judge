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


}
