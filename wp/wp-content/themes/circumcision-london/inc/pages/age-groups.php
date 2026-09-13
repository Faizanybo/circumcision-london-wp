<?php
/**
 * Babies, children and adults pages from prototype src/pages/agegroups.js.
 *
 * Content is serialized into Gutenberg blocks so the pages stay editable.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared local-anaesthetic copy. Identical on all three age pages in the prototype.
 *
 * @return string
 */
function cil_anaesthetic_html() {
	return cil_proto_html(
		'<div class="body-text">
        <p>The circumcision is carried out under local anaesthetic. The patient is awake, but the penis is numb and
        no pain is felt.</p>
        <p>It is given at the base of the penis with a very fine needle, and it usually works within a minute. Often
        one injection point is all that is needed, and most patients do not react to the needle going in at all. What
        does sting, for a few seconds, is the solution itself. That passes quickly and the area goes numb.</p>
        <p><strong>We do not use numbing creams such as EMLA.</strong> The manufacturer contra-indicates them around
        the genitals, and they would make no difference to the stinging sensation anyway, which is the only
        uncomfortable part of the whole thing.</p>
        <p><strong>We test before we start.</strong> Once the anaesthetic has worked we test the area and show you
        that no pain is felt. We only begin once you tell us you are happy. That is not a formality; it is how every
        procedure in this clinic starts.</p>
        <p>Local anaesthetic is safe at every age, and it is illegal to circumcise without it.</p>
      </div>'
	);
}

/**
 * Clinically reviewed-by line used on the age-group pages.
 *
 * @return string
 */
function cil_reviewed_by_haidar() {
	return '<a href="' . esc_url( cil_path_url( '/team#haidar' ) ) . '">Dr Haidar Al-Ali, BDS, MFDS RCPS (Glasg)</a>, September 2026';
}

