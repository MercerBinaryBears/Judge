<?php
class JudgeController extends BaseController {
	/**
	 * The index for a judge. Display the current unjudged problems, and allows
	 * the judge to claim that problem.
	 */
	public function index() {
		return View::make('solutions_judge')
			->with('unjudged_solutions', Solution::forCurrentContest()->unjudged()->unclaimed()->get())
			->with('claimed_solutions', Solution::forCurrentContest()->where('claiming_judge_id', Sentry::getUser()->id)->get());
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
		$solution = Solution::find($id);

		// claim the problem, reporting errors if the user couldn't claim it
		if(!$solution->claim()) {
			Session::flash('error', 'You cannot claim that solution');
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
		$solution = Solution::find($id);

		// Check that this current judge has claimed the problem
		// Check validation on save, and report errors if any. There shouldn't be, but
		// malicious input could cause it.
		if($solution->ownedByCurrentUser()) {
			$solution->solution_state_id = Input::get('solution_state_id');
			if(!$solution->save()) {
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
		$solution = Solution::find($id);

		if(!$solution->unclaim()) {
			Session::flash('error', 'You are not the claiming judge for this problem');
		}
		return Redirect::route('judge_index');
	}

	/**
	 * Creates a download package for judges to judge a problem
	 */
	public function package($id) {
		// get the requested solution
		$solution = Solution::find($id);

		$solution_package = new SolutionPackage($solution);

		// download the zip file
		return Response::download($solution_package->getPath());
	}
}