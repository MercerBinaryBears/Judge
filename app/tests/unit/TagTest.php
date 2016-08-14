<?php

use Judge\Models\Tag;

class TagTest extends TestCase
{
    public function testProblemsRelationship()
    {
        $tag = new Tag();
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Relations\BelongsToMany', $tag->problems());
    }
}
