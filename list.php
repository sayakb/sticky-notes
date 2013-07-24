<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

// Invoke required files
include_once('init.php');
include_once('addons/geshi/geshi.php');

// Collect some data
$project = $core->variable('project', '');
$page = $core->variable('page', 1);
$mode = $core->variable('mode', '');
$age = $core->variable('age', '');
$rss = $core->variable('rss', false);
$trending = $core->variable('trending', false);

if (empty($mode))
{
    $mode = $core->variable('format', '');
    $_GET['mode'] = $mode;
}

// Global variables
$count = 0;
$total = 0;
$output_data = '';
$pagination = '';
$published = date('d M Y, H:i:s e', 1288800000);

// Validate mode
if ($mode && $mode != 'xml' && $mode != 'json')
{
    die;
}

// Initialize the skin file
if ($rss)
{
    $skin->init('rss.xml');
}
else if ($mode)
{
    $skin->init("api_list.{$mode}");
}
else
{
    $skin->init('tpl_list');
}

// Calculate the first paste count
if ($rss)
{
    $lim_start = 0;
}
else
{
    $lim_start = ($page * 10) - 10;
}

// Build the WHERE and ORDER BY claus
$sql_where = 'private = 0';
$sql_order = 'timestamp DESC';
$sql_limit = "{$lim_start}, 10";
$params = array();

if (!empty($project))
{
    $params[':project'] = $project;
    $sql_where .= ' AND project = :project';
}

if ($trending)
{
    $time = time();
    $trend_time = 259200;

    if ($age == 'week')
    {
        $trend_time = 1814400;
    }
    else if ($age == 'month')
    {
        $trend_time = 7776000;
    }
    else if ($age == 'year')
    {
        $trend_time = 94608000;
    }
    else if ($age == 'all')
    {
        $trend_time = $time;
    }

    if ($trend_time > 0)
    {
        $params[':timestamp'] = $time - $trend_time;
        $sql_where .= ' AND timestamp >= :timestamp';
        $sql_order = 'hits DESC';
        $sql_limit = '10';
    }
}
else
{
    // Genarate page numbers for the list
    $sql = "SELECT COUNT(id) AS total FROM {$db->prefix}main WHERE {$sql_where}";
    $row = $db->query($sql, $params);
    $total = $row[0]['total'];

    $pagination = $skin->pagination($total, $page);
}

// Get the list
$sql = "SELECT * FROM {$db->prefix}main WHERE {$sql_where} " .
       "ORDER BY {$sql_order} LIMIT {$sql_limit}";
$rows = $db->query($sql, $params);
$rowcount = count($rows);

