<?php

class HomeController extends BaseController {

	public function index() {
		return View::make('index');
	}

	public function portal() {
		return View::make('portal');
	}

	public function scoreboard() {
		return View::make('scoreboard');
	}
}