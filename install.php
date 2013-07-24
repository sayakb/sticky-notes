<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

// Define constants
define('IN_INSTALL', true);

// Include necessary files
include_once('init.php');

/* COMMENT OUT WHEN INSTALLING */
/* UNCOMMENT ONCE INSTALLING IS COMPLETED */
$gsod->trigger('Install file locked. Check out the README.md for installation instructions.');

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

// Check if the tables already exist
$sql = "SHOW TABLES LIKE '{$db->prefix}%'";
$rows = $db->query($sql);

if (!empty($rows) && count($rows) > 0)
{
    $gsod->trigger('One or more tables already exist in the specified database. '.
                   'Please drop them to start installation.');
}

// Create the config file
$config->create($config->db_type, $config->db_host, $config->db_port, $config->db_name,
                $config->db_username, $config->db_password, $config->db_prefix);

// Create the table structure
$db->query("CREATE TABLE IF NOT EXISTS {$db->prefix}main (" .
           "id INT(12) UNSIGNED NOT NULL AUTO_INCREMENT, " .
           "author VARCHAR(50) DEFAULT '', " .
           "project VARCHAR(50) DEFAULT '', " .
           "timestamp INT(11) UNSIGNED NOT NULL, " .
           "expire INT(11) UNSIGNED NOT NULL, " .
           "title VARCHAR(25) DEFAULT '', " .
           "data MEDIUMTEXT NOT NULL, " .
           "language VARCHAR(50) NOT NULL DEFAULT 'php', " .
           "password VARCHAR(60) NOT NULL, " .
           "salt VARCHAR(5) NOT NULL, " .
           "private TINYINT(1) NOT NULL DEFAULT 0, " .
           "hash INT(12) UNSIGNED NOT NULL, " .
           "ip VARCHAR(50) NOT NULL, " .
           "urlkey VARCHAR(8) DEFAULT '', " .
           "hits INT(11) NOT NULL DEFAULT 0, " .
           "PRIMARY KEY(id)) ENGINE = MyISAM");

$db->query("CREATE TABLE IF NOT EXISTS {$db->prefix}session (" .
           "sid VARCHAR(40) NOT NULL, " .
           "timestamp INT(11) UNSIGNED NOT NULL, " .
           "PRIMARY KEY(sid)) ENGINE = MyISAM");

$db->query("CREATE TABLE IF NOT EXISTS {$db->prefix}cron (" .
           "timestamp INT(11) UNSIGNED NOT NULL DEFAULT 0, " .
           "locked TINYINT(1) NOT NULL DEFAULT 0) ENGINE = MyISAM");

$db->query("CREATE TABLE IF NOT EXISTS {$db->prefix}users (" .
           "id INT(12) UNSIGNED NOT NULL AUTO_INCREMENT, " .
           "username VARCHAR(50) NOT NULL, " .
           "password VARCHAR(60) NOT NULL, " .
           "salt VARCHAR(5) NOT NULL, " .
           "email VARCHAR(100) NOT NULL, " .
           "dispname VARCHAR(100) DEFAULT '', " .
           "sid VARCHAR(40) DEFAULT '', " .
           "lastlogin INT(11) UNSIGNED DEFAULT 0, " .
           "PRIMARY KEY(id)) ENGINE = MyISAM");

$db->query("CREATE TABLE IF NOT EXISTS {$db->prefix}ipbans (" .
           "ip VARCHAR(50) NOT NULL, " .
           "PRIMARY KEY(ip)) ENGINE = MyISAM");

// Add index and charset data
$db->query("ALTER TABLE {$db->prefix}main DEFAULT CHARACTER SET utf8");
$db->query("ALTER TABLE {$db->prefix}main CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci");
$db->query("ALTER TABLE {$db->prefix}main AUTO_INCREMENT = 1000");
$db->query("CREATE INDEX {$db->prefix}idx_urlkey ON {$db->prefix}main(urlkey)");
$db->query("CREATE INDEX {$db->prefix}idx_private ON {$db->prefix}main(id, private)");
$db->query("CREATE INDEX {$db->prefix}idx_author ON {$db->prefix}main(id, author)");
$db->query("CREATE INDEX {$db->prefix}idx_project ON {$db->prefix}main(id, project)");
$db->query("CREATE INDEX {$db->prefix}idx_data ON {$db->prefix}main(id, data)");
$db->query("CREATE INDEX {$db->prefix}idx_sid ON {$db->prefix}session(sid)");
$db->query("CREATE INDEX {$db->prefix}idx_adminuser ON {$db->prefix}users(username)");
$db->query("CREATE INDEX {$db->prefix}idx_adminsid ON {$db->prefix}users(sid)");

// Fill in empty values to cron table
$db->query("INSERT INTO {$db->prefix}cron VALUES (0, 0)");

// Generate a salt and password
$salt = $auth->create_uid(5);
$password = $auth->create_uid(8);
$hash = $auth->create_password($password, $salt);

// Add the default admin user
$sql = "INSERT INTO {$db->prefix}users " .
       "(username, password, salt, email) " .
       "VALUES ('admin', :password, :salt, 'admin@sticky.notes')";

$db->query($sql, array(
    ':password' => $hash,
    ':salt'     => $salt
));

// Done!
$gsod->trigger(
    "Successfully installed! You can log in to the admin panel with the following credentials:" .
    "<ul><li>Username: <b>admin</b></li>" .
    "<li>Password: <b>{$password}</b></li></ul>" .
    "Please make a note of this password. You can change it from the User Management section of " .
    "the <a href=\"../admin/\">admin panel</a>."
);

?>
