<?php
/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// We need to get the global vars
global $core;

// Purge the session and show the login page
$core->unset_cookie('session_id_admin');
$core->unset_cookie('username_admin');
$core->set_cookie('logout_do', 'true', time() + 30);
$core->redirect($core->path() . 'login/');

?>
