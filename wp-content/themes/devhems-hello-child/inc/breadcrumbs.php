<?php
/**
 * Lightweight breadcrumb trail. If Rank Math or Yoast's own breadcrumb
 * feature is enabled, prefer that (it also emits BreadcrumbList schema);
 * this shortcode is a theme-native fallback for use in Elementor templates
 * via the Shortcode widget: [devhems_breadcrumbs].
 */

defined( 'ABSPATH' ) || exit;

function devhems_breadcrumbs_shortcode() {
	if ( is_front_page() ) {
		return '';
	}

	$trail = array(
		array( 'label' => __( 'Home', 'devhems-child' ), 'url' => home_url( '/' ) ),
	);

	if ( is_singular( 'service' ) ) {
		$trail[] = array( 'label' => __( 'Services', 'devhems-child' ), 'url' => get_post_type_archive_link( 'service' ) );
		$trail[] = array( 'label' => get_the_title(), 'url' => '' );
	} elseif ( is_post_type_archive( 'service' ) ) {
		$trail[] = array( 'label' => __( 'Services', 'devhems-child' ), 'url' => '' );
	} elseif ( is_singular( 'case_study' ) ) {
		$trail[] = array( 'label' => __( 'Portfolio', 'devhems-child' ), 'url' => get_post_type_archive_link( 'case_study' ) );
		$trail[] = array( 'label' => get_the_title(), 'url' => '' );
	} elseif ( is_post_type_archive( 'case_study' ) ) {
		$trail[] = array( 'label' => __( 'Portfolio', 'devhems-child' ), 'url' => '' );
	} elseif ( is_singular( 'career' ) ) {
		$trail[] = array( 'label' => __( 'Careers', 'devhems-child' ), 'url' => get_post_type_archive_link( 'career' ) );
		$trail[] = array( 'label' => get_the_title(), 'url' => '' );
	} elseif ( is_post_type_archive( 'career' ) ) {
		$trail[] = array( 'label' => __( 'Careers', 'devhems-child' ), 'url' => '' );
	} elseif ( is_singular( 'post' ) ) {
		$trail[] = array( 'label' => __( 'Blog', 'devhems-child' ), 'url' => get_permalink( get_option( 'page_for_posts' ) ) );
		$trail[] = array( 'label' => get_the_title(), 'url' => '' );
	} elseif ( is_home() ) {
		$trail[] = array( 'label' => __( 'Blog', 'devhems-child' ), 'url' => '' );
	} elseif ( is_page() ) {
		$trail[] = array( 'label' => get_the_title(), 'url' => '' );
	}

	$items = array();
	$html  = '<nav class="devhems-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'devhems-child' ) . '"><ol>';

	foreach ( $trail as $i => $crumb ) {
		$is_last = ( $i === count( $trail ) - 1 );
		$items[] = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => wp_strip_all_tags( $crumb['label'] ),
			'item'     => $crumb['url'] ? $crumb['url'] : get_permalink(),
		);

		if ( $is_last || empty( $crumb['url'] ) ) {
			$html .= '<li aria-current="page">' . esc_html( $crumb['label'] ) . '</li>';
		} else {
			$html .= '<li><a href="' . esc_url( $crumb['url'] ) . '">' . esc_html( $crumb['label'] ) . '</a></li>';
		}
	}
	$html .= '</ol></nav>';

	// Only emit BreadcrumbList schema here if Rank Math/Yoast aren't already
	// doing it, to avoid duplicate structured data.
	if ( ! devhems_seo_plugin_active() ) {
		$html .= '<script type="application/ld+json">' . wp_json_encode( array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $items,
		) ) . '</script>';
	}

	return $html;
}
add_shortcode( 'devhems_breadcrumbs', 'devhems_breadcrumbs_shortcode' );
