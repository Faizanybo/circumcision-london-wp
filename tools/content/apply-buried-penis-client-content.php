<?php
/**
 * Apply full client Buried Penis content to /buried-penis/ + export fixture.
 *
 * Source: Buried penis.docx
 * Architecture:
 * - Content width matches BXO and Paraphimosis pages ('wrap' => 'wrap' across all sections).
 * - Rich Gutenberg block architecture mirroring BXO and Paraphimosis:
 *   - cil/page-head
 *   - cil/split layouts
 *   - cil/spec-panel
 *   - cil/info-cards with balanced row heights, scroll-reveal and hover effects
 *   - cil/faq (8 client FAQs)
 *   - cil/callback-card (formId="buried")
 *   - cil/cta-band
 *   - .cil-consultation-buttons-grid
 *   - Medical diagram figure placeholder
 *   - Medical review & Medical references
 */
$root    = dirname( __DIR__, 2 );
$wp_load = $root . '/app/public/wp-load.php';
require $wp_load;

if ( ! function_exists( 'cil_serialize_blocks' ) ) {
	fwrite( STDERR, "Theme helpers missing\n" );
	exit( 1 );
}

/**
 * Helper to build a paragraph block.
 *
 * @param string $html Inner HTML.
 * @return array
 */
function cil_buried_p( $html ) {
	$content = '<p>' . $html . '</p>';
	return array(
		'blockName'    => 'core/paragraph',
		'attrs'        => array(),
		'innerBlocks'  => array(),
		'innerHTML'    => $content,
		'innerContent' => array( $content ),
	);
}

/**
 * Helper to build a heading block.
 *
 * @param string $text Plain heading.
 * @param int    $level 2–6.
 * @return array
 */
function cil_buried_h( $text, $level = 2 ) {
	$level = max( 2, min( 6, (int) $level ) );
	$tag   = 'h' . $level;
	$class = ( 3 === $level ) ? 'wp-block-heading display d-3' : 'wp-block-heading display d-2';
	$html  = '<' . $tag . ' class="' . esc_attr( $class ) . '">' . esc_html( $text ) . '</' . $tag . '>';
	return array(
		'blockName'    => 'core/heading',
		'attrs'        => array(
			'level'     => $level,
			'className' => $class,
		),
		'innerBlocks'  => array(),
		'innerHTML'    => $html,
		'innerContent' => array( $html ),
	);
}

/**
 * Helper to build a checklist block.
 *
 * @param array<int, string> $items Escaped or HTML items.
 * @return array
 */
function cil_buried_list( $items ) {
	$inner         = array();
	$inner_content = array( '<ul class="cil-checklist wp-block-list">' );
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
		'attrs'        => array( 'className' => 'cil-checklist' ),
		'innerBlocks'  => $inner,
		'innerHTML'    => '',
		'innerContent' => $inner_content,
	);
}

/**
 * Helper to build an info-cards block.
 *
 * @param string $columns Column layout ('g-2', 'g-3').
 * @param array  $items   Card definitions.
 * @param int    $level   Heading level (default 3).
 * @return array
 */
function cil_buried_info_cards( $columns, $items, $level = 3 ) {
	return cil_dyn_block(
		'cil/info-cards',
		array(
			'columns'      => $columns,
			'headingLevel' => $level,
			'items'        => $items,
		)
	);
}

/**
 * Helper to build a section-head block.
 *
 * @param string $eyebrow Eyebrow text.
 * @param string $heading Section heading.
 * @param string $lede    Optional lede paragraph.
 * @param string $display Heading size ('d-1', 'd-2', 'd-3').
 * @return array
 */
function cil_buried_section_head( $eyebrow, $heading, $lede = '', $display = 'd-2' ) {
	$attrs = array(
		'eyebrow' => $eyebrow,
		'heading' => $heading,
		'display' => $display,
	);
	if ( $lede ) {
		$attrs['lede'] = $lede;
	}
	return cil_dyn_block( 'cil/section-head', $attrs );
}

/**
 * Helper to build a core/group block with exact matching nulls.
 *
 * @param array  $inner_blocks List of block arrays.
 * @param string $class_name   CSS class name (default 'body-text').
 * @return array
 */
function cil_buried_group( $inner_blocks, $class_name = 'body-text' ) {
	return array(
		'blockName'    => 'core/group',
		'attrs'        => array( 'className' => $class_name ),
		'innerBlocks'  => $inner_blocks,
		'innerHTML'    => '',
		'innerContent' => array_merge(
			array( '<div class="wp-block-group ' . esc_attr( $class_name ) . '">' ),
			array_fill( 0, count( $inner_blocks ), null ),
			array( '</div>' )
		),
	);
}

/**
 * Target link paths.
 */
$babies    = home_url( '/babies/' );
$children  = home_url( '/children/' );
$adults    = home_url( '/adults/' );
$phimosis  = home_url( '/conditions/phimosis/' );
$bxo       = home_url( '/conditions/bxo/' );
$recirc    = home_url( '/re-circumcision/' );
$aftercare = home_url( '/aftercare/' );
$prices    = home_url( '/prices/' );
$contact   = home_url( '/contact/' );
$book      = home_url( '/book/' );
$team      = home_url( '/team/#haidar' );

$blocks = array();

// -----------------------------------------------------------------------------
// 1. Hero Page Head
// -----------------------------------------------------------------------------
$blocks[] = cil_dyn_block(
	'cil/page-head',
	array(
		'eyebrow'    => 'Specialist assessment & aftercare · London clinic',
		'title'      => 'Buried Penis and Circumcision',
		'lede'       => 'A buried penis, sometimes called a hidden or concealed penis, is when part of the penis is hidden within the surrounding skin and pubic tissue, making the visible penis appear shorter than it actually is.',
		'reviewedBy' => cil_reviewed_by_haidar(),
		'crumbs'     => array(
			array(
				'label' => 'Who we see',
				'href'  => $babies,
			),
			array(
				'label' => 'Buried penis',
				'href'  => '',
			),
		),
	)
);

