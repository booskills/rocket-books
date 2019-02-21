<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://booskills.com/rao
 * @since      1.0.0
 *
 * @package    Rocket_Books
 * @subpackage Rocket_Books/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Rocket_Books
 * @subpackage Rocket_Books/includes
 * @author     Rao <rao@booskills.com>
 */
class Rocket_Books {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Rocket_Books_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'ROCKET_BOOKS_VERSION' ) ) {
			$this->version = ROCKET_BOOKS_VERSION;
		} else {
			$this->version = '1.0.0';
		}

		if ( defined( 'ROCKET_BOOKS_NAME' ) ) {
			$this->plugin_name = ROCKET_BOOKS_NAME;
		} else {
			$this->plugin_name = 'rocket-books';
		}


		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		$this->define_post_type_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Rocket_Books_Loader. Orchestrates the hooks of the plugin.
	 * - Rocket_Books_i18n. Defines internationalization functionality.
	 * - Rocket_Books_Admin. Defines all hooks for the admin area.
	 * - Rocket_Books_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rocket-books-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rocket-books-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rocket-books-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-rocket-books-public.php';


		/**
		 * requiring CMB2 init file for meta boxes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/CMB2/init.php';

		/**
		 * The class responsible for defining all actions for registering custom post types
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rocket-books-post-types.php';


		$this->loader = new Rocket_Books_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Rocket_Books_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Rocket_Books_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Rocket_Books_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

//		/**
//		 * Adding Plugin Admin Menu
//		 */
//		$this->loader->add_action(
//			'admin_menu',
//			$plugin_admin,
//			'add_admin_menu'
//		);
//
//		/**
//		 * Hooks for admin_init
//		 */
//		$this->loader->add_action(
//			'admin_init',
//			$plugin_admin,
//			'admin_init'
//		);
//
//
//		/**
//		 * Hooks for Plugin Action Links
//		 */
//		$this->loader->add_action(
//			'plugin_action_links_' . plugin_basename( ROCKET_BOOKS_BASE_FILE ),
//			$plugin_admin,
//			'add_plugin_action_links'
//		);

		/**
		 * Adding Plugin Admin Menu
		 */
		$this->loader->add_action(
			'admin_menu',
			$plugin_admin,
			'plugin_menu_settings_using_helper'
		);


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Rocket_Books_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

//		$this->loader->add_action('init', $plugin_public , 'register_book_post_type');
//
//		$this->loader->add_action('init', $plugin_public , 'register_taxonomy_genre');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Rocket_Books_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	/**
	 * Defining all action and filter hooks for registering custom post types
	 */
	public function define_post_type_hooks() {

		$plugin_post_types = new Rocket_Books_Post_Types( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'init', $plugin_post_types, 'init' );

//		$this->loader->add_filter('the_content' , $plugin_post_types , 'content_single_book');

		$this->loader->add_filter( 'single_template', $plugin_post_types, 'single_template_book' );

		$this->loader->add_filter( 'archive_template', $plugin_post_types, 'archive_template_book' );


		/**
		 * To add Metabox to CPT: book
		 */
//		$this->loader->add_action( 'add_meta_boxes_book', $plugin_post_types ,'register_metabox_book' , 10 , 1 );

		/**
		 * These loads to all post types
		 */
//		$this->loader->add_action( 'do_meta_boxes', $plugin_post_types ,'register_metabox_book' , 10 , 1 );

//		$this->loader->add_action( 'add_meta_boxes', $plugin_post_types ,'register_metabox_book' , 10 , 2 );


		/**
		 * Save metabox for CPT : book
		 */

//		$this->loader->add_action( 'save_post_book',
//			$plugin_post_types,
//			'metabox_save_book',
//			10,
//			3
//		);


		$this->loader->add_action(
			'cmb2_admin_init',
			$plugin_post_types,
			'register_cmb2_metabox_book'
		);


	}


}
