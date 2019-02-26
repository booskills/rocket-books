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
		public function book_list( $atts, $content ) {


			$atts = shortcode_atts(
				array(
					'limit'  => get_option( 'posts_per_page' ),
					'column' => 3,
				),
				$atts,
				'book_list'
			);

			$loop_args = array(
				'post_type'      => 'book',
				'posts_per_page' => $atts['limit'],
			);

			$loop = new WP_Query( $loop_args );

			$grid_column = rbr_get_column_class( $atts['column'] );

			/**
			 * When using Template Loader
			 */
//			$template_loader = rbr_get_template_loader();

			ob_start();
			?>
            <div class="cpt-cards <?php echo sanitize_html_class( $grid_column ); ?>">
				<?php
				// Start the Loop.
				while ( $loop->have_posts() ) :
					$loop->the_post();
					/**
					 * When using Template Loader
					 */
//					$template_loader->get_template_part( 'archive/content', 'book' );


					include ROCKET_BOOKS_BASE_DIR . 'templates/archive/content-book.php';

					// End the loop.
				endwhile;
				/* Restore original post */
				wp_reset_postdata();

				?>
            </div>

			<?php
			return ob_get_clean();

		}

	}
}