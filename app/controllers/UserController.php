<?php

class UserController extends BaseController {
	/**
	 * Attempt login for a user.
	 *
	 * @return Response
	 */
	public function login()
	{
		$creds = array(
			'username' => Input::get('username'),
			'password' => Input::get('password')
			);

		Auth::attempt($creds, true);

		if(Auth::check()) {
			return Redirect::route('index');
		}

		else {
			return Redirect::route('index')->withInput(Input::except('password'));
		}
	}

	/**
	 * Logs the user out
	 */
	public function logout()
	{
		Auth::logout();
		return Redirect::route('index');
	}

}