<?php

/**
 * Functionality for our Custom Post types
 *
 *
 * @package    Rocket_Books
 * @subpackage Rocket_Books/public
 * @author     Rao <rao@booskills.com>
 */
class Rocket_Books_Post_Types {

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


	private $template_loader;

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

		$this->template_loader = $this->get_template_loader();

	}

	/**
	 * hooked into 'init' action hook
	 */
	public function init() {

		$this->register_cpt_book();
		$this->register_taxonomy_genre();
	}


	/**
	 * Registering Custom Post Type: Book
	 */
	public function register_cpt_book() {

		register_post_type( 'book', array(
			'description'          => __( 'Books', 'rocket-books' ),
			'labels'               => array(
				'name'               => _x( 'Books', 'post type general name', 'rocket-books' ),
				'singular_name'      => _x( 'Book', 'post type singular name', 'rocket-books' ),
				'menu_name'          => _x( 'Books', 'admin menu', 'rocket-books' ),
				'name_admin_bar'     => _x( 'Book', 'add new book on admin bar', 'rocket-books' ),
				'add_new'            => _x( 'Add New', 'post_type', 'rocket-books' ),
				'add_new_item'       => __( 'Add New Book', 'rocket-books' ),
				'edit_item'          => __( 'Edit Book', 'rocket-books' ),
				'new_item'           => __( 'New Book', 'rocket-books' ),
				'view_item'          => __( 'View Book', 'rocket-books' ),
				'search_items'       => __( 'Search Books', 'rocket-books' ),
				'not_found'          => __( 'No books found.', 'rocket-books' ),
				'not_found_in_trash' => __( 'No books found in Trash.', 'rocket-books' ),
				'parent_item_colon'  => __( 'Parent Book:', 'rocket-books' ),
				'all_items'          => __( 'All Books', 'rocket-books' ),
			),
			'public'               => true,
			'hierarchical'         => false,
			'exclude_from_search'  => false,
			'publicly_queryable'   => true,
			'show_ui'              => true,
			'show_in_menu'         => true,
			'show_in_nav_menus'    => true,
			'show_in_admin_bar'    => true,
			'menu_position'        => 20,
			'menu_icon'            => 'dashicons-book-alt',
			'capability_type'      => 'post',
			'capabilities'         => array(),
			'map_meta_cap'         => null,
			'supports'             => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
			'register_meta_box_cb' => array( $this, 'register_metabox_book' ),
			'taxonomies'           => array( 'genre' ),
			'has_archive'          => true,
			'rewrite'              => array(
				'slug'       => 'book',
				'with_front' => true,
				'feeds'      => true,
				'pages'      => true,
			),
			'query_var'            => true,
			'can_export'           => true,
			'show_in_rest'         => true,
		) );
	}

	/**
	 * Register custom taxonomy
	 */
	public function register_taxonomy_genre() {

		register_taxonomy( 'genre', array( 'book' ), array(
			'description'       => 'Genre',
			'labels'            => array(
				'name'                       => _x( 'Genre', 'taxonomy general name', 'rocket-books' ),
				'singular_name'              => _x( 'Genre', 'taxonomy singular name', 'rocket-books' ),
				'search_items'               => __( 'Search Genre', 'rocket-books' ),
				'popular_items'              => __( 'Popular Genre', 'rocket-books' ),
				'all_items'                  => __( 'All Genre', 'rocket-books' ),
				'parent_item'                => __( 'Parent Genre', 'rocket-books' ),
				'parent_item_colon'          => __( 'Parent Genre:', 'rocket-books' ),
				'edit_item'                  => __( 'Edit Genre', 'rocket-books' ),
				'view_item'                  => __( 'View Genre', 'rocket-books' ),
				'update_item'                => __( 'Update Genre', 'rocket-books' ),
				'add_new_item'               => __( 'Add New Genre', 'rocket-books' ),
				'new_item_name'              => __( 'New Genre Name', 'rocket-books' ),
				'separate_items_with_commas' => __( 'Separate genre with commas', 'rocket-books' ),
				'add_or_remove_items'        => __( 'Add or remove genre', 'rocket-books' ),
				'choose_from_most_used'      => __( 'Choose from the most used genre', 'rocket-books' ),
				'not_found'                  => __( 'No genre found.', 'rocket-books' ),
			),
			'public'            => true,
			'show_ui'           => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
			'meta_box_cb'       => null,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'query_var'         => 'genre',
			'rewrite'           => array(
				'slug'         => 'genre',
				'with_front'   => true,
				'hierarchical' => true,
			),
			'capabilities'      => array(),
			'show_in_rest'      => true,
		) );
	}

	/**
	 * filter content for CPT: Book
	 *
	 * @param      mixed $the_content the content of the post/post type.
	 */
	public function content_single_book( $the_content ) {

		// filter contents for just Books
		if ( in_the_loop() && is_singular( 'book' ) ) {

//			return "<pre>" . $the_content . "</pre>";

			ob_start();
			include ROCKET_BOOKS_BASE_DIR . 'templates/book-content.php';

			return ob_get_clean();


		}


		return $the_content;

	}


	/**
	 * Single Template for CPT: book
	 */
	public function single_template_book( $template ) {

		if ( is_singular( 'book' ) ) {

			return $this->template_loader->get_template_part( 'single', 'book', false );

		}

		return $template;
	}

	/**
	 * Archive Template for CPT: book
	 */
	public function archive_template_book( $template ) {

		if ( is_post_type_archive( 'book' ) || is_tax( 'genre' ) ) {

			// template for CPT book

			return $this->template_loader->get_template_part( 'archive', 'book', false );

		}

		return $template;
	}

	public function get_template_loader() {

		require_once ROCKET_BOOKS_BASE_DIR . 'public/class-rocket-books-template-loader.php';

		return new Rocket_Books_Template_Loader();

	}

	/**
	 * Register Metabox for CPT: Book
	 */
	public function register_metabox_book( $post ) {


		$is_gutenberg_active = (
			function_exists( 'use_block_editor_for_post_type' ) &&
			use_block_editor_for_post_type( get_post_type() )
		);

		add_meta_box(
			'book-details',
			__( 'Book Details', 'rocket-books' ),
			array( $this, 'book_metabox_display_cb' ),  // function name for procedural
			'book',
			( $is_gutenberg_active ) ? 'side' : 'normal',
			'high'
		);


	}


	/**
	 * Display for Metabox for CPT: book
	 */
	public function book_metabox_display_cb( $post ) {

//		echo 'here, we shall display fields';

		wp_nonce_field( 'rbr_meta_box_nonce_action', 'rbr_meta_box_nonce' );

		?>
        <label for="rbr-book-pages"><?php
			_e( 'Number of Pages', 'rocket-books' )
			?></label>
        <input
                type="text"
                name="rbr-book-pages"
                class="widefat"
                value="<?php echo get_post_meta( get_the_ID(), 'rbr_book_pages', true ) ?>"
        >
        <label for="rbr-is-featured"><?php
			_e( 'is Featured Book?', 'rocket-books' )
			?></label>
        <input
                type="checkbox"
                name="rbr-is-featured"
                value="yes"
			<?php checked(
				get_post_meta( get_the_ID(), 'rbr_is_featured', true ),
				"yes"
			) ?>
        />


		<?php
//		echo esc_html(get_post_meta( get_the_ID(), 'rbr_book_pages', true ));


//        <script>alert('hello');</script>

//        echo "<pre>";
//        var_export(get_post_meta($post->ID));
//		echo "</pre>";

//
//        get_post_meta(
//                '',
//            '',
//            ''   // true,
//        );


	}


	/**
	 * Saving Custom fields for CPT: book
	 */
	public function metabox_save_book( $post_id, $post, $update ) {

//	    $current_user = wp_get_current_user();
//	    $current_user->add_cap('edit_posts');


		/**
		 * Prevent saving if its triggered for:
		 *  1. Auto save
		 *  2. User does not have permission to edit
		 *  3. invalid nonce
		 */

		// if this is an autosave, our form has not been submitted, so do nothing
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}


		// check user permission
		if ( ! current_user_can( 'edit_posts', $post_id ) ) {
			print __( 'Sorry, you do not have access to edit post', 'rocket-books' );
			exit;
		}


		// Verify Nonce
		if (
			! isset( $_POST['rbr_meta_box_nonce'] )
			||
			! wp_verify_nonce(
				$_POST['rbr_meta_box_nonce'],
				'rbr_meta_box_nonce_action'
			)
		) {
			return null;

//			print __('Sorry, your nonce did not verify.', 'rocket-books');
//			exit;
		}


		/**
		 * We are good to process data.
		 */


//	    var_export($_POST);

//		var_export($_POST['rbr-book-pages']); die();

//		update_post_meta( get_the_ID(), 'rbr_book_page', $_POST['rbr-book-pages'] );

		update_post_meta(
			$post_id,
			'rbr_book_pages',
			absint( $_POST['rbr-book-pages'] ) // should be sanitized
		);

		// Sanitization :   We know the type
        // Validation : We know what are expecting to recieve

		update_post_meta(
			$post_id,
			'rbr_is_featured',
            ( 'yes' === $_POST['rbr-is-featured']) ? 'yes' : 'no' // should be sanitized // yes / no
		);


	}


}
