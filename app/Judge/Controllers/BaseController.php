<?php namespace Judge\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;

use Judge\Repositories\ContestRepository;
use Judge\Repositories\LanguageRepository;
use Judge\Repositories\ProblemRepository;
use Judge\Repositories\SolutionRepository;
use Judge\Repositories\SolutionStateRepository;
use Judge\Repositories\MessageRepository;
use Judge\Repositories\UserRepository;

class BaseController extends Controller
{
    public function __construct(
        ContestRepository $contests,
        LanguageRepository $languages,
        ProblemRepository $problems,
        SolutionRepository $solutions,
        SolutionStateRepository $solution_states,
        MessageRepository $messages,
        UserRepository $users
    ) {
            
        $this->contests = $contests;
        $this->languages = $languages;
        $this->problems = $problems;
        $this->solutions = $solutions;
        $this->solution_states = $solution_states;
        $this->messages = $messages;
        $this->users = $users;

        $this->bindContestName();
        $this->bindMessageCount();
        $this->bindUnjudgedProblemCount();
    }

    /**
     * Binds a contest name for any view, so that the layout works
     *
     * @return void
     */
    protected function bindContestName()
    {
        $contest_name = 'Judge';
        $contest = $this->contests->firstCurrent();

        if (!is_null($contest)) {
            $contest_name = $contest->name;
        }

        View::share('contest_name', $contest_name);
    }

    /**
     * Binds the number of current unread messages to the view
     */
    protected function bindMessageCount()
    {
        View::share('message_count', $this->messages->unresponded()->count());
    }

    /**
     * Binds the umber of current unjudged problems to the view
     */
    protected function bindUnjudgedProblemCount()
    {
        View::share('unjudged_count', $this->solutions->judgeableForContest()->count());
    }
}
