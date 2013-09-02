<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ProductionBackupCommand extends RemoteCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'deploy:backup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Creates a backup of the current production website';

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
		// get the timestamp for the operation
		$timestamp = time();

		// the array of commands that need to be done
		$commands = array(

			// change to the directory where the app is installed
			"cd " . Config::get('deploy.dir'),

			// check that the site has been previously deployed
			"[ -d Judge ]",

			// move the app to a new location, if the directory exists
			"mv Judge Judge.$timestamp",

			// check that a production database exists
			"[ -f Judge.$timestamp/app/database/production.sqlite ]",

			// copy the production database out
			"cp Judge.$timestamp/app/database/production.sqlite production.sqlite.$timestamp",

			// also, keep track of the LAST database we had
			"cp Judge.$timestamp/app/database/production.sqlite production.sqlite.last",

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