// -----------------------------------------------------------------------------
// 2. Direct Answer Box & Overview Split Section (Intro Section)
// -----------------------------------------------------------------------------
$direct_answer_html = '<div class="callout" style="border-left:4px solid var(--blue);background:var(--card);padding:24px;border-radius:12px;margin-bottom:20px">' .
	'<h2 class="display d-3" style="margin-top:0;margin-bottom:10px">Can you circumcise a buried penis?</h2>' .
	'<p style="margin-bottom:0">At Beverley Clinic, we regularly circumcise babies, children, teenagers and adults with mild to severe buried penis. The penis needs to be properly assessed and brought forwards before circumcision so that its true length can be seen and the appropriate amount of foreskin can be removed.</p>' .
	'</div>';

$direct_answer_block = array(
	'blockName'    => 'core/html',
	'attrs'        => array(),
	'innerBlocks'  => array(),
	'innerHTML'    => $direct_answer_html,
	'innerContent' => array( $direct_answer_html ),
);

$intro_left_group = cil_buried_group(
	array(
		$direct_answer_block,
		cil_buried_p( 'Buried penis is particularly common in babies and young children, but it can also occur in older children, teenagers and adults.' ),
		cil_buried_p( 'There are different reasons why a penis may have a buried appearance. In some patients the penis itself is relatively small. In others, the penis is normal in size but the pubic area is relatively high or contains a prominent fatty pad, causing more of the penile shaft to sit within the surrounding tissue. Some patients have a combination of both factors.' ),
		cil_buried_p( 'Having a buried penis does not mean that you or your child cannot be circumcised at Beverley Clinic.' ),
	)
);

$spec_note = 'Assessment across all age groups at our North-West London clinic. See our <a href="' . esc_url( $prices ) . '" style="color:var(--blue-deep)">price list</a>.';

$spec_panel_block = cil_dyn_block(
	'cil/spec-panel',
	array(
		'eyebrow' => 'Buried penis at a glance',
		'rows'    => array(
			array(
				'k' => 'What is a buried penis?',
				'v' => 'Part of the penile shaft is hidden within surrounding pubic tissue or fat pad, making it appear shorter.',
			),
			array(
				'k' => 'Who is affected?',
				'v' => 'Common in babies and toddlers, but also seen in older children, teenagers and adult men.',
			),
			array(
				'k' => 'Does weight cause it?',
				'v' => 'Weight is a factor, but slim boys and men also develop it due to natural anatomy.',
			),
			array(
				'k' => 'Can you circumcise it?',
				'v' => 'Yes. We assess true shaft length and remove the appropriate amount of skin for full extension.',
			),
			array(
				'k' => 'Why is aftercare critical?',
				'v' => 'The penis retracts inwards during healing; gentle backwards pressure prevents adhesions and skin bridges.',
			),
		),
		'note'    => $spec_note,
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
				$intro_left_group,
				$spec_panel_block,
			),
			false
		),
	)
);

// -----------------------------------------------------------------------------
// 3. What is a buried penis?
// -----------------------------------------------------------------------------
$what_is_group = cil_buried_group(
	array(
		cil_buried_h( 'What is a buried penis?', 2 ),
		cil_buried_p( 'A buried penis, sometimes called a hidden or concealed penis, is when part of the penis is hidden within the surrounding skin and pubic tissue, making the visible penis appear shorter than it actually is.' ),
		cil_buried_p( 'Buried penis is particularly common in babies and young children, but it can also occur in older children, teenagers and adults.' ),
		cil_buried_p( 'There are different reasons why a penis may have a buried appearance. In some patients the penis itself is relatively small. In others, the penis is normal in size but the pubic area is relatively high or contains a prominent fatty pad, causing more of the penile shaft to sit within the surrounding tissue. Some patients have a combination of both factors.' ),
		cil_buried_p( 'Having a buried penis does not mean that you or your child cannot be circumcised at Beverley Clinic.' ),
		cil_buried_p( 'We regularly circumcise patients with mild, moderate and severe buried penis, from babies through to adults. The important difference is that a buried penis needs to be recognised before circumcision and the procedure needs to be adapted to the individual\'s anatomy.' ),
		cil_buried_p( 'In particular, the practitioner needs to establish the true penile length and exactly how much skin can safely be removed.' ),
		cil_buried_p( 'Removing too much skin from a buried penis can create problems when the penis is fully extended. Removing too little may produce an unsatisfactory circumcision result. Experience in assessing this anatomy is therefore particularly important.' ),
		cil_buried_p( 'At Beverley Clinic, buried penis is something we routinely look for when examining patients before circumcision.' ),
	)
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'what-is-buried-penis',
	),
	array(
		$what_is_group,
	)
);

