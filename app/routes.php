<?php

// home route. This is where the scoreboard lives.
Route::get('/', array('as'=>'index', 'uses'=>'HomeController@index'));

// judge routes
Route::group(array('before'=>'judge'), function(){
	Route::get('/judge',  array('as'=>'judge_index', 'uses'=>'SolutionController@judgeIndex'));
	// TODO: Perhaps we should embed the user's id into the URL, to be RESTful? However, this
	// means we have more validation to do in that route...
	Route::get('/judge/solutions/{id}/edit', array('as'=>'edit_solution', 'uses'=>'SolutionController@edit'));
	Route::post('/judge/solutions/{id}/edit', array('as'=>'update_solution', 'uses'=>'SolutionController@update'));
	Route::post('/judge/solutions/{id}/unclaim', array('as'=>'unclaim_solution', 'uses'=>'SolutionController@unclaim'));
});

// team routes
Route::group(array('before'=>'team'), function(){
	Route::get('/team', array('as'=>'team_index', 'uses'=>'SolutionController@teamIndex'));
	// there is no create route, since the create form is on the main page for teams
	// TODO: Perhaps we should embed the user's id into the URL, to be RESTful? However, this
	// means we have more validation to do in that route...
	Route::post('/team/solutions/store', array('as'=>'store_solution', 'uses'=>'SolutionController@store'));
});

// Authentication routes
Route::post('/login', array('as'=>'login', 'uses'=>'UserController@login'));
Route::get('/logout', array('as'=>'logout', 'uses'=>'UserController@logout'));