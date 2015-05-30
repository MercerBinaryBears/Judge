<?php

use Carbon\Carbon;
use Judge\Models\Contest;

class DbContestTest extends DbTestCase
{
    public function testCurrentScopeWithMissingModel()
    {
        Contest::create([
            'name' => 'test contest',
            'starts_at' => Carbon::now()->addDay(),
            'ends_at' => Carbon::now()->addDays(2)
        ]);

        $this->assertCount(0, Contest::current()->get());
    }

    public function testCurrentScopeWithExistingModel()
    {
        Contest::create([
            'name' => 'test contest',
            'starts_at' => Carbon::now()->subDay(),
            'ends_at' => Carbon::now()->addDays(2)
        ]);

        $this->assertCount(1, Contest::current()->get());
    }
}
