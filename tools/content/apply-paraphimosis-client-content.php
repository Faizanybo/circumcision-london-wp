<?php
/**
 * Apply full client Paraphimosis content to /conditions/paraphimosis/ + export fixture.
 *
 * Source: Paraphimosis.docx
 * Architecture:
 * - Content width matches BXO page ('wrap' => 'wrap' across all sections).
 * - Rich Gutenberg block architecture mirroring BXO and Phimosis:
 *   - cil/page-head
 *   - cil/split layouts
 *   - cil/spec-panel
 *   - cil/info-cards with balanced row heights, scroll-reveal and hover effects
 *   - cil/faq
 *   - cil/callback-card
 *   - cil/cta-band
 *   - .cil-consultation-buttons-grid
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
function cil_paraphimosis_p( $html ) {
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
function cil_paraphimosis_h( $text, $level = 2 ) {
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
function cil_paraphimosis_list( $items ) {
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
function cil_paraphimosis_info_cards( $columns, $items, $level = 3 ) {
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
function cil_paraphimosis_section_head( $eyebrow, $heading, $lede = '', $display = 'd-2' ) {
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
 * Target link paths.
 */
$phimosis  = home_url( '/conditions/phimosis/' );
$bxo       = home_url( '/conditions/bxo/' );
$balanitis = home_url( '/conditions/balanitis/' );
$children  = home_url( '/children/' );
$adults    = home_url( '/adults/' );
$aftercare = home_url( '/aftercare/' );
$prices    = home_url( '/prices/' );
$contact   = home_url( '/contact/' );
$book      = home_url( '/book/' );

$blocks = array();

// -----------------------------------------------------------------------------
// 1. Hero Page Head
// -----------------------------------------------------------------------------
$blocks[] = cil_dyn_block(
	'cil/page-head',
	array(
		'eyebrow'    => 'Condition · Foreskin emergency & circumcision',
		'title'      => 'Paraphimosis and Circumcision',
		'lede'       => 'Paraphimosis occurs when the foreskin has been pulled back behind the head of the penis (glans) and becomes trapped, so that it cannot be brought forward into its normal position.',
		'reviewedBy' => cil_reviewed_by_haidar(),
		'crumbs'     => array(
			array(
				'label' => 'Reasons',
				'href'  => $phimosis,
			),
			array(
				'label' => 'Paraphimosis',
				'href'  => '',
			),
		),
	)
);

// -----------------------------------------------------------------------------
// 2. Urgent Emergency Warning & Introductory Split Section (Matching BXO Section 1)
// -----------------------------------------------------------------------------
$urgent_callout_html = '<div class="callout urgent">' .
	'<h2 class="display d-3">Paraphimosis is a medical emergency</h2>' .
	'<p style="margin-top:10px">If your foreskin is currently trapped behind the glans and you cannot return it forwards, do not wait for a routine appointment, callback or message reply from Beverley Clinic. <strong>Seek urgent medical care immediately (NHS 111 / A&amp;E).</strong> As BAUS (British Association of Urological Surgeons) notes, failure to reduce a trapped foreskin promptly can progress from venous congestion and swelling to impaired arterial blood flow and tissue damage.</p>' .
	'<div class="btn-row" style="margin-top:16px">' .
	'<a class="btn" href="' . esc_url( $contact ) . '">Already had the emergency treated? Contact us about circumcision →</a>' .
	'</div>' .
	'</div>';

$urgent_callout_block = array(
	'blockName'    => 'core/html',
	'attrs'        => array(),
	'innerBlocks'  => array(),
	'innerHTML'    => $urgent_callout_html,
	'innerContent' => array( $urgent_callout_html ),
);

$intro_paragraphs_group = array(
	'blockName'    => 'core/group',
	'attrs'        => array( 'className' => 'body-text' ),
	'innerBlocks'  => array(
		cil_paraphimosis_p( 'The trapped foreskin can act as a tight band around the penis, causing increasing swelling and pain.' ),
		cil_paraphimosis_p( 'Once the paraphimosis has been successfully treated and the swelling has settled, circumcision can be performed to address the tight foreskin and help prevent the same problem happening again.' ),
		cil_paraphimosis_p( 'At Beverley Clinic in North-West London, we provide circumcision following an episode of paraphimosis for suitable children, teenagers and adults.' ),
	),
	'innerHTML'    => '',
	'innerContent' => array(
		'<div class="wp-block-group body-text">',
		null,
		null,
		null,
		'</div>',
	),
);

