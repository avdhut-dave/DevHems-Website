<?php
/**
 * Asset loading: parent style, child style, mega-menu + form CSS/JS.
 * Non-critical scripts are deferred; only the hero font/image should be
 * preloaded, added per-page via Elementor's own preload settings.
 */

defined( 'ABSPATH' ) || exit;

function devhems_enqueue_assets() {
	wp_enqueue_style(
		'hello-elementor-parent',
		get_template_directory_uri() . '/style.css',
		array(),
		DEVHEMS_THEME_VERSION
	);

	wp_enqueue_style(
		'devhems-child',
		DEVHEMS_THEME_URI . '/style.css',
		array( 'hello-elementor-parent' ),
		DEVHEMS_THEME_VERSION
	);

	wp_enqueue_style(
		'devhems-mega-menu',
		DEVHEMS_THEME_URI . '/assets/css/mega-menu.css',
		array( 'devhems-child' ),
		DEVHEMS_THEME_VERSION
	);

	wp_enqueue_style(
		'devhems-forms',
		DEVHEMS_THEME_URI . '/assets/css/forms.css',
		array( 'devhems-child' ),
		DEVHEMS_THEME_VERSION
	);

	wp_enqueue_style(
		'devhems-components',
		DEVHEMS_THEME_URI . '/assets/css/components.css',
		array( 'devhems-child' ),
		DEVHEMS_THEME_VERSION
	);

	wp_enqueue_script(
		'devhems-mega-menu',
		DEVHEMS_THEME_URI . '/assets/js/mega-menu.js',
		array(),
		DEVHEMS_THEME_VERSION,
		true
	);

	wp_enqueue_script(
		'devhems-accordion',
		DEVHEMS_THEME_URI . '/assets/js/accordion.js',
		array(),
		DEVHEMS_THEME_VERSION,
		true
	);

	// Only load the CF7 UX/tracking script on pages that actually contain a form.
	if ( devhems_page_has_cf7() ) {
		wp_enqueue_script(
			'devhems-forms',
			DEVHEMS_THEME_URI . '/assets/js/forms.js',
			array( 'wpcf7' ),
			DEVHEMS_THEME_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'devhems_enqueue_assets' );

/**
 * Defer every non-critical script (everything except wp-includes/js/dist
 * core runtime scripts that must stay blocking, and jQuery which many
 * plugins depend on synchronously).
 */
function devhems_defer_scripts( $tag, $handle ) {
	$never_defer = array( 'jquery-core', 'jquery-migrate' );
	if ( in_array( $handle, $never_defer, true ) || is_admin() ) {
		return $tag;
	}
	if ( false === strpos( $tag, 'defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'devhems_defer_scripts', 10, 2 );

/**
 * The footer enquiry form (Contact Form 7) is part of the global footer, so
 * forms.js is needed on every page. Kept as a function so the enqueue call
 * site reads clearly and can be tightened later if the footer form becomes
 * optional per page.
 */
function devhems_page_has_cf7() {
	return true;
}
