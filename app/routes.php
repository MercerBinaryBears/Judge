<?php

Route::get('/', 'HomeController@index');

/*
	User routes
*/
Route::post('/login', 'UserController@login');
Route::get('/logout', 'UserController@logout');