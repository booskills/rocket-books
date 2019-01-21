<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://booskills.com/rao
 * @since             1.0.0
 * @package           Rocket_Books
 *
 * @wordpress-plugin
 * Plugin Name:       Rocket Books Shelf
 * Plugin URI:        https://booskills.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Rao
 * Author URI:        https://booskills.com/rao
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       rocket-books
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ROCKET_BOOKS_VERSION', '1.0.0' );


/**
 * Plugin Name.
 * used to hold plugin name that can be used for constructors of plugin classes.
 */
define( 'ROCKET_BOOKS_NAME', 'rocket-books' );


/**
 * Plugin base dir path.
 * used to locate plugin resources primarily code files
 * Start at version 1.0.0
 */
define( 'ROCKET_BOOKS_BASE_DIR', plugin_dir_path( __FILE__ ) );


/**
 * Plugin url to access its resources through browser
 * used to access assets images/css/js files
 * Start at version 1.0.0
 */
define( 'ROCKET_BOOKS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-rocket-books-activator.php
 */
function activate_rocket_books() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rocket-books-activator.php';
	Rocket_Books_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-rocket-books-deactivator.php
 */
function deactivate_rocket_books() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-rocket-books-deactivator.php';
	Rocket_Books_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_rocket_books' );
register_deactivation_hook( __FILE__, 'deactivate_rocket_books' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-rocket-books.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_rocket_books() {

	$plugin = new Rocket_Books();
	$plugin->run();

}

run_rocket_books();

add_action( 'init', 'codex_book_init' );
/**
 * Register a book post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function codex_book_init() {
	$labels = array(
		'name'               => _x( 'Books', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Book', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Books', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Book', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'book', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Book', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Book', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Book', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Book', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Books', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Books', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Books:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No books found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No books found in Trash.', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'book' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
	);

	register_post_type( 'book', $args );
}