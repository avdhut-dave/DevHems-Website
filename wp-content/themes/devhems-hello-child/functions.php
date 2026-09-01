<?php
/**
 * DevHems Technology child theme bootstrap.
 * Loads parent (Hello Elementor) styles plus all custom functionality modules.
 */

defined( 'ABSPATH' ) || exit;

define( 'DEVHEMS_THEME_VERSION', '1.0.0' );
define( 'DEVHEMS_THEME_DIR', get_stylesheet_directory() );
define( 'DEVHEMS_THEME_URI', get_stylesheet_directory_uri() );

/**
 * Module include list. Each file is self-contained and hooks itself in.
 */
function devhems_includes() {
	$modules = array(
		'inc/setup.php',            // theme supports, menus, skip link
		'inc/enqueue.php',          // styles/scripts, defer, preload
		'inc/performance.php',      // disable bloat, lazy-load, CWV
		'inc/post-types-services.php',
		'inc/post-types-case-studies.php',
		'inc/post-types-careers.php',
		'inc/post-types-testimonials.php',
		'inc/taxonomies.php',
		'inc/acf-fields.php',
		'inc/mega-menu.php',
		'inc/cf7-integration.php',
		'inc/schema.php',
		'inc/seo-support.php',
		'inc/template-tags.php',
		'inc/breadcrumbs.php',
		'inc/template-loader.php',
	);

	foreach ( $modules as $module ) {
		$path = DEVHEMS_THEME_DIR . '/' . $module;
		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}
}
devhems_includes();
