<?php
/**
 * Portfolio / Case Studies listing fallback template.
 * Prefer building this visually in Elementor Pro Theme Builder (condition:
 * Archive = Case Study) if a Pro license is available — see
 * elementor-templates/THEME-BUILDER-GUIDE.md.
 */

defined( 'ABSPATH' ) || exit;
get_header();

$industries = get_terms( array( 'taxonomy' => 'industry', 'hide_empty' => true ) );
?>

<main id="content" tabindex="-1">
	<?php echo do_shortcode( '[devhems_breadcrumbs]' ); ?>

	<header class="devhems-archive-header">
		<h1><?php esc_html_e( 'Case Studies', 'devhems-child' ); ?></h1>
		<p><?php esc_html_e( 'Real projects, real results — see how we\'ve helped businesses across industries grow with websites, marketing and automation.', 'devhems-child' ); ?></p>
	</header>

	<?php if ( ! empty( $industries ) && ! is_wp_error( $industries ) ) : ?>
	<nav class="devhems-service-filter" aria-label="<?php esc_attr_e( 'Filter case studies by industry', 'devhems-child' ); ?>">
		<a href="<?php echo esc_url( get_post_type_archive_link( 'case_study' ) ); ?>" class="devhems-filter-pill<?php echo ( ! is_tax() ) ? ' is-active' : ''; ?>">
			<?php esc_html_e( 'All Industries', 'devhems-child' ); ?>
		</a>
		<?php foreach ( $industries as $industry ) : ?>
			<a href="<?php echo esc_url( get_term_link( $industry ) ); ?>" class="devhems-filter-pill<?php echo is_tax( 'industry', $industry->term_id ) ? ' is-active' : ''; ?>">
				<?php echo esc_html( $industry->name ); ?>
			</a>
		<?php endforeach; ?>
	</nav>
	<?php endif; ?>

	<div class="devhems-card-grid">
		<?php while ( have_posts() ) : the_post(); ?>
			<a class="devhems-case-study-card" href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'devhems-card', array( 'loading' => 'lazy' ) ); ?>
				<h2><?php the_title(); ?></h2>
				<?php $client = get_field( 'client_name' ); ?>
				<?php if ( $client ) : ?>
					<p><?php echo esc_html( $client ); ?></p>
				<?php endif; ?>
				<span class="devhems-arrow" aria-hidden="true">&rarr;</span>
			</a>
		<?php endwhile; ?>
	</div>

	<?php the_posts_pagination(); ?>

	<section class="devhems-final-cta">
		<h2><?php esc_html_e( 'Want results like these for your business?', 'devhems-child' ); ?></h2>
		<a class="devhems-header-cta" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Start Your Project', 'devhems-child' ); ?></a>
	</section>
</main>

<?php get_footer(); ?>
