<?php

class ClassWrapperTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->wrapped = Mockery::mock('foo');
        $this->wrapper = new \Judge\Cache\CacheWrapper($this->wrapped);
    }

    public function testUsesCacheWhenEnabled()
    {
        $this->wrapped->shouldReceive('methodName')->once()->andReturn('okie dokie');

        $this->wrapper->enable();

        $result = $this->wrapper->methodName();
        $this->assertEquals('okie dokie', $result);

        $second_result = $this->wrapper->methodName();
        $this->assertEquals('okie dokie', $second_result);
    }

    public function testNoUsesCacheWhenDisabled()
    {
        $this->wrapped->shouldReceive('methodName')->twice()->andReturn('okie dokie');

        $this->wrapper->disable();

        $result = $this->wrapper->methodName();
        $this->assertEquals('okie dokie', $result);

        $second_result = $this->wrapper->methodName();
        $this->assertEquals('okie dokie', $second_result);
    }

    public function testCanDistinguishDifferentModelTypes()
    {
        $contest = new Judge\Models\Contest();
        $contest->id = 1;
        $user = new Judge\Models\User();
        $user->id = 1;

        $this->wrapped->shouldReceive('methodName')->once()->with($contest)->andReturn('contest');
        $this->wrapped->shouldReceive('methodName')->once()->with($user)->andReturn('user');

        $result = $this->wrapper->methodName($contest);
        $this->assertEquals('contest', $result);

        $second_result = $this->wrapper->methodName($user);
        $this->assertEquals('user', $second_result);
    }

    public function testCanDistinguishDifferentModelIds()
    {
        $contest1 = new Judge\Models\Contest();
        $contest1->id = 1;
        $contest2 = new Judge\Models\Contest();
        $contest2->id = 2;

        $this->wrapped->shouldReceive('methodName')->once()->with($contest1)->andReturn('contest1');
        $this->wrapped->shouldReceive('methodName')->once()->with($contest2)->andReturn('contest2');

        $result = $this->wrapper->methodName($contest1);
        $this->assertEquals('contest1', $result);

        $second_result = $this->wrapper->methodName($contest2);
        $this->assertEquals('contest2', $second_result);
    }

    public function testCanDistinguishDifferentDates()
    {
        $date1 = Carbon\Carbon::now();
        $date2 = Carbon\Carbon::now()->subDay();

        $this->wrapped->shouldReceive('methodName')->once()->with($date1)->andReturn('date1');
        $this->wrapped->shouldReceive('methodName')->once()->with($date2)->andReturn('date2');

        $result = $this->wrapper->methodName($date1);
        $this->assertEquals('date1', $result);

        $second_result = $this->wrapper->methodName($date2);
        $this->assertEquals('date2', $second_result);
    }

    public function testCanDistinguishDifferentStrings()
    {
        $string1 = 'string1';
        $string2 = 'string2';

        $this->wrapped->shouldReceive('methodName')->once()->with($string1)->andReturn('string1');
        $this->wrapped->shouldReceive('methodName')->once()->with($string2)->andReturn('string2');

        $result = $this->wrapper->methodName($string1);
        $this->assertEquals('string1', $result);

        $second_result = $this->wrapper->methodName($string2);
        $this->assertEquals('string2', $second_result);
    }
}
