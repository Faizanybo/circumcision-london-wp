<?php
/**
 * Religious, re-circumcision and buried-penis pages.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Data for /religious, /re-circumcision and /buried-penis.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_extra_pages() {
	return array(
		'religious'       => array(
			'slug'        => 'religious',
			'name'        => 'Religious and cultural',
			'title'       => 'Religious & Cultural Circumcision London | Edgware Clinic',
			'description' => 'Circumcision for religious and cultural reasons at a CQC-registered clinic in Edgware. Sunnah practice catered for, all faiths welcome, six languages spoken.',
			'eyebrow'     => 'All faiths and backgrounds · Six languages',
			'h1'          => 'Religious and cultural circumcision',
			'lede'        => 'Religious and cultural circumcision is a large part of our work. We welcome families and adult patients from all faiths and backgrounds and aim to provide a respectful clinical service that understands the importance of the decision.',
			'crumbs'      => array(
				array(
					'label' => 'Who we see',
					'href'  => cil_path_url( '/babies' ),
				),
				array(
					'label' => 'Religious and cultural',
					'href'  => '',
				),
			),
			'faqs'        => array(),
		),
		're-circumcision' => array(
			'slug'        => 're-circumcision',
			'name'        => 'Re-circumcision',
			'title'       => 'Re-Circumcision & Revision London | Beverley Clinic',
			'description' => 'Correcting a circumcision done elsewhere: scar revision, tightening a loose result, removing excess skin. Patients travel to our Edgware clinic from across the UK.',
			'eyebrow'     => 'Revision work · Quoted after examination',
			'h1'          => 'Re-circumcision and revision',
			'lede'        => 'We assess children and adults who have previously been circumcised but are unhappy with the result or have a clinical problem that needs correction.',
			'crumbs'      => array(
				array(
					'label' => 'Who we see',
					'href'  => cil_path_url( '/babies' ),
				),
				array(
					'label' => 'Re-circumcision',
					'href'  => '',
				),
			),
			'faqs'        => array(),
		),
		'buried-penis'    => array(
			'slug'        => 'buried-penis',
			'name'        => 'Buried penis',
			'title'       => 'Buried Penis and Circumcision | Beverley Clinic London',
			'description' => 'What a buried penis is, why circumcision does not cause it, how to keep the area clean afterwards, and when we advise waiting rather than operating.',
			'eyebrow'     => 'Common, treatable, and not caused by circumcision',
			'h1'          => 'Buried or hidden penis',
			'lede'        => 'A buried penis is when some or all of the penis is hidden within the surrounding pubic tissue. It may be present from childhood or become more noticeable with changes in body shape or weight.',
			'crumbs'      => array(
				array(
					'label' => 'Reasons',
					'href'  => cil_path_url( '/conditions/phimosis' ),
				),
				array(
					'label' => 'Buried penis',
					'href'  => '',
				),
			),
			'faqs'        => array(),
		),
	);
}

/**
 * Gutenberg markup for one extra service page.
 *
 * @param string $slug Page slug.
 * @return string
 */
function cil_extra_page_blocks( $slug ) {
	$pages = cil_extra_pages();
	if ( empty( $pages[ $slug ] ) ) {
		return '';
	}

	if ( 'religious' === $slug ) {
		return cil_religious_blocks( $pages[ $slug ] );
	}
	if ( 're-circumcision' === $slug ) {
		return cil_recirc_blocks( $pages[ $slug ] );
	}
	if ( 'buried-penis' === $slug ) {
		return cil_buried_blocks( $pages[ $slug ] );
	}

	return '';
}

/**
 * Shared page-head block for extra pages.
 *
 * @param array<string, mixed> $page Page data.
 * @return array<string, mixed>
 */
function cil_extra_page_head_block( $page ) {
	return cil_dyn_block(
		'cil/page-head',
		array(
			'eyebrow'    => $page['eyebrow'],
			'title'      => $page['h1'],
			'lede'       => $page['lede'],
			'reviewedBy' => cil_reviewed_by_haidar(),
			'crumbs'     => $page['crumbs'],
		)
	);
}

/**
 * Append FAQ only when the page has items from source copy.
 *
 * @param array<int, array<string, mixed>> $blocks Blocks.
 * @param array<string, mixed>             $page   Page data.
 * @return array<int, array<string, mixed>>
 */
function cil_extra_maybe_faq( $blocks, $page ) {
	if ( empty( $page['faqs'] ) ) {
		return $blocks;
	}
	$blocks[] = cil_dyn_block(
		'cil/faq',
		array(
			'heading' => $page['name'] . ': questions',
			'items'   => $page['faqs'],
		)
	);
	return $blocks;
}

