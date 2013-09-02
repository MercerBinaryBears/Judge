<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * This class is sort-of a poly-fill for remote access to another
 * machine, which will be supported in the next version of Laravel
 * For now, we have this kludge that I am writing
 */
class RemoteCommand extends Command {

	/**
	 * Perform a series of commands on a remote machine
	 *
	 * @param array $commands An array of commands to execute
	 */
	protected function ssh($commands = array(), $host = null) {

		/*
		 * For ssh commands, default to the production machine
		 */
		if(is_null($host))
		{
			$host = Config::get('deploy.host');
		}

		// essentially run an ssh to the host and execute the commands
		$full_command = "ssh $host \"" . implode(' && ', $commands) . "\"";

		exec($full_command);
	}

	protected function scp($source, $destination, $host) {
		/*
		 * For ssh commands, default to the production machine
		 */
		if(is_null($host))
		{
			$host = Config::get('deploy.host');
		}

		$full_command = "scp $source $host:$destination";

		exec($full_command);
	}

}