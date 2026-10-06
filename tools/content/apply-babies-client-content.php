<?php
/**
 * Apply full client Babies & Toddlers content using the approved Homepage visual system.
 *
 * Content source: Additional content to add to pages.docx
 * Strictly preserves 100% of client-provided copy, wording, links and FAQs.
 * Formats content into rhythmically banded sections with info-cards, steps,
 * checklists, and scroll-reveal triggers matching the approved Homepage.
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
 * @param string $class Optional class.
 * @return array
 */
function cil_babies_p( $html, $class = '' ) {
	$attrs = array();
	$open  = '<p>';
	if ( $class ) {
		$attrs['className'] = $class;
		$open               = '<p class="' . esc_attr( $class ) . '">';
	}
	$content = $open . $html . '</p>';
	return array(
		'blockName'    => 'core/paragraph',
		'attrs'        => $attrs,
		'innerBlocks'  => array(),
		'innerHTML'    => $content,
		'innerContent' => array( $content ),
	);
}

/**
 * @param string $text Plain heading text.
 * @param int    $level 2–6.
 * @param string $class Class list.
 * @return array
 */
function cil_babies_h( $text, $level = 2, $class = 'wp-block-heading display d-2' ) {
	$level = max( 2, min( 6, (int) $level ) );
	$tag   = 'h' . $level;
	if ( 3 === $level && false === strpos( $class, 'd-3' ) ) {
		$class = 'wp-block-heading display d-3';
	}
	$html = '<' . $tag . ' class="' . esc_attr( $class ) . '">' . esc_html( $text ) . '</' . $tag . '>';
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
 * Wrap inner blocks in a constrained body-text group.
 *
 * @param array<int, array> $inner Inner blocks.
 * @param string            $extra_class Additional class name.
 * @return array
 */
function cil_babies_body_group( $inner, $extra_class = '' ) {
	$cls = trim( 'body-text ' . $extra_class );
	return array(
		'blockName'    => 'core/group',
		'attrs'        => array(
			'className' => $cls,
			'layout'    => array( 'type' => 'constrained' ),
		),
		'innerBlocks'  => $inner,
		'innerHTML'    => '',
		'innerContent' => array_merge(
			array( '<div class="wp-block-group ' . esc_attr( $cls ) . ' is-layout-constrained wp-block-group-is-layout-constrained">' ),
			array_fill( 0, count( $inner ), null ),
			array( '</div>' )
		),
	);
}

/**
 * Render a 2-up checklist block with card styling matching homepage.
 *
 * @param array<int, string> $items Items text.
 * @return array
 */
function cil_babies_checklist_block( $items ) {
	$inner_blocks  = array();
	$inner_content = array();
	$inner_content[] = '<ul class="cil-checklist wp-block-list">';

	foreach ( $items as $text ) {
		$item_html = '<li>' . esc_html( $text ) . '</li>';
		$inner_blocks[] = array(
			'blockName'    => 'core/list-item',
			'attrs'        => array(),
			'innerBlocks'  => array(),
			'innerHTML'    => $item_html,
			'innerContent' => array( $item_html ),
		);
		$inner_content[] = null;
	}
	$inner_content[] = '</ul>';

	return array(
		'blockName'    => 'core/list',
		'attrs'        => array(
			'className' => 'cil-checklist',
		),
		'innerBlocks'  => $inner_blocks,
		'innerHTML'    => '',
		'innerContent' => $inner_content,
	);
}

$buried = '/buried-penis/';
$prices = '/prices/';

// --- Top Section & Spec ---
$intro_paras = array(
	cil_babies_p( 'Choosing to circumcise your baby or young child is an important decision, and it is completely normal for parents to have questions about the procedure, anaesthetic, healing and aftercare.' ),
	cil_babies_p( 'At Beverley Clinic in North-West London, baby and toddler circumcision is a core part of our clinical work. Our practitioners have extensive experience performing circumcisions across different age groups, from newborn babies through to adults.' ),
	cil_babies_p( 'For babies and younger children, we commonly use the Plastibell or Circumplast ring method where clinically suitable. The procedure is performed using local anaesthetic, and parents receive preparation information before the appointment as well as detailed aftercare and follow-up support afterwards.' ),
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

$spec_note = 'From £200, depending on age. See the <a href="' . esc_url( home_url( $prices ) ) . '" style="color:var(--blue-deep)">full price list</a>, broken down by age band.';

$faq_items = array(
	array(
		'q' => 'What is the best age for baby circumcision?',
		'a' => '<p>Where parents have already decided to circumcise, we generally recommend the first few weeks of life and under 1 month where possible. We also regularly treat older babies and toddlers, and suitability is assessed individually.</p>',
	),
	array(
		'q' => 'How long does a baby circumcision take?',
		'a' => '<p>For a young baby, the circumcision itself commonly takes around 10 minutes. The complete appointment takes longer because we need time for registration, examination, preparation, local anaesthetic and explaining the aftercare.</p>',
	),
	array(
		'q' => 'Does a Plastibell circumcision need stitches?',
		'a' => '<p>No stitches are normally required when a Plastibell or Circumplast ring technique is used.</p>',
	),
	array(
		'q' => 'How long does a Plastibell ring stay on?',
		'a' => '<p>The ring usually separates naturally within approximately 3–14 days. Do not try to pull the ring off yourself. If it does not come off within this period, we will have to remove the ring which usually only takes a few seconds.</p>',
	),
	array(
		'q' => 'Is local anaesthetic used?',
		'a' => '<p>Yes. Local anaesthetic is given and allowed time to work before the circumcision begins.</p>',
	),
	array(
		'q' => 'Can my baby wear a nappy afterwards?',
		'a' => '<p>Yes. Your baby\'s nappy and clothes can be put back on after the procedure. We explain how to manage nappy changes while the circumcision heals.</p>',
	),
	array(
		'q' => 'What if the ring does not fall off?',
		'a' => '<p>Contact us for advice if it has not fallen off within the first 10 days. Do not pull or cut the ring off yourself. We can determine whether it simply needs more time or whether your baby should be reviewed.</p>',
	),
	array(
		'q' => 'Can my baby be circumcised if he has a buried penis?',
		'a' => '<p>It depends on the degree of burying and your baby\'s anatomy. We assess this before circumcision. In most cases we are still able to carry out the circumcision. If there are any reasons where we cannot procced, we will advise you on the next steps</p>',
	),
	array(
		'q' => 'Can you circumcise my baby if he is unwell?',
		'a' => '<p>We may postpone the procedure if your baby is unwell or there are other clinical concerns. Contact us before travelling if your baby becomes unwell before the appointment.</p>',
	),
	array(
		'q' => 'Can I speak to somebody before I book?',
		'a' => '<p>Yes. You do not have to book an appointment simply to ask a question. You can telephone or message the clinic and discuss your concerns first.</p>',
	),
);

// --- Build Blocks ---
$blocks = array();

// 1. Page Head
$blocks[] = cil_dyn_block(
	'cil/page-head',
	array(
		'eyebrow'    => 'Best under one month old · From £200',
		'title'      => 'Baby and toddler circumcision',
		'lede'       => 'Choosing to circumcise your baby or young child is an important decision, and it is completely normal for parents to have questions about the procedure, anaesthetic, healing and aftercare.',
		'reviewedBy' => cil_reviewed_by_haidar(),
		'crumbs'     => array(
			array(
				'label' => 'Who we see',
				'href'  => home_url( '/babies/' ),
			),
			array(
				'label' => 'Babies and toddlers',
				'href'  => '',
			),
		),
	)
);

// 2. Intro Split with Spec Panel (White band)
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
							array( 'k' => 'Best age', 'v' => 'Under one month where possible' ),
							array( 'k' => 'Common method', 'v' => 'Plastibell / Circumplast ring' ),
							array( 'k' => 'Anaesthetic', 'v' => 'Local anaesthetic' ),
							array( 'k' => 'Procedure time for a young baby', 'v' => 'Approximately 10 minutes' ),
							array( 'k' => 'Stitches with ring method', 'v' => 'None' ),
							array( 'k' => 'Ring separation', 'v' => 'Usually 3–14 days' ),
							array( 'k' => 'Price', 'v' => 'From £200, depending on age' ),
						),
						'note'    => $spec_note,
					)
				),
			)
		),
	)
);

