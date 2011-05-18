<?php
/**
* Sticky Notes pastebin
* @ver 0.1
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/


///NOTICE Site Configuration

// Database host
$db_host = "";

// Port number, leave blank if unsure
$db_port = "";

// Name of database
$db_name = "";

// Db username
$db_username = "";

// Password (clear text)
$db_password = "";

// Table prefix
$db_prefix = "paste_";

// Name of the website
$site_name = "Sticky Notes";

// Title (tagline) of the site
$site_title = "Sticky Notes Pastebin";

// Copyright notice for the site
// You may add additional text to this copyright
// DO NOT REMOVE ORIGINAL COPYRIGHT
// (Protected by BSD License)
$site_copyright = "Powered by <a href=\"https://projects.kde.org/paste-kde-org\" rel=\"nofollow\">" .
				  "Sticky Notes</a>. Copyright &copy; 2011 <a href=\"" .
				  "http://sayakbanerjee.com\">Sayak Banerjee</a>.";

// Name of the skin to be used
// This need not be in lowercase
$skin_name = "Elegant";

// Language selection
$lang_name = "en_gb";


///NOTICE Antispam measures

/// General settings

// Enabled services (in CSV format)
$sg_services = "stealth,php";

/// Project Honey Pot (PHP) configuration
/// Details about Project Honey Pot: http://www.projecthoneypot.org/
/// For info on the configuration values below, check: http://www.projecthoneypot.org/httpbl_api.php

// Http:BL key
// Visit http://www.projecthoneypot.org/httpbl_configure.php to get your key
// Leave this blank if you want to disable this feature
$sg_php_key = "";

// Activity threshold (in no. of days)
// PHP responses with data older than these no. of days will be ignored
$sg_php_days = 90;

// Threat score
// IPs with PHP threat score greater than or equal to this will be disallowed
$sg_php_score = 50;

// Type of visitor filter
// Visitor type greater than or equal to this will be disallowed
$sg_php_type = 2;

?>