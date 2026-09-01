<?php
/**
 * Reusable "Why Choose DevHems" section, included by the Service, Case
 * Study and Home fallback templates. Content is intentionally generic and
 * site-wide rather than an ACF field on every post type — edit the array
 * below (or move it into a Theme Options page / ACF Options Page if the
 * admin wants it editable without a code change) to update it everywhere
 * at once.
 */

defined( 'ABSPATH' ) || exit;

$devhems_why_us_points = apply_filters( 'devhems_why_choose_us_points', array(
	array(
		'title'       => __( 'Proven Results', 'devhems-child' ),
		'description' => __( 'Data-backed strategies with a track record of measurable growth.', 'devhems-child' ),
	),
	array(
		'title'       => __( 'Full-Stack Team', 'devhems-child' ),
		'description' => __( 'Strategy, design, development and marketing under one roof.', 'devhems-child' ),
	),
	array(
		'title'       => __( 'Transparent Reporting', 'devhems-child' ),
		'description' => __( 'Live dashboards and monthly reviews — no black boxes.', 'devhems-child' ),
	),
	array(
		'title'       => __( 'Fast Turnaround', 'devhems-child' ),
		'description' => __( 'Agile delivery without compromising on quality or accessibility.', 'devhems-child' ),
	),
) );
?>
<section class="devhems-why-us">
	<h2><?php esc_html_e( 'Why Choose DevHems Technology', 'devhems-child' ); ?></h2>
	<div class="devhems-card-grid">
		<?php foreach ( $devhems_why_us_points as $point ) : ?>
			<div class="devhems-benefit-card">
				<h3><?php echo esc_html( $point['title'] ); ?></h3>
				<p><?php echo esc_html( $point['description'] ); ?></p>
			</div>
		<?php endforeach; ?>
	</div>
</section>