// -----------------------------------------------------------------------------
// 4. Why does a penis look buried? (With 5 factors & Medical Diagram placeholder)
// -----------------------------------------------------------------------------
$diagram_html = '<figure class="wp-block-image size-large" style="margin:28px 0;text-align:center">' .
	'<div style="background:var(--surface, #f8fafc);border:2px dashed var(--border, #cbd5e1);border-radius:12px;padding:36px 20px;text-align:center;color:var(--text-muted, #64748b)">' .
	'<div style="font-size:32px;margin-bottom:12px">📐 🩺</div>' .
	'<p style="font-weight:600;font-size:16px;margin-bottom:6px;color:var(--text, #1e293b)">Clinical Medical Diagram</p>' .
	'<p style="font-size:14px;max-width:560px;margin:0 auto 12px">Illustration showing: 1. Normal visible penis · 2. Buried penis with prominent pubic fat pad concealing part of the shaft · 3. Pressing pubic tissue backwards to expose the true shaft length.</p>' .
	'<code style="font-size:12px;background:rgba(0,0,0,0.05);padding:4px 8px;border-radius:4px">buried-penis-pubic-fat-pad.jpg</code>' .
	'</div>' .
	'<figcaption class="wp-element-caption" style="font-size:13px;color:var(--text-muted);margin-top:8px">Diagram showing how a prominent pubic fat pad can conceal part of the penile shaft</figcaption>' .
	'</figure>';

$diagram_block = array(
	'blockName'    => 'core/html',
	'attrs'        => array(),
	'innerBlocks'  => array(),
	'innerHTML'    => $diagram_html,
	'innerContent' => array( $diagram_html ),
);

$why_group = cil_buried_group(
	array(
		cil_buried_h( 'Why does a penis look buried?', 2 ),
		cil_buried_p( 'There is no single reason.' ),
		cil_buried_p( 'The visible length of the penis depends not only on the size of the penis itself but also on its relationship with the surrounding pubic area.' ),
		cil_buried_p( 'A buried appearance may occur because:' ),
		cil_buried_list(
			array(
				'The penis itself is relatively small',
				'The pubic area is relatively high or prominent',
				'There is a significant pubic fat pad surrounding the base of the penis',
				'The penis naturally retracts into the surrounding tissue',
				'There is a combination of these factors',
			)
		),
		cil_buried_p( 'This explains why two patients with a similar penile length can look very different externally.' ),
		cil_buried_p( 'A patient with a normal-sized penis but a relatively high or fatty pubic area may have much less of the shaft visible when standing or sitting normally.' ),
		cil_buried_p( 'When the surrounding tissue is gently compressed backwards, considerably more of the penis may become visible.' ),
		$diagram_block,
	)
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'why-penis-looks-buried',
	),
	array(
		$why_group,
	)
);

// -----------------------------------------------------------------------------
// 5. Is a buried penis the same as a small penis?
// -----------------------------------------------------------------------------
$small_vs_buried_group = cil_buried_group(
	array(
		cil_buried_h( 'Is a buried penis the same as a small penis?', 2 ),
		cil_buried_p( 'Not necessarily.' ),
		cil_buried_p( 'This is an important distinction.' ),
		cil_buried_p( 'Sometimes the penis itself is relatively small.' ),
		cil_buried_p( 'However, in many patients with a buried penis, the penis is normal in size but appears smaller because part of its length is hidden within the surrounding pubic tissue.' ),
		cil_buried_p( 'Some patients have elements of both.' ),
		cil_buried_p( 'During examination, gently compressing the tissue around the base of the penis and bringing the shaft forwards allows us to assess how much penile length is actually present.' ),
		cil_buried_p( 'For circumcision, this is considerably more useful than simply looking at how much penis is visible at rest.' ),
	)
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'small-vs-buried',
	),
	array(
		$small_vs_buried_group,
	)
);

// -----------------------------------------------------------------------------
// 6. Buried penis across age groups: Babies, Older Children & Teens, Adults
// -----------------------------------------------------------------------------
$age_cards = array(
	array(
		'title' => 'Buried penis in babies and young children',
		'html'  => '<p>Buried penis is something we see very commonly in babies attending our circumcision clinic.</p>' .
			'<p>Babies naturally vary considerably in the amount of tissue around the pubic area. Some have a prominent pubic fat pad, which surrounds the base of the penis and makes much of the shaft appear hidden.</p>' .
			'<p>In many babies, gently pressing the surrounding tissue backwards brings substantially more of the penile shaft into view. The penis may therefore look very small initially despite having considerably more length hidden within the surrounding tissue.</p>' .
			'<p>This is particularly important during circumcision. We assess the penis in its exposed position rather than judging how much skin to remove according to the amount of penis visible while it is naturally retracted.</p>' .
			'<p><a href="' . esc_url( $babies ) . '">Learn more about baby circumcision →</a></p>',
	),
	array(
		'title' => 'Buried penis in older children and teenagers',
		'html'  => '<p>Buried penis does not only occur in babies. We also see it in older children and teenagers.</p>' .
			'<p>As children grow, the relationship between penile size, body size and the pubic area changes. In some boys the penis becomes increasingly prominent with growth, while in others a buried appearance remains or becomes more noticeable.</p>' .
			'<p>A relatively high or fatty pubic area can conceal part of an otherwise normal-sized penis. In other patients, the penis itself may be relatively small in comparison with the surrounding pubic area.</p>' .
			'<p>What matters for circumcision is the anatomy when the penis is fully brought forwards from the surrounding tissue. We therefore assess the true penile length and available skin before deciding how much foreskin should be removed.</p>' .
			'<p><a href="' . esc_url( $children ) . '">Learn more about child and teenage circumcision →</a></p>',
	),
	array(
		'title' => 'Buried penis in adults',
		'html'  => '<p>Buried penis can also occur in adult men. The principle is similar.</p>' .
			'<p>An adult may have a penis of normal size but a relatively prominent or fatty pubic area that surrounds the base of the penis. This can conceal a significant proportion of the shaft, particularly when the penis is flaccid.</p>' .
			'<p>For example, two men may have a similar actual penile length, but the man with a higher or more prominent suprapubic area may appear to have a considerably shorter penis because a greater proportion of the shaft is hidden.</p>' .
			'<p>In some adults, weight gain can make this more noticeable because additional fatty tissue develops around the pubic region. Other men may have a relatively small penis together with a prominent pubic area, making the buried appearance more pronounced.</p>' .
			'<p>This distinction is important because visible penile length is not necessarily the same as actual penile length.</p>' .
			'<p><a href="' . esc_url( $adults ) . '">Learn more about adult circumcision →</a></p>',
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'age-groups',
	),
	array(
		cil_buried_section_head(
			'Across all age groups',
			'Buried penis in babies, children and adults',
			'From newborn infants through to adult men, how buried penis presents and how circumcision is planned differs according to age and anatomical development.'
		),
		cil_buried_info_cards( 'g-3', $age_cards ),
	)
);

