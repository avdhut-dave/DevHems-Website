<?php
/**
 * The single reusable "Ready to grow?" component used identically at the
 * bottom of Home, About Us, Services Listing and every Inner Service page.
 * Opens the header consultation modal (inc/consultation-modal.php) rather
 * than linking to another page.
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="devhems-page-banner devhems-bottom-cta">
	<div class="devhems-page-banner-inner" style="text-align:center;">
		<h2 style="color:#fff;"><?php esc_html_e( 'Ready to Grow With a Team That Shows Its Work?', 'devhems-child' ); ?></h2>
		<?php echo do_shortcode( '[devhems_consultation_trigger]' ); ?>
	</div>
</section>
