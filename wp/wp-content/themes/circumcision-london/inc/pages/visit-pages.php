<?php
/**
 * Contact and booking pages from prototype src/pages/misc.js.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prototype data for /contact and /book.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_visit_pages() {
	$clinic = cil_clinic();

	return array(
		'contact' => array(
			'slug'        => 'contact',
			'path'        => '/contact',
			'name'        => 'Visit us',
			'title'       => 'Visit the Clinic | Edgware, North-West London',
			'description' => 'The clinic is at 78 Beverley Drive, Edgware HA8 5NE. Open Monday to Saturday. Free street parking, and directions by tube, bus, car and plane.',
			'eyebrow'     => 'Edgware · Free parking · Outside ULEZ',
			'h1'          => 'Visiting the clinic',
			'lede'        => 'One site in a quiet residential street in Edgware, on the same road as Queensbury station and about eight minutes on foot from it. Free street parking all around the clinic, at all times.',
			'crumbs'      => array(
				array(
					'label' => 'Visit us',
					'href'  => '',
				),
			),
		),
		'book'    => array(
			'slug'        => 'book',
			'path'        => '/book',
			'name'        => 'Book a consultation',
			'title'       => 'Book a Consultation | Circumcision Clinic in London',
			'description' => 'Book a circumcision consultation in Edgware, usually available within seven days. Nothing is booked and no deposit is taken until you decide to go ahead.',
			'eyebrow'     => 'Next available: ' . strtolower( $clinic['next_available'] ),
			'h1'          => 'Book a consultation',
			'lede'        => 'One booking route, not several. Fill in the form and we will call you back within two working hours to agree a time. Call the clinic directly if you would rather speak to somebody now.',
			'crumbs'      => array(
				array(
					'label' => 'Book a consultation',
					'href'  => '',
				),
			),
		),
	);
}

/**
 * Block markup for contact or book.
 *
 * @param string $slug Page slug.
 * @return string
 */
function cil_visit_page_blocks( $slug ) {
	if ( 'contact' === $slug ) {
		return cil_contact_page_blocks();
	}
	if ( 'book' === $slug ) {
		return cil_book_page_blocks();
	}
	return '';
}

/**
 * Three NAP cards on /contact.
 *
 * @return string
 */
function cil_contact_nap_cards_html() {
	$clinic = cil_clinic();
	$maps   = cil_maps_url();
	$link   = 'color:var(--blue-deep);text-decoration:none';

	$phone_rows = array(
		array(
			'k' => 'Clinic',
			'v' => '<a href="' . esc_url( $clinic['phone']['href'] ) . '" data-track="call-contact" style="' . $link . '">' . esc_html( $clinic['phone']['display'] ) . '</a>',
		),
		array(
			'k' => 'Mobile',
			'v' => '<a href="' . esc_url( $clinic['mobile']['href'] ) . '" data-track="call-contact-mobile" style="' . $link . '">' . esc_html( $clinic['mobile']['display'] ) . '</a>',
		),
		array(
			'k' => 'WhatsApp',
			'v' => '<a href="' . esc_url( $clinic['whatsapp']['href'] ) . '" rel="noopener" target="_blank" data-track="whatsapp-contact" style="' . $link . '">' . esc_html( $clinic['whatsapp']['display'] ) . '</a>',
		),
		array(
			'k' => 'Email',
			'v' => '<a href="mailto:' . esc_attr( $clinic['email'] ) . '" style="' . $link . ';font-size:15px">' . esc_html( $clinic['email'] ) . '</a>',
		),
	);

	$hour_rows = array();
	foreach ( $clinic['hours'] as $row ) {
		$hour_rows[] = array(
			'k' => $row['days'],
			'v' => $row['open'] . ' – ' . $row['close'],
		);
	}
	$hour_rows[] = array(
		'k' => 'Sunday and holidays',
		'v' => 'Phone lines closed',
	);

	return '<div class="grid g-3">
      <div class="card" data-reveal>
        <span class="caps eyebrow">By phone</span>
        <h2 class="display d-3">Talk to the clinic</h2>
        ' . cil_render_part( 'spec-list', array( 'rows' => $phone_rows ) ) . '
      </div>
      <div class="card" data-reveal data-reveal-delay="80">
        <span class="caps eyebrow">Opening hours</span>
        <h2 class="display d-3">When we are here</h2>
        ' . cil_render_part( 'spec-list', array( 'rows' => $hour_rows ) ) . '
        <p class="muted" style="margin-top:14px;font-size:15px">' . esc_html( $clinic['hours_note'] ) . '</p>
      </div>
      <div class="card" data-reveal data-reveal-delay="160">
        <span class="caps eyebrow">Where we are</span>
        <h2 class="display d-3">Find us</h2>
        <address style="font-style:normal;margin-top:16px;font-size:17px;line-height:1.8;color:var(--muted)">'
			. esc_html( $clinic['address']['street'] ) . '<br>'
			. esc_html( $clinic['address']['locality'] ) . '<br>'
			. esc_html( $clinic['address']['postcode'] ) .
		'</address>
        <a class="btn btn-ghost" style="margin-top:20px" href="' . esc_url( $maps ) . '" rel="noopener" target="_blank" data-track="map-open">Open in Google Maps</a>
        <p class="muted" style="margin-top:14px;font-size:15px">Free street parking all around the clinic, at all times. Outside the Congestion Charge zone and the ULEZ.</p>
      </div>
    </div>';
}

