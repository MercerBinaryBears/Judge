<?php

use Illuminate\Database\Eloquent\Collection as Collection;

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * The array of contest objects to bind with the dummy repository
	 */
	public $current_contests_array = array();

	/**
	 * The array of problem objects to bind with the dummy repository
	 */
	public $problems_for_contest_array = array();

	/**
	 * The array of users to bind with the dummy repository
	 */
	public $users_for_contest = array();

	/**
	 * The Repository for querying contests
	 */
	public $contests_repo;

	public function setUp() {

		// setup the application, using the parent TestCase class
		parent::setUp();

		// rebind the IOC with testing versions of our repositories
		$this->rebindIoc();

		// assign the repositories for each of our models
		$this->assignRepos();
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

		return require __DIR__.'/../../bootstrap/start.php';
	}

	/**
	 * Rebinds the IOC with dummy instances of the repository classes, instead
	 * of the Eloquent ones
	 */
	public function rebindIoc() {
		App::bind('ContestRepository', function() {
			return new DummyContestRepository(
				Collection::make($this->current_contests_array),
				Collection::make($this->problems_for_contest_array),
				Collection::make($this->users_for_contest)
				);
		});
	}

	/**
	 * Gets instances of each of the repositories needed for the application, pulled
	 * from the IOC
	 */
	public function assignRepos() {
		$this->contests_repo = App::make('ContestRepository');
	}

}
