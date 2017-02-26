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

        $contest = Factory::create('contest');

        Factory::create('problem', ['contest_id' => $contest->id]);
        Factory::create('message', [
            'responder_id' => null,
            'contest_id' => $contest->id
        ]);

        $view = App::make('Judge\Controllers\MessageController')->index();
        $this->assertCount(1, $view['problems']);
        $this->assertCount(1, $view['unresponded_messages']);
    }

    public function testIndexForTeam()
    {
        $contest = Factory::create('contest');

        $team = Factory::create('team');
        Auth::shouldReceive('user')->zeroOrMoreTimes()->andReturn($team);

        Factory::create('problem', ['contest_id' => $contest->id]);
        Factory::create('message', [
            'sender_id' => $team->id,
            'contest_id' => $contest->id
        ]);
        Factory::create('global_message', ['contest_id' => $contest->id]);

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
        $this->assertEquals($team->id, $message->sender_id);
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
        $this->assertEquals($judge->id, $message->sender_id);
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
