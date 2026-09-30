<?php
/**
 * Apply full client Children & Teenagers content to /children/ + export fixture.
 *
 * Source: Additional content to add to pages.docx
 * Does not hard-code content into theme PHP page builders.
 */
$root    = dirname( __DIR__, 2 );
$wp_load = $root . '/app/public/wp-load.php';
require $wp_load;

if ( ! function_exists( 'cil_serialize_blocks' ) ) {
	fwrite( STDERR, "Theme helpers missing\n" );
	exit( 1 );
}

/**
 * @param string $html Inner HTML.
 * @return array
 */
function cil_children_p( $html ) {
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
 * @param string $text Plain heading.
 * @param int    $level 2–6.
 * @return array
 */
function cil_children_h( $text, $level = 2 ) {
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
 * @param array<int, string> $items Plain items.
 * @return array
 */
function cil_children_list( $items ) {
	return cil_core_list( array_map( 'esc_html', $items ) );
}

/**
 * @param string $label Link text.
 * @param string $path  Path.
 * @return array
 */
function cil_children_link_p( $label, $path ) {
	$url = esc_url( home_url( $path ) );
	return cil_children_p( '<a href="' . $url . '">' . esc_html( $label ) . '</a>' );
}

/**
 * @param array<int, array> $inner Inner blocks.
 * @return array
 */
function cil_children_body_group( $inner ) {
	return array(
		'blockName'    => 'core/group',
		'attrs'        => array(
			'className' => 'body-text',
			'layout'    => array( 'type' => 'constrained' ),
		),
		'innerBlocks'  => $inner,
		'innerHTML'    => '',
		'innerContent' => array_merge(
			array( '<div class="wp-block-group body-text is-layout-constrained wp-block-group-is-layout-constrained">' ),
			array_fill( 0, count( $inner ), null ),
			array( '</div>' )
		),
	);
}

$aftercare = '/aftercare/';
$phimosis  = '/conditions/phimosis/';
$balanitis = '/conditions/balanitis/';
$bxo       = '/conditions/bxo/';
$prices    = '/prices/';

$body = array();

$body[] = cil_children_h( 'How should I prepare my child for circumcision?', 2 );
$body[] = cil_children_p( 'One of the most common questions parents ask us is how much they should tell their child.' );
$body[] = cil_children_p( 'Our advice is to keep the explanation very minimal and appropriate for your child\'s age.' );
$body[] = cil_children_p( 'Young children usually do not benefit from a detailed description of every stage of the procedure. Too much information given too early can sometimes increase anxiety. Mentioning words like injection or cutting only scares the child and may make them uncooperative for us to carry out the circumcision.' );
$body[] = cil_children_p( 'You might simply explain that you are going to a clinic where we will do some cleaning, and that you will be there to support him.' );
$body[] = cil_children_p( 'Older children and teenagers will naturally have more questions and should be involved in the discussion in a way that is appropriate for their age and understanding.' );
$body[] = cil_children_p( 'Our practitioners are accustomed to speaking directly to children and teenagers. We can explain what is going to happen calmly on the day and answer their questions before anything begins.' );

$body[] = cil_children_h( 'What happens at the appointment?', 2 );
$body[] = cil_children_p( 'The appointment involves more than the circumcision itself. We allow time for registration, local anaesthetic, the procedure and explaining the aftercare.' );
$body[] = cil_children_h( '1. Registration and consent', 3 );
$body[] = cil_children_p( 'We check the required identification and consent and medical history information.' );
$body[] = cil_children_p( 'Please tell us in advance about any medical conditions, medication, allergies or previous problems with bleeding.' );
$body[] = cil_children_h( '2. Examination', 3 );
$body[] = cil_children_p( 'Your son is examined before the circumcision.' );
$body[] = cil_children_p( 'We assess the penis and foreskin and look for anything that may influence how the procedure should be performed, including phimosis, scarring, BXO (lichen sclerosis), a tight frenulum or buried penis.' );
$body[] = cil_children_p( 'If we identify something that means routine circumcision is not appropriate, we will explain this rather than simply proceeding.' );
$body[] = cil_children_h( '3. Local anaesthetic', 3 );
$body[] = cil_children_p( 'Local anaesthetic is given around the penis and allowed time to work.' );
$body[] = cil_children_p( 'We check the area before beginning the circumcision.' );
$body[] = cil_children_h( '4. Circumcision', 3 );
$body[] = cil_children_p( 'For most older children and teenagers, we use a forceps-guided circumcision technique with thermal cautery.' );
$body[] = cil_children_p( 'The foreskin is carefully positioned and a specialist surgical forceps is used to guide the circumcision and protect the underlying structures. Thermal cautery helps control bleeding during the procedure.' );
$body[] = cil_children_p( 'Depending on your son\'s age, anatomy and the wound following circumcision, the skin may be closed using dissolvable stitches, medical skin glue, a combination of both, or occasionally neither.' );
$body[] = cil_children_h( '5. Aftercare', 3 );
$body[] = cil_children_p( 'After the procedure, your son can put his underwear and trousers back on and walk normally.' );
$body[] = cil_children_p( 'Before you leave, we explain how to look after the circumcision, what to expect during healing and when to contact us.' );
$body[] = cil_children_p( 'You will also receive written aftercare instructions.' );
$body[] = cil_children_link_p( 'Read our circumcision aftercare information →', $aftercare );

$body[] = cil_children_h( 'Is general anaesthetic necessary for child circumcision?', 2 );
$body[] = cil_children_p( 'At our clinic, circumcision for suitable children and teenagers is performed using local anaesthetic rather than routine general anaesthetic.' );
$body[] = cil_children_p( 'This means your son remains awake during the procedure.' );
$body[] = cil_children_p( 'Local anaesthetic is administered around the penis and given time to work before we begin. We check that the area is adequately anaesthetised before proceeding.' );
$body[] = cil_children_p( 'Not every child is suitable for a procedure under local anaesthetic. Age alone does not determine this — we also consider the child\'s maturity, anxiety, ability to cooperate and individual circumstances.' );
$body[] = cil_children_p( 'If we do not believe it is appropriate or safe to proceed under local anaesthetic, we will tell you.' );

$body[] = cil_children_h( 'Will my child feel the circumcision?', 2 );
$body[] = cil_children_p( 'Local anaesthetic is used to numb the area before the circumcision begins.' );
$body[] = cil_children_p( 'A child may still be aware that something is happening and may notice movement, touch or pressure even when the area has been anaesthetised.' );
$body[] = cil_children_p( 'Children can also become upset because they are anxious, in an unfamiliar environment or dislike being examined or held still.' );
$body[] = cil_children_p( 'Although most children cooperate really well with the way we do the circumcision, we avoid promising parents that a child will experience absolutely no discomfort. Instead, our aim is to make sure the local anaesthetic has had time to work and to keep your son as comfortable and reassured as possible throughout the procedure.' );

$body[] = cil_children_h( 'Can I stay with my child?', 2 );
$body[] = cil_children_p( 'We understand that having a procedure can be stressful for both children and parents.' );
$body[] = cil_children_p( 'We allow one parent to be present with the child inside the surgery room.' );
$body[] = cil_children_p( 'After the circumcision, a member of staff will make sure you understand the aftercare before you leave.' );

$body[] = cil_children_h( 'What should my child wear?', 2 );
$body[] = cil_children_p( 'We recommend comfortable clothing that is easy to put on after the procedure.' );
$body[] = cil_children_p( 'Loose trousers or tracksuit bottoms can be more comfortable than tight jeans immediately afterwards.' );
$body[] = cil_children_p( 'We recommend tight well fitting and supportive underwear to wear during the healing period such as tight V shaped briefs. This can be useful for older boys and teenagers because it holds the penis upright and reduces unnecessary movement of the penis to minimise swelling.' );

$body[] = cil_children_h( 'What should we bring to the appointment?', 2 );
$body[] = cil_children_p( 'Please bring:' );
$body[] = cil_children_list(
	array(
		'The identification and consent documents requested by the clinic',
		'Details of any medication your son takes',
		'Relevant medical letters if he has an existing medical condition',
		'Comfortable underwear and loose-fitting trousers or tracksuit bottoms',
		'Something familiar to keep him occupied, such as a tablet, headphones, book or handheld game',
	)
);
$body[] = cil_children_p( 'For younger children, bringing a favourite toy or familiar activity can make waiting much easier.' );
$body[] = cil_children_p( 'If only one parent will attend, or there are different parental-responsibility arrangements such as single parents, contact us before the appointment so that we can explain the documents and consent required.' );

$body[] = cil_children_h( 'How long does child circumcision take?', 2 );
$body[] = cil_children_p( 'The circumcision is usually only 10-20 minutes depending on the age, size of the penis and the patient’s cooperation.' );

$body[] = cil_children_h( 'What happens to the stitches after circumcision?', 2 );
$body[] = cil_children_p( 'Where stitches are required, we normally use dissolvable stitches, so they do not usually need to be removed.' );
$body[] = cil_children_p( 'The stitches gradually loosen and disappear as healing progresses.' );
$body[] = cil_children_p( 'Medical skin glue may also be used, either instead of stitches or in combination with them, depending on the wound.' );

$body[] = cil_children_h( 'What does normal healing look like?', 2 );
$body[] = cil_children_p( 'The penis will look different immediately after circumcision, and its appearance changes during healing.' );
$body[] = cil_children_p( 'Some redness, swelling, bruising and sensitivity can occur after the procedure. The head of the penis may also initially appear particularly sensitive because it was previously covered by the foreskin.' );
$body[] = cil_children_p( 'Parents are sometimes concerned simply because they have never seen a circumcision wound healing before.' );
$body[] = cil_children_p( 'We explain what to expect and provide written aftercare instructions. If you are unsure whether something you are seeing is normal, contact us and ask.' );

$body[] = cil_children_h( 'Will my child need pain relief afterwards?', 2 );
$body[] = cil_children_p( 'Some soreness or discomfort during the early healing period is expected after a surgical procedure.' );
$body[] = cil_children_p( 'We will discuss appropriate pain relief with you before you leave the clinic.' );
$body[] = cil_children_p( 'Any medication should be given according to the instructions appropriate for your child\'s age and weight.' );
$body[] = cil_children_p( 'Do not give your child medication simply because another parent was advised to use it following their child\'s circumcision. Follow the instructions provided for your son.' );

$body[] = cil_children_h( 'When can my child return to school?', 2 );
$body[] = cil_children_p( 'This depends on your child\'s age, how he feels and the type of activities he normally does at school.' );
$body[] = cil_children_p( 'Some children are comfortable returning after 2 weeks, while others benefit from additional time at home.' );
$body[] = cil_children_p( 'We can provide a school letter where appropriate.' );
$body[] = cil_children_p( 'When your son returns, his school may need to know that he should temporarily avoid activities that could place pressure or friction on the healing area.' );

$body[] = cil_children_h( 'When can my child return to PE, football and sports?', 2 );
$body[] = cil_children_p( 'Returning to the classroom and returning to sport are not necessarily the same thing.' );
$body[] = cil_children_p( 'Your son may feel comfortable walking and attending school before the circumcision has healed enough for PE, football, cycling, swimming, martial arts or contact sports.' );
$body[] = cil_children_p( 'Activities involving running, jumping or lifting should be avoided until the wound has healed sufficiently.' );
$body[] = cil_children_p( 'We will give you individual advice following the procedure about when these activities can gradually be resumed.' );

$body[] = cil_children_h( 'What about erections in teenagers?', 2 );
$body[] = cil_children_p( 'Teenagers may naturally experience erections during the healing period, including during sleep or on waking.' );
$body[] = cil_children_p( 'An erection can temporarily cause pulling or tightness around a healing circumcision wound and stitches. This can be uncomfortable but does not automatically mean that something has gone wrong.' );
$body[] = cil_children_p( 'We discuss the healing process privately and appropriately with teenage patients and explain when they should contact us if they are concerned.' );

$body[] = cil_children_h( 'Circumcision for a tight foreskin in children', 2 );
$body[] = cil_children_p( 'A foreskin that does not retract in a young boy is not automatically abnormal.' );
$body[] = cil_children_p( 'The foreskin naturally separates from the head of the penis as a child develops, and this process can continue for many years. Parents should never forcibly retract a child\'s foreskin, as this can cause pain, injury and scarring.' );
$body[] = cil_children_p( 'Circumcision for medical reasons is therefore different from circumcision chosen for religious or cultural reasons.' );
$body[] = cil_children_p( 'If your son has a tight foreskin (phimosis) or difficulty urinating, he may need a circumcision for medical reasons. Please mention this at the time of booking as extra information may be required before the appointment and there maybe additional charges depending on the severity.' );
$body[] = cil_children_link_p( 'Learn more about phimosis →', $phimosis );

$body[] = cil_children_h( 'What if my child has recurrent balanitis?', 2 );
$body[] = cil_children_p( 'Balanitis is inflammation affecting the head of the penis and can cause redness, soreness, irritation or discharge.' );
$body[] = cil_children_p( 'A single episode does not necessarily mean that a child requires circumcision.' );
$body[] = cil_children_p( 'Where balanitis keeps returning, particularly in association with foreskin tightness or scarring, we can assess your son and offer circumcision as a suitable treatment option.' );
$body[] = cil_children_link_p( 'Learn more about balanitis →', $balanitis );

$body[] = cil_children_h( 'What is BXO?', 2 );
$body[] = cil_children_p( 'BXO (balanitis xerotica obliterans), also known as male genital lichen sclerosis, is a condition that can cause whitening, thickening and scarring of the foreskin.' );
$body[] = cil_children_p( 'The resulting scar tissue can make the foreskin progressively tighter.' );
$body[] = cil_children_p( 'BXO is different from the normal non-retractile foreskin seen in many younger boys, which is why examination is important when a medical problem is suspected.' );
$body[] = cil_children_p( 'Circumcision may be recommended where BXO significantly affects the foreskin.' );
$body[] = cil_children_link_p( 'Learn more about BXO / lichen sclerosis →', $bxo );

$body[] = cil_children_h( 'What if my child has a buried penis?', 2 );
$body[] = cil_children_p( 'A buried or hidden penis is where some or all of the penis sits within the surrounding pubic tissue.' );
$body[] = cil_children_p( 'Recognising this before circumcision is important because it can influence both the procedure and the aftercare.' );
$body[] = cil_children_p( 'Our practitioners assess this before proceeding. In most patients with buried penis, we are still able to carry out the circumcision. If for any reason, we are unable, we will inform you and discuss the next appropriate steps.' );

$intro_paras = array(
	cil_children_p( 'Circumcision can make both a child and his parents feel anxious, particularly when a child is old enough to understand that he is having a procedure.' ),
	cil_children_p( 'At Beverley Clinic in North-West London, we provide circumcision for boys and teenagers for religious, cultural, personal and medical reasons. Our practitioners have extensive experience treating children of different ages and understand that the way we approach a five-year-old should be very different from the way we speak to a teenager.' ),
	cil_children_p( 'Our aim is to make the experience as calm and straightforward as possible. Before the appointment, we provide preparation information so that parents know what to expect and how to prepare their son without giving him unnecessary information that may increase his anxiety.' ),
	cil_children_p( 'The procedure is performed using local anaesthetic. For older children and teenagers, we commonly use a forceps-guided circumcision technique with thermal cautery.' ),
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

$spec_note = 'From £300, depending on age. See the <a href="' . esc_url( home_url( $prices ) ) . '" style="color:var(--blue-deep)">full price list</a>, broken down by age band.';

$blocks   = array();
$blocks[] = cil_dyn_block(
	'cil/page-head',
	array(
		'eyebrow'    => 'One to seventeen years · From £300',
		'title'      => 'Circumcision for children and teenagers',
		'lede'       => 'Circumcision can make both a child and his parents feel anxious, particularly when a child is old enough to understand that he is having a procedure.',
		'reviewedBy' => cil_reviewed_by_haidar(),
		'crumbs'     => array(
			array(
				'label' => 'Who we see',
				'href'  => home_url( '/babies/' ),
			),
			array(
				'label' => 'Children and teenagers',
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
				$intro_group,
				cil_dyn_block(
					'cil/spec-panel',
					array(
						'eyebrow' => 'At a glance',
						'rows'    => array(
							array( 'k' => 'Age', 'v' => 'Children and teenagers' ),
							array( 'k' => 'Common method', 'v' => 'Forceps-guided (traditional) with thermal cautery' ),
							array( 'k' => 'Anaesthetic', 'v' => 'Local anaesthetic' ),
							array( 'k' => 'Closure', 'v' => 'Skin glue, dissolvable stitches, both, or occasionally neither' ),
							array( 'k' => 'School letters', 'v' => 'Ask the clinic if needed' ),
							array( 'k' => 'Price', 'v' => 'From £300, depending on age' ),
						),
						'note'    => $spec_note,
					)
				),
			)
		),
	)
);

$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'child-circumcision-guide',
	),
	array( cil_children_body_group( $body ) )
);

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
						'name'    => 'waiting-room',
						'caption' => 'The waiting area. Bring whatever keeps him occupied.',
						'alt'     => 'The waiting area at Beverley Clinic.',
					)
				),
				cil_dyn_block(
					'cil/callback-card',
					array(
						'eyebrow' => 'Request a call back',
						'title'   => 'Ask us first',
						'formId'  => 'children',
						'subject' => 'children and teenagers enquiry',
						'urgent'  => true,
					)
				),
			)
		),
	)
);

