<?php
/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
* 
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Invoke required files
include_once('init.php');

// Collect some data
$cat = $core->variable('cat', '');

// Set the file
$skin->init('tpl_doc_' . $cat);

// Yes, this is a tiny file. I like tiny files.
$skin->title($lang->get('doc_' . $cat . '_title') . ' &bull; ' . $lang->get('site_title'));
$skin->output();
?>

