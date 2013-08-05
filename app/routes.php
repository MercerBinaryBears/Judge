<?php

// home routes
Route::get('/', 'HomeController@index');
Route::get('/scoreboard', 'HomeController@scoreboard');

// judge routes
Route::group(array('before'=>'judge'), function(){
	Route::get('/judge', 'SolutionController@judgeIndex');
	Route::get('/judge/solutions/{id}/edit', 'SolutionController@edit');
	Route::post('/judge/solutions/{id}/edit', 'SolutionController@update');
});

/*
	User routes
*/
Route::post('/login', 'UserController@login');
Route::get('/logout', 'UserController@logout');