/**
 * /religious block list.
 *
 * @param array<string, mixed> $page Page data.
 * @return string
 */
function cil_religious_blocks( $page ) {
	$clinic = cil_clinic();

	$intro = cil_proto_html(
		'<p>Our Muslim practitioners can carry out circumcision in accordance with the Sunnah. The same clinical standards apply whatever the reason for circumcision: assessment first, an age-appropriate method, local anaesthetic, written aftercare and access to follow-up support.</p>
        <p>We also understand that it is customary to do male circumcision is some cultures such as Africa, Philippines, Fiji and other parts of the world.</p>
        <p>We see patients from London, elsewhere in the UK and from abroad. If you are travelling a long distance, contact us before booking so we can discuss timing, documentation and follow-up arrangements.</p>'
	);

	$blocks   = array();
	$blocks[] = cil_extra_page_head_block( $page );

	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => '',
			'wrap' => 'wrap',
		),
		array(
			cil_split_block(
				array(
					cil_html_block( '<div class="body-text" data-reveal>' . $intro . '</div>' ),
					cil_dyn_block(
						'cil/spec-panel',
						array(
							'eyebrow' => 'Practical detail',
							'rows'    => array(
								array( 'k' => 'Sunnah practice', 'v' => 'Yes, by our Muslim practitioners' ),
								array( 'k' => 'All faiths', 'v' => 'Families and adult patients welcome' ),
								array( 'k' => 'Anaesthetic', 'v' => 'Local anaesthetic' ),
								array( 'k' => 'Aftercare', 'v' => 'Written aftercare and follow-up support' ),
								array( 'k' => 'Languages', 'v' => $clinic['languages'] ),
								array( 'k' => 'Setting', 'v' => 'CQC registered clinic' ),
							),
							'note'    => '',
						)
					),
				)
			),
		)
	);

	$blocks[] = cil_dyn_block(
		'cil/cta-band',
		array(
			'title' => 'Ask about a date',
			'text'  => 'If you are travelling a long distance, contact us before booking so we can discuss timing, documentation and follow-up arrangements.',
		)
	);

	$blocks   = cil_extra_maybe_faq( $blocks, $page );
	$blocks[] = cil_section_block(
		array(
			'size' => 'section',
			'band' => 'bg-warm',
			'wrap' => 'wrap',
		),
		array(
			cil_dyn_block(
				'cil/section-head',
				array(
					'eyebrow' => 'By age',
					'heading' => 'The procedure itself, by age group',
					'lede'    => '',
					'display' => 'd-2',
				)
			),
			cil_dyn_block(
				'cil/group-cards',
				array(
					'level'   => 3,
					'exclude' => '',
					'banded'  => false,
				)
			),
		)
	);

	return cil_serialize_blocks( $blocks );
}

/**
 * /re-circumcision block list.
 *
 * @param array<string, mixed> $page Page data.
 * @return string
 */
function cil_recirc_blocks( $page ) {
	$intro = cil_proto_html(
		'<p>Reasons for revision can include excess remaining foreskin, an uneven result, prominent scarring or another issue identified on examination.</p>
        <p>Revision is not a one-size-fits-all procedure. A consultation is usually needed so the practitioner can examine the area, understand what you would like corrected and explain what is realistically achievable.</p>
        <p>The technique, anaesthetic, healing time and price depend on the individual case. Contact the clinic for an assessment and quotation. See also <a href="/conditions/phimosis">phimosis</a> if tightness after a previous circumcision is the concern.</p>'
	);

	$blocks   = array();
	$blocks[] = cil_extra_page_head_block( $page );

	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => '',
			'wrap' => 'wrap',
		),
		array(
			cil_split_block(
				array(
					cil_html_block( '<div class="body-text" data-reveal>' . $intro . '</div>' ),
					cil_dyn_block(
						'cil/spec-panel',
						array(
							'eyebrow' => 'At a glance',
							'rows'    => array(
								array( 'k' => 'Who', 'v' => 'Children and adults' ),
								array( 'k' => 'Consultation', 'v' => 'Usually needed first' ),
								array( 'k' => 'Price', 'v' => 'Quoted after examination' ),
								array( 'k' => 'Technique', 'v' => 'Depends on the individual case' ),
							),
							'note'    => '',
						)
					),
				)
			),
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section',
			'band' => '',
			'wrap' => 'wrap',
		),
		array(
			cil_split_block(
				array(
					cil_html_block(
						'<div data-reveal>
        <span class="caps eyebrow">Next step</span>
        <h2 class="display d-2">Contact the clinic for an assessment</h2>
        <div class="body-text" style="margin-top:20px">
          <p>A consultation is usually needed so the practitioner can examine the area, understand what you would like corrected and explain what is realistically achievable.</p>
        </div>
        <div class="btn-row" style="margin-top:26px">
          <a class="btn" href="' . esc_url( cil_book_url() ) . '" data-track="book-recirc">Book a consultation</a>
        </div>
      </div>'
					),
					cil_dyn_block(
						'cil/callback-card',
						array(
							'eyebrow' => 'Request a call back',
							'title'   => 'Tell us what happened',
							'formId'  => 'recirc',
							'subject' => 're-circumcision enquiry',
							'urgent'  => false,
						)
					),
				)
			),
		)
	);

	$blocks[] = cil_dyn_block( 'cil/cta-band', array() );
	$blocks   = cil_extra_maybe_faq( $blocks, $page );

	return cil_serialize_blocks( $blocks );
}

