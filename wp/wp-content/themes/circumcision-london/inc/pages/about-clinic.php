<?php
/**
 * About page (/about/).
 *
 * Sources:
 *  - Wix /about-circumcision-clinic: page heading, introduction, the
 *    "London Circumcision Clinic" points, the "Meet our Circumcision
 *    Practitioners" video and the "Why Choose Us?" list (verbatim).
 *  - Circumcision_in_London_WordPress_Website_Copy.docx, "PAGE 16 — About Us":
 *    SEO title/description and the three practitioner paragraphs.
 *
 * Not reproduced (flagged for client review): the Wix "Over 1000+ (5 star)
 * reviews on Google" line (the copy document says not to hard-code review
 * counts without a checked-on date), and the "Benefits of Circumcision" /
 * "Circumcision articles" sections (medical-benefit claims with 2000–2008
 * citations and dead links).
 *
 * The video is referenced by media-store key, resolved by cil_media_url()
 * (inc/media.php) like the Aftercare videos; it is not part of the
 * Aftercare manifest.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Managed-page catalog entry for /about/.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_about_clinic_pages() {
	return array(
		'about' => array(
			'slug'        => 'about',
			'name'        => 'About',
			'title'       => 'About Us | Circumcision Clinic in London',
			'description' => 'Meet the practitioners at Circumcision in London, a CQC-registered clinic in Edgware with more than 40 years of combined experience.',
			'eyebrow'     => 'About us',
			'h1'          => 'About Circumcision in London',
			'lede'        => 'Welcome to Circumcision in London, offering you professional services baby/ infant, children and adult circumcisions. Our circumcision practitioners have carried out thousands of successful circumcision for all ages. All circumcision are carried out under local anaesthetic to make sure the circumcision is pain-free throughout. We also give a comprehensive aftercare to make sure the patients and parents know exactly what to do to make the healing process as comfortable as possible.',
			'crumbs'      => array(
				array(
					'label' => 'About',
					'href'  => '',
				),
			),
			'faqs'        => array(),
		),
	);
}

/**
 * Storage key of the "Meet our Circumcision Practitioners" video.
 *
 * @return string
 */
function cil_about_video_key() {
	return 'about/meet-our-circumcision-practitioners.mp4';
}

/**
 * Heading block.
 *
 * @param string $text  Heading text.
 * @param int    $level Level.
 * @return array<string, mixed>
 */
function cil_about_heading( $text, $level = 2 ) {
	$class = 'wp-block-heading display d-2';
	$html  = '<h' . $level . ' class="' . $class . '">' . esc_html( $text ) . '</h' . $level . '>';
	return array(
		'blockName'    => 'core/heading',
		'attrs'        => array(
			'level'     => $level,
			'className' => 'display d-2',
		),
		'innerBlocks'  => array(),
		'innerHTML'    => $html,
		'innerContent' => array( $html ),
	);
}

/**
 * Checklist (core/list with the cil-checklist class).
 *
 * @param array<int, string> $items Item HTML (already escaped).
 * @return array<string, mixed>
 */
function cil_about_checklist( $items ) {
	$inner   = array();
	$content = array( '<ul class="cil-checklist wp-block-list">' );
	foreach ( $items as $item ) {
		$li        = '<li>' . $item . '</li>';
		$inner[]   = array(
			'blockName'    => 'core/list-item',
			'attrs'        => array(),
			'innerBlocks'  => array(),
			'innerHTML'    => $li,
			'innerContent' => array( $li ),
		);
		$content[] = null;
	}
	$content[] = '</ul>';
	return array(
		'blockName'    => 'core/list',
		'attrs'        => array( 'className' => 'cil-checklist' ),
		'innerBlocks'  => $inner,
		'innerHTML'    => '',
		'innerContent' => $content,
	);
}

/**
 * Gutenberg markup for /about/.
 *
 * @param string $slug Page slug.
 * @return string
 */
