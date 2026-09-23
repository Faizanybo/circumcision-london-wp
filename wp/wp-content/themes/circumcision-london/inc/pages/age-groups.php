<?php
/**
 * Babies, children and adults pages from prototype src/pages/agegroups.js.
 *
 * Content is serialized into Gutenberg blocks so the pages stay editable.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared local-anaesthetic copy. Identical on all three age pages in the prototype.
 *
 * @return string
 */
function cil_anaesthetic_html() {
	return cil_proto_html(
		'<div class="body-text">
        <p>The circumcision is carried out under local anaesthetic. The patient is awake, but the penis is numb and
        no pain is felt.</p>
        <p>It is given at the base of the penis with a very fine needle, and it usually works within a minute. Often
        one injection point is all that is needed, and most patients do not react to the needle going in at all. What
        does sting, for a few seconds, is the solution itself. That passes quickly and the area goes numb.</p>
        <p><strong>We do not use numbing creams such as EMLA.</strong> The manufacturer contra-indicates them around
        the genitals, and they would make no difference to the stinging sensation anyway, which is the only
        uncomfortable part of the whole thing.</p>
        <p><strong>We test before we start.</strong> Once the anaesthetic has worked we test the area and show you
        that no pain is felt. We only begin once you tell us you are happy. That is not a formality; it is how every
        procedure in this clinic starts.</p>
        <p>Local anaesthetic is safe at every age, and it is illegal to circumcise without it.</p>
      </div>'
	);
}

/**
 * Clinically reviewed-by line used on the age-group pages.
 *
 * @return string
 */
function cil_reviewed_by_haidar() {
	return '<a href="' . esc_url( cil_path_url( '/team#haidar' ) ) . '">Dr Haidar Al-Ali, BDS, MFDS RCPS (Glasg)</a>, September 2026';
}

