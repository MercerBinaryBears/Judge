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
}
