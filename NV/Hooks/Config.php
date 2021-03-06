<?php
/** The \NV\Theme\Hooks\Config class */

namespace NV\Theme\Hooks;

use NV\Theme\NV;

/**
 * Contains functions for reconfiguring the admin back-end. Generally, method names should match the hook name for
 * easy identification. In cases where a generic hook is utilized, a more logical method name should be used.
 */
class Config {

	/**
	 * Sets up basic theme features.
	 *
	 * Used by action hook: 'after_setup_theme'
	 *
	 * @uses self::languages();
	 */
	public static function after_setup_theme() {
		
		// Load available language pack
		load_theme_textdomain( 'nvLangScope', NV::i()->get_path( 'langs' ) );

		// Let WordPress generate the <title> tag for you
		add_theme_support( 'title-tag' );

		// Let WordPress automatically generate RSS feed urls
		add_theme_support( 'automatic-feed-links' );

		// Enable HTML5 support
		add_theme_support( 'html5', 
			array( 
				'comment-list', 
				'comment-form', 
				'search-form', 
				'gallery', 
				'caption',
			) 
		);

		// Add custom header support
		add_theme_support(
			'custom-header', 
			array(
				'width'         		 => 1200,
				'height'                 => 250,
				'flex-height'            => true,
				'flex-width'             => true,
				'default-image'          => NV::i()->get_url( 'img', 'header.gif' ),
				'random-default'         => false,
				'header-text'            => true,
				'default-text-color'     => '',
				'uploads'                => true,
				'wp-head-callback'       => null,
				'admin-head-callback'    => null,
				'admin-preview-callback' => null,
			)
		);

		// Customize your background
		add_theme_support(
			'custom-background', 
			array(
				'default-image'          => '',
				'default-repeat'         => 'repeat',
				'default-position-x'     => 'left',
				'default-attachment'     => 'scroll',
				'default-color'          => '',
				'wp-head-callback'       => '_custom_background_cb',
				'admin-head-callback'    => '',
				'admin-preview-callback' => '',
			)
		);

		// Enable support for blog post thumbnails
		add_theme_support( 'post-thumbnails' );

		// Enable support for post formats
		add_theme_support(
			'post-formats', 
			array(
				'aside',
				'audio',
				'chat',
				'gallery',
				'image',
				'link',
				'quote',
				'status',
				'video',
			)
		);

		// Enable support for WooCommerce
		add_theme_support( 'woocommerce' );

		// Register your default navigation
		register_nav_menu( 'primary', __( 'Primary Menu', 'nvLangScope' ) );
		register_nav_menu( 'footer', __( 'Footer Menu', 'nvLangScope' ) );
		
		/*
		 * Set up any default values needed for theme options. If a default value is needed, it can be provided as a 
		 * second parameter. This will NOT overwrite any existing options with these names.
		 */
//		add_option( 'nouveau_example_checkbox' );
//		add_option( 'nouveau_example_radio' );
//		add_option( 'nouveau_example_text', 'This is example default text.' );
//		add_option( 'nouveau_example_select' );

	}


	/**
	 * Enqueues styles and scripts.
	 *
	 * This is current set up for the majority of use-cases, and you can uncomment additional lines if you want to
	 *
	 * Used by action hook: 'wp_enqueue_scripts'
	 */
	public static function enqueue_assets() {

		/******************
		 * STYLES / CSS
		 ******************/

		// Base stylesheet (compiled Foundation SASS)
		wp_enqueue_style( 'app', NV::i()->get_url( 'css', 'app.css' ) );

		// WordPress's required styles.css
		wp_enqueue_style( 'styles', get_bloginfo( 'stylesheet_url' ), array( 'app' ) );

		/******************
		 * SCRIPTS / JS
		 ******************/

		// Toggle .min on js suffixes if debug
		$js_min = ( WP_DEBUG ) ? '' : '.min';

		// Remove WordPress's jQuery and use our own
		wp_deregister_script( 'jquery' );
		wp_enqueue_script( 'jquery', NV::i()->get_url( 'bower', 'jquery/dist/jquery' . $js_min . '.js' ), array(), false, true );

		// Foundation what-input dependency
		wp_enqueue_script( 'what-input', NV::i()->get_url( 'bower', 'what-input/what-input' . $js_min . '.js' ), array(), false, true );

		// Load the complete version of Foundation
		wp_enqueue_script( 'foundation', NV::i()->get_url( 'bower', 'foundation-sites/dist/foundation' . $js_min . '.js' ), array( 'jquery', 'what-input' ), false, true );

		// Load any custom javascript (remember to update dependencies if you changed the above)...
		wp_enqueue_script( 'nv-theme', NV::i()->get_url( 'js', 'app' . $js_min . '.js' ), array( 'foundation' ), false, true );

	}


	/**
	 * Enqueues styles and scripts for the admin section
	 *
	 * Used by action hook: 'admin_enqueue_scripts'
	 */
	public static function enqueue_admin_assets() {

		// Toggle .min on js suffixes if debug
		$js_min = ( WP_DEBUG ) ? '' : '.min';

		// Base admin styles
		wp_enqueue_style( 'nv-admin', NV::i()->get_url( 'css', 'admin.css' ) );

		// Base admin scripts
		wp_enqueue_script( 'nv-admin', NV::i()->get_url( 'js', 'admin' . $js_min . '.js' ), array( 'jquery' ), false, false );
	}


	/**
	 * Allows further customizations of the body_class() function.
	 *
	 * @param $classes
	 * @param $args
	 */
	public static function body_class( $classes, $args = '' ) {
		//Do stuff!
		return $classes;
	}


	/**
	 * Registers any sidebars that need to be used with the theme.
	 *
	 * Used by action hook: 'widget_init'
	 */
	public static function sidebars() {

		register_sidebar(
			array(
				'name'          => __( 'Blog Sidebar', 'nvLangScope' ),
				'id'            => 'sidebar-1',
				'description'   => __( 'Drag widgets for Blog sidebar here. These widgets will only appear on the blog portion of your site.', 'nvLangScope' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => "</aside>",
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Site Sidebar', 'nvLangScope' ),
				'id'            => 'sidebar-2',
				'description'   => __( 'Drag widgets for the Site sidebar here. These widgets will only appear on non-blog pages.', 'nvLangScope' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => "</aside>",
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
		register_sidebar(
			array(
				'name'          => __( 'Footer', 'nvLangScope' ),
				'id'            => 'sidebar-3',
				'description'   => __( 'Drag footer widgets here.', 'nvLangScope' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => "</aside>",
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}


	/**
	 * Renames WordPress' default .sticky class to .status-sticky to prevent conflicts with Foundation.
	 *
	 * By default, Foundation uses the .sticky class to "stick" content to the top of the screen when scrolling below
	 * the top of the window.
	 * 
	 * @used-by add_filter( 'post_class', $func )
	 * 
	 * @param $classes
	 *
	 * @return array
	 */
	public static function sticky_post_class( $classes ) {
		if( in_array( 'sticky', $classes ) ) {
			$classes = array_diff( $classes, array( 'sticky' ) );
			$classes[] = 'status-sticky';
		}
		return $classes;
	}


}