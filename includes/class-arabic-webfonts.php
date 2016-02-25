<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0
 *
 * @package    Arabic_Webfonts
 * @subpackage Arabic_Webfonts/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'AWF_Arabic_Webfonts') ) :

class AWF_Arabic_Webfonts {

    /**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;
	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;
    /**
	 * All fonts from fontface.me API.
	 *
	 * @since    1.2
	 * @access   protected
	 * @var      array    $fonts    All fonts from fontface.me API.
	 */
	protected $fonts = false;


    /**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0
	 */
    public function __construct() {

        $this->plugin_name = 'arabic-webfonts';
        $this->version = '1.4.4';
        $this->fonts = $this->get_fonts();

        $this->load_customizer();
        $this->get_post_type();

        add_action( 'wp_head' , array( $this, 'header_output' ) );
        add_action( 'wp_enqueue_scripts' , array( $this, 'enqueue_style' ) );
        add_action( 'customize_controls_enqueue_scripts' , array( $this, 'customize_controls_script' ) );
        add_action( 'customize_preview_init' , array( $this, 'live_preview' ) );
        add_action( 'wp_ajax_reset_customizer_settings', array( $this, 'ajax_reset_customizer_settings' ) );
        add_action( 'wp_footer', array( $this, 'custom_footer_actions' ), 9999, 0 );
        add_action( 'wp_footer_preview', array( $this, 'custom_css_selectors_live_preview' ), 9999, 0 );

    }

    /**
	 * The code that runs during plugin activation.
	 *
	 * @since    1.0
	 * @access   public static
	 */
    public static function activate() {

        // get wp version
        global $wp_version;

        // compatible version (or later)
        $wp_compatible_version  = '4.0';

        if ( version_compare( $wp_version, $wp_compatible_version, '<' ) ) {

            deactivate_plugins( basename( __FILE__ ) );
            wp_die(
                '<p>' .
                sprintf(
                    __( 'This plugin can not be activated because it requires a WordPress version at least %1$s (or later). Please go to Dashboard &#9656; Updates to get the latest version of WordPress.', 'arabic-webfonts' ),
                    $wp_compatible_version
                )
                . '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . __( 'go back', 'arabic-webfonts' ) . '</a>'
            );

        }

    }

    /**
	 * The code that runs during plugin deactivation.
	 *
	 * @since    1.0
	 * @access   public static
	 */
    public static function deactivate() {

		// delete transients
		delete_transient( 'awf-get-fonts' );

    }

    /**
	 * Get all fonts from fontface.me API.
	 *
	 * @since    1.2
	 * @access   public
	 */
    public function get_fonts() {

        // name of transient in database
        $transName = 'awf-get-fonts';

        // time in days between updates, set to 2 days
        $cacheTime = 2 * DAY_IN_SECONDS;

        // get cached fonts
        $content = get_transient( $transName );

        // check for transient. If none, then get all fonts from API
        if( $content === false ) {

        	// get all fonts from API json content
            $fontfaceApi = 'http://fontface.me/font/all';
            $fontsContent = wp_remote_get( $fontfaceApi, array('sslverify' => false) );

            // check if it is not a valid request
            if( is_wp_error( $fontsContent ) ) {

                return;

            } else {

                $content = json_decode($fontsContent['body'], true);
        		set_transient($transName, $content, $cacheTime);

            }

        }

		return $content;

    }

    /**
	 * Load the customizer api.
	 *
	 * @since    1.0
	 * @access   private
	 */
    private function load_customizer() {

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-arabic-webfonts-customizer.php';
        new AWF_Customizer( $this->plugin_name, $this->fonts );

    }

    /**
	 * Get the custom post type [ awf_font_control ].
	 *
	 * @since    1.0
	 * @access   private
	 */
    private function get_post_type() {

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-arabic-webfonts-post-type.php';
        new AWF_Post_Type( $this->plugin_name );

    }

