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
 * Prototype data for frenuloplasty. Preputioplasty is not offered.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_procedure_pages() {
	$reasons = cil_path_url( '/conditions/phimosis' );

	return array(
		'frenuloplasty' => cil_procedure_page_data(
			array(
				'slug'        => 'frenuloplasty',
				'name'        => 'Frenuloplasty',
				'title'       => 'Frenuloplasty London | Tight Frenulum Surgery | Beverley Clinic',
				'description' => 'Frenuloplasty releases a short frenulum that tethers or tears during sex. A small procedure under local anaesthetic that keeps the foreskin. Edgware, London.',
				'eyebrow'     => 'Procedure · Keeps the foreskin',
				// h1, lede, FAQs and schema text: Frenulum.docx (client source).
				'h1'          => 'Frenuloplasty for a Tight or Short Frenulum',
				'lede'        => 'A tight or short penile frenulum, sometimes called frenulum breve, can cause pulling, pain, tearing or bleeding underneath the head of the penis, particularly during erections, masturbation or sexual intercourse.',
				'reasons'     => $reasons,
				'intro'       => cil_proto_html(
					'<p>Frenuloplasty is a smaller procedure designed to release the tight frenulum while preserving the foreskin. We assess the cause of the symptoms and discuss whether frenuloplasty, circumcision or another option is the better fit for the individual patient.</p>
      <p>See also <a href="/adults">adult circumcision</a>, <a href="/conditions/phimosis">phimosis</a> and <a href="/aftercare">aftercare</a>.</p>'
				),
				'sections'    => array(),
				'faqs'        => array(
					array( 'q' => 'What is a frenulum?', 'a' => 'The penile frenulum is the band of tissue connecting the underside of the glans to the inner foreskin.' ),
					array( 'q' => 'What is frenulum breve?', 'a' => 'Frenulum breve is the medical term for a frenulum that is too short or tight.' ),
					array( 'q' => 'How do I know if my frenulum is too short?', 'a' => 'Common signs include pulling underneath the glans during an erection, pain during sex, repeated tearing or bleeding and downward pulling of the glans.' ),
					array( 'q' => 'Can a tight frenulum cause pain during sex?', 'a' => 'Yes. The frenulum can become stretched and painful as the foreskin moves backwards during intercourse.' ),
					array( 'q' => 'Can a short frenulum cause bleeding?', 'a' => 'Yes. A tight frenulum can tear during sexual activity and may bleed because the frenulum contains blood vessels.' ),
					array( 'q' => 'Is a tight frenulum the same as phimosis?', 'a' => 'No. Phimosis is tightness of the foreskin opening. A tight frenulum is restriction caused by the band of tissue underneath the glans. A patient can have either condition or both.' ),
					array( 'q' => 'What is frenuloplasty?', 'a' => 'Frenuloplasty is a minor surgical procedure that releases and lengthens a short or tight frenulum.' ),
					array( 'q' => 'Does frenuloplasty remove my foreskin?', 'a' => 'No. Frenuloplasty can treat an isolated frenulum problem while leaving the foreskin intact.' ),
					array( 'q' => 'Is frenuloplasty performed under local anaesthetic?', 'a' => 'Suitable patients at Beverley Clinic can have frenuloplasty under local anaesthetic.' ),
					array( 'q' => 'Will I need stitches?', 'a' => 'This depends on the procedure and wound. Dissolvable stitches, medical skin glue, a combination of the two, or occasionally neither may be used.' ),
					array( 'q' => 'Can you treat the frenulum during circumcision?', 'a' => 'Yes. If circumcision is required and the frenulum is also tight or scarred, it can be treated during the procedure where appropriate.' ),
					array( 'q' => 'Can I have frenuloplasty if I have already been circumcised?', 'a' => 'Yes. Remaining frenular tissue can still cause pulling or discomfort after circumcision and can be assessed separately.' ),
					array( 'q' => 'When can I have sex after frenuloplasty?', 'a' => 'At Beverley Clinic, we generally advise avoiding sexual intercourse and masturbation for at least two weeks and until the wound is sufficiently healed. If healing is incomplete, you should wait longer.' ),
					array( 'q' => 'What if my frenulum keeps tearing?', 'a' => 'Repeated tearing can result in further scar tissue and tightness. We can assess whether frenuloplasty is appropriate.' ),
				),
				'schema'      => array(
					'@type'         => 'MedicalProcedure',
					'procedureType' => 'https://schema.org/SurgicalProcedure',
					'howPerformed'  => 'The tight frenulum is released to remove the restrictive band underneath the glans. Depending on the anatomy and the wound, dissolvable stitches, medical skin glue, a combination of the two, or occasionally neither may be used.',
					'followup'      => 'At Beverley Clinic, we generally advise avoiding sexual intercourse and masturbation for at least two weeks and until the wound is sufficiently healed.',
				),
			)
		),
	);
}

/**
 * Clinic does not offer preputioplasty. Trash any leftover published page.
 */
function cil_retire_preputioplasty_page() {
	if ( wp_installing() || wp_doing_ajax() || wp_doing_cron() ) {
		return;
	}
	if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
		return;
	}
	if ( get_option( 'cil_preputioplasty_retired' ) ) {
		return;
	}

	// Do not trash content on anonymous public requests. Admin, CLI and theme
	// activation still retire a leftover published page once, then record it.
	if ( function_exists( 'cil_is_page_setup_request' ) && ! cil_is_page_setup_request() ) {
		return;
	}

	$page = get_page_by_path( 'procedures/preputioplasty', OBJECT, 'page' );
	if ( ! $page ) {
		$page = get_page_by_path( 'preputioplasty', OBJECT, 'page' );
	}
	if ( $page && 'publish' === $page->post_status ) {
		wp_trash_post( (int) $page->ID );
		$page = get_post( (int) $page->ID );
	}
	if ( ! $page || 'publish' !== $page->post_status ) {
		update_option( 'cil_preputioplasty_retired', '1' );
	}
}
add_action( 'init', 'cil_retire_preputioplasty_page', 50 );
add_action( 'after_switch_theme', 'cil_retire_preputioplasty_page' );

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
