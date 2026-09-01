<?php
/**
 * JSON-LD structured data: Organization/ProfessionalService, WebSite,
 * WebPage, Service, Article and JobPosting. FAQPage schema for the FAQ
 * repeater fields is emitted inline by the reusable FAQ accordion (built
 * in Elementor) using Rank Math/Yoast's own FAQ block/widget where
 * possible; the fallback here only fires if neither SEO plugin is active,
 * to avoid duplicate schema.
 */

defined( 'ABSPATH' ) || exit;

function devhems_seo_plugin_active() {
	return defined( 'RANK_MATH_VERSION' ) || defined( 'WPSEO_VERSION' );
}

function devhems_organization_schema() {
	return array(
		'@context'    => 'https://schema.org',
		'@type'       => 'ProfessionalService',
		'name'        => get_bloginfo( 'name' ),
		'url'         => home_url( '/' ),
		'logo'        => get_theme_mod( 'custom_logo' ) ? wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) : '',
		'sameAs'      => apply_filters( 'devhems_social_profiles', array() ),
		'description' => get_bloginfo( 'description' ),
	);
}

function devhems_website_schema() {
	return array(
		'@context'        => 'https://schema.org',
		'@type'           => 'WebSite',
		'name'            => get_bloginfo( 'name' ),
		'url'             => home_url( '/' ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => home_url( '/?s={search_term_string}' ),
			'query-input' => 'required name=search_term_string',
		),
	);
}

function devhems_service_schema( $post_id ) {
	return array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Service',
		'name'        => get_the_title( $post_id ),
		'description' => function_exists( 'get_field' ) ? get_field( 'short_description', $post_id ) : get_the_excerpt( $post_id ),
		'url'         => get_permalink( $post_id ),
		'provider'    => array(
			'@type' => 'ProfessionalService',
			'name'  => get_bloginfo( 'name' ),
			'url'   => home_url( '/' ),
		),
	);
}

function devhems_article_schema( $post_id ) {
	return array(
		'@context'      => 'https://schema.org',
		'@type'         => 'Article',
		'headline'      => get_the_title( $post_id ),
		'datePublished' => get_the_date( 'c', $post_id ),
		'dateModified'  => get_the_modified_date( 'c', $post_id ),
		'author'        => array(
			'@type' => 'Person',
			'name'  => get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ),
		),
		'image'         => get_the_post_thumbnail_url( $post_id, 'full' ),
		'mainEntityOfPage' => get_permalink( $post_id ),
	);
}

function devhems_job_posting_schema( $post_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return null;
	}

	$deadline = get_field( 'application_deadline', $post_id );
	$status   = get_field( 'job_status', $post_id );

	return array(
		'@context'            => 'https://schema.org',
		'@type'               => 'JobPosting',
		'title'               => get_the_title( $post_id ),
		'description'         => wp_strip_all_tags( get_the_content( null, false, $post_id ) ),
		'datePosted'          => get_the_date( 'c', $post_id ),
		'validThrough'        => $deadline ? gmdate( 'c', strtotime( $deadline ) ) : null,
		'employmentType'      => strtoupper( str_replace( '-', '_', (string) get_field( 'employment_type', $post_id ) ) ),
		'hiringOrganization'  => array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'sameAs' => home_url( '/' ),
		),
		'jobLocation'         => array(
			'@type'   => 'Place',
			'address' => get_field( 'job_location', $post_id ),
		),
		'validThroughFallbackClosed' => ( 'closed' === $status ),
	);
}

/**
 * Output the appropriate schema block(s) in wp_head, only when the active
 * SEO plugin doesn't already provide equivalent schema for the entity type.
 */
function devhems_output_schema() {
	$graphs = array();

	// Organization/WebSite/WebPage schema is left to Rank Math or Yoast when
	// either is active, to avoid emitting duplicate structured data — only
	// the custom-post-type schema types below (Service, Article, JobPosting)
	// aren't covered by those plugins automatically.
	if ( ! devhems_seo_plugin_active() ) {
		$graphs[] = is_front_page() ? devhems_website_schema() : null;
		$graphs[] = devhems_organization_schema();
	}

	if ( is_singular( 'service' ) ) {
		$graphs[] = devhems_service_schema( get_the_ID() );
	} elseif ( is_singular( 'post' ) ) {
		$graphs[] = devhems_article_schema( get_the_ID() );
	} elseif ( is_singular( 'career' ) ) {
		$job = devhems_job_posting_schema( get_the_ID() );
		if ( $job ) {
			unset( $job['validThroughFallbackClosed'] );
			$graphs[] = $job;
		}
	}

	$graphs = array_filter( $graphs );

	foreach ( $graphs as $graph ) {
		echo '<script type="application/ld+json">' . wp_json_encode( $graph ) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'devhems_output_schema', 30 );
