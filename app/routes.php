<?php

// home routes
Route::get('/', 'HomeController@index');
Route::get('/portal', 'HomeController@portal');
Route::get('/scoreboard', 'HomeController@scoreboard');

/*
	User routes
*/
Route::post('/login', 'UserController@login');
Route::get('/logout', 'UserController@logout');