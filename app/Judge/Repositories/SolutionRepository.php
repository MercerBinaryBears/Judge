<?php namespace Judge\Repositories;

use Judge\Models\Contest;
use Judge\Repositories\ContestRepository;
use Judge\Repositories\ProblemRepository;
use Judge\Models\Solution;
use Judge\Repositories\SolutionStateRepository;
use Judge\Models\User;

class SolutionRepository
{
    public function __construct(
        ContestRepository $contests,
        ProblemRepository $problems,
        SolutionStateRepository $solution_states
    ) {
        $this->contests = $contests;
        $this->problems = $problems;
        $this->solution_states = $solution_states;
    }

    public function find($id)
    {
        return Solution::find($id);
    }

    public function judgeableForContest(Contest $c = null)
    {
        if ($c == null) {
            $c = $this->contests->firstCurrent();
        }
        $problems = $this->contests->problemsForContest($c);

        if ($problems->count() < 1) {
            return Illuminate\Support\Collection::make(array());
        }

        return Solution::whereIn('problem_id', $problems->lists('id'))
            ->whereSolutionStateId($this->solution_states->firstPendingId())
            ->whereClaimingJudgeId(null)
            ->orderBy('created_at')
            ->get();
    }

    public function claimedByJudgeInContest(User $u, Contest $c = null)
    {
        if ($c == null) {
            $c = $this->contests->firstCurrent();
        }

        $problems = $this->contests->problemsForContest();

        if ($problems->count() < 1) {
            return Illuminate\Support\Collection::make(array());
        }

        return Solution::whereIn('problem_id', $problems->lists('id'))
            ->whereClaimingJudgeId($u->id)
            ->get();
    }

    public function forUserInContest(User $u, Contest $c = null)
    {
        if ($c == null) {
            $c = $this->contests->firstCurrent();
        }

        $problems = $this->contests->problemsForContest();

        if ($problems->count() < 1) {
            return Illuminate\Support\Collection::make(array());
        }

        return Solution::whereIn('problem_id', $problems->lists('id'))
            ->whereUserId($u->id)
            ->orderBy('id', 'desc')
            ->get();
    }
}
