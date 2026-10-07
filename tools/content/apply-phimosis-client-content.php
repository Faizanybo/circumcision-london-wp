<?php
/**
 * Apply full client Phimosis content to /conditions/phimosis/ + export fixture.
 *
 * Source: Phimosis.docx
 * Implements rich Gutenberg blocks, alternating section bands, animated info-cards,
 * checklists, callouts, and split layout matching Home, Balanitis, and Adults pages.
 * Updates callback-card to "Ask us first".
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
function cil_phimosis_p( $html ) {
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
function cil_phimosis_h( $text, $level = 2 ) {
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
function cil_phimosis_list( $items ) {
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
function cil_phimosis_info_cards( $columns, $items, $level = 3 ) {
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
function cil_phimosis_section_head( $eyebrow, $heading, $lede = '', $display = 'd-2' ) {
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
$phimosis      = home_url( '/conditions/phimosis/' );
$bxo           = home_url( '/conditions/bxo/' );
$balanitis     = home_url( '/conditions/balanitis/' );
$frenuloplasty = home_url( '/procedures/frenuloplasty/' );
$paraphimosis  = home_url( '/conditions/paraphimosis/' );
$adults        = home_url( '/adults/' );
$children      = home_url( '/children/' );
$aftercare     = home_url( '/aftercare/' );
$prices        = home_url( '/prices/' );
$book          = home_url( '/book/' );

// -----------------------------------------------------------------------------
// Section 1: Intro Group & Spec Panel
// -----------------------------------------------------------------------------
$intro_paras = array(
	cil_phimosis_p( 'A non-retractable foreskin can be completely normal in babies and young boys. However, phimosis can become a medical problem when the foreskin is scarred, causes pain, repeatedly cracks or becomes inflamed, interferes with urination or causes problems during erections or sexual activity.' ),
	cil_phimosis_p( 'At Beverley Clinic in North-West London, we assess and treat children, teenagers and adults with tight foreskins and phimosis.' ),
	cil_phimosis_p( 'Treatment depends on the patient\'s age, symptoms, appearance of the foreskin and the underlying cause. Circumcision can provide a definitive surgical treatment for troublesome phimosis.' ),
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
		'eyebrow'    => 'Condition · Tight foreskin',
		'title'      => 'Phimosis (Tight Foreskin) Treatment in London',
		'lede'       => 'Phimosis means that the foreskin is too tight to retract comfortably over the head of the penis (glans).',
		'reviewedBy' => cil_reviewed_by_haidar(),
		'crumbs'     => array(
			array(
				'label' => 'Reasons',
				'href'  => $phimosis,
			),
			array(
				'label' => 'Phimosis',
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
						'eyebrow' => 'At a glance',
						'rows'    => array(
							array( 'k' => 'Who', 'v' => 'Babies, children, teenagers and adults' ),
							array( 'k' => 'Common causes', 'v' => 'Normal development, scarring, BXO, balanitis' ),
							array( 'k' => 'Consultation', 'v' => 'Clinical assessment before procedure' ),
							array( 'k' => 'Anaesthetic', 'v' => 'Local anaesthetic for adults' ),
							array( 'k' => 'Technique', 'v' => 'Forceps-guided with thermal cautery' ),
							array( 'k' => 'Adult price', 'v' => 'Medical / foreskin problem: £1,080' ),
						),
						'note'    => $spec_note,
					)
				),
			)
		),
	)
);

// 3. What is phimosis? (band: bg-card edge)
$what_is_body = array(
	cil_phimosis_p( 'When the foreskin is sufficiently mobile, it can be gently pulled backwards to expose the glans.' ),
	cil_phimosis_p( 'Phimosis is when the opening of the foreskin is too tight to retract comfortably over the glans.' ),
	cil_phimosis_p( 'There is an important difference, however, between a young child\'s foreskin that has not naturally become retractable yet and a foreskin that has become abnormally tight because of scarring or disease.' ),
	cil_phimosis_p( 'This distinction is important because the two situations should not automatically be treated in the same way.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'what-is-phimosis',
	),
	array(
		cil_phimosis_section_head(
			'Understanding phimosis',
			'What is phimosis?',
			'The foreskin is the fold of skin covering the head of the penis.'
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

// 4. Is a non-retractable foreskin normal in babies and children? (band: '')
$babies_body = array(
	cil_phimosis_p( 'The foreskin is naturally attached to the glans during early childhood and gradually separates as the child grows. Some boys become fully retractable relatively early, while for others this process takes considerably longer.' ),
	cil_phimosis_p( 'Parents should not forcibly retract a child\'s foreskin simply because it does not pull back.' ),
	cil_phimosis_p( 'Forceful retraction can cause pain, small tears and subsequent scarring.' ),
	cil_phimosis_p( 'A child\'s foreskin therefore needs to be assessed in the context of his age, symptoms and the appearance of the foreskin, rather than deciding that he has phimosis simply because it cannot yet be pulled back.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'babies-children',
	),
	array(
		cil_phimosis_section_head(
			'Childhood development',
			'Is a non-retractable foreskin normal in babies and children?',
			'Yes. It is normal for the foreskin of a baby or young boy not to retract fully.'
		),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'body-text' ),
			'innerBlocks'  => $babies_body,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group body-text">' ),
				array_fill( 0, count( $babies_body ), null ),
				array( '</div>' )
			),
		),
	)
);

// 5. Physiological phimosis versus pathological phimosis (band: bg-card edge)
$phys_path_cards = array(
	array(
		'title' => 'Physiological phimosis',
		'body'  => '<p>Physiological phimosis describes the normal lack of foreskin retractability seen in babies and younger boys.</p><p style="margin-top:10px">The foreskin has not yet fully separated or loosened enough to retract.</p><p style="margin-top:10px">If the child has no significant symptoms and the foreskin looks healthy, circumcision may not be required unless doing it for religious or cultural reasons.</p>',
	),
	array(
		'title' => 'Pathological phimosis',
		'body'  => '<p>Pathological phimosis means that the foreskin has become abnormally tight, often because of scarring, repeated inflammation or a skin condition such as BXO / lichen sclerosis.</p><p style="margin-top:10px">The opening may appear narrowed, thickened, pale or scarred. This can happen in children and adults.</p><p style="margin-top:10px">A patient may previously have been able to retract his foreskin and then gradually lose the ability to do so.</p><p style="margin-top:10px">Pathological phimosis is more likely to require a circumcision.</p>',
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'physiological-pathological',
	),
	array(
		cil_phimosis_section_head(
			'Clinical distinction',
			'Physiological phimosis versus pathological phimosis',
			'You may see the terms physiological phimosis and pathological phimosis when reading about tight foreskins.'
		),
		cil_phimosis_info_cards( 'g-2', $phys_path_cards, 3 ),
	)
);

// 6. What are the symptoms of phimosis? (band: '')
$symptoms_body = array(
	cil_phimosis_p( 'Common symptoms can include:' ),
	cil_phimosis_list( array(
		'Difficulty retracting the foreskin',
		'Inability to retract the foreskin',
		'A visibly tight ring at the opening of the foreskin',
		'Pain when trying to retract the foreskin',
		'Cracking or splitting of the foreskin',
		'Bleeding following small tears',
		'Recurrent redness or inflammation',
		'Recurrent balanitis',
		'Difficulty cleaning underneath the foreskin',
		'Ballooning of the foreskin when passing urine',
		'A weak or altered urinary stream in more severe cases',
		'Pain or tightness during erections',
		'Pain during sexual activity',
		'Difficulty using a condom comfortably',
		'The foreskin becoming progressively tighter',
		'White, pale or scarred areas of foreskin',
	) ),
	cil_phimosis_p( 'The presence of one of these symptoms does not automatically mean circumcision is necessary.' ),
	cil_phimosis_p( 'An examination helps establish why the foreskin is tight and what treatment is most appropriate.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'symptoms',
	),
	array(
		cil_phimosis_section_head(
			'Signs & symptoms',
			'What are the symptoms of phimosis?',
			'Phimosis does not cause exactly the same symptoms in every patient.'
		),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'body-text' ),
			'innerBlocks'  => $symptoms_body,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group body-text">' ),
				array_fill( 0, count( $symptoms_body ), null ),
				array( '</div>' )
			),
		),
	)
);

// 7. What causes phimosis? (band: bg-warm)
$causes_cards = array(
	array(
		'title' => 'Recurrent tearing',
		'body'  => '<p>A tight foreskin may develop small splits when it is stretched. Repeated tearing and healing can create additional scar tissue, making the opening progressively less elastic and potentially creating a cycle of:</p><p style="margin-top:8px;font-weight:600">tightness → tearing → healing → scarring → further tightness</p>',
	),
	array(
		'title' => 'BXO / lichen sclerosis',
		'body'  => '<p>BXO, also known as male genital lichen sclerosus, is an important cause of scar-related phimosis. The foreskin may become pale or white, thickened, fragile and less elastic. Where significant BXO affects the foreskin, circumcision may be recommended to remove the diseased foreskin.</p><p style="margin-top:12px"><a href="' . esc_url( $bxo ) . '" style="color:var(--blue-deep);font-weight:600">Learn more about BXO / lichen sclerosus →</a></p>',
	),
	array(
		'title' => 'Diabetes',
		'body'  => '<p>Recurrent foreskin inflammation and infection can sometimes be associated with diabetes, particularly when blood glucose is poorly controlled. An adult who develops repeated infections or new foreskin problems may therefore need assessment for an underlying medical cause rather than simply treating each episode separately.</p>',
	),
	array(
		'title' => 'Normal childhood development',
		'body'  => '<p>In younger children, inability to retract the foreskin is commonly part of normal development rather than disease.</p>',
	),
	array(
		'title' => 'Repeated inflammation or infection',
		'body'  => '<p>Repeated episodes of inflammation can make the foreskin less elastic and contribute to scarring.</p>',
	),
	array(
		'title' => 'Balanitis',
		'body'  => '<p>Repeated inflammation of the glans and foreskin can be associated with increasing foreskin tightness.</p><p style="margin-top:12px"><a href="' . esc_url( $balanitis ) . '" style="color:var(--blue-deep);font-weight:600">Learn more about recurrent balanitis →</a></p>',
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'causes',
	),
	array(
		cil_phimosis_section_head(
			'Underlying causes',
			'What causes phimosis?',
			'The cause varies according to age and individual circumstances.'
		),
		cil_phimosis_info_cards( 'g-3', $causes_cards, 3 ),
	)
);

// 8. Adult onset phimosis & Erections (band: '')
$adult_body = array(
	cil_phimosis_p( 'New or progressively worsening phimosis in an adult deserves assessment, particularly if there is visible scarring, whitening, recurrent cracking, inflammation or another change in the skin.' ),
	cil_phimosis_p( 'One possible cause is BXO / lichen sclerosus.' ),
	cil_phimosis_p( 'Other skin conditions, infection, inflammation and repeated trauma can also affect the foreskin.' ),
	cil_phimosis_p( 'If you previously had a normally retractable foreskin and it has become increasingly tight, do not assume this is simply part of ageing.' ),
	cil_phimosis_h( 'What does phimosis feel like during an erection?', 2 ),
	cil_phimosis_p( 'Some men can retract their foreskin when the penis is soft but find that it becomes tight or painful when erect.' ),
	cil_phimosis_p( 'The foreskin may feel as though there is a tight band around the penis, or it may retract partly and then become uncomfortable.' ),
	cil_phimosis_p( 'This can cause pain during masturbation or sexual intercourse and may result in small tears or bleeding.' ),
	cil_phimosis_p( 'It is important to distinguish this from a short or tight frenulum, which can produce similar pulling or discomfort but may require a different treatment.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'adult-phimosis',
	),
	array(
		cil_phimosis_section_head(
			'Adult presentation',
			'Can phimosis develop in adults?',
			'Yes. Some men have always had a relatively tight foreskin, while others develop phimosis later despite previously being able to retract normally.'
		),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'body-text' ),
			'innerBlocks'  => $adult_body,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group body-text">' ),
				array_fill( 0, count( $adult_body ), null ),
				array( '</div>' )
			),
		),
	)
);

// 9. Differential Diagnosis (Frenulum, White ring, Balanitis, Urination) (band: bg-card edge)
$differential_cards = array(
	array(
		'title' => 'Is it phimosis or a tight frenulum?',
		'body'  => '<p>Not every patient who experiences tightness during an erection has phimosis.</p><p style="margin-top:8px">The frenulum is the band of tissue on the underside of the penis connecting the foreskin to the glans. If the frenulum is unusually short or tight, it can cause pulling underneath the glans, pain during erections, downward pulling of the glans, tearing, bleeding, and difficulty comfortably retracting the foreskin.</p><p style="margin-top:8px">Some patients have both phimosis and a tight frenulum.</p><p style="margin-top:12px"><a href="' . esc_url( $frenuloplasty ) . '" style="color:var(--blue-deep);font-weight:600">Learn more about tight frenulum →</a></p>',
	),
	array(
		'title' => 'Can phimosis cause balanitis?',
		'body'  => '<p>Phimosis and balanitis can occur together.</p><p style="margin-top:8px">When a foreskin is difficult to retract, cleaning underneath it can become more difficult and moisture, urine or secretions may remain beneath the foreskin. Inflammation can then occur.</p><p style="margin-top:8px">Conversely, repeated inflammation can contribute to scarring and make an already tight foreskin tighter. Where a patient experiences recurrent balanitis together with phimosis, treating the underlying foreskin problem may be considered rather than repeatedly treating individual episodes of inflammation.</p><p style="margin-top:12px"><a href="' . esc_url( $balanitis ) . '" style="color:var(--blue-deep);font-weight:600">Learn more about balanitis →</a></p>',
	),
	array(
		'title' => 'What is the white ring around my foreskin?',
		'body'  => '<p>A pale or white tight ring around the opening of the foreskin can indicate scar tissue.</p><p style="margin-top:8px">One possible cause is BXO / lichen sclerosis.</p><p style="margin-top:8px">If you have a white, thickened or scarred foreskin—particularly if it is becoming progressively tighter, cracking or bleeding—it should be assessed.</p><p style="margin-top:12px"><a href="' . esc_url( $bxo ) . '" style="color:var(--blue-deep);font-weight:600">Learn more about BXO / lichen sclerosis →</a></p>',
	),
	array(
		'title' => 'Can phimosis cause problems passing urine?',
		'body'  => '<p>It can. A tight foreskin may sometimes cause the foreskin to balloon during urination because urine temporarily collects underneath it before passing through the narrowed opening.</p><p style="margin-top:8px">Difficulty passing urine, a very narrow urinary stream, significant straining or urinary retention requires medical assessment. If you or your child cannot pass urine at all, seek urgent medical attention.</p>',
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'related-conditions',
	),
	array(
		cil_phimosis_section_head(
			'Differential diagnosis',
			'Distinguishing phimosis from other foreskin conditions',
			'Not every tight sensation or foreskin change represents simple phimosis. Identifying associated conditions ensures appropriate treatment.'
		),
		cil_phimosis_info_cards( 'g-2', $differential_cards, 3 ),
	)
);

// 10. Is phimosis dangerous? Phimosis versus paraphimosis (band: '')
$paraphimosis_paras = array(
	cil_phimosis_p( 'There is also an important complication to understand called paraphimosis.' ),
	cil_phimosis_h( 'Phimosis versus paraphimosis', 3 ),
	cil_phimosis_p( 'The names sound similar but they describe different problems.' ),
	cil_phimosis_p( 'Phimosis means the foreskin is too tight to retract normally over the glans.' ),
	cil_phimosis_p( 'Paraphimosis occurs when a tight foreskin has been pulled backwards behind the glans and then cannot be brought forward again.' ),
	cil_phimosis_p( 'The trapped foreskin can form a constricting band and the glans may become increasingly swollen and painful.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'paraphimosis',
	),
	array(
		cil_phimosis_section_head(
			'Urgent complications',
			'Is phimosis dangerous? Phimosis versus paraphimosis',
			'Most cases of phimosis are not an emergency. However, you should seek medical assessment if the tightness is causing significant symptoms, recurrent infections, progressive scarring or urinary problems.'
		),
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $paraphimosis_paras,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $paraphimosis_paras ), null ),
						array( '</div>' )
					),
				),
				cil_dyn_block(
					'cil/callout',
					array(
						'title'  => 'Paraphimosis requires urgent treatment',
						'body'   => '<p>Paraphimosis requires urgent medical treatment.</p><p style="margin-top:10px">If your foreskin is stuck behind the head of the penis and will not return to its normal position, do not wait for a routine circumcision appointment. Seek urgent medical attention immediately at A&E.</p><p style="margin-top:14px"><a href="' . esc_url( $paraphimosis ) . '" style="color:var(--blue-deep);font-weight:600">Learn more about paraphimosis →</a></p>',
						'urgent' => true,
						'level'  => 3,
					)
				),
			)
		),
	)
);

// 11. Diagnosis & Indications for Circumcision (band: bg-warm)
$diag_cards = array(
	array(
		'title' => 'How is phimosis diagnosed?',
		'body'  => '<p>Phimosis is usually diagnosed by taking a history and examining the foreskin. We want to understand:</p><ul class="cil-checklist wp-block-list" style="margin-top:8px"><li>How long the foreskin has been tight</li><li>Whether it was previously retractable</li><li>Whether the tightness is getting worse</li><li>Whether there is pain</li><li>Whether the foreskin cracks or bleeds</li><li>Whether you experience recurrent balanitis</li><li>Whether erections are painful</li><li>Whether urination is affected</li><li>Whether there are white or scarred areas</li><li>Whether previous treatment has been tried</li></ul><p style="margin-top:10px">We then examine the foreskin and penis. The examination can help distinguish normal non-retractability, scar-related phimosis, BXO, a short frenulum, buried penis or another condition.</p>',
	),
	array(
		'title' => 'When is circumcision recommended for phimosis?',
		'body'  => '<p>Circumcision may be considered when phimosis is causing significant or persistent problems and conservative treatment is unsuitable, unsuccessful or unlikely to provide a lasting solution. Reasons can include:</p><ul class="cil-checklist wp-block-list" style="margin-top:8px"><li>Significant scar-related phimosis</li><li>Recurrent cracking or splitting</li><li>Painful erections</li><li>Difficulty with sexual activity</li><li>Recurrent balanitis associated with a tight foreskin</li><li>Significant BXO / lichen sclerosus affecting the foreskin</li><li>Progressive tightening</li><li>Failure or recurrence after conservative treatment</li><li>Patient preference for a definitive surgical treatment</li></ul><p style="margin-top:10px">Circumcision removes the foreskin and therefore removes the tight foreskin ring responsible for phimosis.</p>',
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'diagnosis-indications',
	),
	array(
		cil_phimosis_section_head(
			'Assessment & recommendations',
			'Diagnosis and indications for circumcision',
			'A careful clinical assessment determines why the foreskin is tight and whether surgical treatment is indicated.'
		),
		cil_phimosis_info_cards( 'g-2', $diag_cards, 3 ),
	)
);

// 12. Surgical Approach, Style & Adult Procedure (band: '')
$approach_body = array(
	cil_phimosis_p( 'This is important because the aim is not simply to remove some foreskin for cosmetic reasons. The procedure is being performed to treat a specific medical problem.' ),
	cil_phimosis_p( 'Before circumcision we examine:' ),
	cil_phimosis_list( array(
		'Where the tight band is located',
		'How much foreskin is affected',
		'Whether there is visible scarring',
		'Whether BXO is suspected',
		'The amount and mobility of penile skin',
		'The frenulum',
		'The position of the penis and surrounding tissue',
		'Whether there has been previous surgery',
	) ),
	cil_phimosis_p( 'We then plan the circumcision so that the problematic tight area is appropriately treated while retaining enough healthy mobile skin for comfortable healing and erections.' ),
);

$style_cards = array(
	array(
		'title' => 'Why is the circumcision style important in phimosis?',
		'body'  => '<p>Not every circumcision removes skin from exactly the same position.</p><p style="margin-top:8px">When circumcision is being performed purely for personal or religious reasons, there may be more flexibility regarding the final distribution of inner and outer foreskin. With scar-related phimosis, the priority is different.</p><p style="margin-top:8px">The tight or diseased foreskin needs to be identified and adequately treated. Leaving a significant part of the abnormal tight ring behind may fail to address the original problem. This is why we assess the foreskin before deciding exactly how the circumcision should be performed.</p>',
	),
	array(
		'title' => 'What happens during circumcision for adult phimosis?',
		'body'  => '<p>For suitable adults at Beverley Clinic, circumcision is normally performed under local anaesthetic. You remain awake during the procedure.</p><p style="margin-top:8px">We commonly use a forceps-guided circumcision technique with thermal cautery. The foreskin is carefully positioned according to the anatomy and the location of the tight or scarred tissue. The problematic foreskin is removed and thermal cautery helps control bleeding.</p><p style="margin-top:8px">Depending on the wound, closure may involve dissolvable stitches, medical skin glue, a combination of both, or occasionally neither. You receive detailed aftercare instructions before leaving.</p><p style="margin-top:12px"><a href="' . esc_url( $adults ) . '" style="color:var(--blue-deep);font-weight:600">Learn more about adult circumcision →</a></p>',
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'surgical-approach',
	),
	array(
		cil_phimosis_section_head(
			'Surgical planning',
			'Our approach to circumcision for phimosis',
			'Circumcision for phimosis requires particular attention to the actual tight and scarred area of foreskin.'
		),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'body-text' ),
			'innerBlocks'  => $approach_body,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group body-text">' ),
				array_fill( 0, count( $approach_body ), null ),
				array( '</div>' )
			),
		),
		cil_phimosis_info_cards( 'g-2', $style_cards, 3 ),
	)
);

// 13. Tight Band, Cure Permanence & Co-existing Conditions (band: bg-card edge)
$outcomes_cards = array(
	array(
		'title' => 'Will circumcision permanently cure phimosis?',
		'body'  => '<p>If the phimosis is caused by the foreskin itself, complete circumcision removes the foreskin and therefore removes the tight foreskin opening.</p><p style="margin-top:8px">This makes circumcision a definitive surgical treatment for foreskin-related phimosis. However, circumcision does not mean that every possible penile symptom is automatically cured. For example, if discomfort is being caused by another condition, that condition needs to be recognised separately. This is why diagnosis before treatment matters.</p>',
	),
	array(
		'title' => 'What happens to the tight band during circumcision?',
		'body'  => '<p>When circumcision is being performed specifically to treat phimosis, we identify the tight or scarred foreskin during assessment and procedure planning.</p><p style="margin-top:8px">The intention is to remove the problematic foreskin rather than leave the constricting band behind. This can make the planning different from a straightforward circumcision performed where the foreskin is completely healthy.</p>',
	),
	array(
		'title' => 'What if I also have a tight frenulum?',
		'body'  => '<p>We assess the frenulum as part of the examination.</p><p style="margin-top:8px">If the frenulum is also short, scarred or causing symptoms, we can discuss whether it should be treated at the same time.</p><p style="margin-top:12px"><a href="' . esc_url( $frenuloplasty ) . '" style="color:var(--blue-deep);font-weight:600">Learn more about frenuloplasty →</a></p>',
	),
	array(
		'title' => 'What if I have BXO as well as phimosis?',
		'body'  => '<p>BXO / lichen sclerosus can cause significant scarring and loss of elasticity in the foreskin. Where BXO is suspected, this influences how we think about treatment because simply stretching a diseased, scarred foreskin may not provide an appropriate long-term solution.</p><p style="margin-top:8px">Circumcision is commonly considered where BXO has significantly affected the foreskin. BXO can occasionally affect areas beyond the foreskin, so further medical follow-up may sometimes be required even after circumcision.</p><p style="margin-top:12px"><a href="' . esc_url( $bxo ) . '" style="color:var(--blue-deep);font-weight:600">Learn more about BXO / lichen sclerosus →</a></p>',
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'tight-band-cure',
	),
	array(
		cil_phimosis_section_head(
			'Treatment outcomes',
			'Tight bands, cure permanence and co-existing conditions',
			'Understanding how circumcision resolves the tight ring and how related anatomical concerns are managed.'
		),
		cil_phimosis_info_cards( 'g-2', $outcomes_cards, 3 ),
	)
);

// 14. Pain, Recovery, Work and Sex (band: '')
$recovery_cards = array(
	array(
		'title' => 'Does circumcision for phimosis hurt?',
		'body'  => '<p>Local anaesthetic is used to numb the penis before circumcision at our clinic.</p><p style="margin-top:8px">You may still be aware of pressure, touch or movement during a procedure even when the area is adequately anaesthetised.</p><p style="margin-top:8px">Some soreness and discomfort are expected after the anaesthetic wears off because circumcision creates a surgical wound. We provide aftercare information explaining appropriate pain relief and what to expect during recovery.</p>',
	),
	array(
		'title' => 'When can I have sex after circumcision for phimosis?',
		'body'  => '<p>Sexual intercourse and masturbation should be avoided until the circumcision wound has healed sufficiently.</p><p style="margin-top:8px">As a general guide, this means at least 2 weeks and until the wound is fully healed, although some patients require longer.</p><p style="margin-top:8px">Returning to sexual activity too early can place tension and friction on the healing circumcision line and cause pain, bleeding or wound disruption.</p>',
	),
	array(
		'title' => 'When can I return to work?',
		'body'  => '<p>This depends on your occupation and individual recovery.</p><p style="margin-top:8px">Patients with desk-based jobs may be able to return sooner than those doing heavy manual work, prolonged physical activity or jobs involving significant movement.</p><p style="margin-top:8px">We recommend allowing yourself appropriate recovery time rather than arranging demanding work immediately after surgery.</p>',
	),
	array(
		'title' => 'What is recovery like after circumcision for phimosis?',
		'body'  => '<p>The early appearance is not the final result. You can expect some swelling, bruising, tenderness and increased sensitivity of the glans during the initial healing period.</p><p style="margin-top:8px">If the glans was rarely or never exposed before circumcision because of severe phimosis, it may initially feel particularly sensitive after the foreskin has been removed. This usually becomes easier to tolerate as you become accustomed to the glans being exposed. The wound continues to heal over several weeks and the circumcision scar continues to change after the surface wound has closed.</p><p style="margin-top:12px"><a href="' . esc_url( $aftercare ) . '" style="color:var(--blue-deep);font-weight:600">Read our adult circumcision aftercare guide →</a></p>',
	),
	array(
		'title' => 'Will sex feel different after circumcision?',
		'body'  => '<p>Circumcision permanently removes the foreskin, so the penis will look and feel different afterwards.</p><p style="margin-top:8px">The glans remains exposed and the penile skin no longer moves in exactly the same way as it did when the foreskin was present.</p><p style="margin-top:8px">Individual experiences of sexual sensation vary.</p><p style="margin-top:8px">If your current sexual activity is painful because a scarred foreskin is being stretched during erections or intercourse, removing that tight foreskin removes that particular mechanical source of tightness.</p><p style="margin-top:8px">However, we do not promise a particular change in sexual pleasure or sensitivity.</p>',
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'recovery-work-sex',
	),
	array(
		cil_phimosis_section_head(
			'Recovery & daily life',
			'Recovery, return to work and sexual activity',
			'What to expect during the healing period following circumcision for phimosis.'
		),
		cil_phimosis_info_cards( 'g-3', $recovery_cards, 3 ),
	)
);

// 15. Risks and Urgent Situations (band: bg-warm)
$risks_paras = array(
	cil_phimosis_h( 'What are the risks of circumcision for phimosis?', 3 ),
	cil_phimosis_p( 'Circumcision is a surgical procedure and complications can occur. Potential problems include:' ),
	cil_phimosis_list( array(
		'Bleeding',
		'Infection',
		'Swelling',
		'Bruising',
		'Wound separation',
		'Delayed healing',
		'Scarring',
		'Altered sensation',
		'Cosmetic dissatisfaction',
		'Need for further treatment',
	) ),
	cil_phimosis_p( 'Your penis will permanently look and feel different after removal of the foreskin.' ),
	cil_phimosis_p( 'We discuss the relevant risks before treatment so that you can make an informed decision.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'risks-urgent',
	),
	array(
		cil_phimosis_section_head(
			'Safety & considerations',
			'What are the risks and when should phimosis be treated urgently?',
			'Circumcision is a surgical procedure and complications can occur. We discuss all relevant risks before treatment so that you can make an informed decision.'
		),
		cil_split_block(
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => $risks_paras,
					'innerHTML'    => '',
					'innerContent' => array_merge(
						array( '<div class="wp-block-group body-text">' ),
						array_fill( 0, count( $risks_paras ), null ),
						array( '</div>' )
					),
				),
				cil_dyn_block(
					'cil/callout',
					array(
						'title'  => 'When should phimosis be treated urgently?',
						'body'   => '<p>Most phimosis is not an emergency. However, seek urgent medical attention if:</p><ul class="cil-checklist wp-block-list" style="margin-top:10px"><li>You cannot pass urine</li><li>Your foreskin has been retracted and is stuck behind the glans</li><li>The glans becomes significantly swollen or discoloured</li><li>You have severe or rapidly worsening pain</li><li>You are seriously unwell with an infection</li><li>There is another rapidly worsening penile problem</li></ul><p style="margin-top:12px">A foreskin stuck behind the glans may be paraphimosis, which requires urgent treatment. Do not wait for a routine clinic appointment if you believe you have a medical emergency.</p>',
						'urgent' => true,
						'level'  => 3,
					)
				),
			)
		),
	)
);

// 16. Phimosis Treatment by Age & Cost (band: '')
$age_pricing_cards = array(
	array(
		'title' => 'Phimosis treatment for children',
		'body'  => '<p>A non-retractable foreskin in a young child is not automatically abnormal. We consider the child\'s age, symptoms and appearance of the foreskin before recommending treatment.</p><p style="margin-top:8px">Circumcision may be considered where there is genuine pathological phimosis, significant scarring, BXO or another appropriate medical indication. We do not recommend circumcision simply because a young child\'s foreskin has not naturally become retractable yet.</p><p style="margin-top:12px"><a href="' . esc_url( $children ) . '" style="color:var(--blue-deep);font-weight:600">Learn more about circumcision for children →</a></p>',
	),
	array(
		'title' => 'Phimosis treatment for teenagers',
		'body'  => '<p>Teenagers can develop troublesome phimosis that causes difficulty retracting the foreskin, recurrent cracking, hygiene problems or discomfort during erections.</p><p style="margin-top:8px">We recognise that discussing a foreskin problem can feel embarrassing for a teenager. Consultations are handled sensitively and we explain the problem and treatment in an age-appropriate way. Where circumcision is appropriate, the technique and recovery are explained before treatment.</p>',
	),
	array(
		'title' => 'Phimosis treatment for adults in London',
		'body'  => '<p>We regularly assess adult men with tight foreskins at Beverley Clinic in Edgware, North-West London. Some patients have lived with phimosis for many years. Others have developed a tight foreskin relatively recently.</p><p style="margin-top:8px">We assess the cause and explain whether conservative treatment, treatment of the frenulum, circumcision or another approach should be considered. If circumcision is appropriate, suitable adults are normally treated under local anaesthetic at the clinic.</p><p style="margin-top:12px"><a href="' . esc_url( $adults ) . '" style="color:var(--blue-deep);font-weight:600">Learn more about adult circumcision →</a></p>',
	),
	array(
		'title' => 'How much does circumcision for phimosis cost?',
		'body'  => '<p>An adult circumcision involving a medical or foreskin problem, with or without frenulum treatment, is currently £1,080 at our London clinic.</p><p style="margin-top:8px">Prices for children with medical foreskin problems depend on the individual case. If you are unsure which price applies, contact us before booking.</p><p style="margin-top:12px"><a href="' . esc_url( $prices ) . '" style="color:var(--blue-deep);font-weight:600">View our circumcision prices →</a></p>',
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'ages-pricing',
	),
	array(
		cil_phimosis_section_head(
			'Care across all ages',
			'Phimosis treatment by age and clinic pricing',
			'We assess and treat patients of all ages, adapting our clinical approach and technique to the individual.'
		),
		cil_phimosis_info_cards( 'g-2', $age_pricing_cards, 3 ),
	)
);

// 17. Why choose Beverley Clinic for phimosis circumcision? (band: bg-card edge)
$usp_cards = array(
	array(
		'title' => 'Circumcision is a core part of our work',
		'body'  => '<p>Circumcision is part of our day-to-day clinical practice rather than an occasional procedure. We regularly assess foreskin problems as well as performing circumcisions for medical, personal, religious and cultural reasons.</p>',
	),
	array(
		'title' => 'We recognise that not every tight foreskin is the same',
		'body'  => '<p>Normal childhood non-retractability, scar-related phimosis, BXO and a tight frenulum can produce superficially similar symptoms but may require different approaches. We assess the patient before deciding which treatment or circumcision technique is appropriate.</p>',
	),
	array(
		'title' => 'Circumcision is tailored to the medical problem',
		'body'  => '<p>When circumcision is performed for phimosis, we pay particular attention to the location and extent of the tight or scarred foreskin. The aim is to treat the underlying problem rather than simply perform the same standard circumcision on every patient.</p>',
	),
	array(
		'title' => 'All ages',
		'body'  => '<p>We treat babies, children, teenagers and adults, allowing us to understand how foreskin anatomy and circumcision techniques differ across age groups.</p>',
	),
	array(
		'title' => 'Follow-up',
		'body'  => '<p>Patients receive preparation information before treatment, detailed written aftercare afterwards and follow-up support during healing.</p>',
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'why-choose-us',
	),
	array(
		cil_phimosis_section_head(
			'Why choose us',
			'Why choose Beverley Clinic for phimosis circumcision?',
			'Dedicated circumcision expertise in North-West London with a patient-centred medical approach.'
		),
		cil_phimosis_info_cards( 'g-3', $usp_cards, 3 ),
	)
);

// 18. Frequently Asked Questions (cil/faq)
$blocks[] = cil_dyn_block(
	'cil/faq',
	array(
		'heading' => 'Frequently asked questions about phimosis',
		'items'   => array(
			array(
				'q' => 'What is phimosis?',
				'a' => 'Phimosis is a foreskin that is too tight to retract comfortably over the head of the penis.',
			),
			array(
				'q' => 'How do I know whether my foreskin is too tight?',
				'a' => 'Symptoms can include difficulty retracting, pain, cracking, a visible tight ring, recurrent inflammation, painful erections or problems during sex.',
			),
			array(
				'q' => 'Can phimosis develop later in life?',
				'a' => 'Yes. An adult who previously had a retractable foreskin can develop increasing tightness because of inflammation, scarring or a skin condition such as BXO / lichen sclerosus.',
			),
			array(
				'q' => 'Can phimosis go away by itself?',
				'a' => 'Normal childhood non-retractability often improves naturally with development. Pathological or scar-related phimosis is different and may require treatment.',
			),
			array(
				'q' => 'Can steroid cream cure phimosis?',
				'a' => 'Topical steroid treatment can help some patients, particularly where significant scarring is not present. Results depend on the underlying cause and recurrence can occur. From our experience, it is often a short term fix and the definitive treatment is a circumcision.',
			),
			array(
				'q' => 'Is circumcision a permanent treatment for phimosis?',
				'a' => 'Complete circumcision removes the foreskin and therefore removes foreskin-related phimosis.',
			),
			array(
				'q' => 'Can phimosis cause painful erections?',
				'a' => 'Yes. A tight foreskin can become painful when stretched during an erection and may crack or tear.',
			),
			array(
				'q' => 'Can phimosis affect sex?',
				'a' => 'Yes. Significant phimosis can cause discomfort during intercourse or masturbation and can sometimes lead to tearing or bleeding.',
			),
			array(
				'q' => 'Is a tight frenulum the same as phimosis?',
				'a' => 'No. A tight frenulum affects the band of tissue underneath the glans. It can cause symptoms similar to phimosis but may be treated differently.',
			),
			array(
				'q' => 'What is the difference between phimosis and paraphimosis?',
				'a' => 'Phimosis is a foreskin that is too tight to retract. Paraphimosis occurs when a retracted foreskin becomes trapped behind the glans and cannot be returned forward. Paraphimosis requires urgent medical attention.',
			),
			array(
				'q' => 'What is BXO?',
				'a' => 'BXO, also called male genital lichen sclerosis, is a chronic inflammatory skin condition that can cause whitening, scarring and tightening of the foreskin.',
			),
			array(
				'q' => 'How long does circumcision take to heal?',
				'a' => 'Healing varies between patients. The wound heals progressively between 2-6 weeks and the scar continues to mature afterwards.',
			),
			array(
				'q' => 'When can I have sex after circumcision?',
				'a' => 'As a general guide, avoid sexual intercourse and masturbation for at least 2 weeks and until the wound is fully healed. Some patients require longer.',
			),
			array(
				'q' => 'Can I speak to someone before booking?',
				'a' => 'Yes. If you are unsure whether you have phimosis, BXO, a frenulum problem or another foreskin condition, contact us before booking. You do not need to diagnose the problem yourself.',
			),
		),
	)
);

// 19. Consultation Split Section (Ask us first callback + clinical consultation)
$consultation_paras = array(
	cil_phimosis_h( 'Phimosis assessment and circumcision in London', 2 ),
	cil_phimosis_p( 'If you experience pain, cracking, difficulty retracting or uncomfortable erections and are considering treatment, contact Beverley Clinic.' ),
	cil_phimosis_p( 'We can assess your foreskin and determine whether there is associated scarring, lichen sclerosus / BXO, a tight frenulum or another condition that needs to be considered when planning your treatment.' ),
	cil_phimosis_p( '<a class="btn" href="' . esc_url( $book ) . '">Book a consultation →</a>' ),
);

$consultation_group = array(
	'blockName'    => 'core/group',
	'attrs'        => array( 'className' => 'body-text' ),
	'innerBlocks'  => $consultation_paras,
	'innerHTML'    => '',
	'innerContent' => array_merge(
		array( '<div class="wp-block-group body-text">' ),
		array_fill( 0, count( $consultation_paras ), null ),
		array( '</div>' )
	),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'consultation',
	),
	array(
		cil_split_block(
			array(
				cil_dyn_block(
					'cil/callback-card',
					array(
						'eyebrow' => 'Request a call back',
						'title'   => 'Ask us first',
						'formId'  => 'phimosis',
						'subject' => 'phimosis enquiry',
						'urgent'  => false,
					)
				),
				$consultation_group,
			)
		),
	)
);

// 20. CTA Band
$blocks[] = cil_dyn_block(
	'cil/cta-band',
	array(
		'title'      => 'Book a consultation',
		'text'       => 'You can request a consultation or book by contacting the clinic. Tell us about any medical conditions, medication, allergies or bleeding problems when you book.',
		'eyebrow'    => 'Talk to us',
		'ctaLabel'   => 'Book a consultation',
		'ctaUrl'     => $book,
		'phoneLabel' => 'Call 020 8951 3794',
		'phoneUrl'   => 'tel:+442089513794',
		'cardTitle'  => 'Would you rather write than speak?',
		'cardHtml'   => '<p style="margin-top:10px">A phone call asks you to be fluent and composed in the moment. <a href="https://wa.me/447886779958" rel="noopener" target="_blank" data-track="whatsapp-cta" style="color:var(--blue-deep)">WhatsApp</a> does not. You can take your time, translate, and forward the answer to whoever else in the family needs to see it.</p><p style="margin-top:12px">We reply during opening hours, and we are used to the questions people feel awkward asking out loud.</p>',
	)
);

// 21. Circumcision Across All Ages
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
				'heading' => 'Circumcision across all ages',
				'lede'    => '',
				'display' => 'd-2',
			)
		),
		cil_dyn_block(
			'cil/group-cards',
			array(
				'level'  => 3,
				'banded' => false,
			)
		),
	)
);

// Serialize into full Gutenberg block markup
$content = cil_serialize_blocks( $blocks );

// Find Phimosis page in LocalWP
$page = get_post( 19 );
if ( ! $page || 'phimosis' !== $page->post_name ) {
	$page = get_page_by_path( 'conditions/phimosis', OBJECT, 'page' );
	if ( ! $page ) {
		$page = get_page_by_path( 'phimosis', OBJECT, 'page' );
	}
}
if ( ! $page ) {
	fwrite( STDERR, "Phimosis page not found\n" );
	exit( 1 );
}

kses_remove_filters();
remove_filter( 'content_save_pre', 'convert_invalid_entities' );
remove_filter( 'content_save_pre', 'balanceTags', 50 );
$result = wp_update_post(
	wp_slash(
		array(
			'ID'           => (int) $page->ID,
			'post_title'   => 'Phimosis (Tight Foreskin) Treatment in London',
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

update_post_meta( $page->ID, '_cil_document_title', 'Phimosis Treatment London | Tight Foreskin & Circumcision' );
update_post_meta( $page->ID, '_cil_meta_description', 'Phimosis is a tight foreskin that can cause pain, cracking, infections or painful erections. Learn about steroid treatment, BXO and circumcision for phimosis in London.' );
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
	'slug'                => 'phimosis',
	'path'                => '/conditions/phimosis/',
	'local_id'            => (int) $page->ID,
	'source_modified_gmt' => $page->post_modified_gmt,
	'source_sha256'       => hash( 'sha256', $content_remote ),
	'replace_from'        => untrailingslashit( $replace_from ),
	'replace_to'          => untrailingslashit( $replace_to ),
	'content'             => $content_remote,
);
$path = cil_content_write_fixture( 'phimosis', $fixture );
if ( is_wp_error( $path ) ) {
	fwrite( STDERR, $path->get_error_message() . "\n" );
	exit( 1 );
}

echo "UPDATED PHIMOSIS SUCCESSFUL\n";
echo "Post ID: " . $page->ID . "\n";
echo "Content Bytes: " . strlen( $page->post_content ) . "\n";
echo "Fixture Path: " . $path . "\n";
echo "Fixture SHA256: " . $fixture['source_sha256'] . "\n";
