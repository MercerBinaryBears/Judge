<?php

use Judge\Controllers\BaseController;
use Illuminate\Support\Facades\View;
use Laracasts\TestDummy\Factory;

class DbBaseControllerTest extends DbTestCase
{
    public function testBindContestNameWithNoContest()
    {
        View::shouldReceive('share')->once()->with('contest_name', 'Judge');
        View::shouldReceive('share')->once()->with('message_count', 0);
        View::shouldReceive('share')->once()->with('unjudged_count', 0);
        App::make('Judge\Controllers\BaseController');
    }

    public function testBindContestNameWithContest()
    {
        Factory::create('contest', [
            'name' => 'CONTEST NAME'
        ]);

        View::shouldReceive('share')->once()->with('contest_name', 'CONTEST NAME');
        View::shouldReceive('share')->once()->with('message_count', 0);
        View::shouldReceive('share')->once()->with('unjudged_count', 0);
        App::make('Judge\Controllers\BaseController');
    }
}
