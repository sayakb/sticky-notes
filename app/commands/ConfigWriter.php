<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ConfigWriter extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'snconfig:set';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sets a Sticky Notes configruation value';

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
		// Get the config group, key and value
		$group = $this->option('group');

		$key = $this->option('key');

		$value = $this->option('value');

		// Group, key and value are mandatory options
		if ( ! empty($group) AND ! empty($key) AND ! empty($value))
		{
			Site::config($group, array($key => $value));

			$this->info('Configuration data saved successfully. Please delete the contents of `app/storage/cache` folder for your changes to take effect.');
		}
		else
		{
			$this->error('Insufficient arguments specified.');

			$this->error('Usage: snconfig:get --group="..." --key="..." --value="..."');
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
			array('value', NULL, InputOption::VALUE_REQUIRED, 'Configuration data.', NULL),
		);
	}

}
