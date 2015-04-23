<?php
/**
 * Register the custom post type [ awf_font_control ]
 *
 * @since      1.0
 *
 * @package    Arabic_Webfonts
 * @subpackage Arabic_Webfonts/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'AWF_Post_Type') ) :

class AWF_Post_Type {

    /**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0
	 * @access   protected
	 */
    protected $plugin_name;
    
    /**
	 * Define attributes and actions for custom post type.
	 *
	 * @since    1.0
	 */
    public function __construct( $plugin_name ) {
       
        $this->plugin_name = $plugin_name;
        
        add_action( 'init', array( $this, 'register_post_type' ) );
        add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );
        add_filter( 'manage_edit-awf_font_control_columns', array( $this, 'columns_filter' ), 10, 2 );
        add_filter( 'post_row_actions', array( $this, 'remove_action' ), 10, 2 );
        add_filter( 'months_dropdown_results', array( $this, 'remove_month_filters' ), 10, 2 );
        add_filter( 'bulk_actions-edit-awf_font_control', array( $this, 'remove_bulk_actions' ), 10, 2 );
        add_action( 'admin_head-post.php', array( $this, 'hide_publishing_actions' ) );
        add_action( 'admin_head-post-new.php', array( $this, 'hide_publishing_actions' ) );
        add_action( 'admin_head-edit.php', array( $this, 'hide_publishing_actions' ) );
        add_action( 'admin_init', array( $this, 'disable_autosave' ) );
        add_filter( 'bulk_post_updated_messages', array( $this, 'bulk_post_updated_messages_filter' ), 10, 2 );
        add_action( 'add_meta_boxes', array( $this, 'remove_slugdiv_metabox' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save_meta_box' ) );
        
    }
    
    /**
	 * Register custom post type for controls.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function register_post_type() {
 
        $labels = array( 
            'name'               => __( 'Arabic Webfonts Controls', $this->plugin_name ),
            'singular_name'      => __( 'Arabic Webfonts Control', $this->plugin_name ),
            'add_new'            => __( 'Add New Control', $this->plugin_name ),
            'add_new_item'       => __( 'Add New Control', $this->plugin_name ),
            'edit_item'          => __( 'Edit Control', $this->plugin_name ),
            'new_item'           => __( 'New Control', $this->plugin_name ),
            'all_items'          => __( 'All Controls', $this->plugin_name ),
            'not_found'          => __( 'No Controls found', $this->plugin_name ),
            'menu_name'          => __( 'Arabic Webfonts', $this->plugin_name ),
        );

        $args = array( 
            'labels'              => $labels,
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 80,
            'menu_icon'           => 'dashicons-menu',
            'show_in_nav_menus'   => false,
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'query_var'           => false,
            'rewrite'             => false,
            'supports'            => array( 'title' ),
        );

        register_post_type( 'awf_font_control', $args );
        
    }
    
    /**
	 * Customizing the messages for control post type.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function updated_messages( $messages ) {

        $post = get_post();
        $post_type = get_post_type( $post );

        $messages['awf_font_control'] = array(
            0  => '',
            1  => __( 'Control updated. Please visit the Customizer to manage this control.', $this->plugin_name ),
            2  => __( 'Control updated.', $this->plugin_name ),
            3  => __( 'Control deleted.', $this->plugin_name ),
            4  => __( 'Control updated. Please visit the Customizer to manage this control.', $this->plugin_name ),
            5  => isset( $_GET['revision'] ) ? sprintf( 'Control restored to revision from %s', 
                  wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => __( 'Control published. Please visit the Customizer to manage this control.', $this->plugin_name ),
            7  => __( 'Control saved. Please visit the Customizer to manage this control.', $this->plugin_name ),
            8  => __( 'Control submitted. Please visit the Customizer to manage this control.', $this->plugin_name ),
            9  => sprintf( 'Control scheduled for: <strong>%1$s</strong>.', 
                  date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) )
            ),
            10 => 'Control draft updated.'
        );

        return $messages;

    }
    
    /**
	 * Remove date & cb columns.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function columns_filter( $columns ) {

       unset($columns['date']);
       unset($columns['cb']);
       return $columns;

    }
    
    /**
	 * Remove actions and add new delete link.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function remove_action( $actions ) {

       $post = get_post();
       $post_type = get_post_type( $post );

       if($post_type == 'awf_font_control') { 

        unset( $actions['inline hide-if-no-js'] );
        unset( $actions['trash'] );


        $actions['trash'] = "<a class='submitdelete' title='" . esc_attr(__('Delete this control permanently', $this->plugin_name)) . "' href='" . get_delete_post_link($post->ID, '', true) . "'>". __( 'Delete Permanently', $this->plugin_name ). "</a>";

       }

       return $actions;

    }
    
    /**
	 * Remove month filter.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function remove_month_filters( $months, $post_type ) {
        
        return in_array( $post_type, array( 'awf_font_control' ) ) ? array() : $months;
        
    }
    
    /**
	 * Remove bulk actions.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function remove_bulk_actions( $actions ) {
        
        unset( $actions[ 'trash' ] );
        unset( $actions[ 'edit' ] );
        return $actions;
        
    }
    
    /**
	 * Hide publishing actions.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function hide_publishing_actions() {

        global $post_type;

        if($post_type == 'awf_font_control'){
            echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions,
                    #post-query-submit,
                    .subsubsub,
                    .tablenav .view-switch,
                    #delete-action,
                    p.search-box {
                        display:none;
                    }
                </style>
            ';
        }
    }
    
    /**
	 * Disable AutoSave for post type.
	 *
     * will do that because after uninstalling the plugin
     * all autosave drafts still in database and should
     * cleanup all plugin data after the uninstalling
     *
	 * @since    1.0
	 * @access   public
	 */
    public function disable_autosave() {

        global $post_type;

        if($post_type == 'awf_font_control'){
            
            wp_deregister_script( 'autosave' );
            
        }
    }
    
    /**
	 * Customizing the bulk messages for control post type.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function bulk_post_updated_messages_filter( $bulk_messages, $bulk_counts ) {

        $bulk_messages['awf_font_control'] = array(
            'deleted'   => _n( 'Control permanently deleted.', '%s Controls permanently deleted.', $bulk_counts['deleted'], $this->plugin_name ),
        );

        return $bulk_messages;

    }
    
    /**
	 * Remove slugdiv metabox.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function remove_slugdiv_metabox() {
        
        remove_meta_box( 'slugdiv', 'awf_font_control', 'normal' );
        
    }
    
    /**
	 * Add the metabox [ CSS Selectors ].
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function add_meta_box() {
        
        add_meta_box(
            'awf_css_selectors_meta_box',
            __('CSS Selectors', $this->plugin_name),
            array( $this, 'render_meta_box_content' ),
            'awf_font_control',
            'normal',
            'high'
        ); 
        
    }
    
    /**
	 * Save the metabox when the post is saved.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function save_meta_box( $post_id ) {

        if ( ! isset( $_POST['awf_inner_css_selectors_nonce'] ) )
            return $post_id;

        $nonce = $_POST['awf_inner_css_selectors_nonce'];

        if ( ! wp_verify_nonce( $nonce, 'awf_inner_css_selectors' ) )
            return $post_id;

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            return $post_id;

        if ( ! current_user_can( 'edit_post', $post_id ) )
            return $post_id;

        $mydata = sanitize_text_field( $_POST['awf_css_selectors'] );

        update_post_meta( $post_id, '_awf_css_selectors', $mydata );
    }
    
    /**
	 * Render the metabox content.
	 *
	 * @since    1.0
	 * @access   public
	 */
    public function render_meta_box_content( $post ) {

        wp_nonce_field( 'awf_inner_css_selectors', 'awf_inner_css_selectors_nonce' );

        $value = get_post_meta( $post->ID, '_awf_css_selectors', true );

        echo '
        <table class="form-table">
        <tr><td>
        <span>'. _e( 'Type each CSS selector that you would like this font control to manage in the box below. <br /> Example : .some-class, #some-id', $this->plugin_name ) .'</span>
        <textarea style="width:100%;direction:ltr;" name="awf_css_selectors" id="awf_css_selectors" cols="60" rows="6">'. esc_textarea ( $value ) .'</textarea>
        </td></tr>
        </table>';
        
     }
    
}

endif; // End Check Class Exists