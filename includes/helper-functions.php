<?php


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
