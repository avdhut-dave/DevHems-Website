<?php
/**
 * Custom 404 page. Kept intentionally simple and code-based (rather than an
 * editable Elementor template) since a 404 must always render even if
 * Elementor/the page builder database entry is ever missing or corrupted;
 * the messaging/CTA text below can still be edited by changing this file
 * or, if preferred, replaced with an Elementor "Error 404" Theme Builder
 * template (Elementor Pro), which takes priority automatically.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<main id="content" tabindex="-1" class="devhems-404">
	<h1><?php esc_html_e( 'Page Not Found', 'devhems-child' ); ?></h1>
	<p><?php esc_html_e( "Sorry, the page you're looking for doesn't exist or has moved.", 'devhems-child' ); ?></p>

	<form role="search" method="get" class="devhems-404-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label for="dh-404-search"><?php esc_html_e( 'Search the site', 'devhems-child' ); ?></label>
		<input type="search" id="dh-404-search" name="s" placeholder="<?php esc_attr_e( 'What are you looking for?', 'devhems-child' ); ?>">
		<button type="submit"><?php esc_html_e( 'Search', 'devhems-child' ); ?></button>
	</form>

	<div class="devhems-404-links">
		<a class="devhems-header-cta" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'devhems-child' ); ?></a>
		<a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>"><?php esc_html_e( 'Browse our Services', 'devhems-child' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'devhems-child' ); ?></a>
	</div>
</main>

<?php get_footer(); ?>
