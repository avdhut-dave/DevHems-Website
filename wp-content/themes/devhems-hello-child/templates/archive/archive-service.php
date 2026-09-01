<?php
/**
 * Services Listing page fallback template.
 * Prefer building this visually in Elementor Pro Theme Builder
 * (condition: Archive = Service) with the reusable service card component.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<main id="content" tabindex="-1">
	<?php echo do_shortcode( '[devhems_breadcrumbs]' ); ?>

	<header class="devhems-archive-header">
		<h1><?php esc_html_e( 'Our Services', 'devhems-child' ); ?></h1>
	</header>

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

<?php get_footer(); ?>
