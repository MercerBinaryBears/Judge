<?php

class HomeController extends BaseController {
	/**
	 * The index route for the Judge site. Will contain a scoreboard
	 * that auto-refreshes (we can do that part later)
	 */
	public function index() {
		$user_data = array();

		$current_contest = $this->contests->firstCurrent();

		if(is_null($current_contest)) {
			return View::make('index')->with('user_data', array())->with('problems', array());
		}

		foreach($this->contests->usersForContest($current_contest) as $user) {

			$user_data[] = $user->contestSummary($current_contest);
		}

		usort($user_data, function($user_1, $user_2) {

			if($user_1['problems_solved'] === $user_2['problems_solved']) {

				// Sort scores in ascending order
				return $user_1['score'] - $user_2['score'];
			}

			// Sort problems solved in descending order
			return $user_2['problems_solved'] - $user_1['problems_solved'];
		});

		return View::make('index')
			->with('user_data', $user_data)
			->with('problems', $this->contests->problemsForContest($current_contest));
	}
}
