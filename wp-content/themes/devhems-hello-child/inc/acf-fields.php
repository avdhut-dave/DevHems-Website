<?php
/**
 * Advanced Custom Fields field group registrations (PHP local registration
 * — field groups are version-controlled with the theme instead of living
 * only in the database, and mirrored to /acf-json for the ACF admin UI's
 * built-in sync screen). All fields here are editable by the administrator
 * from the WP admin edit screen; nothing on the front end is hardcoded.
 *
 * Requires the Advanced Custom Fields (or ACF PRO) plugin to be active.
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

add_filter( 'acf/settings/save_json', function ( $path ) {
	return DEVHEMS_THEME_DIR . '/acf-json';
} );

add_filter( 'acf/settings/load_json', function ( $paths ) {
	$paths[] = DEVHEMS_THEME_DIR . '/acf-json';
	return $paths;
} );

/**
 * Reusable "SEO Information" field tab, attached near the bottom of every
 * content type per the project spec. If Rank Math/Yoast is active and its
 * own per-post SEO panel is preferred instead, this group can be disabled
 * from Custom Fields > Field Groups without any code change.
 */
function devhems_seo_fields() {
	return array(
		array(
			'key'   => 'field_seo_tab',
			'label' => 'SEO',
			'name'  => '',
			'type'  => 'tab',
		),
		array(
			'key'   => 'field_seo_title',
			'label' => 'Meta Title',
			'name'  => 'seo_meta_title',
			'type'  => 'text',
			'instructions' => 'Leave blank to use the Rank Math / Yoast generated title.',
		),
		array(
			'key'   => 'field_seo_description',
			'label' => 'Meta Description',
			'name'  => 'seo_meta_description',
			'type'  => 'textarea',
			'rows'  => 3,
		),
		array(
			'key'   => 'field_seo_og_image',
			'label' => 'Social Share Image',
			'name'  => 'seo_og_image',
			'type'  => 'image',
			'return_format' => 'id',
		),
		array(
			'key'   => 'field_seo_noindex',
			'label' => 'Exclude from search engines (noindex)',
			'name'  => 'seo_noindex',
			'type'  => 'true_false',
			'ui'    => 1,
		),
	);
}

