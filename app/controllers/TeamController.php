<?php

class TeamController extends BaseController {

	/**
	 * The index page for a team. Displays their current submitted problems,
	 * the current state of those problems, and a form for submitting a new
	 * problem
	 */
	public function teamIndex() {
		$problems = array();

		return View::make('solutions_team')
			->with('solutions', Solution::forCurrentContest()->where('user_id', Auth::user()->id)->get())
			->with('problems', Problem::lists('name', 'id'))
			->with('languages', $this->languages->getSelectBoxData() );
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
		$solution->user_id = Auth::user()->id;
		$solution->solution_state_id = $solution_state_id;
		$solution->language_id = Input::get('language_id');

		/*
		| Take the uploaded file, save it to a permanent path, reading the file
		| from Input::file('solution_code'), saving the path to
		| $solution->solution_code, the client's name for the file in
		| $solution->solution_filename, ignoring the file extension
		*/
		$solution->processUpload('solution_code', 'solution_code', 'solution_filename', null);

		// Save, (attempting validation). If validation fails, we show the errors before saving.
		// Otherwise, the team will see the file in their list of submitted problems
		if(!$solution->save()) {
			Session::flash('error', $solution->errors());
		}

		return Redirect::route('team_index');
	}
}
