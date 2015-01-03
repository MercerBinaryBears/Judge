<?php namespace Judge\Models\User;

use Illuminate\Support\Facades\App;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Carbon\Carbon as Carbon;

use Judge\Models\Base;

class User extends Base implements UserInterface, RemindableInterface
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Gets the contests that a user is participating in.
     */
    public function contests()
    {
        return $this->belongsToMany('Contest');
    }

    /**
     * Scores the user's solution for this problem.
     * 20 pts added for each incorrect solution, plus 1 pt
     * for each additional minute since contest start time.
     *
     * @param problem $problem the problem to score
     * @return int the number of points
     */
    public function pointsForProblem(Problem $problem)
    {
        // get the start time for the contest
        $starts_at = new Carbon($this->cachedContest()->starts_at);

        // if they didn't solve it, return 0
        if (! $this->solvedProblem($problem)) {
            return 0;
        }

        $incorrect_count = $this->incorrectSubmissionCountForProblem($problem);

        $earliest_solution = $this->earliestCorrectSolutionForProblem($problem);

        $minutes_since_contest = $earliest_solution->created_at->diffInMinutes($starts_at);
        
        return $incorrect_count * 20 + $minutes_since_contest;
    }

    /**
     * Gets all of the solutions submitted by a user.
     */
    public function solutions()
    {
        return $this->hasMany('Solution');
    }

    /**
     * Scores the solutions submitted by a user for a
     * given contest.
     *
     * @param contest $contest the contest to score points on
     * @return int the total number of points for this user
     */
    public function totalPoints($contest)
    {
        $problems = $this->cachedProblems($contest);
        $points = 0;
        foreach ($problems as $problem) {
            $points += $this->pointsForProblem($problem);
        }
        return $points;
    }

    /**
     * Generates a random API key for a user. VERY low chance of non-uniqueness
     *
     * @param The string length for the key. Default is 20
     * @return string
     */
    public static function generateApiKey($length = 20)
    {
        $time = microtime(true) * 10000;

        // reverse the string, so we get most commonly changing bit first
        // which makes the tokens easier to distinguish
        $s =  strrev(sprintf('%x', $time));

        // append random numbers on until we reach our length
        while (strlen($s) < $length) {
            $s .= sprintf('%x', rand());
        }

        // trim off the excess
        return substr($s, 0, $length);
    }

    /**
     * Startup code for the model. We can register some events to the model here.
     */
    public static function boot()
    {
        parent::boot();

        /*
         * Before a user is about to be created, 
         * Create an api key for that user. Before any updates
         * or creations, be sure to hash the password
         */
        User::creating(function($user) {
            $user->api_key = User::generateApiKey();
            \Log::debug('Current password: ' . $user->password);
            $user->password = Hash::make($user->password);
            \Log::debug('New password: ' . $user->password);
        });

        User::updating(function($user) {
            if (Hash::needsRehash($user->password)) {
                $user->password = Hash::make($user->password);
            }
        });
    }

    /**
     * Provides a summary of the given contest for this user
     *
     * @param Contest $contest The contest to summarize
     * @return array The summary data
     */
    public function contestSummary($contest)
    {
        $summary = new ContestSummary;

        $summary->user = $this;
        
        $summary->problems_solved = $this->problemsSolved($contest);

        $summary->penalty_points = $this->totalPoints($contest);

        $summary->problem_summaries = array();

        foreach ($contest->problems as $problem) {
            $problem_info = array();
            $problem_info['points_for_problem'] = $this->pointsForProblem($problem);
            $problem_info['num_submissions'] = $this->incorrectSubmissionCountForProblem($problem)
                + $this->solvedProblem($problem);

            $summary->problem_summaries[] = $problem_info;
        }
        return $summary;
    }

    /*
     * Calculates the number of problems solved for user in a provide
     * contest. If no contest is provided, defaults to the most recent current
     * contest.
     *
     * @param Contest $contest
     * @return int
     */
    public function problemsSolved(Contest $c = null)
    {
        $problems = $this->cachedProblems($c);
        $solutions = $this->cachedSolutions($c);

        /*
         * Loop over every problem, checking if there is a solution
         * that is solved
         */
        $total = 0;
        foreach ($problems as $problem) {
            if ($this->solvedProblem($problem)) {
                $total++;
            }
        }

        // now sum up the solved states
        return $total;
    }

    /**
     * Returns true if this user solved the given problem
     */
    public function solvedProblem(Problem $problem)
    {
        // TODO: Cache this so we don't have to query all the time!
        $solved_state_id = App::make('SolutionStateRepository')->firstCorrectId();

        foreach ($this->cachedSolutions() as $solution) {
            if ($solution->problem_id == $problem->id && $solution->solution_state_id == $solved_state_id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculates the number of incorrect submissions for a given problem
     */
    public function incorrectSubmissionCountForProblem(Problem $p)
    {
        $solutions = $this->cachedSolutions();
        $solved_state_id = App::make('SolutionStateRepository')->firstCorrectId();

        $incorrect_count = 0;
        foreach ($solutions as $solution) {
            if ($solution->problem_id == $p->id && $solution->solution_state_id != $solved_state_id) {
                $incorrect_count++;
            }
        }
        return $incorrect_count;
    }

    public function earliestCorrectSolutionForProblem(Problem $problem)
    {
        $correct_solution = null;
        
        $solved_state_id = App::make('SolutionStateRepository')->firstCorrectId();

        foreach ($this->cachedSolutions() as $solution) {
            $is_solved = $solution->solution_state_id == $solved_state_id ;
            if ($is_solved && $solution->problem_id == $problem->id) {
                if ($correct_solution == null) {
                    $correct_solution = $solution;
                } elseif ($correct_solution->created_at > $solution->created_at) {
                    $correct_solution = $solution;
                }
            }

        }

        return $correct_solution;
    }

    protected function cachedContest()
    {
        if (isset($this->cached_contest)) {
            return $this->cached_contest;
        }

        $this->cached_contest = App::make('ContestRepository')->firstCurrent();

        return $this->cached_contest;
    }

    protected function cachedProblems(Contest $c = null)
    {
        if (isset($this->cached_problems)) {
            return $this->cached_problems;
        }

        $this->cached_problems = App::make('ContestRepository')->problemsForContest($c);

        return $this->cached_problems;
    }

    protected function cachedSolutions(Contest $c = null)
    {
        if (isset($this->cached_solutions)) {
            return $this->cached_solutions;
        }

        $this->cached_solutions = App::make('SolutionRepository')->forUserInContest($this, $c);

        return $this->cached_solutions;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getReminderEmail()
    {
        return $this->email;
    }
}
