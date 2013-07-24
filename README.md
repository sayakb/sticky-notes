Sticky Notes pastebin v0.4
===========================

Sticky notes is a free and open source pastebin application.
This software is protected by the BSD license, which means that you can freely
copy and share the software and its source code, and you are free to adapt from
this work. Although you may not remove the original copyright notice whatsoever.
For details, please see: http://www.opensource.org/licenses/bsd-license.php

[![](http://www.pledgie.com/campaigns/20549.png?skin_name=chrome)](http://goo.gl/oWyEG)

Copyright (c) 2013, Sayak Banerjee <mail@sayakbanerjee.com>.
All rights reserved.


Installation
=============

Step 1 - Setting up the config file
------------------------------------
Rename config.sample.php to config.php and fill in the database details.

Step 2 - Server configuration
------------------------------
Because of the variety of apache configurations on different hosts, Sticky Notes
assumes certain apache flags/config values. Following are some configs to look
out for:
 * Check for the AllowOverride option - AllowOverride should be set to 'All' for
   your installation of Sticky Notes. This is required as we disable MultiViews
   at .htaccess level. See http://goo.gl/9UT1l for details.
 * magic_quotes_gpc is evil! We set this option to off in the .htaccess file to
   stop php from escaping incoming data, as we handle it explicitly. Some hosts
   may not recognize the php_flag directive in .htaccess and will throw you a
   server error. If you have php version <= 5.3.0 and your host does not
   recognize the php_flag directive, set magic_quotes to Off in your php.ini file
   as most webapps are designed to work without it. As of php 5.4.0, magic_quotes
   directive has been deprecated, so you can completely remove that line.
 * We need the php_openssl extension for the update checker to work. Without that,
   Sricky Notes will continue to work normally but the update check in the admin
   dashboard will not work. It is recommended that you enable this extension in
   your php.ini before installing Sticky Notes.

Sticky notes uses PDO extension for database access. To enable PDO, look for the
following in your php.ini
 * Linux users - extension=pdo.so
 * Windows users - extension=php_pdo.dll

For details on setting up PDO, see here: http://goo.gl/6bBnK

Step 3 - Running the installation script
-----------------------------------------
Open install.php and comment out line 19 (the line with the $gsod->trigger(...)
function). Visit http://www.yoursite.com/install in your internet browser
(assuming that Sticky Notes resides in the root folder of that site. Adjust the
URL accordingly).

Once the install script finishes executing, it should display "Successfully
installed" on the browser window.

Now uncomment line 19 again. This is strongly recommended for security reasons.
You may as well completely remove install.php from your host's root folder.

Step 4 - Cache configuration
-----------------------------
The only thing you need to do is to make the cache/ folder writable by apache.

If you want to disable caching, simply make the cache/ folder inaccessible to
apache or you may completely remove it from your web server. However, for high 
volume sites, it is recommended that you enable caching.

Sticky Notes cache works out of the box and does not depend on any PHP libraries.

Step 5 - Adding themes
-----------------------
Copy the theme folder within the "skins" folder. Make sure the name of the folder
is in lower case. For example, if the skin is BlueNinja, the folder's name should
be blueninja.

Now go to admin panel -> site configuration, and select the theme from the
list. Save your configuration by clicking the 'Save' button at the bottom.

Step 6 - Adding localization files
-----------------------------------
Copy the localization script (for example en_us.php) to the "lang" folder.
Make sure the file name is in lowercase.

Now login to the admin panel -> site configuration and change the language
code. Save your configuration by clicking the 'Save' button at the bottom.


Upgrading
==========

You can upgrade your Sticky Notes installation from v0.3 to v0.4 using the
automated update script. Simply comment out line 16 and visit
http://yoursite.com/upgrade in your browser. Once upgrade is complete,
remember to uncomment line 16 again or remove upgrade.php from your root
folder.