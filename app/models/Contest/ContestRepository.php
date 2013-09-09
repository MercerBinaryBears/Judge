<?php

interface ContestRepository {
	/**
	 * Returns all problems associated with the passed contest
	 *
	 * @param Contest $c
	 * @return Collection A laravel collection of Problem instances
	 */
	public function problemsForContest(Contest $c);

	/**
	 * Returns all users associated with the passed contest
	 *
	 * @param Contest $c
	 * @return Collection a laravel collection of User instances
	 */
	public function usersForContest(Contest $c);

	/**
	 * Returns all contests considered "current", i.e. their start
	 * time is in the past.
	 *
	 * @return Collection a collection of contest instances
	 */
	public function currentContests();
}