/**
 * Prototype data for /babies, /children and /adults.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_age_pages() {
	$anaesthetic = cil_anaesthetic_html();
	$prices_note = ' See the <a href="' . esc_url( cil_path_url( '/prices' ) ) . '" style="color:var(--blue-deep)">full price list</a>, broken down by age band.';

	return array(
		'babies'   => array(
			'slug'        => 'babies',
			'name'        => 'Babies and toddlers',
			'title'       => 'Baby Circumcision London | From £200 | Edgware Clinic',
			'description' => 'Baby and toddler circumcision in Edgware, North-West London. The Plastibell or Circumplast ring method under local anaesthetic, about ten minutes. From £200.',
			'eyebrow'     => 'Best under one month old · From £200',
			'h1'          => 'Baby and toddler circumcision',
			'lede'        => 'The ring method under local anaesthetic. The circumcision itself takes about ten minutes, there are no stitches, and you put his nappy and trousers back on and take him home as normal.',
			'price_note'  => 'From £200 for babies up to two months.' . $prices_note,
			'form_id'     => 'babies',
			'subject'     => 'babies and toddlers enquiry',
			'schema_name' => 'Infant circumcision',
			'offer'       => '200',
			'intro'       => cil_proto_html(
				'<p>Circumcision is an anxious time for parents. We try to take the worry out of it by telling you exactly what
    will happen, showing you that your son feels no pain before we begin, and sending you home knowing precisely what
    to do for the next fortnight.</p>
    <p>When you book, we send you videos on how to prepare and what to expect from start to finish, so nothing on the
    day comes as a surprise. On the day we give the local anaesthetic, wait for it to work, test him, and show you
    that he feels nothing. We only start once you say you are happy.</p>
    <p>The circumcision takes about <strong>ten minutes</strong>, and the anaesthetic lasts around two hours.
    Afterwards you put his nappy and trousers back on and take him home normally, with printed aftercare instructions
    we have already talked you through.</p>'
			),
			'spec'        => array(
				array( 'k' => 'Best age', 'v' => 'Under one month old' ),
				array( 'k' => 'Method', 'v' => 'Plastibell or Circumplast ring' ),
				array( 'k' => 'Anaesthetic', 'v' => 'Local, tested before we start' ),
				array( 'k' => 'Circumcision takes', 'v' => 'About 10 minutes' ),
				array( 'k' => 'Anaesthetic lasts', 'v' => 'About 2 hours' ),
				array( 'k' => 'Stitches', 'v' => 'None' ),
				array( 'k' => 'Ring falls off', 'v' => '3 to 14 days' ),
				array( 'k' => 'Price', 'v' => 'From £200' ),
			),
			'figure'      => 'baby-parent',
			'sections'    => array(
				array(
					'eyebrow' => 'The method',
					'heading' => 'The Plastibell or Circumplast ring',
					'banded'  => true,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>This is the method we use for young infants and toddlers, and for that age group it is the best one.</p>
        <p>After the local anaesthetic, the foreskin is separated from the head of the penis. A plastic ring is fitted
        around the head and the foreskin is drawn over it, then a thread is tied over the skin. The thread cuts off
        the blood supply to the foreskin, which dies and falls away with the ring, usually within
        <strong>three to fourteen days</strong>.</p>
        <p>It works in much the same way as the umbilical cord, which also dies and drops off on its own. There are no
        stitches to remove and nothing for you to do except keep the area clean and dry.</p>
        <p>If the ring has not come off after two weeks, book a follow-up and bring him in. Taking it off takes a few
        seconds. Do not pull at it yourself, however loose it looks.</p>
      </div>'
					),
				),
				array(
					'eyebrow' => 'The anaesthetic',
					'heading' => 'How we numb him, and how we check',
					'banded'  => false,
					'narrow'  => true,
					'html'    => $anaesthetic,
				),
				array(
					'eyebrow' => 'Before you come',
					'heading' => 'What to bring',
					'banded'  => true,
					'narrow'  => false,
					'html'    => cil_proto_html(
						'<div class="body-text" style="max-width:74ch">
        <p>Preparation is minimal, because it is done under local anaesthetic. There is no fasting. Please bring:</p>
        <ul>
          <li>A clean nappy and baby wipes</li>
          <li>A bottle of milk, or his dummy if he takes one</li>
          <li>His birth certificate</li>
          <li>His red book. If he has any medical problems, tell us <em>before</em> the appointment, because we
            sometimes need letters from his doctors first</li>
          <li>Photo ID for the patient and for both parents. If you are a single parent, call us beforehand and we
            will tell you what we can accept instead</li>
        </ul>
        <p style="margin-top:16px">The best age is <strong>under one month old</strong>: at that age it is quick and
        simple and healing is faster. If your son is older we still circumcise routinely at every age, right through
        to adults.</p>
      </div>'
					),
				),
				array(
					'eyebrow' => 'Being straight with you',
					'heading' => 'When we will postpone or decline',
					'banded'  => false,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>We examine before anything else happens, which is how these get picked up.</p>
        <p>We postpone if your son is unwell, significantly jaundiced, or was very premature and has not caught up.
        Those are delays rather than refusals, and we will tell you when to come back.</p>
        <p>We decline where there is <strong>hypospadias</strong> or another variation in which the foreskin may be
        needed for reconstruction later. If there is any family history of a bleeding disorder we want that
        investigated first.</p>
        <p>If your son has a <a href="/buried-penis">buried penis</a> we treat a great many of those without any
        problem, but in severe cases we advise waiting. You can send us a photograph before your appointment and save
        yourself the trip.</p>
      </div>'
					),
				),
			),
			'faqs'        => array(
				array(
					'q' => 'Is the circumcision painful?',
					'a' => '<p>No. We give local anaesthetic to every patient at every age. It is safe, and it is illegal to circumcise without it. We test him before we start and show you that he feels no pain.</p><p>Babies do sometimes cry during the procedure. The anaesthetic removes the sensation of pain but keeps normal touch sensation, which is painless, and an unfamiliar touch in that area is enough to make a baby object. It is not pain.</p>',
				),
				array(
					'q' => 'What is the best age?',
					'a' => '<p>Under one month old. At that age the circumcision is very quick and simple and healing is faster. We circumcise routinely at all ages if your son is older, though the ring method is generally suitable only up to around the toddler stage.</p>',
				),
				array(
					'q' => 'Why do you not use numbing cream first?',
					'a' => '<p>Because EMLA and creams like it are contra-indicated around the genitals by the manufacturer, and because they would not help with the only uncomfortable part anyway. The needle itself usually causes no reaction. The brief sting comes from the anaesthetic solution, and no cream affects that.</p>',
				),
				array(
					'q' => 'What if the ring does not fall off?',
					'a' => '<p>It normally separates between three and fourteen days. If it is still attached after two weeks, book a follow-up and come in. Removing it takes a few seconds. Do not pull at it at home.</p>',
				),
				array(
					'q' => 'How long does the appointment take?',
					'a' => '<p>The circumcision itself is about ten minutes. Allow about an hour for the visit, covering settling in, consent, the anaesthetic taking effect and going through the aftercare with you before you leave. The anaesthetic then lasts around two hours.</p>',
				),
				array(
					'q' => 'Can you tidy up a circumcision done elsewhere?',
					'a' => cil_proto_html( '<p>Yes, and we do it often. Parents bring sons to us from across the UK and from abroad to correct circumcisions they are unhappy with, from scar revisions to tightening a loose result. <a href="/re-circumcision">More about re-circumcision</a>. Contact us for a quote; you will usually need a consultation first so we can examine him.</p>' ),
				),
			),
		),
		'children' => array(
			'slug'        => 'children',
			'name'        => 'Children and teenagers',
			'title'       => 'Circumcision for Boys London | From £280 | Edgware',
			'description' => 'Circumcision for children and teenagers in Edgware, North-West London. The forceps guided method under local anaesthetic. School holidays book up fast. From £280.',
			'eyebrow'     => 'One to seventeen years · From £280',
			'h1'          => 'Circumcision for children and teenagers',
			'lede'        => 'The forceps guided method with thermal cautery, under local anaesthetic. There is a proper consultation first, we test him before we begin, and he walks out afterwards in his own clothes.',
			'price_note'  => 'From £280 for one to two year olds, rising by age band.' . $prices_note,
			'form_id'     => 'children',
			'subject'     => 'children and teenagers enquiry',
			'schema_name' => 'Child circumcision',
			'offer'       => '280',
			'intro'       => cil_proto_html(
				'<p>Circumcision is an anxious time for the boy as much as for his parents. When you arrive there is a consultation
    where we explain what we are going to do, step by step, and answer whatever either of you wants to ask.</p>
    <p>When you are comfortable we give the local anaesthetic, wait for it to work, and test him. We show you that he
    feels no pain, and we only begin once you tell us you are happy.</p>
    <p>Afterwards he puts his own underwear and trousers back on and goes home normally, with printed aftercare
    instructions covering everything you need to do.</p>
    <p><strong>School holiday appointments book up very quickly</strong>, so book well in advance if you are planning
    around one. We give school letters where they are needed.</p>'
			),
			'spec'        => array(
				array( 'k' => 'Age', 'v' => '1 to 17 years' ),
				array( 'k' => 'Method', 'v' => 'Forceps guided, thermal cautery' ),
				array( 'k' => 'Anaesthetic', 'v' => 'Local, tested before we start' ),
				array( 'k' => 'Closure', 'v' => 'Stitches, glue, or sometimes neither' ),
				array( 'k' => 'Consultation', 'v' => 'On the day, before anything else' ),
				array( 'k' => 'Goes home', 'v' => 'Straight afterwards, dressed normally' ),
				array( 'k' => 'Letters', 'v' => 'School letters given if needed' ),
				array( 'k' => 'Price', 'v' => 'From £280' ),
			),
			'figure'      => 'waiting-room',
			'sections'    => array(
				array(
					'eyebrow' => 'The method',
					'heading' => 'Forceps guided, with thermal cautery',
					'banded'  => true,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>This is the method we use for older children and for adults.</p>
        <p>After the local anaesthetic, the foreskin is drawn forward past the head of the penis. A special forceps is
        applied just in front of the head, and the foreskin is removed using thermal cautery, guided by the smooth
        surface of the forceps. That gives a clean, tidy line.</p>
        <p>Because thermal cautery is used there is minimal bleeding, and your son may not need any sutures at all.
        Where the wound does need closing we use stitches, skin glue, or a combination of the two.</p>
      </div>'
					),
				),
				array(
					'eyebrow' => 'Choosing the method',
					'heading' => 'Why not the ring at this age',
					'banded'  => false,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>Parents often ask for the ring method, usually because they have heard it avoids stitches. For an older boy
        it is generally the wrong choice.</p>
        <p>Plastibell and Circumplast rings come in a range of sizes, but even the largest may not fit an older boy.
        The ring method is designed for babies and toddlers. Used on an older child he may well complain before the
        ring comes away, because older boys are more physically active and we expect more swelling.</p>
        <p>There is no fixed age at which we switch from one method to the other. Every patient is different, and we
        decide after examining him.</p>
      </div>'
					),
				),
				array(
					'eyebrow' => 'The anaesthetic',
					'heading' => 'How we numb him, and how we check',
					'banded'  => true,
					'narrow'  => true,
					'html'    => $anaesthetic,
				),
				array(
					'eyebrow' => 'Preparing him',
					'heading' => 'Tell him less than you think',
					'banded'  => false,
					'narrow'  => false,
					'html'    => cil_proto_html(
						'<div class="body-text" style="max-width:74ch">
        <p>This is the advice parents are most surprised by, and it comes from watching a great many boys through it.</p>
        <p><strong>Do not give a child too much detail in advance.</strong> It tends to frighten rather than prepare
        him. A boy who arrives already frightened may be uncooperative, or may cry even when he feels nothing at all,
        because a nervous child anticipates ordinary touch as painful touch.</p>
        <p>Most older boys are completely quiet throughout the circumcision. We talk to the patient himself when he
        arrives, pitched at his age, and that is usually enough.</p>
        <p>Bring photo ID for him and for both parents, and tell us in advance about any medical problems, because we
        sometimes need letters from his doctors first.</p>
      </div>'
					),
				),
			),
			'faqs'        => array(
				array(
					'q' => 'Is it painful?',
					'a' => '<p>No. Local anaesthetic is given to every patient, and it is illegal to circumcise without it. We test him before starting and show you that he feels nothing. Most older boys are completely quiet during the whole circumcision.</p>',
				),
				array(
					'q' => 'Which method will you use?',
					'a' => '<p>The forceps guided method for older children, closed with stitches, glue or sometimes neither. The ring method is designed for babies and toddlers, and even the largest ring may not fit an older boy. There is no fixed cut-off age; we decide after examining him.</p>',
				),
				array(
					'q' => 'How much should I tell him beforehand?',
					'a' => '<p>Less than you would think. Too much detail tends to frighten a child, and a frightened child may cry even when he feels nothing, because he anticipates ordinary touch as painful. We explain it to him ourselves when he arrives, pitched at his age.</p>',
				),
				array(
					'q' => 'When should I book?',
					'a' => '<p>Early, if you want a school holiday. Those appointments book up very quickly. We give school letters where they are needed.</p>',
				),
				array(
					'q' => 'Does he need stitches?',
					'a' => '<p>Sometimes. Thermal cautery means minimal bleeding and he may need none at all. Where the wound needs closing we use stitches, skin glue, or both together.</p>',
				),
				array(
					'q' => 'What if he has a medical or foreskin problem?',
					'a' => cil_proto_html( '<p>Tell us when you call. Circumcision for a child with a medical or foreskin problem is quoted after examination rather than from the standard age-band list, because what is involved varies a great deal. See <a href="/conditions/phimosis">phimosis</a> if that is the concern.</p>' ),
				),
			),
		),
		'adults'   => array(
			'slug'        => 'adults',
			'name'        => 'Adult men',
			'title'       => 'Adult Circumcision London | From £680 | Edgware Clinic',
			'description' => 'Adult circumcision in Edgware, North-West London. Forceps guided under local anaesthetic, awake and comfortable throughout. IV sedation available. From £680.',
			'eyebrow'     => 'Eighteen and over · From £680',
			'h1'          => 'Adult circumcision in London',
			'lede'        => 'Everything from a first circumcision to correcting one done elsewhere. Under local anaesthetic, so you are comfortable and pain free, and you can talk to the doctor, watch television or listen to music while it is done.',
			'price_note'  => 'From £680, or £880 with removal of the frenulum, or £1,080 with a medical or foreskin problem.' . $prices_note,
			'form_id'     => 'adults',
			'subject'     => 'adult men enquiry',
			'schema_name' => 'Adult circumcision',
			'offer'       => '680',
			'intro'       => cil_proto_html(
				'<p>We see adults for every reason there is: a first circumcision for personal, religious or cultural reasons, a
    medical problem such as <a href="/conditions/phimosis">phimosis</a>, and men who want a previous circumcision put
    right.</p>
    <p>It is done under local anaesthetic, so you are comfortable and pain free throughout. <strong>We do not start
    until we have tested the penis and confirmed it is completely numb.</strong> You stay awake, and you can talk to
    the doctor, watch television or listen to music while it is done.</p>
    <p>The aftercare is comprehensive but simple. We explain it, we give it to you in writing, and afterwards you can
    reach us by phone or email or come back for free follow-up appointments.</p>
    <p>If you are anxious about it, IV sedation is available for an extra £800. It has to be pre-booked and paid for
    in advance, and you will need somebody to take you home.</p>'
			),
			'spec'        => array(
				array( 'k' => 'Age', 'v' => '18 and over' ),
				array( 'k' => 'Method', 'v' => 'Forceps guided, thermal cautery' ),
				array( 'k' => 'Anaesthetic', 'v' => 'Local, tested before we start' ),
				array( 'k' => 'During', 'v' => 'Awake; talk, television or music' ),
				array( 'k' => 'Closure', 'v' => 'Stitches and glue' ),
				array( 'k' => 'Follow-up', 'v' => 'Free, until healing is complete' ),
				array( 'k' => 'IV sedation', 'v' => 'Optional, extra £800, pre-booked' ),
				array( 'k' => 'Price', 'v' => '£680, £880 or £1,080' ),
			),
			'figure'      => 'certificates',
			'sections'    => array(
				array(
					'eyebrow' => 'The method',
					'heading' => 'Forceps guided, and why we prefer it',
					'banded'  => true,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>After the local anaesthetic, the foreskin is drawn forward past the head of the penis. A special forceps is
        applied just in front of the head and the foreskin is removed using thermal cautery, guided by the smooth
        surface of the forceps. That gives a clean and tidy cut, and cautery means minimal bleeding. The wound is
        closed with stitches, skin glue, or both.</p>
        <p><strong>We do not use the Shang Ring.</strong> The forceps guided method gives far more flexibility and
        greater control over how much foreskin is removed. With a ring you have to wait for the foreskin to die, which
        can produce a smell even in babies, and in an adult that becomes socially awkward.</p>
      </div>'
					),
				),
				array(
					'eyebrow' => 'The anaesthetic',
					'heading' => 'How we numb you, and how we check',
					'banded'  => false,
					'narrow'  => true,
					'html'    => $anaesthetic,
				),
				array(
					'eyebrow' => 'Medical reasons',
					'heading' => 'Phimosis, and getting it treated properly',
					'banded'  => true,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>A great many of the adults we see have a medical need, and the most common by far is
        <a href="/conditions/phimosis">phimosis</a>: difficulty retracting the foreskin.</p>
        <p>It is better treated early. Left alone it gets worse, because you cannot keep the penis properly clean,
        which leads to infections, which scar, which makes the tightness worse again.</p>
        <p>The NHS does provide circumcision where there is a medical need. From what our patients tell us, the
        waiting list runs beyond a year. Steroid creams are sometimes prescribed in mild cases, but they do not
        address the root of it, which is the tight inner skin, the lighter-coloured skin behind the tip.</p>
        <p>This is the part that matters when choosing who does it. <strong>The surgeon has to recognise the phimosis
        and treat that tightness specifically</strong>, not simply perform a standard circumcision. Get that wrong and
        you are left with a tight ring behind the head of the penis, which is a worse problem than the one you started
        with.</p>
        <p>Adult circumcision with a medical or foreskin problem is £1,080, with or without removal of the frenulum.</p>
      </div>'
					),
				),
				array(
					'eyebrow' => 'Being straight with you',
					'heading' => 'Sedation, and whether you need it',
					'banded'  => false,
					'narrow'  => true,
					'html'    => cil_proto_html(
						'<div class="body-text">
        <p>IV sedation is available for nervous patients at an extra £800. It must be pre-booked and paid for in
        advance, and you will need somebody to take you home afterwards.</p>
        <p>Two honest things about it. The great majority of adults do not have it, and afterwards describe the
        procedure as far less of an ordeal than they had built up. And if you have real anxiety, a needle phobia or a
        bad previous experience, it is a proper clinical option, and having it beats putting the whole thing off for
        another five years.</p>
        <p>We would rather explain that here than put £800 on an invoice as a surprise.</p>
      </div>'
					),
				),
			),
			'faqs'        => array(
				array(
					'q' => 'Will I feel anything?',
					'a' => '<p>No. It is done under local anaesthetic and we do not begin until we have tested the penis and confirmed it is completely numb. You are awake throughout and can talk to the doctor, watch television or listen to music.</p>',
				),
				array(
					'q' => 'Do you use the Shang Ring?',
					'a' => '<p>No. We prefer the forceps guided method for adults because it gives more flexibility and greater control over how much foreskin is removed. With the Shang Ring you have to wait for the foreskin to die, which can cause a smell even in babies and would be worse, and socially awkward, in an adult.</p>',
				),
				array(
					'q' => 'What does it cost?',
					'a' => '<p>£680 with no medical or foreskin problem. £880 if the frenulum is removed as well. £1,080 where there is a medical or foreskin problem, with or without removal of the frenulum. All include stitches and glue, aftercare, the next-day call and free follow-ups until healed. IV sedation is £800 on top and must be pre-booked and paid in advance.</p>',
				),
				array(
					'q' => 'Could the NHS do it instead?',
					'a' => '<p>Where there is a medical need, yes. From what our patients tell us the wait runs beyond a year. Steroid creams are sometimes offered for mild phimosis, but they do not deal with the underlying tight inner skin.</p>',
				),
				array(
					'q' => 'Can you correct a circumcision I had elsewhere?',
					'a' => cil_proto_html( '<p>Yes. We see a lot of men wanting a previous circumcision put right, from scar revision to tightening a loose result. <a href="/re-circumcision">More about re-circumcision</a>. Contact us for a quote; you will usually need a consultation first.</p>' ),
				),
				array(
					'q' => 'What is the aftercare like?',
					'a' => cil_proto_html( '<p>Comprehensive but simple. We explain it and give it to you in writing before you leave. Afterwards you can contact us by phone or email, and follow-up appointments are free until you are fully healed. See the <a href="/aftercare">aftercare page</a>.</p>' ),
				),
			),
		),
	);
}

/**
 * Self-closing dynamic block.
 *
 * @param string               $name  Block name.
 * @param array<string, mixed> $attrs Attributes.
 * @return array<string, mixed>
 */
