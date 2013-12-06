<?php

interface SolutionRepository {
	public function find($id);

	public function judgeableForCurrentContest();

	public function claimedByJudgeInCurrentContest(User $u);

	/*
	 * Gets the solutions sent by a user in a contest. If no
	 * contest is provided, it defaults to the first current contest
	 */
	public function forUserInContest(User $u, Contest $c);
}
