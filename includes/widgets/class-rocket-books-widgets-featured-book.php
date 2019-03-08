<?php
/**
 * Register Widget : Featured Book
 *
 * @package    Rocket_Books
 * @subpackage Rocket_Books/includes
 * @author     Rao <rao@booskills.com>
 */

if ( ! class_exists( 'Rocket_Books_Widget_Featured_Book' ) ) {

	class Rocket_Books_Widget_Featured_Book extends Boo_Widget_Helper {

		/**
		 * Sets up the widgets name etc
		 */
		public function __construct() {

			$config_array = array(
				'id'   => 'rbr_featured_book',
				'name' => __( 'Featured Book', 'rocket-books' ),
				'desc' => __( 'Display your Featured Book', 'rocket-books' ),
			);


			parent::__construct( $config_array );
		}

	}
}