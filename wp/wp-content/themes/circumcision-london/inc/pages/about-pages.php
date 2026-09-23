<?php
/**
 * Team, testimonials and courses pages from the prototype.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prototype data for /team, /testimonials and /courses.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_about_pages() {
	$clinic = cil_clinic();
	$origin = untrailingslashit( home_url() );

	return array(
		'team'          => array(
			'slug'        => 'team',
			'path'        => '/team',
			'name'        => 'Our team',
			'title'       => 'Our Practitioners | Circumcision Clinic in London',
			'description' => 'Dr Haidar Al-Ali, who carries out most circumcisions here and trains other doctors, and Mr Samir Al-Ali, plastic surgeon. Qualifications and registrations in full.',
			'eyebrow'     => 'CQC registered · More than 40 years combined experience',
			'h1'          => 'Meet our circumcision practitioners',
			'lede'        => 'Our two practitioners have more than 40 years of combined experience in the UK and abroad and have carried out thousands of circumcisions across different age groups.',
			'crumbs'      => array(
				array(
					'label' => 'Our team',
					'href'  => '',
				),
			),
			'schema'      => array(
				array(
					'@type'           => 'Physician',
					'@id'             => $origin . '/team#haidar',
					'name'            => 'Dr Haidar Al-Ali',
					'honorificSuffix' => 'BDS, MFDS RCPS (Glasg)',
					'jobTitle'        => 'Circumcision practitioner',
					'worksFor'        => array(
						'@id' => $origin . '/#clinic',
					),
					'alumniOf'        => array(
						'@type' => 'CollegeOrUniversity',
						'name'  => "King's College London",
					),
					'memberOf'        => array(
						array(
							'@type' => 'Organization',
							'name'  => 'Royal College of Physicians and Surgeons of Glasgow',
						),
					),
					'knowsAbout'      => array( 'Circumcision', 'Frenuloplasty', 'Minor surgery' ),
					'knowsLanguage'   => array( 'English', 'Arabic' ),
					'image'           => cil_asset( 'images/dr-haidar-700.jpg' ),
				),
				array(
					'@type'            => 'Physician',
					'@id'              => $origin . '/team#samir',
					'name'             => 'Mr Samir Al-Ali',
					'honorificSuffix'  => 'MB ChB, FICMS, MRCS Ed',
					'jobTitle'         => 'Plastic surgeon',
					'medicalSpecialty' => 'PlasticSurgery',
					'worksFor'         => array(
						'@id' => $origin . '/#clinic',
					),
					'memberOf'         => array(
						array(
							'@type' => 'Organization',
							'name'  => 'Royal College of Surgeons of Edinburgh',
						),
						array(
							'@type' => 'Organization',
							'name'  => 'British College of Aesthetic Medicine',
						),
					),
					'image'            => cil_asset( 'images/dr-samir-700.jpg' ),
				),
			),
		),
		'testimonials'  => array(
			'slug'        => 'testimonials',
			'path'        => '/testimonials',
			'name'        => 'Testimonials',
			'title'       => 'Patient Testimonials | Circumcision Clinic in London',
			'description' => 'What parents and patients say about circumcision at our Edgware clinic, plus 4.9 from 2,092 Google reviews. Most of our patients are sent by family and friends.',
			'eyebrow'     => $clinic['reviews']['rating'] . ' from ' . $clinic['reviews']['count_display'] . ' Google reviews',
			'h1'          => 'What patients and parents say',
			'lede'        => 'A mix of feedback given to us directly, immediately after the circumcision, and reviews left publicly on Google. The figure above was checked on ' . $clinic['reviews']['verified'] . ' and we refresh it quarterly.',
			'crumbs'      => array(
				array(
					'label' => 'Testimonials',
					'href'  => '',
				),
			),
		),
		'courses'       => array(
			'slug'        => 'courses',
			'path'        => '/courses',
			'name'        => 'For doctors',
			'title'       => 'Circumcision Training Course for Doctors | London',
			'description' => 'A five-day, one-to-one circumcision training course for doctors in London. Plastibell, forceps guided and free hand methods, live surgery and practice setup.',
			'eyebrow'     => 'One-to-one programme',
			'h1'          => 'Circumcision training for doctors',
			'lede'        => 'We provide training for doctors who want to develop their knowledge and skills in male circumcision. The course combines theory with observation of clinical practice and is designed to cover circumcision across different age groups.',
			'crumbs'      => array(
				array(
					'label' => 'For doctors',
					'href'  => '',
				),
			),
			'faqs'        => array(),
		),
	);
}

/**
 * Block markup for a team/testimonials/courses page.
 *
 * @param string $slug Page slug.
 * @return string
 */
