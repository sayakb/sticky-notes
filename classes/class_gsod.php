<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

/**
* In case if you're wondering, GSoD is, in fact, the Grey Screen of Death!
*/

class gsod
{
    // Method to trigger an error
    function trigger($message)
    {
        // This needs to be hard coded, we can't depend on any of the class files
        echo '<html><head><title>Sticky Notes error</title><style type="text/css">' .
             'a {color: #000;}</style></head>' .
             '<body style="background:#efefef; font-family:Arial; font-size:0.95em;">' .
             '<div style="border:1px solid #aeaeae; border-radius:10px;'.
             'padding:10px; background: #fff;">' .
             $message . '</div></body></html>';
        exit;
    }
}
?>