$spec_note = 'Circumcision following paraphimosis is assessed individually. See our <a href="' . esc_url( $prices ) . '" style="color:var(--blue-deep)">price list</a> for consultation and surgical fees.';

$spec_panel_block = cil_dyn_block(
	'cil/spec-panel',
	array(
		'eyebrow' => 'Paraphimosis at a glance',
		'rows'    => array(
			array(
				'k' => 'What is paraphimosis?',
				'v' => 'The foreskin is pulled back behind the glans and becomes trapped, so that it cannot return to its normal position.',
			),
			array(
				'k' => 'What does it look and feel like?',
				'v' => 'The head of the penis and retracted foreskin may become swollen and painful, with a tight band of foreskin sitting behind the glans.',
			),
			array(
				'k' => 'Is paraphimosis an emergency?',
				'v' => 'Yes. It requires urgent medical treatment.',
			),
			array(
				'k' => 'Should I book a routine appointment at Beverley Clinic if I have it now?',
				'v' => 'No. Acute paraphimosis requires urgent medical treatment rather than a routine clinic appointment.',
			),
			array(
				'k' => 'What does Beverley Clinic provide?',
				'v' => 'After the emergency has been treated and the swelling has settled, we can provide circumcision to treat the underlying foreskin problem and help prevent recurrence.',
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
		$urgent_callout_block,
		cil_split_block(
			array(
				$intro_paragraphs_group,
				$spec_panel_block,
			)
		),
	)
);

// -----------------------------------------------------------------------------
// 3. Understanding Paraphimosis & Symptoms (Split layout, matching BXO Section 3)
// -----------------------------------------------------------------------------
$mechanism_col = array(
	cil_paraphimosis_h( 'What is paraphimosis?', 2 ),
	cil_paraphimosis_p( 'Normally, when a retractable foreskin is pulled backwards over the glans, it should be possible to bring it forwards again so that it returns to its normal position.' ),
	cil_paraphimosis_p( 'With paraphimosis, the foreskin becomes stuck in the retracted position behind the glans.' ),
	cil_paraphimosis_p( 'The trapped foreskin forms a constricting ring.' ),
	cil_paraphimosis_p( 'As swelling develops, it can become progressively more difficult to return the foreskin to its normal position.' ),
	cil_paraphimosis_p( 'This is why paraphimosis should not be ignored or left to see whether it improves by itself.' ),
);

$symptoms_items = array(
	'Foreskin trapped behind the glans',
	'Inability to pull the foreskin forwards again',
	'Swelling of the glans',
	'Swelling of the retracted foreskin',
	'Pain or increasing discomfort',
	'A tight band of foreskin behind the glans',
	'Red, dark red, purple or blue discolouration in more severe cases',
);

$symptoms_col = array(
	cil_paraphimosis_h( 'What are the symptoms of paraphimosis?', 2 ),
	cil_paraphimosis_p( 'Typical features include:' ),
	cil_paraphimosis_list( $symptoms_items ),
	cil_paraphimosis_p( '<strong>The most important feature is a retracted foreskin that cannot be returned to its normal position over the glans.</strong>' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'mechanism-and-symptoms',
	),
	array(
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $mechanism_col,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $mechanism_col ), null ),
						array( '</div>' )
					),
				),
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $symptoms_col,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $symptoms_col ), null ),
						array( '</div>' )
					),
				),
			)
		),
	)
);

// -----------------------------------------------------------------------------
// 4. Emergency Guidance & What To Do Now (Split layout, matching BXO Section 4)
// -----------------------------------------------------------------------------
$emergency_why_col = array(
	cil_paraphimosis_h( 'Why is paraphimosis an emergency?', 2 ),
	cil_paraphimosis_p( 'A trapped foreskin can act like a constricting band around the penis.' ),
	cil_paraphimosis_p( 'As the tissues swell, the constriction can become tighter.' ),
	cil_paraphimosis_p( 'This can interfere with normal blood circulation to the tissues beyond the constricting foreskin.' ),
	cil_paraphimosis_p( 'If severe paraphimosis is left untreated, there is a risk of damage to the tissues from impaired blood supply.' ),
	cil_paraphimosis_p( 'Prompt treatment is therefore essential.' ),
);

