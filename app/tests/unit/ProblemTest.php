<?php

use Judge\Models\Problem;

class ProblemTest extends TestCase
{
    public function testContestRelationship()
    {
        $problem = new Problem();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsTo', $problem->contest());
    }

    public function testSolutionsRelationship()
    {
        $problem = new Problem();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\HasMany', $problem->solutions());
    }

    public function testDifficultyValidation()
    {
        $problem = new Problem([
            'name' => 'name',
            'contest_id' => '1',
            'judging_input' => '1',
            'judging_output' => '2',
            'difficulty' => '12'
        ]);

        $this->assertFalse($problem->validate());
    }
}
