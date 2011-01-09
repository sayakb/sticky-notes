<?php
/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Turn off error reporting
//error_reporting(0);

// Include classes
include_once('./classes/class_core.php');
include_once('./classes/class_db.php');
include_once('./classes/class_lang.php');
include_once('./classes/class_skin.php');
include_once('./classes/class_api.php');
include_once('./addons/geshi/geshi.php');

// Include other files
include('./config.php');

// Instantiate class objects
$core = new core();
$db = new db();
$lang = new lang();
$skin = new skin();
$api = new api();

// Define macros
define('GESHI_LANG_PATH', $core->base_uri() . '/addons/geshi/geshi/');

// Before we do anything, let's add a trailing slash
$url = $core->request_uri();
$direct = strpos($url, '?');

if (strrpos($url, '/') != (strlen($url) - 1) && !$direct)
{
    header("Location: {$url}/");
    exit;
}
else
{
    unset($url);
}

// Change project name to lower case
if (isset($_GET['project'])) $_GET['project'] = strtolower($_GET['project']);
if (isset($_POST['project'])) $_POST['project'] = strtolower($_POST['project']);
if (isset($_GET['paste_project'])) $_GET['paste_project'] = strtolower($_GET['paste_project']);
if (isset($_POST['paste_project'])) $_POST['paste_project'] = strtolower($_POST['paste_project']);

// Set up the db connection
$db->connect($db_host, $db_port, $db_name, $db_username, $db_password);

// Set a root path template var
$skin->assign('root_path', $core->path());

// Cleanup expired pastes every 1 minute
$sql = "SELECT timestamp, locked FROM {$db_prefix}cron LIMIT 1";
$row = $db->query($sql, true);
$timestamp = $row['timestamp'];
$locked = $row['locked'];

if (((time() - $timestamp) > 60) && !$locked)
{
    $db->query("UPDATE {$db_prefix}cron SET locked = 1 WHERE locked = 0");

    if ($db->affected_rows() > 0)
    {
	$db->query("DELETE FROM {$db_prefix}main WHERE expire < " . time());
	$db->query("UPDATE {$db_prefix}cron SET timestamp = " . time() . ", locked = 0");
    }
}

?>