/**
 * Prototype data for /babies, /children and /adults.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_age_pages() {
	$prices_note = ' See the <a href="' . esc_url( cil_path_url( '/prices' ) ) . '" style="color:var(--blue-deep)">full price list</a>, broken down by age band.';
	$aftercare   = cil_path_url( '/aftercare' );

	return array(
		'babies'   => array(
			'slug'        => 'babies',
			'name'        => 'Babies and toddlers',
			'title'       => 'Baby Circumcision London | From £200 | Edgware Clinic',
			'description' => 'Baby and toddler circumcision in Edgware, North-West London. The Plastibell or Circumplast ring method under local anaesthetic, about ten minutes. From £200.',
			'eyebrow'     => 'Best under one month old · From £200',
			'h1'          => 'Baby and toddler circumcision',
			'lede'        => 'Circumcision can be an anxious time for parents. We aim to make the process as clear and reassuring as possible by explaining what will happen before the appointment, talking you through each stage on the day and providing detailed aftercare afterwards.',
			'price_note'  => 'From £200, depending on age.' . $prices_note,
			'form_id'     => 'babies',
			'subject'     => 'babies and toddlers enquiry',
			'schema_name' => 'Infant circumcision',
			'offer'       => '200',
			'intro'       => cil_proto_html(
				'<p>When you book, we send preparation information and videos explaining what to expect. At the clinic, your son is given local anaesthetic and we check the area before beginning the circumcision.</p>
    <p>For many babies and toddlers we use the Plastibell or Circumplast ring method. With this method, a plastic ring is positioned around the head of the penis and the foreskin is secured over it. The unwanted foreskin and ring then separate naturally, usually within 3 to 14 days. No stitches are normally required with the ring method.</p>'
			),
			'spec'        => array(
				array( 'k' => 'Best age', 'v' => 'Under one month where possible' ),
				array( 'k' => 'Common method', 'v' => 'Plastibell / Circumplast ring' ),
				array( 'k' => 'Anaesthetic', 'v' => 'Local anaesthetic' ),
				array( 'k' => 'Procedure time for a young baby', 'v' => 'Approximately 10 minutes' ),
				array( 'k' => 'Stitches with ring method', 'v' => 'None' ),
				array( 'k' => 'Ring separation', 'v' => 'Usually 3–14 days' ),
				array( 'k' => 'Price', 'v' => 'From £200, depending on age' ),
			),
			'figure'      => 'baby-parent',
			'sections'    => array(
				array(
					'eyebrow' => 'Before you come',
					'heading' => 'What to bring',
					'banded'  => true,
					'narrow'  => false,
					'html'    => cil_proto_html(
						'<div class="body-text" style="max-width:74ch">
        <ul>
          <li>Tight fitting clean nappies and baby wipes</li>
          <li>Milk bottle if used</li>
          <li>Dummy/ pacifier to soothe the baby. Please buy one for the circumcision even if your son does not use usually use it</li>
          <li>Birth certificate/ passport if you have it issued</li>
          <li>Red Book</li>
          <li>Photo identification for the parents</li>
          <li>Any relevant medical letters if your child has an existing condition (please send this prior to the appointment)</li>
        </ul>
        <p style="margin-top:16px">If only one parent can attend, or your family circumstances are different, contact the clinic before the appointment so the team can explain the consent and identification documents required.</p>
      </div>'
					),
				),
				array(
					'eyebrow' => 'Afterwards',
					'heading' => 'After the procedure',
					'banded'  => false,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>Once the procedure is complete, your baby\'s nappy and clothes can be put back on. You will be given written aftercare instructions and shown what to do at home. The clinic also provides preparation and aftercare videos.</p>
        <p>If the ring remains attached after the expected period, contact the clinic for advice. Free follow-up appointments are available during the healing period where clinically appropriate. See the <a href="' . esc_url( $aftercare ) . '">aftercare page</a> for an overview of healing support.</p>
      </div>'
					),
				),
				array(
					'eyebrow' => 'Safety first',
					'heading' => 'When treatment may be delayed',
					'banded'  => true,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>Circumcision may be postponed if a baby is unwell or if there are other clinical concerns. We may advise against routine circumcision where there is an anatomical condition such as certain types of hypospadias, because the foreskin can sometimes be needed for later reconstruction. Tell us about any family history of bleeding disorders before the appointment.</p>
      </div>'
					),
				),
			),
			'faqs'        => array(),
		),
		'children' => array(
			'slug'        => 'children',
			'name'        => 'Children and teenagers',
			'title'       => 'Circumcision for Boys London | From £280 | Edgware',
			'description' => 'Circumcision for children and teenagers in Edgware, North-West London. The forceps guided method under local anaesthetic. School holidays book up fast. From £280.',
			'eyebrow'     => 'One to seventeen years · From £280',
			'h1'          => 'Circumcision for children and teenagers',
			'lede'        => 'Circumcision can make both a child and his parents anxious. When you book, we will send you simple and short videos to watch so that you know what to expect and how to prepare your son for circumcision in a way that is appropriate for the child\'s age.',
			'price_note'  => 'From £280, depending on age.' . $prices_note,
			'form_id'     => 'children',
			'subject'     => 'children and teenagers enquiry',
			'schema_name' => 'Child circumcision',
			'offer'       => '280',
			'intro'       => cil_proto_html(
				'<p>Parents can ask questions anytime before the appointment if they have anything they are unsure about.</p>
    <p>Local anaesthetic is given and the area is checked before the procedure. For older children and teenagers we commonly use the forceps-guided (traditional) method with thermal cautery to minimize bleeding. The foreskin is drawn forward and a specialist forceps guides the removal. Depending on the wound, closure may involve skin glue, dissolvable stitches, a combination of both, or occasionally neither.</p>
    <p>Afterwards, the patient can put on his own underwear, trousers and walk normally. We provide written aftercare instructions and explain how to care for the area at home. See the <a href="' . esc_url( $aftercare ) . '">aftercare page</a>.</p>'
			),
			'spec'        => array(
				array( 'k' => 'Age', 'v' => 'Children and teenagers' ),
				array( 'k' => 'Common method', 'v' => 'Forceps-guided (traditional) with thermal cautery' ),
				array( 'k' => 'Anaesthetic', 'v' => 'Local anaesthetic' ),
				array( 'k' => 'Closure', 'v' => 'Skin glue, dissolvable stitches, both, or occasionally neither' ),
				array( 'k' => 'School letters', 'v' => 'Ask the clinic if needed' ),
				array( 'k' => 'Price', 'v' => 'From £280, depending on age' ),
			),
			'figure'      => 'waiting-room',
			'sections'    => array(
				array(
					'eyebrow' => 'Before you come',
					'heading' => 'Preparing your child',
					'banded'  => true,
					'narrow'  => false,
					'html'    => cil_proto_html(
						'<div class="body-text" style="max-width:74ch">
        <ul>
          <li>Keep the explanation simple and age appropriate. Too much detail can increase anxiety. Our practitioners are accustomed to talking directly to children and teenagers and explaining the process calmly on the day.</li>
          <li>Tell us about any medical problems before the appointment.</li>
          <li>Bring the identification and consent documents requested by the clinic.</li>
          <li>Bring something familiar to keep your child occupied such as a tablet or video game.</li>
          <li>If you need a school letter, ask the clinic.</li>
          <li>School-holiday appointments can be busy, so book early if timing is important.</li>
        </ul>
      </div>'
					),
				),
				array(
					'eyebrow' => 'Medical need',
					'heading' => 'Medical or foreskin problems',
					'banded'  => false,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>If your son has <a href="/conditions/phimosis">phimosis</a>, recurrent inflammation, a <a href="/buried-penis">buried penis</a> or another foreskin problem, tell us when you contact the clinic. These cases may need a more detailed examination and an individual quotation.</p>
      </div>'
					),
				),
			),
			'faqs'        => array(),
		),
		'adults'   => array(
			'slug'        => 'adults',
			'name'        => 'Adult men',
			'title'       => 'Adult Circumcision London | From £680 | Edgware Clinic',
			'description' => 'Adult circumcision in Edgware, North-West London. Forceps guided under local anaesthetic, awake and comfortable throughout. IV sedation available. From £680.',
			'eyebrow'     => 'Eighteen and over · From £680',
			'h1'          => 'Adult circumcision',
			'lede'        => 'We provide adult circumcision for a wide range of reasons, including religious and cultural choice, personal preference and medical foreskin problems such as phimosis. We also assess men who are unhappy with a previous circumcision and want revision or re-circumcision.',
			'price_note'  => 'From £680, or £880 with frenulum removal, or £1,080 with a medical or foreskin problem.' . $prices_note,
			'form_id'     => 'adults',
			'subject'     => 'adult men enquiry',
			'schema_name' => 'Adult circumcision',
			'offer'       => '680',
			'intro'       => cil_proto_html(
				'<p>The circumcision is carried out under local anaesthetic. You remain awake and can talk to the practitioner, watch videos or listen to music. We allow the anaesthetic time to work and check the area before the procedure begins.</p>
    <p>For adults we commonly use the forceps-guided method with thermal cautery. Depending on the individual case, the wound may be closed with dissolvable stitches and/or skin glue.</p>'
			),
			'spec'        => array(
				array( 'k' => 'Anaesthetic', 'v' => 'Local anaesthetic' ),
				array( 'k' => 'During', 'v' => 'Awake; talk, videos or music' ),
				array( 'k' => 'Common method', 'v' => 'Forceps-guided with thermal cautery' ),
				array( 'k' => 'Closure', 'v' => 'Dissolvable stitches and/or skin glue' ),
				array( 'k' => 'Follow-up', 'v' => 'Available during the healing period if needed' ),
				array( 'k' => 'Price', 'v' => '£680, £880 or £1,080' ),
			),
			'figure'      => 'certificates',
			'sections'    => array(
				array(
					'eyebrow' => 'Before you come',
					'heading' => 'Preparation',
					'banded'  => true,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <ul>
          <li>Bring photo ID.</li>
          <li>Bring close-fitting underwear such as tight V shaped briefs.</li>
          <li>Shave clean the pubic area.</li>
          <li>Buy a 250ml tub of Vaseline.</li>
          <li>Tell us in advance about medical conditions, medication, allergies or bleeding problems.</li>
        </ul>
      </div>'
					),
				),
				array(
					'eyebrow' => 'Afterwards',
					'heading' => 'Work and normal activities',
					'banded'  => false,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>Many patients with desk-based work can return to work immediately, while physically demanding work may require more time away (usually 2 weeks). We can provide a work letter where appropriate. Follow the individual activity restrictions given to you after your procedure.</p>
      </div>'
					),
				),
				array(
					'eyebrow' => 'Afterwards',
					'heading' => 'Aftercare',
					'banded'  => true,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>Before you leave, we explain the aftercare and give you written instructions. You can contact the clinic by telephone, WhatsApp or email if you have questions, and follow-up appointments are available during the healing period if needed. See the <a href="' . esc_url( $aftercare ) . '">aftercare page</a>.</p>
      </div>'
					),
				),
				array(
					'eyebrow' => 'Medical circumcision',
					'heading' => 'Foreskin problems',
					'banded'  => false,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>Adult circumcision may be recommended after assessment for conditions such as <a href="/conditions/phimosis">phimosis</a>, recurrent <a href="/conditions/balanitis">balanitis</a>, <a href="/conditions/bxo">BXO/lichen sclerosus</a> or other foreskin problems. We also assess <a href="/re-circumcision">re-circumcision or revision</a>.</p>
      </div>'
					),
				),
			),
			'faqs'        => array(),
		),
	);
}


/**
 * Self-closing dynamic block.
 *
 * @param string               $name  Block name.
 * @param array<string, mixed> $attrs Attributes.
 * @return array<string, mixed>
 */
