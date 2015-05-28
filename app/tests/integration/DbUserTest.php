<?php

use Judge\Models\User;
use Laracasts\TestDummy\Factory;

class DbUserTest extends DbTestCase
{
    public function testCreatingEvent()
    {
        $user = Factory::create('team', ['password' => 'PASSWORD', 'api_key' => '']);

        $this->assertNotEquals('PASSWORD', $user->password);
    }

    public function testCachedContestForFirstCall()
    {
        $contest = Factory::create('contest');
        $user = Factory::create('team');

        $this->assertEquals($contest->id, $user->cachedContest()->id);
    }

    public function testCachedContestForNextCall()
    {
        $contest = Factory::create('contest');
        $user = Factory::create('team');
        $user->cachedContest();

        $this->assertEquals($contest->id, $user->cachedContest()->id);
    }

    public function testCachedProblemsForFirstCall()
    {
        Factory::create('problem');
        $user = Factory::create('team');
        $this->assertCount(1, $user->cachedProblems());
    }

    public function testCachedProblemsForNextCall()
    {
        Factory::create('problem');
        $user = Factory::create('team');
        $user->cachedProblems();
        $this->assertCount(1, $user->cachedProblems());
    }

    public function testCachedSolutionsForFirstCall()
    {
        $solution = Factory::create('solution');
        $user = $solution->user;

        $this->assertCount(1, $user->cachedSolutions());
    }

    public function testCachedSolutionsForNextCall()
    {
        $solution = Factory::create('solution');
        $user = $solution->user;
        $user->cachedSolutions();

        $this->assertCount(1, $user->cachedSolutions());
    }
}
