<?php namespace Judge\Repositories;

use Illuminate\Support\Collection;
use Judge\Models\Contest;
use Judge\Repositories\ContestRepository;
use Judge\Repositories\ProblemRepository;
use Judge\Models\Problem;
use Judge\Models\Solution;
use Judge\Models\SolutionState;
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

        return Solution::join('problems', 'problems.id', '=', 'solutions.problem_id')
            ->where('problems.contest_id', '=', $c->id)
            ->whereSolutionStateId($this->solution_states->firstPendingId())
            ->whereClaimingJudgeId(null)
            ->select('solutions.*')
            ->orderBy('created_at', 'ASC')
            ->get();
    }

    public function claimedByJudgeInContest(User $u, Contest $c = null)
    {
        if ($c == null) {
            $c = $this->contests->firstCurrent();
        }

        $problems = $this->contests->problemsForContest();

        if ($problems->count() < 1) {
            return Collection::make(array());
        }

        return Solution::whereIn('problem_id', $problems->lists('id'))
            ->whereClaimingJudgeId($u->id)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function forUserInContest(User $u, Contest $c = null)
    {
        if ($c == null) {
            $c = $this->contests->firstCurrent();
        }

        $problems = $this->contests->problemsForContest();

        if ($problems->count() < 1) {
            return Collection::make(array());
        }

        return Solution::whereIn('problem_id', $problems->lists('id'))
            ->whereUserId($u->id)
            ->orderBy('created_at', 'ASC')
            ->get();
    }

    public function hasCorrectSolutionFromUser(User $user, Problem $problem)
    {
        return Solution::join('solution_states', 'solution_state_id', '=', 'solution_states.id')
            ->whereUserId($user->id)
            ->whereProblemId($problem->id)
            ->where('solution_states.is_correct', '=', true)
            ->count() > 0;
    }

    public function incorrectSubmissionCountFromUserFromProblem(User $user, Problem $problem)
    {
        return Solution::join('solution_states', 'solution_state_id', '=', 'solution_states.id')
            ->whereUserId($user->id)
            ->whereProblemId($problem->id)
            ->where('solution_states.pending', '=', false)
            ->where('solution_states.is_correct', '=', false)
            ->count();
    }

    public function earliestCorrectSolutionFromUserForProblem(User $user, Problem $problem)
    {
        return Solution::join('solution_states', 'solution_state_id', '=', 'solution_states.id')
            ->whereUserId($user->id)
            ->whereProblemId($problem->id)
            ->where('solution_states.is_correct', '=', true)
            ->orderBy('created_at')
            ->first();
    }
}