$emergency_now_items = array(
	'<strong>Seek urgent medical treatment.</strong>',
	'Do not wait for a routine appointment at Beverley Clinic and do not delay treatment while waiting for us to respond to an email, WhatsApp message or callback request.',
	'If you are in the UK, seek urgent NHS medical assistance. If the problem is severe or rapidly worsening, attend an appropriate emergency service (A&amp;E).',
	'Once the emergency has been treated and swelling has subsided, you can contact us to discuss circumcision.',
);

$emergency_now_col = array(
	cil_paraphimosis_h( 'What should I do if I have paraphimosis now?', 2 ),
	cil_paraphimosis_p( 'If your foreskin is currently trapped behind the glans and you cannot return it to its normal position:' ),
	cil_paraphimosis_list( $emergency_now_items ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'emergency-guidance',
	),
	array(
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $emergency_why_col,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $emergency_why_col ), null ),
						array( '</div>' )
					),
				),
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $emergency_now_col,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $emergency_now_col ), null ),
						array( '</div>' )
					),
				),
			)
		),
	)
);

// -----------------------------------------------------------------------------
// 5. Causes and Triggers (Card Grid Layout matching BXO Section 6)
// -----------------------------------------------------------------------------
$causes_cards = array(
	array(
		'eyebrow' => 'Sexual activity',
		'title'   => 'Can paraphimosis happen during sex?',
		'html'    => '<p>Yes. A relatively tight foreskin may retract during sexual activity or masturbation and then become trapped behind the glans.</p><p>If you have previously noticed that your foreskin becomes tight, painful or difficult to bring forwards after an erection, this should not be ignored.</p><p>If it becomes completely trapped and cannot be returned forwards, seek urgent medical treatment.</p><p><a href="' . esc_url( $adults ) . '">Adult circumcision →</a></p>',
	),
	array(
		'eyebrow' => 'Hygiene & cleaning',
		'title'   => 'During washing or medical examination',
		'html'    => '<p>Paraphimosis frequently occurs when the foreskin is pulled back for washing, personal cleaning, or during a physical examination.</p><p>If the foreskin is left retracted rather than immediately returned forward over the glans, swelling can quickly trap it in place.</p><p>It can also happen following catheterisation if the foreskin is not replaced immediately.</p>',
	),
	array(
		'eyebrow' => 'Tight foreskin',
		'title'   => 'Underlying phimosis or tightness',
		'html'    => '<p>Paraphimosis usually occurs when a relatively tight foreskin is pulled backwards over the glans and then cannot be returned forwards.</p><p>The tight foreskin can pass backwards over the widest part of the glans during retraction, but then struggles to move forwards again once swelling begins.</p><p><a href="' . esc_url( $phimosis ) . '">Phimosis and tight foreskin →</a></p>',
	),
);

$causes_checklist_items = array(
	'During washing or cleaning',
	'During sexual activity',
	'During masturbation',
	'Following examination of the penis',
	'Following a medical procedure where the foreskin has been retracted',
	'In someone who already has a tight foreskin or phimosis',
);

$causes_footer_blocks = array(
	cil_paraphimosis_p( 'Typical situations where paraphimosis can occur include:' ),
	cil_paraphimosis_list( $causes_checklist_items ),
	cil_paraphimosis_p( 'The swelling that develops can then make the foreskin increasingly difficult to return to its normal position. If you have recurring foreskin tightness without an acute paraphimosis, read:' ),
	array(
		'blockName'    => 'core/buttons',
		'attrs'        => array( 'className' => 'wp-block-buttons', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '16px' ) ) ) ),
		'innerBlocks'  => array(
			array(
				'blockName'    => 'core/button',
				'attrs'        => array( 'className' => 'wp-block-button is-style-outline' ),
				'innerBlocks'  => array(),
				'innerHTML'    => '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $phimosis ) . '">Phimosis and tight foreskin →</a></div>',
				'innerContent' => array( '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $phimosis ) . '">Phimosis and tight foreskin →</a></div>' ),
			),
			array(
				'blockName'    => 'core/button',
				'attrs'        => array( 'className' => 'wp-block-button is-style-outline' ),
				'innerBlocks'  => array(),
				'innerHTML'    => '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $adults ) . '">Adult circumcision →</a></div>',
				'innerContent' => array( '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $adults ) . '">Adult circumcision →</a></div>' ),
			),
		),
		'innerHTML'    => '',
		'innerContent' => array(
			'<div class="wp-block-buttons" style="margin-top:16px">',
			null,
			null,
			'</div>',
		),
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'causes',
	),
	array(
		cil_paraphimosis_section_head(
			'Clinical Causes',
			'What causes paraphimosis?',
			'Paraphimosis usually occurs when a relatively tight foreskin is pulled backwards over the glans and then cannot be returned forwards.'
		),
		cil_paraphimosis_info_cards( 'g-3', $causes_cards ),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'body-text', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '32px' ) ) ) ),
			'innerBlocks'  => $causes_footer_blocks,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group body-text" style="margin-top:32px">' ),
				array_fill( 0, count( $causes_footer_blocks ), null ),
				array( '</div>' )
			),
		),
	)
);

