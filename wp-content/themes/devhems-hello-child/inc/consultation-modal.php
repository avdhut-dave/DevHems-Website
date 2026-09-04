<?php
/**
 * "Get Free Consultation" header CTA modal — built without Elementor Pro's
 * Popup Builder, since that's a paid feature. The modal markup is injected
 * once in wp_footer and toggled by any element carrying the
 * `devhems-open-modal` class; the header CTA button uses
 * [devhems_consultation_trigger] instead of a plain link so it never
 * navigates away from the page.
 *
 * The modal's form is the "Popup Enquiry Form" Contact Form 7 template
 * (cf7-forms/7-popup-enquiry-form.txt) — set its ID via the
 * `devhems_consultation_form_id` filter/option, same pattern as the
 * service/career form wrappers in inc/template-tags.php.
 */

defined( 'ABSPATH' ) || exit;

function devhems_consultation_form_id() {
	return apply_filters( 'devhems_consultation_form_id', get_option( 'devhems_consultation_form_id', 0 ) );
}

function devhems_consultation_trigger_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'label' => __( 'Get Free Consultation', 'devhems-child' ),
		'class' => 'devhems-header-cta',
	), $atts, 'devhems_consultation_trigger' );

	return sprintf(
		'<button type="button" class="%1$s devhems-open-modal">%2$s</button>',
		esc_attr( $atts['class'] ),
		esc_html( $atts['label'] )
	);
}
add_shortcode( 'devhems_consultation_trigger', 'devhems_consultation_trigger_shortcode' );

function devhems_render_consultation_modal() {
	$form_id = devhems_consultation_form_id();
	?>
	<div class="devhems-modal-overlay" id="devhems-consultation-modal" aria-hidden="true">
		<div class="devhems-modal-box" role="dialog" aria-modal="true" aria-labelledby="devhems-modal-title">
			<button type="button" class="devhems-modal-close" aria-label="<?php esc_attr_e( 'Close', 'devhems-child' ); ?>">&times;</button>
			<h2 id="devhems-modal-title"><?php esc_html_e( 'Get a Free Consultation', 'devhems-child' ); ?></h2>
			<p><?php esc_html_e( "Tell us a bit about your business — we'll follow up within one business day.", 'devhems-child' ); ?></p>
			<?php if ( $form_id ) : ?>
				<?php echo do_shortcode( '[contact-form-7 id="' . absint( $form_id ) . '" title="Popup Enquiry Form"]' ); ?>
			<?php else : ?>
				<p><em><?php esc_html_e( 'Set the Popup Enquiry Form ID via the devhems_consultation_form_id filter to render the form here.', 'devhems-child' ); ?></em></p>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'devhems_render_consultation_modal' );
