<?php
/**
* Sticky Notes pastebin
* @ver 0.2
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2011 Sayak Banerjee <sayakb@kde.org>
* All rights reserved. Do not remove this copyright notice.
*/

class nav
{
    // Class level variables
    var $rewrite_on;

    // Constructor
    function __construct()
    {
        $this->rewrite_on = $this->check_rewrite();
    }
    
    // Check if mod_rewrite is enabled or not
    function check_rewrite()
    {
        if (function_exists('apache_get_modules'))
        {
            $modules = apache_get_modules();
            return in_array('mod_rewrite', $modules);
        }
        else
        {
            return getenv('HTTP_MOD_REWRITE') == 'On';
        }
    }

    // Gets a root navigation path
    function get($nav_key, $project = '', $page = 1)
    {
        try
        {
            global $core;

            // Set URL bases
            $base = $core->path();
            $project_arg = !empty($project) ? '?project=' . $project : '?';
            $page_arg = $page > 1 ? "&page={$page}" : ""; 
            $rewrite_base = $core->path() . (!empty($project) ? "~{$project}/" : "");
            $rewrite_page = $page > 1 ? "{$page}/" : "";

            // URLs when rewrite is enabled
            $rewrite_ary = array(
                'nav_newpaste'      => $rewrite_base,
                'nav_archives'      => "{$rewrite_base}all/{$rewrite_page}",
                'nav_rss'           => "{$rewrite_base}rss/",
                'nav_api'           => "{$rewrite_base}doc/api/",
                'nav_help'          => "{$rewrite_base}doc/help/",
                'nav_about'         => "{$rewrite_base}doc/about/",
                'nav_admin'         => "{$base}admin/",
            );

            // URLs when rewrite is disabled
            $general_ary = array(
                'nav_newpaste'      => "{$base}{$project_arg}",
                'nav_archives'      => "{$base}list.php{$project_arg}{$page_arg}",
                'nav_rss'           => "{$base}list.php{$project_arg}&rss=1",
                'nav_api'           => "{$base}doc.php{$project_arg}&cat=api",
                'nav_help'          => "{$base}doc.php{$project_arg}&cat=help",
                'nav_about'         => "{$base}doc.php{$project_arg}&cat=about",
                'nav_admin'         => "{$base}admin/",
            );

            // Generate the navigation URL
            if ($this->rewrite_on)
            {
                $url = $rewrite_ary[$nav_key];
            }
            else
            {
                $url = $general_ary[$nav_key];
            }

            return $url;
        }
        catch (Exception $e)
        {
            return null;
        }
    }

    // Get the URL for a paste
    function get_paste($paste_id, $hash, $project, $rss, $format = '')
    {
        global $core;
        
        try
        {
            $base_path = $rss ? $core->base_uri() : $core->path();
            
            if ($this->rewrite_on)
            {
                $url = $base_path . (!empty($project) ? "~{$project}/" : "") .
                                    "{$paste_id}/" .
                                    (!empty($hash) ? "{$hash}/" : "") .
                                    (!empty($format) ? "{$format}/" : "");
            }
            else
            {
                $url = $base_path . "show.php?id={$paste_id}" .
                                    (!empty($hash) ? "&hash={$hash}" : "") .
                                    (!empty($project) ? "&project={$project}" : "") .
                                    (!empty($format) ? "&mode={$format}" : "");
            }

            return $url;
        }
        catch (Exception $e)
        {
            return null;
        }
    }
}

?>