// -----------------------------------------------------------------------------
// 7. Weight & Growth Development
// -----------------------------------------------------------------------------
$weight_and_growth_cards = array(
	array(
		'title' => 'Can weight affect buried penis?',
		'html'  => '<p>Yes.</p>' .
			'<p>Where there is significant fatty tissue around the pubic area, part of the penile shaft can become hidden within this tissue.</p>' .
			'<p>An increase in weight can therefore make a previously mild buried penis more noticeable in some older children, teenagers and adults.</p>' .
			'<p>Similarly, reducing the amount of fat around the pubic area may make more of the penile shaft visible.</p>' .
			'<p>However, weight is not the only factor. A slim patient can also have a relatively high pubic area or penile anatomy that produces a buried appearance.</p>' .
			'<p>We therefore assess the individual anatomy rather than assuming that every buried penis is caused by being overweight.</p>',
	),
	array(
		'title' => 'Can a buried penis develop as a child grows?',
		'html'  => '<p>Yes. A baby may not have an obvious buried penis at the time of circumcision but develop a buried appearance later as he grows.</p>' .
			'<p>This can happen because the penis and the surrounding pubic area do not necessarily grow or change at the same rate.</p>' .
			'<p>For example, a newborn may have a clearly visible penis with relatively little tissue around the pubic area. As the child grows and gains weight, the pubic fat pad may become more prominent while penile growth during that stage of childhood is relatively limited. More of the penile shaft can then become hidden within the surrounding tissue.</p>' .
			'<p>The penis may therefore appear increasingly buried even though it was not noticeably buried when the circumcision was originally performed.</p>' .
			'<p>This is particularly important for parents to understand because the change may occur months or years after an otherwise satisfactory circumcision.</p>',
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'weight-and-growth',
	),
	array(
		cil_buried_section_head(
			'Weight & development',
			'How weight and physical growth affect penile visibility'
		),
		cil_buried_info_cards( 'g-2', $weight_and_growth_cards ),
	)
);

// -----------------------------------------------------------------------------
// 8. Circumcision Does Not Cause a Buried Penis & Beverley Clinic Expertise Callout
// -----------------------------------------------------------------------------
$clinic_expertise_html = '<div class="callout" style="border-left:4px solid var(--blue);background:var(--card);padding:24px;border-radius:12px;margin-top:28px">' .
	'<span class="caps eyebrow" style="color:var(--text-muted);font-size:12px;letter-spacing:1px;font-weight:600;display:block;margin-bottom:8px">Beverley Clinic Expertise</span>' .
	'<h3 class="display d-3" style="margin-top:0;margin-bottom:10px">Buried penis circumcision at Beverley Clinic</h3>' .
	'<p>We regularly circumcise patients with mild, moderate and severe buried penis, from babies through to adults. Before circumcision, we expose the true penile length and assess the amount of available skin so that the appropriate amount of foreskin can be removed. We also provide specific aftercare advice because a buried penis can retract into the surrounding pubic tissue during healing.</p>' .
	'<p style="margin-top:14px;margin-bottom:0"><strong>Related clinical conditions:</strong> ' .
	'<a href="' . esc_url( $phimosis ) . '">Phimosis (tight foreskin)</a> · ' .
	'<a href="' . esc_url( $bxo ) . '">BXO / lichen sclerosis</a> · ' .
	'<a href="' . esc_url( $recirc ) . '">Re-circumcision & revision</a></p>' .
	'</div>';

$clinic_expertise_block = array(
	'blockName'    => 'core/html',
	'attrs'        => array(),
	'innerBlocks'  => array(),
	'innerHTML'    => $clinic_expertise_html,
	'innerContent' => array( $clinic_expertise_html ),
);

$circumcision_cause_group = cil_buried_group(
	array(
		cil_buried_h( 'Circumcision does not cause a buried penis', 2 ),
		cil_buried_p( 'A buried penis is related to the relationship between the penis and the surrounding pubic tissue, rather than whether or not the foreskin is present.' ),
		cil_buried_p( 'If a child develops a prominent pubic fat pad that surrounds and conceals the penile shaft, the penis would have a buried appearance whether he had been circumcised or remained uncircumcised.' ),
		cil_buried_p( 'Circumcision can sometimes make the anatomy easier to recognise because the foreskin is no longer present.' ),
		cil_buried_p( 'In an uncircumcised boy with a buried penis, the foreskin may project beyond the underlying penile shaft. Parents may understandably look at the end of the foreskin and assume that the entire visible length represents the length of the penis itself.' ),
		cil_buried_p( 'However, foreskin extending beyond the glans should not be mistaken for penile shaft length.' ),
		cil_buried_p( 'When the pubic tissue at the base is pressed backwards and the penis is properly exposed, the relationship between the actual penile shaft and the surrounding pubic tissue becomes much clearer.' ),
		cil_buried_p( 'This is why a child who appears to have developed a buried penis after circumcision has not necessarily developed it because of the circumcision. The buried appearance can result from changes in growth, body proportions and the amount of tissue around the pubic area.' ),
		cil_buried_h( 'Can you circumcise a buried penis?', 2 ),
		cil_buried_p( 'Yes. Having a buried penis does not prevent circumcision, but it does mean the procedure must be planned around the individual anatomy rather than treating it like a standard procedure.' ),
		$clinic_expertise_block,
	)
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'circumcision-and-buried-penis',
	),
	array(
		$circumcision_cause_group,
	)
);

