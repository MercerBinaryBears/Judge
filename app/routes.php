<?php

// home routes
Route::get('/', array('as'=>'index', 'uses'=>'HomeController@index'));
Route::get('/scoreboard', array('as'=>'scoreboard', 'uses'=>'HomeController@scoreboard'));

// judge routes
Route::group(array('before'=>'judge'), function(){
	Route::get('/judge',  array('as'=>'judge_index', 'uses'=>'SolutionController@judgeIndex'));
	Route::get('/judge/solutions/{id}/edit', array('as'=>'edit_solution', 'uses'=>'SolutionController@edit'));
	Route::post('/judge/solutions/{id}/edit', array('as'=>'update_solution', 'uses'=>'SolutionController@update'));
});

// team routes
Route::group(array('before'=>'team'), function(){
	Route::get('/team', array('as'=>'team_index', 'uses'=>'SolutionController@teamIndex'));
});

/*
	User routes
*/
Route::post('/login', array('as'=>'login', 'uses'=>'UserController@login'));
Route::get('/logout', array('as'=>'logout', 'uses'=>'UserController@logout'));