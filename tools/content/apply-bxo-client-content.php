<?php
/**
 * Apply full client BXO content to /conditions/bxo/ + export fixture.
 *
 * Source: BXO.docx
 * Implements rich Gutenberg blocks, alternating section bands, animated info-cards,
 * checklists, visual clinical cycle, callouts, and split layout matching Home, Balanitis, and Phimosis pages.
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
function cil_bxo_p( $html ) {
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
function cil_bxo_h( $text, $level = 2 ) {
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
function cil_bxo_list( $items ) {
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
function cil_bxo_info_cards( $columns, $items, $level = 3 ) {
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
function cil_bxo_section_head( $eyebrow, $heading, $lede = '', $display = 'd-2' ) {
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
$bxo       = home_url( '/conditions/bxo/' );
$phimosis  = home_url( '/conditions/phimosis/' );
$balanitis = home_url( '/conditions/balanitis/' );
$children  = home_url( '/children/' );
$adults    = home_url( '/adults/' );
$aftercare = home_url( '/aftercare/' );
$prices    = home_url( '/prices/' );
$contact   = home_url( '/contact/' );
$book      = home_url( '/book/' );

// -----------------------------------------------------------------------------
// Section 1: Intro Group & Spec Panel
// -----------------------------------------------------------------------------
$intro_paras = array(
	cil_bxo_p( 'As the foreskin becomes less elastic, it can become increasingly difficult or painful to retract. Some patients develop cracking, bleeding, painful erections or a tight scarred ring around the foreskin opening.' ),
	cil_bxo_p( 'At Beverley Clinic in North-West London, we provide circumcision for children, teenagers and adults with BXO / lichen sclerosis affecting the foreskin.' ),
	cil_bxo_p( 'When circumcision is being performed for BXO, it is particularly important to recognise the abnormal scarred foreskin so that the affected foreskin is appropriately removed.' ),
);

$intro_group = array(
	'blockName'    => 'core/group',
	'attrs'        => array( 'className' => 'body-text' ),
	'innerBlocks'  => $intro_paras,
	'innerHTML'    => '',
	'innerContent' => array_merge(
		array( '<div class="wp-block-group body-text">' ),
		array_fill( 0, count( $intro_paras ), null ),
		array( '</div>' )
	),
);

$spec_note = 'An adult circumcision involving a medical or foreskin problem is £1,080. See our <a href="' . esc_url( $prices ) . '" style="color:var(--blue-deep)">price list</a>.';

// -----------------------------------------------------------------------------
// Assembly of Blocks
// -----------------------------------------------------------------------------
$blocks = array();

// 1. Hero Page Head
$blocks[] = cil_dyn_block(
	'cil/page-head',
	array(
		'eyebrow'    => 'Condition · Lichen sclerosis',
		'title'      => 'BXO / Lichen Sclerosis in Men',
		'lede'       => 'BXO (balanitis xerotica obliterans), also known as male genital lichen sclerosis, is a chronic inflammatory condition that can cause whitening, scarring and progressive tightening of the foreskin.',
		'reviewedBy' => cil_reviewed_by_haidar(),
		'crumbs'     => array(
			array(
				'label' => 'Reasons',
				'href'  => $phimosis,
			),
			array(
				'label' => 'BXO',
				'href'  => '',
			),
		),
	)
);

// 2. Intro Section (Split with Spec Panel)
$blocks[] = cil_section_block(
	array(
		'size' => 'section-sm',
		'band' => '',
		'wrap' => 'wrap',
	),
	array(
		cil_split_block(
			array(
				$intro_group,
				cil_dyn_block(
					'cil/spec-panel',
					array(
						'eyebrow' => 'BXO at a glance',
						'rows'    => array(
							array( 'k' => 'What is it?', 'v' => 'A chronic inflammatory and scarring condition affecting the foreskin and sometimes the glans.' ),
							array( 'k' => 'What does it look like?', 'v' => 'The foreskin may appear white or pale, thickened, scarred or less elastic.' ),
							array( 'k' => 'What problems can it cause?', 'v' => 'Progressive phimosis, painful retraction, cracking, bleeding, painful erections and sometimes urinary problems.' ),
							array( 'k' => 'Who can develop it?', 'v' => 'BXO can occur in both children and adults.' ),
							array( 'k' => 'What treatment does Beverley Clinic provide?', 'v' => 'Our clinic provides circumcision for BXO affecting the foreskin.' ),
							array( 'k' => 'Why is assessment important?', 'v' => 'The extent and position of the scarred tissue needs to be recognised before the circumcision is planned.' ),
						),
						'note'    => $spec_note,
					)
				),
			)
		),
	)
);

// 3. What is BXO? (band: bg-card edge)
$what_is_body = array(
	cil_bxo_p( 'It is the term commonly used when lichen sclerosis affects the male genital area, particularly the foreskin and glans.' ),
	cil_bxo_p( 'You may see the condition referred to elsewhere as lichen sclerosus, which is an alternative spelling commonly used in medical literature.' ),
	cil_bxo_p( 'BXO can gradually change the structure of the foreskin. Healthy foreskin is normally soft and elastic, whereas foreskin affected by BXO can become pale, thickened, fragile, scarred and less flexible.' ),
	cil_bxo_p( 'As this progresses, the foreskin opening can narrow and eventually become too tight to retract over the head of the penis.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'what-is-bxo',
	),
	array(
		cil_bxo_section_head(
			'Understanding BXO',
			'What is BXO?',
			'BXO stands for balanitis xerotica obliterans.'
		),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'body-text' ),
			'innerBlocks'  => $what_is_body,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group body-text">' ),
				array_fill( 0, count( $what_is_body ), null ),
				array( '</div>' )
			),
		),
	)
);

// 4. Appearance and Symptoms (Split layout)
$appearance_blocks = array(
	cil_bxo_h( 'What does BXO look like?', 2 ),
	cil_bxo_p( 'The appearance varies between patients.' ),
	cil_bxo_p( 'Typical changes can include:' ),
	cil_bxo_list(
		array(
			'A white or pale area of foreskin',
			'A white ring around the foreskin opening',
			'Thickened or hardened skin',
			'A tight scarred foreskin',
			'Loss of normal skin elasticity',
			'Small cracks or splits',
			'Bleeding following retraction',
			'Red or inflamed areas',
			'Changes affecting the glans',
			'Narrowing around the urinary opening in more extensive disease',
		)
	),
	cil_bxo_p( 'Some patients initially think they simply have a tight foreskin.' ),
	cil_bxo_p( 'On examination, however, the white or scarred ring characteristic of BXO may be visible.' ),
);

$symptoms_blocks = array(
	cil_bxo_h( 'What are the symptoms of BXO?', 2 ),
	cil_bxo_p( 'BXO does not cause identical symptoms in every patient.' ),
	cil_bxo_p( 'Symptoms can include:' ),
	cil_bxo_list(
		array(
			'Increasing difficulty retracting the foreskin',
			'Phimosis',
			'Pain when retracting the foreskin',
			'A tight or constricting sensation',
			'Cracking or splitting of the foreskin',
			'Bleeding from small tears',
			'Painful erections',
			'Discomfort during sexual activity',
			'Recurrent inflammation',
			'Difficulty cleaning underneath the foreskin',
			'Changes in the appearance of the foreskin',
			'Problems with the urinary stream if the condition affects the urinary opening',
		)
	),
	cil_bxo_p( 'The condition can develop gradually, so some patients do not appreciate how much their foreskin has changed until retraction becomes significantly restricted.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'appearance-and-symptoms',
	),
	array(
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $appearance_blocks,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $appearance_blocks ), null ),
						array( '</div>' )
					),
				),
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $symptoms_blocks,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $symptoms_blocks ), null ),
						array( '</div>' )
					),
				),
			)
		),
	)
);

// 5. BXO and Phimosis + The Clinical Cycle (band: bg-warm)
$cycle_items_inner = array();
$cycle_content     = array( '<ul class="cil-cycle-flow wp-block-list">' );
$cycle_stages      = array(
	'Tightness',
	'Attempted retraction',
	'Cracking or micro-tears',
	'Healing and further scarring',
	'Increased tightness',
);
foreach ( $cycle_stages as $stage ) {
	$li                  = '<li><span class="cycle-title">' . esc_html( $stage ) . '</span></li>';
	$cycle_items_inner[] = array(
		'blockName'    => 'core/list-item',
		'attrs'        => array(),
		'innerBlocks'  => array(),
		'innerHTML'    => $li,
		'innerContent' => array( $li ),
	);
	$cycle_content[]     = null;
}
$cycle_content[] = '</ul>';

$cycle_list_block = array(
	'blockName'    => 'core/list',
	'attrs'        => array( 'className' => 'cil-cycle-flow' ),
	'innerBlocks'  => $cycle_items_inner,
	'innerHTML'    => '',
	'innerContent' => $cycle_content,
);

$phimosis_cycle_blocks = array(
	cil_bxo_section_head(
		'Progressive Tightness',
		'BXO and phimosis',
		'BXO is an important cause of pathological phimosis.'
	),
	array(
		'blockName'    => 'core/group',
		'attrs'        => array( 'className' => 'body-text' ),
		'innerBlocks'  => array(
			cil_bxo_p( 'Phimosis means that the foreskin is too tight to retract comfortably over the glans.' ),
			cil_bxo_p( 'With BXO, chronic inflammation can cause the foreskin to lose elasticity and develop a firm scarred ring.' ),
			cil_bxo_p( 'A patient may previously have been able to retract his foreskin normally but notice that it becomes progressively tighter.' ),
			cil_bxo_p( 'This is different from the normal non-retractable foreskin seen in babies and many younger boys.' ),
			cil_bxo_p( '<a href="' . esc_url( $phimosis ) . '">Learn more about phimosis →</a>' ),
		),
		'innerHTML'    => '',
		'innerContent' => array(
			'<div class="wp-block-group body-text">',
			null,
			null,
			null,
			null,
			null,
			'</div>',
		),
	),
	cil_bxo_h( 'The cycle of tightness, cracking and scarring', 3 ),
	cil_bxo_p( 'One of the problems we see with a scarred foreskin is a cycle of:' ),
	$cycle_list_block,
	array(
		'blockName'    => 'core/group',
		'attrs'        => array( 'className' => 'body-text' ),
		'innerBlocks'  => array(
			cil_bxo_p( 'As the foreskin becomes less elastic, attempting to retract it can produce further splitting.' ),
			cil_bxo_p( 'The patient may then notice that the foreskin becomes increasingly difficult to retract despite repeatedly trying to loosen it.' ),
		),
		'innerHTML'    => '',
		'innerContent' => array(
			'<div class="wp-block-group body-text">',
			null,
			null,
			'</div>',
		),
	),
	cil_dyn_block(
		'cil/callout',
		array(
			'title' => 'Clinical advice',
			'text'  => 'If the foreskin is visibly scarred, white or repeatedly cracking, do not forcibly retract it.',
		)
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'phimosis-and-cycle',
	),
	$phimosis_cycle_blocks
);

// 6. Can children get BXO? & Can adults develop BXO? (band: bg-card edge)
$children_blocks = array(
	cil_bxo_h( 'Can children get BXO?', 2 ),
	cil_bxo_p( 'Yes.' ),
	cil_bxo_p( 'BXO can occur in children as well as teenagers and adults.' ),
	cil_bxo_p( 'A non-retractable foreskin alone is very common in younger boys and does not mean that a child has BXO.' ),
	cil_bxo_p( 'The concern is different when there are features such as:' ),
	cil_bxo_list(
		array(
			'A clearly scarred foreskin',
			'A white or pale tight ring',
			'Progressive rather than developmental tightness',
			'Repeated cracking',
			'Significant inflammation',
			'Pain',
			'Urinary symptoms',
		)
	),
	cil_bxo_p( 'We examine the foreskin before circumcision because it is important to distinguish a normally developing foreskin from one affected by pathological scarring.' ),
	cil_bxo_p( '<a href="' . esc_url( $children ) . '">Learn more about circumcision for children →</a>' ),
);

$adults_blocks = array(
	cil_bxo_h( 'Can adults develop BXO?', 2 ),
	cil_bxo_p( 'Yes.' ),
	cil_bxo_p( 'Some adults develop increasing foreskin tightness despite previously having a normally retractable foreskin.' ),
	cil_bxo_p( 'A man may initially notice that retraction becomes uncomfortable during erections. Later, the foreskin may begin cracking or become difficult to retract even when the penis is soft.' ),
	cil_bxo_p( 'A new tight foreskin in an adult, particularly where there is whitening or scarring, should be medically assessed.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'children-and-adults',
	),
	array(
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $children_blocks,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $children_blocks ), null ),
						array( '</div>' )
					),
				),
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $adults_blocks,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $adults_blocks ), null ),
						array( '</div>' )
					),
				),
			)
		),
	)
);

// 7. Is BXO contagious? What causes BXO? How is BXO diagnosed? (band: '')
$causes_cards = array(
	array(
		'eyebrow' => 'Transmission',
		'title'   => 'Is BXO contagious?',
		'html'    => '<p>No.</p><p>BXO / lichen sclerosis is not a sexually transmitted infection and it is not contagious.</p><p>You cannot pass BXO to a partner through sexual contact.</p><p>It is also not simply caused by poor personal hygiene.</p>',
	),
	array(
		'eyebrow' => 'Aetiology',
		'title'   => 'What causes BXO?',
		'html'    => '<p>The exact cause is not completely understood.</p><p>It is regarded as a chronic inflammatory skin condition, and immune-system factors are thought to be involved.</p><p>What matters clinically is recognising the characteristic changes and determining how much of the foreskin and surrounding penile tissue appears to be affected.</p>',
	),
	array(
		'eyebrow' => 'Clinical assessment',
		'title'   => 'How is BXO diagnosed?',
		'html'    => '<p>BXO is often suspected from the patient\'s history and the appearance of the foreskin during examination.</p><p>Not every white patch on the penis is BXO. Other skin conditions can produce changes in colour or appearance, which is why a persistent or unusual penile lesion should not simply be self-diagnosed from photographs online.</p>',
	),
);

$diagnosis_checklist_body = array(
	cil_bxo_p( 'During examination, we look for features such as:' ),
	cil_bxo_list(
		array(
			'Whitening or pale scar tissue',
			'A tight fibrous ring',
			'Loss of elasticity',
			'Cracking or splitting',
			'Thickening',
			'Associated phimosis',
			'Changes involving the glans',
			'Changes around the urinary opening',
		)
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'causes-and-diagnosis',
	),
	array(
		cil_bxo_section_head(
			'Clinical Facts',
			'Causes and diagnosis of BXO'
		),
		cil_bxo_info_cards( 'g-3', $causes_cards ),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'body-text', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '32px' ) ) ) ),
			'innerBlocks'  => $diagnosis_checklist_body,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group body-text" style="margin-top:32px">' ),
				array_fill( 0, count( $diagnosis_checklist_body ), null ),
				array( '</div>' )
			),
		),
	)
);

// 8. Circumcision for BXO, Style matters, What happens (band: bg-warm)
$circ_intro_body = array(
	cil_bxo_p( 'The treatment we provide at Beverley Clinic for BXO affecting the foreskin is circumcision.' ),
	cil_bxo_p( 'Circumcision removes the foreskin, including the tight and scarred foreskin responsible for the phimosis.' ),
	cil_bxo_p( 'For BXO, the procedure needs to be planned with particular attention to where the diseased or scarred tissue is located.' ),
	cil_bxo_p( 'This is different from approaching circumcision as a purely cosmetic procedure on otherwise healthy foreskin.' ),
	cil_bxo_p( 'The aim is to adequately remove the problematic foreskin rather than leave a significant tight or diseased ring behind.' ),
);

$style_matters_blocks = array(
	cil_bxo_h( 'Why does the style of circumcision matter with BXO?', 2 ),
	cil_bxo_p( 'When circumcision is being performed for religious, cultural or personal reasons on healthy foreskin, there may be more flexibility regarding how the foreskin is distributed.' ),
	cil_bxo_p( 'BXO is different.' ),
	cil_bxo_p( 'There is an underlying scarring condition, so our priority is to identify the abnormal foreskin and make sure that the circumcision appropriately addresses it.' ),
	cil_bxo_p( 'Before proceeding we consider:' ),
	cil_bxo_list(
		array(
			'The position of the scarred ring',
			'How much foreskin appears affected',
			'The mobility of the surrounding penile skin',
			'Whether the glans appears affected',
			'The urinary opening',
			'The frenulum',
			'Whether there is a buried or retractile penis',
			'Any previous penile surgery',
		)
	),
	cil_bxo_p( 'The circumcision is then planned according to the individual anatomy and the extent of the foreskin problem.' ),
);

$what_happens_blocks = array(
	cil_bxo_h( 'What happens during circumcision for BXO?', 2 ),
	cil_bxo_p( 'For suitable adults at Beverley Clinic, circumcision is normally performed under local anaesthetic.' ),
	cil_bxo_p( 'The penis and foreskin are examined before the procedure and the scarred area is identified.' ),
	cil_bxo_p( 'We commonly use a forceps-guided circumcision technique with thermal cautery.' ),
	cil_bxo_p( 'The affected foreskin is removed and thermal cautery helps control bleeding.' ),
	cil_bxo_p( 'Depending on the individual wound, the skin may be closed using dissolvable stitches, medical skin glue, a combination of the two, or occasionally neither.' ),
	cil_bxo_p( 'You receive detailed aftercare instructions before leaving the clinic.' ),
	cil_bxo_p( '<a href="' . esc_url( $adults ) . '">Learn more about adult circumcision →</a>' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'circumcision-for-bxo',
	),
	array(
		cil_bxo_section_head(
			'Surgical Treatment',
			'Circumcision for BXO'
		),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'body-text', 'style' => array( 'spacing' => array( 'margin' => array( 'bottom' => '40px' ) ) ) ),
			'innerBlocks'  => $circ_intro_body,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group body-text" style="margin-bottom:40px">' ),
				array_fill( 0, count( $circ_intro_body ), null ),
				array( '</div>' )
			),
		),
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $style_matters_blocks,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $style_matters_blocks ), null ),
						array( '</div>' )
					),
				),
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $what_happens_blocks,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $what_happens_blocks ), null ),
						array( '</div>' )
					),
				),
			)
		),
	)
);

// 9. Ongoing disease, Urinary opening & Cancer risk (band: bg-card edge)
$anatomy_cards = array(
	array(
		'eyebrow' => 'Ongoing disease',
		'title'   => 'Can BXO continue after circumcision?',
		'html'    => '<p>Circumcision removes the affected foreskin and can successfully treat BXO that is limited to the foreskin.</p><p>However, BXO can sometimes involve the glans or urinary opening as well as the foreskin.</p><p>Circumcision therefore does not mean that changes elsewhere on the penis should subsequently be ignored.</p><p>If there are persistent white areas, changes around the urinary opening, difficulty passing urine or another abnormal area after healing, further medical assessment may be required.</p>',
	),
	array(
		'eyebrow' => 'Meatal involvement',
		'title'   => 'BXO and the urinary opening',
		'html'    => '<p>In some patients, lichen sclerosis can affect the meatus, which is the opening at the tip of the penis through which urine passes.</p><p>Scarring around this area can cause the opening to become narrower.</p><p>Circumcision removes the foreskin but does not itself treat narrowing further inside the urinary opening or urethra. Patients with suspected meatal or urethral involvement may require assessment by an appropriate urology service.</p><p><strong>If you cannot pass urine at all, seek urgent medical attention.</strong></p>',
	),
	array(
		'eyebrow' => 'Clinical awareness',
		'title'   => 'Is BXO associated with penile cancer?',
		'html'    => '<p>There is an association between genital lichen sclerosis and an increased risk of penile cancer, although the overall risk remains low.</p><p>This does not mean that a patient with BXO has cancer or will develop cancer. It does mean that persistent abnormal changes should not simply be ignored.</p><p>These findings require appropriate medical assessment rather than simply booking a routine circumcision without mentioning them.</p>',
	),
);

$meatal_symptoms_col = array(
	cil_bxo_p( '<strong>Symptoms of meatal involvement can include:</strong>' ),
	cil_bxo_list(
		array(
			'A weaker urinary stream',
			'Spraying of urine',
			'Taking longer to empty the bladder',
			'Straining to pass urine',
			'Pain or discomfort during urination',
		)
	),
	cil_bxo_p( 'Tell us if you have urinary symptoms when you contact the clinic.' ),
);

$cancer_warning_col = array(
	cil_bxo_p( '<strong>Seek medical assessment if you notice:</strong>' ),
	cil_bxo_list(
		array(
			'A persistent ulcer or sore',
			'A lump',
			'An area that repeatedly bleeds',
			'A persistent thickened area',
			'A new or changing lesion',
			'An abnormal area that does not heal',
		)
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'anatomy-and-risks',
	),
	array(
		cil_bxo_section_head(
			'Anatomical Considerations',
			'Disease extent and clinical precautions'
		),
		cil_bxo_info_cards( 'g-3', $anatomy_cards ),
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '32px' ) ) ) ),
					'innerBlocks'  => $meatal_symptoms_col,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text" style="margin-top:32px">' ),
						array_fill( 0, count( $meatal_symptoms_col ), null ),
						array( '</div>' )
					),
				),
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '32px' ) ) ) ),
					'innerBlocks'  => $cancer_warning_col,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text" style="margin-top:32px">' ),
						array_fill( 0, count( $cancer_warning_col ), null ),
						array( '</div>' )
					),
				),
			)
		),
	)
);

// 10. Differential Comparisons + Clinical Experience (band: '')
$comparison_cards = array(
	array(
		'eyebrow' => 'Differential diagnosis',
		'title'   => 'BXO or balanitis – what is the difference?',
		'html'    => '<p>Balanitis describes inflammation of the glans and can occur for many different reasons.</p><p>BXO / lichen sclerosis is a chronic inflammatory and scarring condition that can progressively alter the structure and elasticity of the foreskin.</p><p>A patient with BXO can also experience inflammation, so the two terms are not mutually exclusive.</p><p>The presence of whitening, significant scarring and progressive phimosis makes BXO particularly important to consider.</p><p><a href="' . esc_url( $balanitis ) . '">Learn more about recurrent balanitis →</a></p>',
	),
	array(
		'eyebrow' => 'Differential diagnosis',
		'title'   => 'BXO or ordinary phimosis – what is the difference?',
		'html'    => '<p>Phimosis simply describes a foreskin that is too tight to retract.</p><p>BXO is one possible cause of pathological phimosis.</p><p>A tight foreskin without significant scarring may look quite different from a foreskin affected by BXO.</p><p>With BXO, we may see a distinctive pale or white scarred ring, thickening, loss of elasticity and repeated cracking.</p><p>Recognising the difference matters when planning the circumcision because the abnormal scarred tissue needs to be appropriately treated.</p><p><a href="' . esc_url( $phimosis ) . '">Learn more about phimosis →</a></p>',
	),
);

$clinical_exp_body = array(
	cil_bxo_h( 'From our clinical experience', 3 ),
	cil_bxo_p( 'Patients frequently contact us saying that they have a “tight foreskin”, without realising that the foreskin itself has become scarred.' ),
	cil_bxo_p( 'On examination, some of these patients have a clearly defined white, inelastic band of tissue rather than simple tightness.' ),
	cil_bxo_p( 'This distinction matters.' ),
	cil_bxo_p( 'When we circumcise a patient with BXO, our attention is not simply on how much foreskin to remove. We identify the position and extent of the abnormal scarred foreskin so that the procedure addresses the medical problem for which the patient came to us.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'differential-comparisons',
	),
	array(
		cil_bxo_section_head(
			'Clinical Differentiation',
			'Distinguishing BXO from other foreskin conditions'
		),
		cil_bxo_info_cards( 'g-2', $comparison_cards ),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'wp-block-group is-style-cil-card body-text', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '36px' ) ) ) ),
			'innerBlocks'  => $clinical_exp_body,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group is-style-cil-card body-text" style="margin-top:36px">' ),
				array_fill( 0, count( $clinical_exp_body ), null ),
				array( '</div>' )
			),
		),
	)
);

// 11. Recovery, Sex, Risks & Urgent Care (band: bg-warm)
$recovery_sex_blocks = array(
	cil_bxo_h( 'Recovery after circumcision for BXO', 2 ),
	cil_bxo_p( 'Some swelling, bruising, tenderness and sensitivity are expected following circumcision.' ),
	cil_bxo_p( 'If severe BXO or phimosis meant that the glans was rarely exposed before surgery, the newly exposed glans can initially feel particularly sensitive.' ),
	cil_bxo_p( 'This generally becomes easier as healing progresses and the patient becomes accustomed to the glans being exposed.' ),
	cil_bxo_p( 'The early appearance is not the final result. Swelling reduces and the circumcision scar continues to settle over time.' ),
	cil_bxo_p( 'We provide detailed written aftercare and follow-up support during healing.' ),
	cil_bxo_p( '<a href="' . esc_url( $aftercare ) . '">Read our circumcision aftercare guide →</a>' ),
	cil_bxo_h( 'When can I have sex after circumcision for BXO?', 2 ),
	cil_bxo_p( 'At Beverley Clinic, we generally advise avoiding sexual intercourse and masturbation for at least two weeks and until the wound is sufficiently healed.' ),
	cil_bxo_p( 'Healing varies between patients.' ),
	cil_bxo_p( 'If the wound has not healed sufficiently at two weeks, or sexual activity would place tension on the wound, you should wait longer.' ),
	cil_bxo_p( 'Follow the individual aftercare instructions provided following your procedure.' ),
);

$risks_blocks = array(
	cil_bxo_h( 'What are the risks of circumcision?', 2 ),
	cil_bxo_p( 'Circumcision is a surgical procedure and complications can occur.' ),
	cil_bxo_p( 'Potential risks include:' ),
	cil_bxo_list(
		array(
			'Bleeding',
			'Infection',
			'Swelling and bruising',
			'Wound separation',
			'Delayed healing',
			'Scarring',
			'Altered sensation',
			'Cosmetic dissatisfaction',
			'Need for further treatment',
		)
	),
	cil_bxo_p( 'Circumcision permanently removes the foreskin and therefore permanently changes the appearance of the penis.' ),
	cil_bxo_p( 'We discuss the relevant risks before treatment.' ),
);

$urgent_callout_inner = array(
	cil_bxo_h( 'When should I seek urgent medical attention?', 3 ),
	cil_bxo_p( 'BXO itself usually develops gradually rather than presenting as an emergency.' ),
	cil_bxo_p( 'However, seek urgent medical attention if:' ),
	cil_bxo_list(
		array(
			'You cannot pass urine',
			'Your foreskin becomes trapped behind the glans and cannot be returned forward',
			'There is rapidly increasing swelling or severe pain',
			'You are seriously unwell with an infection',
		)
	),
	cil_bxo_p( 'You should also arrange appropriate medical assessment for a persistent ulcer, lump, unexplained bleeding or other abnormal area that does not heal.' ),
	cil_bxo_p( '<strong>Do not wait for a routine circumcision appointment if you believe you have an emergency.</strong>' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'recovery-and-risks',
	),
	array(
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $recovery_sex_blocks,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $recovery_sex_blocks ), null ),
						array( '</div>' )
					),
				),
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $risks_blocks,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $risks_blocks ), null ),
						array( '</div>' )
					),
				),
			)
		),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'wp-block-group is-style-cil-callout-urgent body-text', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '36px' ) ) ) ),
			'innerBlocks'  => $urgent_callout_inner,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group is-style-cil-callout-urgent body-text" style="margin-top:36px">' ),
				array_fill( 0, count( $urgent_callout_inner ), null ),
				array( '</div>' )
			),
		),
	)
);

// 12. Pricing / Cost (band: bg-card edge)
$pricing_blocks = array(
	cil_bxo_h( 'How much does circumcision for BXO cost?', 2 ),
	cil_bxo_p( 'At our London clinic, adult circumcision involving a medical or foreskin problem, with or without frenulum treatment, is currently £1,080.' ),
	cil_bxo_p( 'Children with medical foreskin problems are assessed individually.' ),
	cil_bxo_p( 'If you believe you have BXO, tell us when contacting the clinic so that we know you are enquiring about circumcision for a medical foreskin condition.' ),
	array(
		'blockName'    => 'core/buttons',
		'attrs'        => array( 'className' => 'wp-block-buttons', 'style' => array( 'spacing' => array( 'margin' => array( 'top' => '20px' ) ) ) ),
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
			'<div class="wp-block-buttons" style="margin-top:20px">',
			null,
			'</div>',
		),
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section-sm',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'pricing',
	),
	array(
		cil_bxo_section_head(
			'Transparent Pricing',
			'Circumcision fees'
		),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'body-text' ),
			'innerBlocks'  => $pricing_blocks,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group body-text">' ),
				array_fill( 0, count( $pricing_blocks ), null ),
				array( '</div>' )
			),
		),
	)
);

// 13. Why choose Beverley Clinic for BXO circumcision? (band: '')
$why_cards = array(
	array(
		'title' => 'Specialist circumcision experience',
		'body'  => 'Circumcision is a core part of our day-to-day clinical work rather than an occasional procedure.',
	),
	array(
		'title' => 'Recognition of scarred foreskin',
		'body'  => 'We understand the importance of distinguishing ordinary foreskin tightness from a scarred foreskin where BXO may be present.',
	),
	array(
		'title' => 'Circumcision planned around the problem',
		'body'  => 'With BXO, we identify the location and extent of the abnormal foreskin when planning the circumcision.',
	),
	array(
		'title' => 'Children, teenagers and adults',
		'body'  => 'We provide circumcision across different age groups and adapt the procedure to the patient\'s age and anatomy.',
	),
	array(
		'title' => 'Continuity of care',
		'body'  => 'Patients receive preparation information before treatment, detailed aftercare afterwards and follow-up during healing.',
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
		cil_bxo_section_head(
			'Clinical Expertise',
			'Why choose Beverley Clinic for BXO circumcision?'
		),
		cil_bxo_info_cards( 'g-3', $why_cards ),
	)
);

// 14. Frequently Asked Questions (cil/faq)
$faq_items = array(
	array(
		'q' => 'What does BXO stand for?',
		'a' => 'BXO stands for balanitis xerotica obliterans. It is associated with male genital lichen sclerosis.',
	),
	array(
		'q' => 'Is BXO the same as lichen sclerosis?',
		'a' => 'BXO is the term commonly used when lichen sclerosis affects the male genital area, particularly the foreskin and glans. You may also see the condition spelled “lichen sclerosus” in medical literature.',
	),
	array(
		'q' => 'What does BXO look like?',
		'a' => 'It can cause white or pale areas, a tight white ring, thickening, loss of elasticity, cracking and scarring of the foreskin.',
	),
	array(
		'q' => 'Does BXO cause phimosis?',
		'a' => 'Yes. Scarring caused by BXO can narrow the foreskin opening and cause pathological phimosis.',
	),
	array(
		'q' => 'Can children get BXO?',
		'a' => 'Yes. BXO can affect boys as well as adult men.',
	),
	array(
		'q' => 'Is BXO an STI?',
		'a' => 'No. BXO is not a sexually transmitted infection and is not contagious.',
	),
	array(
		'q' => 'Is BXO caused by poor hygiene?',
		'a' => 'No. It is not simply caused by poor personal hygiene.',
	),
	array(
		'q' => 'What treatment does Beverley Clinic offer for BXO?',
		'a' => 'For BXO affecting the foreskin, the treatment we provide is circumcision.',
	),
	array(
		'q' => 'Why does BXO circumcision need careful planning?',
		'a' => 'The foreskin can contain a defined area of abnormal, scarred tissue. We assess where this tissue is located so that the circumcision appropriately addresses the problematic foreskin.',
	),
	array(
		'q' => 'Can BXO affect the urinary opening?',
		'a' => 'Yes. Lichen sclerosis can sometimes affect the opening through which urine passes. A weak, spraying or altered urinary stream should be reported.',
	),
	array(
		'q' => 'Does circumcision cure BXO?',
		'a' => 'Circumcision removes the affected foreskin and can successfully treat disease limited to the foreskin. BXO can sometimes also affect the glans or urinary opening, so persistent abnormalities after circumcision still require appropriate medical assessment.',
	),
	array(
		'q' => 'Can I have BXO circumcision under local anaesthetic?',
		'a' => 'Suitable adults at Beverley Clinic are normally circumcised under local anaesthetic.',
	),
	array(
		'q' => 'When can I have sex after circumcision?',
		'a' => 'Our clinic generally advises avoiding sex and masturbation for at least two weeks and until the wound is sufficiently healed. If healing is incomplete, you should wait longer.',
	),
);

$blocks[] = cil_dyn_block(
	'cil/faq',
	array(
		'heading' => 'Frequently asked questions about BXO',
		'items'   => $faq_items,
	)
);

// 15. Book a consultation section with address & CTAs (Split layout)
$consultation_left = array(
	cil_bxo_h( 'Book a consultation for BXO circumcision in London', 2 ),
	cil_bxo_p( 'If you have a white, scarred or progressively tightening foreskin and are considering circumcision, contact Beverley Clinic.' ),
	cil_bxo_p( 'Tell us if you have been diagnosed with BXO / lichen sclerosis or believe that you may have it.' ),
	cil_bxo_p( 'We will examine the foreskin and plan the circumcision according to the extent of the scarring and your individual anatomy.' ),
	cil_bxo_p( '<strong>Beverley Clinic</strong><br>78 Beverley Drive<br>Edgware<br>North-West London<br>HA8 5NE' ),
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
				'innerHTML'    => '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $book ) . '">Book a consultation →</a></div>',
				'innerContent' => array( '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $book ) . '">Book a consultation →</a></div>' ),
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
		'formId'  => 'bxo',
		'subject' => 'BXO / lichen sclerosis enquiry',
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
			)
		),
	)
);

// 16. CTA Band
$blocks[] = cil_dyn_block(
	'cil/cta-band',
	array(
		'eyebrow'    => 'Talk to us',
		'title'      => 'Ask us anything before you decide',
		'text'       => 'Most people call with a question rather than to book. That is what the phone is for, and nothing is booked until you say so.',
		'ctaLabel'   => 'Book a consultation',
		'ctaUrl'     => $book,
		'phoneLabel' => 'Call 020 8951 3794',
		'phoneUrl'   => 'tel:+442089513794',
	)
);

// Serialize into full Gutenberg block markup
$content = cil_serialize_blocks( $blocks );

// Find BXO page in LocalWP
$page = get_post( 21 );
if ( ! $page || 'bxo' !== $page->post_name ) {
	$page = get_page_by_path( 'conditions/bxo', OBJECT, 'page' );
	if ( ! $page ) {
		$page = get_page_by_path( 'bxo', OBJECT, 'page' );
	}
}
if ( ! $page ) {
	fwrite( STDERR, "BXO page not found\n" );
	exit( 1 );
}

kses_remove_filters();
remove_filter( 'content_save_pre', 'convert_invalid_entities' );
remove_filter( 'content_save_pre', 'balanceTags', 50 );
$result = wp_update_post(
	wp_slash(
		array(
			'ID'           => (int) $page->ID,
			'post_title'   => 'BXO / Lichen Sclerosis in Men',
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

update_post_meta( $page->ID, '_cil_document_title', 'BXO Treatment London | Lichen Sclerosis Circumcision' );
update_post_meta( $page->ID, '_cil_meta_description', 'BXO (lichen sclerosis) can cause a white, scarred and progressively tight foreskin. Learn about symptoms, phimosis and circumcision for BXO in London.' );
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
	'slug'                => 'bxo',
	'path'                => '/conditions/bxo/',
	'local_id'            => (int) $page->ID,
	'source_modified_gmt' => $page->post_modified_gmt,
	'source_sha256'       => hash( 'sha256', $content_remote ),
	'replace_from'        => untrailingslashit( $replace_from ),
	'replace_to'          => untrailingslashit( $replace_to ),
	'content'             => $content_remote,
);
$path = cil_content_write_fixture( 'bxo', $fixture );
if ( is_wp_error( $path ) ) {
	fwrite( STDERR, $path->get_error_message() . "\n" );
	exit( 1 );
}

echo "UPDATED BXO SUCCESSFUL\n";
echo "Post ID: " . $page->ID . "\n";
echo "Content Bytes: " . strlen( $page->post_content ) . "\n";
echo "Fixture Path: " . $path . "\n";
echo "Fixture SHA256: " . $fixture['source_sha256'] . "\n";
