<?php

use Judge\Controllers\MessageController;
use Laracasts\TestDummy\Factory;

class DbMessageControllerTest extends DbTestCase
{
    public function testIndexForJudge()
    {
        $judge = Factory::create('judge');
        Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn($judge);

        Factory::create('problem');
        Factory::create('message', [
            'is_global' => false,
            'responder_id' => null
        ]);

        $view = App::make('Judge\Controllers\MessageController')->index();
        $this->assertCount(1, $view['problems']);
        $this->assertCount(1, $view['unresponded_messages']);
    }

    public function testIndexForTeam()
    {
        $team = Factory::create('team');
        Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn($team);

        Factory::create('problem');
        Factory::create('message', [
            'sender_id' => $team->id
        ]);
        Factory::create('message', [
            'is_global' => true
        ]);

        $view = App::make('Judge\Controllers\MessageController')->index();
        $this->assertCount(1, $view['problems']);
        $this->assertCount(1, $view['messages']);
        $this->assertCount(1, $view['global_messages']);
    }
}
