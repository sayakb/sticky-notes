Incorporate DB changes
=======================
 * Add authorid (same as id of user table) to main table.
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
