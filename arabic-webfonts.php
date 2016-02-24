<?php

/**
 * Plugin Name: Arabic Webfonts
 * Plugin URI: http://plugins.jozoor.com/arabic-webfonts/
 * Description: An easy way to add Arabic fonts to any theme without coding using WordPress Customizer.
 * Version: 1.4.4
 * Author: Jozoor Team
 * Author URI: http://plugins.jozoor.com/
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
if ( ! defined( 'ABSPATH' ) ) exit;

// Load the core plugin class.
require_once( plugin_dir_path( __FILE__ ) . 'includes/class-arabic-webfonts.php' );

/**
 * Run activation/deactivation hook.
 *
 * @since    1.0
 */
register_activation_hook( __FILE__, array( 'AWF_Arabic_Webfonts', 'activate' ) );
register_activation_hook( __FILE__, array( 'AWF_Arabic_Webfonts', 'deactivate' ) );

/**
 * Begins execution of the plugin.
 *
 * @since  1.0
 * @return object AWF_Arabic_Webfonts
 */
function awf_run_arabic_webfonts() {

    new AWF_Arabic_Webfonts();

}
awf_run_arabic_webfonts();

/**
 * Load plugin textdomain.
 *
 * @since    1.0
 */
function awf_load_textdomain() {

    load_plugin_textdomain( 'arabic-webfonts', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

}
add_action( 'plugins_loaded', 'awf_load_textdomain' );