function cil_about_page_blocks( $slug ) {
	if ( 'team' === $slug ) {
		return cil_team_page_blocks();
	}
	if ( 'testimonials' === $slug ) {
		return cil_testimonials_page_blocks();
	}
	if ( 'courses' === $slug ) {
		return cil_courses_page_blocks();
	}
	return '';
}

/**
 * Profile column used on the team page.
 *
 * @param array<string, mixed> $profile Profile copy.
 * @return string
 */
function cil_team_profile_html( $profile ) {
	$spec = cil_render_part(
		'spec-list',
		array(
			'rows' => $profile['rows'],
		)
	);

	$note = '';
	if ( ! empty( $profile['note'] ) ) {
		$note = '<p class="muted" style="margin-top:14px;font-size:15px">' . cil_rich_text( $profile['note'] ) . '</p>';
	}

	return '<div data-reveal data-reveal-delay="100">
        <span class="caps eyebrow">' . esc_html( $profile['eyebrow'] ) . '</span>
        <h2 class="display d-1">' . esc_html( $profile['name'] ) . '</h2>
        <p class="caps" style="color:var(--muted);margin-top:8px;letter-spacing:.06em">' . esc_html( $profile['letters'] ) . '</p>
        <div class="body-text" style="margin-top:20px">' . $profile['bio'] . '</div>
        <div style="margin-top:26px">
          <span class="caps" style="color:var(--blue);display:block;margin-bottom:14px">' . esc_html( $profile['spec_label'] ) . '</span>
          ' . $spec . $note . '
        </div>
      </div>';
}

/**
 * Gutenberg markup for /team.
 *
 * @return string
 */