// -----------------------------------------------------------------------------
// 9. Why is buried penis particularly important for circumcision? & Mild/Severe
// -----------------------------------------------------------------------------
$clinical_assessment_group = cil_buried_group(
	array(
		cil_buried_h( 'Why is buried penis particularly important for circumcision?', 2 ),
		cil_buried_p( 'Circumcision permanently removes foreskin.' ),
		cil_buried_p( 'With a buried penis, the practitioner cannot simply look at the amount of penis visible at rest and use that to decide how much skin to remove.' ),
		cil_buried_p( 'The penis needs to be fully brought forwards from the surrounding pubic tissue.' ),
		cil_buried_p( 'We then assess:' ),
		cil_buried_list(
			array(
				'The true penile length',
				'How much of the shaft is normally buried',
				'The height and prominence of the pubic area',
				'The amount of surrounding fatty tissue',
				'How far the penis can be brought forwards',
				'The amount and mobility of penile skin',
				'The amount of foreskin that can safely be removed',
			)
		),
		cil_buried_p( 'This becomes increasingly important when the penis is significantly buried.' ),
		cil_buried_p( 'Our aim is to achieve an appropriate circumcision while retaining sufficient skin for the penis when it is fully extended.' ),
		cil_buried_p( 'Removing too much skin from a buried penis can create problems when the penis is fully extended, potentially causing painful tension or a skin deficiency. Conversely, removing too little skin may leave redundant foreskin and produce an unsatisfactory cosmetic outcome.' ),
		cil_buried_h( 'Circumcising mild and severe buried penis', 2 ),
		cil_buried_p( 'At Beverley Clinic, we circumcise patients across the spectrum from mild to severe buried penis.' ),
		cil_buried_p( 'We do not regard the presence of a buried penis alone as a reason that circumcision cannot be performed.' ),
		cil_buried_p( 'Instead, we recognise that the circumcision requires experience and careful judgement.' ),
		cil_buried_p( 'The more buried the penis, the more important it becomes to understand what proportion of the penis is hidden and how much skin will be required when the penis is fully extended.' ),
		cil_buried_p( 'This is why identifying buried penis before beginning the circumcision is so important.' ),
	)
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'clinical-assessment',
	),
	array(
		$clinical_assessment_group,
	)
);

// -----------------------------------------------------------------------------
// 10. Post-Operative Retraction, Aftercare & Late Buried Appearance
// -----------------------------------------------------------------------------
$aftercare_group = cil_buried_group(
	array(
		cil_buried_h( 'What happens after circumcision when the penis is buried?', 2 ),
		cil_buried_p( 'A buried penis naturally tends to retract back into the surrounding pubic tissue.' ),
		cil_buried_p( 'This can happen immediately after circumcision or become more noticeable later as the child grows and body proportions change.' ),
		cil_buried_p( 'As the penis retracts, the shaft skin can move forwards towards the glans.' ),
		cil_buried_p( 'Parents may sometimes think that the foreskin is growing back or that the circumcision is being reversed. In reality, what they are seeing may simply be shaft skin moving forwards because the penis itself is sitting deeper within the surrounding pubic tissue.' ),
		cil_buried_p( 'The penis has not necessarily become shorter and the foreskin has not grown back.' ),
		cil_buried_p( 'This is why aftercare is particularly important for patients with a buried penis.' ),
		cil_buried_h( 'Buried penis circumcision aftercare', 2 ),
		cil_buried_p( 'When the penis is buried within the surrounding pubic tissue, it naturally tends to retract inwards. As it does so, the shaft skin can move forwards towards the glans.' ),
		cil_buried_p( 'Following circumcision, it is important that the glans and the inner skin immediately behind it remain appropriately exposed during healing, according to the aftercare instructions provided by the clinic.' ),
		cil_buried_p( 'For babies and children, parents may therefore need to regularly expose the penis.' ),
		cil_buried_p( 'This is usually done by pressing down on the pubic area at the base of the penis to bring the penis forwards, while gently drawing the shaft skin backwards so that the glans and inner skin behind it can be seen.' ),
		cil_buried_p( 'For older children and adults with a buried penis, the same principle may form part of their own aftercare.' ),
		cil_buried_p( 'The purpose is not to stretch or pull forcefully. It is to prevent the penis from remaining continuously buried with the shaft skin sitting forwards against the glans during the healing period.' ),
		cil_buried_p( 'If the penis repeatedly retracts and the skin remains in contact with the glans, the surfaces can begin to stick together and penile adhesions may develop.' ),
		cil_buried_p( 'The amount and duration of this aftercare varies according to the patient\'s age, anatomy, degree of buried penis and stage of healing. We explain the appropriate technique following circumcision and provide follow-up support if parents or patients are unsure about the appearance.' ),
		cil_buried_p( 'For a ring circumcision, the timing of this care differs from a forceps-guided circumcision, so follow the instructions given specifically for the method used. See our <a href="' . esc_url( $aftercare ) . '">circumcision aftercare page</a>.' ),
		cil_buried_h( 'What if the penis becomes buried months or years after circumcision?', 2 ),
		cil_buried_p( 'The same principle remains important even if the buried appearance develops well after the original circumcision.' ),
		cil_buried_p( 'If increasing pubic tissue causes the penis to sit further inwards, the shaft skin may move forwards and begin covering part of the glans.' ),
		cil_buried_p( 'Parents should not assume that the circumcision has somehow been reversed or that the foreskin has grown back.' ),
		cil_buried_p( 'What they are seeing may simply be shaft skin moving forwards because the penis itself is sitting deeper within the surrounding pubic tissue.' ),
		cil_buried_p( 'Where appropriate, regularly bringing the penis forwards by pressing down around its base and gently drawing the shaft skin backwards can help keep the glans exposed and reduce the tendency for the skin to stick.' ),
		cil_buried_p( 'If the skin has already become firmly attached, there is significant scarring, or parents are unsure whether the appearance is normal, they should contact us for assessment rather than forcibly trying to separate the skin themselves.' ),
	)
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'aftercare-and-healing',
	),
	array(
		$aftercare_group,
	)
);

