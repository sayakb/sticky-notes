Incorporate DB changes
=======================
 * Add author_id (same as id of user table) to main table.
 * Add admin `tinyint[1]` and active column to users table.
   active = 0 for banned user
 * Add type column to users table with default value 'db'.
   in update script, set value = 'ldap' for empty password
 * Change hash in main table to `varchar(12) not null`;
 * Drop session table
 * Create config table and insert config data:
	CREATE TABLE `paste_config` (
		`id` int(12) unsigned NOT NULL AUTO_INCREMENT,
		`group` varchar(30) NOT NULL,
		`key` varchar(30) NOT NULL,
		`value` text,
		PRIMARY KEY (`id`)
	)
 * Revisions table:
	 CREATE TABLE `paste_revisions` (
		`id` int(12) unsigned NOT NULL AUTO_INCREMENT,
		`paste_id` int(12) unsigned NOT NULL,
		`urlkey` varchar(9) NOT NULL DEFAULT '',
		`author` varchar(50) DEFAULT '',
		`timestamp` int(11) unsigned NOT NULL,
		PRIMARY KEY (`id`)
	)
 * Remove sid and lastlogin from paste_users
 * Generate urlkeys as a part of updater
 * If urlkeys already exist, prepend them with 'p'
 * Change length of urlkey column to 9
