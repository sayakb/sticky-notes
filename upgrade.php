<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

// Include necessary files
include_once('init.php');

/* COMMENT OUT WHEN UPGRADING */
/* UNCOMMENT ONCE UPDATE IS COMPLETED */
$gsod->trigger('Update file locked. Check out the README.md for upgrade instructions.');

// Check is config file is present
if (!file_exists(realpath('config.php')))
{
    $gsod->trigger('Config file not found. Please rename config.sample.php to ' .
                   'config.php and make it writable.');
}

// Check if the config file is writable
if (!is_writable(realpath('config.php')))
{
    $gsod->trigger('Config file is not writable. Please adjust the permissions ' .
                   'to start installation.');
}

// Check if PDO is available
if (!class_exists('PDO'))
{
    $gsod->trigger('PDO not found on your server. <a href="http://goo.gl/e6jyj">Click here' .
                   '</a> to view the installation guide.');
}

// Check if DB data is set
$db_fields = array($config->db_host, $config->db_name, $config->db_username,
                   $config->db_password, $config->db_prefix);

foreach ($db_fields as $field)
{
    if (empty($field))
    {
        $gsod->trigger('One or more database options have not been set in the config file.');
    }
}

// Add the new columns
$sql = "ALTER TABLE {$db->prefix}main ADD (" .
       "title VARCHAR(25) DEFAULT '', " .
       "urlkey VARCHAR(8) DEFAULT '', " .
       "hits INT(11) NOT NULL DEFAULT 0)";
$db->query($sql);

// Change the password column lengths to 60
$db->query("ALTER TABLE {$db->prefix}main MODIFY password VARCHAR(60) NOT NULL");
$db->query("ALTER TABLE {$db->prefix}users MODIFY password VARCHAR(60) NOT NULL");

// Create indexes
$db->query("CREATE INDEX {$db->prefix}idx_urlkey ON {$db->prefix}main(urlkey)");

// Done!
$gsod->trigger('Upgrade complete. Please lock/remove the update file.');

?>