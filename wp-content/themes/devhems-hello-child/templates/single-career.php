<?php
/**
 * Fallback template for the Career (Job) Detail page.
 * Prefer building this visually in Elementor Pro Theme Builder
 * (condition: Post Type = Career) when a license is available.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php
	$status     = get_field( 'job_status' );
	$department = get_the_terms( get_the_ID(), 'department' );

	$meta_bits = array_filter( array(
		( $department && ! is_wp_error( $department ) ) ? $department[0]->name : '',
		get_field( 'job_location' ),
		get_field( 'employment_type' ) ? ucwords( str_replace( '-', ' ', get_field( 'employment_type' ) ) ) : '',
		get_field( 'experience_required' ),
	) );

	get_template_part( 'template-parts/page-banner', null, array(
		'title'   => get_the_title(),
		'support' => implode( ' · ', $meta_bits ),
	) );
	?>

<main id="content" tabindex="-1">

	<?php if ( 'closed' === $status ) : ?>
		<p class="devhems-job-closed-notice"><?php esc_html_e( 'This position is no longer accepting applications.', 'devhems-child' ); ?></p>
	<?php endif; ?>

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