// -----------------------------------------------------------------------------
// 11. From Our Clinical Experience & 3 Core Principles
// -----------------------------------------------------------------------------
$principles_html = '<div class="callout" style="border-left:4px solid var(--blue);background:var(--card);padding:24px;border-radius:12px;margin:24px 0">' .
	'<span class="caps eyebrow" style="color:var(--text-muted);font-size:12px;letter-spacing:1px;font-weight:600;display:block;margin-bottom:8px">Clinical Principles</span>' .
	'<h3 class="display d-3" style="margin-top:0;margin-bottom:12px">Our 3 core clinical principles</h3>' .
	'<p style="font-size:18px;font-weight:700;color:var(--blue-deep);margin-bottom:0">' .
	'Recognise the anatomy → expose the true penile length → remove the appropriate amount of skin.' .
	'</p>' .
	'</div>';

$principles_block = array(
	'blockName'    => 'core/html',
	'attrs'        => array(),
	'innerBlocks'  => array(),
	'innerHTML'    => $principles_html,
	'innerContent' => array( $principles_html ),
);

$clinical_experience_group = cil_buried_group(
	array(
		cil_buried_h( 'From our clinical experience', 2 ),
		cil_buried_p( 'Buried penis is something we see very commonly, particularly in babies, but also in older children, teenagers and adults.' ),
		cil_buried_p( 'There are two features we particularly consider.' ),
		cil_buried_p( 'The first is the size and length of the penis itself.' ),
		cil_buried_p( 'The second is the relationship between the penis and the surrounding pubic area.' ),
		cil_buried_p( 'A patient can have a normal-sized penis but a relatively high or fatty pubic area that surrounds the base of the penis and hides a significant proportion of the shaft. Alternatively, the penis itself may be relatively small. Some patients have a combination of both.' ),
		cil_buried_p( 'When assessing a patient for circumcision, we therefore do not judge the penis simply by how much is visible at rest.' ),
		cil_buried_p( 'We compress the surrounding tissue and bring the penis forwards to understand its true anatomy.' ),
		cil_buried_p( 'This allows us to judge exactly how much skin should be removed during circumcision.' ),
		cil_buried_p( 'Afterwards, we also take into account the fact that the penis may naturally retract back into the surrounding tissue. This is particularly important when explaining aftercare and preventing the remaining skin from sticking to the glans during healing.' ),
		cil_buried_p( 'For us, the three important principles when circumcising a buried penis are:' ),
		cil_buried_p( '<strong>Recognise the anatomy → expose the true penile length → remove the appropriate amount of skin.</strong>' ),
		$principles_block,
	)
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'clinical-experience',
	),
	array(
		$clinical_experience_group,
	)
);

// -----------------------------------------------------------------------------
// 12. Why Choose Beverley Clinic for Buried Penis Circumcision? (5 Info-Cards)
// -----------------------------------------------------------------------------
$why_choose_cards = array(
	array(
		'title' => 'Specialist buried penis experience',
		'body'  => 'We regularly assess and circumcise patients with mild, moderate and severe buried penis across all age groups, from newborn babies through to adult men.',
	),
	array(
		'title' => 'Accurate assessment of true shaft length',
		'body'  => 'We do not judge the penis by how much is visible at rest. Compressing the surrounding tissue reveals the true anatomical shaft length before surgery begins.',
	),
	array(
		'title' => 'Tailored skin excision',
		'body'  => 'Skin removal is carefully calculated to retain sufficient skin for full penile extension while avoiding excess redundant tissue.',
	),
	array(
		'title' => 'Adhesion & skin bridge prevention',
		'body'  => 'We provide detailed, hands-on guidance on the backwards-pressure technique to keep healing surfaces separated and prevent penile adhesions.',
	),
	array(
		'title' => 'Comprehensive aftercare & follow-up',
		'body'  => 'Every patient receives a next-day follow-up phone call, direct WhatsApp support and free clinical review appointments during the healing process.',
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'why-choose-us',
	),
	array(
		cil_buried_section_head(
			'Clinical Expertise',
			'Why choose Beverley Clinic for buried penis circumcision?',
			'Dedicated circumcision expertise in North-West London with a patient-centred medical approach.'
		),
		cil_buried_info_cards( 'g-3', $why_choose_cards ),
	)
);

