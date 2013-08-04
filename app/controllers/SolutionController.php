<?php

class SolutionController extends BaseController {

	public function teamIndex() {
		return "HEY";
	}

	public function judgeIndex() {
		return View::make('judge')
			->with('solutions', Solution::forCurrentContest()->unjudged()->unclaimed()->get());
	}

	/**
	 * Saves an uploaded submission
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Shows the update form for a submission, also forces a judge
	 * to claim the submission
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if(!Sentry::check()) {
			App::abort(403);
		}
		$user = Sentry::getUser();
		if(!$user->judge && !$user->admin) {
			App::abort(403);
		}

		// check that the solution isn't claimed already, and
		// make the current judge claim it...
		$solution = Solution::find($id);
		if($solution->claiming_judge != null) {
			return Redirect::to('/judge');
		}
		$solution->claiming_judge = $user->id;

		$solution_states = array();
		foreach(SolutionState::all() as $solution_state) {
			$solution_states[$solution_state->id] = $solution_state->name;
		}

		// return the form
		return View::make('forms.edit_solution')
			->with('solution', $solution)
			->with('solution_states', $solution_states);
	}

	/**
	 * Updates the status of a submission
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
	}
}