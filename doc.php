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
$cat = $core->variable('cat', '');

// Validate category
$docs = array('about', 'api', 'help');

if (!in_array($cat, $docs))
{
    exit;
}

// Set the file
$skin->init('tpl_doc_' . $cat);

// Set template variables
$skin->assign(array(
    'show_id'  => $skin->visibility($config->url_key_enabled, true),
    'show_key' => $skin->visibility($config->url_key_enabled),
));

// Yes, this is a tiny file. I like tiny files.
$skin->title($lang->get('doc_' . $cat . '_title') . ' &bull; ' . $lang->get('site_title'));
$skin->output();

?>

