<?php
/**
 * Procedure pages from prototype src/pages/conditions.js.
 *
 * Nested under /procedures/{slug}. Layout matches the condition pages.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prototype data for frenuloplasty and preputioplasty.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_procedure_pages() {
	$reasons = cil_path_url( '/conditions/phimosis' );

	return array(
		'frenuloplasty'  => cil_procedure_page_data(
			array(
				'slug'        => 'frenuloplasty',
				'name'        => 'Frenuloplasty',
				'title'       => 'Frenuloplasty London | Tight Frenulum Surgery | Beverley Clinic',
				'description' => 'Frenuloplasty releases a short frenulum that tethers or tears during sex. A small procedure under local anaesthetic that keeps the foreskin. Edgware, London.',
				'eyebrow'     => 'Procedure · Keeps the foreskin',
				'h1'          => 'Frenuloplasty for a tight or short frenulum',
				'lede'        => 'The frenulum is the small band of tissue on the underside of the penis, joining the foreskin to the glans. When it is too short it tethers, tears and bleeds. A frenuloplasty releases it, and unlike a circumcision it keeps the foreskin.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>A short frenulum, known as frenulum breve, is one of the most commonly missed causes of pain during sex in men. The
      band pulls the glans downward on erection, produces a bowing or curve, and under tension it splits. The split
      heals as scar, the scar is shorter and less elastic than what it replaced, and the next tear comes more easily.
      Many men have been through that cycle several times before anyone examines them properly.</p>
      <p>It is frequently mistaken for phimosis, and it matters, because the treatment is different and considerably
      smaller. If the foreskin retracts perfectly well but something on the underside pulls and hurts, this page is
      probably the relevant one.</p>'
				),
				'sections'    => array(
					array(
						'eyebrow' => 'The procedure',
						'heading' => 'What frenuloplasty involves',
						'html'    => cil_proto_html(
							'<p>It is a small operation, done under local anaesthetic, taking roughly twenty to thirty minutes
          including preparation. The tight band is divided transversely and then closed longitudinally. That sounds
          like a technicality but it is the entire point: it converts a short band into a longer one, lengthening the
          tissue instead of simply cutting it.</p>
          <p>Dissolvable sutures are used, so nothing needs removing. You go home the same day.</p>
          <p>Recovery is quicker than a circumcision. Expect discomfort for a few days, ordinary activity within two or
          three, and no sexual activity for four weeks to let the repair mature properly. That last instruction is the
          one people are tempted to shorten, and it is the one that matters most.</p>
          <p>Where the frenulum is tight <em>and</em> the foreskin is too narrow as well, the two are dealt with
          together at a <a href="/adults">circumcision</a>; that combination is priced at £880.</p>'
						),
					),
					array(
						'eyebrow' => 'Deciding',
						'heading' => 'Frenuloplasty or circumcision?',
						'html'    => cil_proto_html(
							'<p>If the only problem is the frenulum, a frenuloplasty is the smaller, quicker and more conservative
          operation and it keeps the foreskin. That is usually the right answer.</p>
          <p>Circumcision becomes the better option where there is <em>also</em> a properly tight foreskin, where
          there is scarring from repeated tearing that has now involved the foreskin as well, or where
          <a href="/conditions/bxo">BXO</a> is present, in which case preserving tissue that will continue to scar
          serves nobody.</p>
          <p>There is a small chance, perhaps one in twenty, that a frenuloplasty tightens as it heals and needs
          revising or converting to a circumcision. You should know that in advance instead of discovering it
          afterwards.</p>
          <p>Which applies to you is a two-minute examination.</p>'
						),
					),
				),
				'faqs'        => array(
					array(
						'q' => 'How do I know it is the frenulum and not the foreskin?',
						'a' => '<p>A useful distinction: if the foreskin retracts fully and comfortably but something on the underside pulls, tethers or tears, it is the frenulum. If the foreskin will not come back over the glans at all, it is phimosis. Both can be present together, which is why it is worth being examined rather than self-diagnosing.</p>',
					),
					array(
						'q' => 'It has torn before and healed. Do I still need surgery?',
						'a' => '<p>Not necessarily, but a torn frenulum heals shorter and less elastic than it was, so tears tend to recur and each one makes the next more likely. If it has happened more than once, releasing it properly usually ends the cycle.</p>',
					),
					array(
						'q' => 'Will it affect sensation?',
						'a' => '<p>The frenulum is a sensitive area and men reasonably worry about this. A frenuloplasty lengthens the tissue rather than removing it, and most men report that sex is better afterwards simply because it no longer hurts. It is a much more conservative operation than circumcision in this respect.</p>',
					),
					array(
						'q' => 'How long before I can have sex?',
						'a' => '<p>Four weeks. It is a small operation with a short recovery, but the repair needs time to gain strength, and going back too early is the commonest reason a frenuloplasty needs redoing.</p>',
					),
				),
				'schema'      => array(
					'@type'         => 'MedicalProcedure',
					'procedureType' => 'https://schema.org/SurgicalProcedure',
					'howPerformed'  => 'The short frenulum is divided transversely and closed longitudinally with dissolvable sutures, lengthening the tissue. Performed under local anaesthetic as a day case.',
					'followup'      => 'No sexual activity for four weeks; follow-up appointments until healed.',
				),
			)
		),
		'preputioplasty' => cil_procedure_page_data(
			array(
				'slug'        => 'preputioplasty',
				'name'        => 'Preputioplasty',
				'title'       => 'Preputioplasty London | Foreskin-Sparing | Beverley Clinic',
				'description' => 'Preputioplasty widens a tight foreskin without removing it, an alternative to circumcision in suitable cases. What it involves and when it is not right for you.',
				'eyebrow'     => 'Procedure · Foreskin-sparing',
				'h1'          => 'Preputioplasty: widening a tight foreskin without removing it',
				'lede'        => 'Preputioplasty is the operation most men with a tight foreskin have never heard of. It releases the constricting ring and widens the opening while leaving the foreskin in place. It is not right for everyone, but where it is right it is a smaller operation with a shorter recovery.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>A great many men arrive at a clinic assuming that a tight foreskin means circumcision, because that is the
      only option anyone has mentioned. For a proportion of them there is a real alternative, and we think it is
      worth explaining properly instead of leaving it to be discovered afterwards.</p>
      <p>Preputioplasty works on the constricting band itself. One or more small incisions are made across the tight
      ring and then closed at right angles to the direction they were cut, the same principle as a
      <a href="/procedures/frenuloplasty">frenuloplasty</a>. The circumference is increased, the foreskin retracts
      normally, and the foreskin itself is preserved.</p>'
				),
				'sections'    => array(
					array(
						'eyebrow' => 'The procedure',
						'heading' => 'What is involved',
						'html'    => cil_proto_html(
							'<p>It is carried out under local anaesthetic and takes roughly thirty minutes, with dissolvable
          sutures and same-day discharge.</p>
          <p>Recovery is generally quicker and more comfortable than a circumcision: discomfort for a few days,
          ordinary activity within two or three, and four weeks before sexual activity. There is no exposed glans to
          adapt to, and the cosmetic change is minimal, which for many men is the point. Gentle daily retraction while
          it heals is what stops the ring narrowing again.</p>
          <p><strong>What this clinic does.</strong> Our practice is circumcision, including removal of the frenulum
          where that is needed. We have written this page because a foreskin-sparing option exists and most men are
          never told about it, not to sell you one. If you come for a consultation and we think preputioplasty is the
          better answer for you, we will say so and point you towards a urologist rather than talk you into the
          operation we happen to perform.</p>'
						),
					),
					array(
						'eyebrow' => 'Suitability',
						'heading' => 'When it is not the right operation',
						'html'    => cil_proto_html(
							'<p>This is the part where enthusiasm helps nobody. Preputioplasty is <strong>not</strong>
          appropriate where:</p>
          <ul style="margin:16px 0 0 20px;display:grid;gap:9px">
            <li><a href="/conditions/bxo">BXO or lichen sclerosus</a> is present, because the disease continues in the
              retained tissue and the scarring simply re-forms. This is the single most important exclusion.</li>
            <li>there is dense, established scarring from repeated tearing or infection</li>
            <li>the foreskin is markedly redundant as well as tight</li>
            <li>the reason for the procedure is religious or cultural, where a circumcision is what is intended</li>
            <li>there is recurrent <a href="/conditions/balanitis">balanitis</a> driven by the space under the
              foreskin, which preputioplasty preserves</li>
          </ul>
          <p style="margin-top:16px">There is also a meaningful recurrence rate. A proportion of men, usually quoted at
          around one in ten and higher where there was scarring to begin with, find the ring tightens again and go on
          to have a circumcision anyway. That is not a reason to avoid it, but it is a reason to hear
          it in advance.</p>
          <p>Whether you are a candidate is a matter of examining the foreskin and establishing what is causing the
          tightness. That is a five-minute job, and it is worth doing before you commit to anything larger.</p>'
						),
					),
				),
				'faqs'        => array(
					array(
						'q' => 'Why has nobody offered me this before?',
						'a' => '<p>It is less commonly performed than circumcision, it suits a narrower group of patients, and it has a recurrence rate that circumcision does not. Those are reasonable clinical grounds for offering it less often. They are not a reason for never mentioning it, which is why we have written this page.</p>',
					),
					array(
						'q' => 'What are the chances I will need a circumcision anyway?',
						'a' => '<p>Around one in ten in suitable cases, and higher where there was significant scarring at the outset. If it does tighten again, a circumcision remains perfectly possible afterwards, so you have not closed any doors by trying.</p>',
					),
					array(
						'q' => 'Is the recovery really shorter than a circumcision?',
						'a' => '<p>Generally yes. There is less tissue disturbed and no exposed glans to adapt to. Most men are comfortable within a few days. The four-week wait before sex is the same, because the same principle applies to the healing repair.</p>',
					),
					array(
						'q' => 'Will it look different?',
						'a' => '<p>Very little. That is the main reason men choose it. There are small scars on the foreskin which usually become difficult to see, and the foreskin continues to function as it did. It simply retracts properly.</p>',
					),
				),
				'schema'      => array(
					'@type'         => 'MedicalProcedure',
					'procedureType' => 'https://schema.org/SurgicalProcedure',
					'howPerformed'  => 'One or more incisions are made across the constricting preputial ring and closed at right angles, widening the circumference while preserving the foreskin. Performed under local anaesthetic as a day case.',
					'followup'      => 'Gentle daily retraction during healing; no sexual activity for four weeks.',
				),
			)
		),
	);
}

/**
 * Fill shared fields for a procedure page.
 *
 * @param array<string, mixed> $page Partial page data.
 * @return array<string, mixed>
 */
function cil_procedure_page_data( $page ) {
	$page['parent']       = 'procedures';
	$page['parent_title'] = 'Procedures';
	$page['path']         = '/procedures/' . $page['slug'];
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
 * Gutenberg markup for one procedure page.
 *
 * @param string $slug Procedure slug.
 * @return string
 */
function cil_procedure_page_blocks( $slug ) {
	$pages = cil_procedure_pages();
	if ( empty( $pages[ $slug ] ) ) {
		return '';
	}
	return cil_clinical_page_blocks( $pages[ $slug ] );
}