// -----------------------------------------------------------------------------
// 13. Frequently Asked Questions (8 Client FAQs)
// -----------------------------------------------------------------------------
$faq_items = array(
	array(
		'q' => 'Can a baby with a buried penis be circumcised?',
		'a' => 'Yes. At Beverley Clinic, we regularly circumcise babies with mild, moderate and severe buried penis. The penis is properly assessed and brought forwards before circumcision so that true penile length can be established and the appropriate amount of foreskin can be removed.',
	),
	array(
		'q' => 'Does circumcision cause buried penis?',
		'a' => 'No. Circumcision permanently removes foreskin; it does not create extra pubic fat or push the penis further into the body. However, before circumcision, the foreskin may project beyond the underlying penile shaft, which can be mistaken for shaft length. Once the foreskin is removed, the underlying relationship between the penile shaft and the surrounding pubic tissue becomes clearer.',
	),
	array(
		'q' => 'Why does my baby\'s penis look buried after circumcision?',
		'a' => 'A buried penis naturally tends to retract into the surrounding pubic tissue. As it does so, the remaining shaft skin can slide forwards towards the glans. Parents may mistake this for foreskin regrowth or foreskin left behind, but it is simply the shaft skin moving forwards because the penis is sitting deeper within the surrounding pubic tissue.',
	),
	array(
		'q' => 'Can buried penis develop later?',
		'a' => 'Yes. A baby may not have an obvious buried penis at the time of circumcision but can develop a buried appearance later as he grows. The penile shaft and the surrounding pubic tissue do not always grow at the same rate. As the child gains weight and develops a more prominent pubic fat pad, more of the shaft can become hidden within the tissue.',
	),
	array(
		'q' => 'Does weight cause buried penis?',
		'a' => 'Weight is a major contributing factor, but it is not the only cause. An increase in fatty tissue around the pubic area can conceal more of the penile shaft. However, slim children and adults can also have a buried penis due to natural anatomy, such as a naturally high pubic area or natural retraction.',
	),
	array(
		'q' => 'Can adults have buried penis?',
		'a' => 'Yes. Buried penis can affect adult men as well as babies and children. An adult may have a normal-sized penis with a prominent or fatty pubic area that conceals part of the shaft, particularly when flaccid. Weight gain can make this more noticeable, but it can also occur in slim men.',
	),
	array(
		'q' => 'Can a buried penis be normal size?',
		'a' => 'Yes. A buried penis is not the same as a small penis (micropenis). In many patients, the penile shaft is completely normal in length, but part of it is hidden within the surrounding pubic fat pad or tissue. Compressing the tissue backwards reveals the true shaft length.',
	),
	array(
		'q' => 'What aftercare is needed after circumcision with a buried penis?',
		'a' => 'Aftercare is particularly important because the penis naturally tends to retract inwards. Parents or patients are taught to gently press backwards on the pubic tissue around the base to bring the penis forwards and expose the glans and inner skin. This prevents the healing skin from sitting against the glans and sticking together, reducing the risk of penile adhesions and skin bridges.',
	),
);

$blocks[] = cil_dyn_block(
	'cil/faq',
	array(
		'heading' => 'Frequently asked questions about buried penis',
		'items'   => $faq_items,
	)
);

// -----------------------------------------------------------------------------
// 14. Medical Authorship & Medical References
// -----------------------------------------------------------------------------
$authorship_html = '<div class="callout" style="border-left:4px solid var(--blue);background:var(--card);padding:24px;border-radius:12px;margin-bottom:20px">' .
	'<span class="caps eyebrow" style="color:var(--text-muted);font-size:12px;letter-spacing:1px;font-weight:600;display:block;margin-bottom:8px">Medical Review & Authorship</span>' .
	'<h3 class="display d-3" style="margin-top:0;margin-bottom:8px">Medically reviewed by Dr Haidar Al-Ali</h3>' .
	'<p style="margin-bottom:4px"><strong>Dr Haidar Al-Ali, BDS, MFDS RCPS (Glasg)</strong></p>' .
	'<p style="color:var(--text-muted);font-size:15px;margin-bottom:12px">Lead Circumcision Practitioner, Beverley Clinic · Last medically reviewed: September 2026</p>' .
	'<p style="margin-bottom:0"><a href="' . esc_url( $team ) . '" style="color:var(--blue-deep);font-weight:600">View practitioner profile &amp; qualifications →</a></p>' .
	'</div>';

$references_html = '<div class="body-text">' .
	'<h3 class="display d-3" style="margin-bottom:12px">Medical references</h3>' .
	'<p>Our clinical guidance and information on buried and concealed penis are grounded in established paediatric surgical and urological standards, including:</p>' .
	'<ul class="cil-checklist wp-block-list">' .
	'<li><strong>NHS (National Health Service):</strong> Clinical guidance on childhood penile development, prepuce anatomy and post-circumcision care.</li>' .
	'<li><strong>BAUS (British Association of Urological Surgeons):</strong> Clinical information on concealed / buried penis assessment and male foreskin surgery.</li>' .
	'<li><strong>EAU (European Association of Urology):</strong> Paediatric urology guidelines on the management of the prepuce, concealed penis and foreskin conditions.</li>' .
	'</ul>' .
	'</div>';

$blocks[] = cil_section_block(
	array(
		'size'   => 'section-sm',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'authorship-and-references',
	),
	array(
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/html',
					'attrs'        => array(),
					'innerBlocks'  => array(),
					'innerHTML'    => $authorship_html,
					'innerContent' => array( $authorship_html ),
				),
				array(
					'blockName'    => 'core/html',
					'attrs'        => array(),
					'innerBlocks'  => array(),
					'innerHTML'    => $references_html,
					'innerContent' => array( $references_html ),
				),
			),
			false
		),
	)
);

