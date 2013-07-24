<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

// Purge the session and show the login page
$core->unset_cookie('session_id_admin');
$core->unset_cookie('username_admin');
$core->set_cookie('logout_do', 'true', time() + 30);
$core->redirect($core->current_uri() . 'login.php');

?>
