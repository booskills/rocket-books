<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-book-container' ); ?>>

    <div class="book-meta-container">

        <div class="book-entry-img">
			<?php the_post_thumbnail(); ?>
        </div>

        <ul class="book-meta-fields">
            <!--			Here we will display our custom meta-->
            <li>Pages:
				<?php echo
				esc_html(
					get_post_meta(
						get_the_ID(),
						'rbr_book_pages',
						true
					)
				) ?></li>

            <li>Format:
		        <?php echo
		        esc_html(
			        get_post_meta(
				        get_the_ID(),
				        'rbr_book_format',
				        true
			        )
		        ) ?></li>
            <li>is Featured:
		        <?php echo
		        esc_html(
			        get_post_meta(
				        get_the_ID(),
				        'rbr_is_featured',
				        true
			        )
		        ) ?></li>
        </ul>

    </div>

    <div class="book-entry-content">
		<?php
		the_content();
		?>
    </div><!-- .book-entry-content -->

    <footer class="book-entry-footer">
		<?php
		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'rocket-books' ),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
		?>
    </footer><!-- .entry-footer -->
</article><!-- #post-## -->
