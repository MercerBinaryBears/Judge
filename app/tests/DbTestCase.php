<?php

use Laracasts\TestDummy\Factory;

class DbTestCase extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Factory::$factoriesPath = 'app/tests/factories';
        Artisan::call('migrate');
        DB::beginTransaction();
    }

    public function tearDown()
    {
        parent::tearDown();

        DB::rollback();
    }
}
