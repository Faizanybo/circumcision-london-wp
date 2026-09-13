<?php
/**
 * Prices and aftercare pages from prototype src/pages/misc.js.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prototype data for /prices and /aftercare.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_misc_pages() {
	return array(
		'prices'    => array(
			'slug'        => 'prices',
			'path'        => '/prices',
			'name'        => 'Prices',
			'title'       => 'Circumcision Prices London | From £200 | Edgware Clinic',
			'description' => 'Circumcision prices by age band, from £200 for babies to £1,080 for adults with a foreskin problem. Aftercare, the next-day call and free follow-ups included.',
			'eyebrow'     => 'By age band · Everything included',
			'h1'          => 'Circumcision prices',
			'lede'        => 'Priced by age, because the method and the time in the room change with it. Every figure below covers the anaesthetic, the procedure, preparation videos, printed aftercare, a call the day after and free follow-up appointments until healing is complete.',
			'crumbs'      => array(
				array(
					'label' => 'Prices',
					'href'  => '',
				),
			),
			'faqs'        => array(
				array(
					'q' => 'Why is it priced by age?',
					'a' => '<p>Because the method and the time involved change with age. Babies and toddlers have the ring method, which is quick. Older boys and adults have the forceps guided method, which takes longer and usually needs closing with stitches or glue. The bands reflect that.</p>',
				),
				array(
					'q' => 'Why does sedation cost £800?',
					'a' => '<p>Because IV sedation is a separate clinical service, not a box to tick. It has to be pre-booked and paid for in advance, and you will need somebody to take you home afterwards.</p><p>Two honest things: the great majority of adults do not have it and describe the procedure afterwards as far less of an ordeal than they had built up. And if you have real anxiety or a needle phobia, it is a proper clinical option and having it beats putting the whole thing off for another five years.</p>',
				),
				array(
					'q' => 'What if there is a medical or foreskin problem?',
					'a' => '<p>For adults it is £1,080, with or without removal of the frenulum. For children it is quoted after examination rather than from the age-band list, because what is involved varies a great deal.</p>',
				),
				array(
					'q' => 'Is anything added afterwards?',
					'a' => '<p>No. The price covers the local anaesthetic, the procedure, the preparation videos, printed aftercare, the phone call the day after, free follow-up appointments until healing is complete, and a GP letter. Optional IV sedation is the only extra, and it is priced above.</p>',
				),
				array(
					'q' => 'Do you offer a GP or school letter?',
					'a' => '<p>Yes, both, and they are included. The GP letter updates your medical records. School and work letters are given where they are needed.</p>',
				),
			),
		),
		'aftercare' => array(
			'slug'        => 'aftercare',
			'path'        => '/aftercare',
			'name'        => 'Aftercare',
			'title'       => 'Circumcision Aftercare | Healing Day by Day | Edgware Clinic',
			'description' => 'Circumcision aftercare for babies, boys and adults: what normal healing looks like day by day, what to do at nappy changes, and exactly when to call the clinic.',
			'eyebrow'     => 'Written to be read at 2am',
			'h1'          => 'Circumcision aftercare',
			'lede'        => 'What normal healing looks like, day by day, and the specific things that mean you should pick up the phone. Everyone we treat leaves with this in writing and a number that reaches the clinic out of hours.',
			'crumbs'      => array(
				array(
					'label' => 'Aftercare',
					'href'  => '',
				),
			),
			'faqs'        => array(
				array(
					'q' => 'The tip looks yellow. Is it infected?',
					'a' => '<p>Almost certainly not. A yellowish film over the healing edge is granulation tissue and it is the commonest thing parents call us about. Infection looks different: spreading redness up the shaft, heat, swelling that is getting worse rather than better after day three, a bad smell, or a fever. Call us if you see those.</p>',
				),
				array(
					'q' => 'How much bleeding is normal?',
					'a' => '<p>Spotting on the nappy or the dressing for the first day or two is normal. Bleeding that soaks through, or that does not stop after ten minutes of firm, steady pressure with a clean cloth, is not. Apply pressure, keep applying it, and call us. If you cannot reach us quickly, go to A&E.</p>',
				),
				array(
					'q' => 'When can he go swimming?',
					'a' => '<p>Two weeks for boys, and not until fully healed for adults. Baths are fine from the day after; it is pools, the sea and anything with chlorine that wait.</p>',
				),
				array(
					'q' => 'The ring has not come off yet.',
					'a' => '<p>Seven to ten days is usual and up to fourteen still happens. If it is still attached at day fourteen, call us and bring your baby in. Removing it here takes two minutes. Do not pull it at home, however loose it looks.</p>',
				),
				array(
					'q' => 'When do I actually need to call?',
					'a' => '<p>Bleeding that will not stop with ten minutes of pressure. No urine passed in twelve hours. A fever. Spreading redness. Swelling worsening after day three. The ring still on at day fourteen. Or anything that simply worries you, which is a good enough reason on its own.</p>',
				),
			),
		),
	);
}

/**
 * Block markup for a misc page slug.
 *
 * @param string $slug prices|aftercare.
 * @return string
 */
