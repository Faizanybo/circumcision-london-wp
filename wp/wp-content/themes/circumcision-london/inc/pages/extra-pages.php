<?php
/**
 * Religious, re-circumcision and buried-penis pages from the prototype.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prototype data for /religious, /re-circumcision and /buried-penis.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_extra_pages() {
	$clinic = cil_clinic();
	$wa     = $clinic['whatsapp']['href'];

	return array(
		'religious'        => array(
			'slug'        => 'religious',
			'name'        => 'Religious and cultural',
			'title'       => 'Religious & Cultural Circumcision London | Edgware Clinic',
			'description' => 'Circumcision for religious and cultural reasons at a CQC-registered clinic in Edgware. Sunnah practice catered for, all faiths welcome, six languages spoken.',
			'eyebrow'     => 'All faiths and backgrounds · Six languages',
			'h1'          => 'Religious and cultural circumcision',
			'lede'        => 'Muslim and Jewish families circumcise as part of religious practice, and it is widely practised across parts of Africa and fairly common in the USA. Our Muslim practitioners carry out the procedure according to the Sunnah, and we routinely cater for all faiths and backgrounds.',
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
			'faqs'        => array(
				array(
					'q' => 'Do you carry out the procedure according to the Sunnah?',
					'a' => '<p>Yes. Our Muslim practitioners carry out circumcision according to the Sunnah, and we routinely cater for all faiths and backgrounds alongside that.</p>',
				),
				array(
					'q' => 'Why a clinic rather than at home?',
					'a' => '<p>Hygiene, mainly. We recommend babies are circumcised in a dedicated clinic rather than at home, and we would say that whoever you chose. We would also say to check that any clinic you consider is registered with the Care Quality Commission, because it is a legal requirement and it is how UK national standards are enforced.</p>',
				),
				array(
					'q' => 'Can family be present?',
					'a' => '<p>Yes. Families are welcome at the consultation and in the clinic. For the few minutes of the procedure itself we ask everyone to wait just outside, which is about the room rather than about you.</p>',
				),
				array(
					'q' => 'Do you speak our language?',
					'a' => '<p>Our staff offer confidential information in ' . esc_html( $clinic['languages'] ) . '. If it is easier in writing, so it can be translated or forwarded to a relative, <a href="' . esc_url( $wa ) . '" rel="noopener" target="_blank">message us on WhatsApp</a>.</p>',
				),
				array(
					'q' => 'Is it different from a medical circumcision?',
					'a' => '<p>Not clinically. Same method for the age group, same anaesthetic, same testing before we start, same aftercare and the same free follow-ups. The reason changes the conversation, not the standard.</p>',
				),
				array(
					'q' => 'Can you do it on a specific date?',
					'a' => '<p>Tell us when you call and we will do our best to work around it. The only thing that overrides a date is your son being unwell on the morning, and we will say so rather than proceed.</p>',
				),
			),
		),
		're-circumcision'  => array(
			'slug'        => 're-circumcision',
			'name'        => 'Re-circumcision',
			'title'       => 'Re-Circumcision & Revision London | Beverley Clinic',
			'description' => 'Correcting a circumcision done elsewhere: scar revision, tightening a loose result, removing excess skin. Patients travel to our Edgware clinic from across the UK.',
			'eyebrow'     => 'Revision work · Quoted after examination',
			'h1'          => 'Correcting a circumcision done elsewhere',
			'lede'        => 'Scar revisions, tightening a loose result, removing skin that was left behind. We do a lot of this, and patients travel to us from across the UK and from outside it.',
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
			'faqs'        => array(
				array(
					'q' => 'What does it cost?',
					'a' => '<p>It is quoted after we have examined, because what is involved varies enormously between one case and the next. Contact us and we will tell you what a consultation involves and what to expect.</p>',
				),
				array(
					'q' => 'Do I need a consultation first?',
					'a' => '<p>Usually yes. We need to examine the existing circumcision before we can tell you what can be done and what it will cost. If you are travelling a long way, send us a photograph first and we will tell you whether the trip is worth making.</p>',
				),
				array(
					'q' => 'How long should I wait after the first circumcision?',
					'a' => '<p>Long enough for it to have fully settled, which is usually several months. Scar tissue keeps changing for a good while, and operating too early means judging a result that has not finished forming. We will tell you if you have come too soon.</p>',
				),
				array(
					'q' => 'Can a circumcision that is too tight be corrected?',
					'a' => '<p>That is a harder problem than one that is too loose, and it depends on how much skin there is to work with. It needs examining. We would rather tell you honestly what is and is not achievable than take a booking.</p>',
				),
				array(
					'q' => 'Do you do this for children as well as adults?',
					'a' => '<p>Yes. Parents bring sons to us from across the UK and from abroad to correct circumcisions they are unhappy with. The same applies: examination first, then a quote.</p>',
				),
			),
		),
		'buried-penis'     => array(
			'slug'        => 'buried-penis',
			'name'        => 'Buried penis',
			'title'       => 'Buried Penis and Circumcision | Beverley Clinic London',
			'description' => 'What a buried penis is, why circumcision does not cause it, how to keep the area clean afterwards, and when we advise waiting rather than operating.',
			'eyebrow'     => 'Common, treatable, and not caused by circumcision',
			'h1'          => 'Buried penis',
			'lede'        => 'When the penis sits hidden inside the pubic area, so the head is partly or completely out of sight. We treat a great many patients with a buried penis without any problem, and in severe cases we advise waiting.',
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
			'faqs'        => array(
				array(
					'q' => 'Did the circumcision cause it?',
					'a' => '<p>No. A buried penis is about the height of the pubic area relative to the length of the penis. The circumcision does not change either of those. What can happen is that the foreskin was making up much of the visible length, so once it is removed there is less to see than you expected.</p>',
				),
				array(
					'q' => 'Will it get better?',
					'a' => '<p>It improves if the pubic area flattens, usually through weight loss, or if the penis grows in length. There is no set timeline and it varies from person to person, but it can take several years.</p>',
				),
				array(
					'q' => 'What do I have to do afterwards?',
					'a' => '<p>Press down around the base as we show you, every day, so the head of the penis and the circumcision line are fully exposed, then clean and dry the area. Keep doing it for as long as the penis stays buried. With the ring method you start once the ring has come off; with the forceps guided method you start straight away.</p>',
				),
				array(
					'q' => 'Will you still circumcise if there is a buried penis?',
					'a' => '<p>Usually yes. We treat a great many patients with a buried penis without any problem at all. In severe cases we advise waiting until there has been weight loss or growth, or both.</p>',
				),
				array(
					'q' => 'How do I know if it is severe?',
					'a' => '<p>Send us a photograph before your appointment. We will tell you whether to come in or wait, and it saves you the trip.</p>',
				),
			),
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
 * /religious block list.
 *
 * @param array<string, mixed> $page Page data.
 * @return string
 */