function cil_about_clinic_page_blocks( $slug ) {
	$pages = cil_about_clinic_pages();
	if ( empty( $pages[ $slug ] ) ) {
		return '';
	}
	$page = $pages[ $slug ];
	$link = static function ( $path, $text ) {
		return '<a href="' . esc_url( cil_path_url( $path ) ) . '">' . esc_html( $text ) . '</a>';
	};

	$blocks = array(
		cil_dyn_block(
			'cil/page-head',
			array(
				'eyebrow' => $page['eyebrow'],
				'title'   => $page['h1'],
				'lede'    => $page['lede'],
				'crumbs'  => $page['crumbs'],
			)
		),

		// London Circumcision Clinic (Wix points; the Google review-count line is
		// left out pending a checked-on date, per the client copy document).
		cil_section_block(
			array(
				'size'   => 'section',
				'band'   => 'bg-card edge',
				'wrap'   => 'wrap',
				'anchor' => 'clinic',
			),
			array(
				cil_split_block(
					array(
						cil_aftercare_group(
							array(
								cil_about_heading( 'London Circumcision Clinic' ),
								cil_about_checklist(
									array(
										'Patient can wear all clothes',
										'Patient can walk normally immediately',
										$link( '/babies', 'Baby and infant Circumcision' ),
										$link( '/children', 'Child Circumcision' ),
										$link( '/adults', 'Adult Circumcision' ),
									)
								),
							)
						),
						cil_dyn_block(
							'cil/figure',
							array(
								'name' => 'waiting-room',
							)
						),
					)
				),
			)
		),

		// Meet our Circumcision Practitioners: the Wix video plus the copy-document text.
		cil_section_block(
			array(
				'size'   => 'section',
				'band'   => '',
				'wrap'   => 'wrap',
				'anchor' => 'practitioners',
			),
			array(
				cil_dyn_block(
					'cil/video-grid',
					array(
						'items'     => array(
							array(
								'title'       => 'Meet our Circumcision Practitioners',
								'description' => 'Our two practitioners have more than 40 years of combined experience in the UK and abroad and have carried out thousands of circumcisions across different age groups.',
								'duration'    => '0:27',
								'mediaKey'    => cil_about_video_key(),
								'poster'      => cil_asset( 'images/about/meet-our-circumcision-practitioners.jpg' ),
								'mp4'         => '',
								'posterId'    => 0,
								'mp4Id'       => 0,
							),
						),
						'layout'    => 'list',
						'itemLevel' => 2,
						'showHead'  => false,
					)
				),
				cil_aftercare_group(
					array(
						cil_aftercare_p( 'The clinic\'s work includes baby and infant circumcision, circumcision for children and teenagers, adult circumcision, medical foreskin problems, re-circumcision and practitioner training.' ),
						cil_aftercare_p( 'Procedures are carried out in a dedicated clinical environment with attention to infection control, consent, anaesthesia, aftercare and follow-up.' ),
						cil_aftercare_p( $link( '/about/#samir', 'Meet the practitioners →' ) ),
					)
				),
			)
		),

		// Why Choose Us? (Wix list, verbatim).
		cil_section_block(
			array(
				'size'   => 'section',
				'band'   => 'bg-warm',
				'wrap'   => 'wrap',
				'anchor' => 'why-choose-us',
			),
			array(
				cil_aftercare_group(
					array(
						cil_about_heading( 'Why Choose Us?' ),
						cil_about_checklist(
							array(
								'Experienced - over 40 years of combined experience between the 2 practitioners',
								'Hygienic and modern clinic',
								'Care Quality Commission (CQC) registered',
								'Plastibell/ Circumplast (ring) and Forceps Guided (traditional) methods used accordingly',
								'Comprehensive aftercare',
								'Simple but thorough aftercare sheet given',
								'GP letter given to update medical records',
								'School and work letters given if needed',
								'Free street parking - plenty of spaces',
								'Easy commute - no Congestion Charge',
							)
						),
					)
				),
			)
		),

		cil_dyn_block(
			'cil/cta-band',
			array(
				'title'      => 'Book a consultation',
				'text'       => 'You can request a consultation or book by contacting the clinic. Tell us about any medical conditions, medication, allergies or bleeding problems when you book.',
				'eyebrow'    => 'Talk to us',
				'ctaLabel'   => 'Book a consultation',
				'ctaUrl'     => cil_path_url( '/book' ),
				'phoneLabel' => 'Call 020 8951 3794',
				'phoneUrl'   => 'tel:+442089513794',
				'cardTitle'  => 'Would you rather write than speak?',
				'cardHtml'   => '<p style="margin-top:10px">A phone call asks you to be fluent and composed in the moment. <a href="https://wa.me/447886779958" rel="noopener" target="_blank" data-track="whatsapp-cta" style="color:var(--blue-deep)">WhatsApp</a> does not. You can take your time, translate, and forward the answer to whoever else in the family needs to see it.</p><p style="margin-top:12px">We reply during opening hours, and we are used to the questions people feel awkward asking out loud.</p>',
			)
		),
	);

	return cil_serialize_blocks( $blocks );
}
