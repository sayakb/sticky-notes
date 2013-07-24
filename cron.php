<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

// Cleanup expired pastes every 1 minute
$sql = "SELECT timestamp, locked FROM {$db->prefix}cron LIMIT 1";
$row = $db->query($sql, array(), true);
$timestamp = $row['timestamp'];
$locked = $row['locked'];

// Check the time difference
if (((time() - $timestamp) > 60) && !$locked)
{
    // Make sure the cron is run only once
    $db->query("UPDATE {$db->prefix}cron SET locked = 1 WHERE locked = 0");

    if ($db->affected_rows > 0)
    {
        // Caching garbage collection
        $cache->_gc();

        // Delete expired pastes
        $db->query("DELETE FROM {$db->prefix}main WHERE expire > 0 AND expire < " . time());

        // Clear expired sessions
        $db->query("DELETE FROM {$db->prefix}session WHERE timestamp < " . time() - 1200);

        // Update cron run time
        $db->query("UPDATE {$db->prefix}cron SET timestamp = " . time() . ", locked = 0");
    }
}

?>