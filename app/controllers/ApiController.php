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
}