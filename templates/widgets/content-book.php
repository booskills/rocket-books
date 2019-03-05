<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'cpt-card-widget' ); ?>>
    <div class="book-entry-img">
		<?php the_post_thumbnail( 'thumbnail' ); ?>
    </div>
    <div class="book-entry-content">
		<?php
		the_title( sprintf( '<h2 class="book-entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

		include ROCKET_BOOKS_BASE_DIR . 'templates/book-meta.php';
		?>
    </div><!-- .entry-content -->

</article><!-- #post-## -->
