<?php
/**
* Sticky Notes pastebin
* @ver 0.3
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Check if we just want the version number
if (isset($_GET['ver']))
{
    $version = file_get_contents(UPDATE_SERVER, false);
    $version = explode("\n", $version);
    
    die($version[1]);
}

// Get MySQL version
$sql = "SELECT VERSION() AS ver";
$row = $db->query($sql, true);
$mysql_ver = !empty($row['ver']) ? $row['ver'] : $lang->get('n_a');

// Get the server load
$server_load = $core->server_load() !== false ? $core->server_load() : $lang->get('n_a');

// Get the MySQL DB size
$sql = "SHOW TABLE STATUS";
$rows = $db->query($sql);
$db_size_num = 0;
$postfix = array('KB', 'MB', 'GB', 'TB');
$postfix_idx = 0;

foreach($rows as $row)
{
    $db_size_num += intval($row["Data_length"]) + intval($row["Index_length"]);
}

$db_size_num /= 1024;

while ($db_size_num > 1024)
{
    $postfix_idx++;
    $db_size_num /= 1024;
}

$db_size = strval(round($db_size_num, 2)) . ' ' . $postfix[$postfix_idx];

// Get the number of posts
$sql = "SELECT COUNT(*) AS count FROM {$db->prefix}main";
$row = $db->query($sql, true);
$paste_count = $row['count'];

// Make the new version link
$update_url =  '&bull; ' . $lang->get('new_ver_available') . ' (' .
               '<a href="https://github.com/sayakb/sticky-notes/zipball/master">' .
               $lang->get('download_latest') . '</a>)';

// Assign skin data
$skin->assign(array(
    'stickynotes_ver'   => $core->build,
    'update_url'        => $update_url,
    'build_num'         => $core->build_num,
    'php_version'       => phpversion(),
    'mysql_version'     => $mysql_ver,
    'server_load'       => $server_load,
    'db_size'           => $db_size,
    'paste_count'       => $paste_count,
));

// Set the page title
$module_title = $lang->get('dashboard');
$module_data =  $skin->output('tpl_dashboard', true, true);

?>
