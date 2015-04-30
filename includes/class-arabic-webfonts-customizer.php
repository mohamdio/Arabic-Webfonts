<?php
/**
 * The customizer api class that register all panels, sections and settings
 *
 * @since      1.0
 *
 * @package    Arabic_Webfonts
 * @subpackage Arabic_Webfonts/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'AWF_Customizer') ) :

class AWF_Customizer {

    /**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 */
    protected $plugin_name;
    
    /**
	 * Is this an 'option' or a 'theme_mod'? for setting.
	 *
	 * @since    1.0
	 * @access   protected
	 */
    protected $type;
    
    /**
	 * What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
	 *
	 * @since    1.0
	 * @access   protected
	 */
    protected $transport;
    
    /**
	 * Capability needed to tweak for panels & sections and settings
	 *
	 * @since    1.0
	 * @access   protected
	 */
    protected $capability;
    
    /**
	 * All fonts from fontface.me API.
	 *
	 * @since    1.2
	 * @access   protected
	 * @var      array    $fonts    All fonts from fontface.me API.
	 */
	protected $fonts = false;
    
    /**
	 * Define the customizer api functionality.
	 *
	 * @since    1.0
	 */
    public function __construct( $plugin_name, $fonts ) {
       
        $this->plugin_name = $plugin_name;
        $this->fonts = $fonts;
        
        $this->type        = 'theme_mod';
        $this->transport   = 'postMessage';
        $this->capability  = 'edit_theme_options';
        
        add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
        
    }
    
    /**
	 * Get the customizer settings.
	 *
	 * @since    1.0
	 * @access   private
	 */
    private function get_customizer_settings() {
        
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-arabic-webfonts-customizer-settings.php';
        $settings = new AWF_Customizer_Settings( $this->plugin_name, $this->fonts );
        return $settings->settings_fields();
        
    }
    
    /**
	 * Register the customizer settings.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function register_customizer_settings( $wp_customize ) {
        
        // load custom controls
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-arabic-webfonts-custom-controls.php';
        
        // get all settings
        $options = $this->get_customizer_settings();
        
        $priority = 0;
        
        foreach ( $options as $option ) {
            
            // Add panel
            if ( $option['type'] == 'panel' ) {
                
                $priority += 10;
                
                $wp_customize->add_panel( esc_attr( $option['id'] ), array(
                    'title'          => $option['title'],
                    'description'    => $option['description'],
                    'priority'       => $priority,
                    'theme_supports' => $option['theme_supports'],
                    'capability'     => $this->capability
                ) );

            }
            
            // Add section
            if ( $option['type'] == 'section' ) {
                
                $priority += 10;
                
                $wp_customize->add_section( esc_attr( $option['id'] ), array(
                    'title'          => $option['title'],
                    'description'    => $option['description'],
                    'priority'       => $priority,
                    'theme_supports' => $option['theme_supports'],
                    'panel'          => $option['panel'],
                    'capability'     => $this->capability
                ) );
                
                // Add settings & controls
                foreach ( $option['fields'] as $field ) {
                    
                    $priority += 10;
                    
                    // add setting
                    $wp_customize->add_setting( esc_attr( $field['id'] ), array(
                        'default'           => $field['default'],
                        'type'              => $this->type,
                        'capability'        => $this->capability,
                        'transport'         => $this->transport,
                        'sanitize_callback' => $field['sanitize_callback']
                    ) );
                    
                    // Add control fields
                    switch ( $field['type'] ) {

                        // Select field
                        case 'select':
                            $choices  = ( isset( $field['choices'] ) ) ? $field['choices'] : array();

                            $wp_customize->add_control( esc_attr( $field['id'].'_control' ), array(
                                'label'       => $field['title'],
                                'description' => $field['description'],
                                'section'     => $option['id'],
                                'settings'    => $field['id'],
                                'priority'    => $priority,
                                'type'        => 'select',
                                'choices'     => $choices
                            ) );
                        break;
                        
                        // Textarea field
                        case 'textarea':

                            $wp_customize->add_control( esc_attr( $field['id'].'_control' ), array(
                                'label'       => $field['title'],
                                'description' => $field['description'],
                                'section'     => $option['id'],
                                'settings'    => $field['id'],
                                'priority'    => $priority,
                                'type'        => 'textarea'
                            ) );
                        break;
                        
                        // Text field
                        case 'text':

                            $wp_customize->add_control( esc_attr( $field['id'].'_control' ), array(
                                'label'       => $field['title'],
                                'description' => $field['description'],
                                'section'     => $option['id'],
                                'settings'    => $field['id'],
                                'priority'    => $priority,
                                'type'        => 'text'
                            ) );
                        break;
                        
                        // Range Field
                        case 'range':
                            $input_attrs  = ( isset( $field['input_attrs'] ) ) ? $field['input_attrs'] : array();

                            $wp_customize->add_control( esc_attr( $field['id'].'_control' ) , array(
                                'label'       => $field['title'],
                                'description' => $field['description'],
                                'section'     => $option['id'],
                                'settings'    => $field['id'],
                                'priority'    => $priority,
                                'type'        => 'range',
                                'input_attrs' => $input_attrs
                            ) );
                        break;
                        
                        // reset button Field
                        case 'reset_button':

                            $wp_customize->add_control( new AWF_Customize_Reset_Button_Control( $wp_customize, esc_attr( $field['id'].'_control' ) , array(
                                'label'       => $field['title'],
                                'description' => $field['description'],
                                'section'     => $option['id'],
                                'settings'    => $field['id'],
                                'priority'    => $priority,
                                'type'        => 'reset_button',
                            ) )
                            );
                        
                        break;
                        
                        // fields group Field
                        case 'fields_group':

                            $wp_customize->add_control( new AWF_Customize_Fields_Group_Control( $wp_customize, esc_attr( $field['id'].'_control' ) , array(
                                'label'       => $field['title'],
                                'description' => $field['description'],
                                'section'     => $option['id'],
                                'settings'    => $field['id'],
                                'priority'    => $priority,
                                'type'        => 'fields_group',
                            ) )
                            );
                        
                        break;
                        
                    } // end switch field type
                    
                } // end sub foreach for settings & controls

            }
            
            
        } // end main foreach for panels & sections
        
    }

    
}

endif; // End Check Class Exists