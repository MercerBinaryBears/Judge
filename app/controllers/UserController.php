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
			'username' => Input::get('username', 'NOBODY'),
			'password' => Input::get('password', 'NOBODY')
			);

		try {
			Sentry::authenticate($creds);
		}
		catch(Cartalyst\Sentry\Users\LoginRequiredException $e) {
			Session::flash('login_message', 'You must provide a login');
		}
		catch(Cartalyst\Sentry\Users\PasswordRequiredException $e) {
			Session::flash('login_message', 'You must provide a password');
		}
		catch(Cartalyst\Sentry\Users\WrongPasswordException $e) {
			Session::flash('login_message', 'Invalid login');
		}
		catch(Cartalyst\Sentry\Users\UserNotFoundException $e) {
			Session::flash('login_message', 'Invalid login');
		}

		return Redirect::to('/')->withInput(Input::except('password'));
	}

	/**
	 * Logs the user out
	 */
	public function logout()
	{
		Sentry::logout();
		return Redirect::to('/');
	}

}