// -----------------------------------------------------------------------------
// 6. Acute Treatment & When Circumcision is Considered (Split Layout)
// -----------------------------------------------------------------------------
$treatment_col = array(
	cil_paraphimosis_h( 'How is acute paraphimosis treated?', 2 ),
	cil_paraphimosis_p( 'The immediate priority is to release the trapped foreskin and restore it to its normal position.' ),
	cil_paraphimosis_p( 'This requires urgent medical assessment.' ),
	cil_paraphimosis_p( 'In many cases a healthcare professional can reduce the swelling and return the foreskin forwards over the glans.' ),
	cil_paraphimosis_p( 'If this cannot be achieved, an urgent surgical procedure may sometimes be required.' ),
	cil_paraphimosis_p( '<strong>Beverley Clinic does not provide emergency reduction of acute paraphimosis.</strong>' ),
	cil_paraphimosis_p( 'Our role is different: after the emergency has been successfully treated and the swelling has settled, we can assess the patient for circumcision to prevent the same tight foreskin causing another episode.' ),
);

$when_circ_items = array(
	'That you have recently had paraphimosis',
	'When the episode occurred',
	'How it was treated',
	'Whether the foreskin is still swollen or inflamed',
	'Whether you have had paraphimosis before',
	'Whether you have an underlying tight foreskin',
	'Whether you have any other foreskin condition',
);

$when_circ_col = array(
	cil_paraphimosis_h( 'When can circumcision be performed after paraphimosis?', 2 ),
	cil_paraphimosis_p( 'Circumcision is generally planned after the acute paraphimosis has been successfully treated and the significant swelling and inflammation have settled.' ),
	cil_paraphimosis_p( 'The exact timing depends on the individual patient and the condition of the penis and foreskin.' ),
	cil_paraphimosis_p( 'When you contact us, tell us:' ),
	cil_paraphimosis_list( $when_circ_items ),
	cil_paraphimosis_p( 'We can then advise you about assessment and circumcision.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'treatment',
	),
	array(
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $treatment_col,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $treatment_col ), null ),
						array( '</div>' )
					),
				),
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $when_circ_col,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $when_circ_col ), null ),
						array( '</div>' )
					),
				),
			)
		),
	)
);

// -----------------------------------------------------------------------------
// 7. Paraphimosis, Phimosis & Related Conditions (Info-Cards Grid matching BXO Section 9)
// -----------------------------------------------------------------------------
$condition_comparison_cards = array(
	array(
		'eyebrow' => 'Differential diagnosis',
		'title'   => 'Paraphimosis or phimosis – what is the difference?',
		'html'    => '<p>The names are similar but they describe two different problems.</p><p><strong>Phimosis</strong> means the foreskin is too tight to retract normally over the glans.</p><p><strong>Paraphimosis</strong> means the foreskin has already been retracted but is now trapped behind the glans and cannot be brought forwards again.</p><p>Phimosis is generally not an emergency unless it prevents urination. Paraphimosis is an emergency because the trapped foreskin can compromise blood flow.</p><p><a href="' . esc_url( $phimosis ) . '">Learn more about phimosis →</a></p>',
	),
	array(
		'eyebrow' => 'Scarred foreskin',
		'title'   => 'What if my foreskin is scarred (BXO)?',
		'html'    => '<p>If examination shows that the foreskin is not simply tight but also white, thickened or scarred, there may be an underlying foreskin condition such as BXO / lichen sclerosis.</p><p>This matters because the abnormal scarred foreskin needs to be recognised when the circumcision is planned so that the affected tissue is appropriately removed.</p><p><a href="' . esc_url( $bxo ) . '">BXO / lichen sclerosis and circumcision →</a></p>',
	),
	array(
		'eyebrow' => 'Recurrent inflammation',
		'title'   => 'What if I also get recurrent balanitis?',
		'html'    => '<p>Some patients with a tight foreskin experience repeated episodes of inflammation as well as difficulty retracting the foreskin.</p><p>Recurrent balanitis can lead to scarring and progressive tightness, increasing the risk of subsequent foreskin entrapment.</p><p>If recurrent inflammation is part of your history, tell us when booking.</p><p><a href="' . esc_url( $balanitis ) . '">Recurrent balanitis and circumcision →</a></p>',
	),
);

