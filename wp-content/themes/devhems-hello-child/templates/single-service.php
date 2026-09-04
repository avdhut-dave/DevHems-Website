<?php
/**
 * Fallback template for the Service Detail page.
 *
 * If Elementor Pro is licensed, build this template visually instead via
 * Templates > Theme Builder > Single Post (condition: Post Type = Service)
 * using the reusable Elementor components (CTA section, service cards,
 * FAQ accordion, service enquiry form) — Elementor's own theme-builder
 * template takes priority over this file automatically. This file only
 * renders when no matching Elementor theme-builder template exists.
 *
 * Sections, per the spec:
 * breadcrumbs, hero, overview, problems addressed, services included,
 * benefits, process, technologies, related case studies, why DevHems,
 * FAQs, related services, service enquiry form, final CTA.
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<?php
	get_template_part( 'template-parts/page-banner', null, array(
		'title'   => get_the_title(),
		'support' => get_field( 'hero_subtitle' ) ?: devhems_get_service_short_description(),
	) );
	?>

<main id="content" tabindex="-1">

	<section class="devhems-service-overview">
		<?php the_content(); ?>
	</section>

	<?php $problems = get_field( 'problems_addressed' ); ?>
	<?php if ( $problems ) : ?>
	<section class="devhems-service-problems">
		<h2><?php esc_html_e( 'Business Problems We Solve', 'devhems-child' ); ?></h2>
		<div class="devhems-card-grid">
			<?php foreach ( $problems as $problem ) : ?>
				<div class="devhems-benefit-card">
					<h3><?php echo esc_html( $problem['title'] ); ?></h3>
					<p><?php echo esc_html( $problem['description'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php $included = get_field( 'services_included' ); ?>
	<?php if ( $included ) : ?>
	<section class="devhems-service-included">
		<h2><?php esc_html_e( 'What\'s Included', 'devhems-child' ); ?></h2>
		<div class="devhems-card-grid">
			<?php foreach ( $included as $item ) : ?>
				<div class="devhems-benefit-card">
					<h3><?php echo esc_html( $item['title'] ); ?></h3>
					<p><?php echo esc_html( $item['description'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php $benefits = get_field( 'benefits' ); ?>
	<?php if ( $benefits ) : ?>
	<section class="devhems-service-benefits">
		<h2><?php esc_html_e( 'Benefits', 'devhems-child' ); ?></h2>
		<div class="devhems-card-grid">
			<?php foreach ( $benefits as $benefit ) : ?>
				<div class="devhems-benefit-card">
					<?php if ( ! empty( $benefit['icon'] ) ) : ?>
						<?php echo wp_get_attachment_image( $benefit['icon'], 'thumbnail', false, array( 'loading' => 'lazy', 'alt' => esc_attr( $benefit['title'] ) ) ); ?>
					<?php endif; ?>
					<h3><?php echo esc_html( $benefit['title'] ); ?></h3>
					<p><?php echo esc_html( $benefit['description'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php $steps = get_field( 'process_steps' ); ?>
	<?php if ( $steps ) : ?>
	<section class="devhems-service-process">
		<h2><?php esc_html_e( 'Our Process', 'devhems-child' ); ?></h2>
		<ol class="devhems-process-list">
			<?php foreach ( $steps as $step ) : ?>
				<li>
					<span class="devhems-process-number"><?php echo esc_html( $step['step_number'] ); ?></span>
					<h3><?php echo esc_html( $step['step_title'] ); ?></h3>
					<p><?php echo esc_html( $step['step_description'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>
	<?php endif; ?>

	<?php $technologies = get_field( 'technologies' ); ?>
	<?php if ( $technologies ) : ?>
	<section class="devhems-service-technologies">
		<h2><?php esc_html_e( 'Technologies & Platforms', 'devhems-child' ); ?></h2>
		<div class="devhems-logo-grid">
			<?php foreach ( $technologies as $tech ) : ?>
				<div class="devhems-logo-item">
					<?php if ( ! empty( $tech['logo'] ) ) : ?>
						<?php echo wp_get_attachment_image( $tech['logo'], 'thumbnail', false, array( 'loading' => 'lazy', 'alt' => esc_attr( $tech['name'] ) ) ); ?>
					<?php endif; ?>
					<span><?php echo esc_html( $tech['name'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php $related_case_studies = get_field( 'related_case_studies' ); ?>
	<?php if ( $related_case_studies ) : ?>
	<section class="devhems-related-case-studies">
		<h2><?php esc_html_e( 'Related Case Studies', 'devhems-child' ); ?></h2>
		<div class="devhems-card-grid">
			<?php foreach ( $related_case_studies as $case_study ) : ?>
				<a class="devhems-case-study-card" href="<?php echo esc_url( get_permalink( $case_study ) ); ?>">
					<?php echo get_the_post_thumbnail( $case_study, 'devhems-card', array( 'loading' => 'lazy' ) ); ?>
					<h3><?php echo esc_html( get_the_title( $case_study ) ); ?></h3>
				</a>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php get_template_part( 'template-parts/why-choose-devhems' ); ?>

	<?php $faqs = get_field( 'faqs' ); ?>
	<?php if ( $faqs ) : ?>
	<section class="devhems-service-faqs">
		<h2><?php esc_html_e( 'Frequently Asked Questions', 'devhems-child' ); ?></h2>
		<div class="devhems-faq-accordion">
			<?php foreach ( $faqs as $i => $faq ) : ?>
				<?php $faq_id = 'dh-faq-' . get_the_ID() . '-' . $i; ?>
				<div class="devhems-faq-item">
					<h3>
						<button type="button" class="devhems-faq-question" aria-expanded="false" aria-controls="<?php echo esc_attr( $faq_id ); ?>">
							<?php echo esc_html( $faq['question'] ); ?>
						</button>
					</h3>
					<div id="<?php echo esc_attr( $faq_id ); ?>" class="devhems-faq-answer" hidden>
						<?php echo wp_kses_post( $faq['answer'] ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php $related_services = get_field( 'related_services' ); ?>
	<?php if ( $related_services ) : ?>
	<section class="devhems-related-services">
		<h2><?php esc_html_e( 'Related Services', 'devhems-child' ); ?></h2>
		<div class="devhems-card-grid">
			<?php foreach ( $related_services as $related ) : ?>
				<a class="devhems-service-card" href="<?php echo esc_url( get_permalink( $related ) ); ?>">
					<?php echo get_the_post_thumbnail( $related, 'devhems-card', array( 'loading' => 'lazy' ) ); ?>
					<h3><?php echo esc_html( get_the_title( $related ) ); ?></h3>
					<p><?php echo esc_html( devhems_get_service_short_description( $related ) ); ?></p>
				</a>
			<?php endforeach; ?>
		</div>
	</section>
	<?php endif; ?>

	<section class="devhems-service-enquiry">
		<h2><?php esc_html_e( 'Get a Free Consultation', 'devhems-child' ); ?></h2>
		<?php echo do_shortcode( '[devhems_service_enquiry_form]' ); ?>
	</section>

</main>

<?php get_template_part( 'template-parts/bottom-cta-banner' ); ?>
<?php endwhile; ?>

<?php get_footer(); ?>
