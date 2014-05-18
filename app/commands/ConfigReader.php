<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ConfigReader extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'snconfig:get';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Gets a Sticky Notes configruation value';

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
	 * @return mixed
	 */
	public function fire()
	{
		// Get the config group and key
		$group = $this->option('group');

		$key = $this->option('key');

		// Both group and key are mandatory options
		if ( ! empty($group) AND ! empty($key))
		{
			$values = Site::config($group);

			if (isset($values->$key))
			{
				$this->info($values->$key);
			}
			else
			{
				$this->error('No config data exists for given key.');
			}
		}
		else
		{
			$this->error('Insufficient arguments specified.');

			$this->error('Usage: snconfig:get --group="..." --key="..."');
		}
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
		return array(
			array('group', NULL, InputOption::VALUE_REQUIRED, 'Configuration group.', NULL),
			array('key', NULL, InputOption::VALUE_REQUIRED, 'Configuration key.', NULL),
		);
	}

}
