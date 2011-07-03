<?php
/**
* Sticky Notes pastebin
* @ver 0.2
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Invoke required files
include_once('init.php');

// Collect some data
$author = $core->variable('paste_user', '');
$language = $core->variable('paste_lang', 'text');
$data = $core->variable('paste_data', '');
$expire = $core->variable('paste_expire', 0);
$password = $core->variable('paste_password', '');
$private = $core->variable('paste_private', '');
$project = $core->variable('project', '');
$mode = $core->variable('mode', '');
$time = time();

if (empty($project))
{
    $project = $core->variable('paste_project', '');
    $_GET['project'] = $project;
}

if ($expire > 0)
{
    $expire += $time;
}

$paste_submit = isset($_POST['paste_submit']) ? true : false;
$api_submit = isset($_POST['api_submit']) || isset($_GET['api_submit']) ? true : false;

// Global vars
$show_error = false;

// Initialize the skin file
if (!$mode)
{
    $skin->init('tpl_create');
}
else
{
    // Only two modes are allowed
    if ($mode && $mode != 'xml' && $mode != 'json')
    {
        die;
    }

    // Exit if nothing has been submitted
    if (!$api_submit)
    {
        $skin->assign('error_message', 'err_nothing_to_do');
        echo $skin->output("api_error.{$mode}");
        die;
    }
}

// Check if author is numeric
$tmp_author = trim(preg_replace('/[1-9]/', '', $author));

if (strlen($tmp_author) == 0 && $author)
{
    if ($mode == 'xml' || $mode == 'json')
    {
        $skin->assign('error_message', 'err_author_numeric');
        echo $skin->output("api_error.{$mode}");
        die;
    }
    else
    {
        $show_error = true;
        $skin->assign('error_box', 'alias_error_box');
        // No exit required here
    }
}

// Mode is mandatory for API
if ($api_submit && !$mode)
{
    die;
}

// Let's do some spam check!
if ($paste_submit)
{
    $sg->validate();
}

// Save user and language data in cookies
if ($paste_submit || $api_submit)
{
    $core->set_cookie('author', $author);
    $core->set_cookie('language', $language);
}

if (($paste_submit || $api_submit) && strlen($data) > 0 && !$show_error)
{
    // Escape text
    $db->escape($author);
    $db->escape($data);
    $db->escape($language);
    $db->escape($private);
    $db->escape($project);
    
    $author = trim($author);

    // Generate a hash value
    $timestr = time();
    $hash = substr($timestr . $timestr, rand(0, 5), 8);

    // Generate the password hash
    $salt = substr(sha1(time()), rand(0, 34), 5);
    $md5 = $password ? sha1(sha1($password) . $salt) : '';
    
    // Capture the IP address
    $remote_ip = $core->remote_ip();

    // Insert into the DB
    $sql = "INSERT INTO {$db->prefix}main " .
           "(author, project, timestamp, expire, data, language, " .
           "password, salt, private, hash, ip) VALUES " .
           "('{$author}', '{$project}', {$time}, {$expire}" .
           ", '{$data}', " . "'{$language}', '{$md5}', '{$salt}', " .
           ($private == "on" || $private == "yes" || $password ? "1" : "0") .
           ", {$hash}, '{$remote_ip}')";
    $db->query($sql);

    $new_id = $db->get_id();

    // Address API requests
    if ($mode == 'xml' || $mode == 'json')
    {
        if ($new_id)
        {
            $skin->assign('paste_id', $new_id);

            if ($private)
            {
                $skin->assign('paste_hash', $hash);
            }

            // Output the XML/JSON data
            echo $skin->output("api_create.{$mode}");
            exit;
        }
        else
        {
            // An error occurred
            $skin->assign('error_message', 'err_save_error');
            echo $skin->output("api_error.{$mode}");
            die;
        }
    }
    else
    {
        if ($new_id)
        {
            $url = $core->rss_uri() .
                   $new_id . ($private || $password ? "/{$hash}/" : "/");

            if (!$password)
            {
                $core->redirect($url);
            }

            $message = $lang->get('paste_saved');
            $link = "<a href=\"{$url}\">{$url}</a>";

            $message = preg_replace('/\_\_url\_\_/', $link, $message);

            $skin->assign(array(
                'msg_visibility'    => 'visible',
                'error_visibility'    => 'hidden',
                'message_text'        => $message,
                'msg_color'            => 'green',
            ));
        }
        else
        {
            $skin->assign(array(
                'msg_visibility'    => 'visible',
                'error_visibility'    => 'hidden',
                'message_text'        => $lang->get('paste_error'),
                'msg_color'            => 'red',
            ));
        }
    }
}
else
{
    // Assign template data
    $skin->assign(array(
        'post_user'                => $author,
        'post_data'                => $data,
        'post_' . $language     => 'selected="selected"',
        'post_checked'            => ($private == "on" ? "checked" : ""),
        'msg_visibility'        => 'hidden',
    ));
}

// Assign template data
$skin->assign(array(
    'post_lang_list'        => $skin->output('tpl_languages'),
    'error_visibility'        => ($show_error === true ? 'visible' : 'hidden'),
));

// Yes, that's pretty much everything we need for index page ;)
$skin->title($lang->get('create_new') . ' &bull; ' . $lang->get('site_title'));
$skin->output();
?>