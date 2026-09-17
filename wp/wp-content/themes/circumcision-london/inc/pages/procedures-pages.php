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
				'h1'          => 'Frenuloplasty for a tight frenulum',
				'lede'        => 'A short or tight frenulum can cause pulling, discomfort or tearing, particularly during erection or sexual activity. If the foreskin itself is otherwise healthy, full circumcision may not be necessary.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>Frenuloplasty is a smaller procedure designed to release the tight frenulum while preserving the foreskin. We assess the cause of the symptoms and discuss whether frenuloplasty, circumcision or another option is the better fit for the individual patient.</p>
      <p>See also <a href="/adults">adult circumcision</a>, <a href="/conditions/phimosis">phimosis</a> and <a href="/aftercare">aftercare</a>.</p>'
				),
				'sections'    => array(),
				'faqs'        => array(),
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