    /**
	 * Get all font controls ids from custom post type [ awf_font_control ].
	 *
	 * @since    1.0
	 * @access   private
	 */
    private function get_custom_controls_ids() {

        // check if any controls exists
        $custom_controls = get_posts( array('post_type' => 'awf_font_control', 'posts_per_page' => -1) );

        if($custom_controls) {

            // get all controls ids
            $custom_controls_ids =  array();
            foreach ( $custom_controls as $post ) {

                $custom_controls_ids[] = $post->ID;

            }

            return $custom_controls_ids;

        }

    }

    /**
	 * Enqueue style for for live customize preview and the normal site.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function enqueue_style() {

        // check if customize preview is active
        if ( is_customize_preview() ) {

            /**
             * Get all fonts
             * this updated to use fonts API
             *
             * @since    1.2
             */
            $all_fonts = array();
            foreach ($this->get_fonts() as $font) {
                $all_fonts[] = $font['permalink'] . ',';
            }
            $all_fonts_display = implode('',$all_fonts);

            wp_enqueue_style(
                $this->plugin_name .'-live',
                '//www.fontstatic.com/f='.$all_fonts_display,
                array(),
                null,
                'all'
            );

        } else {

            // get body font
            if ( get_theme_mod('awf_body_font_family') != '' ) { $body_font = get_theme_mod('awf_body_font_family'). ','; } else { $body_font = ''; }
            // get paragraphs font
            if ( get_theme_mod('awf_paragraphs_font_family') != '' ) { $paragraphs_font = get_theme_mod('awf_paragraphs_font_family'). ','; } else { $paragraphs_font = ''; }
            // get h1 font
            if ( get_theme_mod('awf_h1_font_family') != '' ) { $h1_font = get_theme_mod('awf_h1_font_family'). ','; } else { $h1_font = ''; }
            // get h2 font
            if ( get_theme_mod('awf_h2_font_family') != '' ) { $h2_font = get_theme_mod('awf_h2_font_family'). ','; } else { $h2_font = ''; }
            // get h3 font
            if ( get_theme_mod('awf_h3_font_family') != '' ) { $h3_font = get_theme_mod('awf_h3_font_family'). ','; } else { $h3_font = ''; }
            // get h4 font
            if ( get_theme_mod('awf_h4_font_family') != '' ) { $h4_font = get_theme_mod('awf_h4_font_family'). ','; } else { $h4_font = ''; }
            // get h5 font
            if ( get_theme_mod('awf_h5_font_family') != '' ) { $h5_font = get_theme_mod('awf_h5_font_family'). ','; } else { $h5_font = ''; }
            // get h6 font
            if ( get_theme_mod('awf_h6_font_family') != '' ) { $h6_font = get_theme_mod('awf_h6_font_family'). ','; } else { $h6_font = ''; }

            // get custom controls fonts
            if( $this->get_custom_controls_ids() ) {

                $custom_controls_fonts = array();
                foreach ( $this->get_custom_controls_ids() as $id ) {

                    if ( get_theme_mod('awf_'. $id .'_font_family') != '' ) {
                        $custom_controls_fonts[] = get_theme_mod('awf_'. $id .'_font_family'). ',';
                    } else { $custom_controls_fonts[] = ''; }

                }

                $custom_controls_fonts_display = implode('',$custom_controls_fonts);

            } else {
                $custom_controls_fonts_display = '';
            }


            if( empty($body_font) && empty($paragraphs_font) && empty($h1_font) && empty($h2_font) && empty($h3_font)
                && empty($h4_font) && empty($h5_font) && empty($h6_font) && empty($custom_controls_fonts_display) ) {

            } else {

                // check if font type is duplicated
                $default_settings_fonts_types = array ( $body_font, $paragraphs_font, $h1_font, $h2_font, $h3_font, $h4_font, $h5_font, $h6_font );
                if( $this->get_custom_controls_ids() ) {
                    $all_fonts_types = array_merge($default_settings_fonts_types, $custom_controls_fonts);
                    $final_fonts_types = implode('',array_unique($all_fonts_types));
                } else {
                    $final_fonts_types = implode('',array_unique($default_settings_fonts_types));
                }

                wp_enqueue_style(
                    $this->plugin_name,
                    '//www.fontstatic.com/f='.$final_fonts_types,
                    array(),
                    null,
                    'all'
                );

            }
        }

    }

    /**
	 * Do action footer custom action working like [ customize_preview_init ].
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function custom_footer_actions() {

        // check if customize preview is active
        if ( is_customize_preview() ) {

           // this action used to load wp.customize in live preview for custom controls
           do_action('wp_footer_preview');

        }

    }

    /**
	 * Enqueue script for customize live preview.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function live_preview() {

        wp_enqueue_script( $this->plugin_name .'-customizer-preview', plugins_url( '/assets/js/customizer-preview.js', dirname(__FILE__) ), array( 'jquery', 'customize-preview' ), $this->version, true );

    }

    /**
	 * Enqueue custom script for cunstom controls in customize live preview.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function custom_css_selectors_live_preview() {

        // get custom controls styles
        if( $this->get_custom_controls_ids() ) {

        ?>
        <script type="text/javascript" id="<?php echo $this->plugin_name .'-css-selectors-customizer-preview'; ?>">

        ( function( $ ) {

            <?php
            foreach ( $this->get_custom_controls_ids() as $id ) {

                // get css selectors
                $css_selectors = get_post_meta( $id, '_awf_css_selectors', true );

                if( !empty( $css_selectors ) ) {
            ?>

                    // Update paragraphs font family in real time...
                    wp.customize( 'awf_<?php echo $id; ?>_font_family', function( value ) {
                        value.bind( function( newval ) {
                            $('<?php echo $css_selectors; ?>').css('font-family', newval );
                        } );
                    } );

                    // Update paragraphs font size in real time...
                    wp.customize( 'awf_<?php echo $id; ?>_font_size', function( value ) {
                        value.bind( function( newval ) {
                            $('<?php echo $css_selectors; ?>').css('font-size', newval+ 'px' );
                        } );
                    } );

                    // Update paragraphs line height in real time...
                    wp.customize( 'awf_<?php echo $id; ?>_line_height', function( value ) {
                        value.bind( function( newval ) {
                            $('<?php echo $css_selectors; ?>').css('line-height', newval );
                        } );
                    } );

                    // Update paragraphs text decoration in real time...
                    wp.customize( 'awf_<?php echo $id; ?>_text_decoration', function( value ) {
                        value.bind( function( newval ) {
                            $('<?php echo $css_selectors; ?>').css('text-decoration', newval );
                        } );
                    } );

            <?php
                } // end check selectors

            } // end controls foreach
            ?>

        } )( jQuery );

        </script>
        <?php
        } // end check controls

    }

    /**
	 * Enqueue style and script for customizer controls.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function customize_controls_script() {

        wp_enqueue_style(
            $this->plugin_name .'-customizer-controls',
            plugins_url( '/assets/css/customizer-controls.css', dirname(__FILE__) ),
            array(),
            $this->version
        );

        wp_enqueue_script(
            $this->plugin_name .'-customizer-controls',
            plugins_url( '/assets/js/customizer-controls.js', dirname(__FILE__) ),
            array( 'jquery', 'customize-controls' ),
            $this->version, true
        );

        wp_localize_script(
            $this->plugin_name .'-customizer-controls',
            'AWF_Customizer_Reset',
            array( 'confirm' => __( "Click OK to reset. All settings will be lost and replaced with default settings!", $this->plugin_name ), )
        );

    }

    /**
	 * Ajax method for reset customizer settings button.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function ajax_reset_customizer_settings() {

        // get section id
        $reset_section = esc_attr($_POST['reset_section']);

        switch ( $reset_section ) {

            // body section
            case 'awf_body_settings_reset_control':

              remove_theme_mod('awf_body_font_family');
              remove_theme_mod('awf_body_font_size');
              remove_theme_mod('awf_body_line_height');

            break;

            // paragraphs section
            case 'awf_paragraphs_settings_reset_control':

              remove_theme_mod('awf_paragraphs_font_family');
              remove_theme_mod('awf_paragraphs_font_size');
              remove_theme_mod('awf_paragraphs_line_height');
              remove_theme_mod('awf_paragraphs_text_decoration');

            break;

            // headings section - h1 group
            case 'awf_h1_settings_reset_control':

              remove_theme_mod('awf_h1_font_family');
              remove_theme_mod('awf_h1_font_size');
              remove_theme_mod('awf_h1_line_height');
              remove_theme_mod('awf_h1_text_decoration');

            break;

            // headings section - h2 group
            case 'awf_h2_settings_reset_control':

              remove_theme_mod('awf_h2_font_family');
              remove_theme_mod('awf_h2_font_size');
              remove_theme_mod('awf_h2_line_height');
              remove_theme_mod('awf_h2_text_decoration');

            break;

            // headings section - h3 group
            case 'awf_h3_settings_reset_control':

              remove_theme_mod('awf_h3_font_family');
              remove_theme_mod('awf_h3_font_size');
              remove_theme_mod('awf_h3_line_height');
              remove_theme_mod('awf_h3_text_decoration');

            break;

            // headings section - h4 group
            case 'awf_h4_settings_reset_control':

              remove_theme_mod('awf_h4_font_family');
              remove_theme_mod('awf_h4_font_size');
              remove_theme_mod('awf_h4_line_height');
              remove_theme_mod('awf_h4_text_decoration');

            break;

            // headings section - h5 group
            case 'awf_h5_settings_reset_control':

              remove_theme_mod('awf_h5_font_family');
              remove_theme_mod('awf_h5_font_size');
              remove_theme_mod('awf_h5_line_height');
              remove_theme_mod('awf_h5_text_decoration');

            break;

            // headings section - h6 group
            case 'awf_h6_settings_reset_control':

              remove_theme_mod('awf_h6_font_family');
              remove_theme_mod('awf_h6_font_size');
              remove_theme_mod('awf_h6_line_height');
              remove_theme_mod('awf_h6_text_decoration');

            break;

            // reset all settings
            case 'awf_all_settings_reset_control':

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
              // custom controls section
              if( $this->get_custom_controls_ids() ) {

                foreach ( $this->get_custom_controls_ids() as $id ) {

                      remove_theme_mod('awf_'. $id .'_font_family');
                      remove_theme_mod('awf_'. $id .'_font_size');
                      remove_theme_mod('awf_'. $id .'_line_height');
                      remove_theme_mod('awf_'. $id .'_text_decoration');

                }

              }

            break;

            // custom controls section
            default:

            if( $this->get_custom_controls_ids() ) {

                foreach ( $this->get_custom_controls_ids() as $id ) {

                  if( $reset_section == 'awf_'. $id .'_settings_reset_control' ) {
                      remove_theme_mod('awf_'. $id .'_font_family');
                      remove_theme_mod('awf_'. $id .'_font_size');
                      remove_theme_mod('awf_'. $id .'_line_height');
                      remove_theme_mod('awf_'. $id .'_text_decoration');
                  }

                }

            }

            break;


        } // end switch section settings

    }

    /**
	 * Output css styles in header to activate customizer settings.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function header_output() {

        echo '<!-- Start '. $this->plugin_name. ' v'. $this->version .' styles -->';
        ?>
        <style id='<?php echo $this->plugin_name; ?>' type='text/css'>
           <?php
           // body styles
           $body_font_family = get_theme_mod('awf_body_font_family');
           $body_font_size = get_theme_mod('awf_body_font_size');
           $body_line_height = get_theme_mod('awf_body_line_height');

           if( empty( $body_font_family ) && empty( $body_font_size ) && empty( $body_line_height ) ) {

           } else {
           ?>
           body, header, footer, .content, .sidebar, p, h1, h2, h3, h4, h5, h6 {
           <?php if ( $body_font_family != '' ) { ?>
           font-family: '<?php echo $body_font_family; ?>' <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $body_font_size != '' ) { ?>
           font-size: <?php echo $body_font_size; ?>px <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $body_line_height != '' ) { ?>
           line-height: <?php echo $body_line_height; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           }
           <?php } ?>
           <?php
           // paragraphs styles
           $paragraphs_font_family = get_theme_mod('awf_paragraphs_font_family');
           $paragraphs_font_size = get_theme_mod('awf_paragraphs_font_size');
           $paragraphs_line_height = get_theme_mod('awf_paragraphs_line_height');
           $paragraphs_text_decoration = get_theme_mod('awf_paragraphs_text_decoration');

           if( empty( $paragraphs_font_family ) && empty( $paragraphs_font_size ) && empty( $paragraphs_line_height ) && empty( $paragraphs_text_decoration ) ) {

           } else {
           ?>
           p {
           <?php if ( $paragraphs_font_family != '' ) { ?>
           font-family: '<?php echo $paragraphs_font_family; ?>' <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $paragraphs_font_size != '' ) { ?>
           font-size: <?php echo $paragraphs_font_size; ?>px <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $paragraphs_line_height != '' ) { ?>
           line-height: <?php echo $paragraphs_line_height; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $paragraphs_text_decoration != '' ) { ?>
           text-decoration: <?php echo $paragraphs_text_decoration; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           }
           <?php } ?>
           <?php
           // h1 styles
           $h1_font_family = get_theme_mod('awf_h1_font_family');
           $h1_font_size = get_theme_mod('awf_h1_font_size');
           $h1_line_height = get_theme_mod('awf_h1_line_height');
           $h1_text_decoration = get_theme_mod('awf_h1_text_decoration');

           if( empty( $h1_font_family ) && empty( $h1_font_size ) && empty( $h1_line_height ) && empty( $h1_text_decoration ) ) {

           } else {
           ?>
           h1 {
           <?php if ( $h1_font_family != '' ) { ?>
           font-family: '<?php echo $h1_font_family; ?>' <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h1_font_size != '' ) { ?>
           font-size: <?php echo $h1_font_size; ?>px <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h1_line_height != '' ) { ?>
           line-height: <?php echo $h1_line_height; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h1_text_decoration != '' ) { ?>
           text-decoration: <?php echo $h1_text_decoration; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           }
           <?php } ?>
           <?php
           // h2 styles
           $h2_font_family = get_theme_mod('awf_h2_font_family');
           $h2_font_size = get_theme_mod('awf_h2_font_size');
           $h2_line_height = get_theme_mod('awf_h2_line_height');
           $h2_text_decoration = get_theme_mod('awf_h2_text_decoration');

           if( empty( $h2_font_family ) && empty( $h2_font_size ) && empty( $h2_line_height ) && empty( $h2_text_decoration ) ) {

           } else {
           ?>
           h2 {
           <?php if ( $h2_font_family != '' ) { ?>
           font-family: '<?php echo $h2_font_family; ?>' <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h2_font_size != '' ) { ?>
           font-size: <?php echo $h2_font_size; ?>px <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h2_line_height != '' ) { ?>
           line-height: <?php echo $h2_line_height; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h2_text_decoration != '' ) { ?>
           text-decoration: <?php echo $h2_text_decoration; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           }
           <?php } ?>
           <?php
           // h3 styles
           $h3_font_family = get_theme_mod('awf_h3_font_family');
           $h3_font_size = get_theme_mod('awf_h3_font_size');
           $h3_line_height = get_theme_mod('awf_h3_line_height');
           $h3_text_decoration = get_theme_mod('awf_h3_text_decoration');

           if( empty( $h3_font_family ) && empty( $h3_font_size ) && empty( $h3_line_height ) && empty( $h3_text_decoration ) ) {

           } else {
           ?>
           h3 {
           <?php if ( $h3_font_family != '' ) { ?>
           font-family: '<?php echo $h3_font_family; ?>' <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h3_font_size != '' ) { ?>
           font-size: <?php echo $h3_font_size; ?>px <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h3_line_height != '' ) { ?>
           line-height: <?php echo $h3_line_height; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h3_text_decoration != '' ) { ?>
           text-decoration: <?php echo $h3_text_decoration; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           }
           <?php } ?>
           <?php
           // h4 styles
           $h4_font_family = get_theme_mod('awf_h4_font_family');
           $h4_font_size = get_theme_mod('awf_h4_font_size');
           $h4_line_height = get_theme_mod('awf_h4_line_height');
           $h4_text_decoration = get_theme_mod('awf_h4_text_decoration');

           if( empty( $h4_font_family ) && empty( $h4_font_size ) && empty( $h4_line_height ) && empty( $h4_text_decoration ) ) {

           } else {
           ?>
           h4 {
           <?php if ( $h4_font_family != '' ) { ?>
           font-family: '<?php echo $h4_font_family; ?>' <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h4_font_size != '' ) { ?>
           font-size: <?php echo $h4_font_size; ?>px <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h4_line_height != '' ) { ?>
           line-height: <?php echo $h4_line_height; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h4_text_decoration != '' ) { ?>
           text-decoration: <?php echo $h4_text_decoration; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           }
           <?php } ?>
           <?php
           // h5 styles
           $h5_font_family = get_theme_mod('awf_h5_font_family');
           $h5_font_size = get_theme_mod('awf_h5_font_size');
           $h5_line_height = get_theme_mod('awf_h5_line_height');
           $h5_text_decoration = get_theme_mod('awf_h5_text_decoration');

           if( empty( $h5_font_family ) && empty( $h5_font_size ) && empty( $h5_line_height ) && empty( $h5_text_decoration ) ) {

           } else {
           ?>
           h5 {
           <?php if ( $h5_font_family != '' ) { ?>
           font-family: '<?php echo $h5_font_family; ?>' <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h5_font_size != '' ) { ?>
           font-size: <?php echo $h5_font_size; ?>px <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h5_line_height != '' ) { ?>
           line-height: <?php echo $h5_line_height; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h5_text_decoration != '' ) { ?>
           text-decoration: <?php echo $h5_text_decoration; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           }
           <?php } ?>
           <?php
           // h6 styles
           $h6_font_family = get_theme_mod('awf_h6_font_family');
           $h6_font_size = get_theme_mod('awf_h6_font_size');
           $h6_line_height = get_theme_mod('awf_h6_line_height');
           $h6_text_decoration = get_theme_mod('awf_h6_text_decoration');

           if( empty( $h6_font_family ) && empty( $h6_font_size ) && empty( $h6_line_height ) && empty( $h6_text_decoration ) ) {

           } else {
           ?>
           h6 {
           <?php if ( $h6_font_family != '' ) { ?>
           font-family: '<?php echo $h6_font_family; ?>' <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h6_font_size != '' ) { ?>
           font-size: <?php echo $h6_font_size; ?>px <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h6_line_height != '' ) { ?>
           line-height: <?php echo $h6_line_height; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           <?php if ( $h6_text_decoration != '' ) { ?>
           text-decoration: <?php echo $h6_text_decoration; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
           <?php } ?>
           }
           <?php } ?>
           <?php

            // get custom controls styles
            if( $this->get_custom_controls_ids() ) {

                foreach ( $this->get_custom_controls_ids() as $id ) {

                    // get css selectors
                    $css_selectors = get_post_meta( $id, '_awf_css_selectors', true );

                    $id_font_family = get_theme_mod('awf_'. $id .'_font_family');
                    $id_font_size = get_theme_mod('awf_'. $id .'_font_size');
                    $id_line_height = get_theme_mod('awf_'. $id .'_line_height');
                    $id_text_decoration = get_theme_mod('awf_'. $id .'_text_decoration');

                    if( empty( $id_font_family ) && empty( $id_font_size ) && empty( $id_line_height ) && empty( $id_text_decoration ) ) {

                    } else {

                        if( !empty( $css_selectors ) ) {
                ?>

                            <?php echo $css_selectors; ?> {
                                <?php if ( $id_font_family != '' ) { ?>
                                font-family: '<?php echo $id_font_family; ?>' <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
                                <?php } ?>
                                <?php if ( $id_font_size != '' ) { ?>
                                font-size: <?php echo $id_font_size; ?>px <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
                                <?php } ?>
                                <?php if ( $id_line_height != '' ) { ?>
                                line-height: <?php echo $id_line_height; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
                                <?php } ?>
                                <?php if ( $id_text_decoration != '' ) { ?>
                                text-decoration: <?php echo $id_text_decoration; ?> <?php if ( !is_customize_preview() ) { ?>!important<?php } ?>;
                                <?php } ?>
                            }

                <?php
                        } // end check selectors

                    } // end check fields

                } // end controls foreach

            } // end check controls

           ?>
	    </style>
        <?php
        echo '<!-- /End '. $this->plugin_name. ' styles -->';

    }



}

endif; // End Check Class Exists