function cil_team_page_blocks() {
	$page   = cil_about_pages()['team'];
	$clinic = cil_clinic();

	$haidar = cil_team_profile_html(
		array(
			'eyebrow'    => 'Circumcision lead',
			'name'       => 'Dr Haidar Al-Ali',
			'letters'    => 'BDS · MFDS RCPS (Glasg)',
			'spec_label' => 'Qualification and registration',
			'bio'        => cil_proto_html(
				'<p>Dr Al-Ali qualified from King\'s College London in 2013 and carries out the great majority of the
          circumcisions performed at this clinic, for babies, boys and adults alike.</p>
          <p>He also trains other doctors in circumcision technique. That is a more useful description of where this
          clinic\'s experience comes from than any claim we could make about ourselves: the technique is taught here,
          which means it gets examined and defended rather than simply repeated.</p>'
			),
			'rows'       => array(
				array(
					'k' => 'Qualified',
					'v' => "BDS, King's College London, 2013",
				),
				array(
					'k' => 'Membership',
					'v' => 'MFDS, RCPS Glasgow',
				),
				array(
					'k' => 'Register',
					'v' => 'General Dental Council (UK)',
				),
				array(
					'k' => 'Teaches',
					'v' => 'Circumcision technique to other practitioners',
				),
				array(
					'k' => 'Languages',
					'v' => 'English, Arabic',
				),
			),
			'note'       => '',
		)
	);

	$samir = cil_team_profile_html(
		array(
			'eyebrow'    => 'Plastic surgeon',
			'name'       => 'Mr Samir Al-Ali',
			'letters'    => 'MB ChB · FICMS · MRCS Ed',
			'spec_label' => 'Registration and membership',
			'bio'        => cil_proto_html(
				'<p>Mr Al-Ali is a board-certified plastic surgeon with more than 35 years in practice. At this clinic he
          takes the more complex adult work: revision of an earlier circumcision, cases with significant scarring,
          and anything where the reconstruction needs a plastic surgeon\'s hands.</p>
          <p>He also runs the cosmetic surgery side of the practice at
          <a href="' . esc_url( $clinic['parent_url'] ) . '" rel="noopener" target="_blank">Beverley Clinic</a>, which is the same building.</p>'
			),
			'rows'       => array(
				array(
					'k' => 'Medical register',
					'v' => 'General Medical Council (UK)',
				),
				array(
					'k' => 'Also registered',
					'v' => 'Dubai Health Authority, Kuwait MoH',
				),
				array(
					'k' => 'Fellowship',
					'v' => 'Royal College of Surgeons of Edinburgh',
				),
				array(
					'k' => 'Member',
					'v' => 'British College of Aesthetic Medicine',
				),
				array(
					'k' => 'Member',
					'v' => 'European College of Aesthetic Medicine & Surgery',
				),
			),
			'note'       => 'You can check any UK doctor yourself on the <a href="https://www.gmc-uk.org/registration-and-licensing/the-medical-register" rel="noopener" target="_blank" style="color:var(--blue-deep)">GMC medical register</a>. Ask us for the registration number at your consultation and we will give it to you.',
		)
	);

	$blocks   = array();
	$blocks[] = cil_dyn_block(
		'cil/page-head',
		array(
			'eyebrow' => $page['eyebrow'],
			'title'   => $page['h1'],
			'lede'    => $page['lede'],
			'crumbs'  => $page['crumbs'],
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => '',
			'wrap' => 'wrap-narrow',
		),
		array(
			cil_html_block(
				'<div class="body-text" data-reveal>' .
				cil_proto_html(
					'<p>The clinic\'s work includes baby and infant circumcision, circumcision for children and teenagers, adult circumcision, medical foreskin problems, re-circumcision and practitioner training.</p>
        <p>Procedures are carried out in a dedicated clinical environment with attention to infection control, consent, anaesthesia, aftercare and follow-up.</p>'
				) .
				'</div>'
			),
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => 'bg-card edge',
			'wrap' => 'wrap',
		),
		array(
			cil_dyn_block(
				'cil/section-head',
				array(
					'eyebrow' => 'Why patients choose the clinic',
					'heading' => 'What we provide',
					'lede'    => '',
					'display' => 'd-2',
				)
			),
			cil_dyn_block(
				'cil/info-cards',
				array(
					'items' => array(
						array( 'title' => 'CQC registered', 'body' => 'A dedicated circumcision service in a registered clinical environment.' ),
						array( 'title' => 'Experienced practitioners', 'body' => 'More than 40 years of combined experience in the UK and abroad.' ),
						array( 'title' => 'Age-appropriate methods', 'body' => 'Methods chosen according to age, anatomy and clinical circumstances.' ),
						array( 'title' => 'Aftercare and follow-up', 'body' => 'Comprehensive written aftercare and follow-up support during healing.' ),
						array( 'title' => 'Letters', 'body' => 'GP, school and work letters where appropriate.' ),
						array( 'title' => 'Location', 'body' => 'North-West London location with public transport links.' ),
					),
				)
			),
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size'   => 'section-sm',
			'band'   => '',
			'wrap'   => 'wrap',
			'anchor' => 'haidar',
		),
		array(
			cil_split_block(
				array(
					cil_dyn_block(
						'cil/figure',
						array(
							'name' => 'dr-haidar',
						)
					),
					cil_html_block( $haidar ),
				)
			),
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size'   => 'section',
			'band'   => 'bg-card edge',
			'wrap'   => 'wrap',
			'anchor' => 'samir',
		),
		array(
			cil_split_block(
				array(
					cil_html_block( $samir ),
					cil_dyn_block(
						'cil/figure',
						array(
							'name' => 'dr-samir',
						)
					),
				),
				true
			),
		)
	);

	$blocks[] = cil_dyn_block( 'cil/trust-strip', array() );

	$blocks[] = cil_dyn_block(
		'cil/cta-band',
		array(
			'title' => 'Meet whoever would be doing it',
			'text'  => 'The consultation is where you find out whether the procedure is right, what it costs in writing, and who is holding the instrument. Nothing is booked until you say so.',
		)
	);

	return cil_serialize_blocks( $blocks );
}

/**
 * Gutenberg markup for /testimonials.
 *
 * @return string
 */
