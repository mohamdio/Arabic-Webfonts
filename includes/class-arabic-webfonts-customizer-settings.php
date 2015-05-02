<?php
/**
 * Define all panels, sections and settings for customizer
 *
 * @since      1.0
 *
 * @package    Arabic_Webfonts
 * @subpackage Arabic_Webfonts/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'AWF_Customizer_Settings') ) :

class AWF_Customizer_Settings {

    /**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 */
    protected $textdomain;
    
    /**
	 * The prefix for all customizer settings.
	 *
	 * @since    1.0
	 * @access   protected
	 */
	protected $prefix;
    
    /**
	 * All fonts from fontface.me API.
	 *
	 * @since    1.2
	 * @access   protected
	 * @var      array    $fonts    All fonts from fontface.me API.
	 */
	protected $fonts = false;
    
    /**
	 * Define attributes for the customizer settings.
	 *
	 * @since    1.0
	 */
    public function __construct( $textdomain, $fonts ) {
        
        $this->textdomain = $textdomain;
        $this->prefix = 'awf_';
        $this->fonts = $fonts;
        
    }
    
    /**
	 * Load all fontface.me fonts.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function load_fonts() {
        
        /**
         * Get all fonts
         * this updated to use fonts API
         *
         * @since    1.2
         */
        
        $fonts = array();
        $fonts_permalink = array();
        $fonts_name = array();

        foreach ($this->fonts as $font) {
          $fonts_permalink[] = $font['permalink'];
          $fonts_name[] = $font['name'];
        }

        $fonts_none = array( '' => '— '.__( 'Select Font', $this->textdomain ).' —' );
        $all_fonts = array_combine($fonts_permalink, $fonts_name);
        $fonts = array_merge($fonts_none, $all_fonts);

        return $fonts;

    }
    
    /**
	 * Build settings fields for customizer.
	 *
	 * @since    1.0
	 * @access   public
	 */
	public function settings_fields() {
        
        /**
         *****************************
         * Build panels 
         *****************************
         */
        $options[] = array( 
            'id'             => $this->prefix . 'arabic_webfonts',
            'title'          => __( 'Arabic Webfonts', $this->textdomain ),
            'description'    => __( 'An easy way to add Arabic fonts to any theme without coding using WordPress Customizer.', $this->textdomain ),
            'theme_supports' => '',
            'type'           => 'panel' // type = panel
        ); 
        
        /**
         *****************************
         * Build sections & controls
         *****************************
         */
        
        // Body section
        $options[] = array( 
            'id'             => $this->prefix . 'body_styles',
            'title'          => __( 'Body', $this->textdomain ),
            'description'    => __( 'Control in body content typography', $this->textdomain ),
            'theme_supports' => '',
            'type'           => 'section', // type = section
            'panel'          => $this->prefix . 'arabic_webfonts', // panel
            'fields'         => array(
                                array(
                                    'id'                => $this->prefix . 'body_font_family',
                                    'title'             => __( 'Font Family', $this->textdomain ),
                                    'description'       => '',
                                    'default'           => '',
                                    'sanitize_callback' => '',
                                    'type'              => 'select', // field type
                                    'choices'           => $this->load_fonts()
                                ),
                                array(
                                    'id'                => $this->prefix . 'body_font_size',
                                    'title'             => __( 'Font Size', $this->textdomain ),
                                    'description'       => __( 'from 10 to 100 px, default = 14px', $this->textdomain ),
                                    'default'           => 14,
                                    'sanitize_callback' => '',
                                    'type'              => 'range', // field type
                                    'input_attrs'       => array(
                                        'min'   => 10,
                                        'max'   => 100,
                                        'step'  => 1,
                                        'style' => 'width: 100%',
                                    )
                                ),
                                array(
                                    'id'                => $this->prefix . 'body_line_height',
                                    'title'             => __( 'Line Height', $this->textdomain ),
                                    'description'       => __( 'from 0.5 to 5, default = 1.2', $this->textdomain ),
                                    'default'           => 1.2,
                                    'sanitize_callback' => '',
                                    'type'              => 'range', // field type
                                    'input_attrs'       => array(
                                        'min'   => 0.5,
                                        'max'   => 5,
                                        'step'  => 0.1,
                                        'style' => 'width: 100%',
                                    )
                                ),
                                array(
                                    'id'                => $this->prefix . 'body_settings_reset',
                                    'title'             => '',
                                    'description'       => '',
                                    'default'           => '',
                                    'sanitize_callback' => '',
                                    'type'              => 'reset_button', // field type
                                ),
            )
        ); 
        
        // Paragraphs section
        $options[] = array( 
            'id'             => $this->prefix . 'paragraphs_styles',
            'title'          => __( 'Paragraphs', $this->textdomain ),
            'description'    => __( 'Control in Paragraphs typography', $this->textdomain ),
            'theme_supports' => '',
            'type'           => 'section', // type = section
            'panel'          => $this->prefix . 'arabic_webfonts', // panel
            'fields'         => array(
                                array(
                                    'id'                => $this->prefix . 'paragraphs_font_family',
                                    'title'             => __( 'Font Family', $this->textdomain ),
                                    'description'       => '',
                                    'default'           => '',
                                    'sanitize_callback' => '',
                                    'type'              => 'select',
                                    'choices'           => $this->load_fonts()
                                ),
                                array(
                                    'id'                => $this->prefix . 'paragraphs_font_size',
                                    'title'             => __( 'Font Size', $this->textdomain ),
                                    'description'       => __( 'from 10 to 100 px, default = 14px', $this->textdomain ),
                                    'default'           => 14,
                                    'sanitize_callback' => '',
                                    'type'              => 'range', // field type
                                    'input_attrs'       => array(
                                        'min'   => 10,
                                        'max'   => 100,
                                        'step'  => 1,
                                        'style' => 'width: 100%',
                                    )
                                ),
                                array(
                                    'id'                => $this->prefix . 'paragraphs_line_height',
                                    'title'             => __( 'Line Height', $this->textdomain ),
                                    'description'       => __( 'from 0.5 to 5, default = 1.2', $this->textdomain ),
                                    'default'           => 1.2,
                                    'sanitize_callback' => '',
                                    'type'              => 'range', // field type
                                    'input_attrs'       => array(
                                        'min'   => 0.5,
                                        'max'   => 5,
                                        'step'  => 0.1,
                                        'style' => 'width: 100%',
                                    )
                                ),
                                array(
                                    'id'                => $this->prefix . 'paragraphs_text_decoration',
                                    'title'             => __( 'Text Decoration', $this->textdomain ),
                                    'description'       => '',
                                    'default'           => '',
                                    'sanitize_callback' => '',
                                    'type'              => 'select', // field type
                                    'choices'           => array(
                                        ''             => '— '.__( 'Select Decoration', $this->textdomain ).' —',
                                        'none'         => 'None',
                                        'underline'    => 'Underline',
                                        'line-through' => 'Line-through',
                                        'overline'     => 'Overline',
                                    )
                                ),
                                array(
                                    'id'                => $this->prefix . 'paragraphs_settings_reset',
                                    'title'             => '',
                                    'description'       => '',
                                    'default'           => '',
                                    'sanitize_callback' => '',
                                    'type'              => 'reset_button', // field type
                                ),
            )
        ); 
        
        // Headings section
        $options[] = array( 
            'id'             => $this->prefix . 'headings_styles',
            'title'          => __( 'Headings', $this->textdomain ),
            'description'    => __( 'Control in Headings typography', $this->textdomain ),
            'theme_supports' => '',
            'type'           => 'section', // type = section
            'panel'          => $this->prefix . 'arabic_webfonts', // panel
            'fields'         => array(
                
                                // H1 fields group
                                array(
                                    'id'                => $this->prefix . 'h1_fields_group',
                                    'title'             => __( 'Heading - H1', $this->textdomain ),
                                    'description'       => '',
                                    'default'           => '',
                                    'sanitize_callback' => '',
                                    'type'              => 'fields_group', // field type
                                ),
                                    array(
                                        'id'                => $this->prefix . 'h1_font_family',
                                        'title'             => __( 'Font Family', $this->textdomain ),
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'select', // field type
                                        'choices'           => $this->load_fonts()
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h1_font_size',
                                        'title'             => __( 'Font Size', $this->textdomain ),
                                        'description'       => __( 'from 10 to 100 px, default = 24px', $this->textdomain ),
                                        'default'           => 24,
                                        'sanitize_callback' => '',
                                        'type'              => 'range', // field type
                                        'input_attrs'       => array(
                                            'min'   => 10,
                                            'max'   => 100,
                                            'step'  => 1,
                                            'style' => 'width: 100%',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h1_line_height',
                                        'title'             => __( 'Line Height', $this->textdomain ),
                                        'description'       => __( 'from 0.5 to 5, default = 1.2', $this->textdomain ),
                                        'default'           => 1.2,
                                        'sanitize_callback' => '',
                                        'type'              => 'range', // field type
                                        'input_attrs'       => array(
                                            'min'   => 0.5,
                                            'max'   => 5,
                                            'step'  => 0.1,
                                            'style' => 'width: 100%',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h1_text_decoration',
                                        'title'             => __( 'Text Decoration', $this->textdomain ),
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'select', // field type
                                        'choices'           => array(
                                            ''             => '— '.__( 'Select Decoration', $this->textdomain ).' —',
                                            'none'         => 'None',
                                            'underline'    => 'Underline',
                                            'line-through' => 'Line-through',
                                            'overline'     => 'Overline',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h1_settings_reset',
                                        'title'             => '',
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'reset_button', // field type
                                    ),
                
                                // H2 fields group
                                array(
                                    'id'                => $this->prefix . 'h2_fields_group',
                                    'title'             => __( 'Heading - H2', $this->textdomain ),
                                    'description'       => '',
                                    'default'           => '',
                                    'sanitize_callback' => '',
                                    'type'              => 'fields_group', // field type
                                ),
                                    array(
                                        'id'                => $this->prefix . 'h2_font_family',
                                        'title'             => __( 'Font Family', $this->textdomain ),
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'select', // field type
                                        'choices'           => $this->load_fonts()
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h2_font_size',
                                        'title'             => __( 'Font Size', $this->textdomain ),
                                        'description'       => __( 'from 10 to 100 px, default = 22px', $this->textdomain ),
                                        'default'           => 22,
                                        'sanitize_callback' => '',
                                        'type'              => 'range', // field type
                                        'input_attrs'       => array(
                                            'min'   => 10,
                                            'max'   => 100,
                                            'step'  => 1,
                                            'style' => 'width: 100%',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h2_line_height',
                                        'title'             => __( 'Line Height', $this->textdomain ),
                                        'description'       => __( 'from 0.5 to 5, default = 1.2', $this->textdomain ),
                                        'default'           => 1.2,
                                        'sanitize_callback' => '',
                                        'type'              => 'range', // field type
                                        'input_attrs'       => array(
                                            'min'   => 0.5,
                                            'max'   => 5,
                                            'step'  => 0.1,
                                            'style' => 'width: 100%',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h2_text_decoration',
                                        'title'             => __( 'Text Decoration', $this->textdomain ),
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'select', // field type
                                        'choices'           => array(
                                            ''             => '— '.__( 'Select Decoration', $this->textdomain ).' —',
                                            'none'         => 'None',
                                            'underline'    => 'Underline',
                                            'line-through' => 'Line-through',
                                            'overline'     => 'Overline',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h2_settings_reset',
                                        'title'             => '',
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'reset_button', // field type
                                    ),
                
                                // H3 fields group
                                array(
                                    'id'                => $this->prefix . 'h3_fields_group',
                                    'title'             => __( 'Heading - H3', $this->textdomain ),
                                    'description'       => '',
                                    'default'           => '',
                                    'sanitize_callback' => '',
                                    'type'              => 'fields_group', // field type
                                ),
                                    array(
                                        'id'                => $this->prefix . 'h3_font_family',
                                        'title'             => __( 'Font Family', $this->textdomain ),
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'select', // field type
                                        'choices'           => $this->load_fonts()
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h3_font_size',
                                        'title'             => __( 'Font Size', $this->textdomain ),
                                        'description'       => __( 'from 10 to 100 px, default = 20px', $this->textdomain ),
                                        'default'           => 20,
                                        'sanitize_callback' => '',
                                        'type'              => 'range', // field type
                                        'input_attrs'       => array(
                                            'min'   => 10,
                                            'max'   => 100,
                                            'step'  => 1,
                                            'style' => 'width: 100%',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h3_line_height',
                                        'title'             => __( 'Line Height', $this->textdomain ),
                                        'description'       => __( 'from 0.5 to 5, default = 1.2', $this->textdomain ),
                                        'default'           => 1.2,
                                        'sanitize_callback' => '',
                                        'type'              => 'range', // field type
                                        'input_attrs'       => array(
                                            'min'   => 0.5,
                                            'max'   => 5,
                                            'step'  => 0.1,
                                            'style' => 'width: 100%',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h3_text_decoration',
                                        'title'             => __( 'Text Decoration', $this->textdomain ),
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'select', // field type
                                        'choices'           => array(
                                            ''             => '— '.__( 'Select Decoration', $this->textdomain ).' —',
                                            'none'         => 'None',
                                            'underline'    => 'Underline',
                                            'line-through' => 'Line-through',
                                            'overline'     => 'Overline',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h3_settings_reset',
                                        'title'             => '',
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'reset_button', // field type
                                    ),
                
                                 // H4 fields group
                                array(
                                    'id'                => $this->prefix . 'h4_fields_group',
                                    'title'             => __( 'Heading - H4', $this->textdomain ),
                                    'description'       => '',
                                    'default'           => '',
                                    'sanitize_callback' => '',
                                    'type'              => 'fields_group', // field type
                                ),
                                    array(
                                        'id'                => $this->prefix . 'h4_font_family',
                                        'title'             => __( 'Font Family', $this->textdomain ),
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'select', // field type
                                        'choices'           => $this->load_fonts()
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h4_font_size',
                                        'title'             => __( 'Font Size', $this->textdomain ),
                                        'description'       => __( 'from 10 to 100 px, default = 18px', $this->textdomain ),
                                        'default'           => 18,
                                        'sanitize_callback' => '',
                                        'type'              => 'range', // field type
                                        'input_attrs'       => array(
                                            'min'   => 10,
                                            'max'   => 100,
                                            'step'  => 1,
                                            'style' => 'width: 100%',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h4_line_height',
                                        'title'             => __( 'Line Height', $this->textdomain ),
                                        'description'       => __( 'from 0.5 to 5, default = 1.2', $this->textdomain ),
                                        'default'           => 1.2,
                                        'sanitize_callback' => '',
                                        'type'              => 'range', // field type
                                        'input_attrs'       => array(
                                            'min'   => 0.5,
                                            'max'   => 5,
                                            'step'  => 0.1,
                                            'style' => 'width: 100%',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h4_text_decoration',
                                        'title'             => __( 'Text Decoration', $this->textdomain ),
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'select', // field type
                                        'choices'           => array(
                                            ''             => '— '.__( 'Select Decoration', $this->textdomain ).' —',
                                            'none'         => 'None',
                                            'underline'    => 'Underline',
                                            'line-through' => 'Line-through',
                                            'overline'     => 'Overline',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h4_settings_reset',
                                        'title'             => '',
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'reset_button', // field type
                                    ),
                
                                 // H5 fields group
                                array(
                                    'id'                => $this->prefix . 'h5_fields_group',
                                    'title'             => __( 'Heading - H5', $this->textdomain ),
                                    'description'       => '',
                                    'default'           => '',
                                    'sanitize_callback' => '',
                                    'type'              => 'fields_group', // field type
                                ),
                                    array(
                                        'id'                => $this->prefix . 'h5_font_family',
                                        'title'             => __( 'Font Family', $this->textdomain ),
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'select', // field type
                                        'choices'           => $this->load_fonts()
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h5_font_size',
                                        'title'             => __( 'Font Size', $this->textdomain ),
                                        'description'       => __( 'from 10 to 100 px, default = 16px', $this->textdomain ),
                                        'default'           => 16,
                                        'sanitize_callback' => '',
                                        'type'              => 'range', // field type
                                        'input_attrs'       => array(
                                            'min'   => 10,
                                            'max'   => 100,
                                            'step'  => 1,
                                            'style' => 'width: 100%',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h5_line_height',
                                        'title'             => __( 'Line Height', $this->textdomain ),
                                        'description'       => __( 'from 0.5 to 5, default = 1.2', $this->textdomain ),
                                        'default'           => 1.2,
                                        'sanitize_callback' => '',
                                        'type'              => 'range', // field type
                                        'input_attrs'       => array(
                                            'min'   => 0.5,
                                            'max'   => 5,
                                            'step'  => 0.1,
                                            'style' => 'width: 100%',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h5_text_decoration',
                                        'title'             => __( 'Text Decoration', $this->textdomain ),
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'select', // field type
                                        'choices'           => array(
                                            ''             => '— '.__( 'Select Decoration', $this->textdomain ).' —',
                                            'none'         => 'None',
                                            'underline'    => 'Underline',
                                            'line-through' => 'Line-through',
                                            'overline'     => 'Overline',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h5_settings_reset',
                                        'title'             => '',
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'reset_button', // field type
                                    ),
                
                                 // H6 fields group
                                array(
                                    'id'                => $this->prefix . 'h6_fields_group',
                                    'title'             => __( 'Heading - H6', $this->textdomain ),
                                    'description'       => '',
                                    'default'           => '',
                                    'sanitize_callback' => '',
                                    'type'              => 'fields_group', // field type
                                ),
                                    array(
                                        'id'                => $this->prefix . 'h6_font_family',
                                        'title'             => __( 'Font Family', $this->textdomain ),
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'select', // field type
                                        'choices'           => $this->load_fonts()
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h6_font_size',
                                        'title'             => __( 'Font Size', $this->textdomain ),
                                        'description'       => __( 'from 10 to 100 px, default = 14px', $this->textdomain ),
                                        'default'           => 14,
                                        'sanitize_callback' => '',
                                        'type'              => 'range', // field type
                                        'input_attrs'       => array(
                                            'min'   => 10,
                                            'max'   => 100,
                                            'step'  => 1,
                                            'style' => 'width: 100%',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h6_line_height',
                                        'title'             => __( 'Line Height', $this->textdomain ),
                                        'description'       => __( 'from 0.5 to 5, default = 1.2', $this->textdomain ),
                                        'default'           => 1.2,
                                        'sanitize_callback' => '',
                                        'type'              => 'range', // field type
                                        'input_attrs'       => array(
                                            'min'   => 0.5,
                                            'max'   => 5,
                                            'step'  => 0.1,
                                            'style' => 'width: 100%',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h6_text_decoration',
                                        'title'             => __( 'Text Decoration', $this->textdomain ),
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'select', // field type
                                        'choices'           => array(
                                            ''             => '— '.__( 'Select Decoration', $this->textdomain ).' —',
                                            'none'         => 'None',
                                            'underline'    => 'Underline',
                                            'line-through' => 'Line-through',
                                            'overline'     => 'Overline',
                                        )
                                    ),
                                    array(
                                        'id'                => $this->prefix . 'h6_settings_reset',
                                        'title'             => '',
                                        'description'       => '',
                                        'default'           => '',
                                        'sanitize_callback' => '',
                                        'type'              => 'reset_button', // field type
                                    ),
                
            )
        ); 
        
        /** 
        * Custom controls section
        */
        
        // check if any controls exists
        $custom_controls = get_posts( array('post_type' => 'awf_font_control', 'posts_per_page' => -1) );
        
        if($custom_controls) {
            
            // get all controls
            $fields_group =  array();
            foreach ( $custom_controls as $post ){

              $fields_group[] = array(
                                'id'                => $this->prefix . $post->ID .'_fields_group',
                                'title'             => get_the_title( $post->ID ),
                                'description'       => '',
                                'default'           => '',
                                'sanitize_callback' => '',
                                'type'              => 'fields_group', // field type
                            );

              array_push( $fields_group, 
                            array(
                                'id'                => $this->prefix . $post->ID .'_font_family',
                                'title'             => __( 'Font Family', $this->textdomain ),
                                'description'       => '',
                                'default'           => '',
                                'sanitize_callback' => '',
                                'type'              => 'select', // field type
                                'choices'           => $this->load_fonts()
                            ),
                            array(
                                'id'                => $this->prefix . $post->ID .'_font_size',
                                'title'             => __( 'Font Size', $this->textdomain ),
                                'description'       => __( 'from 10 to 100 px, default = 14px', $this->textdomain ),
                                'default'           => 14,
                                'sanitize_callback' => '',
                                'type'              => 'range', // field type
                                'input_attrs'       => array(
                                    'min'   => 10,
                                    'max'   => 100,
                                    'step'  => 1,
                                    'style' => 'width: 100%',
                                )
                            ),
                            array(
                                'id'                => $this->prefix . $post->ID .'_line_height',
                                'title'             => __( 'Line Height', $this->textdomain ),
                                'description'       => __( 'from 0.5 to 5, default = 1.2', $this->textdomain ),
                                'default'           => 1.2,
                                'sanitize_callback' => '',
                                'type'              => 'range', // field type
                                'input_attrs'       => array(
                                    'min'   => 0.5,
                                    'max'   => 5,
                                    'step'  => 0.1,
                                    'style' => 'width: 100%',
                                )
                            ),
                            array(
                                'id'                => $this->prefix . $post->ID .'_text_decoration',
                                'title'             => __( 'Text Decoration', $this->textdomain ),
                                'description'       => '',
                                'default'           => '',
                                'sanitize_callback' => '',
                                'type'              => 'select', // field type
                                'choices'           => array(
                                    ''             => '— '.__( 'Select Decoration', $this->textdomain ).' —',
                                    'none'         => 'None',
                                    'underline'    => 'Underline',
                                    'line-through' => 'Line-through',
                                    'overline'     => 'Overline',
                                )
                            ),
                            array(
                                'id'                => $this->prefix . $post->ID .'_settings_reset',
                                'title'             => '',
                                'description'       => '',
                                'default'           => '',
                                'sanitize_callback' => '',
                                'type'              => 'reset_button', // field type
                            )
                        );

            }
            
            $options[] = array( 
                'id'             => $this->prefix . 'custom_controls_styles',
                'title'          => __( 'Custom controls', $this->textdomain ),
                'description'    => __( 'Control in Custom Controls typography', $this->textdomain ),
                'theme_supports' => '',
                'type'           => 'section', // type = section
                'panel'          => $this->prefix . 'arabic_webfonts', // panel
                'fields'         => $fields_group
            ); 
            
        }
        
        // Reset All section
        $options[] = array( 
            'id'             => $this->prefix . 'reset_all',
            'title'          => __( 'Reset All Settings', $this->textdomain ),
            'description'    => __( 'When click to reset, All settings will be lost and replaced with default settings!', $this->textdomain ),
            'theme_supports' => '',
            'type'           => 'section', // type = section
            'panel'          => $this->prefix . 'arabic_webfonts', // panel
            'fields'         => array(
                                array(
                                    'id'                => $this->prefix . 'all_settings_reset',
                                    'title'             => '',
                                    'description'       => '',
                                    'default'           => '',
                                    'sanitize_callback' => '',
                                    'type'              => 'reset_button', // field type
                                ),
            )
        ); 
        
        
        return $options;
        
        
    }

    
}

endif; // End Check Class Exists