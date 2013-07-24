<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

// Get current action
$action = $core->variable('action', '');

// Check if we just want the version number
if ($action == 'version')
{
    $version = file_get_contents(UPDATE_SERVER, false);
    $version = explode("\n", $version);

    die($version[1]);
}

// Check if we just want server load
if ($action == 'sysload')
{
    $load = $core->server_load();
    $load = $load !== false ? $load : $lang->get('n_a');

    die($load);
}

// Check if we want to clear the cache
if ($action == 'clearcache')
{
    $cache->_gc(true);
    $core->redirect($core->current_uri());
}

// Get DB version
$sql = "SELECT VERSION() AS ver";
$row = $db->query($sql, array(), true);
$db_ver = $row !== false && !empty($row['ver']) ? $row['ver'] : $lang->get('n_a');

// Get the DB size
if ($config->db_type == 'mysql')
{
    $db_size = $db->get_size();
    $db_size = $skin->display_size($db_size);
}
else
{
    $db_size = $lang->get('n_a');
}

// Get the cache size
if ($cache->is_available)
{
    $cache_size = $cache->get_size();
    $cache_size = $skin->display_size($cache_size);
}
else
{
    $cache_size = '<span class="darkred">' . $lang->get('cache_unvailable') . '</span>';
}

// Get the number of posts
$sql = "SELECT COUNT(*) AS count FROM {$db->prefix}main";
$row = $db->query($sql, array(), true);
$paste_count = $row['count'];

// Make the new version link
$update_url =  '&bull; ' . $lang->get('new_ver_available') . ' (<a href="' .
               UPDATE_DL_PATH . '">' . $lang->get('download_latest') . '</a>)';

// Assign skin data
$skin->assign(array(
    'stickynotes_ver'   => $core->build,
    'update_url'        => $update_url,
    'build_num'         => $core->build_num,
    'php_version'       => phpversion(),
    'db_type'           => $config->db_type,
    'db_version'        => $db_ver,
    'db_size'           => $db_size,
    'cache_size'        => $cache_size,
    'paste_count'       => $paste_count,
));

// Set the page title
$module_title = $lang->get('dashboard');
$module_data =  $skin->output('tpl_dashboard', true, true);

?>