// Populate list items
foreach ($rows as $row)
{
    $count++;

    // We need 5 lines, whatsoever
    $lines = substr_count($row['data'], "\n");

    if ($lines > 5)
    {
        $lines_arr = explode("\n", $row['data']);
        $row['data'] = '';

        for ($idx = 0; $idx < 5; $idx++)
        {
            $row['data'] .= ($lines_arr[$idx] . "\n");
        }

        $row['data'] = substr($row['data'], 0, strlen($row['data']) - 2);
    }

    // Syntax highlighting - only for web interface
    if (!$rss)
    {
        // Check if the GeSHi output was cached
        $geshi_key = $row['data'] . $row['language'];
        $code_data = $cache->get($geshi_key . 'data');
        $code_style = $cache->get($geshi_key . 'style');

        if ($code_data === false || $code_style === false)
        {
            // Configure GeSHi
            $geshi = $skin->geshi($row['data'], $row['language']);

            // Run GeSHi
            $code_data = $geshi->parse_code();
            $code_style = $geshi->get_stylesheet(true);

            $cache->set($geshi_key . 'data', $code_data);
            $cache->set($geshi_key . 'style', $code_style);
        }
    }
    else
    {
        $code_data = nl2br(htmlspecialchars($row['data']));
        $code_style = '';
    }

    // Generate the data
    $user = empty($row['author']) ? $lang->get('anonymous') : htmlspecialchars($row['author']);
    $timestamp = $row['timestamp'];
    $time = date('d M Y, H:i:s e', $timestamp);
    $info = $lang->get('posted_info');

    $info = preg_replace('/\_\_user\_\_/', $user, $info);
    $info = preg_replace('/\_\_time\_\_/', $time, $info);

    // Get first post time
    if ($count == 1)
    {
        $published = $time;
    }

    // Before we display, we need to escape the data from the skin/lang parsers
    if ($rss)
    {
        $core->rss_encode($code_data);
    }
    else
    {
        $lang->escape($code_data);
        $skin->escape($code_data);
    }

    // Determine the unique identifier
    if ($config->url_key_enabled && !empty($row['urlkey']))
    {
        $key = 'p' . $row['urlkey'];
    }
    else
    {
        $key = $row['id'];
    }

    // Format the paste title
    if (!empty($row['title']))
    {
        $title = htmlspecialchars($row['title']);
    }
    else
    {
        $title = $lang->get('paste') . " #{$key}";
    }

    // Assign template variables
    $skin->assign(array(
        'paste_id'          => $key,
        'paste_url'         => $nav->get_paste($row['id'], $row['urlkey'], null, $project),
        'paste_title'       => $title,
        'paste_data'        => $code_data,
        'paste_lang'        => htmlspecialchars($row['language']),
        'paste_info'        => $info,
        'paste_time'        => $time,
        'paste_timestamp'   => $timestamp,
        'error_visibility'  => 'hidden',
        'geshi_stylesheet'  => $code_style,
    ));

    if ($rss)
    {
        $output_data .= $skin->output('rss_item.xml');
    }
    else if ($mode)
    {
        $output_data .= $api->parse($mode, $count, $key, ($count == $rowcount));
    }
    else
    {
        $output_data .= $skin->output('tpl_list_item');
    }
}

// Assign some final variables
if ($rowcount)
{
    $skin->assign(array(
        'error_visibility'       => 'hidden',
        'data_visibility'        => 'visible',
    ));
}
else
{
    if ($mode)
    {
        $skin->assign('error_message', 'err_no_pastes');
        echo $skin->output("api_error.{$mode}");
        die;
    }
    else
    {
        $skin->assign(array(
            'error_visibility'       => 'visible',
            'data_visibility'        => 'hidden',
        ));
    }
}

$skin->assign(array(
    'paste_count'       => $rowcount,
    'paste_pages'       => ceil($total / 10),
    'error_text'        => $lang->get('no_pastes'),
    'list_title'        => $trending ? $lang->get('trending') : $lang->get('archives'),
    'list_icon'         => $trending ? 'trending' : 'archives',
    'list_data'         => $output_data,
    'list_pagination'   => $pagination,
    'feed_time'         => $published,
    'filter_visibility' => $skin->visibility($trending),
    'pages_visibility'  => $skin->visibility($trending, true),
    'trending_now'      => $nav->get('nav_trending', $project),
    'trending_week'     => $nav->get('nav_trending', $project, $page, 'week'),
    'trending_month'    => $nav->get('nav_trending', $project, $page, 'month'),
    'trending_year'     => $nav->get('nav_trending', $project, $page, 'year'),
    'trending_all'      => $nav->get('nav_trending', $project, $page, 'all'),
    'tr_active_now'     => $skin->active(empty($age)),
    'tr_active_week'    => $skin->active($age == 'week'),
    'tr_active_month'   => $skin->active($age == 'month'),
    'tr_active_year'    => $skin->active($age == 'year'),
    'tr_active_all'     => $skin->active($age == 'all'),
));

// Output the page
if ($rss || $mode)
{
    $skin->output(false, true);
}
else
{
    $title = $trending ? $lang->get('trending') : $lang->get('paste_archive');

    $skin->title($title . ' &bull; ' . $lang->get('site_title'));
    $skin->output();
}

?>

