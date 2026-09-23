<?php
/**
 * Prototype content used by reusable sections.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme asset URL.
 *
 * @param string $relative Path under assets/.
 * @return string
 */
function cil_asset( $relative ) {
	return get_template_directory_uri() . '/assets/' . ltrim( $relative, '/' );
}

/**
 * Allowlisted HTML for prototype FAQ answers.
 *
 * @param string $html Raw HTML.
 * @return string
 */
function cil_rich_text( $html ) {
	return wp_kses(
		$html,
		array(
			'p'       => array( 'style' => true, 'class' => true ),
			'a'       => array(
				'href'       => true,
				'rel'        => true,
				'target'     => true,
				'style'      => true,
				'class'      => true,
				'data-track' => true,
			),
			'strong'  => array(),
			'em'      => array(),
			'br'      => array(),
			'ul'      => array( 'style' => true, 'class' => true ),
			'ol'      => array( 'style' => true, 'class' => true ),
			'li'      => array( 'style' => true, 'class' => true ),
			'span'    => array( 'style' => true, 'class' => true, 'aria-hidden' => true ),
			'div'     => array( 'style' => true, 'class' => true ),
			'address' => array( 'style' => true, 'class' => true ),
			'h2'      => array( 'style' => true, 'class' => true ),
			'h3'      => array( 'style' => true, 'class' => true ),
			'h4'      => array( 'style' => true, 'class' => true ),
			'dl'      => array( 'style' => true, 'class' => true ),
			'dt'      => array( 'style' => true, 'class' => true ),
			'dd'      => array( 'style' => true, 'class' => true ),
		)
	);
}

/**
 * Turn a prototype path (`/prices`, `/team#haidar`) into a site URL.
 *
 * @param string $path Path with optional hash.
 * @return string
 */
function cil_path_url( $path ) {
	$hash = '';
	$bits = explode( '#', $path, 2 );
	$path = $bits[0];
	if ( isset( $bits[1] ) ) {
		$hash = '#' . $bits[1];
	}
	if ( '' === $path || '/' === $path ) {
		return home_url( '/' ) . $hash;
	}
	return home_url( user_trailingslashit( $path ) ) . $hash;
}

/**
 * Booking URL with the prototype ?for= label used on price rows.
 *
 * @param string $label Age band or procedure name.
 * @return string
 */
function cil_book_for_url( $label ) {
	return add_query_arg( 'for', $label, cil_book_url() );
}

/**
 * Google Maps search URL for the clinic address (prototype mapsUrl).
 *
 * Linked out rather than embedded, so the page sets no map cookie until chosen.
 *
 * @return string
 */
function cil_maps_url() {
	$clinic = cil_clinic();
	$query  = $clinic['address']['street'] . ', ' . $clinic['address']['locality'] . ' ' . $clinic['address']['postcode'];
	return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( $query );
}

/**
 * Published price list from prototype src/site.js PRICES.
 *
 * @return array<string, array<int, array<string, string>>>
 */
function cil_price_catalog() {
	return array(
		'ages'   => array(
			array(
				'name'  => '0 to 2 months',
				'price' => '£200',
			),
			array(
				'name'  => '3 to 5 months',
				'price' => '£230',
			),
			array(
				'name'  => '6 to 11 months',
				'price' => '£250',
			),
			array(
				'name'  => '1 to 2 years',
				'price' => '£280',
			),
			array(
				'name'  => '3 to 5 years',
				'price' => '£300',
			),
			array(
				'name'  => '6 to 9 years',
				'price' => '£330',
			),
			array(
				'name'  => '10 to 11 years',
				'price' => '£360',
			),
			array(
				'name'  => '12 to 13 years',
				'price' => '£400',
			),
			array(
				'name'  => '14 to 15 years',
				'price' => '£530',
			),
			array(
				'name'  => '16 to 17 years',
				'price' => '£630',
			),
		),
		'adults' => array(
			array(
				'name'  => 'Adult circumcision, no medical or foreskin problem',
				'desc'  => 'Forceps guided method, closed with stitches and glue.',
				'price' => '£680',
				'href'  => cil_path_url( '/adults' ),
			),
			array(
				'name'  => 'Adult circumcision with removal of the frenulum',
				'desc'  => 'Where the frenulum is short or tethering, it is dealt with in the same procedure. Stitches and glue.',
				'price' => '£880',
				'href'  => cil_path_url( '/procedures/frenuloplasty' ),
			),
			array(
				'name'  => 'Adult circumcision with a medical or foreskin problem',
				'desc'  => 'Phimosis, scarring or BXO, with or without removal of the frenulum. Stitches and glue.',
				'price' => '£1,080',
				'href'  => cil_path_url( '/conditions/phimosis' ),
			),
		),
		'other'  => array(
			array(
				'name'  => 'Children with a medical or foreskin problem',
				'desc'  => 'Priced after examination, because what is involved varies a great deal.',
				'price' => 'Contact us for a quote',
				'href'  => cil_path_url( '/children' ),
				'cta'   => 'Enquire',
			),
			array(
				'name'  => 'Re-circumcision and revision',
				'desc'  => 'Tidying up a circumcision done elsewhere: scar revision, tightening a loose result. Usually needs a consultation first.',
				'price' => 'Contact us for a quote',
				'href'  => cil_path_url( '/re-circumcision' ),
				'cta'   => 'Enquire',
			),
			array(
				'name'  => 'IV sedation for nervous patients',
				'desc'  => 'Call the clinic. The clinic liaises with the anaesthetist and arranges the appointment separately. This is not an online booking add-on.',
				'price' => 'Extra £800',
				'cta'   => 'Call the clinic',
				'url'   => cil_clinic()['phone']['href'],
			),
		),
	);
}

