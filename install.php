<?php
/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

/* COMMENT OUT WHEN INSTALLING */
/* UNCOMMENT ONCE INSTALLING IS COMPLETED */
die('Open install.php and comment out line 13 when installing.');

include_once('./init.php');

$db->query("CREATE TABLE IF NOT EXISTS {$db_prefix}main (" .
           "id INT(12) UNSIGNED NOT NULL AUTO_INCREMENT, " .
           "author VARCHAR(50) DEFAULT '', " .
           "project VARCHAR(50) DEFAULT '', " .
           "timestamp INT(11) UNSIGNED NOT NULL, " .
	   "expire INT(11) UNSIGNED NOT NULL, " .
           "data MEDIUMTEXT NOT NULL, " .
           "language VARCHAR(50) NOT NULL DEFAULT 'php', " .
           "password VARCHAR(40) NOT NULL, " .
           "salt VARCHAR(5) NOT NULL, " .
           "private TINYINT(1) NOT NULL DEFAULT 0, " .
           "hash INT(12) UNSIGNED NOT NULL, " .
           "PRIMARY KEY(id))");

$db->query("CREATE TABLE IF NOT EXISTS {$db_prefix}session (" .
           "sid VARCHAR(40) NOT NULL, " .
           "timestamp INT(11) UNSIGNED NOT NULL, " .
           "PRIMARY KEY(sid))");

$db->query("CREATE TABLE IF NOT EXISTS {$db_prefix}cron (" .
           "timestamp INT(11) UNSIGNED NOT NULL DEFAULT 0, " .
	   "locked TINYINT(1) NOT NULL DEFAULT 0)");

$db->query("ALTER TABLE {$db_prefix}main DEFAULT CHARACTER SET utf8");
$db->query("ALTER TABLE {$db_prefix}main CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci");
$db->query("ALTER TABLE {$db_prefix}main AUTO_INCREMENT = 1000");
$db->query("CREATE INDEX {$db_prefix}idx_private ON {$db_prefix}main(id, private)");
$db->query("CREATE INDEX {$db_prefix}idx_author ON {$db_prefix}main(id, author)");
$db->query("CREATE INDEX {$db_prefix}idx_project ON {$db_prefix}main(id, project)");
$db->query("CREATE INDEX {$db_prefix}idx_data ON {$db_prefix}main(id, data)");
$db->query("CREATE INDEX {$db_prefix}idx_sid ON {$db_prefix}session(sid)");

$db->query("INSERT INTO {$db_prefix}cron VALUES (0, 0)");

die("Successfully installed");

?>