<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://booskills.com/rao
 * @since      1.0.0
 *
 * @package    Rocket_Books
 * @subpackage Rocket_Books/admin/partials
 */

?>

<div class="wrap">
    <h1><?php echo get_admin_page_title(); ?></h1>

    <form method="post" action="options.php">
		<?php

        // Security
		settings_fields('rbr-settings-page-options-group');

		// Display Section
		do_settings_sections( 'rbr-settings-page' )


		?>


		<?php submit_button(); ?>
    </form>
</div>