/**
 * Directions column and map card on /contact.
 *
 * @return array{0: string, 1: string}
 */
function cil_contact_directions_html() {
	$clinic = cil_clinic();
	$maps   = cil_maps_url();

	$copy = cil_proto_html(
		'<div data-reveal>
        <span class="caps eyebrow">Getting here</span>
        <h2 class="display d-1">Directions</h2>
        <div class="body-text" style="margin-top:20px">
          <p><strong>By tube.</strong> Queensbury on the Jubilee line is on the same road as the clinic, about eight
          minutes\' walk. Come out of the station and walk straight until you reach us. Burnt Oak on the Northern line
          is about fifteen minutes on foot.</p>
          <p><strong>By bus.</strong> Routes 79, 114 and 302 all stop within walking distance.</p>
          <p><strong>By car.</strong> Fifteen minutes from Junction 4 of the M1 and the A1, fifteen from Wembley
          Stadium, fifteen from the North Circular and about thirty from central London. There is free street parking
          all around the clinic at all times, and we sit outside both the Congestion Charge zone and the ULEZ, so the
          journey costs nothing either.</p>
          <p><strong>By plane.</strong> We see a lot of patients from outside the UK. Luton is about thirty-five
          minutes from the clinic and Heathrow about forty-five.</p>
          <p><strong>With a baby.</strong> There is room for a pram and there are changing facilities. Come a few
          minutes early if you would rather feed here than in the car.</p>
        </div>
        <div class="btn-row" style="margin-top:26px">
          <a class="btn" href="' . esc_url( $maps ) . '" rel="noopener" target="_blank" data-track="map-directions">Get directions</a>
          <a class="btn btn-ghost" href="' . esc_url( $clinic['phone']['href'] ) . '" data-track="call-directions">Call ' . esc_html( $clinic['phone']['display'] ) . '</a>
        </div>
      </div>'
	);

	$map = '<div data-reveal data-reveal-delay="120">
        <a href="' . esc_url( $maps ) . '" rel="noopener" target="_blank" data-track="map-static"
           style="display:block;text-decoration:none;border:1px solid var(--line);border-radius:6px;background:var(--paper-2);padding:clamp(26px,3vw,40px)">
          <span class="caps" style="color:var(--blue)">Location</span>
          <span class="display d-2" style="display:block;margin-top:12px">' . esc_html( $clinic['address']['locality'] ) . ', ' . esc_html( $clinic['address']['postcode'] ) . '</span>
          <span class="muted" style="display:block;margin-top:14px;font-size:16px;line-height:1.7">
            ' . esc_html( $clinic['address']['street'] ) . ', ' . esc_html( $clinic['address']['locality'] ) . ', London ' . esc_html( $clinic['address']['postcode'] ) . '.<br>
            Eight minutes on foot from Queensbury station.
          </span>
          <span class="link-arrow" style="margin-top:22px">Open in Google Maps</span>
        </a>
        <p class="muted" style="margin-top:12px;font-size:14.5px">
          We link out to Google Maps instead of embedding it, so this page loads immediately and sets no third-party
          cookie before you have chosen to open the map.
        </p>
      </div>';

	return array( $copy, $map );
}

