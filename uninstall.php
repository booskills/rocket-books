<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://booskills.com/rao
 * @since      1.0.0
 *
 * @package    Rocket_Books
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}


// Remove Options
$option_ids_to_delete = array(
	0  => 'rbr_test_field',
	1  => 'rbr_archive_column',
	2  => 'rbr_text_field1',
	3  => 'rbr_color_test',
	4  => 'rbr_number_field1',
	5  => 'rbr_textarea_field1',
	6  => 'rbr_advance_color_field',
	7  => 'rbr_checkbox_field1',
	8  => 'rbr_multi_op_test',
	9  => 'rbr_radio_test2',
	10 => 'rbr_select_test1',
	11 => 'rbr_pages_test',
	12 => 'rbr_posts_test',
	13 => 'rbr_password_test',
	14 => 'rbr_file_test',
	15 => 'rbr_media_test',
	16 => 'rbr_advance_field1',
	17 => 'rbr_advance_field2',
	18 => 'rbr_general_section',
	19 => 'rbr_advance_section',
);
// Remove Widget Options
$option_ids_to_delete[] = 'widget_rbr_featured_book';
$option_ids_to_delete[] = 'widget_rbr_books_list';

if ( current_user_can( 'manage_options' ) ) {

	foreach ( $option_ids_to_delete as $option_id ) {
		delete_option( $option_id );
	}

}

// Remove Tables

// Remove Everything and anything plugin has added.
