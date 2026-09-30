<?php
/**
 * Apply full client Re-circumcision content to /re-circumcision/ + export fixture.
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
function cil_recirc_p( $html ) {
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
function cil_recirc_h( $text, $level = 2 ) {
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
 * @param array<int, string> $items Escaped items.
 * @return array
 */
function cil_recirc_list( $items ) {
	return cil_core_list( $items );
}

/**
 * @param string $label Link text.
 * @param string $path  Path.
 * @return array
 */
function cil_recirc_link_p( $label, $path ) {
	$url = esc_url( home_url( $path ) );
	return cil_recirc_p( '<a href="' . $url . '">' . esc_html( $label ) . '</a>' );
}

/**
 * @param array<int, array> $inner Inner blocks.
 * @return array
 */
function cil_recirc_body_group( $inner ) {
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
$bxo           = '/conditions/bxo/';
$frenuloplasty = '/procedures/frenuloplasty/';
$buried        = '/buried-penis/';
$book          = '/book/';

$body = array();

$body[] = cil_recirc_h( 'What is re-circumcision?', 2 );
$body[] = cil_recirc_p( 'Re-circumcision, sometimes called circumcision revision, is a further procedure performed after a previous circumcision.' );
$body[] = cil_recirc_p( 'It may involve removing additional foreskin or excess skin, removing or revising troublesome scar tissue, correcting an uneven circumcision line or addressing another problem identified during examination.' );
$body[] = cil_recirc_p( 'The procedure is individually planned because no two previous circumcisions are exactly the same.' );
$body[] = cil_recirc_p( 'Some patients need only a relatively small correction, while others require more extensive revision.' );

$body[] = cil_recirc_h( 'Why do people consider circumcision revision?', 2 );
$body[] = cil_recirc_p( 'There are several reasons why somebody may be unhappy with a previous circumcision.' );
$body[] = cil_recirc_p( 'These can include:' );
$body[] = cil_recirc_list(
	array(
		esc_html( 'Too much foreskin or loose skin remaining' ),
		esc_html( 'An incomplete circumcision' ),
		esc_html( 'An uneven circumcision line' ),
		esc_html( 'One side appearing longer than the other' ),
		esc_html( 'A bulky, raised or irregular scar' ),
		esc_html( 'Excess or folded skin around the circumcision line' ),
		esc_html( 'Tight or uncomfortable scar tissue' ),
		esc_html( 'Skin bridge' ),
		esc_html( 'A remaining tight or troublesome frenulum' ),
		esc_html( 'Persistent foreskin tightness or scarring' ),
		esc_html( 'A cosmetic result that is different from what the patient expected' ),
		esc_html( 'A previous circumcision that requires further correction' ),
	)
);
$body[] = cil_recirc_p( 'Sometimes a patient simply knows that something does not look or feel right but does not know exactly what the problem is.' );
$body[] = cil_recirc_p( 'That is what the assessment is for.' );

$body[] = cil_recirc_h( 'Too much foreskin left after circumcision', 2 );
$body[] = cil_recirc_p( 'One of the most common reasons patients enquire about re-circumcision is that they feel too much foreskin or loose skin remains.' );
$body[] = cil_recirc_p( 'The head of the penis may still be partly covered when the penis is soft, or the remaining skin may bunch behind or around the glans.' );
$body[] = cil_recirc_p( 'Whether additional skin can safely be removed depends on how much movable penile skin remains when the penis is both flaccid and erect.' );
$body[] = cil_recirc_p( 'It is important not simply to remove as much skin as possible. Removing too much can create excessive tightness, particularly during an erection.' );
$body[] = cil_recirc_p( 'We therefore assess the amount and distribution of remaining skin before deciding whether further removal is appropriate.' );

$body[] = cil_recirc_h( 'Uneven circumcision', 2 );
$body[] = cil_recirc_p( 'Sometimes the circumcision line is higher on one side than the other, or more skin has been left in one area.' );
$body[] = cil_recirc_p( 'A small degree of asymmetry is common in the human body and does not necessarily require treatment.' );
$body[] = cil_recirc_p( 'However, where the difference is significant or bothersome, it may be possible to remove additional skin and reposition or reshape the circumcision line to produce a more balanced result.' );
$body[] = cil_recirc_p( 'What can be achieved depends on the existing scar and the amount of skin available.' );

$body[] = cil_recirc_h( 'Circumcision scar revision', 2 );
$body[] = cil_recirc_p( 'Every circumcision produces a permanent scar.' );
$body[] = cil_recirc_p( 'In most patients the scar gradually softens and becomes less noticeable as it matures. In some patients, however, the scar may remain raised, thick, uneven, puckered, uncomfortable or visually prominent.' );
$body[] = cil_recirc_p( 'Where appropriate, troublesome scar tissue can sometimes be removed as part of circumcision revision and the wound carefully re-closed.' );
$body[] = cil_recirc_p( 'It is important to understand that scar revision replaces one surgical scar with another. We cannot promise an invisible scar or guarantee exactly how an individual\'s skin will heal.' );
$body[] = cil_recirc_p( 'If you naturally form thick or raised scars such as keloids, tell us during your consultation.' );

$body[] = cil_recirc_h( 'Skin bridges and adhesions after circumcision', 2 );
$body[] = cil_recirc_p( 'Occasionally, healing after circumcision can result in penile skin becoming attached to the head of the penis.' );
$body[] = cil_recirc_p( 'A minor adhesion and a more established skin bridge are not necessarily the same problem.' );
$body[] = cil_recirc_p( 'Some adhesions may improve or require little treatment, whereas a mature skin bridge may require a procedure to divide it.' );
$body[] = cil_recirc_p( 'If you believe you have a skin bridge or adhesion, we can examine it and explain whether treatment is necessary and whether this can be dealt with independently or as part of a wider circumcision revision.' );

$body[] = cil_recirc_h( 'Can you correct a tight circumcision?', 2 );
$body[] = cil_recirc_p( 'A patient can occasionally feel that a previous circumcision is too tight, particularly during an erection.' );
$body[] = cil_recirc_p( 'This requires careful assessment.' );
$body[] = cil_recirc_p( 'If too much skin has already been removed, the solution is not simply another circumcision, because removing additional skin could make the problem worse.' );
$body[] = cil_recirc_p( 'We need to determine whether the tightness comes from the circumcision scar, remaining foreskin, the frenulum, another area of scar tissue or a shortage of mobile penile skin.' );
$body[] = cil_recirc_p( 'We will explain what we believe is causing the problem and whether further surgery is likely to help.' );

$body[] = cil_recirc_h( 'Phimosis or tight foreskin after a previous circumcision', 2 );
$body[] = cil_recirc_p( 'Some patients have been partially circumcised or have enough foreskin remaining that they can still develop troublesome tightness or scarring.' );
$body[] = cil_recirc_p( 'Where a tight or scarred band of remaining foreskin is present, completing or revising the circumcision may provide a more definitive solution.' );
$body[] = cil_recirc_p( 'This is particularly important where there is evidence of BXO / lichen sclerosus or significant foreskin scarring, because the abnormal tissue needs to be recognised during planning.' );
$body[] = cil_recirc_link_p( 'Learn more about phimosis →', $phimosis );
$body[] = cil_recirc_link_p( 'Learn more about BXO / lichen sclerosus →', $bxo );

$body[] = cil_recirc_h( 'What if the problem is the frenulum?', 2 );
$body[] = cil_recirc_p( 'Sometimes the circumcision itself is satisfactory but the frenulum on the underside of the penis remains short, tight or scarred.' );
$body[] = cil_recirc_p( 'A tight frenulum can cause pulling, discomfort, tearing, bleeding or downward movement of the glans during an erection.' );
$body[] = cil_recirc_p( 'In this situation, a complete re-circumcision may not be necessary.' );
$body[] = cil_recirc_p( 'Treatment of the frenulum alone may be more appropriate.' );
$body[] = cil_recirc_p( 'Alternatively, if both the circumcision and frenulum require correction, they can be considered together when planning the revision.' );
$body[] = cil_recirc_link_p( 'Learn more about tight frenulum and frenuloplasty →', $frenuloplasty );

$body[] = cil_recirc_h( 'What if I have a buried or hidden penis?', 2 );
$body[] = cil_recirc_p( 'A buried penis can sometimes make a circumcision appear as though too much foreskin has been left behind.' );
$body[] = cil_recirc_p( 'When the penis retracts into the surrounding pubic tissue, penile skin can move forward and partly cover the glans even though an appropriate amount of foreskin was removed during the original circumcision.' );
$body[] = cil_recirc_p( 'This is an important distinction.' );
$body[] = cil_recirc_p( 'Simply removing more skin from somebody with a significantly buried penis may not correct the underlying problem and, in some circumstances, could make the situation worse.' );
$body[] = cil_recirc_p( 'We therefore assess the penis and surrounding pubic tissue before recommending further skin removal.' );
$body[] = cil_recirc_link_p( 'Learn more about buried penis →', $buried );

$body[] = cil_recirc_h( 'When should I consider revision after a recent circumcision?', 2 );
$body[] = cil_recirc_p( 'A newly circumcised penis can look very different from the final healed result.' );
$body[] = cil_recirc_p( 'During the first few weeks there may be swelling, bruising, stitches, skin glue, firmness around the scar and temporary unevenness.' );
$body[] = cil_recirc_p( 'These can make a perfectly normal healing circumcision appear uneven or unsatisfactory.' );
$body[] = cil_recirc_p( 'The scar also continues changing after the surface wound has healed.' );
$body[] = cil_recirc_p( 'For this reason, unless there is a medical problem requiring earlier treatment, it is often sensible to allow the original circumcision sufficient time to heal and settle before making decisions about cosmetic revision.' );
$body[] = cil_recirc_p( 'If you are concerned following a recent circumcision, however, you do not need to diagnose the problem yourself. We can assess the area and advise whether it simply needs more healing time or whether further treatment should eventually be considered.' );

$body[] = cil_recirc_h( 'Can you revise a circumcision performed at another clinic or abroad?', 2 );
$body[] = cil_recirc_p( 'Yes.' );
$body[] = cil_recirc_p( 'We regularly assess patients whose original circumcision was performed elsewhere in the UK or overseas.' );
$body[] = cil_recirc_p( 'It does not matter where the original procedure was performed.' );
$body[] = cil_recirc_p( 'What matters to us is the current anatomy, the remaining skin, existing scar tissue, your symptoms and what you would like to improve.' );
$body[] = cil_recirc_p( 'If you have information about the original procedure, you are welcome to bring it, but it is not always essential.' );

$body[] = cil_recirc_h( 'Can you revise a childhood circumcision when I am now an adult?', 2 );
$body[] = cil_recirc_p( 'Yes.' );
$body[] = cil_recirc_p( 'Some adults seek revision of a circumcision performed during infancy or childhood because they are unhappy with the appearance or have developed a problem that has become more noticeable with age.' );
$body[] = cil_recirc_p( 'The original circumcision may have been performed many years ago.' );
$body[] = cil_recirc_p( 'We assess the current anatomy rather than relying on how or when the original circumcision was performed.' );

$body[] = cil_recirc_h( 'Re-circumcision for children', 2 );
$body[] = cil_recirc_p( 'We also assess children who have previously been circumcised but appear to have excess remaining foreskin, significant asymmetry, troublesome scar tissue or another problem.' );
$body[] = cil_recirc_p( 'Children require particularly careful assessment because penile appearance changes as they grow, and conditions such as buried penis can make the penis appear incompletely circumcised when the underlying issue is actually retraction into the surrounding tissue.' );
$body[] = cil_recirc_p( 'Not every child who appears to have excess skin needs another circumcision.' );
$body[] = cil_recirc_p( 'We will examine your son and explain whether revision is appropriate or whether observation or another approach would be better.' );

$body[] = cil_recirc_h( 'What happens at a revision consultation?', 2 );
$body[] = cil_recirc_p( 'A consultation is particularly important for re-circumcision because we cannot accurately plan revision from a description alone.' );
$body[] = cil_recirc_h( '1. Tell us what concerns you', 3 );
$body[] = cil_recirc_p( 'We first want to understand what you are unhappy with.' );
$body[] = cil_recirc_p( 'For example:' );
$body[] = cil_recirc_list(
	array(
		esc_html( 'Is too much skin remaining?' ),
		esc_html( 'Is the circumcision uneven?' ),
		esc_html( 'Is the scar prominent?' ),
		esc_html( 'Is there pain or tightness?' ),
		esc_html( 'Is there a skin bridge?' ),
		esc_html( 'Is the frenulum causing problems?' ),
		esc_html( 'Are you mainly concerned about appearance?' ),
		esc_html( 'Is there a medical problem?' ),
	)
);
$body[] = cil_recirc_p( 'Do not feel embarrassed about explaining exactly what concerns you. Revision consultations are specifically intended for these discussions.' );
$body[] = cil_recirc_h( '2. Examination', 3 );
$body[] = cil_recirc_p( 'We examine the penis and existing circumcision.' );
$body[] = cil_recirc_p( 'We assess:' );
$body[] = cil_recirc_list(
	array(
		esc_html( 'The amount of remaining skin' ),
		esc_html( 'Distribution and mobility of the penile skin' ),
		esc_html( 'Existing circumcision scar' ),
		esc_html( 'Symmetry' ),
		esc_html( 'Frenulum' ),
		esc_html( 'Any adhesions or skin bridges' ),
		esc_html( 'Evidence of phimosis, BXO or other scarring' ),
		esc_html( 'Whether the penis is buried or retractile' ),
		esc_html( 'Whether enough skin remains to perform the correction safely' ),
	)
);
$body[] = cil_recirc_h( '3. Discussing what is achievable', 3 );
$body[] = cil_recirc_p( 'After examination, we explain what we believe can be improved.' );
$body[] = cil_recirc_p( 'Just as importantly, we will tell you if something cannot safely or predictably be corrected.' );
$body[] = cil_recirc_p( 'Revision surgery should have a realistic objective rather than simply removing more skin in the hope that the appearance will improve.' );
$body[] = cil_recirc_h( '4. Treatment plan and price', 3 );
$body[] = cil_recirc_p( 'If revision is appropriate, we explain the proposed procedure, anaesthetic, expected recovery, potential risks and price.' );
$body[] = cil_recirc_p( 'Because revision varies considerably between patients, the price is quoted after assessment rather than using a single fixed fee for everybody.' );

$body[] = cil_recirc_h( 'Can I send photographs before booking?', 2 );
$body[] = cil_recirc_p( 'If the clinic\'s current secure communication policy allows photographs, images can sometimes help us understand the nature of an enquiry before you travel.' );
$body[] = cil_recirc_p( 'However, photographs do not replace a physical examination and we may not be able to tell from an image alone whether revision is appropriate.' );
$body[] = cil_recirc_p( 'Only send intimate medical photographs using a communication method that the clinic has specifically confirmed is appropriate for this purpose.' );
$body[] = cil_recirc_p( 'For children, follow the clinic\'s instructions carefully regarding photographs and do not send intimate images unless you have specifically been asked to do so through an approved clinical process.' );

$body[] = cil_recirc_h( 'How is re-circumcision performed?', 2 );
$body[] = cil_recirc_p( 'There is no single re-circumcision technique because the procedure depends on what needs correcting.' );
$body[] = cil_recirc_p( 'Revision may involve removing additional skin, excising an existing scar, releasing an adhesion or skin bridge, treating the frenulum or reshaping and re-closing the circumcision line.' );
$body[] = cil_recirc_p( 'For suitable adults and older patients, revision can often be performed using local anaesthetic.' );
$body[] = cil_recirc_p( 'The wound may be closed using dissolvable stitches, medical skin glue or a combination of both, depending on the procedure.' );
$body[] = cil_recirc_p( 'We explain the specific plan before you decide whether to proceed.' );

$body[] = cil_recirc_h( 'Is revision circumcision more complicated than a first circumcision?', 2 );
$body[] = cil_recirc_p( 'It can be.' );
$body[] = cil_recirc_p( 'During a first circumcision, the practitioner is working with the original foreskin and anatomy.' );
$body[] = cil_recirc_p( 'During revision, some skin has already been removed and scar tissue has already formed. This means the amount and position of the remaining skin need to be assessed carefully.' );
$body[] = cil_recirc_p( 'The goal is not simply to perform another standard circumcision. It is to correct a specific problem while preserving enough skin for comfortable movement and erections.' );

$body[] = cil_recirc_h( 'Will re-circumcision improve the appearance?', 2 );
$body[] = cil_recirc_p( 'The purpose of cosmetic revision is to improve a feature of the existing circumcision that concerns the patient.' );
$body[] = cil_recirc_p( 'In many cases, a meaningful improvement may be possible.' );
$body[] = cil_recirc_p( 'However, no practitioner can guarantee a perfectly symmetrical penis, an invisible scar or a particular cosmetic result.' );
$body[] = cil_recirc_p( 'Human anatomy is naturally variable and every surgical procedure produces scar tissue.' );
$body[] = cil_recirc_p( 'During your consultation we will explain what we believe can realistically be improved and any limitations that apply in your particular case.' );

$body[] = cil_recirc_h( 'Will the old scar disappear?', 2 );
$body[] = cil_recirc_p( 'If the existing scar is removed during revision, that tissue is replaced by a new surgical scar.' );
$body[] = cil_recirc_p( 'The new scar will initially be visible and usually changes in colour, firmness and appearance as it matures.' );
$body[] = cil_recirc_p( 'The aim may be to create a more even or better-positioned circumcision line, but it would be misleading to promise that revision will leave no visible scar.' );

$body[] = cil_recirc_h( 'Will I lose more sensitivity?', 2 );
$body[] = cil_recirc_p( 'Any procedure that removes or rearranges penile skin can alter how the penis feels.' );
$body[] = cil_recirc_p( 'Revision circumcision can also involve scar tissue and areas that have already undergone surgery.' );
$body[] = cil_recirc_p( 'Individual experiences vary, so we do not promise that sensation will remain exactly the same or predict a particular change in sexual sensitivity.' );
$body[] = cil_recirc_p( 'If sensitivity is one of your main concerns, discuss this during your consultation before deciding whether to proceed.' );

$body[] = cil_recirc_h( 'What is recovery like after circumcision revision?', 2 );
$body[] = cil_recirc_p( 'Recovery depends on the extent of the revision.' );
$body[] = cil_recirc_p( 'A small scar correction may have a different recovery from a more extensive re-circumcision.' );
$body[] = cil_recirc_p( 'After surgery you may experience swelling, bruising, tenderness and increased sensitivity during early healing.' );
$body[] = cil_recirc_p( 'If dissolvable stitches are used, they normally loosen and disappear gradually.' );
$body[] = cil_recirc_p( 'We provide detailed aftercare instructions appropriate to the procedure performed.' );

$body[] = cil_recirc_h( 'When can I return to work?', 2 );
$body[] = cil_recirc_p( 'This depends on the extent of your revision and the type of work you do.' );
$body[] = cil_recirc_p( 'Someone with a desk-based job may be able to return sooner than someone whose work involves heavy lifting or significant physical activity.' );
$body[] = cil_recirc_p( 'We will advise you according to the procedure performed.' );

$body[] = cil_recirc_h( 'When can I exercise?', 2 );
$body[] = cil_recirc_p( 'Heavy lifting, running, cycling, contact sports and strenuous gym activity can place tension and friction on a healing wound.' );
$body[] = cil_recirc_p( 'Return to exercise gradually and follow the individual advice we give you.' );

$body[] = cil_recirc_h( 'When can I have sex or masturbate after revision?', 2 );
$body[] = cil_recirc_p( 'Sex and masturbation should be avoided until the wound has healed sufficiently.' );
$body[] = cil_recirc_p( 'As with a first adult circumcision, this will usually mean at least four weeks and until the wound is fully healed, but more extensive revision may require longer.' );
$body[] = cil_recirc_p( 'Returning too early can cause pain, bleeding or disruption of the healing wound.' );
$body[] = cil_recirc_p( 'We will advise you according to your individual recovery.' );

$body[] = cil_recirc_h( 'What are the risks of circumcision revision?', 2 );
$body[] = cil_recirc_p( 'Re-circumcision is surgery and complications are possible.' );
$body[] = cil_recirc_p( 'Potential problems include:' );
$body[] = cil_recirc_list(
	array(
		esc_html( 'Bleeding' ),
		esc_html( 'Infection' ),
		esc_html( 'Swelling' ),
		esc_html( 'Wound separation' ),
		esc_html( 'Delayed healing' ),
		esc_html( 'Persistent or new scar tissue' ),
		esc_html( 'Asymmetry' ),
		esc_html( 'Altered sensation' ),
		esc_html( 'Removal of too much or too little skin' ),
		esc_html( 'Persistence of the original concern' ),
		esc_html( 'Dissatisfaction with the cosmetic result' ),
		esc_html( 'Need for further treatment' ),
	)
);
$body[] = cil_recirc_p( 'Previous surgery can make revision more technically challenging because there may be less available skin and more scar tissue.' );
$body[] = cil_recirc_p( 'We discuss the risks relevant to your proposed revision before you decide whether to proceed.' );

$body[] = cil_recirc_h( 'When should I contact the clinic after revision?', 2 );
$body[] = cil_recirc_p( 'Contact us if:' );
$body[] = cil_recirc_list(
	array(
		esc_html( 'You have bleeding that concerns you' ),
		esc_html( 'Pain is severe or worsening' ),
		esc_html( 'Redness or swelling is progressively increasing' ),
		esc_html( 'The wound becomes hot or produces concerning discharge' ),
		esc_html( 'The wound appears to be opening' ),
		esc_html( 'You develop a fever or feel unwell' ),
		esc_html( 'You have difficulty passing urine' ),
		esc_html( 'You are concerned about your stitches' ),
		esc_html( 'You are worried about the appearance or healing of the revision' ),
	)
);
$body[] = cil_recirc_p( 'If you have significant bleeding that will not stop, cannot pass urine, become seriously unwell or believe you have a medical emergency, seek urgent medical attention rather than waiting for a routine clinic response.' );

$body[] = cil_recirc_h( 'What follow-up do we provide?', 2 );
$body[] = cil_recirc_p( 'Revision patients receive detailed aftercare instructions appropriate to the procedure performed.' );
$body[] = cil_recirc_p( 'We provide follow-up support during healing and can arrange an examination where clinically appropriate if there is a concern.' );
$body[] = cil_recirc_p( 'It is important to remember that the final result cannot be judged immediately after surgery. Swelling must settle and the new scar needs time to mature before the longer-term appearance can be assessed.' );

$body[] = cil_recirc_h( 'How much does re-circumcision cost?', 2 );
$body[] = cil_recirc_p( 'There is no single fixed price for circumcision revision because the amount of corrective work varies considerably between patients.' );
$body[] = cil_recirc_p( 'A patient who needs a small scar correction is different from somebody requiring removal of substantial remaining foreskin and reconstruction of the circumcision line.' );
$body[] = cil_recirc_p( 'We therefore normally assess the patient first and provide a quotation based on the treatment required.' );
$body[] = cil_recirc_link_p( 'Book a revision consultation →', $book );

$body[] = cil_recirc_h( 'Re-circumcision and circumcision revision in London', 2 );
$body[] = cil_recirc_p( 'Our clinic is located at:' );
$body[] = cil_recirc_p( 'Beverley Clinic, 78 Beverley Drive, Edgware, North-West London, HA8 5NE' );
$body[] = cil_recirc_p( 'We assess patients from across London, elsewhere in the UK and overseas who are seeking correction or improvement of a previous circumcision.' );
$body[] = cil_recirc_p( 'If you are travelling a significant distance, contact us before booking so that we can explain the assessment process.' );

$faq_items = array(
	array(
		'q' => 'Can you circumcise someone twice?',
		'a' => '<p>Yes. Where clinically appropriate, further surgery can be performed after a previous circumcision. This is usually described as re-circumcision or circumcision revision.</p>',
	),
	array(
		'q' => 'Can you remove more foreskin after circumcision?',
		'a' => '<p>Sometimes. Whether additional skin can safely be removed depends on how much skin remains, its mobility, the position of the existing scar and your anatomy. We need to examine you before deciding.</p>',
	),
	array(
		'q' => 'My circumcision looks uneven. Can it be corrected?',
		'a' => '<p>Potentially. If enough skin is available, it may be possible to revise an uneven circumcision line or remove additional skin from a particular area. We cannot determine what is achievable without examination.</p>',
	),
	array(
		'q' => 'My glans is still partly covered. Does that mean the circumcision was incomplete?',
		'a' => '<p>Not necessarily. Remaining skin can cover part of the glans for several reasons, including the amount of skin originally removed and a buried or retractile penis. We assess the cause before recommending further skin removal.</p>',
	),
	array(
		'q' => 'Can you correct a bad circumcision?',
		'a' => '<p>We prefer to talk about what specifically concerns you rather than simply labelling a previous circumcision "bad". Excess skin, asymmetry, scar problems, skin bridges and some other issues may be suitable for revision. What can be improved depends on examination.</p>',
	),
	array(
		'q' => 'Can you make my circumcision tighter?',
		'a' => '<p>Sometimes additional skin can be removed, but tighter is not automatically better. Removing too much skin can cause discomfort and excessive tension during erections. We aim for a safe and appropriate result rather than simply making the circumcision as tight as possible.</p>',
	),
	array(
		'q' => 'Can a circumcision scar be removed?',
		'a' => '<p>Scar tissue can sometimes be excised during revision, but the procedure creates a new scar. The aim is to improve the existing problem rather than promise a scar-free result.</p>',
	),
	array(
		'q' => 'Can you treat a skin bridge after circumcision?',
		'a' => '<p>Yes, skin bridges can be assessed and may be suitable for surgical division. Whether this is done alone or as part of a wider revision depends on the individual case.</p>',
	),
	array(
		'q' => 'Can you correct a tight frenulum without re-circumcising me?',
		'a' => '<p>Possibly. If the circumcision itself is satisfactory and the problem comes from the frenulum, treatment of the frenulum alone may be more appropriate.</p>',
	),
	array(
		'q' => 'Can you revise a circumcision done abroad?',
		'a' => '<p>Yes. We assess the current anatomy regardless of where the original circumcision was performed.</p>',
	),
	array(
		'q' => 'How soon after circumcision can I have revision surgery?',
		'a' => '<p>This depends on the problem. Unless there is a complication requiring earlier treatment, it is often appropriate to allow swelling to settle and the original scar to mature before deciding on cosmetic revision. We can examine you and advise when revision should be considered.</p>',
	),
	array(
		'q' => 'Is re-circumcision performed under local anaesthetic?',
		'a' => '<p>Many suitable revision procedures can be performed under local anaesthetic. The appropriate anaesthetic depends on the patient\'s age and the complexity of the proposed revision.</p>',
	),
	array(
		'q' => 'Does revision guarantee a better cosmetic result?',
		'a' => '<p>No surgical procedure can guarantee a particular cosmetic result. We assess what concerns you and explain what we believe can realistically be improved before you decide whether to proceed.</p>',
	),
	array(
		'q' => 'Will I need stitches?',
		'a' => '<p>Many revision procedures require dissolvable stitches. Medical skin glue may also be used depending on the wound.</p>',
	),
	array(
		'q' => 'How long does revision take to heal?',
		'a' => '<p>This depends on how extensive the procedure is. Adult circumcision wounds commonly take several weeks to heal, and the scar continues changing after the surface wound has healed.</p>',
	),
	array(
		'q' => 'When can I have sex after re-circumcision?',
		'a' => '<p>As a general guide, avoid sex and masturbation for at least four weeks and until the wound is fully healed. More extensive revision may require longer.</p>',
	),
	array(
		'q' => 'Can you tell me whether I need revision from a photograph?',
		'a' => '<p>A photograph may sometimes help us understand your concern, but it cannot reliably replace examination. The amount and mobility of the remaining skin and the effect of an erection cannot necessarily be assessed from a photograph.</p>',
	),
	array(
		'q' => 'Do I need a consultation first?',
		'a' => '<p>Usually, yes. Re-circumcision is individualised surgery, so an examination is normally needed before we can explain what can be corrected, how we would do it and what it will cost.</p>',
	),
);

$intro_paras = array(
	cil_recirc_p( 'If you have already been circumcised but are unhappy with the appearance, have too much foreskin remaining, troublesome scarring or another problem following your original circumcision, re-circumcision or circumcision revision may be possible.' ),
	cil_recirc_p( 'At Beverley Clinic in North-West London, we assess both adults and children who have previously been circumcised and would like the result corrected or improved.' ),
	cil_recirc_p( 'Revision circumcision is different from a first circumcision. The practitioner has to work with the remaining penile skin, the position of the existing circumcision scar and any scar tissue created by the previous procedure.' ),
	cil_recirc_p( 'For this reason, we normally need to examine you before deciding what can safely and realistically be improved.' ),
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
		'eyebrow'    => 'Revision work · Quoted after examination',
		'title'      => 'Re-circumcision and circumcision revision in London',
		'lede'       => 'If you have already been circumcised but are unhappy with the appearance, have too much foreskin remaining, troublesome scarring or another problem following your original circumcision, re-circumcision or circumcision revision may be possible.',
		'reviewedBy' => cil_reviewed_by_haidar(),
		'crumbs'     => array(
			array(
				'label' => 'Who we see',
				'href'  => home_url( '/babies/' ),
			),
			array(
				'label' => 'Re-circumcision',
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
							array( 'k' => 'Who', 'v' => 'Children and adults' ),
							array( 'k' => 'Consultation', 'v' => 'Usually needed first' ),
							array( 'k' => 'Price', 'v' => 'Quoted after examination' ),
							array( 'k' => 'Technique', 'v' => 'Depends on the individual case' ),
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
		'anchor' => 're-circumcision-guide',
	),
	array( cil_recirc_body_group( $body ) )
);

$blocks[] = cil_dyn_block(
	'cil/faq',
	array(
		'heading' => 'Frequently asked questions about re-circumcision',
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
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text' ),
					'innerBlocks'  => array(
						cil_recirc_h( 'Contact the clinic for an assessment', 2 ),
						cil_recirc_p( 'A consultation is usually needed so the practitioner can examine the area, understand what you would like corrected and explain what is realistically achievable.' ),
						cil_recirc_link_p( 'Book a revision consultation →', $book ),
					),
					'innerHTML'    => '',
					'innerContent' => array(
						'<div class="wp-block-group body-text">',
						null,
						null,
						null,
						'</div>',
					),
				),
				cil_dyn_block(
					'cil/callback-card',
					array(
						'eyebrow' => 'Request a call back',
						'title'   => 'Tell us what happened',
						'formId'  => 'recirc',
						'subject' => 're-circumcision enquiry',
						'urgent'  => false,
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

$content = cil_serialize_blocks( $blocks );

$page = get_page_by_path( 're-circumcision', OBJECT, 'page' );
if ( ! $page ) {
	fwrite( STDERR, "Re-circumcision page not found\n" );
	exit( 1 );
}

kses_remove_filters();
remove_filter( 'content_save_pre', 'convert_invalid_entities' );
remove_filter( 'content_save_pre', 'balanceTags', 50 );
$result = wp_update_post(
	wp_slash(
		array(
			'ID'           => (int) $page->ID,
			'post_title'   => 'Re-circumcision and circumcision revision in London',
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
$fixture      = cil_content_export_slug( 're-circumcision', $replace_from, $replace_to );
if ( is_wp_error( $fixture ) ) {
	fwrite( STDERR, $fixture->get_error_message() . "\n" );
	exit( 1 );
}
$path = cil_content_write_fixture( 're-circumcision', $fixture );
if ( is_wp_error( $path ) ) {
	fwrite( STDERR, $path->get_error_message() . "\n" );
	exit( 1 );
}

$v      = get_post( $page->ID )->post_content;
$checks = array(
	'Re-circumcision and circumcision revision in London',
	'What is re-circumcision?',
	'Why do people consider circumcision revision?',
	'Too much foreskin left after circumcision',
	'Uneven circumcision',
	'Circumcision scar revision',
	'Skin bridges and adhesions after circumcision',
	'Can you correct a tight circumcision?',
	'Phimosis or tight foreskin after a previous circumcision',
	'What if the problem is the frenulum?',
	'What if I have a buried or hidden penis?',
	'When should I consider revision after a recent circumcision?',
	'Can you revise a circumcision performed at another clinic or abroad?',
	'Can you revise a childhood circumcision when I am now an adult?',
	'Re-circumcision for children',
	'What happens at a revision consultation?',
	'Can I send photographs before booking?',
	'How is re-circumcision performed?',
	'Is revision circumcision more complicated than a first circumcision?',
	'Will re-circumcision improve the appearance?',
	'Will the old scar disappear?',
	'Will I lose more sensitivity?',
	'What is recovery like after circumcision revision?',
	'When can I return to work?',
	'When can I exercise?',
	'When can I have sex or masturbate after revision?',
	'What are the risks of circumcision revision?',
	'When should I contact the clinic after revision?',
	'What follow-up do we provide?',
	'How much does re-circumcision cost?',
	'Beverley Clinic, 78 Beverley Drive, Edgware, North-West London, HA8 5NE',
	'Frequently asked questions about re-circumcision',
	'Can you circumcise someone twice?',
	'Do I need a consultation first?',
	'Learn more about phimosis',
	'Learn more about BXO / lichen sclerosus',
	'Learn more about tight frenulum and frenuloplasty',
	'Learn more about buried penis',
	'Book a revision consultation',
	'/conditions/phimosis/',
	'/conditions/bxo/',
	'/procedures/frenuloplasty/',
	'/buried-penis/',
	'/book/',
	'wp:cil/faq',
);
foreach ( $checks as $k ) {
	echo ( false !== strpos( $v, $k ) ? 'OK' : 'MISS' ) . "  $k\n";
}
echo 'bytes=' . strlen( $v ) . "\n";
echo "fixture={$path}\n";
echo 'title=' . get_post( $page->ID )->post_title . "\n";
echo "OK\n";