// 3. Best Age Section (Band: bg-card edge)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'best-age',
	),
	array(
		cil_dyn_block(
			'cil/section-head',
			array(
				'eyebrow' => 'Timing & Suitability',
				'heading' => 'What is the best age to circumcise a baby?',
				'display' => 'd-2',
			)
		),
		cil_babies_body_group(
			array(
				cil_babies_p( 'Where parents have already decided that they would like their son circumcised, we generally recommend having the procedure during the first few weeks of life where possible.' ),
			),
			'cil-section-lede'
		),
		cil_babies_body_group(
			array(
				cil_babies_p( 'Younger babies are smaller and usually easier to settle. The procedure itself is also usually quicker in a young baby.' ),
				cil_babies_p( "However, there is no need to worry if your baby is already older. We regularly circumcise babies and toddlers of different ages. The method we recommend will depend on your child's age, size, anatomy and findings on examination." ),
				cil_babies_p( 'If you are unsure whether your child is suitable for the ring method, contact us before booking and we can advise you.' ),
			)
		),
	)
);

// 4. Ring Methods (Band: default white)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'ring-methods',
	),
	array(
		cil_dyn_block(
			'cil/section-head',
			array(
				'eyebrow' => 'Ring technique',
				'heading' => 'How is a baby circumcision performed?',
				'display' => 'd-2',
			)
		),
		cil_babies_body_group(
			array(
				cil_babies_p( 'For most young babies, we use either a Plastibell or Circumplast circumcision. These are established ring techniques designed specifically for circumcision.' ),
			),
			'cil-section-lede'
		),
		cil_babies_body_group(
			array(
				cil_babies_p( 'A correctly sized plastic ring is placed over the head of the penis (glans) and underneath the foreskin. The foreskin is then secured around the ring.' ),
				cil_babies_p( 'The ring remains in place when your baby goes home. Over the following days, the unwanted foreskin naturally separates together with the ring.' ),
			)
		),
		cil_dyn_block(
			'cil/info-cards',
			array(
				'columns'      => 'g-2',
				'headingLevel' => 3,
				'items'        => array(
					array(
						'title' => 'Plastibell or Circumplast – which one will my baby have?',
						'html'  => '<p>Plastibell and Circumplast work according to a similar principle, but there are differences between the devices.</p><p>You do not need to choose the device yourself. We assess your baby and select the ring and size that we consider most appropriate. In our experience, both methods give the same cosmetic results.</p><p>The most important factor is not simply the name of the device, but choosing the correct technique and ring size for the individual child.</p>',
					),
					array(
						'title' => 'Does baby circumcision require stitches?',
						'html'  => '<p>With the Plastibell or Circumplast ring method, stitches are not required.</p><p>The ring controls the circumcision line while the area underneath heals. The unwanted foreskin and ring then separate naturally.</p><p>If we believe that a ring technique is not suitable for your child, we will explain this before proceeding and discuss the alternatives with you.</p>',
					),
				),
			)
		),
		cil_babies_body_group(
			array(
				cil_babies_p( 'One of the main advantages of the ring method is that stitches are not required.' ),
				cil_babies_p( 'The ring usually separates within approximately 3–14 days, although every baby heals at a slightly different rate. If it does not come off, we will book a follow up appointment to remove the ring which only takes a few seconds.' ),
			)
		),
	)
);