$recurrence_left = array(
	cil_paraphimosis_h( 'What if I have had paraphimosis more than once?', 3 ),
	cil_paraphimosis_p( 'Repeated episodes are particularly important to mention.' ),
	cil_paraphimosis_p( 'If the foreskin repeatedly becomes trapped behind the glans, the underlying foreskin problem remains present.' ),
	cil_paraphimosis_p( 'Once the current episode has been treated and the tissues have recovered, contact us to discuss circumcision.' ),
	cil_paraphimosis_p( 'Circumcision removes the foreskin permanently and therefore prevents future paraphimosis caused by that foreskin.' ),
);

$recurrence_right = array(
	cil_paraphimosis_h( 'Is paraphimosis related to a tight foreskin?', 3 ),
	cil_paraphimosis_p( 'Often, yes.' ),
	cil_paraphimosis_p( 'A relatively tight foreskin can pass backwards over the widest part of the glans but then struggle to move forwards again.' ),
	cil_paraphimosis_p( 'Some patients therefore have a history of phimosis or foreskin tightness before developing paraphimosis.' ),
	cil_paraphimosis_p( 'If there is a tight or scarred foreskin, we take this into account when planning the circumcision.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'related-conditions',
	),
	array(
		cil_paraphimosis_section_head(
			'Clinical Differentiation',
			'Paraphimosis, phimosis and related foreskin conditions',
			'Recognising underlying foreskin conditions helps determine the right circumcision technique and prevents recurrence.'
		),
		cil_paraphimosis_info_cards( 'g-3', $condition_comparison_cards ),
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '32px' ) ) ) ),
					'innerBlocks'  => $recurrence_left,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text" style="margin-top:32px">' ),
						array_fill( 0, count( $recurrence_left ), null ),
						array( '</div>' )
					),
				),
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '32px' ) ) ) ),
					'innerBlocks'  => $recurrence_right,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text" style="margin-top:32px">' ),
						array_fill( 0, count( $recurrence_right ), null ),
						array( '</div>' )
					),
				),
			)
		),
	)
);

// -----------------------------------------------------------------------------
// 8. Surgical Assessment & Examination at Beverley Clinic (Split Layout)
// -----------------------------------------------------------------------------
$why_consider_col = array(
	cil_paraphimosis_h( 'Why consider circumcision after paraphimosis?', 2 ),
	cil_paraphimosis_p( 'An episode of paraphimosis demonstrates that the foreskin has been capable of becoming trapped behind the glans.' ),
	cil_paraphimosis_p( 'If the underlying foreskin remains tight, there is a possibility that the same problem can occur again.' ),
	cil_paraphimosis_p( 'Circumcision permanently removes the foreskin, meaning that the foreskin can no longer become trapped behind the glans in the same way.' ),
	cil_paraphimosis_p( 'For patients attending Beverley Clinic following paraphimosis, circumcision is the treatment we provide.' ),
);

$exam_items = array(
	'A persistent tight foreskin',
	'A defined tight ring',
	'Foreskin scarring',
	'BXO / lichen sclerosis',
	'Evidence of previous inflammation',
	'A tight or scarred frenulum',
	'Another anatomical issue that needs to be considered',
);

