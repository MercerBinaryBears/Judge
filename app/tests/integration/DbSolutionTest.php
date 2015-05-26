<?php

use Carbon\Carbon;
use Judge\Models\Contest;
use Judge\Models\Problem;
use Judge\Models\Solution;

class DbSolutionTest extends DbTestCase
{
    public function testScopeForCurrentContestWithMatch()
    {
        $contest = Contest::create([
            'name' => 'test contest',
            'starts_at' => Carbon::yesterday(),
            'ends_at' => Carbon::tomorrow()
        ]);

        $problem = Problem::create([
            'name' => 'test problem',
            'contest_id' => $contest->id,
            'judging_input' => 'INPUT',
            'judging_output' => 'OUTPUT'
        ]);

        $solution = Solution::create([
            'problem_id' => $problem->id,
            'user_id' => 1,
            'language_id' => 1,
            'solution_state_id' => 1,
            'solution_code' => 'asdf',
            'solution_filename' => 'asdf'
        ]);

        $this->assertEquals(1, Solution::forCurrentContest()->count());
    }

    public function testScopeForCurrentContestWithNoProblems()
    {
        $this->assertEquals(0, Solution::forCurrentContest()->count());
    }
}