// 5. Patient Comfort & Parents (Band: bg-warm)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'anaesthetic-and-parents',
	),
	array(
		cil_dyn_block(
			'cil/section-head',
			array(
				'eyebrow' => 'Patient comfort',
				'heading' => 'Is local anaesthetic used for baby circumcision?',
				'display' => 'd-2',
			)
		),
		cil_dyn_block(
			'cil/info-cards',
			array(
				'columns'      => 'g-2',
				'headingLevel' => 3,
				'items'        => array(
					array(
						'eyebrow' => 'Pain relief',
						'title'   => 'Local anaesthetic before starting',
						'html'    => '<p>Yes. We use local anaesthetic for baby circumcision.</p><p>The anaesthetic is given around the penis and usually works very quickly. We test the area before the circumcision begins to check that your son is not feeling pain.</p><p>Babies can still cry during an appointment for many reasons, including being undressed, held still, feeling strange touch or being hungry. Crying does not necessarily mean that a baby is experiencing pain from the circumcision itself.</p><p>Our aim is to keep your baby as comfortable and settled as possible throughout the appointment.</p>',
					),
					array(
						'eyebrow' => 'Parent support',
						'title'   => 'Can parents stay with their baby?',
						'html'    => '<p>We understand that parents can be more anxious about circumcision than their baby.</p><p>We allow one parent inside the surgery room where we will explain what happens during the circumcision. Your baby is returned to you as soon as the procedure is complete so that you can comfort, feed and settle him.</p><p>Before you leave, we make sure you understand the aftercare and have an opportunity to ask questions.</p>',
					),
				),
			)
		),
	)
);

