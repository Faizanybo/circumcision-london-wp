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
			'lede'        => 'The price includes the procedure, local anaesthetic, preparation/aftercare information, a next-day follow-up call, written aftercare, free follow-up appointments during healing and a GP letter, nursery/ school/ work letter where required.',
			'crumbs'      => array(
				array(
					'label' => 'Prices',
					'href'  => '',
				),
			),
			'faqs'        => array(),
		),
		'aftercare' => array(
			'slug'        => 'aftercare',
			'path'        => '/aftercare',
			'name'        => 'Aftercare',
			'title'       => 'Circumcision Aftercare | Healing Day by Day | Edgware Clinic',
			'description' => 'Circumcision aftercare for babies, boys and adults: what normal healing looks like day by day, what to do at nappy changes, and exactly when to call the clinic.',
			'eyebrow'     => 'Written instructions · Videos · Follow-up',
			'h1'          => 'Circumcision aftercare',
			'lede'        => 'Good aftercare is an important part of the service. Before you leave the clinic, we explain what to expect, show you what you need to do and give you written instructions. Preparation and aftercare videos are also available for the relevant age group and method.',
			'crumbs'      => array(
				array(
					'label' => 'Aftercare',
					'href'  => '',
				),
			),
			'faqs'        => array(),
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
		$name       = isset( $row['name'] ) ? $row['name'] : '';
		$row['url'] = ! empty( $row['url'] ) ? $row['url'] : cil_book_for_url( $name );
		$row['cta'] = isset( $row['cta'] ) && $row['cta'] ? $row['cta'] : 'Book this';
		$row['desc'] = isset( $row['desc'] ) ? $row['desc'] : '';
		$row['href'] = isset( $row['href'] ) ? $row['href'] : '';
		$out[]      = $row;
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
      Children with medical/foreskin problems: contact the clinic for a quote. See
      <a href="/babies" style="color:var(--blue-deep)">babies and toddlers</a> or
      <a href="/children" style="color:var(--blue-deep)">children and teenagers</a>.
    </p>'
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
					'The ring method is commonly used for babies and toddlers. The forceps-guided method is commonly used for older boys. The method is decided after examination.'
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
					'Adult circumcision with no medical/foreskin problem, with frenulum removal, or with a medical/foreskin problem +/- frenulum removal.'
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

	$blocks[] = cil_dyn_block(
		'cil/cta-band',
		array(
			'title' => 'Not sure which band applies?',
			'text'  => 'Call and tell us the age, or describe the problem, and we will tell you the figure.',
		)
	);

	if ( ! empty( $page['faqs'] ) ) {
		$blocks[] = cil_dyn_block(
			'cil/faq',
			array(
				'heading' => 'Questions about cost',
				'items'   => $page['faqs'],
			)
		);
	}

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
        <span class="caps eyebrow">When to seek urgent help</span>
        <h2 class="display d-1">Emergency contact</h2>
        <div class="body-text" style="margin-top:22px">
          <p>Use the emergency contact instructions supplied by the clinic if you are concerned after a procedure. If there is a medical emergency and the clinic cannot be reached, use the appropriate NHS emergency service and take the aftercare information with you.</p>
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
					'eyebrow' => 'Overview',
					'heading' => 'Aftercare topics covered by the clinic',
					'lede'    => 'This page is an overview. Do not treat it as a substitute for the clinic\'s current aftercare sheets and videos.',
					'display' => 'd-2',
				)
			),
			cil_dyn_block(
				'cil/info-cards',
				array(
					'items' => array(
						array(
							'title' => 'Baby/toddler ring-method aftercare',
							'body'  => 'Written instructions and videos are provided for the Plastibell or Circumplast ring method. See also the <a href="' . esc_url( cil_path_url( '/babies' ) ) . '">babies and toddlers</a> page.',
						),
						array(
							'title' => 'Children\'s forceps-guided aftercare',
							'body'  => 'Aftercare for older children is explained before you leave. See the <a href="' . esc_url( cil_path_url( '/children' ) ) . '">children and teenagers</a> page.',
						),
						array(
							'title' => 'Adult forceps-guided aftercare',
							'body'  => 'Adult aftercare is given in writing before you leave. See the <a href="' . esc_url( cil_path_url( '/adults' ) ) . '">adult circumcision</a> page.',
						),
						array(
							'title' => 'Nappies or underwear',
							'body'  => 'Guidance on nappies or underwear is included in the written instructions for the method used.',
						),
						array(
							'title' => 'Cleaning and keeping the area dry',
							'body'  => 'The clinic shows you what you need to do and gives you written instructions before you leave.',
						),
						array(
							'title' => 'What normal healing can look like',
							'body'  => 'Preparation and aftercare videos are available for the relevant age group and method.',
						),
						array(
							'title' => 'What to do if there is bleeding',
							'body'  => 'Follow the written aftercare sheet given to you. Contact the clinic if you are concerned.',
						),
						array(
							'title' => 'Special advice for a buried penis',
							'body'  => 'See the <a href="' . esc_url( cil_path_url( '/buried-penis' ) ) . '">buried penis</a> page and follow the method-specific instructions given at the clinic.',
						),
						array(
							'title' => 'When to contact the clinic',
							'body'  => 'We contact patients or parents the day after the circumcision to check progress and understanding of the aftercare. Follow-up appointments during the healing period are included where needed.',
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
			'title' => 'Already had the procedure and something is worrying you?',
			'text'  => 'Call the clinic. Follow-up appointments during the healing period are included where needed.',
		)
	);

	if ( ! empty( $page['faqs'] ) ) {
		$blocks[] = cil_dyn_block(
			'cil/faq',
			array(
				'heading' => 'Aftercare questions',
				'items'   => $page['faqs'],
			)
		);
	}

	return cil_serialize_blocks( $blocks );
}
