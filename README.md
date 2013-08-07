Sticky Notes pastebin v1.0
===========================

Sticky notes is a free and open source pastebin application.
This software is protected by the BSD license, which means that you can freely
copy and share the software and its source code, and you are free to adapt from
this work. Although you may not remove the original copyright notice.
For details, please see: http://www.opensource.org/licenses/bsd-license.php

[![](http://www.pledgie.com/campaigns/20549.png?skin_name=chrome)](http://goo.gl/oWyEG)

Copyright (c) 2013, Sayak Banerjee <mail@sayakbanerjee.com>.
All rights reserved.

Sticky Notes v1.0 is under development. It is being written using the
[Laravel framework](http://laravel.com/).

However, the currently stable version is still available and maintained in the
master branch. It is strongly recommended that you do not use v1.0 in a
production environment until it is tagged as v1.0 and moved to master.


Dependencies
=============

Configuring sticky-notes is easy, you need to make the following changes:
 * Install the php Mcrypt extention (this is required by laravel)
 * Make the app/storage directory and its subfolders writable by your web server
 * You can place the contents of the `public/` directory in your web server root,
   or a subfolder. For example, if your web root is at `/var/www` and you want to
   run sticky-notes by running http://example.com/sticky-notes, place the public
   folder contents inside `/var/www/sticky-notes`
 * The rest of the files can be moved outside your web root as long as the path
   to the bootstrap folder is properly adjusted within the public/index.php file
