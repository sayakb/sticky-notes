<?php

/**
 * Sticky Notes
 *
 * An open source lightweight pastebin application
 *
 * @package     StickyNotes
 * @author      Sayak Banerjee
 * @copyright   (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @link        http://sayakbanerjee.com/sticky-notes
 * @since       Version 1.0
 * @filesource
 */

/**
 * Setup class
 *
 * Layer for performing install and update activities
 *
 * @package     StickyNotes
 * @subpackage  Libraries
 * @author      Sayak Banerjee
 */
class Setup {

	/**
	 * Tests the database connection
	 *
	 * @static
	 * @return bool|string
	 */
	public static function testConnection()
	{
		try
		{
			Schema::getConnection();

			return TRUE;
		}
		catch (Exception $e)
		{
			return $e->getMessage();
		}
	}

	/**
	 * Processes AJAX requests for installation.
	 * The response is in the following format:
	 *
	 * <percent-complete>|<next-action>|<status-message>
	 *
	 * @static
	 * @param  string  $action
	 * @return string
	 */
	public static function install($action)
	{
		// Fetch the installer schema
		$schema = Config::get('schema');

		// Define the tables data
		$tables = $schema['install']['tables'];

		$tableNames = array_keys($tables);

		// Define the callback closure
		$closure = $schema['install']['closure'];

		// We assign 5% to initiate and 5% to completion
		// The weightage of each table is calculated out of 90
		$weight = floor(90 / count($tables));

		// Initialize everything
		if (empty($action))
		{
			$firstTable = $tableNames[0];

			return "5|{$firstTable}|".sprintf(Lang::get('setup.create_table'), $firstTable);
		}

		// This is the last step, but needs to be called out first
		else if ($action == '~complete')
		{
			// Run the post-install closure
			call_user_func($closure);

			// Mark completion of this stage
			Session::put('install.stage', 4);

			return "100||".Lang::get('setup.install_complete');
		}

		// This loops across all tables and processes them
		else if (in_array($action, $tableNames))
		{
			// Drop the table
			Schema::dropIfExists($action);

			// Generate schema and create the table
			Schema::create($action, function($table) use ($tables, $action)
			{
				Setup::schema($table, $tables[$action]);
			});

			// Get the next table name
			$index = array_search($action, $tableNames);

			// Get the percentage done
			$percent = ($index + 1) * $weight;

			// Get the next action and message
			if ($index < count($tableNames) - 1)
			{
				$nextAction = $tableNames[$index + 1];

				$message = sprintf(Lang::get('setup.create_table'), $nextAction);
			}
			else
			{
				$nextAction = '~complete';

				$message = Lang::get('setup.almost_done');
			}

			return "{$percent}|{$nextAction}|{$message}";
		}
	}

	/**
	 * Applies a specific table schema to a table
	 *
	 * @static
	 * @param  Illuminate\Database\Schema\Blueprint  $table
	 * @param  array  $schema
	 * @return void
	 */
	private static function schema($table, $schema)
	{
		foreach ($schema as $column)
		{
			$coltype = $column->type;

			// Make the column
			if (isset($column->length))
			{
				$context = $table->$coltype($column->name, $column->length);
			}
			else
			{
				$context = $table->$coltype($column->name);
			}

			// Set default value
			if (isset($column->default))
			{
				$context = $context->default($column->default);
			}

			// Set nullable type
			if (isset($column->nullable) AND $column->nullable)
			{
				$context = $context->nullable();
			}

			// Set unsigned for integers
			if (isset($column->unsigned) AND $column->unsigned)
			{
				$context = $context->unsigned();
			}
		}
	}

}
