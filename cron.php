<?php
/**
* Sticky Notes pastebin
* @ver 0.2
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

// Cleanup expired pastes every 1 minute
$sql = "SELECT timestamp, locked FROM {$db->prefix}cron LIMIT 1";
$row = $db->query($sql, true);
$timestamp = $row['timestamp'];
$locked = $row['locked'];

// Check the time difference
if (((time() - $timestamp) > 60) && !$locked)
{
    // Make sure the cron is run only once
    $db->query("UPDATE {$db->prefix}cron SET locked = 1 WHERE locked = 0");

    if ($db->affected_rows() > 0)
    {
        // Perform cron tasks
        $db->query("DELETE FROM {$db->prefix}main WHERE expire > 0 AND expire < " . time());
        $db->query("UPDATE {$db->prefix}cron SET timestamp = " . time() . ", locked = 0");
    }
}

?>