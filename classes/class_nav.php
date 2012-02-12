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

            // Define base URLs
            $rewrite_base = $core->path() . (!empty($project) ? '~' . $project . '/' : '');
            $general_base = $core->path() . (!empty($project) ? '?project=' . $project : '');
            $general_list = $core->path() . 'list.php' . (!empty($project) ? '?project=' . $project . '&page=' . $page
                                                                           : '?page=' . $page);

            // URLs when rewrite is enabled
            $rewrite_ary = array(
                'nav_newpaste'      => $rewrite_base,
                'nav_archives'      => $rewrite_base . 'all/',
                'nav_rss'           => $rewrite_base . 'rss/',
                'nav_api'           => $core->path() . 'doc/api/',
                'nav_help'          => $core->path() . 'doc/help/',
                'nav_about'         => $core->path() . 'doc/about/',
                'nav_admin'         => $core->path() . 'admin/',
            );

            // URLs when rewrite is disabled
            $general_ary = array(
                'nav_newpaste'      => $general_base,
                'nav_archives'      => $general_list,
                'nav_rss'           => $general_list . '&rss=1',
                'nav_api'           => $core->path() . 'doc.php?cat=api',
                'nav_help'          => $core->path() . 'doc.php?cat=help',
                'nav_about'         => $core->path() . 'doc.php?cat=about',
                'nav_admin'         => $core->path() . 'admin/',
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
    function get_paste($paste_id, $project, $rss, $format = '')
    {
        global $core;
        
        try
        {          
            if ($this->rewrite_on)
            {
                $url = !$rss ? $core->path() . ($project ? '~' . $project . '/' : '') . $paste_id .
                                               ($format ? $format . '/' : '')
                             : $core->base_uri() . $paste_id;
            }
            else
            {
                $url = !$rss ? $core->path() . 'show.php?id=' . $paste_id . ($project ? '&project = ' . $project : '') .
                                                                            ($format ? '&mode=' . $format : '')
                             : $core->base_uri() . 'show.php?id=' . $paste_id;
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