// 6. Appointment Walkthrough (Band: default white)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'appointment-walkthrough',
	),
	array(
		cil_dyn_block(
			'cil/section-head',
			array(
				'eyebrow' => 'On the day',
				'heading' => 'What happens at the appointment?',
				'lede'    => 'Your appointment involves more than the circumcision itself.',
				'display' => 'd-2',
			)
		),
		cil_dyn_block(
			'cil/steps',
			array(
				'items' => array(
					array(
						'h' => '1. Registration and consent',
						'p' => "We check the relevant identification and consent information and confirm your baby's medical history. Please tell us about any health problems, medication, allergies or family history of unusual or excessive bleeding before attending the appointment in case we need to obtain letters from your doctor or postpone the appointment.",
					),
					array(
						'h' => '2. Examination',
						'p' => 'Your baby is examined before circumcision. We check the penis and foreskin and look for anatomical features that could affect whether or how the circumcision should be performed. If we identify something that makes routine circumcision inappropriate, we will explain this to you rather than simply proceeding.',
					),
					array(
						'h' => '3. Local anaesthetic',
						'p' => 'Local anaesthetic is administered and given time to take effect.',
					),
					array(
						'h' => '4. Circumcision',
						'p' => 'For a young baby, the circumcision itself commonly takes approximately 10 minutes.',
					),
					array(
						'h' => '5. Aftercare',
						'p' => "Your baby's nappy and clothes can be put back on and he can return to you. Before you leave, we explain how to care for the circumcision at home, what normal healing looks like and when you should contact us.",
					),
				),
			)
		),
	)
);

// 7. Preparation & What to Bring (Band: bg-card edge)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'preparation',
	),
	array(
		cil_dyn_block(
			'cil/section-head',
			array(
				'eyebrow' => 'Preparation',
				'heading' => 'What should I bring to the appointment?',
				'display' => 'd-2',
			)
		),
		cil_babies_body_group(
			array(
				cil_babies_h( 'Can my baby feed before or after circumcision?', 2 ),
				cil_babies_p( 'Follow the feeding instructions we give you when the appointment is booked.' ),
				cil_babies_p( 'For a procedure carried out under local anaesthetic, babies do not generally require the same fasting arrangements that would be required for a general anaesthetic.' ),
				cil_babies_p( "Bring your baby's usual milk or bottle if he uses one. We also recommend bringing a dummy/pacifier for soothing, even if it is not something your baby normally uses. We usually want the baby a little bit hungry so that we feed him and keep him comforted during the circumcision." ),
				cil_babies_p( '<strong>Please bring:</strong>' ),
				cil_babies_checklist_block(
					array(
						'Clean, well-fitting nappies',
						'Baby wipes',
						"Your baby's usual milk or bottle if required",
						'A dummy/pacifier even if your son does not usually take one',
						"Your baby's Red Book",
						'Birth certificate or passport if available',
						'Photo identification for the parents',
						'Any relevant medical letters or information (to be sent beforehand)',
					)
				),
				cil_babies_p( 'If your child has an existing medical condition, please send relevant medical information to us before the appointment where possible.' ),
				cil_babies_p( 'If only one parent will be attending, or your family circumstances are different (e.g. single parents), contact the clinic beforehand so that we can explain the consent and identification requirements.' ),
			)
		),
	)
);

