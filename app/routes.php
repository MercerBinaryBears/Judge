<?php

Route::group(array('namespace' => 'Judge\Controllers'), function () {

    // home route. This is where the scoreboard lives.
    Route::get('/', array('as'=>'index', 'uses'=>'HomeController@index'));

    Route::get('/login', array('uses' => 'HomeController@login'));

    Route::group(array('before' => 'auth'), function () {
        Route::get('/solutions', array('as' => 'solutions.index', 'uses' => 'SolutionController@index'));

        Route::get('/messages', array('as' => 'messages.index', 'uses' => 'MessageController@index'));
    });

    // judge routes
    Route::group(array('before'=>'judge'), function(){
        // TODO: Perhaps we should embed the user's id into the URL, to be RESTful? However, this
        // means we have more validation to do in that route...
        Route::get('/judge/solutions/{id}/edit', array('as'=>'edit_solution', 'uses'=>'SolutionController@edit'));
        Route::post('/judge/solutions/{id}/edit', array('as'=>'update_solution', 'uses'=>'SolutionController@update'));
        Route::post('/judge/solutions/{id}/unclaim', array('as'=>'unclaim_solution', 'uses'=>'SolutionController@unclaim'));
        Route::get('/judge/solutions/{id}/package', array('as'=>'solution_package', 'uses'=>'SolutionController@package'));
        Route::post('/messages/{id}', array('uses' => 'MessageController@update'));
    });

    // team routes
    Route::group(array('before'=>'team'), function(){
        // there is no create route, since the create form is on the main page for teams
        // TODO: Perhaps we should embed the user's id into the URL, to be RESTful? However, this
        // means we have more validation to do in that route...
        Route::post('/team/solutions/store', array('as'=>'store_solution', 'uses'=>'SolutionController@store'));
        Route::post('/messages', array('uses' => 'MessageController@store'));
    });

    // Authentication routes
    Route::post('/login', array('as'=>'login', 'uses'=>'UserController@login'));
    Route::get('/logout', array('as'=>'logout', 'uses'=>'UserController@logout'));
});
