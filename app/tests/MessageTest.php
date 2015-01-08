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
