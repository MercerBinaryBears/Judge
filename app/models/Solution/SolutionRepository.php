<?php

interface SolutionRepository {
	public function find($id);

	public function judgeableForCurrentContest();

	public function claimedByJudgeInCurrentContest(User $u);
}