/**
 * What every listed price covers. Prototype INCLUDED.
 *
 * @return string[]
 */
function cil_included_items() {
	return array(
		'The procedure',
		'Local anaesthetic',
		'Preparation and aftercare information',
		'A next-day follow-up call',
		'Written aftercare',
		'Free follow-up appointments during healing',
		'A GP letter',
		'Nursery, school or work letter where required',
	);
}

/**
 * Rewrite prototype root-relative hrefs inside copied HTML.
 *
 * @param string $html Markup from the prototype.
 * @return string
 */
function cil_proto_html( $html ) {
	return preg_replace_callback(
		'/href="(\/[^"]*)"/',
		static function ( $match ) {
			return 'href="' . esc_url( cil_path_url( $match[1] ) ) . '"';
		},
		$html
	);
}

/**
 * Named figure assets used on inner pages. Values match the prototype img/ files.
 *
 * @param string $name Asset key.
 * @return array<string, mixed>|null
 */
function cil_figure_asset( $name ) {
	$base = cil_asset( 'images/' );
	$catalog = array(
		'baby-parent'   => array(
			'src'     => $base . 'baby-parent-700.jpg',
			'webp'    => $base . 'baby-parent-700.webp 700w, ' . $base . 'baby-parent-1100.webp 1100w',
			'alt'     => 'A father holding his swaddled newborn in the treatment room, with a practitioner beside him.',
			'caption' => 'Your son comes straight back to you, dressed and ready to go home.',
			'width'   => 1920,
			'height'  => 1080,
			'ratio'   => '4-3',
		),
		'waiting-room'  => array(
			'src'     => $base . 'waiting-room-700.jpg',
			'webp'    => $base . 'waiting-room-700.webp 700w, ' . $base . 'waiting-room-1100.webp 1100w',
			'alt'     => 'The clinic waiting area, with adult seating and a small blue child\'s chair.',
			'caption' => 'The waiting area. Bring whatever keeps him occupied.',
			'width'   => 1400,
			'height'  => 1600,
			'ratio'   => '4-3',
		),
		'certificates'  => array(
			'src'     => $base . 'certificates-700.jpg',
			'webp'    => $base . 'certificates-700.webp 700w, ' . $base . 'certificates-1100.webp 1100w',
			'alt'     => 'Framed qualifications along the clinic corridor at the Edgware practice.',
			'caption' => 'Qualifications hang in the corridor. Ask to see any of them.',
			'width'   => 1800,
			'height'  => 1400,
			'ratio'   => '4-3',
		),
		'dr-haidar'     => array(
			'src'     => $base . 'dr-haidar-700.jpg',
			'webp'    => $base . 'dr-haidar-700.webp 700w, ' . $base . 'dr-haidar-1100.webp 1100w',
			'alt'     => 'Dr Haidar Al-Ali in clinic scrubs in the treatment room at the Edgware practice.',
			'caption' => '',
			'width'   => 1920,
			'height'  => 1080,
			'ratio'   => '4-3',
		),
		'dr-samir'      => array(
			'src'     => $base . 'dr-samir-700.jpg',
			'webp'    => $base . 'dr-samir-700.webp 700w, ' . $base . 'dr-samir-1100.webp 1100w',
			'alt'     => 'Mr Samir Al-Ali, plastic surgeon, in navy clinic scrubs at the Edgware practice.',
			'caption' => '',
			'width'   => 1400,
			'height'  => 1600,
			'ratio'   => '3-4',
		),
	);

	return isset( $catalog[ $name ] ) ? $catalog[ $name ] : null;
}

/**
 * Homepage testimonials from the prototype.
 *
 * @return array<int, array<string, string>>
 */
function cil_testimonials() {
	return array(
		array(
			'name'    => 'Mustafa Butt',
			'context' => __( 'Baby circumcision, three weeks old', 'circumcision-london' ),
			'quote'   => __( 'The receptionist explained the procedure clearly and put us at ease straight away. The doctor was thorough and professional, explained everything beforehand, and stayed calm and reassuring throughout. He took the time to go through the aftercare in detail.', 'circumcision-london' ),
		),
		array(
			'name'    => 'Wajahat Murtaza',
			'context' => __( 'Baby circumcision', 'circumcision-london' ),
			'quote'   => __( 'Smooth and efficient from start to finish. Dr Haidar is professional and experienced, we were given all the information we needed, and a follow-up call was made as well, which is a nice thing to do. The ring came off after a week and my son is doing well.', 'circumcision-london' ),
		),
		array(
			'name'    => 'Sara Salmanpour',
			'context' => __( 'Baby circumcision, two months old', 'circumcision-london' ),
			'quote'   => __( 'As first-time parents we were naturally anxious, but the team made us feel completely at ease. The doctor explained the procedure in detail and answered every concern with patience. They gave us the option to contact them any time afterwards, which was a huge relief.', 'circumcision-london' ),
		),
	);
}

