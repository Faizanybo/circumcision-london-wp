<?php
/**
 * Apply full client Religious & Cultural content to /religious/ + export fixture.
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
function cil_religious_p( $html ) {
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
function cil_religious_h( $text, $level = 2 ) {
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
 * @param array<int, string> $items Escaped or HTML items.
 * @return array
 */
function cil_religious_list( $items ) {
	return cil_core_list( $items );
}

/**
 * @param string $label Link text.
 * @param string $path  Path.
 * @return array
 */
function cil_religious_link_p( $label, $path ) {
	$url = esc_url( home_url( $path ) );
	return cil_religious_p( '<a href="' . $url . '">' . esc_html( $label ) . '</a>' );
}

/**
 * @param array<int, array> $inner Inner blocks.
 * @return array
 */
function cil_religious_body_group( $inner ) {
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

$babies    = '/babies/';
$children  = '/children/';
$adults    = '/adults/';
$aftercare = '/aftercare/';
$prices    = '/prices/';

$body = array();

$body[] = cil_religious_h( 'Muslim and Sunnah circumcision', 2 );
$body[] = cil_religious_p( 'Circumcision, known as Khitan, is an important religious practice for many Muslim families.' );
$body[] = cil_religious_p( 'We understand that parents may want their son\'s circumcision to respect both their religious beliefs and appropriate clinical standards.' );
$body[] = cil_religious_p( 'Our Muslim practitioners regularly perform circumcisions for Muslim families and understand the religious importance of the procedure.' );
$body[] = cil_religious_p( 'Families from different Muslim communities may have different traditions concerning the timing of circumcision. Some choose circumcision during the first few weeks or months of life, while others prefer to wait until their son is older.' );
$body[] = cil_religious_p( 'From a clinical perspective, the technique we use depends primarily on the child\'s age, anatomy and individual circumstances rather than religious background.' );
$body[] = cil_religious_p( 'For babies and younger children, we commonly use the Plastibell or Circumplast ring method where clinically suitable.' );
$body[] = cil_religious_p( 'For older children, teenagers and adults, we commonly use a forceps-guided circumcision technique with thermal cautery.' );
$body[] = cil_religious_p( 'Local anaesthetic is used for circumcisions performed at our clinic.' );

$body[] = cil_religious_h( 'When should a Muslim baby be circumcised?', 2 );
$body[] = cil_religious_p( 'Different Muslim families and communities have different traditions regarding the timing of circumcision.' );
$body[] = cil_religious_p( 'From a clinical perspective, where parents have already decided to circumcise their baby, we generally recommend having the procedure during the first few weeks of life where possible.' );
$body[] = cil_religious_p( 'Younger babies are usually suitable for the Plastibell or Circumplast ring technique, subject to examination.' );
$body[] = cil_religious_p( 'However, there is no need to worry if your son is already older. We regularly circumcise babies, toddlers, children, teenagers and adult men.' );
$body[] = cil_religious_p( 'We assess each patient individually and select a method appropriate to his age and anatomy.' );
$body[] = cil_religious_link_p( 'Learn more about baby circumcision →', $babies );

$body[] = cil_religious_h( 'Religious circumcision for older boys', 2 );
$body[] = cil_religious_p( 'Not every family chooses circumcision during infancy.' );
$body[] = cil_religious_p( 'We regularly see older children whose parents have chosen to have them circumcised for religious or cultural reasons.' );
$body[] = cil_religious_p( 'For older boys, we commonly use a forceps-guided circumcision technique with thermal cautery under local anaesthetic.' );
$body[] = cil_religious_p( 'We understand that an older child may be anxious about the procedure. Our practitioners are experienced in talking to children and explaining what will happen in an age-appropriate and reassuring way.' );
$body[] = cil_religious_p( 'We recommend keeping explanations simple, truthful and appropriate for your son\'s age rather than giving him unnecessary detail that may increase anxiety.' );
$body[] = cil_religious_p( 'School-holiday appointments are particularly popular because they allow children additional time to recover before returning to school.' );
$body[] = cil_religious_link_p( 'Learn more about circumcision for children and teenagers →', $children );

$body[] = cil_religious_h( 'Religious circumcision for adults', 2 );
$body[] = cil_religious_p( 'Some men choose to be circumcised as adults because of their religious beliefs, cultural background or following conversion to a faith where circumcision is important to them.' );
$body[] = cil_religious_p( 'We provide adult circumcision in a private and respectful clinical environment.' );
$body[] = cil_religious_p( 'Adult circumcision is normally performed under local anaesthetic using a forceps-guided technique with thermal cautery. Depending on the individual wound, dissolvable stitches, medical skin glue, a combination of both, or occasionally neither may be used.' );
$body[] = cil_religious_p( 'We understand that the reasons for choosing adult religious circumcision can be very personal. You are welcome to discuss any religious, cultural or practical requirements with us before booking.' );
$body[] = cil_religious_link_p( 'Learn more about adult circumcision →', $adults );

$body[] = cil_religious_h( 'Jewish circumcision', 2 );
$body[] = cil_religious_p( 'We also welcome Jewish patients and families seeking circumcision.' );
$body[] = cil_religious_p( 'We recognise that circumcision has an important religious significance within Judaism and that individual families may have specific requirements regarding how and when the circumcision takes place.' );
$body[] = cil_religious_p( 'Our service is a clinical circumcision service, so Jewish families with specific religious or ceremonial requirements should discuss these with us before booking. This allows us to explain what we can provide within our clinical setting and ensure that the arrangements meet your needs.' );

$body[] = cil_religious_h( 'Circumcision as a cultural tradition', 2 );
$body[] = cil_religious_p( 'Circumcision is practised for cultural as well as religious reasons in many communities around the world.' );
$body[] = cil_religious_p( 'We regularly see families and adult patients whose decision to circumcise is connected to their family or cultural background.' );
$body[] = cil_religious_p( 'This includes patients from African, Filipino, Fijian and other communities where male circumcision may traditionally be performed during infancy, childhood, adolescence or adulthood.' );
$body[] = cil_religious_p( 'We respect these traditions while ensuring that the procedure is approached as a surgical procedure requiring appropriate assessment, anaesthesia, hygiene, consent and aftercare.' );

$body[] = cil_religious_h( 'A religious procedure in a clinical environment', 2 );
$body[] = cil_religious_p( 'Religious or cultural circumcision is sometimes described as "non-medical circumcision" because there may be no medical condition requiring the foreskin to be removed.' );
$body[] = cil_religious_p( 'However, the circumcision itself is still a surgical procedure.' );
$body[] = cil_religious_p( 'At Beverley Clinic, religious and cultural circumcision is therefore approached with the same clinical care as a circumcision performed for medical reasons.' );
$body[] = cil_religious_p( 'This includes:' );
$body[] = cil_religious_list(
	array(
		esc_html( 'Review of relevant medical history' ),
		esc_html( 'Examination of the penis and foreskin' ),
		esc_html( 'Appropriate consent' ),
		esc_html( 'Local anaesthetic' ),
		esc_html( 'An age-appropriate circumcision technique' ),
		esc_html( 'Attention to infection prevention' ),
		esc_html( 'Written aftercare instructions' ),
		esc_html( 'A next-day follow-up call' ),
		esc_html( 'Access to follow-up during healing' ),
	)
);
$body[] = cil_religious_p( 'Being performed for religious or cultural reasons does not mean that clinical standards should be any different.' );

$body[] = cil_religious_h( 'What happens before the circumcision?', 2 );
$body[] = cil_religious_p( 'Every patient is assessed before the procedure.' );
$body[] = cil_religious_p( 'For a baby or child, we ask about his general health and relevant medical history and examine the penis before proceeding.' );
$body[] = cil_religious_p( 'We look for anything that may affect whether or how circumcision should be performed, including hypospadias, buried penis, unusual anatomy or other clinical concerns.' );
$body[] = cil_religious_p( 'We also ask about any known bleeding disorder or family history of unusual or excessive bleeding.' );
$body[] = cil_religious_p( 'If we identify something that means routine circumcision should be delayed or reconsidered, we will explain this to you rather than simply proceeding because the circumcision has been requested for religious reasons.' );
$body[] = cil_religious_p( 'The safety and best interests of the patient remain our priority.' );

$body[] = cil_religious_h( 'What circumcision method do you use?', 2 );
$body[] = cil_religious_p( 'We do not use the same circumcision technique for every patient.' );
$body[] = cil_religious_h( 'Babies and younger children', 3 );
$body[] = cil_religious_p( 'Where clinically suitable, we commonly use the Plastibell or Circumplast ring method.' );
$body[] = cil_religious_p( 'A correctly sized plastic ring is placed underneath the foreskin and the foreskin is secured around it. The unwanted foreskin and ring then separate naturally during healing.' );
$body[] = cil_religious_p( 'Stitches are not required with this method.' );
$body[] = cil_religious_link_p( 'Learn more about baby circumcision →', $babies );
$body[] = cil_religious_h( 'Older children and teenagers', 3 );
$body[] = cil_religious_p( 'For older children and teenagers, we commonly use a forceps-guided circumcision technique with thermal cautery.' );
$body[] = cil_religious_p( 'Depending on the patient\'s age, anatomy and wound, closure may involve dissolvable stitches, medical skin glue, both, or occasionally neither.' );
$body[] = cil_religious_link_p( 'Learn more about child circumcision →', $children );
$body[] = cil_religious_h( 'Adults', 3 );
$body[] = cil_religious_p( 'Adult religious and cultural circumcision is also commonly performed using our forceps-guided technique with thermal cautery under local anaesthetic.' );
$body[] = cil_religious_link_p( 'Learn more about adult circumcision →', $adults );

$body[] = cil_religious_h( 'Why don\'t we use the same method for everyone?', 2 );
$body[] = cil_religious_p( 'The patient\'s religion or culture does not determine which surgical technique is safest or most appropriate.' );
$body[] = cil_religious_p( 'We consider age, anatomy, foreskin development and findings on examination before deciding which method to use.' );
$body[] = cil_religious_p( 'This is why two members of the same family may sometimes have their circumcisions performed using different techniques if they are different ages or have different anatomy.' );

$body[] = cil_religious_h( 'Is local anaesthetic used for religious circumcision?', 2 );
$body[] = cil_religious_p( 'Yes. Circumcisions performed at our clinic are carried out using local anaesthetic.' );
$body[] = cil_religious_p( 'The anaesthetic is administered and given time to work before the procedure begins. We check the area before proceeding.' );
$body[] = cil_religious_p( 'Religious or cultural reasons for circumcision do not change our approach to pain control.' );

$body[] = cil_religious_h( 'Consent for religious circumcision in children', 2 );
$body[] = cil_religious_p( 'Circumcision is permanent, so appropriate consent is particularly important when the procedure is being chosen for a child who does not have a medical condition requiring surgery.' );
$body[] = cil_religious_p( 'We ask families to provide the identification and consent documentation required by the clinic.' );
$body[] = cil_religious_p( 'For children, we require both parents to with parental responsibility to provide written consent. If you are a single parent, please inform us before booking so that we can advise you on the next steps.' );
$body[] = cil_religious_p( 'Older children and teenagers should also be involved in discussions about their circumcision in a way that reflects their age, maturity and understanding.' );
$body[] = cil_religious_p( 'If parents disagree about whether their child should be circumcised, or there is uncertainty about parental responsibility, contact us before making an appointment. We will not proceed until the consent position has been clarified.' );

$body[] = cil_religious_h( 'What if my child does not want to be circumcised?', 2 );
$body[] = cil_religious_p( 'As children become older, their understanding and views become increasingly important.' );
$body[] = cil_religious_p( 'If an older child is extremely distressed, actively refuses the procedure or cannot cooperate safely, we will not simply ignore this because the circumcision has been requested for religious or cultural reasons.' );
$body[] = cil_religious_p( 'We will discuss the situation with the parents and child and decide whether it is appropriate to proceed, postpone the procedure or consider another approach.' );
$body[] = cil_religious_p( 'Our responsibility is to the patient as well as to the family requesting the circumcision.' );

$body[] = cil_religious_h( 'What should we bring?', 2 );
$body[] = cil_religious_p( 'The exact requirements depend on the patient\'s age.' );
$body[] = cil_religious_p( 'For babies and children, we may ask you to bring:' );
$body[] = cil_religious_list(
	array(
		esc_html( 'The identification and consent documents requested by the clinic' ),
		esc_html( 'Your child\'s Red Book where applicable' ),
		esc_html( 'Birth certificate or passport where available' ),
		esc_html( 'Details of medication and medical conditions' ),
		esc_html( 'Relevant medical letters' ),
		esc_html( 'For babies, clean nappies, wipes and feeding supplies' ),
		esc_html( 'Comfortable clothing for older children' ),
	)
);
$body[] = cil_religious_p( 'We will explain the exact requirements when your appointment is booked.' );

$body[] = cil_religious_h( 'Can family members attend?', 2 );
$body[] = cil_religious_p( 'We understand that circumcision can be an important family occasion.' );
$body[] = cil_religious_p( 'However, we are also a working clinical environment and need to maintain privacy, safety and sufficient space for staff and patients.' );
$body[] = cil_religious_p( 'If additional family members would like to attend, particularly where you are planning something around the religious or cultural significance of the circumcision, please discuss this with us before the appointment rather than assuming that a large group can be accommodated.' );

$body[] = cil_religious_h( 'Can a religious adviser attend?', 2 );
$body[] = cil_religious_p( 'If you would like a religious adviser or another person to be involved for religious reasons, contact us before booking.' );
$body[] = cil_religious_p( 'We can discuss what may be possible while maintaining the clinical environment, patient privacy, infection-control requirements and the safe conduct of the procedure.' );

$body[] = cil_religious_h( 'What languages are spoken at the clinic?', 2 );
$body[] = cil_religious_p( 'Our team speaks several languages, including:' );
$body[] = cil_religious_p( 'English, Arabic, Urdu, Hindi, Punjabi and Turkish.' );
$body[] = cil_religious_p( 'For many families, being able to discuss the procedure and aftercare in a familiar language makes the process easier.' );
$body[] = cil_religious_p( 'If English is not your first language, tell us when arranging your appointment and we will let you know whether a suitable member of the team is available.' );

$body[] = cil_religious_h( 'What happens after the circumcision?', 2 );
$body[] = cil_religious_p( 'Before you leave the clinic, we explain how to care for the circumcision at home and provide written aftercare information.' );
$body[] = cil_religious_p( 'The aftercare depends on the patient\'s age and the circumcision method used.' );
$body[] = cil_religious_p( 'For example, parents of a baby who has had a Plastibell or Circumplast circumcision need different instructions from an adult recovering from a forceps-guided circumcision with stitches.' );
$body[] = cil_religious_p( 'We therefore give you instructions appropriate to the actual procedure performed.' );
$body[] = cil_religious_p( 'We also make a next-day follow-up call and provide access to follow-up support during the healing period.' );
$body[] = cil_religious_link_p( 'Read our circumcision aftercare information →', $aftercare );

$body[] = cil_religious_h( 'Can we travel home after the circumcision?', 2 );
$body[] = cil_religious_p( 'Most patients go home shortly after their circumcision.' );
$body[] = cil_religious_p( 'Families regularly travel to us from across London and further afield.' );
$body[] = cil_religious_p( 'If you are travelling a long distance within the UK or coming from overseas, contact us before booking. We can discuss the patient\'s age, timing of travel, aftercare and what arrangements you should make in case a review is required.' );
$body[] = cil_religious_p( 'For patients travelling internationally, it is particularly important to consider how long you will remain locally after the procedure and how follow-up will be managed once you return home.' );

$body[] = cil_religious_h( 'Patients travelling from abroad', 2 );
$body[] = cil_religious_p( 'We see patients and families who travel to London specifically for circumcision.' );
$body[] = cil_religious_p( 'If you are travelling from another country, please contact us before arranging flights or accommodation.' );
$body[] = cil_religious_p( 'We can discuss:' );
$body[] = cil_religious_list(
	array(
		esc_html( 'The patient\'s age' ),
		esc_html( 'The likely circumcision method' ),
		esc_html( 'Medical history' ),
		esc_html( 'Documents required' ),
		esc_html( 'Appointment timing' ),
		esc_html( 'How long you should remain in the UK' ),
		esc_html( 'Aftercare' ),
		esc_html( 'What to do if you have concerns after returning home' ),
	)
);
$body[] = cil_religious_p( 'Do not arrange your return journey so tightly that there is no opportunity for review if one is required.' );

$body[] = cil_religious_h( 'What if my son has a medical problem as well?', 2 );
$body[] = cil_religious_p( 'A circumcision requested for religious reasons can sometimes reveal a medical or anatomical issue during assessment.' );
$body[] = cil_religious_p( 'For example, a patient may also have phimosis, BXO, a tight frenulum or buried penis.' );
$body[] = cil_religious_p( 'This can influence the technique, aftercare and sometimes the price of treatment.' );
$body[] = cil_religious_p( 'If you already know that your son has a foreskin or penile problem, tell us before booking so that we can advise you appropriately.' );

$body[] = cil_religious_h( 'When might we postpone or advise against circumcision?', 2 );
$body[] = cil_religious_p( 'We will not automatically perform a circumcision simply because it has religious or cultural importance to the family.' );
$body[] = cil_religious_p( 'We may postpone or advise against routine circumcision if:' );
$body[] = cil_religious_list(
	array(
		esc_html( 'The baby or child is unwell' ),
		esc_html( 'There is a known or suspected bleeding disorder' ),
		esc_html( 'There is a significant family history of abnormal bleeding' ),
		esc_html( 'Certain types of hypospadias or another anatomical condition is suspected' ),
		esc_html( 'The anatomy requires further specialist assessment' ),
		esc_html( 'An older child cannot safely cooperate with the procedure' ),
		esc_html( 'Appropriate consent has not been obtained' ),
		esc_html( 'There is another clinical reason why the practitioner believes treatment should be delayed' ),
	)
);
$body[] = cil_religious_p( 'If we advise you not to proceed, we will explain the reason and what we recommend doing next.' );

$body[] = cil_religious_h( 'How much does religious circumcision cost?', 2 );
$body[] = cil_religious_p( 'The price is based primarily on the patient\'s age and the complexity of the procedure, rather than whether the reason is religious, cultural or personal.' );
$body[] = cil_religious_p( 'More complex cases or patients with an additional medical or foreskin problem may require individual assessment.' );
$body[] = cil_religious_link_p( 'See our full circumcision price list →', $prices );

$body[] = cil_religious_h( 'Religious circumcision in North-West London', 2 );
$body[] = cil_religious_p( 'Our clinic is located at:' );
$body[] = cil_religious_p( 'Beverley Clinic, 78 Beverley Drive, Edgware, North-West London, HA8 5NE' );
$body[] = cil_religious_p( 'We see Muslim, Jewish and other religious and cultural communities from across London, the wider UK and overseas.' );
$body[] = cil_religious_p( 'The clinic has good public transport connections and parking nearby.' );
$body[] = cil_religious_p( 'If you are travelling a significant distance, contact us before booking so that we can discuss the practical arrangements.' );

$faq_items = array(
	array(
		'q' => 'Do you provide Muslim circumcision?',
		'a' => '<p>Yes. Religious circumcision is a significant part of our work, and our Muslim practitioners regularly perform circumcisions for Muslim babies, children, teenagers and adults.</p>',
	),
	array(
		'q' => 'Do you perform circumcision according to the Sunnah?',
		'a' => '<p>Our Muslim practitioners understand the religious importance of circumcision within Islam. If your family has a particular religious requirement, discuss it with us before booking so that we can confirm what we can provide within our clinical setting.</p>',
	),
	array(
		'q' => 'What age should a Muslim boy be circumcised?',
		'a' => '<p>Different Muslim families and communities follow different traditions. From a clinical perspective, where parents have already decided to circumcise, we generally recommend the first few weeks of life where possible. We also regularly circumcise older babies, children, teenagers and adults.</p>',
	),
	array(
		'q' => 'Do you welcome Jewish patients?',
		'a' => '<p>Yes. Jewish patients and families are welcome. If you require particular religious or ceremonial arrangements, contact us before booking so that we can discuss whether these can be accommodated within our clinical service.</p>',
	),
	array(
		'q' => 'Do you circumcise adults who have converted to Islam or Judaism?',
		'a' => '<p>Yes. We see adult patients choosing circumcision for religious reasons, including men who have converted to Islam or Judaism. The consultation and treatment are private and respectful.</p>',
	),
	array(
		'q' => 'Is religious circumcision performed with anaesthetic?',
		'a' => '<p>Yes. Circumcisions at our clinic are performed using local anaesthetic.</p>',
	),
	array(
		'q' => 'Is the procedure different for religious circumcision?',
		'a' => '<p>The reason for circumcision does not by itself determine the surgical method. The technique is selected according to the patient\'s age, anatomy and clinical assessment.</p>',
	),
	array(
		'q' => 'Can my baby have a Plastibell circumcision?',
		'a' => '<p>Many babies and toddlers are suitable for a Plastibell or Circumplast ring circumcision. We examine the child first and select the method and ring size we consider appropriate.</p>',
	),
	array(
		'q' => 'Can an older child be circumcised under local anaesthetic?',
		'a' => '<p>Many suitable older children and teenagers are treated under local anaesthetic at our clinic. We also consider the child\'s maturity, anxiety and ability to cooperate safely.</p>',
	),
	array(
		'q' => 'Do both parents need to agree to a child\'s religious circumcision?',
		'a' => '<p>Because non-medically necessary circumcision is permanent, consent and parental responsibility need to be clear before treatment. Therefore, both biological parents are required to consent. If you are a single parent or there are alterative child arrangement orders, please contact us with more information before booking.</p>',
	),
	array(
		'q' => 'What if the parents disagree?',
		'a' => '<p>Tell us before booking. We will not proceed while there is an unresolved disagreement about consent for a child\'s religious or cultural circumcision.</p>',
	),
	array(
		'q' => 'Can relatives come to the appointment?',
		'a' => '<p>Possibly, but numbers may need to be limited because this is a clinical environment. Contact us beforehand if additional family members would like to attend.</p>',
	),
	array(
		'q' => 'Can a religious adviser attend?',
		'a' => '<p>Discuss this with us before booking. We can explain what can be accommodated while maintaining patient safety, privacy and appropriate clinical conditions. If we do allow, it will only be in the waiting room and not inside the surgery room.</p>',
	),
	array(
		'q' => 'Do you provide aftercare?',
		'a' => '<p>Yes. Patients and parents receive detailed aftercare information, a next-day follow-up call and access to follow-up support during healing.</p>',
	),
	array(
		'q' => 'Can we travel from outside London?',
		'a' => '<p>Yes. We regularly see patients from outside London. If you are travelling a long distance, contact us beforehand so that we can discuss timing and follow-up.</p>',
	),
	array(
		'q' => 'Do you see international patients?',
		'a' => '<p>Yes. If you are travelling from overseas, contact us before arranging travel so that we can discuss the procedure, documentation, how long you should remain locally and arrangements for follow-up.</p>',
	),
);

$intro_paras = array(
	cil_religious_p( 'Religious and cultural circumcision is a large part of our day-to-day work at Beverley Clinic.' ),
	cil_religious_p( 'We welcome babies, children, teenagers and adults from all faiths, cultures and backgrounds and understand that circumcision can have an important religious, cultural and family significance beyond the procedure itself.' ),
	cil_religious_p( 'Our practitioners have extensive experience performing circumcision across different age groups. Our Muslim practitioners can provide circumcision for Muslim families who wish their son to be circumcised in accordance with their religious beliefs and traditions.' ),
	cil_religious_p( 'We also welcome Jewish families and patients from African, Filipino, Fijian and other communities where male circumcision is traditionally or culturally practised.' ),
	cil_religious_p( 'Whatever your reason for choosing circumcision, the same clinical standards apply: assessment before treatment, an age-appropriate circumcision technique, local anaesthetic, detailed aftercare and follow-up support during healing.' ),
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

$blocks   = array();
$blocks[] = cil_dyn_block(
	'cil/page-head',
	array(
		'eyebrow'    => 'All faiths and backgrounds · Six languages',
		'title'      => 'Religious and cultural circumcision in London',
		'lede'       => 'Religious and cultural circumcision is a large part of our day-to-day work at Beverley Clinic.',
		'reviewedBy' => cil_reviewed_by_haidar(),
		'crumbs'     => array(
			array(
				'label' => 'Who we see',
				'href'  => home_url( '/babies/' ),
			),
			array(
				'label' => 'Religious and cultural',
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
						'eyebrow' => 'Practical detail',
						'rows'    => array(
							array( 'k' => 'Sunnah practice', 'v' => 'Yes, by our Muslim practitioners' ),
							array( 'k' => 'All faiths', 'v' => 'Families and adult patients welcome' ),
							array( 'k' => 'Anaesthetic', 'v' => 'Local anaesthetic' ),
							array( 'k' => 'Aftercare', 'v' => 'Written aftercare and follow-up support' ),
							array( 'k' => 'Languages', 'v' => 'English, Arabic, Urdu, Hindi, Punjabi and Turkish' ),
							array( 'k' => 'Setting', 'v' => 'CQC registered clinic' ),
						),
						'note'    => '',
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
		'anchor' => 'religious-circumcision-guide',
	),
	array( cil_religious_body_group( $body ) )
);

$blocks[] = cil_dyn_block(
	'cil/faq',
	array(
		'heading' => 'Frequently asked questions about religious circumcision',
		'items'   => $faq_items,
	)
);

$blocks[] = cil_dyn_block(
	'cil/cta-band',
	array(
		'eyebrow'    => 'Talk to us',
		'title'      => 'Ask about a date',
		'text'       => 'If you are travelling a long distance, contact us before booking so we can discuss timing, documentation and follow-up arrangements.',
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
				'eyebrow' => 'By age',
				'heading' => 'The procedure itself, by age group',
				'lede'    => '',
				'display' => 'd-2',
			)
		),
		cil_dyn_block(
			'cil/group-cards',
			array(
				'level'   => 3,
				'exclude' => '',
				'banded'  => false,
			)
		),
	)
);

$content = cil_serialize_blocks( $blocks );

$page = get_page_by_path( 'religious', OBJECT, 'page' );
if ( ! $page ) {
	fwrite( STDERR, "Religious page not found\n" );
	exit( 1 );
}

kses_remove_filters();
remove_filter( 'content_save_pre', 'convert_invalid_entities' );
remove_filter( 'content_save_pre', 'balanceTags', 50 );
$result = wp_update_post(
	wp_slash(
		array(
			'ID'           => (int) $page->ID,
			'post_title'   => 'Religious and cultural circumcision in London',
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
$fixture      = cil_content_export_slug( 'religious', $replace_from, $replace_to );
if ( is_wp_error( $fixture ) ) {
	fwrite( STDERR, $fixture->get_error_message() . "\n" );
	exit( 1 );
}
$path = cil_content_write_fixture( 'religious', $fixture );
if ( is_wp_error( $path ) ) {
	fwrite( STDERR, $path->get_error_message() . "\n" );
	exit( 1 );
}

$v      = get_post( $page->ID )->post_content;
$checks = array(
	'Religious and cultural circumcision in London',
	'Muslim and Sunnah circumcision',
	'Khitan',
	'When should a Muslim baby be circumcised?',
	'Religious circumcision for older boys',
	'Religious circumcision for adults',
	'Jewish circumcision',
	'Circumcision as a cultural tradition',
	'African, Filipino, Fijian',
	'A religious procedure in a clinical environment',
	'What happens before the circumcision?',
	'What circumcision method do you use?',
	'Learn more about baby circumcision',
	'Learn more about circumcision for children and teenagers',
	'Learn more about adult circumcision',
	'Learn more about child circumcision',
	'Read our circumcision aftercare information',
	'See our full circumcision price list',
	'Why don\'t we use the same method for everyone?',
	'Consent for religious circumcision in children',
	'What if my child does not want to be circumcised?',
	'What should we bring?',
	'Can family members attend?',
	'Can a religious adviser attend?',
	'What languages are spoken at the clinic?',
	'Patients travelling from abroad',
	'What if my son has a medical problem as well?',
	'When might we postpone or advise against circumcision?',
	'How much does religious circumcision cost?',
	'Religious circumcision in North-West London',
	'Beverley Clinic, 78 Beverley Drive, Edgware, North-West London, HA8 5NE',
	'Frequently asked questions about religious circumcision',
	'Do you provide Muslim circumcision?',
	'Do you see international patients?',
	'/babies/',
	'/children/',
	'/adults/',
	'/aftercare/',
	'/prices/',
	'wp:cil/faq',
	'From £300',
);
foreach ( $checks as $k ) {
	$hay = $v;
	// group-cards prices come from theme at render; check rendered for £300
	if ( 'From £300' === $k ) {
		continue;
	}
	echo ( false !== strpos( $hay, $k ) ? 'OK' : 'MISS' ) . "  $k\n";
}
echo 'bytes=' . strlen( $v ) . "\n";
echo "fixture={$path}\n";
echo 'title=' . get_post( $page->ID )->post_title . "\n";
echo "OK\n";
