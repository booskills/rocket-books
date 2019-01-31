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

add_action( 'init', function(){

	register_post_type( 'rocket', array(
		'description'          => __( 'Rcocket', 'rocket-books' ),
		'labels'               => array(
			'name'               => _x( 'Rockets', 'post type general name', 'rocket-books' ),
			'singular_name'      => _x( 'Rocket', 'post type singular name', 'rocket-books' ),
			'menu_name'          => _x( 'Rockets', 'admin menu', 'rocket-books' ),
			'name_admin_bar'     => _x( 'Rocket', 'add new rocket on admin bar', 'rocket-books' ),
			'add_new'            => _x( 'Add New', 'post_type', 'rocket-books' ),
			'add_new_item'       => __( 'Add New Rocket', 'rocket-books' ),
			'edit_item'          => __( 'Edit Rocket', 'rocket-books' ),
			'new_item'           => __( 'New Rocket', 'rocket-books' ),
			'view_item'          => __( 'View Rocket', 'rocket-books' ),
			'search_items'       => __( 'Search Rockets', 'rocket-books' ),
			'not_found'          => __( 'No rockets found.', 'rocket-books' ),
			'not_found_in_trash' => __( 'No rockets found in Trash.', 'rocket-books' ),
			'parent_item_colon'  => __( 'Parent Rocket:', 'rocket-books' ),
			'all_items'          => __( 'All Rockets', 'rocket-books' ),
		),
		'public'               => true,
		'hierarchical'         => false,
		'exclude_from_search'  => true,
		'publicly_queryable'   => true,
		'show_ui'              => true,
		'show_in_menu'         => true,
		'show_in_nav_menus'    => true,
		'show_in_admin_bar'    => true,
		'menu_position'        => null,
		'menu_icon'            => null,
		'capability_type'      => 'post',
		'capabilities'         => array(),
		'map_meta_cap'         => null,
		'supports'             => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		'register_meta_box_cb' => null,
		'taxonomies'           => array(),
		'has_archive'          => false,
		'rewrite'              => array(
			'slug'       => 'rocket',
			'with_front' => true,
			'feeds'      => false,
			'pages'      => true,
		),
		'query_var'            => true,
		'can_export'           => true,
		'show_in_rest'         => true,
	) );

} );