// 8. Healing & Recovery (Band: default white)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'healing-and-aftercare',
	),
	array(
		cil_dyn_block(
			'cil/section-head',
			array(
				'eyebrow' => 'Healing & Recovery',
				'heading' => 'What does normal healing look like after baby circumcision?',
				'display' => 'd-2',
			)
		),
		cil_babies_body_group(
			array(
				cil_babies_p( 'The penis will look different immediately after circumcision and will continue to change as it heals.' ),
			),
			'cil-section-lede'
		),
		cil_babies_body_group(
			array(
				cil_babies_p( 'Redness, swelling and a bad smell from the dead foreskin around a circumcision site can be part of normal healing. The appearance can therefore concern parents even when healing is progressing normally. It is similar to the umbilical cord which often looks worse before coming off.' ),
				cil_babies_p( 'With a ring circumcision, the tissue beyond the ring changes as it separates. The ring gradually loosens and eventually falls away with the unwanted foreskin.' ),
				cil_babies_p( 'We explain the expected appearance before you leave and provide aftercare information sheet with example photos so that you know what to look for.' ),
				cil_babies_p( 'If you are unsure whether something you are seeing is normal, contact us rather than trying to treat or manipulate the area yourself.' ),
			)
		),
		cil_dyn_block(
			'cil/info-cards',
			array(
				'columns'      => 'g-3',
				'headingLevel' => 3,
				'items'        => array(
					array(
						'title' => 'When will the Plastibell or Circumplast ring fall off?',
						'html'  => '<p>The ring commonly separates naturally within approximately 3 to 14 days.</p><p>Some rings fall sooner than others. If the ring has not come off within the first 10 days, you can contact us or send us a photo and we can advise you if the ring looks like it is coming off naturally or whether we will need to remove it for your son in a follow up appointment. A ring taking longer than another baby\'s ring does not necessarily mean that something is wrong.</p><p>If the ring has not separated within the timeframe we have given you, or you are concerned about its position or appearance, contact the clinic. We can advise you and arrange a review if necessary.</p>',
					),
					array(
						'title' => "How do I change my baby's nappy after circumcision?",
						'html'  => '<p>You can continue changing your baby\'s nappy normally, but we will show you how to apply Vaseline to the nappy during the healing period.</p><p>Change wet or soiled nappies promptly and take care around the circumcision site and ring.</p><p>The aftercare advice can differ according to the circumcision method and the individual baby, so always follow the instructions you have been given rather than advice intended for somebody else\'s child.</p>',
					),
					array(
						'title' => 'Can I bath my baby after circumcision?',
						'html'  => '<p>Yes and you can do this immediately. This is one of the advantages of a ring circumcision. You can use baby shampoo or add salt to the bath water.</p><p><strong>Will my baby need pain relief afterwards?</strong></p><p>We will discuss appropriate pain relief and comfort measures with you before you leave. Some babies settle very quickly after the procedure, while others can be unsettled for a period afterwards.</p><p>If medication is recommended, follow the dose and timing instructions appropriate to your child\'s age and weight. Do not give medication to a young baby unless it is appropriate for their age and you have been advised how to use it.</p>',
					),
				),
			)
		),
	)
);

