<?php

class DbTestCase extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');
        DB::beginTransaction();
    }

    public function tearDown()
    {
        parent::tearDown();

        DB::rollback();
    }
}
