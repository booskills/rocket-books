<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

?>

<div class="book-content some-new-class" style="background-color: lightskyblue;">
	<?php echo get_the_content(); ?>
</div>