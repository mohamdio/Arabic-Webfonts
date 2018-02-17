<?php
/**
 * Creating custom controls for the customizer
 *
 * @since      1.0
 *
 * @package    Arabic_Webfonts
 * @subpackage Arabic_Webfonts/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( class_exists( 'WP_Customize_Control') ) :

/**
 * Reset button control class
 *
 * @since    1.0
 */
class AWF_Customize_Reset_Button_Control extends WP_Customize_Control {
	
    // Define control type
	public $type = 'reset_button';

	// Render the content
	public function render_content() {
		?>
        <label>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <input class="button-primary awf-reset-button" id="<?php echo $this->id; ?>" type="button" value="<?php _e( 'Reset settings', 'arabic-webfonts' ); ?>">
            <br /><br />
        </label>
		<?php
	}

}

/**
 * Fields group control class
 *
 * @since    1.0
 */
class AWF_Customize_Fields_Group_Control extends WP_Customize_Control {
	
    // Define control type
	public $type = 'fields_group';

	// Render the content
	public function render_content() {
		?>
        <label>
            <span class="customize-control-title awf-fields-group" id="<?php echo $this->id; ?>"><?php echo esc_html( $this->label ); ?></span>
        </label>
		<?php
	}

}

/**
 * Jozoor plugins control class
 *
 * @since    1.0
 */
class AWF_Customize_Jozoor_Plugins_Control extends WP_Customize_Control {
	
    // Define control type
	public $type = 'jozoor_plugins';

	// Render the content
	public function render_content() {
		?>
        <label>
            <span class="customize-control-title awf-jozoor-plugins" id="<?php echo $this->id; ?>"><?php echo esc_html( $this->label ); ?></span>
        </label>
        <ul class="jozoor-plugins-list">
        	<li>
        		<a href="https://wordpress.org/plugins/shortcode-cleaner-lite/" target="_blank" class="awf-jozoor-plugin-name">Shortcode Cleaner</a>
        		<p>
        		<?php _e('Clean WordPress Content from Broken Shortcodes.', 'arabic-webfonts'); ?>
        		</p>
        		<a href="https://www.youtube.com/watch?v=dXVZ5bSUduc" target="_blank"><?php _e('View Demo', 'arabic-webfonts'); ?></a> | <a href="https://wordpress.org/plugins/shortcode-cleaner-lite/" target="_blank"><?php _e('Download Free', 'arabic-webfonts'); ?></a>
        	</li>
        </ul>
        <br>
        <?php _e('Follow Us', 'arabic-webfonts'); ?>
        <br>
        <a href="https://twitter.com/jozoor" target="_blank">Twitter</a> | 
        <a href="https://www.facebook.com/Jozoor" target="_blank">Facebook</a> | 
        <a href="https://youtube.com/jozoor" target="_blank">Youtube</a> | 
        <a href="https://dribbble.com/jozoor" target="_blank">Dribbble</a>
		<?php
	}

}

endif; // End Check Class Exists