// 9. Clinical Safety & Suitability (Band: bg-warm)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap',
		'anchor' => 'safety-and-suitability',
	),
	array(
		cil_dyn_block(
			'cil/section-head',
			array(
				'eyebrow' => 'Clinical Safety',
				'heading' => 'When might we postpone or advise against circumcision?',
				'display' => 'd-2',
			)
		),
		cil_babies_body_group(
			array(
				cil_babies_p( 'Although most babies who attend can be circumcised, we will not proceed simply because an appointment has been booked.' ),
				cil_babies_p( 'Circumcision may need to be postponed or reconsidered if:' ),
				cil_babies_checklist_block(
					array(
						'Your baby is currently unwell',
						'There are concerns about abnormal or excessive bleeding',
						'There is a known or suspected bleeding disorder',
						'There is a significant family history of bleeding problems',
						'The anatomy of the penis requires further assessment',
						'Your baby has certain forms of hypospadias or another condition where the foreskin may need to be preserved',
						'Our practitioner believes circumcision should be delayed for another clinical reason',
					)
				),
				cil_babies_p( 'If we do not believe it is appropriate to proceed, we will explain why and advise you about the next step.' ),
			)
		),
		cil_dyn_block(
			'cil/info-cards',
			array(
				'columns'      => 'g-2',
				'headingLevel' => 3,
				'items'        => array(
					array(
						'eyebrow' => 'Penile Anatomy',
						'title'   => 'What if my baby has a buried or hidden penis?',
						'html'    => '<p>Some babies have a buried or hidden penis, where part of the penis sits within the surrounding pubic tissue.</p><p>Recognising this before circumcision is important because it can affect both the suitability of circumcision and the aftercare required afterwards.</p><p>Our practitioners assess this before the procedure. If your baby has a buried penis, we will explain what this means and show you any additional care that may be required during healing.</p><p><a href="' . esc_url( home_url( $buried ) ) . '">Learn more about buried penis →</a></p>',
					),
					array(
						'eyebrow' => 'Safety & Risks',
						'title'   => 'What are the possible complications of baby circumcision?',
						'html'    => '<p>Circumcision is a surgical procedure and, like any procedure, it has potential complications.</p><p>These can include bleeding, infection, problems with healing, problems associated with the ring, or an unsatisfactory cosmetic result. Other complications are possible and will be given to you as part of the consent process.</p><p>Careful assessment, appropriate technique and good aftercare are important parts of reducing risk.</p><p>We would rather parents contact us with a concern that turns out to be normal than stay at home worrying about something they are unsure about.</p>',
					),
				),
			)
		),
	)
);

// 10. When to Contact the Clinic & Follow-up (Band: default white)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap',
		'anchor' => 'when-to-contact',
	),
	array(
		cil_dyn_block(
			'cil/section-head',
			array(
				'eyebrow' => 'Ongoing support',
				'heading' => 'When should I contact the clinic after circumcision?',
				'display' => 'd-2',
			)
		),
		cil_babies_body_group(
			array(
				cil_babies_p( 'Contact us if:' ),
				cil_babies_checklist_block(
					array(
						'You are concerned about bleeding',
						'The ring has not separated within the timeframe we gave you',
						'You are concerned about how the circumcision is healing',
						'Your baby appears unusually unwell',
						'You have any other concern about the circumcision',
					)
				),
				cil_babies_p(
					'If your baby is unable to pass urine, has significant ongoing bleeding, becomes seriously unwell or you believe there is a medical emergency, contact us immediately. For out of hours, we have a 24/7 emergency number which will be given to you on the day of the circumcision.',
					'callout urgent'
				),
				cil_babies_h( 'What follow-up do we provide?', 2 ),
				cil_babies_p( 'Our care continues after you leave the clinic.' ),
				cil_babies_p( 'Parents receive detailed written aftercare information and access to our preparation and aftercare videos. We also make a next-day follow-up call to check how your baby is doing and whether you have any questions.' ),
				cil_babies_p( 'If something needs to be examined, we can arrange a follow-up appointment during the healing period where clinically appropriate.' ),
			)
		),
	)
);

// 11. Pricing & Clinic Location (Band: bg-card edge)
$blocks[] = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'pricing-location',
	),
	array(
		cil_dyn_block(
			'cil/info-cards',
			array(
				'columns'      => 'g-2',
				'headingLevel' => 2,
				'items'        => array(
					array(
						'eyebrow' => 'Transparent pricing',
						'title'   => 'How much does baby circumcision cost?',
						'html'    => "<p>Baby circumcision starts from £200, with the price depending on your child's age.</p><p>The price includes the circumcision procedure and our routine aftercare and follow-up support.</p><p><a href=\"" . esc_url( home_url( $prices ) ) . "\">See our full circumcision price list →</a></p>",
					),
					array(
						'eyebrow' => 'Edgware, London',
						'title'   => 'Baby circumcision in North-West London',
						'html'    => '<p>Our circumcision clinic is located at Beverley Clinic, 78 Beverley Drive, Edgware, North-West London, HA8 5NE.</p><p>Families travel to us from across London and the surrounding areas. If you are travelling a significant distance with a young baby, contact us beforehand if you have questions about suitability, timing or what you need to bring.</p>',
					),
				),
			)
		),
	)
);

