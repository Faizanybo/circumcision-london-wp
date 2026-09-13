<?php
/**
 * Condition pages from prototype src/pages/conditions.js.
 *
 * Nested under /conditions/{slug}. Layout is shared; copy is per condition.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prototype data for the four condition pages.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_condition_pages() {
	$reasons = cil_path_url( '/conditions/phimosis' );

	return array(
		'phimosis'      => cil_condition_page_data(
			array(
				'slug'        => 'phimosis',
				'name'        => 'Phimosis',
				'title'       => 'Phimosis (Tight Foreskin) | Treatment in London | Beverley Clinic',
				'description' => 'What phimosis is, when a tight foreskin needs treating and when it does not, and the options besides circumcision. CQC-registered clinic in Edgware, London.',
				'eyebrow'     => 'Condition · Tight foreskin',
				'h1'          => 'Phimosis: a foreskin that will not retract',
				'lede'        => 'Phimosis is the medical name for a foreskin too tight to pull back over the head of the penis. In boys it is usually normal and resolves on its own. In adults it can be a real problem, and it is not always circumcision that fixes it.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>Almost every boy is born with a foreskin that does not retract. The inner surface of the foreskin starts life
      fused to the glans, and that attachment separates gradually over years, often not completely until well into the
      teens. This is <strong>physiological phimosis</strong>: normal development, not a condition, and not something to
      operate on. Forcing it back causes small tears that heal as scar, which is one of the few reliable ways to turn a
      normal foreskin into a tight one.</p>
      <p><strong>Pathological phimosis</strong> is different. Here the foreskin was retractile and has become tight,
      usually because of scarring: from repeated infection, from forced retraction, or from
      <a href="/conditions/bxo">BXO</a>. The tip may look pale, thickened or ringed with a firm white band. This kind
      does not resolve by itself, and it is worth having looked at.</p>'
				),
				'sections'    => array(
					array(
						'eyebrow' => 'Symptoms',
						'heading' => 'What it actually feels like',
						'html'    => cil_proto_html(
							'<p>Many men with a mildly tight foreskin have no symptoms at all and need nothing done. Treatment becomes
          worth considering when there is:</p>
          <ul style="margin:16px 0 0 20px;display:grid;gap:9px">
            <li>ballooning of the foreskin when passing urine, or spraying and a weak stream</li>
            <li>pain on erection, or tightness that makes sex uncomfortable or impossible</li>
            <li>splitting or bleeding at the tip, which then heals as more scar</li>
            <li>repeated episodes of <a href="/conditions/balanitis">balanitis</a>, with soreness, redness and discharge</li>
            <li>difficulty cleaning underneath, and the smell and irritation that follows</li>
          </ul>
          <p style="margin-top:16px">If the foreskin retracts but then will not come forward again and the glans starts
          to swell, that is <a href="/conditions/paraphimosis">paraphimosis</a> and it is an emergency. Go to A&amp;E.</p>'
						),
					),
					array(
						'eyebrow' => 'Treatment',
						'heading' => 'What can be done, in order of how much it takes',
						'html'    => cil_proto_html(
							'<p><strong>Nothing.</strong> If it does not hurt, does not interfere with urine or sex, and you can keep
          it clean, it is entirely reasonable to leave it alone. We say this more often than people expect.</p>
          <p><strong>Topical steroid and gentle stretching.</strong> A course of steroid cream applied to the tight
          band, combined with gentle daily retraction to the point of resistance and no further, resolves a substantial
          proportion of cases. It takes four to eight weeks and it is worth trying first in most non-scarred cases.
          Your GP can prescribe it.</p>
          <p><strong><a href="/procedures/preputioplasty">Preputioplasty</a>.</strong> A small operation that widens
          the tight ring without removing the foreskin. Quicker to heal than a circumcision and it keeps the foreskin,
          which matters to a lot of people. It is not suitable where there is dense scarring or BXO, because the
          scarring simply re-forms.</p>
          <p><strong><a href="/adults">Circumcision</a>.</strong> The definitive answer, and the right one where
          there is significant scarring, where BXO is present, or where the other options have been tried and failed.
          In adults it is done here under local anaesthetic with dissolvable sutures, from £680.</p>
          <p>Which of these applies to you is a matter of examination, not of reading. The tight band feels quite
          different depending on the cause, and the cause determines the treatment.</p>'
						),
					),
				),
				'faqs'        => array(
					array(
						'q' => 'My son is six and his foreskin does not pull back. Is that a problem?',
						'a' => '<p>Almost certainly not. Non-retractile foreskin at six is within the normal range and most resolve without any intervention, frequently not until the early teens. Unless he has symptoms such as ballooning, pain or repeated infection, the right course is to leave it alone and never force it. Do bring him in if you are worried; we would rather reassure you than have you worry.</p>',
					),
					array(
						'q' => 'Can I fix phimosis with stretching alone?',
						'a' => '<p>Sometimes, particularly in combination with a prescribed steroid cream and where there is no dense scarring. Gentle means gentle: to the point of resistance, never to the point of tearing. Tearing produces scar tissue and makes the problem worse. If eight weeks of that has not helped, it is time to be examined.</p>',
					),
					array(
						'q' => 'Will circumcision change sensation?',
						'a' => '<p>It removes the foreskin, so the sensation of the foreskin itself goes and the glans is permanently exposed, which many men describe as an initial reduction in sensitivity that settles over some months. Reported experience varies genuinely and honestly between individuals. What we can say clearly is that men who came in because sex was painful almost always describe the exchange as worth it.</p>',
					),
					array(
						'q' => 'Does the NHS treat phimosis?',
						'a' => '<p>Yes, where there is a clear medical indication: scarring, BXO, recurrent infection, or symptoms affecting urination. Start with your GP. The wait for non-urgent cases is commonly over a year, which is why many people choose to be seen privately, but if you can wait, waiting is a reasonable choice.</p>',
					),
				),
				'schema'      => array(
					'@type'             => 'MedicalCondition',
					'alternateName'     => array( 'Tight foreskin', 'Non-retractile foreskin' ),
					'possibleTreatment' => array(
						array(
							'@type' => 'MedicalTherapy',
							'name'  => 'Topical corticosteroid and gentle stretching',
						),
						array(
							'@type' => 'MedicalProcedure',
							'name'  => 'Preputioplasty',
							'url'   => cil_path_url( '/procedures/preputioplasty' ),
						),
						array(
							'@type' => 'MedicalProcedure',
							'name'  => 'Circumcision',
							'url'   => cil_path_url( '/adults' ),
						),
					),
				),
			)
		),
		'balanitis'     => cil_condition_page_data(
			array(
				'slug'        => 'balanitis',
				'name'        => 'Balanitis',
				'title'       => 'Balanitis | Causes and Treatment | Beverley Clinic London',
				'description' => 'Balanitis is inflammation of the glans: what causes it, how it is treated, and when repeated episodes make circumcision worth considering. Clinic in Edgware.',
				'eyebrow'     => 'Condition · Inflammation of the glans',
				'h1'          => 'Balanitis: soreness and inflammation of the glans',
				'lede'        => 'Balanitis is inflammation of the head of the penis, often with the foreskin involved as well. It is common, it is usually treated with a cream rather than an operation, and it is only when it keeps coming back that surgery enters the conversation.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>The glans becomes red, sore, itchy or swollen. There may be discharge, an unpleasant smell, discomfort passing
      urine, or splitting of the skin. Where the foreskin is inflamed too, the correct term is balanoposthitis, and it
      is far and away the more common presentation in uncircumcised men.</p>
      <p>It is worth being clear that balanitis is a description, not a diagnosis. Several quite different things
      produce the same red, sore glans, and they are treated differently, which is why guessing at the pharmacy
      counter so often fails.</p>'
				),
				'sections'    => array(
					array(
						'eyebrow' => 'Causes',
						'heading' => 'What is usually behind it',
						'html'    => cil_proto_html(
							'<p><strong>Candida (thrush).</strong> The most common single cause. Typically itchy, with a red glans,
          sometimes small white patches or a curd-like discharge. Treated with an antifungal cream. Partners often need
          treating at the same time or it simply returns.</p>
          <p><strong>Irritant or contact dermatitis.</strong> Soap, shower gel, detergent residue on underwear, latex,
          or spermicide. Often the cause when nothing infective is found. The treatment is identifying the culprit and
          stopping it, plus a mild steroid cream.</p>
          <p><strong>Poor drying, or over-washing.</strong> Both. Moisture trapped under the foreskin encourages
          overgrowth; scrubbing with soap strips the skin and inflames it. Warm water and careful drying is the whole
          instruction.</p>
          <p><strong>Bacterial infection.</strong> Less common, usually more painful, often with a heavier discharge.
          Needs antibiotics.</p>
          <p><strong>A skin condition.</strong> Psoriasis, lichen planus, and, importantly,
          <a href="/conditions/bxo">lichen sclerosus (BXO)</a>, which causes pale, thickened, scarring skin and needs a
          quite different approach.</p>
          <p><strong>Diabetes.</strong> Repeated candidal balanitis in an adult who has not been tested is a recognised
          way that undiagnosed diabetes first presents. If this keeps happening, ask your GP for a blood glucose test.
          We say this to people regularly and it matters.</p>'
						),
					),
					array(
						'eyebrow' => 'Treatment',
						'heading' => 'When surgery is and is not the answer',
						'html'    => cil_proto_html(
							'<p>Almost all first episodes are treated medically: identify the cause, treat it with the appropriate
          cream or tablet, correct the washing routine, and it settles. Your GP is the right person for this, and a
          swab is worth taking if it is not responding as expected.</p>
          <p>Circumcision becomes a reasonable conversation when episodes are <strong>recurrent</strong>, coming back
          repeatedly despite correct treatment, or when the inflammation has already caused scarring and a
          <a href="/conditions/phimosis">tight foreskin</a>, at which point the tightness traps moisture and drives the
          next episode. Removing the foreskin removes the warm, moist space under it, and for men in that cycle it is
          usually decisive.</p>
          <p>Where <a href="/conditions/bxo">BXO</a> is the underlying cause, circumcision is more than a comfort
          measure: it is the standard treatment, because BXO scars progressively and can go on to narrow the urethral
          opening if left.</p>
          <p>What we will not do is operate on a first episode that has not been properly treated medically. If that is
          where you are, we will tell you to see your GP.</p>'
						),
					),
				),
				'faqs'        => array(
					array(
						'q' => 'Is balanitis a sexually transmitted infection?',
						'a' => '<p>Usually not. Candida and irritant dermatitis, the two commonest causes, are not STIs. That said, some STIs can produce similar symptoms, so if there is any possibility, a sexual health clinic check is sensible and free, and they are very used to this.</p>',
					),
					array(
						'q' => 'How long should it take to clear up?',
						'a' => '<p>With the right treatment, usually within a week or two. If you have used an antifungal cream correctly for two weeks and nothing has changed, the diagnosis is probably wrong rather than the treatment failing. Go back and ask for a swab.</p>',
					),
					array(
						'q' => 'How many episodes before circumcision is worth considering?',
						'a' => '<p>There is no fixed number. The useful question is whether the episodes are genuinely recurrent despite correct treatment, and whether scarring or tightness has developed. Three or four properly treated episodes in a year, or any degree of scarring, makes it a reasonable conversation.</p>',
					),
					array(
						'q' => 'Should I stop using soap?',
						'a' => '<p>Under the foreskin, yes. Warm water alone, then dry carefully. Soap and shower gel are among the most common irritants we see, and stopping them resolves a surprising number of cases on their own.</p>',
					),
				),
				'schema'      => array(
					'@type'             => 'MedicalCondition',
					'alternateName'     => array( 'Balanoposthitis' ),
					'possibleTreatment' => array(
						array(
							'@type' => 'MedicalTherapy',
							'name'  => 'Topical antifungal or corticosteroid treatment',
						),
						array(
							'@type' => 'MedicalProcedure',
							'name'  => 'Circumcision',
							'url'   => cil_path_url( '/adults' ),
						),
					),
				),
			)
		),
		'bxo'           => cil_condition_page_data(
			array(
				'slug'        => 'bxo',
				'name'        => 'BXO',
				'title'       => 'BXO (Lichen Sclerosus) | Treatment in London | Beverley Clinic',
				'description' => 'Balanitis xerotica obliterans is a scarring condition of the foreskin. Why circumcision is the standard treatment, and why BXO should not be left. Edgware, London.',
				'eyebrow'     => 'Condition · Balanitis xerotica obliterans',
				'h1'          => 'BXO: lichen sclerosus of the foreskin and glans',
				'lede'        => 'BXO, or balanitis xerotica obliterans, is the genital form of lichen sclerosus. It is a scarring skin condition, and the one cause of a tight foreskin where circumcision is the treatment rather than one option among several. Leaving it has consequences.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>BXO produces a distinctive appearance: pale, white, thickened, slightly shiny skin, usually forming a firm ring
      at the tip of the foreskin, sometimes extending onto the glans. The skin loses its elasticity, splits easily, and
      heals with more scar than it started with. Over time the foreskin becomes progressively tighter and, in advanced
      cases, adherent to the glans.</p>
      <p>It is not an infection, it is not caused by anything you did, and it is not contagious. It is an inflammatory
      skin condition, and the cause is not fully understood.</p>'
				),
				'sections'    => array(
					array(
						'eyebrow' => 'Why it matters',
						'heading' => 'The reason not to leave it',
						'html'    => cil_proto_html(
							'<p>BXO is progressive. Left alone, the scarring tends to extend, and the two consequences worth knowing
          about are these.</p>
          <p>First, it can involve the <strong>urethral opening</strong>. Narrowing at the meatus makes the urine stream
          thin and slow, and it can progress to a stricture further along the urethra, which is a considerably bigger
          reconstructive problem than a circumcision. If your stream has changed, say so.</p>
          <p>Second, long-standing untreated genital lichen sclerosus carries a <strong>small but real increased risk
          of penile cancer</strong>. The absolute risk is low, and we mention it not to alarm but because it is a
          legitimate reason to treat it instead of tolerating it, and because you deserve to hear it from us and not from
          a search engine.</p>
          <p>The good news is that circumcision removes the affected foreskin and, for the great majority of men whose
          disease is confined to it, is curative. Recurrence after circumcision is uncommon.</p>'
						),
					),
					array(
						'eyebrow' => 'Treatment',
						'heading' => 'How BXO is managed',
						'html'    => cil_proto_html(
							'<p><strong>Diagnosis first.</strong> BXO is usually recognised by appearance. Where there is doubt, or
          where the skin looks atypical, tissue removed at circumcision is sent for histology to confirm it, which is
          another argument for having it treated instead of leaving it.</p>
          <p><strong>Potent topical steroid.</strong> A strong steroid ointment can settle the inflammation and is
          worth using in early or mild disease, particularly where the glans is involved. It controls; it does not
          reverse established scarring.</p>
          <p><strong><a href="/adults">Circumcision</a>.</strong> The standard treatment where the foreskin is
          affected, and for most men, definitive. Done here under local anaesthetic, from £680 for adults. Because BXO
          scarring re-forms, <a href="/procedures/preputioplasty">preputioplasty</a> and stretching regimes are
          <em>not</em> appropriate for BXO. That distinction matters, and it is one reason to have the
          diagnosis made properly.</p>
          <p><strong>Follow-up.</strong> Where the glans or the urethral opening is involved, you should stay under
          review even after circumcision. We will tell you if that applies to you and make sure it is arranged.</p>'
						),
					),
				),
				'faqs'        => array(
					array(
						'q' => 'How do I know it is BXO and not just a tight foreskin?',
						'a' => '<p>By appearance, mostly. BXO skin is pale, white and firm rather than simply narrow, and it often forms a distinct thickened ring at the tip. It cannot be diagnosed reliably from a description or a photograph. It needs examining, and sometimes confirming on histology.</p>',
					),
					array(
						'q' => 'Can stretching or a steroid cream cure it?',
						'a' => '<p>Steroid ointment can control the inflammation and is useful in early disease or where the glans is involved. It does not reverse established scarring. Stretching is specifically <strong>not</strong> advised in BXO: the tissue splits and heals with more scar, making things worse.</p>',
					),
					array(
						'q' => 'Will circumcision cure it?',
						'a' => '<p>For most men whose disease is confined to the foreskin, yes. Removing the affected tissue is definitive and recurrence is uncommon. Where the glans or urethral opening is involved, the circumcision deals with the foreskin but you should stay under review.</p>',
					),
					array(
						'q' => 'Is BXO contagious?',
						'a' => '<p>No. It is not an infection and it cannot be passed to a partner. Lichen sclerosus does occur in both sexes, but it is not transmitted between them.</p>',
					),
				),
				'schema'      => array(
					'@type'             => 'MedicalCondition',
					'alternateName'     => array( 'Balanitis xerotica obliterans', 'Genital lichen sclerosus', 'Male lichen sclerosus' ),
					'possibleTreatment' => array(
						array(
							'@type' => 'MedicalTherapy',
							'name'  => 'Potent topical corticosteroid',
						),
						array(
							'@type' => 'MedicalProcedure',
							'name'  => 'Circumcision',
							'url'   => cil_path_url( '/adults' ),
						),
					),
				),
			)
		),
		'paraphimosis'  => cil_condition_page_data(
			array(
				'slug'        => 'paraphimosis',
				'name'        => 'Paraphimosis',
				'title'       => 'Paraphimosis | Emergency Advice and Treatment | Beverley Clinic',
				'description' => 'Paraphimosis is a retracted foreskin trapped behind the glans. It is a urological emergency needing same-day care. What to do now, and what happens afterwards.',
				'eyebrow'     => 'Condition · Urological emergency',
				'h1'          => 'Paraphimosis: a foreskin trapped behind the glans',
				'lede'        => 'Paraphimosis happens when a retracted foreskin cannot be pulled forward again and forms a tight band behind the head of the penis. It is an emergency. If this is happening now, go to A&E. Do not wait for an appointment here or anywhere else.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p><strong>If you are reading this because it is happening now: go to your nearest emergency department, today.</strong>
      Paraphimosis is time-critical. The retracted foreskin acts as a tourniquet, the glans swells, the swelling makes
      the band tighter still, and the cycle continues. Treated within hours it is straightforward. Left for a day or
      more it can compromise the blood supply to the glans, and that is a serious problem.</p>
      <p>Do not book an appointment and wait. Emergency departments deal with this routinely and are not remotely
      embarrassed by it.</p>'
				),
				'sections'    => array(
					array(
						'eyebrow' => 'Recognising it',
						'heading' => 'What it looks and feels like',
						'html'    => cil_proto_html(
							'<p>The foreskin has been pulled back and will not come forward. This often happens during washing or sex,
          or after a catheter insertion or medical examination that was not reversed. Behind the head of the penis there is a tight,
          often visible band of foreskin. The glans becomes progressively swollen, tense, and painful, and its colour
          may deepen. Passing urine becomes uncomfortable.</p>
          <p>It occurs disproportionately in men with an existing <a href="/conditions/phimosis">tight foreskin</a>, in
          older men, and after hospital procedures where the foreskin was retracted and nobody replaced it, which is a
          well-recognised and entirely preventable cause.</p>'
						),
					),
					array(
						'eyebrow' => 'Afterwards',
						'heading' => 'Preventing the next one',
						'html'    => cil_proto_html(
							'<p>In the emergency department the swelling is reduced, usually with firm sustained compression, ice and
          sometimes a local anaesthetic block, and the foreskin is returned to position. Occasionally a small
          incision in the constricting band is needed to release it.</p>
          <p>Once it is resolved, the question becomes why it happened. If the underlying problem is a tight foreskin,
          it will very likely happen again, and the standard advice after an episode of paraphimosis is to consider
          <a href="/adults">circumcision</a> to prevent recurrence. That is a conversation for a calm week, not
          for the night it happens.</p>
          <p>That is where we can help. Once you are through the acute episode we can see you quickly,
          <strong>' . esc_html( strtolower( cil_clinic()['next_available'] ) ) . '</strong>, examine the foreskin, and talk through whether
          circumcision is the right answer for you, or whether a foreskin-sparing
          <a href="/procedures/preputioplasty">preputioplasty</a> would suit you better.</p>
          <p>In the meantime, if the foreskin is retracted for any reason, whether washing, sex or a medical examination,
          always return it to its normal position afterwards. That single habit prevents most recurrences.</p>'
						),
					),
				),
				'faqs'        => array(
					array(
						'q' => 'Can I push it back myself?',
						'a' => '<p>In the very early stages, with minimal swelling, gentle steady compression of the glans for several minutes followed by easing the foreskin forward sometimes works. If it does not work quickly, or there is significant swelling or pain, stop and go to A&E. Forcing it causes damage.</p>',
					),
					array(
						'q' => 'How urgent is urgent?',
						'a' => '<p>Hours, not days. Treated the same day it is routine. Left for a day or more the swelling and reduced blood supply cause tissue damage. This is one of the few genuinely time-critical problems in this area of medicine.</p>',
					),
					array(
						'q' => 'Will it happen again?',
						'a' => '<p>If the underlying foreskin is tight, quite possibly. After a first episode, being examined and considering circumcision is the standard advice, precisely because recurrence is common and each episode carries the same risk.</p>',
					),
					array(
						'q' => 'Can Beverley Clinic treat it as an emergency?',
						'a' => '<p>No, and it is better to say so than have you drive here. We are an appointment-based clinic and this needs an emergency department. Come to us afterwards, for the conversation about preventing the next one.</p>',
					),
				),
				'schema'      => array(
					'@type'             => 'MedicalCondition',
					'possibleTreatment' => array(
						array(
							'@type' => 'MedicalProcedure',
							'name'  => 'Emergency manual reduction',
						),
						array(
							'@type' => 'MedicalProcedure',
							'name'  => 'Circumcision',
							'url'   => cil_path_url( '/adults' ),
						),
					),
				),
			)
		),
	);
}

/**
 * Fill shared fields for a condition page.
 *
 * @param array<string, mixed> $page Partial page data.
 * @return array<string, mixed>
 */
