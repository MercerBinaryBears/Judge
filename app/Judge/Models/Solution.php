<?php namespace Judge\Models;

use Carbon\Carbon;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

use Judge\Models\Problem;
use Judge\Models\SolutionState;

class Solution extends Base
{
    /**
     * The validation rules for a solution
     */
    public static $rules = array(
        'problem_id' => 'required',
        'user_id' => 'required',
        'solution_code' => 'required',
        'language_id' => 'required',
        'solution_state_id' => 'required',
        'solution_filename' => 'required',
    );

    /**
     * The set of attributes that can be mass-assigned onto a solution via
     * $solution->fill($input_array);
     */
    protected $fillable = array(
        'problem_id',
        'user_id',
        'solution_code',
        'language_id',
        'solution_state_id',
        'claiming_judge_id',
        'solution_filename'
    );

    /**
     * Gets the problem that this solution solves
     */
    public function problem()
    {
        return $this->belongsTo('Judge\Models\Problem');
    }

    /**
     * Gets the user that submitted this solution
     */
    public function user()
    {
        return $this->belongsTo('Judge\Models\User');
    }

    /**
     * Gets the current solution state of this problem
     */
    public function solutionState()
    {
        return $this->belongsTo('Judge\Models\SolutionState');
    }

    /**
     * Gets the judge that claimed this problem
     */
    public function claimingJudge()
    {
        return $this->belongsTo('Judge\Models\User', 'claiming_judge_id');
    }

    /**
     * Gets the language this problem was submitted in
     */
    public function language()
    {
        return $this->belongsTo('Judge\Models\Language');
    }

    /**
     * Gets the unclaimed problems for this contest
     */
    public function scopeUnclaimed($query)
    {
        return $query->whereNull('claiming_judge_id');
    }

    /**
     * Determines if the current user can alter a solution (claim it,
     * unclaim it, edit, or update it)
     */
    public function canBeAltered()
    {
        $user = Auth::user();

        // check if the user is logged in
        if ($user == null) {
            return false;
        }

        // if the user is not a judge or an admin, they can't edit
        if (!$user->judge && !$user->admin) {
            return false;
        }

        return $this->claiming_judge_id == null || $this->ownedByCurrentUser();
    }

    /**
     * Checks if the current user owns this solution at this time
     */
    public function ownedByCurrentUser()
    {
        return $this->claiming_judge_id == Auth::id();
    }

    /**
     * Claims a problem for the current logged in user
     */
    public function claim()
    {
        // check that the user can alter the problem first
        if (!$this->canBeAltered()) {
            return false;
        }

        // the user can alter this problem, so update the claiming judge
        $this->claiming_judge_id = Auth::user()->id;

        // make the update, and return the result
        return $this->save();
    }

    /**
     * Unclaims a solution, if the judge has permission
     */
    public function unclaim()
    {
        // check that the judge has permission
        if (!$this->canBeAltered()) {
            return false;
        }

        // wipe the judge from the claiming judge
        $this->claiming_judge_id = null;

        // make the update and return the result
        return $this->save();
    }

    /**
     * Prints a pretty diff of the solution since the contest start
     */
    public function submissionPrettyDiff()
    {
        // get the contest for this solution
        $current_contest = App::make('Judge\Repositories\ContestRepository')->firstCurrent();

        $submission_time = new Carbon($this->created_at);
        $contest_start = new Carbon($current_contest->starts_at);

        return $submission_time->diffInMinutes($contest_start)
            . ' minutes after contest start';
    }
}
