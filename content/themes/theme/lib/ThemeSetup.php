<?php
/**
 * Created by PhpStorm.
 * User: lgobatto
 * Date: 25/01/17
 * Time: 16:29
 */

namespace LGobatto;


class ThemeSetup {

	/**
	 * ThemeSetup constructor.
	 */
	public function __construct() {
		//add_action( 'after_setup_theme', [ $this, 'setup_wordpress_features' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'assets' ], 100 );
		add_filter( 'get_the_archive_title', [ $this, 'my_theme_archive_title' ] );
		if ( class_exists( 'acf' ) ) {
			//add_action( 'after_setup_theme', [ $this, 'setup_theme_settings' ] );
			add_action( 'after_setup_theme', [ $this, 'widgets_init' ] );
			add_filter( 'acf/settings/save_json', [ $this, 'lgobatto_acf_json_save_point' ] );
			add_filter( 'acf/settings/load_json', [ $this, 'lgobatto_acf_json_load_point' ] );
		}
	}

	/**
	 * Setup Theme Settings Page
	 * @return array|bool|string
	 */
	private function add_options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			return acf_add_options_page( [
				'page_title' => 'Theme Settings',
				'menu_title' => 'Theme Setup',
				'menu_slug'  => 'theme-general-settings',
				'capability' => 'edit_posts',
				'redirect'   => false
			] );
		}

		return false;
	}


	/**
	 * Return Theme Settings Page
	 * @return array|bool
	 */
	static function options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			$options = acf_get_options_page( 'theme-general-settings' );
			$options = ! $options ? self::add_options_page() : $options;

			return $options;
		}
	}

	/**
	 * Create Default Page Settings for theme.
	 */
	public function setup_theme_settings() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			$options = self::options_page();

			$setup_page = acf_add_options_sub_page( [
				'page_title'  => 'Brand Setup',
				'menu_title'  => 'Brand Setup',
				'parent_slug' => $options['menu_slug']
			] );
		}

		return;
	}

	/**
	 * Setup Wordpress features.
	 */
	public function setup_wordpress_features() {
		add_theme_support( 'soil-clean-up' );
		add_theme_support( 'soil-nice-search' );
		add_theme_support( 'soil-jquery-cdn' );
		add_theme_support( 'soil-relative-urls' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'post-formats', [ 'aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio' ] );
		add_theme_support( 'html5', [ 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ] );
		add_editor_style( asset_path( 'styles/main.css' ) );
		add_theme_support( 'woocommerce' );
		add_filter( 'show_admin_bar', '__return_false' );
	}

	/**
	 * Initialize Widgets
	 */
	public function widgets_init() {
		if ( class_exists( 'acf' ) ) {
			$widgets = get_field( 'theme_sidebars', 'options' );
			if ( $widgets ) {
				foreach ( $widgets as $widget ) {
					register_sidebar( [
						'name'          => $widget['title'],
						'id'            => sanitize_title( $widget['title'] ),
						'before_widget' => '<section class="widget %1$s %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h3>',
						'after_title'   => '</h3>'
					] );
				}
			}
		} else {
			register_sidebar( [
				'name'          => __( 'Default Sidebar', 'lgobatto' ),
				'id'            => 'default-sidebar',
				'before_widget' => '<section class="widget %1$s %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3>',
				'after_title'   => '</h3>'
			] );
		}
	}

	/**
	 * Enqueue theme styles and scripts.
	 */
	public function assets() {
		wp_enqueue_style( 'lgobatto/css', asset_path( 'styles/main.css' ), false, null );
		if ( is_single() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		//wp_dequeue_script('jquery');
		//wp_enqueue_script( 'jquery', asset_path( 'scripts/jquery.js' ), [ ], null, true );
		//var_dump(asset_path( 'scripts/main.js' ));
		wp_enqueue_script( 'lgobatto/js', asset_path( 'scripts/main.js' ), [ 'jquery' ], null, true );
	}

	/**
	 * Change ACF json save point
	 *
	 * @param $path
	 *
	 * @return string
	 */
	function lgobatto_acf_json_save_point( $path ) {
		$path = get_template_directory() . '/custom-fields';

		return $path;
	}

	/**
	 * Change ACF json load point
	 *
	 * @param $paths
	 *
	 * @return array
	 */
	function lgobatto_acf_json_load_point( $paths ) {
		unset( $paths[0] );
		$paths[] = get_stylesheet_directory() . '/custom-fields';

		return $paths;
	}

	function my_theme_archive_title( $title ) {
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		}

		return $title;
	}

}

new ThemeSetup();