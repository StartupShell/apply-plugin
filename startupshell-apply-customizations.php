<?php
/*
   Plugin Name: Startup Shell | Apply Customizations
   Plugin URI: https://merus.it
   Version: 0.1
   Author: <a href="//merus.it">Merus</a>
   Description: Customizations for the Startup Shell | Apply portal.
   Text Domain: startupshell-apply-customizations
   License: GPLv3
  */

$StartupShellApplyCustomizations_minimalRequiredPhpVersion = '5.7';

/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function StartupShellApplyCustomizations_noticePhpVersionWrong() {
    global $StartupShellApplyCustomizations_minimalRequiredPhpVersion;
    echo '<div class="updated fade">' .
      __('Error: plugin "Startup Shell | Apply Customizations" requires a newer version of PHP to be running.',  'startupshell-apply-customizations').
            '<br/>' . __('Minimal version of PHP required: ', 'startupshell-apply-customizations') . '<strong>' . $StartupShellApplyCustomizations_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'startupshell-apply-customizations') . '<strong>' . phpversion() . '</strong>' .
         '</div>';
}


function StartupShellApplyCustomizations_PhpVersionCheck() {
    global $StartupShellApplyCustomizations_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $StartupShellApplyCustomizations_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'StartupShellApplyCustomizations_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function StartupShellApplyCustomizations_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('startupshell-apply-customizations', false, $pluginDir . '/languages/');
}


//////////////////////////////////
// Run initialization
/////////////////////////////////

// Initialize i18n
add_action('plugins_loadedi','StartupShellApplyCustomizations_i18n_init');

// Run the version check.
// If it is successful, continue with initialization for this plugin
if (StartupShellApplyCustomizations_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('startupshell-apply-customizations_init.php');
    StartupShellApplyCustomizations_init(__FILE__);
}
