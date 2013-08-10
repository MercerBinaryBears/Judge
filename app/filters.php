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
	return Sentry::check();
});

Route::filter('admin', function(){
	if(!Sentry::check()) {
		return Redirect::to('/');
	}
	else if(!Sentry::getUser()->admin) {
		return Redirect::to('/');
	}
});

Route::filter('judge', function(){
	if(!Sentry::check()) {
		return Redirect::to('/');
	}
	else if(!Sentry::getUser()->judge && !Sentry::getUser()->admin) {
		return Redirect::to('/');
	}
});

Route::filter('team', function() {
	if(!Sentry::check()) {
		return Redirect::to('/');
	}
	else if(!Sentry::getUser()->team && !Sentry::getUser()->admin) {
		return Redirect::to('/');
	}
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