$exam_col = array(
	cil_paraphimosis_h( 'Circumcision following paraphimosis at Beverley Clinic', 2 ),
	cil_paraphimosis_p( 'Once the acute episode has been treated and the penis has recovered sufficiently, we assess the foreskin before circumcision.' ),
	cil_paraphimosis_p( 'We want to establish whether there is:' ),
	cil_paraphimosis_list( $exam_items ),
	cil_paraphimosis_p( 'The circumcision is then planned according to the patient\'s age, anatomy and examination findings.' ),
	cil_paraphimosis_p( 'For detailed information about the actual circumcision procedure, local anaesthetic, wound closure and recovery, use the appropriate guide rather than repeating it here:' ),
	array(
		'blockName'    => 'core/buttons',
		'attrs'        => array( 'className' => 'wp-block-buttons', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '16px' ) ) ) ),
		'innerBlocks'  => array(
			array(
				'blockName'    => 'core/button',
				'attrs'        => array( 'className' => 'wp-block-button is-style-outline' ),
				'innerBlocks'  => array(),
				'innerHTML'    => '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $adults ) . '">Adult circumcision →</a></div>',
				'innerContent' => array( '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $adults ) . '">Adult circumcision →</a></div>' ),
			),
			array(
				'blockName'    => 'core/button',
				'attrs'        => array( 'className' => 'wp-block-button is-style-outline' ),
				'innerBlocks'  => array(),
				'innerHTML'    => '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $children ) . '">Circumcision for children and teenagers →</a></div>',
				'innerContent' => array( '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $children ) . '">Circumcision for children and teenagers →</a></div>' ),
			),
		),
		'innerHTML'    => '',
		'innerContent' => array(
			'<div class="wp-block-buttons" style="margin-top:16px">',
			null,
			null,
			'</div>',
		),
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'clinical-assessment',
	),
	array(
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $why_consider_col,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $why_consider_col ), null ),
						array( '</div>' )
					),
				),
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $exam_col,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $exam_col ), null ),
						array( '</div>' )
					),
				),
			)
		),
	)
);

// -----------------------------------------------------------------------------
// 9. Recovery & Pricing (Split Layout matching BXO Section 10/11)
// -----------------------------------------------------------------------------
$recovery_col = array(
	cil_paraphimosis_h( 'Recovery after circumcision', 2 ),
	cil_paraphimosis_p( 'Recovery depends on the patient\'s age and the circumcision technique used.' ),
	cil_paraphimosis_p( 'You will receive detailed written aftercare following the procedure and follow-up support during healing.' ),
	cil_paraphimosis_p( 'For information about swelling, wound care, stitches, activity and when to contact us, see:' ),
	array(
		'blockName'    => 'core/buttons',
		'attrs'        => array( 'className' => 'wp-block-buttons', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '12px', 'bottom' => '20px' ) ) ) ),
		'innerBlocks'  => array(
			array(
				'blockName'    => 'core/button',
				'attrs'        => array( 'className' => 'wp-block-button is-style-outline' ),
				'innerBlocks'  => array(),
				'innerHTML'    => '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $aftercare ) . '">Circumcision aftercare →</a></div>',
				'innerContent' => array( '<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $aftercare ) . '">Circumcision aftercare →</a></div>' ),
			),
		),
		'innerHTML'    => '',
		'innerContent' => array(
			'<div class="wp-block-buttons" style="margin-top:12px;margin-bottom:20px">',
			null,
			'</div>',
		),
	),
	cil_paraphimosis_p( '<strong>For adults, our clinic generally advises avoiding sexual intercourse and masturbation for at least two weeks and until the wound is sufficiently healed.</strong>' ),
	cil_paraphimosis_p( 'If healing is incomplete at two weeks, you should wait longer and follow your individual aftercare instructions.' ),
);

$pricing_col = array(
	cil_paraphimosis_h( 'How much does circumcision after paraphimosis cost?', 2 ),
	cil_paraphimosis_p( 'Paraphimosis is commonly associated with an underlying medical foreskin problem.' ),
	cil_paraphimosis_p( 'The applicable circumcision price therefore depends on the patient\'s age and findings on examination.' ),
	cil_paraphimosis_p( 'For current prices and an explanation of what is included, see:' ),
	array(
		'blockName'    => 'core/buttons',
		'attrs'        => array( 'className' => 'wp-block-buttons', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '12px', 'bottom' => '20px' ) ) ) ),
		'innerBlocks'  => array(
			array(
				'blockName'    => 'core/button',
				'attrs'        => array( 'className' => 'wp-block-button' ),
				'innerBlocks'  => array(),
				'innerHTML'    => '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $prices ) . '">View circumcision prices →</a></div>',
				'innerContent' => array( '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $prices ) . '">View circumcision prices →</a></div>' ),
			),
		),
		'innerHTML'    => '',
		'innerContent' => array(
			'<div class="wp-block-buttons" style="margin-top:12px;margin-bottom:20px">',
			null,
			'</div>',
		),
	),
	cil_paraphimosis_p( 'If you are unsure which price applies, contact us and tell us that you have previously been treated for paraphimosis.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'recovery-pricing',
	),
	array(
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $recovery_col,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $recovery_col ), null ),
						array( '</div>' )
					),
				),
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $pricing_col,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $pricing_col ), null ),
						array( '</div>' )
					),
				),
			)
		),
	)
);

