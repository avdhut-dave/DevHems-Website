<?php
/**
 * Lightweight performance hardening: strip WordPress bloat that Hello
 * Elementor doesn't already remove, lazy-load below-the-fold media, and
 * avoid render-blocking extras. Full-page caching, image compression to
 * WebP/AVIF and minification are handled by the caching/optimization
 * plugin chosen at hosting setup (e.g. WP Rocket, LiteSpeed Cache) — this
 * file only covers what's appropriate to hardcode in the theme.
 */

defined( 'ABSPATH' ) || exit;

// Remove emoji script/styles — no emoji usage on this site.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// Remove default WP head clutter that adds no SEO/functional value.
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'feed_links_extra', 3 );

// Disable oEmbed discovery — not used anywhere in the design.
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

// Disable the block-library and global-styles CSS Hello Elementor + Elementor
// don't need (Gutenberg is not used for building pages on this site).
function devhems_dequeue_block_assets() {
	if ( is_admin() ) {
		return;
	}
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'devhems_dequeue_block_assets', 100 );

// Limit heartbeat API to the post editor screen only.
function devhems_control_heartbeat( $settings ) {
	$settings['interval'] = 60;
	return $settings;
}
add_filter( 'heartbeat_settings', 'devhems_control_heartbeat' );

function devhems_disable_heartbeat_frontend() {
	if ( ! is_admin() ) {
		wp_deregister_script( 'heartbeat' );
	}
}
add_action( 'init', 'devhems_disable_heartbeat_frontend', 1 );

/**
 * Native lazy-loading for images/iframes below the fold. WordPress core
 * already adds loading="lazy" to content images from 5.5+; this makes sure
 * it also applies to Elementor-rendered images and iframes (maps, video
 * embeds) that don't go through the_content filters.
 */
function devhems_lazyload_iframes( $html, $post_id, $post_image_id ) {
	if ( false === strpos( $html, 'loading=' ) ) {
		$html = str_replace( '<img ', '<img loading="lazy" decoding="async" ', $html );
	}
	return $html;
}
add_filter( 'post_thumbnail_html', 'devhems_lazyload_iframes', 10, 3 );

function devhems_lazyload_generic_iframes( $content ) {
	if ( is_admin() || empty( $content ) ) {
		return $content;
	}
	return preg_replace( '/<iframe(?![^>]*loading=)/i', '<iframe loading="lazy"', $content );
}
add_filter( 'the_content', 'devhems_lazyload_generic_iframes', 20 );

/**
 * Prevent Cumulative Layout Shift on the fixed/sticky header by exposing its
 * measured height as a CSS custom property, consumed by mega-menu.css to pad
 * <body> instead of guessing a fixed value.
 */
function devhems_header_height_css_var() {
	echo '<style id="devhems-header-offset">:root{--dh-header-height:88px;}</style>';
}
add_action( 'wp_head', 'devhems_header_height_css_var', 1 );

// Limit post revisions and disable autosave spam on large content types.
if ( ! defined( 'WP_POST_REVISIONS' ) ) {
	define( 'WP_POST_REVISIONS', 5 );
}
