<?php
/**
 * Careers listing fallback template. Closed jobs are already excluded from
 * the main query by devhems_filter_expired_careers() in
 * inc/post-types-careers.php. The "Why work here" perks list is generic,
 * site-wide copy (filterable) rather than a per-post ACF field, same
 * pattern as template-parts/why-choose-devhems.php.
 *
 * Prefer building this visually in Elementor Pro Theme Builder (condition:
 * Archive = Career) if a Pro license is available — see
 * elementor-templates/THEME-BUILDER-GUIDE.md.
 */

defined( 'ABSPATH' ) || exit;
get_header();

$departments = get_terms( array( 'taxonomy' => 'department', 'hide_empty' => true ) );

$perks = apply_filters( 'devhems_careers_perks', array(
	array( 'title' => __( 'Remote-Friendly', 'devhems-child' ), 'description' => __( 'Work from anywhere with flexible hours built around outcomes, not clock-watching.', 'devhems-child' ) ),
	array( 'title' => __( 'Growth Budget', 'devhems-child' ), 'description' => __( 'Annual learning stipend for courses, certifications and conferences.', 'devhems-child' ) ),
	array( 'title' => __( 'Health Coverage', 'devhems-child' ), 'description' => __( 'Comprehensive health insurance for you and your family.', 'devhems-child' ) ),
	array( 'title' => __( 'Real Ownership', 'devhems-child' ), 'description' => __( 'Work directly with clients and see your work ship — no bureaucracy.', 'devhems-child' ) ),
) );
?>

<main id="content" tabindex="-1">
	<?php echo do_shortcode( '[devhems_breadcrumbs]' ); ?>

	<header class="devhems-archive-header">
		<h1><?php esc_html_e( 'Careers at DevHems Technology', 'devhems-child' ); ?></h1>
		<p><?php esc_html_e( "We're a full-stack digital team solving real growth problems for real clients. If you'd rather build things that ship than sit in process, you'll fit right in.", 'devhems-child' ); ?></p>
	</header>

	<section class="devhems-careers-perks">
		<h2><?php esc_html_e( 'Why Work With Us', 'devhems-child' ); ?></h2>
		<div class="devhems-card-grid">
			<?php foreach ( $perks as $perk ) : ?>
				<div class="devhems-benefit-card">
					<h3><?php echo esc_html( $perk['title'] ); ?></h3>
					<p><?php echo esc_html( $perk['description'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="devhems-open-positions">
		<h2><?php esc_html_e( 'Open Positions', 'devhems-child' ); ?></h2>

		<?php if ( ! empty( $departments ) && ! is_wp_error( $departments ) ) : ?>
		<nav class="devhems-service-filter" aria-label="<?php esc_attr_e( 'Filter jobs by department', 'devhems-child' ); ?>">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'career' ) ); ?>" class="devhems-filter-pill<?php echo ( ! is_tax() ) ? ' is-active' : ''; ?>">
				<?php esc_html_e( 'All Departments', 'devhems-child' ); ?>
			</a>
			<?php foreach ( $departments as $dept ) : ?>
				<a href="<?php echo esc_url( get_term_link( $dept ) ); ?>" class="devhems-filter-pill<?php echo is_tax( 'department', $dept->term_id ) ? ' is-active' : ''; ?>">
					<?php echo esc_html( $dept->name ); ?>
				</a>
			<?php endforeach; ?>
		</nav>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<ul class="devhems-job-list">
				<?php while ( have_posts() ) : the_post(); ?>
					<li class="devhems-job-list-item">
						<a href="<?php the_permalink(); ?>">
							<h3><?php the_title(); ?></h3>
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
			<p><?php esc_html_e( "There are no open positions right now — check back soon, or send us your resume anyway.", 'devhems-child' ); ?></p>
		<?php endif; ?>
	</section>

	<section class="devhems-final-cta">
		<h2><?php esc_html_e( "Don't see the right role?", 'devhems-child' ); ?></h2>
		<a class="devhems-header-cta" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Get in Touch', 'devhems-child' ); ?></a>
	</section>
</main>

<?php get_footer(); ?>
