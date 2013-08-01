<?php

class HomeController extends BaseController {

	public function index() {
		return View::make('layout');
	}
}