<?php
/**
 * "Case Study" custom post type (Portfolio). Client industry, challenge,
 * solution, results, gallery, testimonial and related services live in ACF
 * (inc/acf-fields.php) so admins edit them without touching code.
 */

defined( 'ABSPATH' ) || exit;

function devhems_register_cpt_case_study() {
	$labels = array(
		'name'          => __( 'Case Studies', 'devhems-child' ),
		'singular_name' => __( 'Case Study', 'devhems-child' ),
		'add_new_item'  => __( 'Add New Case Study', 'devhems-child' ),
		'edit_item'     => __( 'Edit Case Study', 'devhems-child' ),
		'new_item'      => __( 'New Case Study', 'devhems-child' ),
		'view_item'     => __( 'View Case Study', 'devhems-child' ),
		'search_items'  => __( 'Search Case Studies', 'devhems-child' ),
		'not_found'     => __( 'No case studies found', 'devhems-child' ),
		'all_items'     => __( 'All Case Studies', 'devhems-child' ),
		'menu_name'     => __( 'Case Studies', 'devhems-child' ),
	);

	register_post_type(
		'case_study',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => 'portfolio',
			'rewrite'       => array( 'slug' => 'portfolio', 'with_front' => false ),
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
			'show_in_rest'  => true,
		)
	);
}
add_action( 'init', 'devhems_register_cpt_case_study' );
