<?php
/**
 * "Testimonial" custom post type. Client name, company, designation,
 * testimonial text and photo/logo live in ACF (inc/acf-fields.php); the
 * post title is used internally (e.g. "Acme Corp — Jane Doe") and is not
 * displayed on the front end.
 */

defined( 'ABSPATH' ) || exit;

function devhems_register_cpt_testimonial() {
	$labels = array(
		'name'          => __( 'Testimonials', 'devhems-child' ),
		'singular_name' => __( 'Testimonial', 'devhems-child' ),
		'add_new_item'  => __( 'Add New Testimonial', 'devhems-child' ),
		'edit_item'     => __( 'Edit Testimonial', 'devhems-child' ),
		'new_item'      => __( 'New Testimonial', 'devhems-child' ),
		'search_items'  => __( 'Search Testimonials', 'devhems-child' ),
		'not_found'     => __( 'No testimonials found', 'devhems-child' ),
		'all_items'     => __( 'All Testimonials', 'devhems-child' ),
		'menu_name'     => __( 'Testimonials', 'devhems-child' ),
	);

	register_post_type(
		'testimonial',
		array(
			'labels'        => $labels,
			'public'        => false,
			'publicly_queryable' => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_icon'     => 'dashicons-format-quote',
			'menu_position' => 23,
			'supports'      => array( 'title', 'revisions' ),
			'show_in_rest'  => true,
		)
	);
}
add_action( 'init', 'devhems_register_cpt_testimonial' );
