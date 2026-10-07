<?php
/**
 * Apply full client Balanitis content to /conditions/balanitis/ + export fixture.
 *
 * Source: Balanitis.docx
 * Implements rich Gutenberg blocks, alternating section bands, animated info-cards,
 * synchronized box grids, and hover effects matching Home, Children, and Adults pages.
 * Updates callback-card to "Ask us first" matching babies & children page.
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
function cil_balanitis_p( $html ) {
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
function cil_balanitis_h( $text, $level = 2 ) {
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
function cil_balanitis_list( $items ) {
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
function cil_balanitis_info_cards( $columns, $items, $level = 3 ) {
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
function cil_balanitis_section_head( $eyebrow, $heading, $lede = '', $display = 'd-2' ) {
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
$frenuloplasty = home_url( '/procedures/frenuloplasty/' );
$adults        = home_url( '/adults/' );
$children      = home_url( '/children/' );
$aftercare     = home_url( '/aftercare/' );
$prices        = home_url( '/prices/' );
$book          = home_url( '/book/' );

// -----------------------------------------------------------------------------
// Section 1: Intro Group & Spec Panel
// -----------------------------------------------------------------------------
$intro_paras = array(
	cil_balanitis_p( 'Symptoms can include redness, soreness, itching, swelling, discharge, an unpleasant smell and discomfort around the head of the penis or foreskin.' ),
	cil_balanitis_p( 'A single episode of balanitis does not necessarily mean that circumcision is required unless you are doing a circumcision for religious/ cultural or personal reasons. However, some men and boys experience recurrent balanitis, where inflammation repeatedly returns.' ),
	cil_balanitis_p( 'When recurrent balanitis is associated with a tight, difficult-to-retract or scarred foreskin, circumcision may provide a definitive solution by permanently removing the foreskin.' ),
	cil_balanitis_p( 'At Beverley Clinic in North-West London, we assess and circumcise children, teenagers and adults with recurrent balanitis and associated foreskin problems.' ),
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

$spec_note = 'Adult circumcision with a medical or foreskin problem is £1,080. Children assessed individually. See our <a href="' . esc_url( $prices ) . '" style="color:var(--blue-deep)">price list</a>.';

// -----------------------------------------------------------------------------
// Assembly of Blocks
// -----------------------------------------------------------------------------
$blocks = array();

// 1. Hero Page Head
$blocks[] = cil_dyn_block(
	'cil/page-head',
	array(
		'eyebrow'    => 'Condition · Inflammation of the glans',
		'title'      => 'Recurrent Balanitis and Circumcision in London',
		'lede'       => 'Balanitis is inflammation of the head of the penis (glans). When the foreskin is also inflamed, this is sometimes called balanoposthitis.',
		'reviewedBy' => cil_reviewed_by_haidar(),
		'crumbs'     => array(
			array(
				'label' => 'Reasons',
				'href'  => $phimosis,
			),
			array(
				'label' => 'Balanitis',
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
							array( 'k' => 'Who', 'v' => 'Children, teenagers and adults' ),
							array( 'k' => 'Factors', 'v' => 'Tight foreskin, phimosis, BXO, diabetes' ),
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

// 3. Symptoms & Nature of Balanitis (band: bg-card edge)
$symptoms_body = array(
	cil_balanitis_p( 'Symptoms can include:' ),
	cil_balanitis_list( array(
		'Redness or inflammation of the glans',
		'Swelling',
		'Soreness or tenderness',
		'Itching or irritation',
		'Pain or discomfort',
		'Discharge underneath the foreskin',
		'An unpleasant smell',
		'Difficulty retracting the foreskin',
		'Pain when passing urine',
		'Bleeding or cracking around the foreskin',
		'Increased foreskin tightness',
		'Repeated episodes of inflammation',
	) ),
	cil_balanitis_p( 'The appearance can vary according to the underlying cause and the patient\'s skin tone.' ),
	cil_balanitis_p( 'If you repeatedly experience these symptoms, particularly together with increasing foreskin tightness, an assessment is important.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'symptoms',
	),
	array(
		cil_balanitis_section_head( 'Symptoms & pattern', 'What are the symptoms of balanitis?' ),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'body-text', 'layout' => array( 'type' => 'constrained' ) ),
			'innerBlocks'  => $symptoms_body,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group body-text is-layout-constrained wp-block-group-is-layout-constrained">' ),
				array_fill( 0, count( $symptoms_body ), null ),
				array( '</div>' )
			),
		),
		cil_balanitis_info_cards(
			'g-2',
			array(
				array(
					'eyebrow' => 'Recurring cycle',
					'title'   => 'What is recurrent balanitis?',
					'html'    => '<p>Recurrent balanitis means that episodes of inflammation repeatedly return after apparently improving.</p><p>A patient may experience redness and soreness, recover, and then develop another episode weeks or months later.</p><p>For some patients this becomes a frustrating cycle.</p><p>Repeated inflammation can also occur together with phimosis, where the foreskin becomes increasingly difficult to retract.</p><p>When recurrent balanitis is associated with a troublesome foreskin, circumcision may be considered as a definitive surgical treatment.</p>',
				),
				array(
					'eyebrow' => 'Underlying factors',
					'title'   => 'Why does balanitis keep coming back?',
					'html'    => '<p>There is not one cause of recurrent balanitis.</p><p>Factors can include:</p><ul><li>A tight or difficult-to-retract foreskin</li><li>Difficulty cleaning underneath the foreskin</li><li>Repeated irritation</li><li>Infection</li><li>Moisture trapped beneath the foreskin</li><li>Phimosis</li><li>Foreskin scarring</li><li>Lichen sclerosis / BXO</li><li>Diabetes or poorly controlled blood glucose</li><li>Other inflammatory or dermatological conditions</li></ul><p style="margin-top:12px">Sometimes several factors are present at the same time.</p><p>This is why somebody experiencing repeated episodes should not assume that every recurrence has exactly the same cause.</p>',
				),
			)
		),
	)
);

// 4. Foreskin Complications: Connection with tight foreskin, phimosis & BXO (band: "")
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'foreskin-complications',
	),
	array(
		cil_balanitis_section_head( 'Foreskin complications', 'Tight foreskin, phimosis and lichen sclerosis' ),
		cil_balanitis_info_cards(
			'g-2',
			array(
				array(
					'eyebrow' => 'Vicious cycle',
					'title'   => 'What is the connection between balanitis and a tight foreskin?',
					'html'    => '<p>Balanitis and phimosis commonly occur together.</p><p>If the foreskin is tight and difficult to retract, it can be more difficult to expose and clean the glans.</p><p>At the same time, repeated inflammation can contribute to changes in the foreskin, making it less elastic and more difficult to retract.</p><p><strong>This can create a cycle:</strong></p><p>Tight foreskin → difficulty retracting and cleaning → inflammation → further tightness or scarring → recurrent inflammation</p><p>When this occurs repeatedly, treating the underlying foreskin problem becomes an important consideration.</p><p><a href="' . esc_url( $phimosis ) . '">Learn more about phimosis →</a></p>',
				),
				array(
					'eyebrow' => 'Progressive scarring',
					'title'   => 'Can balanitis cause phimosis?',
					'html'    => '<p>Repeated or significant inflammation can contribute to scarring and tightening of the foreskin.</p><p>A patient who could previously retract normally may notice that the foreskin becomes progressively tighter following repeated episodes.</p><p>There may eventually be a distinct tight ring, cracking or difficulty retracting over the glans.</p><p>If this happens, we assess both the recurrent balanitis and the resulting foreskin tightness when planning circumcision.</p>',
				),
				array(
					'eyebrow' => 'Hygiene & tightness',
					'title'   => 'Can phimosis cause balanitis?',
					'html'    => '<p>Yes, the relationship can also work in the other direction.</p><p>A significantly tight foreskin can make it difficult to expose the glans and clean underneath the foreskin.</p><p>Some patients with phimosis therefore experience repeated inflammation as well as tightness.</p><p>Where recurrent balanitis and phimosis occur together, circumcision removes the problematic foreskin as well as the tight foreskin ring.</p><p><a href="' . esc_url( $phimosis ) . '">Learn more about phimosis and tight foreskin →</a></p>',
				),
				array(
					'eyebrow' => 'Chronic condition',
					'title'   => 'Balanitis and lichen sclerosis / BXO',
					'html'    => '<p>Lichen sclerosis, also known as BXO (balanitis xerotica obliterans) when affecting the penis, can cause chronic inflammation and scarring involving the foreskin.</p><p>The foreskin may become:</p><ul><li>White or pale</li><li>Thickened</li><li>Scarred</li><li>Less elastic</li><li>Difficult to retract</li><li>Prone to cracking or splitting</li></ul><p style="margin-top:12px">The opening of the foreskin can progressively narrow and produce phimosis.</p><p>If recurrent inflammation is accompanied by whitening or significant scarring of the foreskin, it is important that this is recognised during assessment because it can affect how the circumcision should be planned.</p><p><a href="' . esc_url( $bxo ) . '">Learn more about lichen sclerosis / BXO →</a></p>',
				),
			)
		),
	)
);

// 5. Health, STIs & Lifestyle Factors (band: bg-warm)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'health-and-lifestyle',
	),
	array(
		cil_balanitis_section_head( 'Health & lifestyle', 'Diabetes, infections, sexual activity and symptoms' ),
		cil_balanitis_info_cards(
			'g-2',
			array(
				array(
					'eyebrow' => 'Medical health',
					'title'   => 'Balanitis and diabetes',
					'html'    => '<p>Recurrent balanitis can sometimes be associated with diabetes, particularly where blood glucose is poorly controlled.</p><p>This is particularly relevant when an adult develops repeated episodes without an obvious previous history.</p><p>If you have recurrent balanitis and symptoms or risk factors suggesting diabetes, you may need appropriate medical assessment through your GP.</p><p>Circumcision can address the foreskin component of recurrent balanitis, but it does not replace appropriate investigation or management of an underlying medical condition.</p>',
				),
				array(
					'eyebrow' => 'Sexual health',
					'title'   => 'Is balanitis a sexually transmitted infection?',
					'html'    => '<p>Balanitis itself is not one specific sexually transmitted infection.</p><p>However, some infections and sexually transmitted conditions can cause symptoms affecting the penis that may resemble balanitis.</p><p>If you have urethral discharge, genital sores, ulcers, unusual lesions or concerns following sexual contact, appropriate assessment through a GP or sexual health service may be required.</p><p>Do not assume that every red or sore area on the penis is caused by the foreskin.</p>',
				),
				array(
					'eyebrow' => 'Intimacy',
					'title'   => 'Can balanitis affect sex?',
					'html'    => '<p>During an active episode, the glans and foreskin may be sore, inflamed or swollen, making sexual activity uncomfortable.</p><p>If recurrent balanitis is accompanied by phimosis, the foreskin may also become painful when stretched during an erection or intercourse.</p><p>Some patients experience cracking or bleeding around a tight foreskin.</p><p>When considering circumcision, we assess whether the problem is recurrent inflammation alone or recurrent inflammation combined with tightness, scarring or another foreskin condition.</p>',
				),
				array(
					'eyebrow' => 'Discharge & odour',
					'title'   => 'Can balanitis cause a bad smell or discharge?',
					'html'    => '<p>Yes.</p><p>Inflammation underneath the foreskin can sometimes be associated with discharge and an unpleasant smell.</p><p>However, discharge can have different causes.</p><p>If discharge appears to be coming from the urethra itself rather than from underneath the foreskin, or there is a possibility of a sexually transmitted infection, appropriate sexual-health assessment may be required.</p>',
				),
			)
		),
	)
);

// 6. Surgical Treatment (band: "")
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'surgical-treatment',
	),
	array(
		cil_balanitis_section_head( 'Surgical treatment', 'Circumcision as a treatment for recurrent balanitis' ),
		cil_balanitis_info_cards(
			'g-3',
			array(
				array(
					'eyebrow' => 'Clinical rationale',
					'title'   => 'Is recurrent balanitis a reason for circumcision?',
					'html'    => '<p>Yes, significant recurrent balanitis can be a medical reason for circumcision, particularly where episodes repeatedly return and the foreskin itself is contributing to the problem.</p><p>Circumcision permanently removes the foreskin.</p><p>For a patient whose recurrent balanitis is closely associated with a tight, scarred or repeatedly inflamed foreskin, removing that foreskin removes an important site contributing to the recurring problem.</p><p>We assess the penis and foreskin before deciding how the circumcision should be performed.</p>',
				),
				array(
					'eyebrow' => 'Long-term solution',
					'title'   => 'Why can circumcision help recurrent balanitis?',
					'html'    => '<p>The space underneath the foreskin can retain moisture and secretions, particularly where the foreskin is tight and difficult to retract.</p><p>Circumcision removes the foreskin permanently and leaves the glans exposed.</p><p>This means there is no longer a foreskin covering the glans or a tight foreskin opening to retract for cleaning.</p><p>For patients whose recurrent inflammation is related to their foreskin, this can provide a long-term surgical solution.</p><p>However, circumcision does not prevent every possible inflammatory, dermatological or infectious condition that can affect the glans.</p>',
				),
				array(
					'eyebrow' => 'Realistic expectations',
					'title'   => 'Does circumcision guarantee I will never get inflammation again?',
					'html'    => '<p>No.</p><p>Circumcision removes the foreskin, so foreskin inflammation and foreskin-related tightness can no longer occur in the same way.</p><p>However, the glans and surrounding penile skin remain present and can still be affected by dermatological conditions, irritation or infection.</p><p>We therefore describe circumcision as a definitive treatment for the foreskin component of recurrent balanitis rather than promising that a patient can never experience penile inflammation again.</p>',
				),
			)
		),
	)
);

// 7. Clinical Assessment & Individual Planning (band: bg-card edge)
$approach_body = array(
	cil_balanitis_p( 'We do not treat every patient with recurrent balanitis as though the foreskin is identical.' ),
	cil_balanitis_p( 'Before circumcision we examine:' ),
	cil_balanitis_list( array(
		'Whether the foreskin retracts normally',
		'Whether there is a tight foreskin ring',
		'Whether scarring is present',
		'Whether there are white or abnormal areas suggesting lichen sclerosis / BXO',
		'Whether there is cracking or splitting',
		'Whether the frenulum is also tight or scarred',
		'Whether the penis is buried or retractile',
		'Whether there are other findings requiring medical assessment',
	) ),
	cil_balanitis_p( 'This helps us plan the circumcision according to the actual problem rather than using exactly the same approach for every patient.' ),
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'assessment',
	),
	array(
		cil_balanitis_section_head( 'Clinical assessment', 'Our approach to circumcision for recurrent balanitis' ),
		array(
			'blockName'    => 'core/group',
			'attrs'        => array( 'className' => 'body-text', 'layout' => array( 'type' => 'constrained' ) ),
			'innerBlocks'  => $approach_body,
			'innerHTML'    => '',
			'innerContent' => array_merge(
				array( '<div class="wp-block-group body-text is-layout-constrained wp-block-group-is-layout-constrained">' ),
				array_fill( 0, count( $approach_body ), null ),
				array( '</div>' )
			),
		),
		cil_balanitis_info_cards(
			'g-2',
			array(
				array(
					'eyebrow' => 'Medical history',
					'title'   => 'What happens at the assessment?',
					'html'    => '<p>We first ask about the history of your symptoms.</p><p>It is useful for us to know:</p><ul><li>How often the balanitis occurs</li><li>How long the problem has been happening</li><li>What symptoms you experience</li><li>Whether your foreskin retracts normally</li><li>Whether it has become tighter over time</li><li>Whether the foreskin cracks or bleeds</li><li>Whether erections are painful</li><li>Whether you have been diagnosed with diabetes</li><li>Whether there are white or scarred areas</li><li>Whether you have had previous penile surgery</li><li>Whether you have concerns about sexually transmitted infection</li></ul><p style="margin-top:12px">We then examine the penis and foreskin.</p><p>This helps determine whether there is associated phimosis, scarring, lichen sclerosis / BXO, a frenulum problem or another finding that affects the circumcision.</p>',
				),
				array(
					'eyebrow' => 'Acute episodes',
					'title'   => 'What if I currently have severe balanitis?',
					'html'    => '<p>If the penis is acutely and significantly inflamed, infected or swollen when you contact us, tell us before attending for circumcision.</p><p>It may not be appropriate to perform an elective circumcision during a severe acute episode.</p><p>You may first require appropriate medical assessment of the active inflammation.</p><p>Once the acute problem has settled sufficiently, circumcision can then be considered for patients experiencing recurrent episodes.</p>',
				),
			)
		),
	)
);

// 8. The Procedure & Associated Conditions (band: "")
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'procedure',
	),
	array(
		cil_balanitis_section_head( 'Surgical procedure', 'What happens during circumcision for recurrent balanitis?' ),
		cil_balanitis_info_cards(
			'g-2',
			array(
				array(
					'eyebrow' => 'Adult technique',
					'title'   => 'What happens during circumcision for recurrent balanitis?',
					'html'    => '<p>For suitable adults at Beverley Clinic, circumcision is normally performed under local anaesthetic.</p><p>You remain awake during the procedure.</p><p>We commonly use a forceps-guided circumcision technique with thermal cautery.</p><p>The foreskin is carefully assessed and positioned so that the appropriate tissue can be removed. Thermal cautery helps control bleeding during the procedure.</p><p>Depending on the individual wound, closure may involve dissolvable stitches, medical skin glue, a combination of both, or occasionally neither.</p><p>You receive detailed aftercare instructions before leaving the clinic.</p><p><a href="' . esc_url( $adults ) . '">Learn more about adult circumcision →</a></p>',
				),
				array(
					'eyebrow' => 'Associated phimosis',
					'title'   => 'What if I have phimosis as well as recurrent balanitis?',
					'html'    => '<p>This is a common combination.</p><p>Where a patient has both recurrent balanitis and phimosis, we pay particular attention to the tight or scarred area of foreskin.</p><p>The circumcision needs to adequately address the problematic tight foreskin rather than simply removing an arbitrary amount of skin.</p><p><a href="' . esc_url( $phimosis ) . '">Learn more about circumcision for phimosis →</a></p>',
				),
				array(
					'eyebrow' => 'BXO involvement',
					'title'   => 'What if I have lichen sclerosis / BXO?',
					'html'    => '<p>If the foreskin appears significantly white, scarred or affected by lichen sclerosis / BXO, this influences the planning of the circumcision.</p><p>The abnormal foreskin needs to be recognised so that the problematic tissue can be appropriately treated.</p><p>If the condition appears to involve areas beyond the foreskin, further medical follow-up may sometimes be required after circumcision.</p><p><a href="' . esc_url( $bxo ) . '">Learn more about lichen sclerosis / BXO →</a></p>',
				),
				array(
					'eyebrow' => 'Tight frenulum',
					'title'   => 'What if I have a tight frenulum as well?',
					'html'    => '<p>The frenulum is the band of tissue on the underside of the penis connecting the foreskin to the glans.</p><p>Some patients with foreskin problems also have a short, tight or scarred frenulum.</p><p>We assess this before circumcision.</p><p>If the frenulum is contributing to pain, tearing or pulling, we can discuss whether it should be treated as part of the procedure.</p><p><a href="' . esc_url( $frenuloplasty ) . '">Learn more about tight frenulum →</a></p>',
				),
			)
		),
	)
);

// 9. Age Groups: Children & Adults (band: bg-warm)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'age-groups',
	),
	array(
		cil_balanitis_section_head( 'By age group', 'Circumcision for recurrent balanitis across different ages' ),
		cil_balanitis_info_cards(
			'g-2',
			array(
				array(
					'eyebrow' => 'Children & teenagers',
					'title'   => 'Circumcision for recurrent balanitis in children',
					'html'    => '<p>Children can also experience recurrent inflammation of the foreskin and glans.</p><p>It is important to remember that a non-retractable foreskin can be completely normal in a young boy, so inability to retract alone does not mean that circumcision is medically required.</p><p>However, repeated troublesome episodes of balanitis or balanoposthitis, particularly where there is pathological foreskin tightness or scarring, may be a reason for circumcision.</p><p>We assess the child\'s age, symptoms and foreskin before proceeding.</p><p><a href="' . esc_url( $children ) . '">Learn more about circumcision for children →</a></p>',
				),
				array(
					'eyebrow' => 'Eighteen & over',
					'title'   => 'Circumcision for recurrent balanitis in adults',
					'html'    => '<p>We regularly assess adult patients with recurrent balanitis at Beverley Clinic in Edgware, North-West London.</p><p>Some have experienced episodes for many years. Others develop the problem later in life, sometimes together with increasing foreskin tightness.</p><p>We examine the foreskin to establish whether there is phimosis, scarring, lichen sclerosis / BXO or another foreskin problem relevant to the circumcision.</p><p>Suitable adults are normally circumcised under local anaesthetic.</p><p><a href="' . esc_url( $adults ) . '">Learn more about adult circumcision →</a></p>',
				),
			)
		),
	)
);

// 10. Recovery, Healing & Returning to Daily Activities (band: bg-card edge)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'recovery',
	),
	array(
		cil_balanitis_section_head( 'Recovery & aftercare', 'Healing timeline and returning to daily activities' ),
		cil_balanitis_info_cards(
			'g-2',
			array(
				array(
					'eyebrow' => 'Healing process',
					'title'   => 'What is recovery like after circumcision?',
					'html'    => '<p>Some swelling, bruising, tenderness and increased sensitivity are expected during early healing.</p><p>The glans will remain exposed following circumcision and may initially feel more sensitive, particularly if a tight foreskin previously meant that it was rarely exposed.</p><p>If dissolvable stitches are used, they gradually loosen as the wound heals.</p><p>The early appearance is not the final cosmetic result. Swelling settles and the circumcision scar continues changing during healing.</p><p>You receive detailed written aftercare explaining how to care for the wound and when to contact us.</p><p><a href="' . esc_url( $aftercare ) . '">Read our circumcision aftercare information →</a></p>',
				),
				array(
					'eyebrow' => 'Employment',
					'title'   => 'When can I return to work?',
					'html'    => '<p>This depends on the type of work you do and your individual recovery.</p><p>Someone doing desk-based work may be able to return sooner than somebody whose job involves heavy lifting or significant physical activity.</p><p>We advise you according to the procedure and your circumstances.</p>',
				),
				array(
					'eyebrow' => 'Physical exercise',
					'title'   => 'When can I exercise after circumcision?',
					'html'    => '<p>Strenuous exercise, cycling, heavy lifting and activities that cause significant movement or friction around the wound should be avoided during the early healing period.</p><p>Return to exercise gradually according to your healing and the instructions we give you.</p>',
				),
				array(
					'eyebrow' => 'Sexual activity',
					'title'   => 'When can I have sex after circumcision?',
					'html'    => '<p>At Beverley Clinic, we generally advise patients to avoid sexual intercourse and masturbation for at least two weeks after circumcision and until the wound is sufficiently healed.</p><p>Healing varies between patients.</p><p>If the wound has not healed sufficiently at two weeks, or sexual activity causes discomfort or places tension on the wound, you should wait longer.</p><p>Follow the individual aftercare advice given to you following your procedure.</p>',
				),
			)
		),
	)
);

// 11. Surgical Risks & Medical Advice (band: "")
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'risks-and-advice',
	),
	array(
		cil_balanitis_section_head( 'Safety & advice', 'Risks of surgery and when to seek medical advice' ),
		cil_balanitis_info_cards(
			'g-2',
			array(
				array(
					'eyebrow' => 'Surgical risks',
					'title'   => 'What are the risks of circumcision?',
					'html'    => '<p>Circumcision is a surgical procedure and complications can occur.</p><p>Potential problems include:</p><ul><li>Bleeding</li><li>Infection</li><li>Swelling</li><li>Bruising</li><li>Wound separation</li><li>Delayed healing</li><li>Scarring</li><li>Altered sensation</li><li>Cosmetic dissatisfaction</li><li>Need for further treatment</li></ul><p style="margin-top:12px">Circumcision permanently changes the appearance of the penis because the foreskin is removed and the glans remains exposed.</p><p>We explain the relevant risks before treatment so that you can make an informed decision.</p>',
				),
				array(
					'eyebrow' => 'When to seek advice',
					'title'   => 'When should I seek medical advice rather than simply booking circumcision?',
					'html'    => '<p>Balanitis is usually not an emergency, but some symptoms require medical assessment.</p><p>Seek appropriate medical advice if:</p><ul><li>This is your first significant episode and the cause is unknown</li><li>The inflammation is severe</li><li>You feel generally unwell</li><li>You have difficulty passing urine</li><li>There is urethral discharge</li><li>You have genital ulcers or an unusual lesion</li><li>You are concerned about a sexually transmitted infection</li><li>There is a persistent area of abnormal skin</li><li>There is unexplained bleeding</li><li>Symptoms are persistent or worsening</li></ul><p style="margin-top:12px">If you cannot pass urine at all or believe you have a medical emergency, seek urgent medical attention rather than waiting for a routine circumcision appointment.</p>',
				),
			)
		),
	)
);

// 12. Transparent Pricing & Location (band: bg-card edge)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'pricing',
	),
	array(
		cil_balanitis_section_head( 'Transparent pricing', 'How much does circumcision for recurrent balanitis cost?' ),
		cil_balanitis_info_cards(
			'g-2',
			array(
				array(
					'eyebrow' => 'Medical foreskin problem',
					'title'   => 'How much does circumcision for recurrent balanitis cost?',
					'html'    => '<p>At our London clinic, adult circumcision involving a medical or foreskin problem, with or without frenulum treatment, is currently £1,080.</p><p>Children with medical foreskin problems are assessed individually.</p><p>If you are unsure which price applies to you, contact us before booking.</p><p><a href="' . esc_url( $prices ) . '">View circumcision prices →</a></p>',
				),
				array(
					'eyebrow' => 'Edgware practice',
					'title'   => 'Beverley Clinic in North-West London',
					'html'    => '<p>Our clinic is based at 78 Beverley Drive, Edgware, HA8 5NE.</p><p>We provide circumcision services in a dedicated, CQC-registered clinical setting with experienced practitioners.</p><p>We regularly see patients with recurrent balanitis, phimosis, and foreskin concerns from across London and the UK.</p>',
				),
			)
		),
	)
);

// 13. Why Choose Beverley Clinic (band: bg-warm)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'why-choose-us',
	),
	array(
		cil_balanitis_section_head( 'Why choose us', 'Why choose Beverley Clinic for circumcision for recurrent balanitis?' ),
		cil_balanitis_info_cards(
			'g-3',
			array(
				array(
					'eyebrow' => 'Clinical focus',
					'title'   => 'Specialist focus on circumcision',
					'html'    => '<p>Circumcision is a core part of our day-to-day clinical work rather than an occasional procedure.</p>',
				),
				array(
					'eyebrow' => 'Underlying causes',
					'title'   => 'We examine the underlying foreskin problem',
					'html'    => '<p>Recurrent balanitis can occur alongside phimosis, scarring, lichen sclerosis / BXO or a tight frenulum.</p><p>We assess these features before planning the circumcision.</p>',
				),
				array(
					'eyebrow' => 'Tailored treatment',
					'title'   => 'Circumcision tailored to the patient',
					'html'    => '<p>We consider the patient\'s age, anatomy, symptoms and findings on examination rather than using exactly the same approach for everyone.</p>',
				),
				array(
					'eyebrow' => 'All ages',
					'title'   => 'Children, teenagers and adults',
					'html'    => '<p>We circumcise patients across different age groups and adapt the procedure and aftercare accordingly.</p>',
				),
				array(
					'eyebrow' => 'Follow-up care',
					'title'   => 'Follow-up during healing',
					'html'    => '<p>Patients receive preparation information before treatment, detailed aftercare afterwards and follow-up support during healing.</p>',
				),
			)
		),
	)
);

// 14. Frequently Asked Questions
$faq_items = array(
	array(
		'q' => 'What is balanitis?',
		'a' => 'Balanitis is inflammation of the head of the penis (glans). It can cause redness, swelling, soreness, itching and discomfort.',
	),
	array(
		'q' => 'What is balanoposthitis?',
		'a' => 'Balanoposthitis means inflammation affecting both the glans and foreskin.',
	),
	array(
		'q' => 'Why does my balanitis keep returning?',
		'a' => 'Recurrent balanitis can have several causes. A tight or difficult-to-retract foreskin, repeated irritation, infection, diabetes, scarring and lichen sclerosis / BXO can all be relevant.',
	),
	array(
		'q' => 'Can a tight foreskin cause balanitis?',
		'a' => 'Yes. A tight foreskin can make retraction and cleaning difficult and may be associated with recurrent inflammation.',
	),
	array(
		'q' => 'Can balanitis cause phimosis?',
		'a' => 'Repeated inflammation can contribute to scarring and increasing tightness of the foreskin in some patients.',
	),
	array(
		'q' => 'Does balanitis mean I need circumcision?',
		'a' => 'Not necessarily. A single episode of balanitis does not automatically require circumcision. Circumcision is particularly relevant to patients with significant recurrent balanitis where the foreskin is contributing to the problem.',
	),
	array(
		'q' => 'Can circumcision stop recurrent balanitis?',
		'a' => 'Circumcision removes the foreskin permanently and can provide a definitive treatment for the foreskin component of recurrent balanitis. It does not guarantee that the glans can never develop another inflammatory or dermatological condition.',
	),
	array(
		'q' => 'Can balanitis be caused by diabetes?',
		'a' => 'Recurrent genital inflammation can be associated with diabetes, particularly where blood glucose is poorly controlled. Adults with recurrent balanitis may need appropriate medical assessment for underlying causes.',
	),
	array(
		'q' => 'Is balanitis an STI?',
		'a' => 'Balanitis itself is not a specific sexually transmitted infection. However, some sexually transmitted conditions can cause similar symptoms, so appropriate sexual-health assessment may be required where there is a relevant risk.',
	),
	array(
		'q' => 'What is the difference between balanitis and phimosis?',
		'a' => 'Balanitis is inflammation of the glans. Phimosis is a foreskin that is too tight to retract. The two conditions can occur together.',
	),
	array(
		'q' => 'What is the difference between balanitis and BXO?',
		'a' => 'Balanitis describes inflammation of the glans. BXO is associated with lichen sclerosis and can cause characteristic chronic changes and scarring affecting the foreskin and penis.',
	),
	array(
		'q' => 'Why has my foreskin become tighter after repeated balanitis?',
		'a' => 'Repeated inflammation and healing can sometimes contribute to loss of elasticity and scarring, causing the foreskin to become progressively tighter.',
	),
	array(
		'q' => 'Can children get recurrent balanitis?',
		'a' => 'Yes. Children can experience balanitis and balanoposthitis. Repeated troublesome episodes may be a reason for circumcision after appropriate assessment.',
	),
	array(
		'q' => 'Can adults be circumcised under local anaesthetic?',
		'a' => 'Suitable adults at our clinic are normally circumcised using local anaesthetic.',
	),
	array(
		'q' => 'When can I have sex after circumcision?',
		'a' => 'Our clinic generally advises waiting at least two weeks and until the wound is sufficiently healed. If healing is incomplete at two weeks, wait longer and follow your individual aftercare instructions.',
	),
	array(
		'q' => 'Can I speak to someone before booking?',
		'a' => 'Yes. If you have recurrent balanitis and are unsure whether your foreskin is contributing to the problem, contact us and explain your symptoms.',
	),
);

$blocks[] = cil_dyn_block(
	'cil/faq',
	array(
		'heading' => 'Frequently asked questions about balanitis and circumcision',
		'items'   => $faq_items,
	)
);

// 15. Callback & Consultation Section (Side-by-side: "Ask us first" card on Left, Consultation Text on Right)
$closing_paras = array(
	cil_balanitis_h( 'Recurrent balanitis circumcision in London', 2 ),
	cil_balanitis_p( 'If you experience repeated episodes of balanitis and are considering circumcision, contact Beverley Clinic.' ),
	cil_balanitis_p( 'We can assess the foreskin and determine whether there is associated phimosis, scarring, lichen sclerosis / BXO or another foreskin problem that needs to be considered when planning your circumcision.' ),
	cil_balanitis_p( '<a class="btn" href="' . esc_url( $book ) . '">Book a consultation →</a>' ),
);

$closing_group = array(
	'blockName'    => 'core/group',
	'attrs'        => array( 'className' => 'body-text' ),
	'innerBlocks'  => $closing_paras,
	'innerHTML'    => '',
	'innerContent' => array_merge(
		array( '<div class="wp-block-group body-text">' ),
		array_fill( 0, count( $closing_paras ), null ),
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
						'formId'  => 'balanitis',
						'subject' => 'balanitis enquiry',
						'urgent'  => false,
					)
				),
				$closing_group,
			)
		),
	)
);

// 16. CTA Band
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

// 17. Circumcision Across All Ages
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

// Find Balanitis page in LocalWP
$page = get_post( 20 );
if ( ! $page || 'balanitis' !== $page->post_name ) {
	$page = get_page_by_path( 'conditions/balanitis', OBJECT, 'page' );
	if ( ! $page ) {
		$page = get_page_by_path( 'balanitis', OBJECT, 'page' );
	}
}
if ( ! $page ) {
	fwrite( STDERR, "Balanitis page not found\n" );
	exit( 1 );
}

kses_remove_filters();
remove_filter( 'content_save_pre', 'convert_invalid_entities' );
remove_filter( 'content_save_pre', 'balanceTags', 50 );
$result = wp_update_post(
	wp_slash(
		array(
			'ID'           => (int) $page->ID,
			'post_title'   => 'Recurrent Balanitis and Circumcision in London',
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

update_post_meta( $page->ID, '_cil_document_title', 'Recurrent Balanitis Treatment London | Circumcision Clinic' );
update_post_meta( $page->ID, '_cil_meta_description', 'Repeated balanitis can cause soreness, inflammation and foreskin problems. Learn about recurrent balanitis, phimosis, lichen sclerosis/BXO and circumcision in London.' );
clean_post_cache( $page->ID );

// Export fixture
$manifest     = cil_content_load_manifest();
$replace_to   = ( ! is_wp_error( $manifest ) && ! empty( $manifest['replace_to'] ) ) ? $manifest['replace_to'] : 'https://circumcision-london-wp.vercel.app';
$replace_from = untrailingslashit( home_url() );
$content_remote = cil_content_rewrite_site_urls( $page->post_content, $replace_from, $replace_to );
$check          = cil_content_assert_no_local_host( $content_remote );
if ( is_wp_error( $check ) ) {
	fwrite( STDERR, $check->get_error_message() . "\n" );
	exit( 1 );
}
$fixture = array(
	'schema'              => 1,
	'slug'                => 'balanitis',
	'path'                => '/conditions/balanitis/',
	'local_id'            => (int) $page->ID,
	'source_modified_gmt' => $page->post_modified_gmt,
	'source_sha256'       => hash( 'sha256', $content_remote ),
	'replace_from'        => untrailingslashit( $replace_from ),
	'replace_to'          => untrailingslashit( $replace_to ),
	'content'             => $content_remote,
);
$path = cil_content_write_fixture( 'balanitis', $fixture );
if ( is_wp_error( $path ) ) {
	fwrite( STDERR, $path->get_error_message() . "\n" );
	exit( 1 );
}

echo "UPDATED BALANITIS SUCCESSFUL\n";
echo "Post ID: " . $page->ID . "\n";
echo "Content Bytes: " . strlen( $page->post_content ) . "\n";
echo "Fixture Path: " . $path . "\n";