function cil_dyn_block( $name, $attrs = array() ) {
	return array(
		'blockName'    => $name,
		'attrs'        => $attrs,
		'innerBlocks'  => array(),
		'innerHTML'    => '',
		'innerContent' => array(),
	);
}

/**
 * Custom HTML block.
 *
 * @param string $html Markup.
 * @return array<string, mixed>
 */
function cil_html_block( $html ) {
	return array(
		'blockName'    => 'core/html',
		'attrs'        => array(),
		'innerBlocks'  => array(),
		'innerHTML'    => $html,
		'innerContent' => array( $html ),
	);
}

/**
 * Static layout block with inner blocks (cil/section, cil/split).
 *
 * @param string                 $name  Block name.
 * @param array<string, mixed>   $attrs Attributes.
 * @param array<int, mixed>      $inner Inner blocks.
 * @param string                 $open  Opening HTML.
 * @param string                 $close Closing HTML.
 * @return array<string, mixed>
 */
function cil_layout_block( $name, $attrs, $inner, $open, $close ) {
	$inner_content = array( $open );
	foreach ( $inner as $_unused ) {
		$inner_content[] = null;
	}
	$inner_content[] = $close;

	return array(
		'blockName'    => $name,
		'attrs'        => $attrs,
		'innerBlocks'  => $inner,
		'innerHTML'    => '',
		'innerContent' => $inner_content,
	);
}