function cil_religious_blocks( $page ) {
	$clinic = cil_clinic();

	$intro = cil_proto_html(
		'<p>Circumcision is the removal of the foreskin that covers the head of the penis. For a great many of the
        families who come here the reason is religious, and that is an ordinary reason that needs no justifying.</p>
        <p>What we would say, whoever you chose, is this. <strong>Have it done in a dedicated clinic rather than at
        home</strong>, because it is more hygienic. And <strong>check the clinic is registered with the Care Quality
        Commission</strong>, because that registration is a legal requirement and it is what makes sure UK national
        standards are being met. Ours is registered and was rated <strong>' . esc_html( $clinic['cqc_rating'] ) . '</strong> at previous
        inspections.</p>
        <p>Beyond that, nothing changes. A religious circumcision here gets the same method for the age group, the
        same local anaesthetic, the same test before we start, the same printed aftercare and the same free follow-up
        appointments as any other procedure we do.</p>'
	);

	$say_no = cil_proto_html(
		'<p>Our clinical judgement does not move for the reason behind the procedure, and families would rather hear
          that plainly.</p>
          <p>If your son is unwell, jaundiced or was very premature, we postpone, even where the date matters to you.
          Where there is hypospadias or another variation in which the foreskin may be needed later, we decline and
          explain why. If there is a family history of a bleeding disorder, we want it investigated first.</p>
          <p>Every one of those conversations is easier before the day than on it, which is what the consultation is
          for.</p>'
	);

	$wa = $clinic['whatsapp']['href'];

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
								array( 'k' => 'All faiths', 'v' => 'Routinely catered for' ),
								array( 'k' => 'Family present', 'v' => 'Welcome throughout' ),
								array( 'k' => 'Languages', 'v' => 'Six, see below' ),
								array( 'k' => 'Anaesthetic', 'v' => 'Local, every time' ),
								array( 'k' => 'Setting', 'v' => 'CQC registered, rated ' . $clinic['cqc_rating'] ),
								array( 'k' => 'Babies from', 'v' => '£200' ),
								array( 'k' => 'Adults from', 'v' => '£680' ),
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
					'eyebrow' => 'Language',
					'heading' => 'Confidential information, in six languages',
					'lede'    => 'Our staff speak ' . $clinic['languages'] . '.',
					'display' => 'd-1',
				)
			),
			cil_dyn_block(
				'cil/info-cards',
				array(
					'items' => array(
						array(
							'title' => 'Bring whoever you want',
							'body'  => 'A decision like this is rarely one person\'s. Partners, parents and grandparents are welcome, and we will go through the same explanation as many times as it takes.',
						),
						array(
							'title' => 'Or write instead of calling',
							'body'  => 'A phone call asks you to be fluent and composed in the moment. <a href="' . esc_url( $wa ) . '" rel="noopener" target="_blank" data-track="whatsapp-religious" style="color:var(--blue-deep)">WhatsApp</a> lets you take your time, translate, and forward the answer to whoever else needs to see it.',
						),
						array(
							'title' => 'Families come back',
							'body'  => 'Most of our patients come by word of mouth from family and friends. Some families return after many years, whenever there is a new son.',
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
					cil_html_block(
						'<div data-reveal>
        <span class="caps eyebrow">Being straight with you</span>
        <h2 class="display d-1">What we will still say no to</h2>
        <div class="body-text" style="margin-top:22px">' . $say_no . '</div>
      </div>'
					),
					cil_dyn_block(
						'cil/callback-card',
						array(
							'eyebrow' => 'Request a call back',
							'title'   => 'Talk it through first',
							'formId'  => 'religious',
							'subject' => 'religious circumcision enquiry',
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
			'title' => 'Ask about a date',
			'text'  => 'If the day matters, say so when you call and we will do our best to work around it. Nothing is booked until you are ready.',
		)
	);

	$blocks[] = cil_dyn_block(
		'cil/faq',
		array(
			'heading' => 'Questions families ask',
			'items'   => $page['faqs'],
		)
	);

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
	$clinic = cil_clinic();

	$intro = cil_proto_html(
		'<p>A circumcision that has not healed the way somebody hoped is a more common reason for coming here than
        people expect. Parents bring sons to us from all over the UK, and from outside it, asking us to tidy up work
        done somewhere else. Adults come for the same reason, often having lived with it for years.</p>
        <p>What that covers varies. Scar revision. Tightening a result left too loose. Removing skin that was not
        taken the first time. Correcting an uneven line. Dealing with a tight ring left behind the head of the penis,
        which is what tends to happen when <a href="/conditions/phimosis">phimosis</a> was present and was not
        recognised and treated properly at the time.</p>
        <p>We cannot tell you what is achievable without looking. That is not a way of getting you through the door:
        the range of what people arrive with is genuinely wide, and the honest answer in some cases is that the result
        is as good as it is going to get.</p>'
	);

	$photo = cil_proto_html(
		'<div class="body-text">
        <p>If you are coming any distance, send us a photograph before you book. We will tell you whether it is worth
        the journey, and roughly what would be involved.</p>
        <p>Nobody enjoys sending that photograph. We look at these every week, nobody here will react to it, and it
        saves people a wasted trip across the country often enough that we would rather ask.</p>
        <p><a href="' . esc_url( $clinic['whatsapp']['href'] ) . '" rel="noopener" target="_blank" data-track="whatsapp-recirc">Send it on
        WhatsApp</a> or email <a href="mailto:' . esc_attr( $clinic['email'] ) . '">' . esc_html( $clinic['email'] ) . '</a>.</p>
      </div>'
	);

	$settled = cil_proto_html(
		'<p>The commonest mistake is coming too early. Scar tissue carries on changing for months, and a result that
          looks wrong at six weeks can look entirely different at six months.</p>
          <p>If you have come too soon we will say so and ask you to come back, which costs us the booking and saves
          you an operation you may not need.</p>
          <p>A result that is too loose is generally more correctable than one that is too tight, because a tight
          result leaves less skin to work with. Either way it needs examining.</p>'
	);

	$urgent = cil_render_part( 'urgent-note' );

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
								array( 'k' => 'Consultation', 'v' => 'Needed first, to examine' ),
								array( 'k' => 'Price', 'v' => 'Quoted after examination' ),
								array( 'k' => 'Travelling far?', 'v' => 'Send a photograph first' ),
								array( 'k' => 'Method', 'v' => 'Depends entirely on the case' ),
								array( 'k' => 'Anaesthetic', 'v' => 'Local, tested before we start' ),
								array( 'k' => 'Follow-up', 'v' => 'Free, until healing is complete' ),
							),
							'note'    => 'Our practitioners also teach the free hand technique used for re-circumcisions to other doctors on our <a href="' . esc_url( cil_path_url( '/courses' ) ) . '" style="color:var(--blue-deep)">training course</a>.',
						)
					),
				)
			),
		)
	);

	$blocks[] = cil_dyn_block(
		'cil/text-section',
		array(
			'eyebrow' => 'Before you travel',
			'heading' => 'Send us a photograph first',
			'html'    => $photo,
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
        <span class="caps eyebrow">Being straight with you</span>
        <h2 class="display d-2">Wait until it has settled</h2>
        <div class="body-text" style="margin-top:20px">' . $settled . '</div>
        <div style="margin-top:24px">' . $urgent . '</div>
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

	$blocks[] = cil_dyn_block(
		'cil/cta-band',
		array(
			'title' => 'Get it looked at properly',
			'text'  => 'A consultation gets you an examination, a straight answer about what can be improved, and a written quote. Nothing is booked until you decide.',
		)
	);

	$blocks[] = cil_dyn_block(
		'cil/faq',
		array(
			'heading' => 'Re-circumcision questions',
			'items'   => $page['faqs'],
		)
	);

	return cil_serialize_blocks( $blocks );
}

/**
 * /buried-penis block list.
 *
 * @param array<string, mixed> $page Page data.
 * @return string
 */
function cil_buried_blocks( $page ) {
	$clinic = cil_clinic();

	$intro = cil_proto_html(
		'<p>A buried penis is one that sits hidden inside the pubic area, with the head either partly or fully out of
      sight.</p>
      <p>Some patients have it from the start. What looks like the full length of the penis is in fact largely
      foreskin, so once the foreskin is removed there is noticeably less visible length than the family expected. It
      can also develop later, if weight gain raises the pubic area above the length of the penis.</p>
      <p><strong>The circumcision does not cause a buried penis.</strong> It is a matter of the height of the pubic
      area relative to the length of the penis, and removing the foreskin changes neither. This is worth saying
      plainly, because it is the conclusion most parents reach when they see the result.</p>'
	);

	$aftercare = cil_proto_html(
		'<div class="body-text">
        <p>This is the single most important thing to get right, and it takes a few seconds a day.</p>
        <p>Press down around the base exactly as we show you at the clinic, so that the head of the penis and the
        circumcision line are fully exposed. Then clean the area and dry it properly. Do this <strong>every
        day</strong>, and keep doing it for as long as the penis remains buried.</p>
        <p>With the <strong>ring method</strong>, start once the ring has come off. With the <strong>forceps
        guided method</strong>, start straight away.</p>
        <p>Skipping it is what causes problems. A buried circumcision line that is never exposed stays damp, and damp
        skin in a warm fold is how soreness and infection start.</p>
      </div>'
	);

	$improve = cil_proto_html(
		'<p>A buried penis improves when the pubic area flattens, which generally means weight loss, or when the
          penis grows in length. Often it is a combination of the two.</p>
          <p>There is no set timeline. It varies from person to person and it can take several years. We would rather
          tell you that than suggest it will resolve in a few months.</p>
          <p>In severe cases we advise waiting for one or both of those before circumcising at all. If you are not
          sure which category you or your son fall into, <strong>send us a photograph before your appointment</strong>
          and we will tell you, rather than have you make the trip.</p>'
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
			'heading' => 'Press down, expose, clean, dry',
			'html'    => $aftercare,
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
        <span class="caps eyebrow">Will it improve?</span>
        <h2 class="display d-2">In time, and only in two ways</h2>
        <div class="body-text" style="margin-top:20px">' . $improve . '</div>
        <div class="btn-row" style="margin-top:26px">
          <a class="btn" href="' . esc_url( cil_book_url() ) . '" data-track="book-buried">Book a consultation</a>
          <a class="btn btn-ghost" href="' . esc_url( $clinic['whatsapp']['href'] ) . '" rel="noopener" target="_blank" data-track="whatsapp-buried">Send a photo on WhatsApp</a>
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

	$blocks[] = cil_dyn_block(
		'cil/faq',
		array(
			'heading' => 'Buried penis questions',
			'items'   => $page['faqs'],
		)
	);

	return cil_serialize_blocks( $blocks );
}