// -----------------------------------------------------------------------------
// 10. Why Choose Beverley Clinic for Paraphimosis Circumcision? (5 Info-Cards matching BXO Section 12)
// -----------------------------------------------------------------------------
$why_choose_cards = array(
	array(
		'title' => 'Specialist circumcision experience',
		'body'  => 'Circumcision is a core part of our day-to-day clinical work rather than an occasional procedure. We regularly assess foreskin problems as well as performing circumcisions for medical reasons.',
	),
	array(
		'title' => 'Recognition of underlying foreskin problems',
		'body'  => 'We recognise that paraphimosis often stems from underlying tightness, scarring or BXO. We assess the foreskin carefully before deciding which surgical technique is appropriate.',
	),
	array(
		'title' => 'Circumcision planned around the problem',
		'body'  => 'When circumcision is performed after paraphimosis, we pay particular attention to removing the constricting preputial ring completely to prevent any possibility of recurrence.',
	),
	array(
		'title' => 'All age groups: children, teenagers and adults',
		'body'  => 'We provide circumcision across all age groups, allowing us to understand how foreskin anatomy and surgical techniques differ between paediatric and adult patients.',
	),
	array(
		'title' => 'Comprehensive aftercare and follow-up',
		'body'  => 'Patients receive preparation information before treatment, detailed written aftercare afterwards and clinical follow-up support during the healing process.',
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
		cil_paraphimosis_section_head(
			'Clinical Expertise',
			'Why choose Beverley Clinic for paraphimosis circumcision?',
			'Dedicated circumcision expertise in North-West London with a patient-centred medical approach.'
		),
		cil_paraphimosis_info_cards( 'g-3', $why_choose_cards ),
	)
);

// -----------------------------------------------------------------------------
// 11. Frequently Asked Questions about Paraphimosis (11 Client FAQs)
// -----------------------------------------------------------------------------
$faq_items = array(
	array(
		'q' => 'Is paraphimosis an emergency?',
		'a' => 'Yes. A foreskin that is trapped behind the glans and cannot be returned forwards requires urgent medical treatment.',
	),
	array(
		'q' => 'Should I book an appointment with Beverley Clinic if I have paraphimosis right now?',
		'a' => 'No. Do not wait for a routine circumcision appointment. Acute paraphimosis requires urgent medical treatment.',
	),
	array(
		'q' => 'What is the difference between phimosis and paraphimosis?',
		'a' => 'Phimosis is a foreskin that cannot retract normally. Paraphimosis occurs when the foreskin has been retracted but becomes trapped behind the glans and cannot return forwards. <p><a href="' . esc_url( $phimosis ) . '">Read our phimosis guide →</a></p>',
	),
	array(
		'q' => 'Why is paraphimosis dangerous?',
		'a' => 'The trapped foreskin can form a constricting band. Increasing swelling can interfere with blood circulation to the penis if the condition is not treated promptly.',
	),
	array(
		'q' => 'Can paraphimosis go away by itself?',
		'a' => 'You should not wait to see whether acute paraphimosis resolves by itself. If the foreskin is trapped behind the glans and cannot be returned, seek urgent medical treatment.',
	),
	array(
		'q' => 'Can paraphimosis happen again?',
		'a' => 'Yes. If the underlying tight foreskin remains present, another episode can occur.',
	),
	array(
		'q' => 'Does circumcision prevent paraphimosis?',
		'a' => 'Circumcision permanently removes the foreskin. Once the foreskin has been completely removed, it cannot subsequently become trapped behind the glans and cause paraphimosis.',
	),
	array(
		'q' => 'When can I be circumcised after paraphimosis?',
		'a' => 'Circumcision is generally planned after the acute episode has been successfully treated and significant swelling and inflammation have settled. Timing depends on the individual patient and examination.',
	),
	array(
		'q' => 'What if I have phimosis as well?',
		'a' => 'Phimosis is commonly associated with paraphimosis. We assess the tight foreskin when planning your circumcision. <p><a href="' . esc_url( $phimosis ) . '">Read about circumcision for phimosis →</a></p>',
	),
	array(
		'q' => 'What if my foreskin is white or scarred?',
		'a' => 'A white, thickened or scarred foreskin can suggest BXO / lichen sclerosis and should be recognised before circumcision. <p><a href="' . esc_url( $bxo ) . '">Read about BXO / lichen sclerosis →</a></p>',
	),
	array(
		'q' => 'What treatment does Beverley Clinic provide after paraphimosis?',
		'a' => 'Once the emergency episode has been treated and the penis has recovered sufficiently, the treatment we provide for the underlying foreskin problem is circumcision.',
	),
);

