<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'cpt-card' ); ?>>
    <header class="book-entry-header">

		<?php the_title( sprintf( '<h2 class="book-entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
    </header><!-- .entry-header -->
    <div class="book-entry-img">
		<?php the_post_thumbnail(); ?>
    </div>
    <div class="book-entry-content">
		<?php
		/* translators: %s: Name of current post */
		the_excerpt();

		wp_link_pages(
			array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			)
		);
		?>
    </div><!-- .entry-content -->

    <footer class="book-entry-footer">
		<?php
		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				__( 'Edit<span class="screen-reader-text"> "%s"</span>', 'twentysixteen' ),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
		?>
    </footer><!-- .entry-footer -->
</article><!-- #post-## -->
