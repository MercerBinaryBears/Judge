<?php

class SolutionController extends BaseController {

	/**
	 * The index page for a team. Displays their current submitted problems,
	 * the current state of those problems, and a form for submitting a new
	 * problem
	 */
	public function teamIndex() {
		$problems = array();

		return View::make('solutions_team')
			->with('solutions', Solution::forCurrentContest()->where('user_id', Sentry::getUser()->id)->get())
			->with('problems', Problem::lists('name', 'id'));
	}

	/**
	 * The index for a judge. Display the current unjudged problems, and allows
	 * the judge to claim that problem.
	 */
	public function judgeIndex() {
		return View::make('solutions_judge')
			->with('unjudged_solutions', Solution::forCurrentContest()->unjudged()->unclaimed()->get())
			->with('claimed_solutions', Solution::forCurrentContest()->where('claiming_judge_id', Sentry::getUser()->id)->get());
	}

	/**
	 * Saves a team's uploaded submission. Only accessable by teams
	 *
	 * @return Response
	 */
	public function store()
	{
		$solution_state_id = SolutionState::pending()->id;

		$solution = new Solution();

		// populate with form fields
		$solution->problem_id = Input::get('problem_id');
		$solution->user_id = Sentry::getUser()->id;
		$solution->solution_state_id = $solution_state_id;

		/*
		| Take the uploaded file, save it to a permanent path, reading the file
		| from Input::file('solution_code'), saving the path to
		| $solution->solution_code, the client's name for the file in
		| $solution->solution_filename, and the file extension in $solution->language
		*/
		$solution->processUpload('solution_code', 'solution_code', 'solution_filename', 'solution_language');

		// Save, (attempting validation). If validation fails, we show the errors before saving.
		// Otherwise, the team will see the file in their list of submitted problems
		if(!$solution->save()) {
			Session::flash('error', $solution->errors());
		}

		return Redirect::route('team_index');
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
		$user_id = Sentry::getUser()->id;

		// check that either no judge has claimed the solution, or the current user has
		// If the solution is claimed, redirect back with an error message
		$solution = Solution::find($id);
		if($solution->claiming_judge_id != null && $solution->claiming_judge_id != $user_id) {
			Session::flash('error', 'That solution has already been claimed by ' . $solution->claiming_judge->username);
			return Redirect::route('judge_index');
		}

		// No one has claimed the file, so the current judge claims it.
		// we update the record. If the save failed we flash the error
		// and redirect to the judge index
		$solution->claiming_judge_id = Sentry::getUser()->id;
		if(!$solution->save()) {
			Session::flash('error', $solution->errors());
			return Redirect::route('judge_index');
		}

		// All saved well, show the judge the form for them to judge
		return View::make('forms.edit_solution')
			->with('solution', $solution)
			->with('solution_states', SolutionState::lists('name','id'));
	}

	/**
	 * Updates the status of a submission, only allowed by judges
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$s = Solution::find($id);
		$judge_id = Sentry::getUser()->id;

		// Check that this current judge has claimed the problem
		// Check validation on save, and report errors if any. There shouldn't be, but
		// malicious input could cause it.
		if($s->claiming_judge_id == $judge_id) {
			$s->solution_state_id = Input::get('solution_state_id');
			if(!$s->save()) {
				Session::flash('error', $s->errors());
			}
		}
		else {
			Session::flash('error', 'You are not the claiming judge for this problem any more');
		}

		return Redirect::route('judge_index');
	}

	/**
	 * Unclaims a solution if the current user had claimed that solution
	 *
	 * @param int $id The id of the solution to unclaim
	 */
	public function unclaim($id) {
		$s = Solution::find($id);
		$judge_id = Sentry::getUser()->id;

		if($s->claiming_judge_id == $judge_id) {
			// the user is the claiming judge, he can edit this solution
			$s->claiming_judge_id = null;
			if(!$s->save()) {
				Session::flash('error', $s->errors());
			}
		}
		else {
			Session::flash('error', 'You are not the claiming judge for this problem');
		}

		return Redirect::route('judge_index');
	}
}