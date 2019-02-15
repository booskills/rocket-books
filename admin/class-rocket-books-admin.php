<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://booskills.com/rao
 * @since      1.0.0
 *
 * @package    Rocket_Books
 * @subpackage Rocket_Books/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rocket_Books
 * @subpackage Rocket_Books/admin
 * @author     Rao <rao@booskills.com>
 */
class Rocket_Books_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rocket_Books_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rocket_Books_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rocket-books-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Rocket_Books_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rocket_Books_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rocket-books-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add admin menu for our plugin
	 */
	public function add_admin_menu() {

		// Top Level Menu
//		add_menu_page(
//			'Rocket Books Settings',
//			'Rocket Books',
//			'manage_options',
//			'rocket-books',
//			array($this, 'admin_page_display'),
//			'dashicons-chart-pie',
//			60
//		);


		// Sub Menu
//		add_plugins_page(
//			'Rocket Books Settings',
//			'Rocket Books',
//			'manage_options',
//			'rocket-books',
//			array($this, 'admin_page_display')
//		);

		add_submenu_page(
			'edit.php?post_type=book',
			'Rocket Books Settings Page',
			'Rocket Books',
			'manage_options',
			'rocket-books',
			array( $this, 'admin_page_display' )
		);


	}

	/**
	 * Admin Page Display
	 */
	public function admin_page_display() {

		// Old method of saving options
//		include 'partials/rocket-books-admin-display-form-method.php';


		// Settings API
		include 'partials/rocket-books-admin-display.php';

	}

	/**
	 * All the hooks for admin_init
	 */
	public function admin_init() {

		// Add Settings Section
		$this->add_settings_section();


		// Add Settings Fields
		$this->add_settings_fields();


		// Save Settings
		$this->save_fields();


	}

	/**
	 * Add Settings Sections for plugin options
	 */
	public function add_settings_section() {

		add_settings_section(
			'rbr-general-section',
			'General Settings',
			function () {
				echo '<p>These are general settings for Rocket Books</p>';
			},
			'rbr-settings-page'
		);

		// Advance Section
		add_settings_section(
			'rbr-advance-section',
			'Advance Settings',
			function () {
				echo '<p>These are advance settings for Rocket Books</p>';
			},
			'rbr-settings-page'
		);

	}

	/**
	 * Add settings fields
	 */
	public function add_settings_fields() {

		add_settings_field(
			'rbr_test_field',
			'Test Field',
			function () {
				echo '<input 
						type="text" 
						name="rbr_test_field"
						value="' . esc_html( get_option( 'rbr_test_field' ) ) . '"
						/>';
			},
			'rbr-settings-page',
			'rbr-general-section'
		);

		add_settings_field(
			'rbr_advance_field1',
			'Advance Field 1',
			function () {
				echo '<input 
						type="text" 
						name="rbr_advance_field1"
						value="' . esc_html( get_option( 'rbr_advance_field1' ) ) . '"
						/>';
			},
			'rbr-settings-page',
			'rbr-advance-section'
		);


		add_settings_field(
			'rbr_advance_field2',
			'Advance Field 2',
			function () {
				echo '<input 
						type="text" 
						name="rbr_advance_field2"
						value="' . esc_html( get_option( 'rbr_advance_field2' ) ) . '"
						/>';
			},
			'rbr-settings-page',
			'rbr-advance-section'
		);


	}

	/**
	 * Save Settings Fields
	 */
	public function save_fields() {

		register_setting(
			'rbr-settings-page-options-group',
			'rbr_test_field',
			array(
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

		register_setting(
			'rbr-settings-page-options-group',
			'rbr_advance_field1',
			array(
				'sanitize_callback' => 'sanitize_text_field'
			)
		);

		register_setting(
			'rbr-settings-page-options-group',
			'rbr_advance_field2',
			array(
				'sanitize_callback' => 'absint'
			)
		);


	}


}
