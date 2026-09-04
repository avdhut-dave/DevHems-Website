<?php
/**
 * Services Listing page fallback template.
 * Prefer building this visually in Elementor Pro Theme Builder
 * (condition: Archive = Service) with the reusable service card component
 * and a Loop Grid widget filtered by Service Category — see
 * elementor-templates/THEME-BUILDER-GUIDE.md for the exact widget setup.
 */

defined( 'ABSPATH' ) || exit;
get_header();

$categories = get_terms( array( 'taxonomy' => 'service_category', 'hide_empty' => true ) );
?>

<?php
get_template_part( 'template-parts/page-banner', null, array(
	'title'   => __( 'Our Services', 'devhems-child' ),
	'support' => __( 'Seven disciplines. One accountable team. Zero vendor hand-offs.', 'devhems-child' ),
) );
?>

<main id="content" tabindex="-1">
	<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
	<nav class="devhems-service-filter" aria-label="<?php esc_attr_e( 'Filter services by category', 'devhems-child' ); ?>">
		<a href="<?php echo esc_url( get_post_type_archive_link( 'service' ) ); ?>" class="devhems-filter-pill<?php echo ( ! is_tax() ) ? ' is-active' : ''; ?>">
			<?php esc_html_e( 'All Services', 'devhems-child' ); ?>
		</a>
		<?php foreach ( $categories as $category ) : ?>
			<a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="devhems-filter-pill<?php echo is_tax( 'service_category', $category->term_id ) ? ' is-active' : ''; ?>">
				<?php echo esc_html( $category->name ); ?>
			</a>
		<?php endforeach; ?>
	</nav>
	<?php endif; ?>

	<div class="devhems-card-grid">
		<?php while ( have_posts() ) : the_post(); ?>
			<a class="devhems-service-card" href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'devhems-card', array( 'loading' => 'lazy' ) ); ?>
				<h2><?php the_title(); ?></h2>
				<p><?php echo esc_html( devhems_get_service_short_description() ); ?></p>
				<span class="devhems-arrow" aria-hidden="true">&rarr;</span>
			</a>
		<?php endwhile; ?>
	</div>

	<?php the_posts_pagination(); ?>
</main>

<?php get_template_part( 'template-parts/bottom-cta-banner' ); ?>
<?php get_footer(); ?>
