<?php

use Judge\Controllers\MessageController;
use Judge\Models\Message;
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

    public function testStoreForTeam()
    {
        // create the contest
        Factory::create('contest');

        $team = Factory::create('team');
        Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn($team);
        
        $this->action('POST', 'Judge\Controllers\MessageController@store', [
            'text' => 'TEXT'
        ]);

        $message = Message::first();
        $this->assertNotNull($message);
        $this->assertEquals('TEXT', $message->text);
        $this->assertEquals(false, (bool) $message->is_global);
    }

    public function testStoreForJudge()
    {
        // create the contest
        Factory::create('contest');

        $judge = Factory::create('judge');
        Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn($judge);

        $this->action('POST', 'Judge\Controllers\MessageController@store', [
            'text' => 'TEXT'
        ]);

        $message = Message::first();
        $this->assertNotNull($message);
        $this->assertEquals('TEXT', $message->text);
        $this->assertEquals(true, (bool) $message->is_global);
    }

    public function testUpdate()
    {
        $judge = Factory::create('judge');
        $message = Factory::create('message');
        
        $this->action('POST', 'Judge\Controllers\MessageController@update', [$message->id], [
            'responder_id' => $judge->id,
            'response_text' => 'RESPONSE',
            'text' => 'DONT UPDATE'
        ]);

        $message = Message::first();
        $this->assertEquals($judge->id, $message->responder_id);
        $this->assertEquals('RESPONSE', $message->response_text);
        $this->assertNotEquals('DONT UPDATE', $message->text);
    }
}
