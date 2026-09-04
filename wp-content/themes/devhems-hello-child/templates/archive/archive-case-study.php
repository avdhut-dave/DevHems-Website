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

<?php
get_template_part( 'template-parts/page-banner', null, array(
	'title'   => __( 'Case Studies', 'devhems-child' ),
	'support' => __( 'Real projects, real results — see how we\'ve helped businesses across industries grow with websites, marketing and automation.', 'devhems-child' ),
) );
?>

<main id="content" tabindex="-1">
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
</main>

<?php get_template_part( 'template-parts/bottom-cta-banner' ); ?>
<?php get_footer(); ?>
