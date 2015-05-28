<?php

use Carbon\Carbon;
use Judge\Models\User;

class UserTest extends TestCase
{
    public function testGetAuthIdentifier()
    {
        $user = new User();
        $user->id = 1;
        $this->assertEquals(1, $user->getAuthIdentifier());
    }

    public function testPointsForProblemIsZeroIfNoSolutionYet()
    {
        $contest = Mockery::mock();
        $contest->starts_at = '2015-01-01 00:00:00';
        $user = Mockery::mock('Judge\Models\User[cachedContest,solvedProblem]');
        $user->shouldReceive('cachedContest')->once()->andReturn($contest);
        $user->shouldReceive('solvedProblem')->once()->andReturn(false);

        $problem = Mockery::mock('Judge\Models\Problem');

        $this->assertEquals(0, $user->pointsForProblem($problem));
    }

    public function testPointsForProblemCalculatedCorrectly()
    {
        $contest = Mockery::mock();
        $contest->starts_at = '2015-01-01 00:00:00';
        $user = Mockery::mock('Judge\Models\User[cachedContest,solvedProblem,incorrectSubmissionCountForProblem,earliestCorrectSolutionForProblem]');
        $user->shouldReceive('cachedContest')->once()->andReturn($contest);
        $user->shouldReceive('solvedProblem')->once()->andReturn(true);

        // two incorrect submissions
        $user->shouldReceive('incorrectSubmissionCountForProblem')->once()->andReturn(2);

        // 50 minutes after contest start
        $solution = Mockery::mock();
        $solution->created_at = Carbon::create(2015, 1, 1, 0, 50, 0);
        $user->shouldReceive('earliestCorrectSolutionForProblem')->once()->andReturn($solution);
        
        $problem = Mockery::mock('Judge\Models\Problem');

        $this->assertEquals(20 + 20 + 50, $user->pointsForProblem($problem));
    }

    public function testSolvedProblem()
    {
        $repo = Mockery::mock()->shouldReceive('hasCorrectSolutionFromUser')->andReturn('TEST')->getMock();
        App::shouldReceive('make')->once()->andReturn($repo);

        $user = new User();
        $this->assertEquals('TEST', $user->solvedProblem(Mockery::mock('Judge\Models\Problem')));
    }

    public function testGetAuthPassword()
    {
        $user = new User();
        $user->password = 'asdf';
        $this->assertEquals('asdf', $user->getAuthPassword());
    }

    public function testContests()
    {
        $user = new User();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $user->contests());
    }

    public function testProblems()
    {
        $user = new User();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $user->solutions());
    }

    public function testGenerateApiKey()
    {
        $user = new User();
        $result = $user->generateApiKey(60);
        $this->assertEquals(60, strlen($result));
    }

    public function testContestSummary()
    {
        $user = Mockery::mock('Judge\Models\User[problemsSolved,totalPoints,pointsForProblem,incorrectSubmissionCountForProblem,solvedProblem]');

        $user->shouldReceive('problemsSolved')->once()->andReturn(1);
        $user->shouldReceive('totalPoints')->once()->andReturn(100);
        $user->shouldReceive('pointsForProblem')->once()->andReturn(10);
        $user->shouldReceive('incorrectSubmissionCountForProblem')->once()->andReturn(1);
        $user->shouldReceive('solvedProblem')->once()->andReturn(1);

        $contest = Mockery::mock('Judge\Models\Contest');
        $contest->shouldReceive('getAttribute')->once()->with('problems')->andReturn([Mockery::mock('Judge\Models\Problem')]);

        $result = $user->contestSummary($contest);

        $this->assertEquals(1, $result->problems_solved);
        $this->assertEquals(100, $result->penalty_points);
        $this->assertEquals(10, $result->problem_summaries[0]['points_for_problem']);
        $this->assertEquals(2, $result->problem_summaries[0]['num_submissions']);
    }

    public function testProblemsSolvedWithUnsolvedProblems()
    {
        $user = Mockery::mock('Judge\Models\User[cachedProblems,solvedProblem]');
        $user->shouldReceive('cachedProblems')->once()->andReturn([
            Mockery::mock('Judge\Models\Problem')
        ]);
        $user->shouldReceive('solvedProblem')->once()->andReturn(false);

        $this->assertEquals(0, $user->problemsSolved());
    }

    public function testProblemsSolvedWithSolvedProblems()
    {
        $user = Mockery::mock('Judge\Models\User[cachedProblems,solvedProblem]');
        $user->shouldReceive('cachedProblems')->once()->andReturn([
            Mockery::mock('Judge\Models\Problem')
        ]);
        $user->shouldReceive('solvedProblem')->once()->andReturn(true);

        $this->assertEquals(1, $user->problemsSolved());
    }

    public function testGetSetRememberToken()
    {
        $user = new User();
        $user->setRememberToken('ABC');
        $this->assertEquals('ABC', $user->getRememberToken());
    }

    public function testGetRememberTokenName()
    {
        $user = new User();
        $this->assertEquals('remember_token', $user->getRememberTokenName());
    }

    public function testGetReminderEmail()
    {
        $user = new User();
        $user->email = 'EMAIL';
        $this->assertEquals('EMAIL', $user->getReminderEmail());
    }

    public function testSentMessages()
    {
        $user = new User();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $user->sentMessages());
    }
}
