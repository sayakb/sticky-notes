Incorporate DB changes
=======================
 * Add admin `tinyint[1]` column to users table
 * Change hash in main table to `varchar(12) not null`;
 * Drop session table
 * Create config table and insert config data:
      CREATE TABLE `paste_config` (
       `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
       `config_group` varchar(30) NOT NULL,
       `config_key` varchar(30) NOT NULL,
       `config_value` text,
       PRIMARY KEY (`id`)
     )