/**
 * /buried-penis block list.
 *
 * @param array<string, mixed> $page Page data.
 * @return string
 */
function cil_buried_blocks( $page ) {
	$clinic   = cil_clinic();
	$aftercare = cil_path_url( '/aftercare' );

	$intro = cil_proto_html(
		'<p>It occurs when the pubic area is relatively prominent compared to the length of the penis. It is most common in babies when the baby is growing and has a lot of baby fat but the penis is not growing at the same rate. It often improves as the baby loses weight/ body composition changes and the penis grows. In older patients, the penis will become more prominent when they lose weight and fat in the pubic area.</p>
        <p>We treat many patients with a buried penis and know how to recognize it. We are able to provide the circumcision that is appropriate to the size and length of the penis. We will also show the patient/ parents what the ongoing aftercare that is required with a buried penis.</p>'
	);

	$aftercare_html = cil_proto_html(
		'<div class="body-text">
        <p>Keeping the circumcision line clean and dry is particularly important when the penis tends to retract into the surrounding tissue. The clinic will show you how to expose the area safely as part of the individual aftercare plan.</p>
        <p>For a ring circumcision, the timing of this care differs from a forceps-guided circumcision, so follow the instructions given specifically for the method used.</p>
        <p>The website overview is not a substitute for the clinic\'s current aftercare sheet or videos. See the <a href="' . esc_url( $aftercare ) . '">aftercare page</a>.</p>
      </div>'
	);

	$blocks   = array();
	$blocks[] = cil_extra_page_head_block( $page );

	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => '',
			'wrap' => 'wrap-narrow',
		),
		array(
			cil_html_block( '<div class="body-text" data-reveal>' . $intro . '</div>' ),
		)
	);

	$blocks[] = cil_dyn_block(
		'cil/text-section',
		array(
			'eyebrow' => 'Aftercare',
			'heading' => 'Aftercare where the penis remains buried',
			'html'    => $aftercare_html,
			'narrow'  => true,
			'banded'  => true,
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section',
			'band' => '',
			'wrap' => 'wrap',
		),
		array(
			cil_split_block(
				array(
					cil_html_block(
						'<div data-reveal>
        <span class="caps eyebrow">Next step</span>
        <h2 class="display d-2">Ask us about circumcision with a buried penis</h2>
        <div class="body-text" style="margin-top:20px">
          <p>We treat many patients with a buried penis and know how to recognize it. We are able to provide the circumcision that is appropriate to the size and length of the penis.</p>
        </div>
        <div class="btn-row" style="margin-top:26px">
          <a class="btn" href="' . esc_url( cil_book_url() ) . '" data-track="book-buried">Book a consultation</a>
          <a class="btn btn-ghost" href="' . esc_url( $clinic['phone']['href'] ) . '" data-track="call-buried">Call ' . esc_html( $clinic['phone']['display'] ) . '</a>
        </div>
      </div>'
					),
					cil_dyn_block(
						'cil/callback-card',
						array(
							'eyebrow' => 'Request a call back',
							'title'   => 'Ask us about it',
							'formId'  => 'buried',
							'subject' => 'buried penis enquiry',
							'urgent'  => false,
						)
					),
				)
			),
		)
	);

	$blocks[] = cil_dyn_block( 'cil/cta-band', array() );
	$blocks   = cil_extra_maybe_faq( $blocks, $page );

	return cil_serialize_blocks( $blocks );
}
