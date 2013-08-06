<?php

class SolutionController extends BaseController {

	public function teamIndex() {
		$problems = array();
		foreach(Problem::forCurrentContest()->get() as $problem) {
			$problems[$problem->id] = $problem->name;
		}
		return View::make('solutions_team')
			->with('solutions', Solution::forCurrentContest()->where('user_id', Sentry::getUser()->id)->get())
			->with('problems', $problems);
	}

	public function judgeIndex() {
		return View::make('solutions_judge')
			->with('solutions', Solution::forCurrentContest()->unjudged()->unclaimed()->get());
	}

	/**
	 * Saves an uploaded submission
	 *
	 * @return Response
	 */
	public function store()
	{
		var_dump(Input::all());
		return;
	}

	/**
	 * Shows the update form for a submission, also forces a judge
	 * to claim the submission
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		// TODO: this will be moved to a route filter
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
		if($solution->claiming_judge_id != null) {
			// TODO: Session flash that problem has been claimed by Judge X
			return Redirect::route('judge_index');
		}
		$solution->claiming_judge_id = $user->id;
		$solution->save();

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
		$unjudged_state = SolutionState::where('name','LIKE', '%judging%')->first();

		// TODO: Validate
		$s = Solution::find($id);
		if($s->claiming_judge_id == null && $s->solution_state_id == $unjudged_state->id) {
			$s->solution_state_id = Input::get('solution_state_id');
			$s->save();
		}

		// TODO: Use named routes
		return Redirect::route('judge_index');
	}
}