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

endif; // End Check Class Exists