function cil_testimonials_page_blocks() {
	$page   = cil_about_pages()['testimonials'];
	$clinic = cil_clinic();

	$badges = '<div class="rating-badges" data-reveal>' .
		cil_render_part(
			'rating-badge',
			array(
				'score'  => $clinic['reviews']['rating'],
				'strong' => 'Read all ' . $clinic['reviews']['count_display'] . ' on Google',
				'sub'    => 'Opens Google Maps in a new tab',
				'href'   => $clinic['reviews']['read_url'],
				'track'  => 'reviews-read-testimonials',
			)
		) .
		cil_render_part(
			'rating-badge',
			array(
				'score'       => 'CQC',
				'strong'      => 'Registered and rated ' . $clinic['cqc_rating'],
				'sub'         => 'Care Quality Commission, new tab',
				'href'        => $clinic['cqc_url'],
				'track'       => 'cqc-read-testimonials',
				'small_score' => true,
			)
		) .
		'</div>';

	$why = cil_proto_html(
		'<div data-reveal>
        <span class="caps eyebrow">Why it matters to us</span>
        <h2 class="display d-1">Most people here were sent by somebody they trust</h2>
        <div class="body-text" style="margin-top:22px">
          <p>The great majority of our patients come by word of mouth, from family and friends whose sons we have
          already circumcised. That is how this clinic has grown, and it is a harder thing to earn than a good
          advert.</p>
          <p>What we notice most is the families who come back. Some return after many years, whenever there is a new
          son. Others bring a brother, then a nephew, then a cousin. We have families we have now seen across three
          children and the better part of a decade.</p>
          <p>None of that survives one bad experience, which is the honest reason we do the things we do: the test
          before we start, the call the day after, the follow-ups that stay free until you are healed.</p>
        </div>
      </div>'
	);

	$callout_body = '<p>You will not find rankings or comparative claims about other clinics anywhere here.
      Claims of that kind have to be substantiated with evidence held before publication, health services are held to
      a stricter standard under the CAP Code, and they are worth considerably less than a fact you can check
      yourself.</p>
      <p>So instead: ' . esc_html( $clinic['reviews']['rating'] ) . ' from ' . esc_html( $clinic['reviews']['count_display'] ) . ' Google reviews,
      checked on ' . esc_html( $clinic['reviews']['verified'] ) . '. Registered with the Care Quality Commission and rated
      ' . esc_html( $clinic['cqc_rating'] ) . ', report public. Two practitioners named on this site with their qualifications. Every one of
      those can be checked, and three of them without asking us.</p>';

	$blocks   = array();
	$blocks[] = cil_dyn_block(
		'cil/page-head',
		array(
			'eyebrow' => $page['eyebrow'],
			'title'   => $page['h1'],
			'lede'    => $page['lede'],
			'crumbs'  => $page['crumbs'],
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => '',
			'wrap' => 'wrap',
		),
		array(
			cil_rich_html_block( $badges ),
		)
	);

	$blocks[] = cil_dyn_block(
		'cil/video-grid',
		array(
			'items' => array(),
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section',
			'band' => 'bg-card edge',
			'wrap' => 'wrap',
		),
		array(
			cil_dyn_block(
				'cil/section-head',
				array(
					'eyebrow' => 'In their own words',
					'heading' => 'Feedback given to us on the day',
					'lede'    => 'These were left by name on our public reviews. We have not edited them beyond trimming for length.',
					'display' => 'd-1',
				)
			),
			cil_dyn_block(
				'cil/quotes-grid',
				array(
					'quotes' => cil_testimonials(),
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
					cil_html_block( $why ),
					cil_dyn_block(
						'cil/callback-card',
						array(
							'eyebrow' => 'Request a call back',
							'title'   => 'Ask us anything',
							'formId'  => 'testimonials',
							'subject' => 'testimonials page enquiry',
							'urgent'  => false,
						)
					),
				)
			),
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section',
			'band' => 'bg-warm',
			'wrap' => 'wrap-narrow',
		),
		array(
			cil_dyn_block(
				'cil/callout',
				array(
					'title'  => 'No superlatives on this website',
					'body'   => $callout_body,
					'urgent' => false,
					'level'  => 2,
				)
			),
		)
	);

	$blocks[] = cil_dyn_block( 'cil/cta-band', array() );

	return cil_serialize_blocks( $blocks );
}

/**
 * Gutenberg markup for /courses.
 *
 * @return string
 */
