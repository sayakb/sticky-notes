<?php
/**
* Sticky Notes pastebin
* @ver 0.3
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Turn off error reporting
error_reporting(0);

// Define constants
define('UPDATE_SERVER', 'https://raw.github.com/sayakb/sticky-notes/master/VERSION');

// Include classes
include_once('classes/class_gsod.php');
include_once('classes/class_config.php');
include_once('classes/class_core.php');
include_once('classes/class_db.php');
include_once('classes/class_lang.php');
include_once('classes/class_skin.php');
include_once('classes/class_api.php');
include_once('classes/class_nav.php');
include_once('classes/class_spamguard.php');
include_once('addons/geshi/geshi.php');

// We need to instantiate the GSoD class first, just in case!
$gsod = new gsod();

// Instantiate general classes
$config = new config();
$core = new core();
$db = new db();
$lang = new lang();
$skin = new skin();
$api = new api();
$sg = new spamguard();
$nav = new nav();

// Instantiate admin classes
if (defined('IN_ADMIN'))
{
    include_once('admin/classes/class_auth.php');
    include_once('admin/classes/class_module.php');

    // Instantiate admin classes
    $auth = new auth();
    $module = new module();
}

// Define macros
define('GESHI_LANG_PATH', $core->base_uri() . '/addons/geshi/geshi/');

// Before we do anything, let's add a trailing slash
// We skip this for admin links
$url = $core->request_uri();

if (strrpos($url, '/') != (strlen($url) - 1) && $nav->rewrite_on &&
    strpos($url, 'admin') === false && strpos($url, '.php') === false)
{
    $core->redirect($url . '/');
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
$db->connect();

// Assign defaut variables
$skin->assign(array(
    'root_path'         => $core->path(),
    'msg_visibility'    => 'hidden',
));

// Perform cron tasks
if (!defined('IN_INSTALL') && !defined('IN_ADMIN'))
{
    include_once('cron.php');
}

?>