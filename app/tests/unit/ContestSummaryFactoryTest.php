<?php

use Carbon\Carbon;
use Judge\Models\Contest;
use Judge\Models\Problem;
use Judge\Models\User;

class ContestSummaryFactoryTest extends DbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->contests = Mockery::mock('Judge\Repositories\ContestRepository');
        $this->problems = Mockery::mock('Judge\Repositories\ProblemRepository');
        $this->solutions = Mockery::mock('Judge\Repositories\SolutionRepository');
        $this->solution_states = Mockery::mock('Judge\Repositories\SolutionStateRepository');
        $this->factory = new Judge\Factories\ContestSummaryFactory(
            $this->contests,
            $this->problems,
            $this->solutions,
            $this->solution_states
        );
    }

    public function testPointsForProblemIsZeroIfNoSolutionYet()
    {
        $problem = new Problem();
        $user = new User();
        $this->solutions->shouldReceive('hasCorrectSolutionFromUser')->once()->with($user, $problem)->andReturn(false);

        $this->assertEquals(0, $this->factory->pointsForProblem($problem, $user));
    }

    public function testPointsForProblemCalculatedCorrectly()
    {
        $problem = new Problem();
        $problem->contest = Mockery::mock();
        $problem->contest->starts_at = Carbon::create(2015, 1, 1, 0, 0, 0);
        $user = new User();

        $this->solutions->shouldReceive('hasCorrectSolutionFromUser')->once()->with($user, $problem)->andReturn(true);

        $this->solutions->shouldReceive('incorrectSubmissionCountFromUserFromProblem')->once()->with($user, $problem)->andReturn(2);

        $solution = Mockery::mock();
        $solution->created_at = Carbon::create(2015, 1, 1, 0, 50, 0);
        $this->solutions->shouldReceive('earliestCorrectSolutionFromUserForProblem')->once()->with($user, $problem)->andReturn($solution);

        $this->assertEquals(20 + 20 + 50, $this->factory->pointsForProblem($problem, $user));
    }

    public function testContestSummary()
    {
        $this->factory = Mockery::mock('Judge\Factories\ContestSummaryFactory[problemsSolved, totalPoints, pointsForProblem]', [
            $this->contests,
            $this->problems,
            $this->solutions,
            $this->solution_states
        ]);
        $this->factory->shouldReceive('problemsSolved')->once()->andReturn(1);
        $this->factory->shouldReceive('totalPoints')->once()->andReturn(100);
        $this->factory->shouldReceive('pointsForProblem')->once()->andReturn(10);
        $this->solutions->shouldReceive('incorrectSubmissionCountFromUserFromProblem')->once()->andReturn(1);
        $this->solutions->shouldReceive('hasCorrectSolutionFromUser')->once()->andReturn(1);

        $contest = Mockery::mock('Judge\Models\Contest');
        $contest->shouldReceive('getAttribute')->once()->with('problems')->andReturn([Mockery::mock('Judge\Models\Problem')]);
        $user = Mockery::mock('Judge\Models\User');

        $result = $this->factory->makeForTeam($contest, $user);

        $this->assertEquals(1, $result->problems_solved);
        $this->assertEquals(100, $result->penalty_points);
        $this->assertEquals(10, $result->problem_summaries[0]['points_for_problem']);
        $this->assertEquals(2, $result->problem_summaries[0]['num_submissions']);
    }

    public function testProblemsSolvedWithUnsolvedProblems()
    {
        $contest = new Contest();
        $user = new User();
        $this->problems->shouldReceive('forContest')->once()->andReturn([
            new Problem()
        ]);
        $this->solutions->shouldReceive('hasCorrectSolutionFromUser')->once()->andReturn(false);

        $this->assertEquals(0, $this->factory->problemsSolved($contest, $user));
    }

    public function testTotalPoints()
    {
        $this->factory = Mockery::mock('Judge\Factories\ContestSummaryFactory[pointsForProblem]', [
            $this->contests,
            $this->problems,
            $this->solutions,
            $this->solution_states
        ]);
        $contest = new Contest();
        $user = new User();
        $this->problems->shouldReceive('forContest')->once()->andReturn([ new Problem() ]);
        $this->factory->shouldReceive('pointsForProblem')->once()->andReturn(123);

        $this->assertEquals(123, $this->factory->totalPoints($contest, $user));
    }

    public function testMake()
    {
        $this->factory = Mockery::mock('Judge\Factories\ContestSummaryFactory[makeForTeam]', [
            $this->contests,
            $this->problems,
            $this->solutions,
            $this->solution_states
        ]);

        $this->contests->shouldReceive('teamsForContest')->once()->andReturn([
            new User()
        ]);

        $this->factory->shouldReceive('makeForTeam')->once()->andReturn(Mockery::mock('Judge\Models\ContestSummary'));

        $result = $this->factory->make(new Contest());

        $this->assertInstanceOf('Judge\Models\ContestSummaryCollection', $result);
        $this->assertInstanceOf('Judge\Models\ContestSummary', $result->first());
    }
}