// 12. Frequently Asked Questions
$blocks[] = cil_dyn_block(
	'cil/faq',
	array(
		'heading' => 'Frequently asked questions about baby circumcision',
		'items'   => $faq_items,
	)
);

// 13. Split with Figure + Callback Card
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
						'name'    => 'baby-parent',
						'caption' => 'Your son comes straight back to you, dressed and ready to go home.',
						'alt'     => 'A father holding his swaddled newborn in the treatment room, with a practitioner beside him.',
					)
				),
				cil_dyn_block(
					'cil/callback-card',
					array(
						'eyebrow' => 'Request a call back',
						'title'   => 'Ask us first',
						'formId'  => 'babies',
						'subject' => 'babies and toddlers enquiry',
						'urgent'  => true,
					)
				),
			)
		),
	)
);

// 14. Talk to Us CTA Band
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

// 15. Cross-Navigation (Band: bg-warm)
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
				'exclude' => home_url( '/babies/' ),
				'banded'  => false,
			)
		),
	)
);

$content = cil_serialize_blocks( $blocks );

$page = get_page_by_path( 'babies', OBJECT, 'page' );
if ( ! $page ) {
	fwrite( STDERR, "Babies page not found\n" );
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
$fixture      = cil_content_export_slug( 'babies', $replace_from, $replace_to );
if ( is_wp_error( $fixture ) ) {
	fwrite( STDERR, $fixture->get_error_message() . "\n" );
	exit( 1 );
}
$path = cil_content_write_fixture( 'babies', $fixture );
if ( is_wp_error( $path ) ) {
	fwrite( STDERR, $path->get_error_message() . "\n" );
	exit( 1 );
}

$v = get_post( $page->ID )->post_content;
$checks = array(
	'What is the best age to circumcise a baby?',
	'Where parents have already decided that they would like their son circumcised',
	'Younger babies are smaller and usually easier to settle',
	'How is a baby circumcision performed?',
	'Plastibell or Circumplast',
	'Does baby circumcision require stitches?',
	'With the Plastibell or Circumplast ring method, stitches are not required.',
	'Is local anaesthetic used for baby circumcision?',
	'Local anaesthetic before starting',
	'Can parents stay with their baby?',
	'What happens at the appointment?',
	'1. Registration and consent',
	'2. Examination',
	'3. Local anaesthetic',
	'4. Circumcision',
	'5. Aftercare',
	'Can my baby feed before or after circumcision?',
	'What should I bring to the appointment?',
	'Clean, well-fitting nappies',
	'Baby wipes',
	'A dummy/pacifier even if your son does not usually take one',
	'Birth certificate or passport if available',
	'Photo identification for the parents',
	'What does normal healing look like after baby circumcision?',
	'When will the Plastibell or Circumplast ring fall off?',
	'How do I change my baby',
	'Can I bath my baby after circumcision?',
	'Will my baby need pain relief afterwards?',
	'What if my baby has a buried or hidden penis?',
	'Learn more about buried penis',
	'When might we postpone or advise against circumcision?',
	'Your baby is currently unwell',
	'What are the possible complications of baby circumcision?',
	'When should I contact the clinic after circumcision?',
	'You are concerned about bleeding',
	'What follow-up do we provide?',
	'How much does baby circumcision cost?',
	'See our full circumcision price list',
	'Baby circumcision in North-West London',
	'Frequently asked questions about baby circumcision',
	'Can I speak to somebody before I book?',
	'wp:cil/faq',
	'wp:cil/steps',
	'wp:cil/info-cards',
	'cil-checklist',
	'callout urgent',
	'buried-penis',
	'/prices/',
);

$all_ok = true;
foreach ( $checks as $k ) {
	$matched = ( false !== strpos( $v, $k ) );
	if ( ! $matched ) {
		$all_ok = false;
	}
	echo ( $matched ? 'OK' : 'MISS' ) . "  $k\n";
}
echo 'bytes=' . strlen( $v ) . "\n";
echo "fixture={$path}\n";
if ( $all_ok ) {
	echo "ALL CHECKS PASSED PERFECTLY!\n";
} else {
	echo "SOME CHECKS FAILED!\n";
}
