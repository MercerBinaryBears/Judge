<?php

use Judge\Models\SolutionState;

class SolutionStateTest extends TestCase
{
    public function testSolutions()
    {
        $state = new SolutionState();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $state->solutions());
    }

    public function testBootstrapColorWithPending()
    {
        $state = new SolutionState(['pending' => true]);
        $this->assertEquals('info', $state->bootstrap_color);
    }

    public function testBootstrapColorWithSuccess()
    {
        $state = new SolutionState(['pending' => false, 'is_correct' => true]);
        $this->assertEquals('success', $state->bootstrap_color);
    }

    public function testBootstrapColorWithFailing()
    {
        $state = new SolutionState(['pending' => false, 'is_correct' => false]);
        $this->assertEquals('danger', $state->bootstrap_color);
    }
}
