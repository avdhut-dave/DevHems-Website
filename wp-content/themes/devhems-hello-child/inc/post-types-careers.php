<?php
/**
 * "Career" custom post type. Department, location, employment type,
 * experience, responsibilities, requirements, deadline and job status live
 * in ACF (inc/acf-fields.php). "Job status" (Open/Closed) additionally
 * hides expired listings from the archive automatically.
 */

defined( 'ABSPATH' ) || exit;

function devhems_register_cpt_career() {
	$labels = array(
		'name'          => __( 'Careers', 'devhems-child' ),
		'singular_name' => __( 'Job Opening', 'devhems-child' ),
		'add_new_item'  => __( 'Add New Job Opening', 'devhems-child' ),
		'edit_item'     => __( 'Edit Job Opening', 'devhems-child' ),
		'new_item'      => __( 'New Job Opening', 'devhems-child' ),
		'view_item'     => __( 'View Job Opening', 'devhems-child' ),
		'search_items'  => __( 'Search Job Openings', 'devhems-child' ),
		'not_found'     => __( 'No job openings found', 'devhems-child' ),
		'all_items'     => __( 'All Job Openings', 'devhems-child' ),
		'menu_name'     => __( 'Careers', 'devhems-child' ),
	);

	register_post_type(
		'career',
		array(
			'labels'        => $labels,
			'public'        => true,
			'has_archive'   => 'careers',
			'rewrite'       => array( 'slug' => 'careers', 'with_front' => false ),
			'menu_icon'     => 'dashicons-businessman',
			'menu_position' => 22,
			'supports'      => array( 'title', 'editor', 'revisions' ),
			'show_in_rest'  => true,
		)
	);
}
add_action( 'init', 'devhems_register_cpt_career' );

/**
 * Hide job openings whose ACF "job_status" is Closed, or whose application
 * deadline has passed, from the public /careers archive. Admins keep the
 * post around (for record-keeping) without deleting it.
 */
function devhems_filter_expired_careers( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! is_post_type_archive( 'career' ) ) {
		return;
	}

	$meta_query = array(
		'relation' => 'OR',
		array(
			'key'     => 'job_status',
			'value'   => 'closed',
			'compare' => '!=',
		),
		array(
			'key'     => 'job_status',
			'compare' => 'NOT EXISTS',
		),
	);

	$query->set( 'meta_query', $meta_query );
}
add_action( 'pre_get_posts', 'devhems_filter_expired_careers' );
