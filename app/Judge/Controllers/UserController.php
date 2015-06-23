<?php namespace Judge\Controllers;

use \Auth;
use \Input;
use \Redirect;

class UserController extends BaseController
{
    /**
     * Attempt login for a user.
     *
     * @return Response
     */
    public function login()
    {
        if (Auth::attempt(Input::only('username', 'password'))) {
            return Redirect::route('index');
        }

        return Redirect::to('login')->withInput(Input::except('password'));
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
