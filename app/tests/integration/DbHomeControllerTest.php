<?php

use Judge\Controllers\HomeController;
use Laracasts\TestDummy\Factory;

class DbHomeControllerTest extends DbTestCase
{
    public function testIndexWithNoContest()
    {
        $controller = App::make('Judge\Controllers\HomeController');
        
        $view = $controller->index();
        $this->assertCount(0, $view['contest_summaries']);
        $this->assertCount(0, $view['problems']);
    }

    public function testIndexWithAContest()
    {
        $solution = Factory::create('solution');
        $solution->problem->contest->users()->attach($solution->user->id);

        $controller = App::make('Judge\Controllers\HomeController');
        $view = $controller->index();
        $this->assertCount(1, $view['contest_summaries']);
        $this->assertCount(1, $view['problems']);
    }
}