/**
 * cil/section matching editor.js save markup.
 *
 * @param array<string, mixed> $attrs Section attrs.
 * @param array<int, mixed>    $inner Inner blocks.
 * @return array<string, mixed>
 */
function cil_section_block( $attrs, $inner ) {
	$size   = isset( $attrs['size'] ) ? $attrs['size'] : 'section-sm';
	$band   = isset( $attrs['band'] ) ? $attrs['band'] : '';
	$wrap   = isset( $attrs['wrap'] ) ? $attrs['wrap'] : 'wrap';
	$anchor = isset( $attrs['anchor'] ) ? $attrs['anchor'] : '';
	$cls    = trim( 'wp-block-cil-section ' . $size . ( $band ? ' ' . $band : '' ) );
	$id     = $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '';

	$saved = array(
		'size' => $size,
		'band' => $band,
		'wrap' => $wrap,
	);
	if ( $anchor ) {
		$saved['anchor'] = $anchor;
	}

	return cil_layout_block(
		'cil/section',
		$saved,
		$inner,
		'<section class="' . esc_attr( $cls ) . '"' . $id . '><div class="' . esc_attr( $wrap ) . '">',
		'</div></section>'
	);
}

/**
 * cil/split matching editor.js save markup.
 *
 * @param array<int, mixed> $inner   Inner blocks.
 * @param bool              $reverse Reverse on small screens.
 * @return array<string, mixed>
 */
