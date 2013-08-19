<?php

class ApiController extends BaseController {

	/**
	 * API function to get all SolutionTypes as an id:name pair
	 */
	public function getSolutionStates() {
		return json_encode(SolutionState::all()->toArray());
	}
}