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
				'title'       => 'Phimosis Treatment London | Tight Foreskin & Circumcision',
				'description' => 'Phimosis is a tight foreskin that can cause pain, cracking, infections or painful erections. Learn about steroid treatment, BXO and circumcision for phimosis in London.',
				'eyebrow'     => 'Condition · Tight foreskin',
				'h1'          => 'Phimosis (Tight Foreskin) Treatment in London',
				'lede'        => 'Phimosis means that the foreskin is too tight to retract comfortably over the head of the penis (glans).',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>A non-retractable foreskin can be completely normal in babies and young boys. However, phimosis can become a medical problem when the foreskin is scarred, causes pain, repeatedly cracks or becomes inflamed, interferes with urination or causes problems during erections or sexual activity.</p>
      <p>At Beverley Clinic in North-West London, we assess and treat children, teenagers and adults with tight foreskins and phimosis.</p>
      <p>Treatment depends on the patient\'s age, symptoms, appearance of the foreskin and the underlying cause. Circumcision can provide a definitive surgical treatment for troublesome phimosis.</p>'
				),
				'sections'    => array(),
				'faqs'        => array(
					array(
						'q' => 'What is phimosis?',
						'a' => 'Phimosis is a foreskin that is too tight to retract comfortably over the head of the penis.',
					),
					array(
						'q' => 'How do I know whether my foreskin is too tight?',
						'a' => 'Symptoms can include difficulty retracting, pain, cracking, a visible tight ring, recurrent inflammation, painful erections or problems during sex.',
					),
					array(
						'q' => 'Can phimosis develop later in life?',
						'a' => 'Yes. An adult who previously had a retractable foreskin can develop increasing tightness because of inflammation, scarring or a skin condition such as BXO / lichen sclerosus.',
					),
					array(
						'q' => 'Can phimosis go away by itself?',
						'a' => 'Normal childhood non-retractability often improves naturally with development. Pathological or scar-related phimosis is different and may require treatment.',
					),
					array(
						'q' => 'Can steroid cream cure phimosis?',
						'a' => 'Topical steroid treatment can help some patients, particularly where significant scarring is not present. Results depend on the underlying cause and recurrence can occur. From our experience, it is often a short term fix and the definitive treatment is a circumcision.',
					),
					array(
						'q' => 'Is circumcision a permanent treatment for phimosis?',
						'a' => 'Complete circumcision removes the foreskin and therefore removes foreskin-related phimosis.',
					),
					array(
						'q' => 'Can phimosis cause painful erections?',
						'a' => 'Yes. A tight foreskin can become painful when stretched during an erection and may crack or tear.',
					),
					array(
						'q' => 'Can phimosis affect sex?',
						'a' => 'Yes. Significant phimosis can cause discomfort during intercourse or masturbation and can sometimes lead to tearing or bleeding.',
					),
					array(
						'q' => 'Is a tight frenulum the same as phimosis?',
						'a' => 'No. A tight frenulum affects the band of tissue underneath the glans. It can cause symptoms similar to phimosis but may be treated differently.',
					),
					array(
						'q' => 'What is the difference between phimosis and paraphimosis?',
						'a' => 'Phimosis is a foreskin that is too tight to retract. Paraphimosis occurs when a retracted foreskin becomes trapped behind the glans and cannot be returned forward. Paraphimosis requires urgent medical attention.',
					),
					array(
						'q' => 'What is BXO?',
						'a' => 'BXO, also called male genital lichen sclerosis, is a chronic inflammatory skin condition that can cause whitening, scarring and tightening of the foreskin.',
					),
					array(
						'q' => 'How long does circumcision take to heal?',
						'a' => 'Healing varies between patients. The wound heals progressively between 2-6 weeks and the scar continues to mature afterwards.',
					),
					array(
						'q' => 'When can I have sex after circumcision?',
						'a' => 'As a general guide, avoid sexual intercourse and masturbation for at least 2 weeks and until the wound is fully healed. Some patients require longer.',
					),
					array(
						'q' => 'Can I speak to someone before booking?',
						'a' => 'Yes. If you are unsure whether you have phimosis, BXO, a frenulum problem or another foreskin condition, contact us before booking. You do not need to diagnose the problem yourself.',
					),
				),
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
				'title'       => 'Recurrent Balanitis Treatment London | Circumcision Clinic',
				'description' => 'Repeated balanitis can cause soreness, inflammation and foreskin problems. Learn about recurrent balanitis, phimosis, lichen sclerosis/BXO and circumcision in London.',
				'eyebrow'     => 'Condition · Inflammation of the glans',
				'h1'          => 'Recurrent Balanitis and Circumcision in London',
				'lede'        => 'Balanitis is inflammation of the head of the penis (glans). When the foreskin is also inflamed, this is sometimes called balanoposthitis.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>When recurrent balanitis is associated with a tight, difficult-to-retract or scarred foreskin, circumcision may provide a definitive solution by permanently removing the foreskin.</p>
      <p>Spreading redness with fever, severe swelling or difficulty passing urine requires urgent medical assessment. See also <a href="/conditions/phimosis">phimosis</a>, <a href="/adults">adult circumcision</a> and <a href="/aftercare">aftercare</a>.</p>'
				),
				'sections'    => array(),
				'faqs'        => array(
					array(
						'q' => 'What is balanitis?',
						'a' => 'Balanitis is inflammation of the head of the penis (glans). It can cause redness, swelling, soreness, itching and discomfort.',
					),
					array(
						'q' => 'What is balanoposthitis?',
						'a' => 'Balanoposthitis means inflammation affecting both the glans and foreskin.',
					),
					array(
						'q' => 'Why does my balanitis keep returning?',
						'a' => 'Recurrent balanitis can have several causes. A tight or difficult-to-retract foreskin, repeated irritation, infection, diabetes, scarring and lichen sclerosis / BXO can all be relevant.',
					),
					array(
						'q' => 'Can a tight foreskin cause balanitis?',
						'a' => 'Yes. A tight foreskin can make retraction and cleaning difficult and may be associated with recurrent inflammation.',
					),
					array(
						'q' => 'Can balanitis cause phimosis?',
						'a' => 'Repeated inflammation can contribute to scarring and increasing tightness of the foreskin in some patients.',
					),
					array(
						'q' => 'Does balanitis mean I need circumcision?',
						'a' => 'Not necessarily. A single episode of balanitis does not automatically require circumcision. Circumcision is particularly relevant to patients with significant recurrent balanitis where the foreskin is contributing to the problem.',
					),
					array(
						'q' => 'Can circumcision stop recurrent balanitis?',
						'a' => 'Circumcision removes the foreskin permanently and can provide a definitive treatment for the foreskin component of recurrent balanitis. It does not guarantee that the glans can never develop another inflammatory or dermatological condition.',
					),
					array(
						'q' => 'Can balanitis be caused by diabetes?',
						'a' => 'Recurrent genital inflammation can be associated with diabetes, particularly where blood glucose is poorly controlled. Adults with recurrent balanitis may need appropriate medical assessment for underlying causes.',
					),
					array(
						'q' => 'Is balanitis an STI?',
						'a' => 'Balanitis itself is not a specific sexually transmitted infection. However, some sexually transmitted conditions can cause similar symptoms, so appropriate sexual-health assessment may be required where there is a relevant risk.',
					),
					array(
						'q' => 'What is the difference between balanitis and phimosis?',
						'a' => 'Balanitis is inflammation of the glans. Phimosis is a foreskin that is too tight to retract. The two conditions can occur together.',
					),
					array(
						'q' => 'What is the difference between balanitis and BXO?',
						'a' => 'Balanitis describes inflammation of the glans. BXO is associated with lichen sclerosis and can cause characteristic chronic changes and scarring affecting the foreskin and penis.',
					),
					array(
						'q' => 'Why has my foreskin become tighter after repeated balanitis?',
						'a' => 'Repeated inflammation and healing can sometimes contribute to loss of elasticity and scarring, causing the foreskin to become progressively tighter.',
					),
					array(
						'q' => 'Can children get recurrent balanitis?',
						'a' => 'Yes. Children can experience balanitis and balanoposthitis. Repeated troublesome episodes may be a reason for circumcision after appropriate assessment.',
					),
					array(
						'q' => 'Can adults be circumcised under local anaesthetic?',
						'a' => 'Suitable adults at our clinic are normally circumcised using local anaesthetic.',
					),
					array(
						'q' => 'When can I have sex after circumcision?',
						'a' => 'Our clinic generally advises waiting at least two weeks and until the wound is sufficiently healed. If healing is incomplete at two weeks, wait longer and follow your individual aftercare instructions.',
					),
					array(
						'q' => 'Can I speak to someone before booking?',
						'a' => 'Yes. If you have recurrent balanitis and are unsure whether your foreskin is contributing to the problem, contact us and explain your symptoms.',
					),
				),
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
				'title'       => 'BXO Treatment London | Lichen Sclerosis Circumcision',
				'description' => 'BXO (lichen sclerosis) can cause a white, scarred and progressively tight foreskin. Learn about symptoms, phimosis and circumcision for BXO in London.',
				'eyebrow'     => 'Condition · Lichen sclerosis',
				'h1'          => 'BXO / Lichen Sclerosis in Men',
				'lede'        => 'BXO (balanitis xerotica obliterans), also known as male genital lichen sclerosis, is a chronic inflammatory condition that can cause whitening, scarring and progressive tightening of the foreskin.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>As the foreskin becomes less elastic, it can become increasingly difficult or painful to retract. Some patients develop cracking, bleeding, painful erections or a tight scarred ring around the foreskin opening.</p>
      <p>At Beverley Clinic in North-West London, we provide circumcision for children, teenagers and adults with BXO / lichen sclerosis affecting the foreskin.</p>
      <p>When circumcision is being performed for BXO, it is particularly important to recognise the abnormal scarred foreskin so that the affected foreskin is appropriately removed.</p>'
				),
				'sections'    => array(),
				'faqs'        => array(
					array(
						'q' => 'What does BXO stand for?',
						'a' => 'BXO stands for balanitis xerotica obliterans. It is associated with male genital lichen sclerosis.',
					),
					array(
						'q' => 'Is BXO the same as lichen sclerosis?',
						'a' => 'BXO is the term commonly used when lichen sclerosis affects the male genital area, particularly the foreskin and glans. You may also see the condition spelled “lichen sclerosus” in medical literature.',
					),
					array(
						'q' => 'What does BXO look like?',
						'a' => 'It can cause white or pale areas, a tight white ring, thickening, loss of elasticity, cracking and scarring of the foreskin.',
					),
					array(
						'q' => 'Does BXO cause phimosis?',
						'a' => 'Yes. Scarring caused by BXO can narrow the foreskin opening and cause pathological phimosis.',
					),
					array(
						'q' => 'Can children get BXO?',
						'a' => 'Yes. BXO can affect boys as well as adult men.',
					),
					array(
						'q' => 'Is BXO an STI?',
						'a' => 'No. BXO is not a sexually transmitted infection and is not contagious.',
					),
					array(
						'q' => 'Is BXO caused by poor hygiene?',
						'a' => 'No. It is not simply caused by poor personal hygiene.',
					),
					array(
						'q' => 'What treatment does Beverley Clinic offer for BXO?',
						'a' => 'For BXO affecting the foreskin, the treatment we provide is circumcision.',
					),
					array(
						'q' => 'Why does BXO circumcision need careful planning?',
						'a' => 'The foreskin can contain a defined area of abnormal, scarred tissue. We assess where this tissue is located so that the circumcision appropriately addresses the problematic foreskin.',
					),
					array(
						'q' => 'Can BXO affect the urinary opening?',
						'a' => 'Yes. Lichen sclerosis can sometimes affect the opening through which urine passes. A weak, spraying or altered urinary stream should be reported.',
					),
					array(
						'q' => 'Does circumcision cure BXO?',
						'a' => 'Circumcision removes the affected foreskin and can successfully treat disease limited to the foreskin. BXO can sometimes also affect the glans or urinary opening, so persistent abnormalities after circumcision still require appropriate medical assessment.',
					),
					array(
						'q' => 'Can I have BXO circumcision under local anaesthetic?',
						'a' => 'Suitable adults at Beverley Clinic are normally circumcised under local anaesthetic.',
					),
					array(
						'q' => 'When can I have sex after circumcision?',
						'a' => 'Our clinic generally advises avoiding sex and masturbation for at least two weeks and until the wound is sufficiently healed. If healing is incomplete, you should wait longer.',
					),
				),
				'schema'      => array(
					'@type'             => 'MedicalCondition',
					'alternateName'     => array( 'Balanitis xerotica obliterans', 'Genital lichen sclerosis', 'Male genital lichen sclerosis' ),
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
