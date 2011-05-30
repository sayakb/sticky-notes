<?php
/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Get global vars
global $skin, $lang, $title;

// Set the page title
$title = $lang->get('manage_pastes');

// Set the module data
$skin->assign(array(
    'module_data'   => $skin->output('tpl_pastes', false, true),
));

?>