function cil_misc_page_blocks( $slug ) {
	if ( 'prices' === $slug ) {
		return cil_prices_page_blocks();
	}
	if ( 'aftercare' === $slug ) {
		return cil_aftercare_page_blocks();
	}
	return '';
}

/**
 * Price-table rows with book URLs from the shared catalog.
 *
 * @param string $group ages|adults|other.
 * @return array<int, array<string, string>>
 */
function cil_price_table_rows( $group ) {
	$catalog = cil_price_catalog();
	$rows    = isset( $catalog[ $group ] ) ? $catalog[ $group ] : array();
	$out     = array();

	foreach ( $rows as $row ) {
		$name          = isset( $row['name'] ) ? $row['name'] : '';
		$row['url']    = cil_book_for_url( $name );
		$row['cta']    = isset( $row['cta'] ) && $row['cta'] ? $row['cta'] : 'Book this';
		$row['desc']   = isset( $row['desc'] ) ? $row['desc'] : '';
		$row['href']   = isset( $row['href'] ) ? $row['href'] : '';
		$out[]         = $row;
	}

	return $out;
}

/**
 * Compact section heading used above price tables in the prototype.
 *
 * @param string $heading Heading text.
 * @param string $lede    Optional lede.
 * @return string
 */
function cil_price_section_head_html( $heading, $lede = '' ) {
	$html = '<div class="section-head" style="margin-bottom:24px" data-reveal>';
	$html .= '<h2 class="display d-2">' . esc_html( $heading ) . '</h2>';
	if ( $lede ) {
		$html .= '<p class="lede" style="margin-top:14px;font-size:17px">' . esc_html( $lede ) . '</p>';
	}
	$html .= '</div>';
	return $html;
}

/**
 * Gutenberg markup for /prices.
 *
 * @return string
 */
