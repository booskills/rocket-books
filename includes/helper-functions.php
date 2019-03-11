<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

function rbr_is_single_or_archive_book() {
	return
		(
			is_singular( 'book' )
			||
			rbr_is_archive_book()
		)
			? true
			: false;

}

function rbr_is_archive_book() {
	return
		(
			is_post_type_archive( 'book' )
			|| is_tax( 'genre' )
		)
			? true
			: false;

}


function rbr_get_template_loader() {
	return Rocket_Books_Global::template_loader();
}

/**
 * @param $int int
 *
 * @return $css_class string
 */
function rbr_get_column_class( $int ) {

	switch ( $int ) {

		case( 1 ):
			return 'column-one';
			break;
		case( 2 ):
			return 'column-two';
			break;
		case( 3 ):
			return 'column-three';
			break;
		case( 4 ):
			return 'column-four';
			break;
		case( 5 ):
			return 'column-five';
			break;

		default:
			return 'column-three';

	}

}


function rbr_sanitize_color( $value ) {

	if ( false === strpos( $value, 'rgba' ) ) {
		return sanitize_hex_color( $value );
	} else {
		// By now we know the string is formatted as an rgba color so we need to further sanitize it.

		$value = trim( $value, ' ' );
		$red   = $green = $blue = $alpha = '';
		sscanf( $value, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );

		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
	}
}
