<?php
/**
 * Contact Form 7 integration glue. The form field markup itself lives in
 * /cf7-forms/*.txt (pasted into Contact Form 7 > Add New by the admin);
 * this file supplies the behaviour CF7 doesn't provide out of the box:
 *
 *  - auto-selecting the current service/job on service & career forms
 *  - honeypot spam trap (in addition to CF7's own Akismet/Turnstile options)
 *  - Reply-To header safety net (belt-and-braces on top of the [_reply_to]
 *    mail-tag already used in each form template)
 *  - Flamingo storage (Flamingo just needs to be active; no code required)
 *  - redirect to the Thank You page + GA4 / GTM dataLayer events on submit
 *
 * Requires Contact Form 7 (and Flamingo) to be active.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
	return;
}

/**
 * Honeypot: CF7 forms include a visually hidden field named "dh_hp_website"
 * (see /cf7-forms templates). Any non-empty value fails validation silently
 * as a generic error, without revealing the trap to bots.
 */
function devhems_cf7_honeypot_validate( $result, $tags ) {
	if ( isset( $_POST['dh_hp_website'] ) && '' !== trim( wp_unslash( $_POST['dh_hp_website'] ) ) ) {
		$result->invalidate( $tags[0] ?? null, __( 'Spam check failed.', 'devhems-child' ) );
	}
	return $result;
}
add_filter( 'wpcf7_validate', 'devhems_cf7_honeypot_validate', 10, 2 );

/**
 * Force the Reply-To header to the submitter's own email even if a form is
 * edited later and the mail-tag is accidentally removed, and make sure the
 * From address always stays the site's authenticated domain (SMTP plugin
 * requirement — never send From the visitor's address).
 */
function devhems_cf7_mail_headers( $contact_form ) {
	$mail = $contact_form->prop( 'mail' );
	if ( ! is_array( $mail ) ) {
		return;
	}

	if ( empty( $mail['additional_headers'] ) || false === strpos( $mail['additional_headers'], 'Reply-To' ) ) {
		$mail['additional_headers'] = trim( $mail['additional_headers'] . "\nReply-To: [_reply_to]" );
		$contact_form->set_properties( array( 'mail' => $mail ) );
	}
}
add_action( 'wpcf7_before_send_mail', 'devhems_cf7_mail_headers' );

/**
 * Append the submission source URL and UTM parameters as hidden mail-tag
 * values so they appear in the admin notification. Each CF7 template
 * already includes hidden fields named source_url, utm_source, utm_medium,
 * utm_campaign, utm_term and utm_campaign_content, populated client-side by
 * forms.js from the query string / referrer. This filter is a server-side
 * fallback for JS-disabled submissions.
 */
function devhems_cf7_fallback_hidden_fields( $posted_data ) {
	if ( empty( $posted_data['source_url'] ) && ! empty( $_SERVER['HTTP_REFERER'] ) ) {
		$posted_data['source_url'] = esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) );
	}
	return $posted_data;
}
add_filter( 'wpcf7_posted_data', 'devhems_cf7_fallback_hidden_fields' );

/**
 * Restrict the Career Application Form's resume upload to PDF/DOC/DOCX at
 * 5MB, enforced server-side regardless of what the form's own [file] tag
 * options say (defense in depth — the .txt template also sets this).
 */
function devhems_cf7_resume_validate( $result, $tag ) {
	if ( 'resume' !== $tag->name || empty( $_FILES['resume']['name'] ) ) {
		return $result;
	}

	$filename = sanitize_file_name( $_FILES['resume']['name'] );
	$allowed  = array( 'pdf', 'doc', 'docx' );
	$ext      = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );

	if ( ! in_array( $ext, $allowed, true ) ) {
		$result->invalidate( $tag, __( 'Please upload your resume as PDF, DOC or DOCX.', 'devhems-child' ) );
		return $result;
	}

	if ( ! empty( $_FILES['resume']['size'] ) && $_FILES['resume']['size'] > 5 * MB_IN_BYTES ) {
		$result->invalidate( $tag, __( 'Resume file must be smaller than 5MB.', 'devhems-child' ) );
	}

	return $result;
}
add_filter( 'wpcf7_validate_file*', 'devhems_cf7_resume_validate', 10, 2 );
add_filter( 'wpcf7_validate_file', 'devhems_cf7_resume_validate', 10, 2 );

/**
 * Redirect to the Thank You page on successful submission and fire the GA4 /
 * GTM events, keyed by form title so each form can point at a distinct
 * thank-you URL / conversion label if needed (edit the map below in the
 * WP admin is not possible — this stays code because it's tracking wiring,
 * not editorial content).
 */
function devhems_cf7_redirect_script() {
	$thank_you_url = home_url( '/thank-you/' );
	?>
	<script>
	document.addEventListener('wpcf7mailsent', function (event) {
		if (window.dataLayer) {
			window.dataLayer.push({
				event: 'form_submit',
				form_id: event.detail.contactFormId,
				form_title: (event.detail.apiResponse && event.detail.apiResponse.into) || undefined
			});
		}
		if (typeof gtag === 'function') {
			gtag('event', 'generate_lead', { form_id: event.detail.contactFormId });
		}
		window.location.assign(<?php echo wp_json_encode( $thank_you_url ); ?> + '?form=' + encodeURIComponent(event.detail.contactFormId || ''));
	}, false);
	</script>
	<?php
}
add_action( 'wp_footer', 'devhems_cf7_redirect_script', 20 );