function cil_prices_page_blocks() {
	$page   = cil_misc_pages()['prices'];
	$clinic = cil_clinic();

	$items = '';
	foreach ( cil_included_items() as $item ) {
		$items .= '<li style="display:flex;gap:9px;align-items:flex-start;font-size:16px;color:var(--muted)"><span aria-hidden="true" style="color:var(--blue)">·</span><span>' . esc_html( $item ) . '</span></li>';
	}

	$included = '<div class="callout" data-reveal>
      <span class="caps" style="color:var(--blue-deep)">Included in every price on this page</span>
      <ul class="grid g-2" style="list-style:none;margin-top:16px;gap:10px 30px">
        ' . $items . '
      </ul>
      <p class="muted" style="margin-top:16px;font-size:15px">Carried out in a clean, modern clinic registered with
      the Care Quality Commission and rated ' . esc_html( $clinic['cqc_rating'] ) . '.</p>
    </div>';

	$ages_note = cil_proto_html(
		'<p class="muted" style="margin-top:22px;font-size:15px">
      Children with a medical or foreskin problem are quoted after examination. See
      <a href="/babies" style="color:var(--blue-deep)">babies and toddlers</a> or
      <a href="/children" style="color:var(--blue-deep)">children and teenagers</a>.
    </p>'
	);

	$compare = cil_proto_html(
		'<div class="card" data-reveal>
      <h2 class="display d-2">Comparing us with somewhere cheaper</h2>
      <div class="body-text" style="margin-top:16px">
        <p>A lower headline figure is not automatically a worse deal, and a higher one is not automatically better
        care. Five questions settle it, and they work on any clinic including this one.</p>
        <p>Does the price include the local anaesthetic? Does it include follow-up until healing is complete? Who
        exactly will carry out the procedure, and are they named on the website? Is the clinic registered with the
        Care Quality Commission, which is a legal requirement? And will they test the anaesthetic and show you it has
        worked before they begin?</p>
        <p>We have answered all five on this site so you can ask the same of anybody else.</p>
      </div>
    </div>
    <div class="card" data-reveal style="margin-top:20px">
      <h2 class="display d-2">The NHS alternative</h2>
      <div class="body-text" style="margin-top:16px">
        <p>Where there is a medical need the NHS provides circumcision at no cost, and if you can wait that is a
        perfectly reasonable choice. From what our patients tell us, the wait runs beyond a year. Religious and
        cultural circumcision is not funded.</p>
        <p>Start with your GP if you are unsure which applies to you. We will tell you the same thing if you ask us.</p>
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
			cil_html_block( $included ),
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => 'bg-card edge',
			'wrap' => 'wrap',
		),
		array(
			cil_html_block(
				cil_price_section_head_html(
					'Babies, children and teenagers',
					'The ring method for babies and toddlers, the forceps guided method for older boys. We decide which after examining him.'
				)
			),
			cil_dyn_block(
				'cil/price-table',
				array(
					'rows' => cil_price_table_rows( 'ages' ),
				)
			),
			cil_html_block( $ages_note ),
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => '',
			'wrap' => 'wrap',
		),
		array(
			cil_html_block(
				cil_price_section_head_html(
					'Adults',
					'Forceps guided, closed with stitches and glue. Which band applies depends on whether there is a foreskin problem to treat, which we establish by examining.'
				)
			),
			cil_dyn_block(
				'cil/price-table',
				array(
					'rows' => cil_price_table_rows( 'adults' ),
				)
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
			cil_html_block( cil_price_section_head_html( 'Quoted separately' ) ),
			cil_dyn_block(
				'cil/price-table',
				array(
					'rows' => cil_price_table_rows( 'other' ),
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
			cil_html_block( $compare ),
		)
	);

	$blocks[] = cil_dyn_block(
		'cil/cta-band',
		array(
			'title' => 'Not sure which band applies?',
			'text'  => 'Call and tell us his age, or describe the problem, and we will tell you the figure before you come anywhere near the clinic.',
		)
	);

	$blocks[] = cil_dyn_block(
		'cil/faq',
		array(
			'heading' => 'Questions about cost',
			'items'   => $page['faqs'],
		)
	);

	return cil_serialize_blocks( $blocks );
}

/**
 * Gutenberg markup for /aftercare.
 *
 * @return string
 */
function cil_aftercare_page_blocks() {
	$page   = cil_misc_pages()['aftercare'];
	$clinic = cil_clinic();

	$difference = cil_proto_html(
		'<div data-reveal>
        <span class="caps eyebrow">Normal, and not normal</span>
        <h2 class="display d-1">Telling the difference</h2>
        <div class="body-text" style="margin-top:22px">
          <p><strong>Normal.</strong> Redness around the edge. A yellowish film on the healing tip. Swelling that
          peaks around day two or three then eases. Spotting on the dressing or nappy for a day or two. Stitches
          working loose. Itching as it heals.</p>
          <p><strong>Call us.</strong> Bleeding that does not stop with ten minutes of firm pressure. No urine passed
          in twelve hours. A temperature. Redness spreading up the shaft. Swelling getting worse after day three. A
          bad smell. The Plastibell ring still attached at day fourteen. Pain that painkillers are not touching.</p>
          <p>And anything that simply worries you. That is a good enough reason, we would far rather look and find
          nothing, and nobody here will make you feel you have wasted their time.</p>
        </div>
      </div>'
	);

	$reach_rows = array(
		array(
			'k' => 'During opening hours',
			'v' => '<a href="' . esc_url( $clinic['phone']['href'] ) . '" data-track="call-aftercare" style="color:var(--blue-deep);text-decoration:none">' . esc_html( $clinic['phone']['display'] ) . '</a>',
		),
		array(
			'k' => 'Out of hours',
			'v' => 'The number on your aftercare sheet',
		),
		array(
			'k' => 'By message',
			'v' => '<a href="' . esc_url( $clinic['whatsapp']['href'] ) . '" rel="noopener" target="_blank" data-track="whatsapp-aftercare" style="color:var(--blue-deep);text-decoration:none">WhatsApp</a>',
		),
		array(
			'k' => 'Cannot reach us',
			'v' => 'A&amp;E, or 111 for advice',
		),
		array(
			'k' => 'Next-day call',
			'v' => 'We ring you, you do not chase us',
		),
		array(
			'k' => 'Follow-up visits',
			'v' => 'Included until healed',
		),
	);

	$reach = '<div data-reveal data-reveal-delay="120">
        <div class="card">
          <span class="caps eyebrow">Reaching us</span>
          <h2 class="display d-2" style="margin-bottom:18px">Who to call, and when</h2>
          ' . cil_render_part( 'spec-list', array( 'rows' => $reach_rows ) ) . '
          <p class="muted" style="margin-top:16px;font-size:15px">Photographs sent on WhatsApp are genuinely useful and
          we are asked for that constantly. Send one if it helps.</p>
        </div>
      </div>';

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
			cil_html_block( cil_render_part( 'urgent-note' ) ),
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
					'eyebrow' => 'Babies',
					'heading' => 'The first ten days after a Plastibell',
					'lede'    => '',
					'display' => 'd-2',
				)
			),
			cil_dyn_block(
				'cil/steps',
				array(
					'items' => array(
						array(
							'h' => 'Days 1 to 2',
							'p' => 'Change nappies often. A generous smear of petroleum jelly at every change stops the nappy sticking, which is the single most useful thing you can do. Expect some spotting. Normal feeding, normal sleeping.',
						),
						array(
							'h' => 'Days 3 to 6',
							'p' => 'The area looks red and the tip develops a yellowish film. That film is healing tissue, not infection. Bathing is fine. The ring starts to look loose and darker.',
						),
						array(
							'h' => 'Days 7 to 10',
							'p' => 'The ring separates and comes away on its own, usually into the nappy. Do not pull it. Underneath will look pink and slightly raw for a few more days.',
						),
						array(
							'h' => 'Weeks 2 to 6',
							'p' => 'Appearance settles steadily. Keep using petroleum jelly until the skin looks like ordinary skin. We see you for follow-up until we are both happy.',
						),
					),
				)
			),
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => '',
			'wrap' => 'wrap',
		),
		array(
			cil_dyn_block(
				'cil/section-head',
				array(
					'eyebrow' => 'Boys and adults',
					'heading' => 'After a surgical circumcision',
					'lede'    => '',
					'display' => 'd-2',
				)
			),
			cil_dyn_block(
				'cil/steps',
				array(
					'items' => array(
						array(
							'h' => 'Days 1 to 3',
							'p' => 'Swelling and a dull ache, worst on day two. Paracetamol and ibuprofen together, at the doses on the packet. Keep it dry for twenty-four hours, then shower normally and pat dry. Loose clothing and supportive underwear.',
						),
						array(
							'h' => 'Week 1',
							'p' => 'Swelling peaks then eases. The glans feels raw against clothing, which passes. Desk work or school from day two or three. No cycling, no lifting, no sport.',
						),
						array(
							'h' => 'Weeks 2 to 4',
							'p' => 'Stitches begin dissolving and may come away on underwear. Gym and sport from two weeks. Night-time erections wake adults in the first fortnight and are the most uncomfortable part of the whole thing.',
						),
						array(
							'h' => 'Weeks 4 to 6',
							'p' => 'Adults: sexual activity from four weeks at the earliest, six if healing has been slower. This is the instruction people are most tempted to shorten and the one that most often causes a problem.',
						),
					),
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
			cil_split_block(
				array(
					cil_html_block( $difference ),
					cil_html_block( $reach ),
				)
			),
		)
	);

	$blocks[] = cil_dyn_block(
		'cil/cta-band',
		array(
			'title' => 'Already had it done and something is worrying you?',
			'text'  => 'Call. Follow-up appointments are included until healing is complete, and that includes the visit where we look and tell you it is fine.',
		)
	);

	$blocks[] = cil_dyn_block(
		'cil/faq',
		array(
			'heading' => 'Aftercare questions',
			'items'   => $page['faqs'],
		)
	);

	return cil_serialize_blocks( $blocks );
}
