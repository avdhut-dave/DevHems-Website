<?php
/**
 * Fallback template for the Individual Case Study page.
 * Prefer building this visually in Elementor Pro Theme Builder
 * (condition: Post Type = Case Study) when a license is available.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<main id="content" tabindex="-1">
<?php while ( have_posts() ) : the_post(); ?>

	<?php echo do_shortcode( '[devhems_breadcrumbs]' ); ?>

	<section class="devhems-case-study-hero">
		<h1><?php the_title(); ?></h1>
		<?php $client = get_field( 'client_name' ); ?>
		<?php if ( $client ) : ?>
			<p class="devhems-case-study-client"><?php echo esc_html( $client ); ?></p>
		<?php endif; ?>
		<?php echo get_the_post_thumbnail( get_the_ID(), 'devhems-hero', array( 'loading' => 'eager' ) ); ?>
	</section>

	<div class="devhems-case-study-body">
		<?php $challenge = get_field( 'challenge' ); ?>
		<?php if ( $challenge ) : ?>
			<section>
				<h2><?php esc_html_e( 'The Challenge', 'devhems-child' ); ?></h2>
				<?php echo wp_kses_post( $challenge ); ?>
			</section>
		<?php endif; ?>

		<?php $solution = get_field( 'solution' ); ?>
		<?php if ( $solution ) : ?>
			<section>
				<h2><?php esc_html_e( 'Our Solution', 'devhems-child' ); ?></h2>
				<?php echo wp_kses_post( $solution ); ?>
			</section>
		<?php endif; ?>

		<?php $results = get_field( 'results' ); ?>
		<?php if ( $results ) : ?>
			<section class="devhems-case-study-results">
				<h2><?php esc_html_e( 'Results', 'devhems-child' ); ?></h2>
				<div class="devhems-stat-grid">
					<?php foreach ( $results as $result ) : ?>
						<div class="devhems-stat">
							<span class="devhems-stat-value"><?php echo esc_html( $result['value'] ); ?></span>
							<span class="devhems-stat-label"><?php echo esc_html( $result['label'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php $gallery = get_field( 'gallery' ); ?>
		<?php if ( $gallery ) : ?>
			<section class="devhems-case-study-gallery">
				<div class="devhems-gallery-grid">
					<?php foreach ( $gallery as $image_id ) : ?>
						<?php echo wp_get_attachment_image( $image_id, 'devhems-card', false, array( 'loading' => 'lazy' ) ); ?>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

		<?php $testimonial_post = get_field( 'testimonial' ); ?>
		<?php if ( $testimonial_post ) : ?>
			<section class="devhems-case-study-testimonial">
				<blockquote>
					<p><?php echo esc_html( get_field( 'testimonial_text', $testimonial_post ) ); ?></p>
					<footer>
						<?php echo esc_html( get_field( 'client_name', $testimonial_post ) ); ?>,
						<?php echo esc_html( get_field( 'designation', $testimonial_post ) ); ?>,
						<?php echo esc_html( get_field( 'company', $testimonial_post ) ); ?>
					</footer>
				</blockquote>
			</section>
		<?php endif; ?>

		<?php $related_services = get_field( 'related_services' ); ?>
		<?php if ( $related_services ) : ?>
			<section class="devhems-related-services">
				<h2><?php esc_html_e( 'Related Services', 'devhems-child' ); ?></h2>
				<div class="devhems-card-grid">
					<?php foreach ( $related_services as $related ) : ?>
						<a class="devhems-service-card" href="<?php echo esc_url( get_permalink( $related ) ); ?>">
							<h3><?php echo esc_html( get_the_title( $related ) ); ?></h3>
						</a>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>
	</div>

	<section class="devhems-final-cta">
		<h2><?php esc_html_e( 'Want results like this for your business?', 'devhems-child' ); ?></h2>
		<a class="devhems-header-cta" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Start Your Project', 'devhems-child' ); ?></a>
	</section>

<?php endwhile; ?>
</main>

<?php get_footer(); ?>
