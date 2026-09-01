<?php
/**
 * SEO scaffolding. Rank Math or Yoast SEO (installed by the admin) remains
 * the source of truth for meta titles/descriptions, canonical URLs, XML
 * sitemap, robots.txt and Open Graph tags on regular pages/posts. This file
 * only:
 *  - provides a minimal fallback if neither plugin is active, so the site
 *    never ships with empty <title>/meta description tags
 *  - bridges the ACF "SEO Information" fields (seo_meta_title etc.) into
 *    Rank Math/Yoast's own filters, so editors can override SEO per Service/
 *    Case Study/Career from the same admin screen the content lives on
 *  - forces noindex via the ACF "seo_noindex" toggle regardless of which
 *    SEO plugin is active
 */

defined( 'ABSPATH' ) || exit;

function devhems_acf_seo_override( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	if ( ! $post_id || ! function_exists( 'get_field' ) ) {
		return array();
	}
	return array(
		'title'       => get_field( 'seo_meta_title', $post_id ),
		'description' => get_field( 'seo_meta_description', $post_id ),
		'og_image_id' => get_field( 'seo_og_image', $post_id ),
		'noindex'     => (bool) get_field( 'seo_noindex', $post_id ),
	);
}

// Rank Math title/description filters.
add_filter( 'rank_math/frontend/title', function ( $title ) {
	$seo = devhems_acf_seo_override();
	return ! empty( $seo['title'] ) ? $seo['title'] : $title;
} );

add_filter( 'rank_math/frontend/description', function ( $description ) {
	$seo = devhems_acf_seo_override();
	return ! empty( $seo['description'] ) ? $seo['description'] : $description;
} );

add_filter( 'rank_math/frontend/robots', function ( $robots ) {
	$seo = devhems_acf_seo_override();
	if ( ! empty( $seo['noindex'] ) ) {
		$robots['index'] = 'noindex';
	}
	return $robots;
} );

// Yoast SEO title/description filters.
add_filter( 'wpseo_title', function ( $title ) {
	$seo = devhems_acf_seo_override();
	return ! empty( $seo['title'] ) ? $seo['title'] : $title;
} );

add_filter( 'wpseo_metadesc', function ( $description ) {
	$seo = devhems_acf_seo_override();
	return ! empty( $seo['description'] ) ? $seo['description'] : $description;
} );

add_filter( 'wpseo_robots', function ( $robots ) {
	$seo = devhems_acf_seo_override();
	if ( ! empty( $seo['noindex'] ) ) {
		return 'noindex, follow';
	}
	return $robots;
} );

/**
 * Minimal fallback meta tags — only fires when neither SEO plugin is
 * active, so local previews / staging without the SEO plugin installed
 * still get a sane <title>, description, canonical and OG tags.
 */
function devhems_fallback_meta_tags() {
	if ( devhems_seo_plugin_active() ) {
		return;
	}

	$seo = devhems_acf_seo_override();

	$title = ! empty( $seo['title'] ) ? $seo['title'] : wp_get_document_title();
	$desc  = ! empty( $seo['description'] ) ? $seo['description'] : get_bloginfo( 'description' );
	$url   = is_singular() ? get_permalink() : home_url( add_query_arg( array(), $GLOBALS['wp']->request ) );

	echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $desc ) ) . '">' . "\n";
	echo '<link rel="canonical" href="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( wp_strip_all_tags( $desc ) ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	echo '<meta property="og:type" content="website">' . "\n";

	if ( ! empty( $seo['og_image_id'] ) ) {
		echo '<meta property="og:image" content="' . esc_url( wp_get_attachment_image_url( $seo['og_image_id'], 'full' ) ) . '">' . "\n";
	} elseif ( has_post_thumbnail() ) {
		echo '<meta property="og:image" content="' . esc_url( get_the_post_thumbnail_url( get_the_ID(), 'full' ) ) . '">' . "\n";
	}

	if ( ! empty( $seo['noindex'] ) ) {
		echo '<meta name="robots" content="noindex, follow">' . "\n";
	}
}
add_action( 'wp_head', 'devhems_fallback_meta_tags', 1 );
