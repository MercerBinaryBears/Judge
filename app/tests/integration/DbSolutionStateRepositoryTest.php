<?php

use Illuminate\Support\Facades\App;
use Judge\Repositories\SolutionStateRepository;

class DbSolutionStateRepository extends DbTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->repo = App::make('Judge\Repositories\SolutionStateRepository');
    }

    public function testFirstPendingId()
    {
        // make sure the firstOrFind doesn't fail
        $this->repo->firstPendingId();
    }

    public function testAll()
    {
        $this->assertNotEquals(0, $this->repo->all()->count());
    }

    public function testFirstCorrectId()
    {
        // make sure the firstCorrectid doesn't fail
        $this->repo->firstCorrectId();
    }
}
