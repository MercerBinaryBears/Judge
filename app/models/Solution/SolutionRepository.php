<?php

interface SolutionRepository {
	public function find($id);

	/*
	 * Gets a list of judgeable solutions for a contest.
	 * If the contest is null, it defaults to the first current contest
	 */
	public function judgeableForContest(Contest $c = null);

	public function claimedByJudgeInContest(User $u, Contest $c = null);

	/*
	 * Gets the solutions sent by a user in a contest. If no
	 * contest is provided, it defaults to the first current contest
	 */
	public function forUserInContest(User $u, Contest $c = null);
}
