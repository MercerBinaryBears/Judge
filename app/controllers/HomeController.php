<?php

class HomeController extends BaseController {

	/**
	 * The index route for the Judge site. Will contain a scoreboard
	 * that auto-refreshes (we can do that part later)
	 */
	public function index() {
		return View::make('index');
	}
}
