<?php
/**
 * "Service" custom post type. Fields beyond title/content/featured image
 * (short description, benefits, process, FAQs, related services, SEO) are
 * registered as ACF field groups in inc/acf-fields.php and are fully
 * editable by the site administrator — nothing here is hardcoded content.
 */

defined( 'ABSPATH' ) || exit;

function devhems_register_cpt_service() {
	$labels = array(
		'name'               => __( 'Services', 'devhems-child' ),
		'singular_name'      => __( 'Service', 'devhems-child' ),
		'add_new_item'       => __( 'Add New Service', 'devhems-child' ),
		'edit_item'          => __( 'Edit Service', 'devhems-child' ),
		'new_item'           => __( 'New Service', 'devhems-child' ),
		'view_item'          => __( 'View Service', 'devhems-child' ),
		'search_items'       => __( 'Search Services', 'devhems-child' ),
		'not_found'          => __( 'No services found', 'devhems-child' ),
		'all_items'          => __( 'All Services', 'devhems-child' ),
		'menu_name'          => __( 'Services', 'devhems-child' ),
	);

	register_post_type(
		'service',
		array(
			'labels'             => $labels,
			'public'             => true,
			'has_archive'        => 'services',
			'rewrite'            => array( 'slug' => 'services', 'with_front' => false ),
			'menu_icon'          => 'dashicons-admin-tools',
			'menu_position'      => 20,
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'page-attributes' ),
			'show_in_rest'       => true,
			'hierarchical'       => false,
			'template'           => array(),
		)
	);
}
add_action( 'init', 'devhems_register_cpt_service' );