function cil_split_block( $inner, $reverse = false ) {
	$cls = 'wp-block-cil-split split' . ( $reverse ? ' reverse' : '' );

	return cil_layout_block(
		'cil/split',
		array(
			'reverse' => $reverse,
		),
		$inner,
		'<div class="' . esc_attr( $cls ) . '">',
		'</div>'
	);
}

/**
 * Gutenberg block markup for one age-group page.
 *
 * @param string $slug babies|children|adults.
 * @return string
 */
function cil_age_page_blocks( $slug ) {
	$pages = cil_age_pages();
	if ( empty( $pages[ $slug ] ) ) {
		return '';
	}

	$page = $pages[ $slug ];
	$blocks = array();

	$blocks[] = cil_dyn_block(
		'cil/page-head',
		array(
			'eyebrow'    => $page['eyebrow'],
			'title'      => $page['h1'],
			'lede'       => $page['lede'],
			'reviewedBy' => cil_reviewed_by_haidar(),
			'crumbs'     => array(
				array(
					'label' => 'Who we see',
					'href'  => cil_path_url( '/babies' ),
				),
				array(
					'label' => $page['name'],
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
					cil_html_block( '<div class="body-text" data-reveal>' . $page['intro'] . '</div>' ),
					cil_dyn_block(
						'cil/spec-panel',
						array(
							'eyebrow' => 'At a glance',
							'rows'    => $page['spec'],
							'note'    => $page['price_note'],
						)
					),
				)
			),
		)
	);

	foreach ( $page['sections'] as $section ) {
		$blocks[] = cil_dyn_block(
			'cil/text-section',
			array(
				'eyebrow' => $section['eyebrow'],
				'heading' => $section['heading'],
				'html'    => $section['html'],
				'narrow'  => ! empty( $section['narrow'] ),
				'banded'  => ! empty( $section['banded'] ),
			)
		);
	}

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
							'name' => $page['figure'],
						)
					),
					cil_dyn_block(
						'cil/callback-card',
						array(
							'eyebrow' => 'Request a call back',
							'title'   => 'Ask us first',
							'formId'  => $page['form_id'],
							'subject' => $page['subject'],
							'urgent'  => true,
						)
					),
				)
			),
		)
	);

	$blocks[] = cil_dyn_block( 'cil/cta-band', array() );

	$blocks[] = cil_dyn_block(
		'cil/faq',
		array(
			'heading' => $page['name'] . ': questions',
			'items'   => $page['faqs'],
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
					'exclude' => cil_path_url( '/' . $slug ),
					'banded'  => false,
				)
			),
		)
	);

	return cil_serialize_blocks( $blocks );
}

/**
 * Serialize an array of block arrays into post_content.
 *
 * @param array<int, array<string, mixed>> $blocks Blocks.
 * @return string
 */
function cil_serialize_blocks( $blocks ) {
	$out = '';
	foreach ( $blocks as $block ) {
		$out .= serialize_block( $block ) . "\n\n";
	}
	return trim( $out );
}
