<?php

// IOC Binding
App::bind('Judge\Models\Contest\ContestRepository', 'Judge\Models\Contest\EloquentContestRepository');
App::bind('Judge\Models\Language\LanguageRepository', 'Judge\Models\Language\EloquentLanguageRepository');
App::bind('Judge\Models\Problem\ProblemRepository', 'Judge\Models\Problem\EloquentProblemRepository');
App::bind('Judge\Models\Solution\SolutionRepository', 'Judge\Models\Solution\EloquentSolutionRepository');
App::bind('Judge\Models\SolutionState\SolutionStateRepository', 'Judge\Models\SolutionState\EloquentSolutionStateRepository');

Route::group(array('namespace' => 'Judge\Controllers'), function () {

    // home route. This is where the scoreboard lives.
    Route::get('/', array('as'=>'index', 'uses'=>'HomeController@index'));

    // judge routes
    Route::group(array('before'=>'judge'), function(){
        Route::get('/judge',  array('as'=>'judge_index', 'uses'=>'JudgeController@index'));
        // TODO: Perhaps we should embed the user's id into the URL, to be RESTful? However, this
        // means we have more validation to do in that route...
        Route::get('/judge/solutions/{id}/edit', array('as'=>'edit_solution', 'uses'=>'JudgeController@edit'));
        Route::post('/judge/solutions/{id}/edit', array('as'=>'update_solution', 'uses'=>'JudgeController@update'));
        Route::post('/judge/solutions/{id}/unclaim', array('as'=>'unclaim_solution', 'uses'=>'JudgeController@unclaim'));
        Route::get('/judge/solutions/{id}/package', array('as'=>'solution_package', 'uses'=>'JudgeController@package'));

        Route::get('/judge/messages', array('uses' => 'MessagesController@index'));
    });

    // team routes
    Route::group(array('before'=>'team'), function(){
        Route::get('/team', array('as'=>'team_index', 'uses'=>'TeamController@teamIndex'));
        // there is no create route, since the create form is on the main page for teams
        // TODO: Perhaps we should embed the user's id into the URL, to be RESTful? However, this
        // means we have more validation to do in that route...
        Route::post('/team/solutions/store', array('as'=>'store_solution', 'uses'=>'TeamController@store'));
        
        Route::get('/team/messages', array('uses' => 'MessagesController@index'));
    });

    // Authentication routes
    Route::post('/login', array('as'=>'login', 'uses'=>'UserController@login'));
    Route::get('/logout', array('as'=>'logout', 'uses'=>'UserController@logout'));

    // API Routes
    Route::group(array('before'=>'apiAuth', 'prefix'=>'api'), function(){

        // a ping pong route to verify api access
        Route::get('ping', array('as'=>'api_ping', function(){
            return ApiController::formatJSend('pong');
        }));

        // provide solution types
        Route::get('solutionStates', array('as'=>'', 'uses'=>'ApiController@getSolutionStates'));

        // Solution API routes
        Route::group(array('prefix'=>'solutions'), function(){

            // a listing of all available solutions
            Route::get('', array('as'=>'api_get', 'uses'=>'ApiController@show'));

            // claiming of solutions
            Route::get('{id}/claim', array('as'=>'api_claim', 'uses'=>'ApiController@claim'));

            // unclaim solutions
            Route::get('{id}/unclaim', array('as'=>'api_unclaim', 'uses'=>'ApiController@unclaim'));

            // update solutions
            Route::post('{id}', array('as'=>'api_update', 'uses'=>'ApiController@update'));

            // solution package
            Route::get('{id}/package', array('as'=>'api_package', 'uses'=>'ApiController@package'));

        });
    });
});
