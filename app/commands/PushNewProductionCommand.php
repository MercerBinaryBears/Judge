<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PushNewProductionCommand extends RemoteCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'deploy:push';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Pushes a fresh version of the website to production, without migrating';

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
		$local_commands = array(
			// remove any old files
			'rm -rf /tmp/Judge /tmp/Judge.zip',

			// Clone a fresh copy
			'git clone git@github.com:chipbell4/Judge.git /tmp/Judge',

			// start working in that directoyr
			'cd /tmp/Judge',

			// download repositories
			'~/bin/composer install',

			// get ready to zip
			'cd /tmp',

			// Zip it up
			'zip -r Judge.zip Judge',

			);

		$result = shell_exec(implode(' && ', $local_commands));

		$this->comment($result);
		return;

		// SCP it up to the directory
		$this->scp('/tmp/Judge.zip', Config::get('deploy.dir') . 'Judge.zip');

		// unzip it on the production machine
		$commands = array(
			// change to the directory
			'cd ' . Config::get('deploy.dir'),

			// unzip
			'unzip Judge.zip',
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