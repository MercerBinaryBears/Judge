<?php

class ApiController extends BaseController {

	/**
	 * API function to get all SolutionTypes as an id:name pair
	 */
	public function getSolutionStates() {
		return ApiController::formatJSend(SolutionState::all()->toArray());
	}

	/**
	 * API function to claim and retrieve a problem
	 */
	public function claim($id) {
		$user_id = Sentry::getUser()->id;

		// Attempt to claim, returning an error if it occurs
		$solution = Solution::find($id);
		if(!$solution->claim()) {
			App::abort(403, 'That solution has already been claimed by ' . $solution->claiming_judge->username);
		}

		// No one has claimed the file, so the current judge claims it.
		// we update the record. If the save failed we flash the error
		// and redirect to the judge index
		$solution->claiming_judge_id = $user_id;
		if(!$solution->save()) {
			App::abort(400, $solution->errors());
		}

		return ApiController::formatJSend(array('solution'=>$solution));
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
		if($s->ownedByCurrentUser()) {
			$s->solution_state_id = Input::get('solution_state_id');
			if(!$s->save()) {
				App::abort(400, $s->errors());
			}
		}
		else {
			App::abort(403, 'You are not the claiming judge for this problem any more');
		}

		return ApiController::formatJSend();
	}

	/**
	 * API function to unclaim a problem
	 */
	public function unclaim($id) {
		$s = Solution::find($id);

		if($s->ownedByCurrentUser()) {
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

		return ApiController::formatJSend();
	}

	/**
	 * Formats an array in JSEND format
	 *
	 * @param array $data The data to send to the user
	 * @param bool $success If the response is a success
	 * @param int $code The HTTP status code of the response. Defaults to 200
	 * @param string $message The message to send to the user
	 */
	public static function formatJSend($data=array(), $success=true, $code=200, $message='') {
		return json_encode(
			array(
				'status' => $success ? 'success' : 'error',
				'code' => $code,
				'message' => $message,
				'data' => $data
				)
			);
	}
}