// -----------------------------------------------------------------------------
// 15. Circumcision for Buried Penis in London (Consultation Split Section)
// -----------------------------------------------------------------------------
$consultation_left = array(
	cil_buried_h( 'Circumcision for buried penis in London', 2 ),
	cil_buried_p( 'If you would like to arrange an assessment or circumcision for yourself or your son, contact Beverley Clinic.' ),
	cil_buried_p( 'Tell us the patient\'s age and whether you have noticed a buried or retracted appearance.' ),
	cil_buried_p( '<strong>Beverley Clinic</strong><br>78 Beverley Drive<br>Edgware<br>North-West London<br>HA8 5NE' ),
	array(
		'blockName'    => 'core/buttons',
		'attrs'        => array( 'className' => 'wp-block-buttons cil-consultation-buttons-grid', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '24px' ) ) ) ),
		'innerBlocks'  => array(
			array(
				'blockName'    => 'core/button',
				'attrs'        => array( 'className' => 'wp-block-button is-style-outline' ),
				'innerBlocks'  => array(),
				'innerHTML'    => '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $prices ) . '">View circumcision prices →</a></div>',
				'innerContent' => array( '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $prices ) . '">View circumcision prices →</a></div>' ),
			),
			array(
				'blockName'    => 'core/button',
				'attrs'        => array( 'className' => 'wp-block-button is-style-outline' ),
				'innerBlocks'  => array(),
				'innerHTML'    => '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $contact ) . '">Contact Beverley Clinic →</a></div>',
				'innerContent' => array( '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $contact ) . '">Contact Beverley Clinic →</a></div>' ),
			),
			array(
				'blockName'    => 'core/button',
				'attrs'        => array( 'className' => 'wp-block-button' ),
				'innerBlocks'  => array(),
				'innerHTML'    => '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $book ) . '">Book an appointment →</a></div>',
				'innerContent' => array( '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $book ) . '">Book an appointment →</a></div>' ),
			),
		),
		'innerHTML'    => '',
		'innerContent' => array(
			'<div class="wp-block-buttons cil-consultation-buttons-grid" style="margin-top:24px">',
			null,
			null,
			null,
			'</div>',
		),
	),
);

$consultation_right = cil_dyn_block(
	'cil/callback-card',
	array(
		'eyebrow' => 'Request a call back',
		'title'   => 'Ask us first',
		'formId'  => 'buried',
		'subject' => 'Buried penis enquiry',
		'urgent'  => false,
	)
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'book-consultation',
	),
	array(
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $consultation_left,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $consultation_left ), null ),
						array( '</div>' )
					),
				),
				$consultation_right,
			),
			false
		),
	)
);

// -----------------------------------------------------------------------------
// 16. Closing CTA Band
// -----------------------------------------------------------------------------
$blocks[] = cil_dyn_block(
	'cil/cta-band',
	array(
		'eyebrow'    => 'Talk to us',
		'title'      => 'Ask us anything before you decide',
		'text'       => 'Most people call with a question rather than to book. That is what the phone is for, and nothing is booked until you say so.',
		'ctaLabel'   => 'Book an appointment',
		'ctaUrl'     => $book,
		'phoneLabel' => 'Call 020 8951 3794',
		'phoneUrl'   => 'tel:+442089513794',
	)
);

// -----------------------------------------------------------------------------
// Save to Post ID 14 & Export Fixture
// -----------------------------------------------------------------------------
$content = cil_serialize_blocks( $blocks );

$page = get_page_by_path( 'buried-penis' );
if ( ! $page ) {
	$page = get_post( 14 );
}
if ( ! $page ) {
	fwrite( STDERR, "Page not found for buried-penis\n" );
	exit( 1 );
}

kses_remove_filters();
remove_filter( 'content_save_pre', 'balanceTags', 50 );
remove_filter( 'content_save_pre', 'convert_invalid_entities' );

$result = wp_update_post(
	wp_slash(
		array(
			'ID'           => (int) $page->ID,
			'post_title'   => 'Buried Penis and Circumcision',
			'post_content' => $content,
		)
	),
	true
);
add_filter( 'content_save_pre', 'convert_invalid_entities' );
add_filter( 'content_save_pre', 'balanceTags', 50 );
kses_init_filters();

if ( is_wp_error( $result ) ) {
	fwrite( STDERR, $result->get_error_message() . "\n" );
	exit( 1 );
}

update_post_meta( $page->ID, '_cil_document_title', 'Buried Penis Circumcision London | Beverley Clinic' );
update_post_meta( $page->ID, '_cil_meta_description', 'Buried penis is common in babies but can also affect children and adults. Learn how buried penis affects circumcision, skin removal and aftercare.' );
clean_post_cache( $page->ID );
$page = get_post( $page->ID );

// Export fixture
$manifest       = cil_content_load_manifest();
$replace_to     = ( ! is_wp_error( $manifest ) && ! empty( $manifest['replace_to'] ) ) ? $manifest['replace_to'] : 'https://circumcision-london-wp.vercel.app';
$replace_from   = untrailingslashit( home_url() );
$content_remote = cil_content_rewrite_site_urls( $page->post_content, $replace_from, $replace_to );
$check          = cil_content_assert_no_local_host( $content_remote );
if ( is_wp_error( $check ) ) {
	fwrite( STDERR, $check->get_error_message() . "\n" );
	exit( 1 );
}
$fixture = array(
	'schema'              => 1,
	'slug'                => 'buried-penis',
	'path'                => '/buried-penis/',
	'local_id'            => (int) $page->ID,
	'source_modified_gmt' => $page->post_modified_gmt,
	'source_sha256'       => hash( 'sha256', $content_remote ),
	'replace_from'        => untrailingslashit( $replace_from ),
	'replace_to'          => untrailingslashit( $replace_to ),
	'content'             => $content_remote,
);
$path = cil_content_write_fixture( 'buried-penis', $fixture );
if ( is_wp_error( $path ) ) {
	fwrite( STDERR, $path->get_error_message() . "\n" );
	exit( 1 );
}

echo "UPDATED BURIED PENIS SUCCESSFUL\n";
echo "Post ID: " . $page->ID . "\n";
echo "Content Bytes: " . strlen( $page->post_content ) . "\n";
echo "Fixture Path: " . $path . "\n";
echo "Fixture SHA256: " . $fixture['source_sha256'] . "\n";
