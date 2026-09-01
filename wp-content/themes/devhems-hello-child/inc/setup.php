<?php
/**
 * Theme support, nav menus, skip link, image sizes.
 */

defined( 'ABSPATH' ) || exit;

function devhems_theme_setup() {
	load_child_theme_textdomain( 'devhems-child', DEVHEMS_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );

	// Registered in addition to Hello Elementor's own locations so Elementor's
	// header/footer templates and the WP_Nav_Menu mega-menu walker both work.
	register_nav_menus(
		array(
			'primary-menu' => __( 'Primary Mega Menu', 'devhems-child' ),
			'footer-menu'  => __( 'Footer Menu', 'devhems-child' ),
			'legal-menu'   => __( 'Legal / Bottom Bar Menu', 'devhems-child' ),
		)
	);

	// Image sizes used by service/case-study/career/testimonial cards.
	add_image_size( 'devhems-card', 640, 420, true );
	add_image_size( 'devhems-hero', 1600, 800, true );
	add_image_size( 'devhems-thumb', 160, 160, true );
}
add_action( 'after_setup_theme', 'devhems_theme_setup' );

/**
 * Skip-to-content link, output before the Elementor header renders.
 */
function devhems_skip_link() {
	echo '<a class="skip-link" href="#content">' . esc_html__( 'Skip to content', 'devhems-child' ) . '</a>';
}
add_action( 'wp_body_open', 'devhems_skip_link', 5 );