/**
 * Gutenberg markup for /contact.
 *
 * @return string
 */
function cil_contact_page_blocks() {
	$page = cil_visit_pages()['contact'];
	list( $directions, $map_card ) = cil_contact_directions_html();

	$form = '<div class="card" data-reveal>
      <span class="caps eyebrow">Request a call back</span>
      <h2 class="display d-1" style="margin-bottom:22px">Ask us to call you</h2>
      ' . cil_render_part(
			'callback-form',
			array(
				'id'      => 'contact',
				'subject' => 'contact page callback',
			)
		) . '
    </div>';

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
			cil_html_block( cil_contact_nap_cards_html() ),
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section',
			'band' => 'bg-card edge',
			'wrap' => 'wrap',
		),
		array(
			cil_split_block(
				array(
					cil_html_block( $directions ),
					cil_html_block( $map_card ),
				)
			),
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section',
			'band' => '',
			'wrap' => 'wrap-narrow',
		),
		array(
			cil_html_block( $form ),
		)
	);

	return cil_serialize_blocks( $blocks );
}

/**
 * “What happens next” column on /book.
 *
 * @return string
 */
function cil_book_next_html() {
	$clinic = cil_clinic();

	$steps = cil_render_part(
		'steps',
		array(
			'items' => array(
				array(
					'h' => 'We call you back',
					'p' => 'Within two working hours, to answer questions and agree a consultation time. Plenty of people stop here, and that is a perfectly good outcome.',
				),
				array(
					'h' => 'Consultation and examination',
					'p' => 'You meet the practitioner who would carry out the procedure. They examine, explain, go through the risks, and tell you honestly if you should not go ahead.',
				),
				array(
					'h' => 'A written price',
					'p' => 'All-inclusive, before you commit. Nothing is booked and no deposit is taken on the day unless you ask for it.',
				),
				array(
					'h' => 'The procedure, if you want it',
					'p' => 'Home the same day. We call the next morning and follow-up appointments are included until healing is complete.',
				),
			),
		)
	);

	return '<div data-reveal data-reveal-delay="120">
        <span class="caps eyebrow">What happens next</span>
        <h2 class="display d-2" style="margin-bottom:22px">Four steps, no surprises</h2>
        ' . $steps . '
        <div class="callout" style="margin-top:26px">
          <h3 class="display d-3">Would you rather just call?</h3>
          <p style="margin-top:8px">Most people would. The clinic answers during opening hours on
          <a href="' . esc_url( $clinic['phone']['href'] ) . '" data-track="call-book" style="color:var(--blue-deep);font-weight:600">' . esc_html( $clinic['phone']['display'] ) . '</a>,
          and you can <a href="' . esc_url( $clinic['whatsapp']['href'] ) . '" rel="noopener" target="_blank" data-track="whatsapp-book" style="color:var(--blue-deep);font-weight:600">message on WhatsApp</a>
          if you would rather write than speak.</p>
        </div>
        <div style="margin-top:18px">' . cil_render_part( 'urgent-note' ) . '</div>
      </div>';
}

/**
 * Gutenberg markup for /book.
 *
 * @return string
 */
function cil_book_page_blocks() {
	$page = cil_visit_pages()['book'];

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
					cil_dyn_block(
						'cil/callback-card',
						array(
							'eyebrow' => 'Booking request',
							'title'   => 'Tell us who it is for',
							'formId'  => 'book',
							'subject' => 'consultation booking',
							'urgent'  => false,
						)
					),
					cil_html_block( cil_book_next_html() ),
				)
			),
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
					'eyebrow' => 'Consultations for',
					'heading' => 'Any age',
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
