<?php

use Judge\Controllers\BaseController;
use Illuminate\Support\Facades\View;
use Laracasts\TestDummy\Factory;

class DbBaseControllerTest extends DbTestCase
{
    public function testBindContestNameWithNoContest()
    {
        View::shouldReceive('share')->once()->with('contest_name', 'Judge');
        App::make('Judge\Controllers\BaseController');
    }

    public function testBindContestNameWithContest()
    {
        Factory::create('contest', [
            'name' => 'CONTEST NAME'
        ]);

        View::shouldReceive('share')->once()->with('contest_name', 'CONTEST NAME');
        App::make('Judge\Controllers\BaseController');
    }
}
