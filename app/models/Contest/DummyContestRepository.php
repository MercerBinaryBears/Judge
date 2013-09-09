<?php

uses Illuminate\Database\Eloquent\Collection as Collection;

class DummyContestRepository implements ContestRepository {

	/**
	 * Creates a dummy repository that always returns the passed arrays
	 */
	public function __construct(Collection $current_contests, Collection $problems, Collection $users) {
		$this->current_contests = $current_contests;
		$this->problems = $problems;
		$this->users = $users;
	}

	public function problemsForContest(Contest $c) {
		return $this->problems;
	}

	public function usersForContest(Contest $c) {
		return $this->users;
	}

	public function currentContests() {
		return $this->current_contests;
	}
}