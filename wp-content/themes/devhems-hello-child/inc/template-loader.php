<?php
/**
 * Routes single/archive templates for the custom post types to the files
 * under /templates instead of requiring them in the theme root, so they
 * stay grouped together. Elementor Pro's Theme Builder (if licensed) hooks
 * into template_include at a higher priority than the theme and takes over
 * automatically whenever a matching Theme Builder template exists — this
 * loader only runs as the fallback when it doesn't.
 */

defined( 'ABSPATH' ) || exit;

function devhems_template_loader( $template ) {
	$map = array(
		'single-service.php'      => 'templates/single-service.php',
		'single-case_study.php'   => 'templates/single-case-study.php',
		'single-career.php'       => 'templates/single-career.php',
		'archive-service.php'     => 'templates/archive/archive-service.php',
		'archive-case_study.php'  => 'templates/archive/archive-case-study.php',
		'archive-career.php'      => 'templates/archive/archive-career.php',
	);

	foreach ( $map as $core_name => $theme_relative_path ) {
		if ( is_singular( str_replace( array( 'single-', '.php' ), '', $core_name ) ) || is_post_type_archive( str_replace( array( 'archive-', '.php' ), '', $core_name ) ) ) {
			$candidate = DEVHEMS_THEME_DIR . '/' . $theme_relative_path;
			if ( file_exists( $candidate ) ) {
				return $candidate;
			}
		}
	}

	// Taxonomy term archives reuse their post type's own archive template
	// (each already branches on is_tax()/is_wp_error() to highlight the
	// active term and only show its own filter bar).
	$taxonomy_map = array(
		'service_category' => 'templates/archive/archive-service.php',
		'department'        => 'templates/archive/archive-career.php',
		'industry'          => 'templates/archive/archive-case-study.php',
	);

	foreach ( $taxonomy_map as $taxonomy => $theme_relative_path ) {
		if ( is_tax( $taxonomy ) ) {
			$candidate = DEVHEMS_THEME_DIR . '/' . $theme_relative_path;
			if ( file_exists( $candidate ) ) {
				return $candidate;
			}
		}
	}

	return $template;
}
add_filter( 'template_include', 'devhems_template_loader', 15 );
