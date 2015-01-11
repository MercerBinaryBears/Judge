<?php

use \Mockery;
use Illuminate\Database\Eloquent\Collection as Collection;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	public function setUp() {

		// setup the application, using the parent TestCase class
		parent::setUp();

	}

	public function tearDown() {
		Mockery::close();
	}

	/**
	 * Creates the application.
	 *
	 * @return Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../../bootstrap/start.php';
	}
}
