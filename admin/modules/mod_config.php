<?php
/**
* Sticky Notes pastebin
* @ver 0.4
* @license BSD License - www.opensource.org/licenses/bsd-license.php
*
* Copyright (c) 2013 Sayak Banerjee <mail@sayakbanerjee.com>
* All rights reserved. Do not remove this copyright notice.
*/

// Collect some data
$config_name = $core->variable('config_name', $config->site_name);
$config_title = $core->variable('config_title', $config->site_title);
$config_copyright = $core->variable('config_copyright', $config->site_copyright);
$config_skin = $core->variable('config_skin', $config->skin_name);
$config_lang = $core->variable('config_lang', $config->lang_name);
$config_admin_skin = $core->variable('config_admin_skin', $config->admin_skin_name);
$config_admin_lang = $core->variable('config_admin_lang', $config->admin_lang_name);
$config_url_key = $core->variable('config_url_key', $config->url_key_enabled ? 1 : 0);
$config_google_api_key = $core->variable('config_google_api_key', $config->google_api_key);
$config_tracking = $core->variable('config_tracking', $config->tracking_method == 'REMOTE_ADDR' ? 0 : 1);
$config_cache_life = $core->variable('config_cache_life', $config->cache_life);
$config_sg_svcs = $core->variable('config_sg_svcs', explode(',', $config->sg_services));
$config_php_key = $core->variable('config_php_key', $config->sg_php_key);
$config_php_days = $core->variable('config_php_days', $config->sg_php_days);
$config_php_score = $core->variable('config_php_score', $config->sg_php_score);
$config_php_type = $core->variable('config_php_type', $config->sg_php_type);
$config_censor = $core->variable('config_censor', $config->sg_censor);

$config_save = isset($_POST['config_save']);

// Save button was pressed
if ($config_save)
{
    // Validate required fields
    if (empty($config_name) || empty($config_title) || empty($config_copyright) ||
        empty($config_skin) || empty($config_admin_skin) || empty($config_lang))
    {
        $module->notify($lang->get('config_reqd'));
    }

    // Cache life should be >= 10
    if ($config_cache_life < 10)
    {
        $module->notify($lang->get('cache_life_range'));
    }

    // Check if the file is writable
    else if (!is_writable(realpath('../config.php')))
    {
        $module->notify($lang->get('config_cantwrite'));
    }

    // Write the conf data
    else
    {
        // Update configuration data
        $config->site_name       = $config_name;
        $config->site_title      = $config_title;
        $config->site_copyright  = $config_copyright;
        $config->skin_name       = $config_skin;
        $config->lang_name       = $config_lang;
        $config->admin_skin_name = $config_admin_skin;
        $config->admin_lang_name = $config_admin_lang;
        $config->url_key_enabled = $config_url_key != 0;
        $config->google_api_key  = $config_google_api_key;
        $config->cache_life      = $config_cache_life;
        $config->tracking_method = $config_tracking == 0 ? 'REMOTE_ADDR' : 'HTTP_X_FORWARDED_FOR';

        $config->sg_services     = implode(',', $config_sg_svcs);
        $config->sg_php_key      = $config_php_key;
        $config->sg_php_days     = $config_php_days;
        $config->sg_php_score    = $config_php_score;
        $config->sg_php_type     = $config_php_type;
        $config->sg_censor       = $config_censor;

        $config->save();
        $module->notify($lang->get('changes_saved'));
    }
}

// Generate the skin and language lists
$skin_list       = $skin->get_list('../skins', "", $config_skin, true);
$lang_list       = $skin->get_list('../lang', "index.html", $config_lang, false, true);
$admin_skin_list = $skin->get_list('./skins', "", $config_admin_skin, true);
$admin_lang_list = $skin->get_list('./lang', "index.html", $config_admin_lang, false, true);

// Generate the anti-spam services list
if (!is_array($config_sg_svcs))
{
    $config_sg_svcs = explode(',', $config_sg_svcs);
}

$available_svcs = $sg->get_registered();
$sg_svcs = '';

foreach ($available_svcs as $svc)
{
    $selected =  (in_array($svc, $config_sg_svcs));
    $sg_svcs .= '<option' . ($selected ? ' selected="selected"' : '') . '>' .
                $svc . '</option>';
}

// Assign skin data
$skin->assign(array(
    'config_name'               => htmlspecialchars($config_name),
    'config_title'              => htmlspecialchars($config_title),
    'config_copyright'          => htmlspecialchars($config_copyright),
    'config_php_key'            => htmlspecialchars($config_php_key),
    'config_php_days'           => htmlspecialchars($config_php_days),
    'config_php_score'          => htmlspecialchars($config_php_score),
    'config_php_type'           => htmlspecialchars($config_php_type),
    'config_censor'             => htmlspecialchars($config_censor),
    'config_google_api_key'     => htmlspecialchars($config_google_api_key),
    'config_cache_life'         => htmlspecialchars($config_cache_life),
    'config_tracking_native'    => $skin->selected($config_tracking == 0),
    'config_tracking_forwarded' => $skin->selected($config_tracking == 1),
    'config_url_key_yes'        => $skin->checked($config_url_key == 1),
    'config_url_key_no'         => $skin->checked($config_url_key == 0),
    'skin_list'                 => $skin_list,
    'lang_list'                 => $lang_list,
    'admin_skin_list'           => $admin_skin_list,
    'admin_lang_list'           => $admin_lang_list,
    'sg_svcs'                   => $sg_svcs,
));

// Set the page title
$module_title = $lang->get('site_config');
$module_data  = $skin->output('tpl_config_main', true, true);

?>