/**
 * Homepage process steps from the prototype.
 *
 * @return array<int, array<string, string>>
 */
function cil_home_steps() {
	return array(
		array(
			'h' => __( 'You call, or ask us to call you', 'circumcision-london' ),
			'p' => __( 'We answer the questions you have now, over the phone or on WhatsApp, and agree a consultation time. Plenty of people stop at this step, and that is a perfectly good outcome.', 'circumcision-london' ),
		),
		array(
			'h' => __( 'Consultation and examination', 'circumcision-london' ),
			'p' => __( 'You meet the practitioner who would carry out the procedure. They examine, explain the method for your age group, go through the risks properly, and give you the price in writing. Nothing is booked unless you ask.', 'circumcision-london' ),
		),
		array(
			'h' => __( 'The day itself', 'circumcision-london' ),
			'p' => __( 'Local anaesthetic, twenty to sixty minutes depending on age, and you go home a few hours later with written aftercare instructions and a number that reaches the clinic out of hours.', 'circumcision-london' ),
		),
		array(
			'h' => __( 'Afterwards', 'circumcision-london' ),
			'p' => __( 'We telephone you the next day. Follow-up appointments are included until healing is complete, however many that takes, and we would far rather see you early than have you sitting at home worrying.', 'circumcision-london' ),
		),
	);
}

/**
 * Homepage FAQs from the prototype.
 *
 * @return array<int, array<string, string>>
 */
function cil_home_faqs() {
	return array(
		array(
			'q' => __( 'Is the circumcision painful?', 'circumcision-london' ),
			'a' => '<p>No. Every patient has local anaesthetic, at every age. It is safe, and it is illegal to circumcise without it.</p><p>We do not simply assume it has worked. Once it has had time, we test the area and show you that no pain is felt, and we only begin once you tell us you are happy to proceed. Babies sometimes cry anyway: the anaesthetic removes pain but keeps ordinary touch sensation, which is painless, and an unfamiliar touch there is enough to make a baby object.</p>',
		),
		array(
			'q' => __( 'How long does it take?', 'circumcision-london' ),
			'a' => '<p>For a baby, the circumcision itself is about ten minutes, and the anaesthetic lasts around two hours afterwards. Older children and adults take longer. Allow about an hour for the visit either way, which covers the consultation, consent, the anaesthetic working and going through aftercare before you leave.</p>',
		),
		array(
			'q' => __( 'Which method do you use?', 'circumcision-london' ),
			'a' => '<p>It depends on age. For babies and toddlers we use the ring method, Plastibell or Circumplast, which needs no stitches and separates on its own within three to fourteen days. For older children and adults we use the forceps guided method with thermal cautery, which gives a clean line with minimal bleeding.</p><p>There is no fixed cut-off age. We decide after examining.</p>',
		),
		array(
			'q' => __( 'What is the best age?', 'circumcision-london' ),
			'a' => '<p>Under one month old, if the choice is open to you. At that age it is quick and simple and healing is faster. We circumcise routinely at every age after that, right through to adults, so it is never too late.</p>',
		),
		array(
			'q' => __( 'Do you carry out religious circumcision?', 'circumcision-london' ),
			'a' => '<p>Yes, and it is a large part of what we do. Our Muslim practitioners carry out the procedure according to the Sunnah, and we routinely cater for all faiths and backgrounds. <a href="' . esc_url( home_url( '/religious/' ) ) . '">More about religious and cultural circumcision</a>.</p>',
		),
		array(
			'q' => __( 'Can you correct a circumcision done elsewhere?', 'circumcision-london' ),
			'a' => '<p>Yes. Parents bring sons to us from across the UK and from abroad to tidy up work done somewhere else, and adults come for the same reason. It ranges from scar revision to tightening a loose result. <a href="' . esc_url( home_url( '/re-circumcision/' ) ) . '">More about re-circumcision</a>.</p>',
		),
		array(
			'q' => __( 'Do I need to do anything to prepare?', 'circumcision-london' ),
			'a' => '<p>Very little, because it is done under local anaesthetic. There is no fasting. When you book we send you videos on how to prepare and what to expect from start to finish, and the page for your age group lists exactly what to bring.</p>',
		),
		array(
			'q' => __( 'Could the NHS do this instead?', 'circumcision-london' ),
			'a' => '<p>Where there is a medical need, yes, and if you can wait that is a reasonable choice. From what our patients tell us, the wait runs beyond a year. Religious and cultural circumcision is not funded. Start with your GP if you are unsure which applies to you.</p>',
		),
	);
}
