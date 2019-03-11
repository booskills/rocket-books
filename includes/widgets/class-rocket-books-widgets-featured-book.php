<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
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

			$this->set_fields( $this->get_fields_args() );

			parent::__construct( $config_array );
		}

		/**
		 * fields arguments array
		 */
		public function get_fields_args() {

			$fields_args = array(
				array(
					'id'    => 'title',
					'label' => __( 'Title', 'rocket-books' ),
				),
				array(
					'id'    => 'text_color',
					'type'  => 'color',
					'label' => __( 'Text Color', 'rocket-books' ),
				),
				array(
					'id'    => 'bgcolor',
					'type'  => 'color',
					'label' => __( 'Background Color', 'rocket-books' ),
				),
				array(
					'id'      => 'book_id',
					'type'    => 'posts',
					'label'   => __( 'Select Your Favorite Book', 'rocket-books' ),
					'options' => array(
						'post_type' => 'book'
					)
				),


			);


			return $fields_args;
		}

		/**
		 * Display widget after the title
		 */
		public function widget_display( $args, $instance ) {

//			echo "<pre>";
//			var_export( $instance );
//			echo "</pre>";

			// text color
			$text_color = isset( $instance['text_color'] ) ? $instance['text_color'] : '';
			// bg color
			$bgcolor = isset( $instance['bgcolor'] ) ? $instance['bgcolor'] : '';
			// post id to be shown
			$book_id = isset( $instance['book_id'] ) ? $instance['book_id'] : '';

			echo do_shortcode( "[book_list 
										column=1
										limit=1
										color={$text_color}
										bgcolor={$bgcolor}
										book_id={$book_id}
										 ]" );
		}


	}
}