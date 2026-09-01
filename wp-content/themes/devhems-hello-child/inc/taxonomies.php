<?php
/**
 * Custom taxonomies for Services and Case Studies. Blog categories/tags use
 * WordPress's built-in "category" and "post_tag" taxonomies as required.
 */

defined( 'ABSPATH' ) || exit;

function devhems_register_taxonomies() {

	// Service Category — powers the mega menu groupings (Digital Marketing,
	// Website Services, AI and Automation) and the services archive filter.
	register_taxonomy(
		'service_category',
		array( 'service' ),
		array(
			'labels' => array(
				'name'          => __( 'Service Categories', 'devhems-child' ),
				'singular_name' => __( 'Service Category', 'devhems-child' ),
				'menu_name'     => __( 'Categories', 'devhems-child' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'service-category' ),
		)
	);

	// Industry — used on Case Studies and reusable on Services ("industries served").
	register_taxonomy(
		'industry',
		array( 'case_study', 'service' ),
		array(
			'labels' => array(
				'name'          => __( 'Industries', 'devhems-child' ),
				'singular_name' => __( 'Industry', 'devhems-child' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'industry' ),
		)
	);

	// Department — used on Careers for filtering job openings.
	register_taxonomy(
		'department',
		array( 'career' ),
		array(
			'labels' => array(
				'name'          => __( 'Departments', 'devhems-child' ),
				'singular_name' => __( 'Department', 'devhems-child' ),
			),
			'hierarchical'      => true,
			'public'            => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => array( 'slug' => 'department' ),
		)
	);
}
add_action( 'init', 'devhems_register_taxonomies' );
