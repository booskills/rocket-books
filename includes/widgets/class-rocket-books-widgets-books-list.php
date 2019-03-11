<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Register Widget : Books List
 *
 * @package    Rocket_Books
 * @subpackage Rocket_Books/includes
 * @author     Rao <rao@booskills.com>
 */

if ( ! class_exists( 'Rocket_Books_Widget_Books_List' ) ) {

	class Rocket_Books_Widget_Books_List extends WP_Widget {

		/**
		 * Sets up the widgets name etc
		 */
		public function __construct() {
			$widget_ops = array(
				'classname'   => 'rbr_books_list_class',
				'description' => __( 'Display Rocket Books List', 'rocket-books' ),
			);
			parent::__construct( 'rbr_books_list', __( 'Books List', 'rocket-books' ), $widget_ops );
		}

		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			// outputs the content of the widget
//			$args array keys
//			array (
//				0 => 'name',
//				1 => 'id',
//				2 => 'description',
//				3 => 'class',
//				4 => 'before_widget',
//				5 => 'after_widget',
//				6 => 'before_title',
//				7 => 'after_title',
//				8 => 'widget_id',
//				9 => 'widget_name',
//			)
			$title  = isset( $instance['title'] ) ? $instance['title'] : '';
			$limit  = isset( $instance['limit'] ) ? $instance['limit'] : 5;
			$format = isset( $instance['format'] ) ? $instance['format'] : '';

			echo $args['before_widget'];
			echo $args['before_title'];
			// Title will be displayed here
			echo esc_html( $title );
			echo $args['after_title'];

			// Loop for CPTs

			$loop_args = array(
				'post_type'      => 'book',
				'posts_per_page' => $limit
			);

			if ( ! empty( $format ) ) {
				$loop_args['meta_query'] = array(
					array(
						'key'     => 'rbr_book_format',
						'value'   => $format,
						'compare' => '='
					)
				);
			}

			$loop = new WP_Query( $loop_args );

			echo '<div class="cpt-cards-widget">';
			// Start the loop
			while ( $loop->have_posts() ):
				$loop->the_post();

				include ROCKET_BOOKS_BASE_DIR . 'templates/widgets/content-book.php';

			endwhile;


			echo '</div>';


//			echo "<pre>";
//			var_export( $instance );
//
////			var_export( get_option( 'widget_rbr_books_list', true ) );
//
//			echo "</pre>";

			echo $args['after_widget'];


//			echo "This is widget method";
		}

		/**
		 * Outputs the options form on admin
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {
			// outputs the options form on admin
//			echo "This is form method";

			$title = isset( $instance['title'] ) ? $instance['title'] : '';

			$limit = isset( $instance['limit'] ) ? $instance['limit'] : 5;

			$format = isset( $instance['format'] ) ? $instance['format'] : '';
			?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"><?php _e( 'Title:', 'rocket-books' ); ?></label>
                <input type="text" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                       value="<?php echo esc_html( $title ); ?>"
                >
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>"><?php _e( 'Limit:', 'rocket-books' ); ?></label>
                <input type="number" class="widefat"
                       id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"
                       name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>"
                       value="<?php echo esc_html( $limit ); ?>"
                >
            </p>
			<?php

			$format_select_options = array(
				''          => __( 'All', 'rocket-books' ),
				'hardcover' => __( 'Hardcover', 'rocket-books' ),
				'audio'     => __( 'Audio', 'rocket-books' ),
				'pdf'       => __( 'PDF', 'rocket-books' )
			);

			printf(
				'<p><label for="%s">%s</label>',
				$this->get_field_name( 'format' ),
				__( 'Format', 'rocket-books' )
			);


			printf(
				'<select id="%s" name="%s" class="widefat">',
				$this->get_field_id( 'format' ),
				$this->get_field_name( 'format' )
			);
			// Output options
			foreach ( $format_select_options as $value => $label ) {
				printf(
					'<option value="%s" %s>%s</option>',
					$value,
					selected( $value, $format ),
					$label
				);
			}

			echo "</select></p>";


		}

		/**
		 * Processing widget options on save
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {
			// processes widget options to be saved

			// Sanitization of $new_instance

			$sanitized_instance['title']  = sanitize_text_field( $new_instance['title'] );
			$sanitized_instance['limit']  = absint( $new_instance['limit'] );
			$sanitized_instance['format'] = sanitize_key( $new_instance['format'] );

			return $sanitized_instance;

		}
	}
}