function cil_condition_page_data( $page ) {
	$page['parent']       = 'conditions';
	$page['parent_title'] = 'Reasons';
	$page['path']         = '/conditions/' . $page['slug'];
	if ( ! empty( $page['schema'] ) && is_array( $page['schema'] ) ) {
		if ( empty( $page['schema']['name'] ) ) {
			$page['schema']['name'] = $page['name'];
		}
	}
	$page['crumbs'] = array(
		array(
			'label' => 'Reasons',
			'href'  => $page['reasons'],
		),
		array(
			'label' => $page['name'],
			'href'  => '',
		),
	);
	unset( $page['reasons'] );
	return $page;
}

/**
 * Gutenberg markup for one condition page.
 *
 * @param string $slug Condition slug.
 * @return string
 */
function cil_condition_page_blocks( $slug ) {
	$pages = cil_condition_pages();
	if ( empty( $pages[ $slug ] ) ) {
		return '';
	}
	return cil_clinical_page_blocks( $pages[ $slug ] );
}

/**
 * Shared Gutenberg markup for prototype condition/procedure pages.
 *
 * Layout from src/pages/conditions.js conditionPage().
 *
 * @param array<string, mixed> $page Page data.
 * @return string
 */
function cil_clinical_page_blocks( $page ) {
	$clinic = cil_clinic();
	$next   = strtolower( $clinic['next_available'] );
	$urgent = cil_render_part( 'urgent-note' );

	$being_seen = cil_proto_html(
		'<div data-reveal>
        <span class="caps eyebrow">Being seen</span>
        <h2 class="display d-2">Getting somebody to look at it</h2>
        <div class="body-text" style="margin-top:20px">
          <p>Your GP is the right first stop, and if the NHS will treat you and you can wait, that is a sensible
          choice. In our experience the wait for non-urgent foreskin surgery runs beyond a year.</p>
          <p>Here, a consultation is <strong>' . esc_html( $next ) . '</strong>. You are examined by a named
          practitioner, told clearly whether a procedure is the right answer, and given the price in writing. If we
          think you should be seen on the NHS instead, we will tell you that too.</p>
        </div>
        <div class="btn-row" style="margin-top:26px">
          <a class="btn" href="' . esc_url( cil_book_url() ) . '" data-track="book-condition">Book a consultation</a>
          <a class="btn btn-ghost" href="' . esc_url( $clinic['phone']['href'] ) . '" data-track="call-condition">Call ' . esc_html( $clinic['phone']['display'] ) . '</a>
        </div>
        <div style="margin-top:26px">' . $urgent . '</div>
      </div>'
	);

	$blocks   = array();
	$blocks[] = cil_dyn_block(
		'cil/page-head',
		array(
			'eyebrow'    => $page['eyebrow'],
			'title'      => $page['h1'],
			'lede'       => $page['lede'],
			'reviewedBy' => cil_reviewed_by_haidar(),
			'crumbs'     => $page['crumbs'],
		)
	);

	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => '',
			'wrap' => 'wrap-narrow',
		),
		array(
			cil_html_block( '<div class="body-text" data-reveal>' . $page['intro'] . '</div>' ),
		)
	);

	foreach ( $page['sections'] as $i => $section ) {
		$blocks[] = cil_dyn_block(
			'cil/text-section',
			array(
				'eyebrow' => $section['eyebrow'],
				'heading' => $section['heading'],
				'html'    => '<div class="body-text">' . $section['html'] . '</div>',
				'narrow'  => true,
				'banded'  => ( 0 === $i % 2 ),
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
					cil_html_block( $being_seen ),
					cil_dyn_block(
						'cil/callback-card',
						array(
							'eyebrow' => 'Request a call back',
							'title'   => 'Describe it to us',
							'formId'  => $page['slug'],
							'subject' => strtolower( $page['name'] ) . ' enquiry',
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
			'title' => 'Get an examination and a straight answer',
			'text'  => 'Most of what is written above only becomes useful once somebody has actually looked. Nothing is booked, and no deposit is taken, until you decide.',
		)
	);

	$blocks[] = cil_dyn_block(
		'cil/faq',
		array(
			'heading' => $page['name'] . ': questions',
			'items'   => $page['faqs'],
		)
	);

	return cil_serialize_blocks( $blocks );
}
