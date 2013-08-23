<?php

class ApiController extends BaseController {

	/**
	 * API function to get all SolutionTypes as an id:name pair
	 */
	public function getSolutionStates() {
		return json_encode(SolutionState::all()->toArray());
	}

	/**
	 * API function to claim and retrieve a problem
	 */
	public function getSolution($id) {
		$user_id = Sentry::getUser()->id;

		// check that either no judge has claimed the solution, or the current user has
		// If the solution is claimed, redirect back with an error message
		$solution = Solution::find($id);
		if($solution->claiming_judge != null && $solution->claiming_judge->id != $user_id) {
			App::abort(403, 'That solution has already been claimed by ' . $solution->claiming_judge->username);
		}

		// No one has claimed the file, so the current judge claims it.
		// we update the record. If the save failed we flash the error
		// and redirect to the judge index
		$solution->claiming_judge_id = $user_id;
		if(!$solution->save()) {
			App::abort(400, $solution->errors());
		}

		return $solution;
	}

	/**
	 * Updates the status of a submission via the API
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
				App::abort(400, $s->errors());
			}
		}
		else {
			App::abort(403, 'You are not the claiming judge for this problem any more');
		}

		return json_encode(array(
			'status' => 'success'
			));
	}

	/**
	 * API function to unclaim a problem
	 */
	public function unclaim($id) {
		$s = Solution::find($id);
		$judge_id = Sentry::getUser()->id;

		if($s->claiming_judge_id == $judge_id) {
			// the user is the claiming judge, he can edit this solution
			$s->claiming_judge_id = null;
			$s->solution_state_id = SolutionState::pending()->id;
			if(!$s->save()) {
				App::abort(400, $s->errors());
			}
		}
		else {
			App::abort(403, 'You are not the claiming judge for this problem');
		}

		return json_encode(array(
			'status' => 'success'
			));
	}
}