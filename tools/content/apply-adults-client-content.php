<?php
/**
 * Apply full client Adult Circumcision content to /adults/ + export fixture.
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
function cil_adults_p( $html ) {
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
function cil_adults_h( $text, $level = 2 ) {
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
 * @param array<int, string> $items Plain or HTML items (already escaped as needed).
 * @return array
 */
function cil_adults_list( $items ) {
	return cil_core_list( $items );
}

/**
 * @param string $label Link text.
 * @param string $path  Path.
 * @return array
 */
function cil_adults_link_p( $label, $path ) {
	$url = esc_url( home_url( $path ) );
	return cil_adults_p( '<a href="' . $url . '">' . esc_html( $label ) . '</a>' );
}

/**
 * @param string $label Link text.
 * @param string $path  Path.
 * @return string
 */
function cil_adults_a( $label, $path ) {
	return '<a href="' . esc_url( home_url( $path ) ) . '">' . esc_html( $label ) . '</a>';
}

/**
 * @param array<int, array> $inner Inner blocks.
 * @return array
 */
function cil_adults_body_group( $inner ) {
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

$phimosis      = '/conditions/phimosis/';
$balanitis     = '/conditions/balanitis/';
$bxo           = '/conditions/bxo/';
$frenuloplasty = '/procedures/frenuloplasty/';
$buried        = '/buried-penis/';
$revision      = '/re-circumcision/';
$prices        = '/prices/';

$body = array();

$body[] = cil_adults_h( 'Why do adults choose circumcision?', 2 );
$body[] = cil_adults_p( 'Men come to us for many different reasons.' );
$body[] = cil_adults_p( 'Some have wanted to be circumcised for many years for religious, cultural or personal reasons. Others seek treatment because they have developed a medical problem affecting the foreskin.' );
$body[] = cil_adults_p( 'Common reasons include:' );
$body[] = cil_adults_list(
	array(
		esc_html( 'Personal preference' ),
		esc_html( 'Religious or cultural reasons' ),
		cil_adults_a( 'Phimosis', $phimosis ) . esc_html( ' or a tight foreskin' ),
		esc_html( 'Painful erections caused by foreskin tightness' ),
		esc_html( 'Recurrent ' ) . cil_adults_a( 'balanitis', $balanitis ) . esc_html( ' or inflammation' ),
		cil_adults_a( 'BXO / lichen sclerosis', $bxo ),
		esc_html( 'Foreskin scarring' ),
		esc_html( 'Recurrent tearing or bleeding' ),
		esc_html( 'Paraphimosis' ),
		esc_html( 'Problems associated with a short or tight ' ) . cil_adults_a( 'frenulum', $frenuloplasty ),
		esc_html( 'Difficulty retracting the foreskin for hygiene' ),
		cil_adults_a( 'Revision of a previous circumcision', $revision ),
	)
);
$body[] = cil_adults_p( 'Whatever your reason, we will discuss it with you privately and without judgement.' );

$body[] = cil_adults_h( 'Circumcision for phimosis and a tight foreskin', 2 );
$body[] = cil_adults_p( cil_adults_a( 'Phimosis', $phimosis ) . esc_html( ' means that the foreskin is too tight to retract comfortably over the head of the penis (glans).' ) );
$body[] = cil_adults_p( 'In adults, phimosis can cause difficulty cleaning underneath the foreskin, discomfort during erections or sexual activity, recurrent inflammation, tearing or difficulty retracting the foreskin.' );
$body[] = cil_adults_p( 'The tightness may be concentrated in a band of foreskin around the opening. In other patients there may be more extensive scarring.' );
$body[] = cil_adults_p( 'When circumcision is being performed to treat phimosis, it is important to recognise and adequately remove the abnormal tight or scarred area rather than treating the procedure as a purely cosmetic circumcision.' );
$body[] = cil_adults_p( 'For this reason, we examine the foreskin before treatment and adapt the circumcision to the individual patient\'s anatomy.' );
$body[] = cil_adults_p( 'Circumcision is not necessarily the only treatment for every tight foreskin. Depending on the cause and severity, conservative treatment or a foreskin-preserving procedure may sometimes be considered.' );
$body[] = cil_adults_link_p( 'Learn more about phimosis →', $phimosis );

$body[] = cil_adults_h( 'Circumcision for recurrent balanitis', 2 );
$body[] = cil_adults_p( cil_adults_a( 'Balanitis', $balanitis ) . esc_html( ' is inflammation of the head of the penis (glans). It can cause redness, soreness, irritation, swelling, discharge or discomfort.' ) );
$body[] = cil_adults_p( 'There are several possible causes, and a single episode of balanitis does not automatically mean that circumcision is necessary.' );
$body[] = cil_adults_p( 'However, where episodes keep returning, particularly in association with a tight or scarred foreskin, circumcision may be considered as a longer-term treatment.' );
$body[] = cil_adults_p( 'We assess the foreskin and discuss whether circumcision is appropriate rather than assuming that every episode of balanitis requires surgery.' );
$body[] = cil_adults_link_p( 'Learn more about balanitis →', $balanitis );

$body[] = cil_adults_h( 'Circumcision for BXO / lichen sclerosis', 2 );
$body[] = cil_adults_p( cil_adults_a( 'BXO', $bxo ) . esc_html( ' (balanitis xerotica obliterans), also known as male genital lichen sclerosis, can cause whitening, thickening and scarring of the foreskin.' ) );
$body[] = cil_adults_p( 'As scarring progresses, the foreskin can become increasingly tight and difficult or impossible to retract.' );
$body[] = cil_adults_p( 'Where BXO significantly affects the foreskin, circumcision may be recommended. The appearance and extent of the affected tissue are assessed before treatment so that the procedure can be planned appropriately.' );
$body[] = cil_adults_link_p( 'Learn more about BXO / lichen sclerosis →', $bxo );

$body[] = cil_adults_h( 'What if the problem is a tight frenulum?', 2 );
$body[] = cil_adults_p( 'Not every patient with discomfort during erections or sexual activity needs a circumcision.' );
$body[] = cil_adults_p( 'The frenulum is the band of tissue on the underside of the penis connecting the foreskin to the head of the penis.' );
$body[] = cil_adults_p( 'If the frenulum is unusually short, tight or scarred, it can cause pulling, discomfort, tearing, bleeding or downward pulling of the glans during an erection.' );
$body[] = cil_adults_p( 'If the foreskin itself is healthy and retracts normally, a smaller procedure to release or lengthen the frenulum may be chosen by the patient rather than a circumcision.' );
$body[] = cil_adults_p( 'If both the foreskin and frenulum are causing problems, treatment can be planned accordingly.' );
$body[] = cil_adults_link_p( 'Learn more about tight frenulum and frenuloplasty →', $frenuloplasty );

$body[] = cil_adults_h( 'What happens at an adult circumcision appointment?', 2 );
$body[] = cil_adults_h( '1. Consultation and assessment', 3 );
$body[] = cil_adults_p( 'Before booking, we discuss why you want to be circumcised, your medical history and any particular concerns you have.' );
$body[] = cil_adults_p( 'The penis and foreskin are examined so that we can assess your anatomy and identify conditions such as phimosis, BXO, scarring, a tight frenulum or buried penis.' );
$body[] = cil_adults_p( 'This assessment is particularly important when circumcision is being performed for a medical problem or where you have already been circumcised and are considering revision.' );
$body[] = cil_adults_h( '2. Discussing the circumcision', 3 );
$body[] = cil_adults_p( 'We explain the technique we recommend and what you can reasonably expect from the procedure.' );
$body[] = cil_adults_p( 'If you have particular concerns about the amount of foreskin to be removed, the position of the final scar or treatment of the frenulum, discuss these with the practitioner before the procedure begins.' );
$body[] = cil_adults_p( 'Individual anatomy and the reason for circumcision place limits on what can safely be achieved, so no particular cosmetic result should be assumed or guaranteed.' );
$body[] = cil_adults_h( '3. Local anaesthetic', 3 );
$body[] = cil_adults_p( 'Adult circumcision at our clinic is performed under local anaesthetic.' );
$body[] = cil_adults_p( 'The anaesthetic is administered around the penis and given time to work before the procedure begins.' );
$body[] = cil_adults_p( 'We check the area before proceeding to make sure you do not feel any pain. The anaesthetic takes pain sensation but normal touch and pressure sensation which is painless still remains.' );
$body[] = cil_adults_h( '4. Circumcision', 3 );
$body[] = cil_adults_p( 'For adults we commonly use a forceps-guided circumcision technique with thermal cautery.' );
$body[] = cil_adults_p( 'The foreskin is carefully positioned according to the planned circumcision. A specialist surgical forceps helps guide the procedure and protect the underlying structures while the foreskin is removed.' );
$body[] = cil_adults_p( 'Thermal cautery is used to minimise bleeding.' );
$body[] = cil_adults_h( '5. Wound closure', 3 );
$body[] = cil_adults_p( 'Depending on the individual wound, we may use dissolvable stitches, medical skin glue or a combination of both' );
$body[] = cil_adults_p( 'Where dissolvable stitches are used, they normally disappear gradually as the wound heals and do not usually require removal.' );
$body[] = cil_adults_h( '6. Aftercare', 3 );
$body[] = cil_adults_p( 'Before you leave, we explain how to look after the wound and provide detailed written aftercare instructions.' );
$body[] = cil_adults_p( 'You can then go home and recover.' );

$body[] = cil_adults_h( 'Will I be awake during the circumcision?', 2 );
$body[] = cil_adults_p( 'Yes. Adult circumcision at our clinic is performed under local anaesthetic, which means you remain awake.' );
$body[] = cil_adults_p( 'Some patients prefer to talk to the practitioner during the procedure, while others prefer to watch videos, listen to music or simply relax.' );
$body[] = cil_adults_p( 'If you are particularly nervous about being awake for the procedure, tell us before booking so that we can discuss your concerns and whether treatment at our clinic is appropriate for you.' );

$body[] = cil_adults_h( 'Is adult circumcision painful?', 2 );
$body[] = cil_adults_p( 'Local anaesthetic is used to numb the penis before circumcision.' );
$body[] = cil_adults_p( 'You may still be aware of touch, pressure or movement even when the area is adequately anaesthetised, but the purpose of the local anaesthetic is to control procedural pain.' );
$body[] = cil_adults_p( 'Some soreness and discomfort are expected after the anaesthetic wears off because circumcision is a surgical procedure.' );
$body[] = cil_adults_p( 'We will explain appropriate pain relief and aftercare before you leave.' );

$body[] = cil_adults_h( 'How long does adult circumcision take?', 2 );
$body[] = cil_adults_p( 'The circumcision for adults usually takes 30-45 minutes. The exact procedure time varies according to the patient\'s anatomy and whether circumcision is being performed for a straightforward personal/religious reason or for a more complex medical problem.' );
$body[] = cil_adults_p( 'We do not rush the procedure.' );

$body[] = cil_adults_h( 'Can I choose how much foreskin is removed?', 2 );
$body[] = cil_adults_p( 'You are welcome to discuss your preferences with the practitioner before the procedure.' );
$body[] = cil_adults_p( 'However, circumcision is a surgical procedure rather than a standardised cosmetic product. The amount and position of skin that can safely be removed depend on your anatomy, the mobility of the penile skin, the reason for circumcision and any scarring or medical condition present.' );
$body[] = cil_adults_p( 'If circumcision is being performed for phimosis or BXO, adequately treating the abnormal foreskin takes priority over trying to reproduce a particular appearance seen in a photograph or on another patient.' );
$body[] = cil_adults_p( 'We will discuss what is realistically achievable before proceeding.' );

$body[] = cil_adults_h( 'What will my penis look like after circumcision?', 2 );
$body[] = cil_adults_p( 'Circumcision permanently changes the appearance of the penis because the foreskin is removed and the head of the penis (glans) remains exposed.' );
$body[] = cil_adults_p( 'Immediately after the procedure, the penis will not look like the final healed result.' );
$body[] = cil_adults_p( 'Swelling, bruising, stitches, skin glue and changes around the wound can temporarily make the circumcision look uneven or more prominent than expected.' );
$body[] = cil_adults_p( 'The appearance changes considerably as swelling settles and the scar matures.' );
$body[] = cil_adults_p( 'For this reason, it is important not to judge the final cosmetic result during the first few days or weeks of healing.' );

$body[] = cil_adults_h( 'What does normal healing look like?', 2 );
$body[] = cil_adults_p( 'Some swelling, bruising, tenderness and minor spotting or oozing can occur following circumcision.' );
$body[] = cil_adults_p( 'The glans can also feel unusually sensitive after being permanently exposed, particularly if it was previously covered by the foreskin most of the time.' );
$body[] = cil_adults_p( 'This sensitivity usually becomes easier to tolerate as you become accustomed to the change.' );
$body[] = cil_adults_p( 'The wound and scar continue to change for several weeks, so recovery should be thought of as a process rather than something that happens overnight.' );
$body[] = cil_adults_p( 'If you are unsure whether something you are seeing is part of normal healing, contact us.' );

$body[] = cil_adults_h( 'How long does adult circumcision take to heal?', 2 );
$body[] = cil_adults_p( 'Healing varies between patients.' );
$body[] = cil_adults_p( 'The surface wound begins healing relatively quickly, but complete recovery and maturation of the scar take longer.' );
$body[] = cil_adults_p( 'You should expect the appearance and sensitivity to continue changing over several weeks. Swelling can also take time to disappear completely.' );
$body[] = cil_adults_p( 'Returning to desk work, returning to the gym and returning to sexual activity are therefore separate stages of recovery.' );
$body[] = cil_adults_p( 'Follow the individual instructions we give you rather than deciding that the circumcision is completely healed simply because it looks better.' );

$body[] = cil_adults_h( 'What happens to the stitches?', 2 );
$body[] = cil_adults_p( 'Where stitches are required, we normally use dissolvable stitches.' );
$body[] = cil_adults_p( 'They gradually loosen and disappear as healing progresses and do not normally need to be removed.' );
$body[] = cil_adults_p( 'Do not pull at a stitch because it appears loose.' );
$body[] = cil_adults_p( 'Medical skin glue may also be used. This should similarly be allowed to separate naturally rather than being picked or peeled away.' );
$body[] = cil_adults_p( 'If a stitch remains after the expected healing period or you are concerned about it, contact us.' );

$body[] = cil_adults_h( 'What should I wear after circumcision?', 2 );
$body[] = cil_adults_p( 'Wear comfortable clothing for the journey home and during the early recovery period.' );
$body[] = cil_adults_p( 'Some men prefer loose clothing because it reduces rubbing, while supportive underwear can help keep the penis in a comfortable position upright to reduce swelling and also reduce unnecessary movement.' );
$body[] = cil_adults_p( 'We will give you practical advice according to the dressing and wound closure used.' );

$body[] = cil_adults_h( 'When can I shower after circumcision?', 2 );
$body[] = cil_adults_p( 'We usually recommend to keep the circumcision dry for the first 3 days. After 3 days, you can have a quick shower and then dab the penis dry afterwards.' );
$body[] = cil_adults_p( 'Keeping the area clean is important, but the healing wound should not be aggressively washed, scrubbed or soaked.' );
$body[] = cil_adults_p( 'Follow the instructions we give you about showering, bathing, dressings and drying the area.' );
$body[] = cil_adults_p( 'Do not apply antiseptics, creams (apart from Vaseline) or other products to the wound unless we have advised you to use them.' );

$body[] = cil_adults_h( 'What happens when I get an erection after circumcision?', 2 );
$body[] = cil_adults_p( 'Erections continue normally after circumcision and cannot always be prevented, particularly erections that occur naturally during sleep.' );
$body[] = cil_adults_p( 'During the early healing period, an erection can produce tightness or pulling around the wound and stitches and may be uncomfortable.' );
$body[] = cil_adults_p( 'This does not automatically mean that the circumcision has been damaged.' );

$body[] = cil_adults_h( 'When can I return to work?', 2 );
$body[] = cil_adults_p( 'This depends largely on the type of work you do and how you feel.' );
$body[] = cil_adults_p( 'Patients with desk-based or sedentary jobs may be able to return sooner than someone whose work involves heavy lifting, running, prolonged physical activity or significant movement.' );
$body[] = cil_adults_p( 'Allow yourself time to recover rather than planning an important physical work commitment immediately after the procedure.' );
$body[] = cil_adults_p( 'We can provide a work letter where appropriate.' );

$body[] = cil_adults_h( 'When can I drive?', 2 );
$body[] = cil_adults_p( 'You should only return to driving when you can sit comfortably, concentrate normally and perform an emergency stop without pain or hesitation.' );
$body[] = cil_adults_p( 'You should also make sure that any medication you are taking does not impair your ability to drive.' );
$body[] = cil_adults_p( 'If in doubt, check your motor insurance requirements as well as following the clinical advice given to you.' );

$body[] = cil_adults_h( 'When can I return to the gym and exercise?', 2 );
$body[] = cil_adults_p( 'Walking and normal gentle activity are different from strenuous exercise. We normally advise at least 2 weeks of no physical activity and sometimes longer if the wound is not fully healed.' );
$body[] = cil_adults_p( 'Heavy lifting, running, cycling, contact sport and vigorous gym activity can place additional strain, movement or friction around a healing circumcision wound.' );
$body[] = cil_adults_p( 'We therefore recommend returning to exercise gradually and according to the healing of your individual wound.' );
$body[] = cil_adults_p( 'Do not assume that because you feel comfortable walking around, the wound is ready for heavy exercise.' );

$body[] = cil_adults_h( 'When can I have sex after circumcision?', 2 );
$body[] = cil_adults_p( 'Sexual activity should be avoided until the circumcision wound has healed sufficiently. This is at least 2 weeks and can sometimes take up to 6 weeks depending on your body’s healing.' );
$body[] = cil_adults_p( 'This includes penetrative sex and other sexual activity that places tension or friction on the healing wound.' );
$body[] = cil_adults_p( 'Returning too early can cause pain, bleeding or wound disruption.' );
$body[] = cil_adults_p( 'We will advise you according to your individual healing.' );

$body[] = cil_adults_h( 'When can I masturbate after circumcision?', 2 );
$body[] = cil_adults_p( 'For the same reason, masturbation should be avoided while the wound is healing.' );
$body[] = cil_adults_p( 'The friction and tension produced during masturbation can place stress on the circumcision line and stitches.' );
$body[] = cil_adults_p( 'As a general guide, avoid masturbation for at least 2 weeks and until the wound is fully healed.' );
$body[] = cil_adults_p( 'If healing is taking longer, wait longer.' );

$body[] = cil_adults_h( 'Will circumcision affect sex or sensitivity?', 2 );
$body[] = cil_adults_p( 'This is an important question and deserves a realistic answer.' );
$body[] = cil_adults_p( 'Circumcision permanently removes the foreskin, so the penis will both look and feel different afterwards. The glans remains exposed and the skin of the penis no longer moves in exactly the same way that it did when the foreskin was present.' );
$body[] = cil_adults_p( 'During the early weeks, the glans may feel particularly sensitive because it is newly exposed. This usually becomes less noticeable with time.' );
$body[] = cil_adults_p( 'Individual experiences of sexual sensation after circumcision vary. We therefore do not promise that circumcision will increase or decrease sexual pleasure.' );

$body[] = cil_adults_h( 'Can circumcision improve painful erections?', 2 );
$body[] = cil_adults_p( 'If erections are painful because a tight foreskin or scarred band of foreskin is being stretched, treating the underlying foreskin problem can remove that source of tightness.' );
$body[] = cil_adults_p( 'However, painful erections can have different causes.' );
$body[] = cil_adults_p( 'An examination is therefore important rather than assuming that circumcision will resolve every type of penile pain.' );

$body[] = cil_adults_h( 'Can circumcision help with hygiene?', 2 );
$body[] = cil_adults_p( 'Removing the foreskin means there is no longer a space underneath it that needs to be exposed for cleaning.' );
$body[] = cil_adults_p( 'This can make genital hygiene simpler, particularly for men who have difficulty retracting a tight foreskin.' );
$body[] = cil_adults_p( 'However, circumcision does not remove the need for normal personal hygiene. The penis should still be washed regularly after the circumcision has fully healed. In cases of buried penis, the shaft skin will still need to be pulled back daily for normal cleaning and drying.' );

$body[] = cil_adults_h( 'Does circumcision protect against sexually transmitted infections?', 2 );
$body[] = cil_adults_p( 'Circumcision should not be regarded as a substitute for safer-sex practices.' );
$body[] = cil_adults_p( 'Whatever your circumcision status, condoms and appropriate sexual-health testing remain important for reducing the risk of sexually transmitted infections.' );
$body[] = cil_adults_p( 'If you have symptoms that could represent a sexually transmitted infection, you may need assessment through your GP or a sexual health service rather than assuming that the problem is caused by your foreskin.' );

$body[] = cil_adults_h( 'Do I need an STI test before circumcision?', 2 );
$body[] = cil_adults_p( 'Not routinely simply because you are having a circumcision.' );
$body[] = cil_adults_p( 'However, symptoms such as discharge, genital sores, unusual lesions, pain when passing urine or recent sexual exposure may require appropriate investigation before surgery.' );
$body[] = cil_adults_p( 'Tell us if you have symptoms or an active infection when arranging your appointment.' );

$body[] = cil_adults_h( 'What if I have diabetes?', 2 );
$body[] = cil_adults_p( 'Diabetes can affect infection risk and wound healing, particularly when blood glucose is poorly controlled.' );
$body[] = cil_adults_p( 'Tell us if you have diabetes or take medication for it when booking your appointment.' );
$body[] = cil_adults_p( 'We may need additional information before deciding whether it is appropriate to proceed.' );

$body[] = cil_adults_h( 'What if I take blood-thinning medication?', 2 );
$body[] = cil_adults_p( 'Tell us before your appointment if you take medication that affects bleeding or clotting.' );
$body[] = cil_adults_p( 'This includes prescribed anticoagulant or antiplatelet medication.' );
$body[] = cil_adults_p( 'Do not stop prescribed medication yourself simply because you are planning a circumcision. Any changes should be discussed with the clinician responsible for your medication and our practitioner.' );
$body[] = cil_adults_p( 'Also tell us if you have a bleeding disorder or have previously experienced unusual bleeding after surgery or dental treatment.' );

$body[] = cil_adults_h( 'What if I have a buried penis?', 2 );
$body[] = cil_adults_p( 'A ' . cil_adults_a( 'buried or hidden penis', $buried ) . esc_html( ' is where some or all of the penis is concealed within the surrounding pubic tissue.' ) );
$body[] = cil_adults_p( 'Recognising this before circumcision is important because it can affect the amount of skin that can safely be removed and the aftercare required afterwards.' );
$body[] = cil_adults_p( 'We assess this before treatment.' );
$body[] = cil_adults_p( 'In most patients, circumcision can still be performed with appropriate planning and specific aftercare.' );
$body[] = cil_adults_link_p( 'Learn more about buried penis →', $buried );

$body[] = cil_adults_h( 'Can you revise a previous circumcision?', 2 );
$body[] = cil_adults_p( 'Yes. We assess men who have already been circumcised but are concerned about the result or have developed a problem.' );
$body[] = cil_adults_p( 'Reasons for seeking ' . cil_adults_a( 're-circumcision', $revision ) . esc_html( ' or circumcision revision can include excess remaining foreskin, uneven skin, troublesome scarring or another problem identified on examination.' ) );
$body[] = cil_adults_p( 'Revision circumcision is different from a first circumcision because the amount and distribution of remaining skin and existing scar tissue need to be considered carefully.' );
$body[] = cil_adults_p( 'An assessment is therefore required before we can advise what can safely and realistically be improved.' );
$body[] = cil_adults_link_p( 'Learn more about re-circumcision and revision →', $revision );

$body[] = cil_adults_h( 'What are the possible complications of adult circumcision?', 2 );
$body[] = cil_adults_p( 'Circumcision is a surgical procedure and complications can occur.' );
$body[] = cil_adults_p( 'Potential problems include but not limited to bleeding, infection, swelling, wound separation, delayed healing, scarring, altered sensation or dissatisfaction with the cosmetic result. Further treatment is occasionally required following a complication or where a patient is unhappy with the healed result.' );
$body[] = cil_adults_p( 'Your penis will permanently look and feel different after removal of the foreskin.' );
$body[] = cil_adults_p( 'We give you the relevant risks as part of the consent process so that you can make an informed decision before proceeding.' );

$body[] = cil_adults_h( 'When should I contact the clinic after circumcision?', 2 );
$body[] = cil_adults_p( 'Contact us if:' );
$body[] = cil_adults_list(
	array(
		esc_html( 'You have continuous bleeding that does not stop with pressure' ),
		esc_html( 'Pain is severe or getting worse rather than improving' ),
		esc_html( 'The wound feels unusually hot' ),
		esc_html( 'You develop a fever or feel unwell' ),
		esc_html( 'You have difficulty passing urine' ),
		esc_html( 'You are worried about the appearance or healing of the circumcision' ),
	)
);

$body[] = cil_adults_h( 'What follow-up do we provide?', 2 );
$body[] = cil_adults_p( 'Our care does not end when you leave the clinic.' );
$body[] = cil_adults_p( 'You receive detailed aftercare information explaining how to look after the circumcision and what to expect during recovery.' );
$body[] = cil_adults_p( 'We make a next-day follow-up call to check how you are feeling and whether you have any questions.' );
$body[] = cil_adults_p( 'If something needs to be examined during healing, we can arrange a follow-up appointment where clinically appropriate.' );

$body[] = cil_adults_h( 'How much does adult circumcision cost?', 2 );
$body[] = cil_adults_p( 'Our current prices are:' );
$body[] = cil_adults_list(
	array(
		esc_html( 'Adult circumcision with no medical or foreskin problem — £680' ),
		esc_html( 'Adult circumcision with frenulum removal — £880' ),
		esc_html( 'Adult circumcision with a medical/foreskin problem, with or without frenulum treatment — £1,080' ),
	)
);
$body[] = cil_adults_p( 'More complex procedures, including revision of a previous circumcision, may require an individual assessment and quotation.' );
$body[] = cil_adults_link_p( 'See our full circumcision price list →', $prices );

$body[] = cil_adults_h( 'Private adult circumcision in North-West London', 2 );
$body[] = cil_adults_p( 'Beverley Clinic is located at:' );
$body[] = cil_adults_p( '78 Beverley Drive, Edgware, North-West London, HA8 5NE' );
$body[] = cil_adults_p( 'We see adult patients from across London, elsewhere in the UK and from abroad.' );
$body[] = cil_adults_p( 'If you are travelling a significant distance, contact us before booking if you would like to discuss your suitability, travel arrangements or follow-up.' );

$faq_items = array(
	array(
		'q' => 'Am I too old to be circumcised?',
		'a' => '<p>There is no single upper age limit based purely on age. Your general health, medical history, medication, anatomy and reason for circumcision are more important considerations. Suitability is assessed individually.</p>',
	),
	array(
		'q' => 'Is adult circumcision performed under local anaesthetic?',
		'a' => '<p>At our clinic, suitable adult patients are normally circumcised using local anaesthetic and remain awake during the procedure.</p>',
	),
	array(
		'q' => 'Will I need stitches?',
		'a' => '<p>Many adult circumcisions require dissolvable stitches. Depending on the wound, we may use stitches, medical skin glue, a combination of both, or occasionally neither.</p>',
	),
	array(
		'q' => 'Do the stitches need to be removed?',
		'a' => '<p>Dissolvable stitches normally disappear gradually during healing and do not usually need to be removed.</p>',
	),
	array(
		'q' => 'How long does circumcision take to heal?',
		'a' => '<p>Initial wound healing occurs before the final appearance is reached. Expect recovery to continue over several weeks, with complete healing commonly taking around four to six weeks and sometimes longer.</p>',
	),
	array(
		'q' => 'When can I return to work?',
		'a' => '<p>This depends on your occupation and recovery. Someone with a desk job may be able to return sooner than someone performing heavy manual work.</p>',
	),
	array(
		'q' => 'When can I go back to the gym?',
		'a' => '<p>Strenuous exercise, heavy lifting, cycling and activities that cause friction around the groin should be avoided during early healing. Return gradually according to the advice we give you.</p>',
	),
	array(
		'q' => 'When can I have sex after circumcision?',
		'a' => '<p>As a general guide, avoid sexual intercourse for at least 2 weeks and until the wound is fully healed. Some patients need longer.</p>',
	),
	array(
		'q' => 'When can I masturbate?',
		'a' => '<p>Avoid masturbation for at least 2 weeks and until the wound is fully healed because friction and tension can disrupt the healing circumcision line.</p>',
	),
	array(
		'q' => 'Will erections damage the stitches?',
		'a' => '<p>Erections commonly occur naturally during recovery and can feel tight or uncomfortable. They do not automatically damage the wound, but contact us if an erection causes significant bleeding or wound separation.</p>',
	),
	array(
		'q' => 'Will circumcision affect sensitivity?',
		'a' => '<p>The penis will feel different after circumcision because the foreskin has been removed and the glans remains exposed. Individual experiences of sensation and sexual function vary, so we do not promise a particular change in sensitivity or sexual pleasure.</p>',
	),
	array(
		'q' => 'Can circumcision cure phimosis?',
		'a' => '<p>Circumcision removes the foreskin and therefore can provide a definitive surgical treatment where problematic phimosis arises from the foreskin itself.</p>',
	),
	array(
		'q' => 'Can you remove my frenulum at the same time?',
		'a' => '<p>If the frenulum is tight, scarred or contributing to the problem, treatment can be discussed as part of your assessment. Not every patient needs frenulum treatment.</p>',
	),
	array(
		'q' => 'Can you correct a circumcision performed elsewhere?',
		'a' => '<p>We assess patients seeking re-circumcision or revision. Whether further surgery is appropriate depends on the remaining skin, existing scar and what you would like corrected.</p>',
	),
	array(
		'q' => 'Can I speak to someone before I book?',
		'a' => '<p>Yes. You are welcome to contact us before making an appointment. If you have questions about the procedure, medical suitability, recovery or which treatment you may need, we would rather discuss these with you before you book.</p>',
	),
);

$intro_paras = array(
	cil_adults_p( 'We provide adult circumcision in North-West London for men aged 18 and over who are considering circumcision for medical, religious, cultural or personal reasons.' ),
	cil_adults_p( 'We understand that deciding to have a circumcision as an adult can feel very different from having the procedure as a child. You may have questions about the procedure itself, pain, appearance, erections, sensitivity, time off work, exercise and when you can return to sexual activity.' ),
	cil_adults_p( 'At Beverley Clinic, we aim to answer these questions openly before you decide to proceed.' ),
	cil_adults_p( 'Adult circumcision at our clinic is normally performed under local anaesthetic, so you remain awake throughout the procedure. We commonly use a forceps-guided circumcision technique with thermal cautery, with dissolvable stitches and/or medical skin glue used where appropriate.' ),
	cil_adults_p( 'Every patient is assessed individually so that the circumcision can be planned according to his anatomy, reason for treatment and any medical condition affecting the foreskin or penis.' ),
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

$spec_note = 'From £680, or £880 with frenulum removal, or £1,080 with a medical or foreskin problem. See the <a href="' . esc_url( home_url( $prices ) ) . '" style="color:var(--blue-deep)">full price list</a>.';

$blocks   = array();
$blocks[] = cil_dyn_block(
	'cil/page-head',
	array(
		'eyebrow'    => 'Eighteen and over · From £680',
		'title'      => 'Adult circumcision in London',
		'lede'       => 'We provide adult circumcision in North-West London for men aged 18 and over who are considering circumcision for medical, religious, cultural or personal reasons.',
		'reviewedBy' => cil_reviewed_by_haidar(),
		'crumbs'     => array(
			array(
				'label' => 'Who we see',
				'href'  => home_url( '/babies/' ),
			),
			array(
				'label' => 'Adult men',
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
							array( 'k' => 'Anaesthetic', 'v' => 'Local anaesthetic' ),
							array( 'k' => 'During', 'v' => 'Awake; talk, videos or music' ),
							array( 'k' => 'Common method', 'v' => 'Forceps-guided with thermal cautery' ),
							array( 'k' => 'Closure', 'v' => 'Dissolvable stitches and/or skin glue' ),
							array( 'k' => 'Follow-up', 'v' => 'Next-day call; appointments if needed' ),
							array( 'k' => 'Price', 'v' => '£680, £880 or £1,080' ),
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
		'anchor' => 'adult-circumcision-guide',
	),
	array( cil_adults_body_group( $body ) )
);

$blocks[] = cil_dyn_block(
	'cil/faq',
	array(
		'heading' => 'Frequently asked questions about adult circumcision',
		'items'   => $faq_items,
	)
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
						'name'    => 'certificates',
						'caption' => 'Qualifications hang in the corridor. Ask to see any of them.',
						'alt'     => 'Framed qualifications along the clinic corridor at the Edgware practice.',
					)
				),
				cil_dyn_block(
					'cil/callback-card',
					array(
						'eyebrow' => 'Request a call back',
						'title'   => 'Ask us first',
						'formId'  => 'adults',
						'subject' => 'adult men enquiry',
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
				'exclude' => home_url( '/adults/' ),
				'banded'  => false,
			)
		),
	)
);

$content = cil_serialize_blocks( $blocks );

$page = get_page_by_path( 'adults', OBJECT, 'page' );
if ( ! $page ) {
	fwrite( STDERR, "Adults page not found\n" );
	exit( 1 );
}

kses_remove_filters();
remove_filter( 'content_save_pre', 'convert_invalid_entities' );
remove_filter( 'content_save_pre', 'balanceTags', 50 );
$result = wp_update_post(
	wp_slash(
		array(
			'ID'           => (int) $page->ID,
			'post_title'   => 'Adult circumcision in London',
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
$fixture      = cil_content_export_slug( 'adults', $replace_from, $replace_to );
if ( is_wp_error( $fixture ) ) {
	fwrite( STDERR, $fixture->get_error_message() . "\n" );
	exit( 1 );
}
$path = cil_content_write_fixture( 'adults', $fixture );
if ( is_wp_error( $path ) ) {
	fwrite( STDERR, $path->get_error_message() . "\n" );
	exit( 1 );
}

$v      = get_post( $page->ID )->post_content;
$checks = array(
	'Adult circumcision in London',
	'Why do adults choose circumcision?',
	'Circumcision for phimosis and a tight foreskin',
	'Circumcision for recurrent balanitis',
	'Circumcision for BXO / lichen sclerosis',
	'What if the problem is a tight frenulum?',
	'What happens at an adult circumcision appointment?',
	'1. Consultation and assessment',
	'6. Aftercare',
	'Will I be awake during the circumcision?',
	'Is adult circumcision painful?',
	'How long does adult circumcision take?',
	'Can I choose how much foreskin is removed?',
	'What will my penis look like after circumcision?',
	'What does normal healing look like?',
	'How long does adult circumcision take to heal?',
	'What happens to the stitches?',
	'What should I wear after circumcision?',
	'When can I shower after circumcision?',
	'What happens when I get an erection after circumcision?',
	'When can I return to work?',
	'When can I drive?',
	'When can I return to the gym and exercise?',
	'When can I have sex after circumcision?',
	'When can I masturbate after circumcision?',
	'Will circumcision affect sex or sensitivity?',
	'Can circumcision improve painful erections?',
	'Can circumcision help with hygiene?',
	'Does circumcision protect against sexually transmitted infections?',
	'Do I need an STI test before circumcision?',
	'What if I have diabetes?',
	'What if I take blood-thinning medication?',
	'What if I have a buried penis?',
	'Can you revise a previous circumcision?',
	'What are the possible complications of adult circumcision?',
	'When should I contact the clinic after circumcision?',
	'What follow-up do we provide?',
	'How much does adult circumcision cost?',
	'Adult circumcision with no medical or foreskin problem — £680',
	'Adult circumcision with frenulum removal — £880',
	'Adult circumcision with a medical/foreskin problem, with or without frenulum treatment — £1,080',
	'Private adult circumcision in North-West London',
	'78 Beverley Drive, Edgware, North-West London, HA8 5NE',
	'Frequently asked questions about adult circumcision',
	'Am I too old to be circumcised?',
	'Can I speak to someone before I book?',
	'Learn more about phimosis',
	'Learn more about balanitis',
	'Learn more about BXO / lichen sclerosis',
	'Learn more about tight frenulum and frenuloplasty',
	'Learn more about buried penis',
	'Learn more about re-circumcision and revision',
	'See our full circumcision price list',
	'/conditions/phimosis/',
	'/conditions/balanitis/',
	'/conditions/bxo/',
	'/procedures/frenuloplasty/',
	'/buried-penis/',
	'/re-circumcision/',
	'/prices/',
	'forceps-guided circumcision technique with thermal cautery',
	'wp:cil/faq',
);
foreach ( $checks as $k ) {
	echo ( false !== strpos( $v, $k ) ? 'OK' : 'MISS' ) . "  $k\n";
}
echo 'bytes=' . strlen( $v ) . "\n";
echo "fixture={$path}\n";
echo "title=" . get_post( $page->ID )->post_title . "\n";
echo "OK\n";
