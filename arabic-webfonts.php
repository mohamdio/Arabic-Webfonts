<?php

/**
 * Plugin Name: Arabic Webfonts
 * Plugin URI: http://plugins.jozoor.com/arabic-webfonts/
 * Description: An easy way to add Arabic fonts to any theme without coding using WordPress Customizer.
 * Version: 1.4.6
 * Author: Jozoor
 * Author URI: https://codecanyon.net/user/jozoor/portfolio?ref=Jozoor
 * License: GPL2
 *
 * Text Domain: arabic-webfonts
 * Domain Path: /lang/
 *
 * @package Arabic_Webfonts
 * @author Jozoor Team
 * @since 1.0
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Require once the Composer Autoload.
 *
 * @since  1.4.5
 */
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Load the core plugin class.
require_once plugin_dir_path(__FILE__) . 'includes/class-arabic-webfonts.php';

/**
 * Run activation/deactivation hook.
 *
 * @since    1.0
 */
register_activation_hook(__FILE__, array('AWF_Arabic_Webfonts', 'activate'));
register_activation_hook(__FILE__, array('AWF_Arabic_Webfonts', 'deactivate'));

/**
 * Begins execution of the plugin.
 *
 * @since  1.0
 * @return object AWF_Arabic_Webfonts
 */
function awf_run_arabic_webfonts()
{

    // load plugin textdomain.
    load_plugin_textdomain('arabic-webfonts', false, dirname(plugin_basename(__FILE__)) . '/lang/');

    // run the plugin
    new AWF_Arabic_Webfonts();

}
add_action('plugins_loaded', 'awf_run_arabic_webfonts');
