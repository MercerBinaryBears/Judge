<?php

use Carbon\Carbon;
use Judge\Models\Contest;
use Judge\Models\Problem;

class DbProblemTest extends DbTestCase
{
    public function testCurrentContestForNoContest()
    {
        $this->assertEquals(0, Problem::forCurrentContest()->count());
    }

    protected function stubContest($start_time)
    {
        return Contest::create([
            'name' => 'test' . microtime(true),
            'starts_at' => $start_time,
            'ends_at' => $start_time->copy()->addDays(2)
        ]);
    }

    public function testCurrentContestForProblemsNotAttached()
    {
        $old_contest = $this->stubContest(Carbon::now()->subDays(10));
        $contest = $this->stubContest(Carbon::now()->subDay());

        $problem = Problem::create([
            'name' => 'test problem',
            'contest_id' => $old_contest->id,
            'judging_input' => 'asdf',
            'judging_output' => 'asdf',
        ]);

        $this->assertEquals(0, Problem::forCurrentContest()->count());
    }

    public function testCurrentContestForProblemsAttached()
    {
        $contest = $this->stubContest(Carbon::now()->subDay());

        $problem = Problem::create([
            'name' => 'test problem',
            'contest_id' => $contest->id,
            'judging_input' => 'asdf',
            'judging_output' => 'asdf',
        ]);

        $this->assertEquals(1, Problem::forCurrentContest()->count());
    }
}
