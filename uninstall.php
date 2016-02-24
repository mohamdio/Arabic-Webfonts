<?php
/**
 * Uninstall Arabic Webfonts
 *
 * This file runs when the plugin in uninstalled (deleted).
 * This will not run when the plugin is deactivated.
 *
 * @since      1.0
 *
 * @package    Arabic_Webfonts
 */

// If plugin is not being uninstalled, exit (do nothing)
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Do actions here if plugin is being uninstalled.

/**
 * The uninstalling process.
 *
 * @since    1.0
 */
function awf_uninstall_arabic_webfonts_plugin() {

    if ( function_exists( 'is_multisite' ) && is_multisite() ) {

        // check permission
        if ( false == is_super_admin() ) {
            return;
        }

        // get all sites in network
        $sites = wp_get_sites();
        foreach ( $sites as $site ) {

            switch_to_blog( $site[ 'blog_id' ] );

            // delete custom post type
            awf_delete_custom_post_type();
            // remove all theme mods
            awf_remove_all_theme_mods();
            // delete transients
            delete_transient( 'awf-get-fonts' );

            restore_current_blog();

        }

    } else {

        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        // delete custom post type
        awf_delete_custom_post_type();
        // remove all theme mods
        awf_remove_all_theme_mods();
        // delete transients
        delete_transient( 'awf-get-fonts' );

    }

}
// Run the uninstalling process
awf_uninstall_arabic_webfonts_plugin();

/**
 * Delete custom post type [ awf_font_control ].
 *
 * @since    1.0
 */
function awf_delete_custom_post_type() {

    $args = array ( 'post_type' => 'awf_font_control', 'nopaging' => true );

    $query = new WP_Query ($args);
    while ($query->have_posts ()) {

        $query->the_post ();
        $id = get_the_ID ();
        wp_delete_post ($id, true);

    }

    wp_reset_postdata ();

}

/**
 * Remove all theme mods.
 *
 * @since    1.0
 */
function awf_remove_all_theme_mods() {

    remove_theme_mod('awf_body_font_family');
    remove_theme_mod('awf_body_font_size');
    remove_theme_mod('awf_body_line_height');
    remove_theme_mod('awf_paragraphs_font_family');
    remove_theme_mod('awf_paragraphs_font_size');
    remove_theme_mod('awf_paragraphs_line_height');
    remove_theme_mod('awf_paragraphs_text_decoration');
    remove_theme_mod('awf_h1_font_family');
    remove_theme_mod('awf_h1_font_size');
    remove_theme_mod('awf_h1_line_height');
    remove_theme_mod('awf_h1_text_decoration');
    remove_theme_mod('awf_h2_font_family');
    remove_theme_mod('awf_h2_font_size');
    remove_theme_mod('awf_h2_line_height');
    remove_theme_mod('awf_h2_text_decoration');
    remove_theme_mod('awf_h3_font_family');
    remove_theme_mod('awf_h3_font_size');
    remove_theme_mod('awf_h3_line_height');
    remove_theme_mod('awf_h3_text_decoration');
    remove_theme_mod('awf_h4_font_family');
    remove_theme_mod('awf_h4_font_size');
    remove_theme_mod('awf_h4_line_height');
    remove_theme_mod('awf_h4_text_decoration');
    remove_theme_mod('awf_h5_font_family');
    remove_theme_mod('awf_h5_font_size');
    remove_theme_mod('awf_h5_line_height');
    remove_theme_mod('awf_h5_text_decoration');
    remove_theme_mod('awf_h6_font_family');
    remove_theme_mod('awf_h6_font_size');
    remove_theme_mod('awf_h6_line_height');
    remove_theme_mod('awf_h6_text_decoration');

}
