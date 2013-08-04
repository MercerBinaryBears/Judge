<?php

class JudgeController extends BaseController {
	public function index() {
		return View::make('judge')->with('solutions', Solution::forCurrentContest()->get());
	}
}