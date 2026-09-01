<?php
/**
 * Fallback template for the Career (Job) Detail page.
 * Prefer building this visually in Elementor Pro Theme Builder
 * (condition: Post Type = Career) when a license is available.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<main id="content" tabindex="-1">
<?php while ( have_posts() ) : the_post(); ?>

	<?php echo do_shortcode( '[devhems_breadcrumbs]' ); ?>

	<?php
	$status     = get_field( 'job_status' );
	$department = get_the_terms( get_the_ID(), 'department' );
	?>

	<section class="devhems-job-hero">
		<h1><?php the_title(); ?></h1>
		<ul class="devhems-job-meta">
			<?php if ( $department && ! is_wp_error( $department ) ) : ?>
				<li><?php echo esc_html( $department[0]->name ); ?></li>
			<?php endif; ?>
			<?php if ( get_field( 'job_location' ) ) : ?>
				<li><?php echo esc_html( get_field( 'job_location' ) ); ?></li>
			<?php endif; ?>
			<?php if ( get_field( 'employment_type' ) ) : ?>
				<li><?php echo esc_html( ucwords( str_replace( '-', ' ', get_field( 'employment_type' ) ) ) ); ?></li>
			<?php endif; ?>
			<?php if ( get_field( 'experience_required' ) ) : ?>
				<li><?php echo esc_html( get_field( 'experience_required' ) ); ?></li>
			<?php endif; ?>
		</ul>
		<?php if ( 'closed' === $status ) : ?>
			<p class="devhems-job-closed-notice"><?php esc_html_e( 'This position is no longer accepting applications.', 'devhems-child' ); ?></p>
		<?php endif; ?>
	</section>

	<div class="devhems-job-body">
		<section>
			<h2><?php esc_html_e( 'Job Description', 'devhems-child' ); ?></h2>
			<?php the_content(); ?>
		</section>

		<?php $responsibilities = get_field( 'responsibilities' ); ?>
		<?php if ( $responsibilities ) : ?>
			<section>
				<h2><?php esc_html_e( 'Responsibilities', 'devhems-child' ); ?></h2>
				<?php echo wp_kses_post( $responsibilities ); ?>
			</section>
		<?php endif; ?>

		<?php $requirements = get_field( 'requirements' ); ?>
		<?php if ( $requirements ) : ?>
			<section>
				<h2><?php esc_html_e( 'Requirements', 'devhems-child' ); ?></h2>
				<?php echo wp_kses_post( $requirements ); ?>
			</section>
		<?php endif; ?>

		<?php $deadline = get_field( 'application_deadline' ); ?>
		<?php if ( $deadline ) : ?>
			<p class="devhems-job-deadline">
				<?php
				printf(
					/* translators: %s: formatted application deadline date */
					esc_html__( 'Application deadline: %s', 'devhems-child' ),
					esc_html( date_i18n( get_option( 'date_format' ), strtotime( $deadline ) ) )
				);
				?>
			</p>
		<?php endif; ?>
	</div>

	<?php if ( 'closed' !== $status ) : ?>
	<section class="devhems-job-application">
		<h2><?php esc_html_e( 'Apply for this Position', 'devhems-child' ); ?></h2>
		<?php echo do_shortcode( '[devhems_career_application_form]' ); ?>
	</section>
	<?php endif; ?>

<?php endwhile; ?>
</main>

<?php get_footer(); ?>
