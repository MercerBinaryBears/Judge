<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| Filters to determine if a user is a judge, team, admin, or not logged in
|
*/

Route::filter('auth', function()
{
	return Auth::check();
});

Route::filter('admin', function(){
	if(!Auth::check()) {
		return Redirect::to('/');
	}
	else if(!Auth::user()->admin) {
		return Redirect::to('/');
	}
});

Route::filter('judge', function(){
	if(!Auth::check()) {
		return Redirect::to('/');
	}
	else if(!Auth::user()->judge && !Auth::user()->admin) {
		return Redirect::to('/');
	}
});

Route::filter('team', function() {
	if(!Auth::check()) {
		return Redirect::to('/');
	}
	else if(!Auth::user()->team && !Auth::user()->admin) {
		return Redirect::to('/');
	}
});

/*
|-------------------------------------------------------------------------
| API Authentication
|-------------------------------------------------------------------------
|
| Determines if a user should have API access or not
|
*/
Route::filter('apiAuth', function() {

	// if the user did not provide an api key, kick them out
	if(!Input::has('api_key')) {
		App::abort(401, 'No API key provided');
	}

	// find the user with that API key
	$user = User::where('api_key', '=', Input::get('api_key'))->first();

	// check that we found a user
	if($user == null) {
		App::abort(401, 'Invalid API key');
	}

	// lastly, check that they are a judge or admin
	if(!$user->admin && !$user->judge) {
		App::abort(401, 'No Permission');
	}

	// otherwise, we let them through
	Auth::loginUsingId($user->id);
});


/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});