<?php
/**
 * Condition pages from prototype src/pages/conditions.js.
 *
 * Nested under /conditions/{slug}. Layout is shared; copy is per condition.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prototype data for the four condition pages.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_condition_pages() {
	$reasons = cil_path_url( '/conditions/phimosis' );

	return array(
		'phimosis'      => cil_condition_page_data(
			array(
				'slug'        => 'phimosis',
				'name'        => 'Phimosis',
				'title'       => 'Phimosis (Tight Foreskin) | Treatment in London | Beverley Clinic',
				'description' => 'What phimosis is, when a tight foreskin needs treating and when it does not, and the options besides circumcision. CQC-registered clinic in Edgware, London.',
				'eyebrow'     => 'Condition · Tight foreskin',
				'h1'          => 'Phimosis (tight foreskin)',
				'lede'        => 'Phimosis means that the foreskin cannot be comfortably retracted over the head of the penis. In babies, a non-retractile foreskin can be a normal part of development and does not automatically mean they have phimosis.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>In older children and adults, if there is pain/ discomfort or inability to retract the foreskin, the most predictable long term solution is a circumcision in our experience. The severity of phimosis varies, with some patients only experiencing some discomfort when retracting the foreskin whilst others are unable to retract the foreskin at all which leads to scarring, infections and in some severe cases the inability to pass urine.</p>
      <p>It can become a vicious cycle where a patient will try to retract the tight foreskin but cause micro tears in the skin. When it heals, this causes scarring which leads to more tightness and the condition worsens. Circumcision is a suitable long term option where the scar tissue and tight skin is removed which can relieve symptoms immediately.</p>'
				),
				'sections'    => array(
					array(
						'eyebrow' => 'Assessment',
						'heading' => 'Why the tight band matters',
						'html'    => cil_proto_html(
							'<p>It is important for the practitioner to recognize a phimosis and not treating it in the same way as a normal circumcision. There is often a phimotic (tight) band that must be removed to relieve the symptoms. Otherwise, the tightness will still exist but just behind the head of the penis which will affect the comfort and cosmetic result.</p>
          <p>Seek urgent medical help if the foreskin has been pulled back and becomes trapped behind the head of the penis with increasing swelling, or if the patient cannot pass urine. That is <a href="/conditions/paraphimosis">paraphimosis</a>.</p>
          <p>Adult circumcision with a medical or foreskin problem is listed on the <a href="/prices">prices page</a>. See also <a href="/adults">adult circumcision</a> and <a href="/aftercare">aftercare</a>.</p>'
						),
					),
				),
				'faqs'        => array(),
				'schema'      => array(
					'@type'             => 'MedicalCondition',
					'alternateName'     => array( 'Tight foreskin', 'Non-retractile foreskin' ),
					'possibleTreatment' => array(
						array(
							'@type' => 'MedicalTherapy',
							'name'  => 'Topical corticosteroid and gentle stretching',
						),
						array(
							'@type' => 'MedicalProcedure',
							'name'  => 'Preputioplasty',
							'url'   => cil_path_url( '/procedures/preputioplasty' ),
						),
						array(
							'@type' => 'MedicalProcedure',
							'name'  => 'Circumcision',
							'url'   => cil_path_url( '/adults' ),
						),
					),
				),
			)
		),
		'balanitis'     => cil_condition_page_data(
			array(
				'slug'        => 'balanitis',
				'name'        => 'Balanitis',
				'title'       => 'Balanitis | Causes and Treatment | Beverley Clinic London',
				'description' => 'Balanitis is inflammation of the glans: what causes it, how it is treated, and when repeated episodes make circumcision worth considering. Clinic in Edgware.',
				'eyebrow'     => 'Condition · Inflammation of the glans',
				'h1'          => 'Balanitis',
				'lede'        => 'Balanitis is inflammation affecting the head of the penis and can cause redness, soreness, irritation or discomfort. It can have different causes, so treatment should be based on assessment rather than assumption.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>If episodes keep returning, or if there is associated foreskin tightness or scarring, circumcision will be required.</p>
      <p>Spreading redness with fever, severe swelling or difficulty passing urine requires urgent medical assessment. See also <a href="/conditions/phimosis">phimosis</a>, <a href="/adults">adult circumcision</a> and <a href="/aftercare">aftercare</a>.</p>'
				),
				'sections'    => array(),
				'faqs'        => array(),
				'schema'      => array(
					'@type'             => 'MedicalCondition',
					'alternateName'     => array( 'Balanoposthitis' ),
					'possibleTreatment' => array(
						array(
							'@type' => 'MedicalTherapy',
							'name'  => 'Topical antifungal or corticosteroid treatment',
						),
						array(
							'@type' => 'MedicalProcedure',
							'name'  => 'Circumcision',
							'url'   => cil_path_url( '/adults' ),
						),
					),
				),
			)
		),
		'bxo'           => cil_condition_page_data(
			array(
				'slug'        => 'bxo',
				'name'        => 'BXO',
				'title'       => 'BXO (Lichen Sclerosus) | Treatment in London | Beverley Clinic',
				'description' => 'Balanitis xerotica obliterans is a scarring condition of the foreskin. Why circumcision is the standard treatment, and why BXO should not be left. Edgware, London.',
				'eyebrow'     => 'Condition · Balanitis xerotica obliterans',
				'h1'          => 'BXO / lichen sclerosus',
				'lede'        => 'BXO, also known as male genital lichen sclerosus, can cause whitening, scarring and tightening of the foreskin. It may make retraction difficult and can cause discomfort or recurrent problems.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>It can become a vicious cycle where a patient will try to retract the tight foreskin but cause micro tears in the skin. When it heals, this causes scarring which leads to more tightness and the condition worsens. Circumcision is a suitable long term option where the scar tissue and tight skin is removed which can relieve symptoms immediately.</p>
      <p>See also <a href="/conditions/phimosis">phimosis</a>, <a href="/adults">adult circumcision</a> and <a href="/aftercare">aftercare</a>.</p>'
				),
				'sections'    => array(),
				'faqs'        => array(),
				'schema'      => array(
					'@type'             => 'MedicalCondition',
					'alternateName'     => array( 'Balanitis xerotica obliterans', 'Genital lichen sclerosus', 'Male lichen sclerosus' ),
					'possibleTreatment' => array(
						array(
							'@type' => 'MedicalTherapy',
							'name'  => 'Potent topical corticosteroid',
						),
						array(
							'@type' => 'MedicalProcedure',
							'name'  => 'Circumcision',
							'url'   => cil_path_url( '/adults' ),
						),
					),
				),
			)
		),
		'paraphimosis'  => cil_condition_page_data(
			array(
				'slug'        => 'paraphimosis',
				'name'        => 'Paraphimosis',
				'title'       => 'Paraphimosis | Emergency Advice and Treatment | Beverley Clinic',
				'description' => 'Paraphimosis is a retracted foreskin trapped behind the glans. It is a urological emergency needing same-day care. What to do now, and what happens afterwards.',
				'eyebrow'     => 'Condition · Urological emergency',
				'h1'          => 'Paraphimosis',
				'lede'        => 'Paraphimosis occurs when a retracted foreskin becomes trapped behind the head of the penis and cannot be brought forward again. Swelling can increase quickly and the condition may require urgent treatment.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>If this is happening now, do not wait for a routine clinic appointment. Seek urgent medical assessment, particularly if swelling is increasing, the colour is changing, pain is severe or the patient cannot pass urine.</p>
      <p>After the immediate problem has been treated, some patients may be advised to consider circumcision to reduce the risk of recurrence. See <a href="/conditions/phimosis">phimosis</a>, <a href="/adults">adult circumcision</a> and <a href="/aftercare">aftercare</a>.</p>'
				),
				'sections'    => array(),
				'faqs'        => array(),
				'schema'      => array(
					'@type'             => 'MedicalCondition',
					'possibleTreatment' => array(
						array(
							'@type' => 'MedicalProcedure',
							'name'  => 'Emergency manual reduction',
						),
						array(
							'@type' => 'MedicalProcedure',
							'name'  => 'Circumcision',
							'url'   => cil_path_url( '/adults' ),
						),
					),
				),
			)
		),
	);
}

/**
 * Fill shared fields for a condition page.
 *
 * @param array<string, mixed> $page Partial page data.
 * @return array<string, mixed>
 */
