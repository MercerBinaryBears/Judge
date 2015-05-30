<?php namespace Judge\Repositories;

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
        $problems = $this->contests->problemsForContest($c);

        if ($problems->count() < 1) {
            return \Illuminate\Support\Collection::make(array());
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
            return \Illuminate\Support\Collection::make(array());
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
            return \Illuminate\Support\Collection::make(array());
        }

        return Solution::whereIn('problem_id', $problems->lists('id'))
            ->whereUserId($u->id)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function hasCorrectSolutionFromUser(User $user, Problem $problem)
    {
        $solved_solution_state = SolutionState::whereIsCorrect(true)->firstOrFail()->id;

        return Solution::whereUserId($user->id)
            ->whereProblemId($problem->id)
            ->whereSolutionStateId($solved_solution_state)
            ->count() > 0;
    }

    public function incorrectSubmissionCountFromUserFromProblem(User $user, Problem $problem)
    {
        $non_incorrect_ids = SolutionState::where('is_correct', true)
            ->orWhere('pending', true)
            ->lists('id');

        return Solution::whereUserId($user->id)
            ->whereProblemId($problem->id)
            ->whereNotIn('solution_state_id', $non_incorrect_ids)
            ->count();
    }

    public function earliestCorrectSolutionFromUserForProblem(User $user, Problem $problem)
    {
        $solved_solution_state = SolutionState::whereIsCorrect(true)->firstOrFail()->id;

        return Solution::whereUserId($user->id)
            ->whereProblemId($problem->id)
            ->whereSolutionStateId($solved_solution_state)
            ->orderBy('created_at')
            ->first();
    }
}
