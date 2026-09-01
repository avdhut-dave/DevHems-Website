<?php
/**
 * Wrapper shortcodes that render a Contact Form 7 form pre-filled with
 * context from the current post (service or job title), used on the
 * Service Detail and Career Detail Elementor templates via the Elementor
 * "Shortcode" widget instead of Elementor's native CF7 widget (per the
 * "auto-selected according to the current service/job page" requirement,
 * which CF7 alone can't resolve without knowing the current post).
 *
 * Admin setup: create the "Service Enquiry Form" and "Career Application
 * Form" in Contact Form 7 using /cf7-forms/3-*.txt and /cf7-forms/5-*.txt,
 * note each form's ID, then set it via the constants below or a filter.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Filterable form IDs so the site owner can point these shortcodes at their
 * actual Contact Form 7 post IDs from a small must-use snippet or
 * functions.php filter, without editing this file.
 */
function devhems_service_enquiry_form_id() {
	return apply_filters( 'devhems_service_enquiry_form_id', get_option( 'devhems_service_enquiry_form_id', 0 ) );
}

function devhems_career_application_form_id() {
	return apply_filters( 'devhems_career_application_form_id', get_option( 'devhems_career_application_form_id', 0 ) );
}

function devhems_service_enquiry_form_shortcode() {
	$form_id = devhems_service_enquiry_form_id();
	if ( ! $form_id || ! is_singular( 'service' ) ) {
		return $form_id ? do_shortcode( '[contact-form-7 id="' . absint( $form_id ) . '"]' ) : '';
	}

	$service_title = get_the_title();

	ob_start();
	?>
	<div class="devhems-service-enquiry-form" data-dh-autofill-value="<?php echo esc_attr( $service_title ); ?>">
		<?php echo do_shortcode( '[contact-form-7 id="' . absint( $form_id ) . '"]' ); ?>
	</div>
	<script>
	(function () {
		var wrap = document.currentScript.previousElementSibling;
		if (!wrap) return;
		var field = wrap.querySelector('.dh-autoselect-service');
		if (field) {
			field.value = wrap.getAttribute('data-dh-autofill-value');
		}
	})();
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'devhems_service_enquiry_form', 'devhems_service_enquiry_form_shortcode' );

function devhems_career_application_form_shortcode() {
	$form_id = devhems_career_application_form_id();
	if ( ! $form_id || ! is_singular( 'career' ) ) {
		return $form_id ? do_shortcode( '[contact-form-7 id="' . absint( $form_id ) . '"]' ) : '';
	}

	$job_title = get_the_title();

	ob_start();
	?>
	<div class="devhems-career-application-form" data-dh-autofill-value="<?php echo esc_attr( $job_title ); ?>">
		<?php echo do_shortcode( '[contact-form-7 id="' . absint( $form_id ) . '"]' ); ?>
	</div>
	<script>
	(function () {
		var wrap = document.currentScript.previousElementSibling;
		if (!wrap) return;
		var field = wrap.querySelector('.dh-autoselect-job');
		if (field) {
			field.value = wrap.getAttribute('data-dh-autofill-value');
		}
	})();
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode( 'devhems_career_application_form', 'devhems_career_application_form_shortcode' );

/**
 * Small helper for Elementor dynamic tags / templates: outputs the ACF
 * "short_description" of a Service, falling back to the excerpt.
 */
function devhems_get_service_short_description( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$value   = function_exists( 'get_field' ) ? get_field( 'short_description', $post_id ) : '';
	return $value ? $value : get_the_excerpt( $post_id );
}

/**
 * Estimated reading time for the current post, shown on blog cards and the
 * blog detail hero. Based on a 200 words-per-minute average reading speed.
 */
function devhems_reading_time( $post_id = null ) {
	$post_id    = $post_id ?: get_the_ID();
	$word_count = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post_id ) ) );
	$minutes    = max( 1, (int) ceil( $word_count / 200 ) );

	return sprintf(
		/* translators: %d: estimated reading time in minutes */
		_n( '%d min read', '%d min read', $minutes, 'devhems-child' ),
		$minutes
	);
}
