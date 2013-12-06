<?php

class EloquentSolutionRepository implements SolutionRepository {
	
	public function __construct(ContestRepository $contests, ProblemRepository $problems, SolutionStateRepository $solution_states) {
		$this->contests = $contests;
		$this->problems = $problems;
		$this->solution_states = $solution_states;
	}

	public function find($id) {
		return Solution::find($id);
	}	

	public function judgeableForCurrentContest() {
		$problems = $this->contest->problemsForContest();

		if($problems->count() < 1) {
			return Illuminate\Support\Collection::make(array());
		}

		return Solution::whereIn($problems->lists('id'))
			->whereSolutionStateId($this->solution_states->firstPendingId())
			->whereJudgeId(null)
			->get();
	}

	public function claimedByJudgeInCurrentContest(User $u) {
		$problems = $this->contest->problemsForContest();

		if($problems->count() < 1) {
			return Illuminate\Support\Collection::make(array());
		}

		return Solution::whereIn($problems->lists('id'))
			->whereJudgeId($u->id)
			->get();
	}
}
