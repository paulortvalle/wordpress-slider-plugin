<?php
/*
 * YVR Slider. Slideshow plugin for WordPress.
 *
 * Plugin Name: YVRSlider
 * Plugin URI:  http://www.yvrca.com
 * Description: Easy to use slideshow plugin. Create SEO optimised responsive slideshows with Bootstrap and UIKit.
 * Version:     2.1.1
 * Author:      Paulo RT Valle
 * Author URI:  http://www.yvrca.com
 * License:     GPL-2.0+
 * Copyright:   2018- Paulo RT Valle
 *
 * Text Domain: yvr-slider
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) die('No direct access.');


if (!class_exists('YVRSliderPlugin')) :

class YVRSliderPlugin {

	/**
     * YVR slider version number
     *
     * @var string
     */
    public $version = '2.1.1';

    /**
     * Specific SLider
     *
     * @var MetaSlider
     */
    public $slider = null;
    
	/**
	 * Instance object
	 *
	 * @var object
	 * @see get_instance()
	 */
	protected static $instance = NULL;

    /**
     * Constructor
     */
    public function __construct() {}

	/**
	 * Used to access the instance
     *
     * @return object - class instance
	 */
	public static function get_instance() {
		if (NULL === self::$instance) self::$instance = new self();
		return self::$instance;
	}

	/**
     * Setup Function
     * Used to config the setup options
     */
    public function setup() { 
    	$this->define_constants();
    	$this->includes();
    	$this->setup_actions();


    	$this->setup_shortcode();
    	$this->register_slider_post_types();
    }

    /**
     * Define Constancts Function
     * Define YVRSlider constants
     */
    private function define_constants() {
    	if (!defined('YVRSLIDER_VERSION')) { 
	        define('YVRSLIDER_VERSION',    		$this->version);
	        define('YVRSLIDER_BASE_URL',   		trailingslashit(plugins_url('yvr-slider')));
	        define('YVRSLIDER_ASSETS_URL', 		trailingslashit(METASLIDER_BASE_URL . 'assets'));
	        define('YVRSLIDER_ADMIN_URL',  		trailingslashit(METASLIDER_BASE_URL . 'admin'));
	        define('YVRSLIDER_PATH',       		plugin_dir_path(__FILE__));
		}
    }

    /**
     * All YVRSlider classes
     */
    private function get_plugin_classes() {
        return array(
            'yvrslider_admin_pages' => YVRSLIDER_PATH . 'admin/Pages.php',
        );
    }

    /**
     * Load required classes
     */
    private function includes() {
        if ( function_exists( "__autoload" ) ) {
            spl_autoload_register( "__autoload" );
        }

        spl_autoload_register( array( $this, 'autoload' ) );
    }

    /**
     * Autoload YVRSlider classes
     *
     * @param  string $class Class name
     */
    public function autoload( $class ) {

        $classes = $this->get_plugin_classes();

        $class_name = strtolower( $class );

        if ( isset( $classes[$class_name] ) && is_readable( $classes[$class_name] ) ) {
            require_once( $classes[$class_name] );
        }

    }


    /**
     * Setup Actions Function
     * Hook YVRSlider into WordPress
     */
    private function setup_actions() {

        add_action('admin_menu', 	array($this, 'register_admin_pages'));
        add_action('init', 			array($this, 'register_slider_post_types'));
        add_action('init', 			array($this, 'register_taxonomy'));
        // more functions
    }

    /**
    * Add the menu pages
    */
    public function register_admin_pages() {

    	// Add a menu item | Docs: https://developer.wordpress.org/reference/functions/add_menu_page/
		add_menu_page(
		    'YVR Slider', 				// $page_title: (string) (Required) The text to be displayed in the title tags of the page when the menu is selected.
		    'Slides', 					// $menu_title: (string) (Required) The text to be used for the menu.
		    'manage_options', 			// $capability: (string) (Required) The capability required for this menu to be displayed to the user.
		    'yvrslider', 				// $menu_slug: (string) (Required) The slug name to refer to this menu by. Should be unique for this menu page and only include lowercase alphanumeric, dashes, and underscores characters to be compatible with sanitize_key().
		    array(
		    	$this,
		    	'load_admin_page'
		    ), 							// $function: (callable) (Optional) The function to be called to output the content for this page.
		    'dashicons-slides',			// $icon_url: (string) (Optional) The URL to the icon to be used for this menu. 
		    	// Find icons on https://developer.wordpress.org/resource/dashicons/
		  	15 							// $position: (int) (Optional) The position in the menu order this one should appear.
		);

		// Add a sub menu item | Docs: https://developer.wordpress.org/reference/functions/add_submenu_page/
		add_submenu_page(
			'yvrslider', 					// $parent_slug: (string) (Required) The slug name for the parent menu (or the file name of a standard WordPress admin page).
			'PRTV Slider Config',			// $page_title: (string) (Required) The text to be displayed in the title tags of the page when the menu is selected.
			'Configurations',				// $menu_title: (string) (Required) The text to be used for the menu.
			'manage_options',				// $capability: (string) (Required) The capability required for this menu to be displayed to the user.
			'yvrslider_config',				// $menu_slug: (string) (Required) The slug name to refer to this menu by. Should be unique for this menu and only include lowercase alphanumeric, dashes, and underscores characters to be compatible with sanitize_key().
			array(
				$this,
				'load_config_page'
			)								// $function: (callable) (Optional) The function to be called to output the content for this page.								
		);

    }

     /**
    * Load the admin page
    */
    public function load_admin_page() {

    	if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

    	require_once YVRSLIDER_PATH . 'admin/admin-view.php';

    }


    /**
     * Setup Shortcode Function
     * Register the [metaslider] shortcode.
     */
    private function setup_shortcode() {

        add_shortcode( 'yvrslider', array( $this, 'register_shortcode' ) );
        add_shortcode( 'yvr-slider', array( $this, 'register_shortcode' ) ); // backwards compatibility

    }

    /**
     * Shortcode used to display slideshow
     *
     * @param  string $atts attributes for short code
     * @return string HTML output of the shortcode
     */
    public function register_shortcode( $atts ) {

        extract( shortcode_atts( array(
            'id' => false
        ), $atts, 'yvrslider' ) );


        if (!$id)
            return false;

        // use the ID
        $slider = get_post( $id );

        // check the slider is published and the ID is correct
        if ( ! $slider || $slider->post_status != 'publish' || $slider->post_type != 'yvr-slider' )
            return "<!-- YVRSlider {$atts['id']} not found -->";

        // do it
        $this->set_slider( $id, $atts );
        $this->slider->enqueue_scripts();

        return $this->slider->render_public_slides();

    }

    /**
     * Register YVR Slider post type
     */
    public function register_slider_post_types() {

        $show_ui = false;

        $capability = apply_filters( 'yvrslider_capability', 'edit_others_posts' );

        if ( is_admin() && current_user_can( $capability ) && ( isset($_GET['show_ui']) || defined("YVRSLIDER_DEBUG") && YVRSLIDER_DEBUG ) ) {
            $show_ui = true;
        }

        register_post_type( 'yvr-slider', array(
                'query_var' => false,
                'rewrite' => false,
                'public' => false,
                'exclude_from_search' => true,
                'publicly_queryable' => false,
                'show_in_nav_menus' => false,
                'show_ui' => $show_ui,
                'labels' => array(
                    'name' => 'YVRSlider'
                )
            )
        );

        register_post_type( 'yvr-slide', array(
                'query_var' => false,
                'rewrite' => false,
                'public' => false,
                'exclude_from_search' => true,
                'publicly_queryable' => false,
                'show_in_nav_menus' => false,
                'show_ui' => $show_ui,
                'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt'),
                'labels' => array(
                    'name' => 'YVR Slides'
                )
            )
        );

    }

    /**
     * Register taxonomy to store slider => slides relationship
     */
    public function register_taxonomy() {

        $show_ui = false;

        $capability = apply_filters( 'yvrslider_capability', 'edit_others_posts' );

        if (is_admin() && current_user_can( $capability ) && ( isset($_GET['show_ui']) || defined("YVRSLIDER_DEBUG") && YVRSLIDER_DEBUG ) ) {
            $show_ui = true;
        }

        register_taxonomy( 'yvr-slider', array('attachment', 'yvr-slide'), array(
                'hierarchical' => true,
                'public' => false,
                'query_var' => false,
                'rewrite' => false,
                'show_ui' => $show_ui,
                'label' => "Slider"
            )
        );

    }





} // end class YVRSliderPlugin

endif;


add_action('plugins_loaded', array(YVRSliderPlugin::get_instance(), 'setup'), 10);