$blocks[] = cil_dyn_block(
	'cil/cta-band',
	array(
		'eyebrow'    => 'Talk to us',
		'title'      => 'Ask us anything before you decide',
		'text'       => 'Most people call with a question rather than to book. That is what the phone is for, and nothing is booked until you say so.',
		'ctaLabel'   => 'Book a consultation',
		'ctaUrl'     => home_url( '/book/' ),
		'phoneLabel' => 'Call 020 8951 3794',
		'phoneUrl'   => 'tel:+442089513794',
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
				'exclude' => home_url( '/children/' ),
				'banded'  => false,
			)
		),
	)
);

$content = cil_serialize_blocks( $blocks );

$page = get_page_by_path( 'children', OBJECT, 'page' );
if ( ! $page ) {
	fwrite( STDERR, "Children page not found\n" );
	exit( 1 );
}

kses_remove_filters();
remove_filter( 'content_save_pre', 'convert_invalid_entities' );
remove_filter( 'content_save_pre', 'balanceTags', 50 );
$result = wp_update_post(
	wp_slash(
		array(
			'ID'           => (int) $page->ID,
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
clean_post_cache( $page->ID );

$manifest     = cil_content_load_manifest();
$replace_to   = ( ! is_wp_error( $manifest ) && ! empty( $manifest['replace_to'] ) ) ? $manifest['replace_to'] : 'https://circumcision-london-wp.vercel.app';
$replace_from = untrailingslashit( home_url() );
$fixture      = cil_content_export_slug( 'children', $replace_from, $replace_to );
if ( is_wp_error( $fixture ) ) {
	fwrite( STDERR, $fixture->get_error_message() . "\n" );
	exit( 1 );
}
$path = cil_content_write_fixture( 'children', $fixture );
if ( is_wp_error( $path ) ) {
	fwrite( STDERR, $path->get_error_message() . "\n" );
	exit( 1 );
}

$v = get_post( $page->ID )->post_content;
$checks = array(
	'How should I prepare my child for circumcision?',
	'What happens at the appointment?',
	'Is general anaesthetic necessary for child circumcision?',
	'Will my child feel the circumcision?',
	'Can I stay with my child?',
	'What should my child wear?',
	'What should we bring to the appointment?',
	'The identification and consent documents requested by the clinic',
	'How long does child circumcision take?',
	'What happens to the stitches after circumcision?',
	'What does normal healing look like?',
	'Will my child need pain relief afterwards?',
	'When can my child return to school?',
	'When can my child return to PE, football and sports?',
	'What about erections in teenagers?',
	'Circumcision for a tight foreskin in children',
	'What if my child has recurrent balanitis?',
	'What is BXO?',
	'What if my child has a buried penis?',
	'Read our circumcision aftercare information',
	'Learn more about phimosis',
	'Learn more about balanitis',
	'Learn more about BXO / lichen sclerosis',
	'/aftercare/',
	'/conditions/phimosis/',
	'/conditions/balanitis/',
	'/conditions/bxo/',
	'forceps-guided circumcision technique with thermal cautery',
);
foreach ( $checks as $k ) {
	echo ( false !== strpos( $v, $k ) ? 'OK' : 'MISS' ) . "  $k\n";
}
echo 'bytes=' . strlen( $v ) . "\n";
echo "fixture={$path}\n";
echo "OK\n";
