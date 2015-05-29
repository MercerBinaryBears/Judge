<?php namespace Judge\Controllers;

use \App;
use \Auth;
use \Input;
use \Redirect;
use \Response;
use \Session;
use \View;

use Judge\Models\Solution;

class SolutionController extends BaseController
{
    public function index()
    {
        // If the user is a judge, show them the judging page
        if (Auth::user()->judge || Auth::user()->admin) {
            return View::make('Solutions.judge')
                ->with('unjudged_solutions', $this->solutions->judgeableForContest())
                ->with('claimed_solutions', $this->solutions->claimedByJudgeInContest(Auth::user()))
                ->with('api_key', Auth::user()->api_key);
        }

        // otherwise, show a team submission page page
        return View::make('Solutions.team')
            ->with('solutions', $this->solutions->forUserInContest(Auth::user()))
            ->with('problems', $this->contests->problemsForContest())
            ->with('languages', $this->languages->all());
    }
    
    /**
     * Shows the update form for a submission (only viewable by judges), also
     * forces a judge to "claim" the submission, so no other judge can edit
     * it. If a judge visits an already claimed problem, they should be
     * redirected to the judgeIndex page.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        // get the solution passed
        $solution = $this->solutions->find($id);

        // claim the problem, reporting errors if the user couldn't claim it
        if (!$solution->claim()) {
            Session::flash('error', 'You cannot claim that solution');
            return Redirect::route('solutions.index');
        }

        // All saved well, show the judge the form for them to judge
        return View::make('forms.edit_solution')
            ->with('solution', $solution)
            ->with('solution_states', $this->solution_states->all());
    }
    
    /**
     * Saves a team's uploaded submission. Only accessable by teams
     *
     * @return Response
     */
    public function store()
    {
        $solution = new Solution([
            'problem_id' => Input::get('problem_id'),
            'language_id' => Input::get('language_id'),
            'user_id' => Auth::id(),
            'solution_state_id' => $this->solution_states->firstPendingId()
        ]);

        Session::put('language_preference', Input::get('language_id'));

        // Take the uploaded file, save it to a permanent path, reading the file
        // from Input::file('solution_code'), saving the path to
        // $solution->solution_code, the client's name for the file in
        // $solution->solution_filename, ignoring the file extension
        $solution->processUpload('solution_code', 'solution_code', 'solution_filename', null);

        // Save, (attempting validation). If validation fails, we show the errors before saving.
        // Otherwise, the team will see the file in their list of submitted problems
        if (!$solution->save()) {
            Session::flash('error', $solution->errors());
        }

        return Redirect::route('solutions.index');
    }
    
    /**
     * Updates the status of a submission, only allowed by judges
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $solution = $this->solutions->find($id);

        // Check that this current judge has claimed the problem
        // Check validation on save, and report errors if any. There shouldn't be, but
        // malicious input could cause it.
        if ($solution->ownedByCurrentUser()) {
            $solution->solution_state_id = Input::get('solution_state_id');
            if (!$solution->save()) {
                Session::flash('error', $s->errors());
            }
        } else {
            Session::flash('error', 'You are not the claiming judge for this problem any more');
        }

        return Redirect::route('solutions.index');
    }

    /**
     * Unclaims a solution if the current user had claimed that solution
     *
     * @param int $id The id of the solution to unclaim
     */
    public function unclaim($id)
    {
        $solution = $this->solutions->find($id);

        if (!$solution->unclaim()) {
            Session::flash('error', 'You are not the claiming judge for this problem');
        }
        return Redirect::route('solutions.index');
    }

    /**
     * Creates a download package for judges to judge a problem
     */
    public function package($id)
    {
        // get the requested solution
        $solution = $this->solutions->find($id);

        $zip_path = App::make('Judge\Models\SolutionPackageFactory')
            ->setSolution($solution)
            ->getPath();

        // download the zip file
        return Response::download($zip_path);
    }
}
