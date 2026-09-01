<?php
/**
 * Careers listing fallback template. Closed jobs are already excluded from
 * the main query by devhems_filter_expired_careers() in
 * inc/post-types-careers.php.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<main id="content" tabindex="-1">
	<?php echo do_shortcode( '[devhems_breadcrumbs]' ); ?>

	<header class="devhems-archive-header">
		<h1><?php esc_html_e( 'Careers at DevHems Technology', 'devhems-child' ); ?></h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<ul class="devhems-job-list">
			<?php while ( have_posts() ) : the_post(); ?>
				<li class="devhems-job-list-item">
					<a href="<?php the_permalink(); ?>">
						<h2><?php the_title(); ?></h2>
						<span class="devhems-job-list-meta">
							<?php
							$meta = array_filter( array(
								get_field( 'job_location' ),
								get_field( 'employment_type' ) ? ucwords( str_replace( '-', ' ', get_field( 'employment_type' ) ) ) : '',
								get_field( 'experience_required' ),
							) );
							echo esc_html( implode( ' · ', $meta ) );
							?>
						</span>
						<span class="devhems-arrow" aria-hidden="true">&rarr;</span>
					</a>
				</li>
			<?php endwhile; ?>
		</ul>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'There are no open positions right now — check back soon.', 'devhems-child' ); ?></p>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