/* ---------------------------------------------------------------------- */
/* Service                                                                 */
/* ---------------------------------------------------------------------- */
acf_add_local_field_group( array(
	'key'      => 'group_service_details',
	'title'    => 'Service Details',
	'fields'   => array_merge(
		array(
			array(
				'key'   => 'field_service_tab_main',
				'label' => 'Overview',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'   => 'field_service_short_description',
				'label' => 'Short Description',
				'name'  => 'short_description',
				'type'  => 'textarea',
				'rows'  => 3,
				'instructions' => 'Used on service cards, the mega menu and archive listings.',
			),
			array(
				'key'   => 'field_service_hero_subtitle',
				'label' => 'Hero Subtitle',
				'name'  => 'hero_subtitle',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_service_tab_problems',
				'label' => 'Problems Addressed',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'      => 'field_service_problems',
				'label'    => 'Business Problems Addressed',
				'name'     => 'problems_addressed',
				'type'     => 'repeater',
				'layout'   => 'block',
				'button_label' => 'Add Problem',
				'instructions' => 'The pain points this service solves for a prospective client.',
				'sub_fields' => array(
					array(
						'key'   => 'field_problem_title',
						'label' => 'Problem',
						'name'  => 'title',
						'type'  => 'text',
						'required' => 1,
					),
					array(
						'key'   => 'field_problem_description',
						'label' => 'Description',
						'name'  => 'description',
						'type'  => 'textarea',
						'rows'  => 2,
					),
				),
			),
			array(
				'key'   => 'field_service_tab_included',
				'label' => 'Services Included',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'      => 'field_service_included',
				'label'    => 'Services Included',
				'name'     => 'services_included',
				'type'     => 'repeater',
				'layout'   => 'block',
				'button_label' => 'Add Item',
				'instructions' => 'The specific deliverables bundled into this service.',
				'sub_fields' => array(
					array(
						'key'   => 'field_included_title',
						'label' => 'Item Title',
						'name'  => 'title',
						'type'  => 'text',
						'required' => 1,
					),
					array(
						'key'   => 'field_included_description',
						'label' => 'Item Description',
						'name'  => 'description',
						'type'  => 'textarea',
						'rows'  => 2,
					),
				),
			),
			array(
				'key'   => 'field_service_tab_benefits',
				'label' => 'Benefits',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'      => 'field_service_benefits',
				'label'    => 'Benefits',
				'name'     => 'benefits',
				'type'     => 'repeater',
				'layout'   => 'block',
				'button_label' => 'Add Benefit',
				'sub_fields' => array(
					array(
						'key'   => 'field_benefit_icon',
						'label' => 'Icon',
						'name'  => 'icon',
						'type'  => 'image',
						'return_format' => 'id',
					),
					array(
						'key'   => 'field_benefit_title',
						'label' => 'Title',
						'name'  => 'title',
						'type'  => 'text',
						'required' => 1,
					),
					array(
						'key'   => 'field_benefit_description',
						'label' => 'Description',
						'name'  => 'description',
						'type'  => 'textarea',
						'rows'  => 2,
					),
				),
			),
			array(
				'key'   => 'field_service_tab_process',
				'label' => 'Process',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'      => 'field_service_process',
				'label'    => 'Work Process Steps',
				'name'     => 'process_steps',
				'type'     => 'repeater',
				'layout'   => 'block',
				'button_label' => 'Add Step',
				'sub_fields' => array(
					array(
						'key'   => 'field_process_step_number',
						'label' => 'Step Number',
						'name'  => 'step_number',
						'type'  => 'number',
					),
					array(
						'key'   => 'field_process_step_title',
						'label' => 'Step Title',
						'name'  => 'step_title',
						'type'  => 'text',
						'required' => 1,
					),
					array(
						'key'   => 'field_process_step_description',
						'label' => 'Step Description',
						'name'  => 'step_description',
						'type'  => 'textarea',
						'rows'  => 2,
					),
				),
			),
			array(
				'key'   => 'field_service_tab_technologies',
				'label' => 'Technologies',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'      => 'field_service_technologies',
				'label'    => 'Technologies / Platforms',
				'name'     => 'technologies',
				'type'     => 'repeater',
				'layout'   => 'table',
				'button_label' => 'Add Technology',
				'sub_fields' => array(
					array(
						'key'   => 'field_tech_logo',
						'label' => 'Logo',
						'name'  => 'logo',
						'type'  => 'image',
						'return_format' => 'id',
					),
					array(
						'key'   => 'field_tech_name',
						'label' => 'Name',
						'name'  => 'name',
						'type'  => 'text',
					),
				),
			),
			array(
				'key'   => 'field_service_tab_faqs',
				'label' => 'FAQs',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'      => 'field_service_faqs',
				'label'    => 'Frequently Asked Questions',
				'name'     => 'faqs',
				'type'     => 'repeater',
				'layout'   => 'block',
				'button_label' => 'Add FAQ',
				'sub_fields' => array(
					array(
						'key'   => 'field_faq_question',
						'label' => 'Question',
						'name'  => 'question',
						'type'  => 'text',
						'required' => 1,
					),
					array(
						'key'   => 'field_faq_answer',
						'label' => 'Answer',
						'name'  => 'answer',
						'type'  => 'wysiwyg',
						'tabs'  => 'text',
						'media_upload' => 0,
						'toolbar' => 'basic',
					),
				),
			),
			array(
				'key'   => 'field_service_tab_related',
				'label' => 'Related Services',
				'name'  => '',
				'type'  => 'tab',
			),
			array(
				'key'   => 'field_service_related',
				'label' => 'Related Services',
				'name'  => 'related_services',
				'type'  => 'relationship',
				'post_type' => array( 'service' ),
				'filters'   => array( 'search', 'taxonomy' ),
				'max'       => 4,
			),
			array(
				'key'   => 'field_service_related_case_studies',
				'label' => 'Related Case Studies',
				'name'  => 'related_case_studies',
				'type'  => 'relationship',
				'post_type' => array( 'case_study' ),
				'filters'   => array( 'search' ),
				'max'       => 3,
			),
		),
		devhems_seo_fields()
	),
	'location' => array(
		array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => 'service',
			),
		),
	),
) );

/* ---------------------------------------------------------------------- */
/* Case Study                                                              */
/* ---------------------------------------------------------------------- */
acf_add_local_field_group( array(
	'key'    => 'group_case_study_details',
	'title'  => 'Case Study Details',
	'fields' => array_merge(
		array(
			array(
				'key'   => 'field_cs_client_name',
				'label' => 'Client Name',
				'name'  => 'client_name',
				'type'  => 'text',
			),
			array(
				'key'   => 'field_cs_service_category',
				'label' => 'Service Category',
				'name'  => 'service_category_ref',
				'type'  => 'taxonomy',
				'taxonomy' => 'service_category',
				'field_type' => 'select',
				'return_format' => 'id',
			),
			array(
				'key'   => 'field_cs_challenge',
				'label' => 'Challenge',
				'name'  => 'challenge',
				'type'  => 'wysiwyg',
				'toolbar' => 'basic',
				'media_upload' => 0,
			),
			array(
				'key'   => 'field_cs_solution',
				'label' => 'Solution',
				'name'  => 'solution',
				'type'  => 'wysiwyg',
				'toolbar' => 'basic',
				'media_upload' => 0,
			),
			array(
				'key'      => 'field_cs_results',
				'label'    => 'Results',
				'name'     => 'results',
				'type'     => 'repeater',
				'layout'   => 'table',
				'button_label' => 'Add Result Metric',
				'sub_fields' => array(
					array(
						'key'   => 'field_result_value',
						'label' => 'Value',
						'name'  => 'value',
						'type'  => 'text',
						'placeholder' => 'e.g. 240%',
					),
					array(
						'key'   => 'field_result_label',
						'label' => 'Label',
						'name'  => 'label',
						'type'  => 'text',
						'placeholder' => 'e.g. Organic traffic growth',
					),
				),
			),
			array(
				'key'   => 'field_cs_gallery',
				'label' => 'Project Images',
				'name'  => 'gallery',
				'type'  => 'gallery',
				'return_format' => 'id',
			),
			array(
				'key'   => 'field_cs_testimonial',
				'label' => 'Client Testimonial',
				'name'  => 'testimonial',
				'type'  => 'post_object',
				'post_type' => array( 'testimonial' ),
			),
			array(
				'key'   => 'field_cs_related_services',
				'label' => 'Related Services',
				'name'  => 'related_services',
				'type'  => 'relationship',
				'post_type' => array( 'service' ),
				'max'       => 4,
			),
		),
		devhems_seo_fields()
	),
	'location' => array(
		array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => 'case_study',
			),
		),
	),
) );