function cil_dyn_block( $name, $attrs = array() ) {
	return array(
		'blockName'    => $name,
		'attrs'        => $attrs,
		'innerBlocks'  => array(),
		'innerHTML'    => '',
		'innerContent' => array(),
	);
}

/**
 * Custom HTML block.
 *
 * @param string $html Markup.
 * @return array<string, mixed>
 */
function cil_html_block( $html ) {
	return array(
		'blockName'    => 'core/html',
		'attrs'        => array(),
		'innerBlocks'  => array(),
		'innerHTML'    => $html,
		'innerContent' => array( $html ),
	);
}

/**
 * Native paragraph block. $content may be inner HTML or a full <p>.
 *
 * @param string $content Paragraph HTML.
 * @return array<string, mixed>
 */
function cil_core_paragraph( $content ) {
	$content = trim( $content );
	if ( ! preg_match( '/^<p\b/i', $content ) ) {
		$content = '<p>' . $content . '</p>';
	}
	return array(
		'blockName'    => 'core/paragraph',
		'attrs'        => array(),
		'innerBlocks'  => array(),
		'innerHTML'    => $content,
		'innerContent' => array( $content ),
	);
}

/**
 * Native heading block. Never emits H1 — page-head owns the H1.
 *
 * @param string $text  Heading text (may include links).
 * @param int    $level 2–6.
 * @return array<string, mixed>
 */
