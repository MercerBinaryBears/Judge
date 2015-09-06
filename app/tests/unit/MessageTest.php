<?php

use \Mockery;
use Judge\Models\Message;

class MessageTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->message = new Message();
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function testContestRelationship()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $this->message->contest());
    }

    public function testProblemRelationship()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $this->message->problem());
    }

    public function testSenderRelationship()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $this->message->sender());
    }

    public function testResponderRelationship()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $this->message->responder());
    }

    public function testTextAttribute()
    {
        $this->message->text = "hello\nworld";
        $this->assertEquals("hello<br />\nworld", $this->message->text);
    }

    public function testResponseTextAttribute()
    {
        $this->message->response_text = "hello\nworld";
        $this->assertEquals("hello<br />\nworld", $this->message->response_text);
    }
}
