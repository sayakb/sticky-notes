<?php
/**
* Sticky Notes pastebin
* @ver 0.3
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2012 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Invoke required files
include_once('init.php');

// Collect some data
$project = $core->variable('project', '');
$page = $core->variable('page', 1);
$mode = $core->variable('mode', '');
$rss = $core->variable('rss', false);

if (empty($mode))
{
    $mode = $core->variable('format', '');
    $_GET['mode'] = $mode;
}

// Global variables
$count = 0;
$output_data = '';
$published = date('d M Y, h:i:s e', 1288800000);

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

// Escape the project
$db->escape($project);

// Get total number of posts
$sql = "SELECT COUNT(id) AS total FROM {$db->prefix}main WHERE " .
       (!empty($project) ? "project = '{$project}' AND " : '') .
       'private = 0';
$row = $db->query($sql);
$total = $row[0]['total'];

// Get page numbers
$pagination = $skin->pagination($total, $page);

// Get the list
$sql = "SELECT * FROM {$db->prefix}main WHERE " .
       (!empty($project) ? "project = '{$project}' AND " : '') .
       'private = 0 ORDER BY timestamp ' .
       "DESC LIMIT {$lim_start}, 10";
$rows = $db->query($sql);
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

    // Configure GeSHi
    $geshi = new GeSHi($row['data'], $row['language']);
    $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 2);
    $geshi->set_header_type(GESHI_HEADER_DIV);
    $geshi->set_line_style('background: #f7f7f7; text-shadow: 0px 1px #fff; padding: 1px;',
                           'background: #fbfbfb; text-shadow: 0px 1px #fff; padding: 1px;');
    $geshi->set_overall_style('word-wrap:break-word;');

    // Generate the data
    $user = empty($row['author']) ? $lang->get('anonymous') : htmlspecialchars($row['author']);
    $timestamp = $row['timestamp'];
    $time = date('d M Y, h:i:s e', $timestamp);
    $info = $lang->get('posted_info');

    $info = preg_replace('/\_\_user\_\_/', $user, $info);
    $info = preg_replace('/\_\_time\_\_/', $time, $info);

    // Get first post time
    if ($count == 1)
    {
        $published = $time;
    }    

    // Before we display, we need to escape the data from the skin/lang parsers
    $code_data = (!$rss ? $geshi->parse_code() : nl2br(htmlspecialchars($row['data'])));
    
    if ($rss)
    {        
        $core->rss_encode($code_data);
    }
    else
    {
        $lang->escape($code_data);
        $skin->escape($code_data);
    }

    // Assign template variables
    $skin->assign(array(
        'paste_id'          => $row['id'],
        'paste_url'         => $nav->get_paste($row['id'], null, $project, $rss),
        'paste_data'        => htmlspecialchars($code_data),
        'paste_lang'        => htmlspecialchars($row['language']),
        'paste_info'        => htmlspecialchars($info),
        'paste_time'        => $time,
        'paste_timestamp'   => $timestamp,
        'error_visibility'  => 'hidden',
        'geshi_stylesheet'  => $geshi->get_stylesheet(),
    ));

    if ($rss)
    {
        $output_data .= $skin->output('rss_item.xml');
    }
    else if ($mode)
    {
        $output_data .= $api->parse($mode, $count, $row['id'], ($count == $rowcount));
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
    'list_data'         => $output_data,
    'list_pagination'   => $pagination,
    'feed_time'         => $published,
));

// Output the page
if ($rss || $mode)
{
    $skin->output(false, true);
}
else
{
    $skin->title($lang->get('paste_archive') . ' &bull; ' . $lang->get('site_title'));
    $skin->output();
}

?>