function cil_core_heading( $text, $level = 2 ) {
	$level = max( 2, min( 6, (int) $level ) );
	$tag   = 'h' . $level;
	$html  = '<' . $tag . ' class="wp-block-heading">' . $text . '</' . $tag . '>';
	return array(
		'blockName'    => 'core/heading',
		'attrs'        => array( 'level' => $level ),
		'innerBlocks'  => array(),
		'innerHTML'    => $html,
		'innerContent' => array( $html ),
	);
}

/**
 * Native list block with list-item children.
 *
 * @param array<int, string> $items Item inner HTML.
 * @return array<string, mixed>
 */
function cil_core_list( $items ) {
	$inner         = array();
	$inner_content = array( '<ul class="wp-block-list">' );
	foreach ( $items as $item ) {
		$li              = '<li>' . $item . '</li>';
		$inner[]         = array(
			'blockName'    => 'core/list-item',
			'attrs'        => array(),
			'innerBlocks'  => array(),
			'innerHTML'    => $li,
			'innerContent' => array( $li ),
		);
		$inner_content[] = null;
	}
	$inner_content[] = '</ul>';

	return array(
		'blockName'    => 'core/list',
		'attrs'        => array(),
		'innerBlocks'  => $inner,
		'innerHTML'    => '',
		'innerContent' => $inner_content,
	);
}

/**
 * Custom HTML wrapper for a cluster of body paragraphs (preserves .body-text).
 *
 * @param array<int, string>|string $paragraphs Paragraph inner HTML.
 * @return array<string, mixed>
 */
function cil_body_html( $paragraphs ) {
	$html = '<div class="body-text" data-reveal>';
	foreach ( (array) $paragraphs as $p ) {
		$p = trim( $p );
		if ( '' === $p ) {
			continue;
		}
		if ( ! preg_match( '/^<(p|ul|ol|div|h[2-6])\b/i', $p ) ) {
			$p = '<p>' . $p . '</p>';
		}
		$html .= $p;
	}
	$html .= '</div>';
	return cil_html_block( cil_proto_html( $html ) );
}

/**
 * Static layout block with inner blocks (cil/section, cil/split).
 *
 * @param string                 $name  Block name.
 * @param array<string, mixed>   $attrs Attributes.
 * @param array<int, mixed>      $inner Inner blocks.
 * @param string                 $open  Opening HTML.
 * @param string                 $close Closing HTML.
 * @return array<string, mixed>
 */
function cil_layout_block( $name, $attrs, $inner, $open, $close ) {
	$inner_content = array( $open );
	foreach ( $inner as $_unused ) {
		$inner_content[] = null;
	}
	$inner_content[] = $close;

	return array(
		'blockName'    => $name,
		'attrs'        => $attrs,
		'innerBlocks'  => $inner,
		'innerHTML'    => '',
		'innerContent' => $inner_content,
	);
}

/**
 * cil/section matching editor.js save markup.
 *
 * @param array<string, mixed> $attrs Section attrs.
 * @param array<int, mixed>    $inner Inner blocks.
 * @return array<string, mixed>
 */