/* ---------------------------------------------------------------------- */
/* Career                                                                  */
/* ---------------------------------------------------------------------- */
acf_add_local_field_group( array(
	'key'    => 'group_career_details',
	'title'  => 'Job Opening Details',
	'fields' => array(
		array(
			'key'   => 'field_job_location',
			'label' => 'Location',
			'name'  => 'job_location',
			'type'  => 'text',
			'placeholder' => 'e.g. Remote / Pune, India',
		),
		array(
			'key'     => 'field_employment_type',
			'label'   => 'Employment Type',
			'name'    => 'employment_type',
			'type'    => 'select',
			'choices' => array(
				'full-time' => 'Full-Time',
				'part-time' => 'Part-Time',
				'contract'  => 'Contract',
				'internship' => 'Internship',
			),
			'default_value' => 'full-time',
		),
		array(
			'key'   => 'field_experience_required',
			'label' => 'Experience Required',
			'name'  => 'experience_required',
			'type'  => 'text',
			'placeholder' => 'e.g. 2-4 years',
		),
		array(
			'key'   => 'field_job_responsibilities',
			'label' => 'Responsibilities',
			'name'  => 'responsibilities',
			'type'  => 'wysiwyg',
			'toolbar' => 'basic',
			'media_upload' => 0,
		),
		array(
			'key'   => 'field_job_requirements',
			'label' => 'Requirements',
			'name'  => 'requirements',
			'type'  => 'wysiwyg',
			'toolbar' => 'basic',
			'media_upload' => 0,
		),
		array(
			'key'   => 'field_application_deadline',
			'label' => 'Application Deadline',
			'name'  => 'application_deadline',
			'type'  => 'date_picker',
			'display_format' => 'd/m/Y',
			'return_format'  => 'Ymd',
		),
		array(
			'key'     => 'field_job_status',
			'label'   => 'Job Status',
			'name'    => 'job_status',
			'type'    => 'select',
			'choices' => array(
				'open'   => 'Open',
				'closed' => 'Closed',
			),
			'default_value' => 'open',
			'instructions'  => 'Closed listings are automatically hidden from the public Careers archive.',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => 'career',
			),
		),
	),
) );

/* ---------------------------------------------------------------------- */
/* Testimonial                                                             */
/* ---------------------------------------------------------------------- */
acf_add_local_field_group( array(
	'key'    => 'group_testimonial_details',
	'title'  => 'Testimonial Details',
	'fields' => array(
		array(
			'key'   => 'field_testimonial_client_name',
			'label' => 'Client Name',
			'name'  => 'client_name',
			'type'  => 'text',
			'required' => 1,
		),
		array(
			'key'   => 'field_testimonial_company',
			'label' => 'Company',
			'name'  => 'company',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_testimonial_designation',
			'label' => 'Designation',
			'name'  => 'designation',
			'type'  => 'text',
		),
		array(
			'key'   => 'field_testimonial_text',
			'label' => 'Testimonial',
			'name'  => 'testimonial_text',
			'type'  => 'textarea',
			'rows'  => 4,
			'required' => 1,
		),
		array(
			'key'   => 'field_testimonial_rating',
			'label' => 'Rating (out of 5)',
			'name'  => 'rating',
			'type'  => 'number',
			'min'   => 1,
			'max'   => 5,
			'default_value' => 5,
		),
		array(
			'key'   => 'field_testimonial_photo',
			'label' => 'Client Photograph or Company Logo',
			'name'  => 'photo',
			'type'  => 'image',
			'return_format' => 'id',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => 'testimonial',
			),
		),
	),
) );

/* ---------------------------------------------------------------------- */
/* Blog posts — SEO tab only (content itself uses the standard editor)     */
/* ---------------------------------------------------------------------- */
acf_add_local_field_group( array(
	'key'      => 'group_post_seo',
	'title'    => 'SEO',
	'fields'   => devhems_seo_fields(),
	'location' => array(
		array(
			array(
				'param'    => 'post_type',
				'operator' => '==',
				'value'    => 'post',
			),
		),
	),
) );
