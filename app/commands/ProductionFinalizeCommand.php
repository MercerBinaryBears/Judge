<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ProductionFinalizeCommand extends RemoteCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'deploy:migrate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updates composer packages, and migrates the production database';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$commands = array(
			// change to the installation directory
			"cd " . Config::get('deploy.dir') . "Judge",

			// first copy the last migration database (or create an empty file)
			"( cp ../production.sqlite.last app/database/production.sqlite || touch app/database/production.sqlite )",

			// migrate
			"./artisan migrate",

			// publish assets for administrator
			"./artisan asset:publish",

			);

		$this->ssh($commands);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}