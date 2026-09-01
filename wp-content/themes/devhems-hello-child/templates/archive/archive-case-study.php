<?php
/**
 * Portfolio / Case Studies listing fallback template.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<main id="content" tabindex="-1">
	<?php echo do_shortcode( '[devhems_breadcrumbs]' ); ?>

	<header class="devhems-archive-header">
		<h1><?php esc_html_e( 'Case Studies', 'devhems-child' ); ?></h1>
	</header>

	<div class="devhems-card-grid">
		<?php while ( have_posts() ) : the_post(); ?>
			<a class="devhems-case-study-card" href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'devhems-card', array( 'loading' => 'lazy' ) ); ?>
				<h2><?php the_title(); ?></h2>
				<?php $client = get_field( 'client_name' ); ?>
				<?php if ( $client ) : ?>
					<p><?php echo esc_html( $client ); ?></p>
				<?php endif; ?>
			</a>
		<?php endwhile; ?>
	</div>

	<?php the_posts_pagination(); ?>
</main>

<?php get_footer(); ?>