function cil_condition_page_data( $page ) {
	$page['parent']       = 'conditions';
	$page['parent_title'] = 'Reasons';
	$page['path']         = '/conditions/' . $page['slug'];
	if ( ! empty( $page['schema'] ) && is_array( $page['schema'] ) ) {
		if ( empty( $page['schema']['name'] ) ) {
			$page['schema']['name'] = $page['name'];
		}
	}
	$page['crumbs'] = array(
		array(
			'label' => 'Reasons',
			'href'  => $page['reasons'],
		),
		array(
			'label' => $page['name'],
			'href'  => '',
		),
	);
	unset( $page['reasons'] );
	return $page;
}

/**
 * Gutenberg markup for one condition page.
 *
 * @param string $slug Condition slug.
 * @return string
 */
function cil_condition_page_blocks( $slug ) {
	$pages = cil_condition_pages();
	if ( empty( $pages[ $slug ] ) ) {
		return '';
	}
	return cil_clinical_page_blocks( $pages[ $slug ] );
}

/**
 * Shared Gutenberg markup for prototype condition/procedure pages.
 *
 * Layout from src/pages/conditions.js conditionPage().
 *
 * @param array<string, mixed> $page Page data.
 * @return string
 */
function cil_clinical_page_blocks( $page ) {
	$clinic = cil_clinic();
	$urgent = cil_render_part( 'urgent-note' );

	$being_seen = cil_proto_html(
		'<div data-reveal>
        <span class="caps eyebrow">Being seen</span>
        <h2 class="display d-2">Request a consultation</h2>
        <div class="body-text" style="margin-top:20px">
          <p>You can request a consultation or book by contacting the clinic. If you are booking for a child, please read the identification and consent requirements before attending. Tell us about any medical conditions, medication, allergies or bleeding problems when you book.</p>
        </div>
        <div class="btn-row" style="margin-top:26px">
          <a class="btn" href="' . esc_url( cil_book_url() ) . '" data-track="book-condition">Book a consultation</a>
          <a class="btn btn-ghost" href="' . esc_url( $clinic['phone']['href'] ) . '" data-track="call-condition">Call ' . esc_html( $clinic['phone']['display'] ) . '</a>
        </div>
        <div style="margin-top:26px">' . $urgent . '</div>
      </div>'
	);

	$blocks   = array();
	$blocks[] = cil_dyn_block(
		'cil/page-head',
		array(
			'eyebrow'    => $page['eyebrow'],
			'title'      => $page['h1'],
			'lede'       => $page['lede'],
			'reviewedBy' => cil_reviewed_by_haidar(),
			'crumbs'     => $page['crumbs'],
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => '',
			'wrap' => 'wrap-narrow',
		),
		array(
			cil_html_block( '<div class="body-text" data-reveal>' . $page['intro'] . '</div>' ),
		)
	);

	foreach ( $page['sections'] as $i => $section ) {
		$blocks[] = cil_dyn_block(
			'cil/text-section',
			array(
				'eyebrow' => $section['eyebrow'],
				'heading' => $section['heading'],
				'html'    => '<div class="body-text">' . $section['html'] . '</div>',
				'narrow'  => true,
				'banded'  => ( 0 === $i % 2 ),
			)
		);
	}

	$blocks[] = cil_section_block(
		array(
			'size' => 'section',
			'band' => '',
			'wrap' => 'wrap',
		),
		array(
			cil_split_block(
				array(
					cil_rich_html_block( $being_seen ),
					cil_dyn_block(
						'cil/callback-card',
						array(
							'eyebrow' => 'Request a call back',
							'title'   => 'Describe it to us',
							'formId'  => $page['slug'],
							'subject' => strtolower( $page['name'] ) . ' enquiry',
							'urgent'  => false,
						)
					),
				)
			),
		)
	);

	$blocks[] = cil_dyn_block(
		'cil/cta-band',
		array(
			'title' => 'Book a consultation',
			'text'  => 'You can request a consultation or book by contacting the clinic. Tell us about any medical conditions, medication, allergies or bleeding problems when you book.',
		)
	);

	if ( ! empty( $page['faqs'] ) ) {
		$blocks[] = cil_dyn_block(
			'cil/faq',
			array(
				'heading' => $page['name'] . ': questions',
				'items'   => $page['faqs'],
			)
		);
	}

	return cil_serialize_blocks( $blocks );
}
