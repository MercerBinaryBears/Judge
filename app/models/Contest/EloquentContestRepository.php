<?php

use Carbon\Carbon as Carbon;

class EloquentContestRepository implements ContestRepositoryInterface {

	public function problemsForContest(Contest $c) {
		return $c->problems;
	}

	public function usersForContest(Contest $c) {
		return $c->users;
	}

	public function currentContests() {
		return Contest::where('starts_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
			->orderBy('starts_at', 'desc')->get();
	}
}