function cil_courses_page_blocks() {
	$page   = cil_about_pages()['courses'];
	$clinic = cil_clinic();

	$intro = cil_proto_html(
		'<div class="body-text" data-reveal>
        <p>One-to-one programme. Training topics include relevant anatomy, indications and contraindications, the Plastibell / ring method, the forceps-guided method, instruments and equipment, suturing and wound closure, aftercare and complications, therapeutic circumcision and phimosis, frenulum procedures, re-circumcision, buried penis considerations, and practice setup, documents and suppliers.</p>
        <p>The existing course information also lists a certificate of completion, handouts, observation of live procedures, opportunities to assist where appropriate, and ongoing communication/support.</p>
      </div>'
	);

	$included = cil_proto_html(
		'<div data-reveal>
        <span class="caps eyebrow">Included</span>
        <h2 class="display d-1">What the course covers</h2>
        <div class="body-text" style="margin-top:20px"><ul>
          <li>Certificate of completion</li>
          <li>Handouts</li>
          <li>Observation of live procedures</li>
          <li>Opportunities to assist where appropriate</li>
          <li>Ongoing communication and support</li>
        </ul></div>
        <div class="btn-row" style="margin-top:28px">
          <a class="btn" href="' . esc_url( $clinic['phone']['href'] ) . '" data-track="call-courses">Call ' . esc_html( $clinic['phone']['display'] ) . '</a>
        </div>
      </div>'
	);


	$blocks   = array();
	$blocks[] = cil_dyn_block(
		'cil/page-head',
		array(
			'eyebrow' => $page['eyebrow'],
			'title'   => $page['h1'],
			'lede'    => $page['lede'],
			'crumbs'  => $page['crumbs'],
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => '',
			'wrap' => 'wrap',
		),
		array(
			cil_split_block(
				array(
					cil_html_block( $intro ),
					cil_dyn_block(
						'cil/spec-panel',
						array(
							'eyebrow' => 'The course',
							'rows'    => array(
								array( 'k' => 'Format', 'v' => 'One to one' ),
								array( 'k' => 'Theory', 'v' => 'Combined with observation of clinical practice' ),
								array( 'k' => 'Live procedures', 'v' => 'Observation, with opportunities to assist where appropriate' ),
								array( 'k' => 'Methods', 'v' => 'Plastibell / ring and forceps-guided' ),
								array( 'k' => 'Ages covered', 'v' => 'Different age groups' ),
								array( 'k' => 'Certificate', 'v' => 'Certificate of completion' ),
								array( 'k' => 'After the course', 'v' => 'Ongoing communication and support' ),
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
			'band' => 'bg-card edge',
			'wrap' => 'wrap',
		),
		array(
			cil_dyn_block(
				'cil/section-head',
				array(
					'eyebrow' => 'Syllabus',
					'heading' => 'Training topics',
					'lede'    => 'The course is designed to cover circumcision across different age groups.',
					'display' => 'd-1',
				)
			),
			cil_dyn_block(
				'cil/info-cards',
				array(
					'items' => array(
						array(
							'title' => 'Fundamentals',
							'html'  => '<ul><li>Anatomy</li><li>Indications for circumcision</li><li>Contra-indications</li></ul>',
						),
						array(
							'title' => 'Plastibell (ring) method',
							'html'  => '<ul><li>Instruments needed</li><li>How to use the device</li><li>Aftercare</li><li>Dealing with complications</li></ul>',
						),
						array(
							'title' => 'Forceps guided method',
							'html'  => '<ul><li>Instruments needed</li><li>Performing the circumcision</li><li>Suturing</li><li>Aftercare and complications</li></ul>',
						),
						array(
							'title' => 'Therapeutic circumcision',
							'html'  => '<ul><li>Dealing with phimosis</li><li>Preventing recurrence</li><li>Frenulum removal</li></ul>',
						),
						array(
							'title' => 'Harder cases',
							'html'  => '<ul><li>Re-circumcisions, free hand method</li><li><a href="' . esc_url( cil_path_url( '/buried-penis' ) ) . '">Buried penis</a></li></ul>',
						),
						array(
							'title' => 'Setting up',
							'html'  => '<ul><li>How to set up your practice</li><li>List of all suppliers</li><li>Consent forms and essential documents</li></ul>',
						),
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
					cil_html_block( $included ),
					cil_dyn_block(
						'cil/callback-card',
						array(
							'eyebrow' => 'Course enquiry',
							'title'   => 'Ask about dates',
							'formId'  => 'courses',
							'subject' => 'training course enquiry',
							'urgent'  => false,
						)
					),
				)
			),
		)
	);

	if ( ! empty( $page['faqs'] ) ) {
		$blocks[] = cil_dyn_block(
			'cil/faq',
			array(
				'heading' => 'Course questions',
				'items'   => $page['faqs'],
			)
		);
	}

	return cil_serialize_blocks( $blocks );
}