function cil_section_block( $attrs, $inner ) {
	$size   = isset( $attrs['size'] ) ? $attrs['size'] : 'section-sm';
	$band   = isset( $attrs['band'] ) ? $attrs['band'] : '';
	$wrap   = isset( $attrs['wrap'] ) ? $attrs['wrap'] : 'wrap';
	$anchor = isset( $attrs['anchor'] ) ? $attrs['anchor'] : '';
	$cls    = trim( 'wp-block-cil-section ' . $size . ( $band ? ' ' . $band : '' ) );
	$id     = $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '';

	$saved = array(
		'size' => $size,
		'band' => $band,
		'wrap' => $wrap,
	);
	if ( $anchor ) {
		$saved['anchor'] = $anchor;
	}

	return cil_layout_block(
		'cil/section',
		$saved,
		$inner,
		'<section class="' . esc_attr( $cls ) . '"' . $id . '><div class="' . esc_attr( $wrap ) . '">',
		'</div></section>'
	);
}

/**
 * cil/split matching editor.js save markup.
 *
 * @param array<int, mixed> $inner   Inner blocks.
 * @param bool              $reverse Reverse on small screens.
 * @return array<string, mixed>
 */
function cil_split_block( $inner, $reverse = false ) {
	$cls = 'wp-block-cil-split split' . ( $reverse ? ' reverse' : '' );

	return cil_layout_block(
		'cil/split',
		array(
			'reverse' => $reverse,
		),
		$inner,
		'<div class="' . esc_attr( $cls ) . '">',
		'</div>'
	);
}

/**
 * Gutenberg block markup for one age-group page.
 *
 * @param string $slug babies|children|adults.
 * @return string
 */
function cil_age_page_blocks( $slug ) {
	$pages = cil_age_pages();
	if ( empty( $pages[ $slug ] ) ) {
		return '';
	}

	$page = $pages[ $slug ];
	$blocks = array();

	$blocks[] = cil_dyn_block(
		'cil/page-head',
		array(
			'eyebrow'    => $page['eyebrow'],
			'title'      => $page['h1'],
			'lede'       => $page['lede'],
			'reviewedBy' => cil_reviewed_by_haidar(),
			'crumbs'     => array(
				array(
					'label' => 'Who we see',
					'href'  => cil_path_url( '/babies' ),
				),
				array(
					'label' => $page['name'],
					'href'  => '',
				),
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
			cil_split_block(
				array(
					cil_rich_html_block( '<div class="body-text">' . $page['intro'] . '</div>' ),
					cil_dyn_block(
						'cil/spec-panel',
						array(
							'eyebrow' => 'At a glance',
							'rows'    => $page['spec'],
							'note'    => $page['price_note'],
						)
					),
				)
			),
		)
	);

	foreach ( $page['sections'] as $section ) {
		$blocks[] = cil_dyn_block(
			'cil/text-section',
			array(
				'eyebrow' => $section['eyebrow'],
				'heading' => $section['heading'],
				'html'    => $section['html'],
				'narrow'  => ! empty( $section['narrow'] ),
				'banded'  => ! empty( $section['banded'] ),
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
					cil_dyn_block(
						'cil/figure',
						array(
							'name' => $page['figure'],
						)
					),
					cil_dyn_block(
						'cil/callback-card',
						array(
							'eyebrow' => 'Request a call back',
							'title'   => 'Ask us first',
							'formId'  => $page['form_id'],
							'subject' => $page['subject'],
							'urgent'  => true,
						)
					),
				)
			),
		)
	);

	$blocks[] = cil_dyn_block( 'cil/cta-band', array() );

	if ( ! empty( $page['faqs'] ) ) {
		$blocks[] = cil_dyn_block(
			'cil/faq',
			array(
				'heading' => $page['name'] . ': questions',
				'items'   => $page['faqs'],
			)
		);
	}

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
					'eyebrow' => 'Also at this clinic',
					'heading' => 'Circumcision at other ages',
					'lede'    => '',
					'display' => 'd-2',
				)
			),
			cil_dyn_block(
				'cil/group-cards',
				array(
					'level'   => 3,
					'exclude' => cil_path_url( '/' . $slug ),
					'banded'  => false,
				)
			),
		)
	);

	return cil_serialize_blocks( $blocks );
}

/**
 * Serialize an array of block arrays into post_content.
 *
 * @param array<int, array<string, mixed>> $blocks Blocks.
 * @return string
 */
function cil_serialize_blocks( $blocks ) {
	$out = '';
	foreach ( $blocks as $block ) {
		$out .= serialize_block( $block ) . "\n\n";
	}
	return trim( $out );
}
