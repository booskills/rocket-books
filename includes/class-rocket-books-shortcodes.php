<?php
/**
 * Plugin Shortcode Functionality
 *
 * @package    Rocket_Books
 * @subpackage Rocket_Books/includes
 * @author     Rao <rao@booskills.com>
 */
if ( ! class_exists( 'Rocket_Books_Shortcodes' ) ) {

	class Rocket_Books_Shortcodes {

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
		 * @param      string $plugin_name The name of the plugin.
		 * @param      string $version The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

		}

		/**
		 * Shortcode for Books List
		 *
		 * Usage: [book_list limit=5 column=3]These are contents of shortcode[/book_list]
		 *
		 */
		public function book_list($atts, $content) {

			return "i am shortcode" . "<br/>" . "<strong>{$content}</strong>" . "<br/>" . var_export( $atts, true );
		}

	}
}