$blocks[] = cil_dyn_block(
	'cil/faq',
	array(
		'heading' => 'Frequently asked questions about paraphimosis',
		'items'   => $faq_items,
	)
);

// -----------------------------------------------------------------------------
// 12. Circumcision after paraphimosis in London (Consultation Split Section)
// -----------------------------------------------------------------------------
$consultation_left = array(
	cil_paraphimosis_h( 'Circumcision after paraphimosis in London', 2 ),
	cil_paraphimosis_p( 'If you have already received urgent treatment for paraphimosis and would now like to arrange circumcision, contact Beverley Clinic.' ),
	cil_paraphimosis_p( 'Tell us when the paraphimosis occurred, how it was treated and whether you have had previous episodes.' ),
	cil_paraphimosis_p( 'If your foreskin is currently trapped behind the glans, however, do not wait for an appointment with us. Seek urgent medical treatment.' ),
	cil_paraphimosis_p( '<strong>Beverley Clinic</strong><br>78 Beverley Drive<br>Edgware<br>North-West London<br>HA8 5NE' ),
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
		'formId'  => 'paraphimosis',
		'subject' => 'Paraphimosis enquiry (post-emergency)',
		'urgent'  => false,
	)
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
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
// 13. Closing CTA Band
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
// Save to Post ID 22 & Export Fixture
// -----------------------------------------------------------------------------
$content = cil_serialize_blocks( $blocks );

$page = get_page_by_path( 'conditions/paraphimosis' );
if ( ! $page ) {
	$page = get_post( 22 );
}
if ( ! $page ) {
	fwrite( STDERR, "Page not found for paraphimosis\n" );
	exit( 1 );
}

kses_remove_filters();
remove_filter( 'content_save_pre', 'balanceTags', 50 );
remove_filter( 'content_save_pre', 'convert_invalid_entities' );

$result = wp_update_post(
	wp_slash(
		array(
			'ID'           => (int) $page->ID,
			'post_title'   => 'Paraphimosis and Circumcision',
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

update_post_meta( $page->ID, '_cil_document_title', 'Paraphimosis | Emergency Symptoms & Circumcision' );
update_post_meta( $page->ID, '_cil_meta_description', 'Paraphimosis occurs when the foreskin becomes trapped behind the head of the penis and is a medical emergency. Learn about symptoms and circumcision after paraphimosis.' );
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
	'slug'                => 'paraphimosis',
	'path'                => '/conditions/paraphimosis/',
	'local_id'            => (int) $page->ID,
	'source_modified_gmt' => $page->post_modified_gmt,
	'source_sha256'       => hash( 'sha256', $content_remote ),
	'replace_from'        => untrailingslashit( $replace_from ),
	'replace_to'          => untrailingslashit( $replace_to ),
	'content'             => $content_remote,
);
$path = cil_content_write_fixture( 'paraphimosis', $fixture );
if ( is_wp_error( $path ) ) {
	fwrite( STDERR, $path->get_error_message() . "\n" );
	exit( 1 );
}

echo "UPDATED PARAPHIMOSIS SUCCESSFUL\n";
echo "Post ID: " . $page->ID . "\n";
echo "Content Bytes: " . strlen( $page->post_content ) . "\n";
echo "Fixture Path: " . $path . "\n";
echo "Fixture SHA256: " . $fixture['source_sha256'] . "\n";
