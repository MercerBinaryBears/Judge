<?php

use Judge\Models\User;

class UserTest extends TestCase
{
    public function testGetAuthIdentifier()
    {
        $user = new User();
        $user->id = 1;
        $this->assertEquals(1, $user->getAuthIdentifier());
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
}
