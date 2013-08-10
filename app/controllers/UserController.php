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

		try {
			// Attempt authentication via Sentry. If it fails, it throws
			// a number of errors, which we catch here.
			Sentry::authenticate($creds);
		}
		catch(Cartalyst\Sentry\Users\LoginRequiredException $e) {
			Session::flash('error', 'You must provide a login');
		}
		catch(Cartalyst\Sentry\Users\PasswordRequiredException $e) {
			Session::flash('error', 'You must provide a password');
		}
		catch(Cartalyst\Sentry\Users\WrongPasswordException $e) {
			Session::flash('error', 'Invalid login');
		}
		catch(Cartalyst\Sentry\Users\UserNotFoundException $e) {
			Session::flash('error', 'Invalid login');
		}

		return Redirect::route('index')->withInput(Input::except('password'));
	}

	/**
	 * Logs the user out
	 */
	public function logout()
	{
		Sentry::logout();
		return Redirect::route('index');
	}

}