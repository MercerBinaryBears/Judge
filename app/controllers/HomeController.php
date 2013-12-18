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

		$contest_summaries = new ContestSummaryCollection();

		foreach($this->contests->usersForContest($current_contest) as $user) {

			$contest_summaries->add( $user->contestSummary($current_contest) );

		}

		return View::make('index')
			->with('contest_summaries', $contest_summaries)
			->with('problems', $this->contests->problemsForContest($current_contest));
	}
}
