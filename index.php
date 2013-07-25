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

// Collect some data
$author = $core->variable('paste_user', '');
$language = $core->variable('paste_lang', 'text');
$title = $core->variable('paste_title', '');
$data = $core->variable('paste_data', '');
$expire = $core->variable('paste_expire', 604800);
$password = $core->variable('paste_password', '');
$private = $core->variable('paste_private', '');
$project = $core->variable('project', '');
$mode = $core->variable('mode', '');
$skip_insert = false;
$new_id = 0;
$url_key = '';
$time = time();

if (empty($project))
{
    $project = $core->variable('paste_project', '');
    $_GET['project'] = $project;
}

if (empty($author))
{
    $author = $lang->get('anonymous');
}

if ($expire > 0)
{
    $expire += $time;
}

$paste_submit = isset($_POST['paste_submit']);
$api_submit = isset($_POST['api_submit']) || isset($_GET['api_submit']);

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

if ($paste_submit || $api_submit)
{
    // Let's do some spam check!
    $error = $sg->validate($api_submit);

    if ($api_submit && !empty($error))
    {
        $skin->assign('error_message', $error);
        echo $skin->output("api_error.{$mode}");
        die;
    }

    // Save user and language data in cookies
    $core->set_cookie('author', $author, 365);
    $core->set_cookie('language', $language, 365);
}

if (($paste_submit || $api_submit) && strlen($data) > 0 && !$show_error)
{
    $author = trim($author);
    $timestr = time();
    $hash = substr($timestr . $timestr, rand(0, 5), 8);

    // Generate the password hash
    $salt = $auth->create_uid(5);
    $pwd_hash = $password ? $auth->create_password($password, $salt) : '';

    // Generate URL key
    if ($config->url_key_enabled)
    {
        $skip_insert = true;

        // Generate a unique key. We cannot simply use a constraint as we have a nullable column
        // We retry 3 times only
        for($unique = 1; $unique <= 3; $unique++)
        {
            $url_key = $auth->create_uid(8, $unique);
            $sql = "SELECT id AS count FROM {$db->prefix}main WHERE urlkey = :urlkey";

            $row = $db->query($sql, array(
                ':urlkey' => $url_key
            ));

            if ($row == null)
            {
                $skip_insert = false;
                break;
            }
        }
    }

    if (!$skip_insert)
    {
        // Insert into the DB
        $sql = "INSERT INTO {$db->prefix}main " .
               "(author, project, timestamp, expire, title, data, urlkey, " .
               "language, password, salt, private, hash, ip) VALUES " .
               "(:author, :project, :timestamp, :expire, :title, :data, :urlkey, " .
               ":language, :password, :salt, :private, :hash, :ip)";

        $db->query($sql, array(
            ':author'       => $author,
            ':project'      => $project,
            ':timestamp'    => $time,
            ':expire'       => $expire,
            ':title'        => $title,
            ':data'         => $data,
            ':urlkey'       => $url_key,
            ':language'     => $language,
            ':password'     => $pwd_hash,
            ':salt'         => $salt,
            ':private'      => $private == 'on' || $private == 'yes' || $password ? 1 : 0,
            ':hash'         => $hash,
            ':ip'           => $core->remote_ip()
        ));

        // Get the last inserted paste ID
        $new_id = $db->insert_id('id');
    }

    // Address API requests
    if ($mode == 'xml' || $mode == 'json')
    {
        if ($new_id)
        {
            $skin->assign(array(
                'paste_id'    => $config->url_key_enabled ? "p{$url_key}" : $new_id,
                'paste_hash'  => $private ? $hash : '',
            ));

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
            $hash_arg = ($private || $password) ? $hash : '';
            $url = $nav->get_paste($new_id, $url_key, $hash_arg, $project);

            if (!$password)
            {
                $core->redirect($url);
            }

            $message = $lang->get('paste_saved');
            $link = "<a href=\"{$url}\">{$url}</a>";

            $message = preg_replace('/\_\_url\_\_/', $link, $message);

            $skin->assign(array(
                'msg_visibility'    => 'visible',
                'error_visibility'  => 'hidden',
                'message_text'      => $message,
                'msg_color'         => 'green',
            ));
        }
        else
        {
            $skin->assign(array(
                'msg_visibility'    => 'visible',
                'error_visibility'  => 'hidden',
                'message_text'      => $lang->get('paste_error'),
                'msg_color'         => 'red',
            ));
        }
    }
}
else
{
    // Assign template data
    $skin->assign(array(
        'post_user'             => htmlspecialchars($author),
        'post_title'            => htmlspecialchars($title),
        'post_data'             => htmlspecialchars($data),
        'post_' . $language     => 'selected="selected"',
        'post_checked'          => ($private == "on" ? "checked" : ""),
        'msg_visibility'        => 'hidden',
    ));
}

// Assign template data
$skin->assign(array(
    'post_lang_list'            => $skin->output('tpl_languages'),
    'post_token'                => $sg->validate_token(true),
    'error_visibility'          => ($show_error === true ? 'visible' : 'hidden'),
));

// Yes, that's pretty much everything we need for index page ;)
$skin->title($lang->get('create_new') . ' &bull; ' . $lang->get('site_title'));
$skin->output();

?>
