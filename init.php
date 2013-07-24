<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

// Turn off error reporting
error_reporting(E_ALL);

// Define constants
define('UPDATE_SERVER', 'https://raw.github.com/sayakb/sticky-notes/master/VERSION');
define('UPDATE_DL_PATH', 'https://github.com/sayakb/sticky-notes/zipball/master');

// Include classes
include_once('classes/class_gsod.php');
include_once('classes/class_core.php');
include_once('classes/class_config.php');
include_once('classes/class_nav.php');
include_once('classes/class_cache.php');
include_once('classes/class_email.php');
include_once('classes/class_db.php');
include_once('classes/class_lang.php');
include_once('classes/class_skin.php');
include_once('classes/class_api.php');
include_once('classes/class_spamguard.php');
include_once('classes/class_auth.php');
include_once('classes/class_module.php');

// We need to instantiate the GSoD class first, just in case!
$gsod = new gsod();

// Instantiate classes
$core = new core();
$config = new config();
$nav = new nav();
$cache = new cache();
$email = new email();
$db = new db();
$lang = new lang();
$skin = new skin();
$api = new api();
$sg = new spamguard();
$auth = new auth();
$module = new module();

// Before we do anything, let's add a trailing slash
// We skip this for admin links
$url = $core->full_uri();

if (strrpos($url, '/') != (strlen($url) - 1) && $nav->rewrite_on &&
    strpos($url, 'admin') === false && strpos($url, '.php') === false)
{
    $core->redirect($url . '/');
}
else
{
    unset($url);
}

// Change project name to lower case and escape it
if (isset($_GET['project'])) $_GET['project'] = htmlspecialchars(strtolower($_GET['project']));
if (isset($_POST['project'])) $_POST['project'] = htmlspecialchars(strtolower($_POST['project']));
if (isset($_GET['paste_project'])) $_GET['paste_project'] = htmlspecialchars(strtolower($_GET['paste_project']));
if (isset($_POST['paste_project'])) $_POST['paste_project'] = htmlspecialchars(strtolower($_POST['paste_project']));

// Set up the db connection
$db->connect();

// Assign defaut variables
$skin->assign(array(
    'root_path'         => $core->current_uri(),
    'msg_visibility'    => 'hidden',
));

// Perform cron tasks
if (!defined('IN_INSTALL') && !defined('IN_ADMIN'))
{
    include_once('cron.php');
}

?>