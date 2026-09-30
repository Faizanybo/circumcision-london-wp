<?php
/**
 * One-off: insert client Homepage additional sections into the live Home page,
 * then export the Gutenberg fixture. Does not hard-code content into theme PHP.
 *
 * Usage (from repo root, with Local PHP):
 *   php tools/content/apply-home-additional-sections.php
 */
$root    = dirname( __DIR__, 2 );
$wp_load = $root . '/app/public/wp-load.php';
if ( ! is_file( $wp_load ) ) {
	fwrite( STDERR, "wp-load.php not found\n" );
	exit( 1 );
}

require $wp_load;

if ( ! function_exists( 'cil_serialize_blocks' ) ) {
	fwrite( STDERR, "Theme helpers missing (cil_serialize_blocks)\n" );
	exit( 1 );
}

/**
 * Resolve the published Homepage post.
 *
 * @return WP_Post|null
 */
function cil_home_resolve_page() {
	$front = (int) get_option( 'page_on_front' );
	if ( $front ) {
		$post = get_post( $front );
		if ( $post && 'page' === $post->post_type && 'publish' === $post->post_status ) {
			return $post;
		}
	}
	foreach ( array( 'home', 'homepage' ) as $slug ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $page && 'publish' === $page->post_status ) {
			return $page;
		}
	}
	return null;
}

/**
 * Absolute site URL for a local path (no invented destinations).
 *
 * @param string $path Path beginning with /.
 * @return string
 */
function cil_home_url_path( $path ) {
	return home_url( $path );
}

/**
 * Core paragraph with optional class.
 *
 * @param string $html Inner HTML (escaped by caller where needed).
 * @param string $class Optional class.
 * @return array
 */
function cil_home_paragraph( $html, $class = '' ) {
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
 * Core heading with display class matching theme patterns.
 *
 * @param string $text  Plain text.
 * @param int    $level Heading level.
 * @param string $class Extra class.
 * @return array
 */
function cil_home_heading( $text, $level = 3, $class = 'wp-block-heading display d-3' ) {
	$level = max( 2, min( 6, (int) $level ) );
	$tag   = 'h' . $level;
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
 * Learn-more paragraph link.
 *
 * @param string $label Link text.
 * @param string $path  Site path.
 * @return array
 */
function cil_home_learn_more( $label, $path ) {
	$url  = esc_url( cil_home_url_path( $path ) );
	$text = esc_html( $label );
	return cil_home_paragraph( '<a href="' . $url . '">' . $text . '</a>' );
}

$base = untrailingslashit( home_url() );

$why_items = array(
	array(
		'title' => 'Specialist focus',
		'body'  => 'Circumcision is a core part of our day-to-day clinical work rather than an occasional procedure. Our practitioners have extensive experience in circumcision and have also trained doctors from the UK and abroad in circumcision techniques and patient care.',
	),
	array(
		'title' => 'All ages',
		'body'  => "We provide circumcision for babies, toddlers, children, teenagers and adults, using techniques appropriate to the patient's age, anatomy and individual circumstances.",
	),
	array(
		'title' => 'Continuity of care',
		'body'  => 'Our care does not end when the procedure is finished. Patients and parents receive preparation information before treatment, detailed written aftercare afterwards, a next-day follow-up call and ongoing support throughout the healing period.',
	),
	array(
		'title' => 'Trusted by patients and parents',
		'body'  => 'Much of our work comes through word-of-mouth recommendations from previous patients and families. We are proud that patients continue to recommend Beverley Clinic to their friends, relatives and communities.',
	),
	array(
		'title' => 'Thousands of patient reviews',
		'body'  => 'We have received thousands of 5 star reviews from patients and parents who have used our service. Read about their experiences on Google, Trustpilot and Yell.com.',
	),
	array(
		'title' => 'Convenient North-West London location',
		'body'  => 'Our clinic is located in Edgware, North-West London, with good public transport links and free street parking nearby at all times.',
	),
);

$why_section = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-card edge',
		'wrap'   => 'wrap',
		'anchor' => 'why-patients-come-to-us',
	),
	array(
		cil_dyn_block(
			'cil/section-head',
			array(
				'eyebrow' => '',
				'heading' => 'Why patients come to us',
				'lede'    => '',
				'display' => 'd-2',
			)
		),
		cil_dyn_block(
			'cil/info-cards',
			array(
				'items'        => $why_items,
				'columns'      => 'g-3',
				'headingLevel' => 3,
			)
		),
	)
);

$medical_inner = array(
	cil_dyn_block(
		'cil/section-head',
		array(
			'eyebrow' => '',
			'heading' => 'Circumcision for medical reasons',
			'lede'    => '',
			'display' => 'd-2',
		)
	),
	array(
		'blockName'   => 'core/group',
		'attrs'       => array( 'className' => 'body-text' ),
		'innerBlocks' => array(
			cil_home_paragraph( 'Circumcision is not only performed for religious (Islam and Judaism), cultural or personal reasons. It can also be recommended to treat certain medical conditions affecting the foreskin or penis. We assess each patient individually and will discuss whether circumcision or an alternative treatment is most appropriate.' ),

			cil_home_heading( 'Phimosis (tight foreskin)', 3 ),
			cil_home_paragraph( 'Phimosis occurs when the foreskin is too tight to retract comfortably over the head of the penis (glans). It can cause discomfort, difficulty retracting the foreskin and problems with hygiene, and in some patients circumcision provides a long-term solution.' ),
			cil_home_paragraph( "When circumcision is performed for phimosis, it is important that the tight or scarred area of foreskin is adequately treated. The technique is therefore tailored to the individual patient's anatomy and the extent of the tightness or scarring." ),
			cil_home_learn_more( 'Learn more about phimosis →', '/conditions/phimosis/' ),

			cil_home_heading( 'Recurrent balanitis', 3 ),
			cil_home_paragraph( 'Balanitis is inflammation of the head of the penis (glans), which can cause redness, soreness, irritation and discomfort. Recurrent episodes can sometimes be associated with a tight foreskin, particularly where the foreskin is difficult to retract and cleaning underneath it is challenging.' ),
			cil_home_paragraph( 'Where balanitis keeps returning, we can assess the foreskin and discuss whether circumcision may be appropriate.' ),
			cil_home_learn_more( 'Learn more about balanitis →', '/conditions/balanitis/' ),

			cil_home_heading( 'BXO / lichen sclerosis', 3 ),
			cil_home_paragraph( 'BXO (balanitis xerotica obliterans), also known as male genital lichen sclerosis, can affect the foreskin and cause whitening, thickening, scarring and progressive tightness.' ),
			cil_home_paragraph( 'It can sometimes resemble phimosis, but the presence of scarring means careful assessment is important. Circumcision may be recommended where the foreskin is significantly affected.' ),
			cil_home_learn_more( 'Learn more about BXO / lichen sclerosis →', '/conditions/bxo/' ),

			cil_home_heading( 'Paraphimosis', 3 ),
			cil_home_paragraph( 'Paraphimosis occurs when the foreskin has been pulled back behind the head of the penis and cannot be returned to its normal position. This can cause increasing swelling and pain and, in some circumstances, requires urgent medical treatment.' ),
			cil_home_paragraph( 'If you currently have a painful, swollen foreskin trapped behind the head of the penis, particularly if the swelling is increasing or you are having difficulty passing urine, seek urgent medical attention rather than waiting for a routine clinic appointment.' ),
			cil_home_learn_more( 'Learn more about paraphimosis →', '/conditions/paraphimosis/' ),

			cil_home_heading( 'Tight frenulum', 3 ),
			cil_home_paragraph( 'The frenulum is the band of tissue on the underside of the penis connecting the foreskin to the head of the penis (glans). If it is unusually short, tight or scarred, it can cause pulling, pain, bleeding or downward curvature of the glans, particularly during an erection or sexual activity.' ),
			cil_home_paragraph( 'Circumcision is not always necessary for a tight frenulum. In suitable patients, releasing or lengthening the frenulum can treat the problem while preserving the foreskin.' ),
			cil_home_learn_more( 'Learn more about frenuloplasty and tight frenulum →', '/procedures/frenuloplasty/' ),

			cil_home_heading( 'Buried penis', 3 ),
			cil_home_paragraph( 'A buried or hidden penis is where some or all of the penis is concealed within the surrounding pubic tissue. This can sometimes be associated with excess fatty tissue around the pubic area, although there are other causes.' ),
			cil_home_paragraph( 'Recognising a buried penis before circumcision is important because it can influence both the procedure and the aftercare required. Our practitioners will assess this and explain any additional care that may be needed following circumcision.' ),
			cil_home_learn_more( 'Learn more about buried penis →', '/buried-penis/' ),
		),
		'innerHTML'    => '',
		'innerContent' => array(),
	),
);

// Populate group innerContent for valid save markup.
$medical_group                 = &$medical_inner[1];
$medical_group['innerContent'] = array( '<div class="wp-block-group body-text">' );
foreach ( $medical_group['innerBlocks'] as $_i ) {
	$medical_group['innerContent'][] = null;
}
$medical_group['innerContent'][] = '</div>';

$medical_section = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => '',
		'wrap'   => 'wrap-narrow',
		'anchor' => 'circumcision-for-medical-reasons',
	),
	$medical_inner
);

$methods_inner = array(
	cil_dyn_block(
		'cil/section-head',
		array(
			'eyebrow' => '',
			'heading' => 'Circumcision methods used at our London clinic',
			'lede'    => '',
			'display' => 'd-2',
		)
	),
	array(
		'blockName'   => 'core/group',
		'attrs'       => array( 'className' => 'body-text' ),
		'innerBlocks' => array(
			cil_home_paragraph( "There are several ways to perform a circumcision, and no single method is suitable for every patient. At Beverley Clinic, we select the circumcision technique according to the patient's age, anatomy, reason for circumcision and findings on examination." ),
			cil_home_paragraph( 'Our practitioners have extensive experience in circumcising babies, children, teenagers and adults and use different techniques according to the individual patient.' ),

			cil_home_heading( 'Plastibell and Circumplast circumcision', 3 ),
			cil_home_paragraph( 'For babies and younger children, we commonly use the Plastibell or Circumplast ring method where clinically suitable.' ),
			cil_home_paragraph( 'A correctly sized plastic ring is positioned over the head of the penis (glans) and underneath the foreskin. The foreskin is secured around the ring, allowing the unwanted foreskin to separate naturally as the area heals.' ),
			cil_home_paragraph( 'One advantage of this technique is that stitches are not required. The plastic ring remains in place after leaving the clinic and usually separates and falls off naturally during the healing process. If it does not fall off naturally, we book a ring removal follow up at no extra cost and this usually takes a few seconds to remove.' ),
			cil_home_paragraph( 'Parents receive detailed aftercare instructions explaining how to care for their baby or child while the ring is in place, what to expect during normal healing and when to contact us for advice. We also provide follow-up support throughout the healing period.' ),
			cil_home_learn_more( 'Learn more about baby circumcision →', '/babies/' ),

			cil_home_heading( 'Forceps-guided circumcision', 3 ),
			cil_home_paragraph( 'For older children, teenagers and adults, we commonly use a forceps-guided circumcision technique with thermal cautery.' ),
			cil_home_paragraph( 'The foreskin is carefully positioned to determine the appropriate amount to remove. A specialist surgical forceps is used to guide the circumcision and protect the underlying structures, while thermal cautery helps control bleeding during the procedure.' ),
			cil_home_paragraph( "Depending on the patient's age, anatomy and the wound following circumcision, the skin may be closed using dissolvable stitches, medical skin glue, a combination of both, or occasionally neither." ),
			cil_home_paragraph( 'Patients receive detailed aftercare instructions explaining how to care for the wound, what to expect during healing and when they can gradually return to normal activities.' ),
			cil_home_learn_more( 'Learn more about child circumcision →', '/children/' ),
			cil_home_learn_more( 'Learn more about adult circumcision →', '/adults/' ),

			cil_home_heading( "Why don't we use the same circumcision method for everyone?", 3 ),
			cil_home_paragraph( 'Circumcision should be tailored to the individual rather than using the same technique for every patient.' ),
			cil_home_paragraph( "A method suitable for a young baby may not be appropriate for an older child or adult. Before deciding which technique to use, we consider the patient's age, penile anatomy, foreskin development, reason for circumcision and any medical condition affecting the foreskin or penis." ),
			cil_home_paragraph( 'This is particularly important when treating conditions such as phimosis or BXO (lichen sclerosis), where scarring or a tight band of foreskin may affect how the circumcision needs to be performed. Additional consideration may also be required for patients with a buried penis or those seeking re-circumcision or revision surgery.' ),
			cil_home_paragraph( 'Every patient is assessed before the procedure so that we can select the method most appropriate for their individual circumstances and explain what to expect from the procedure, aftercare and recovery.' ),
		),
		'innerHTML'    => '',
		'innerContent' => array(),
	),
);

$methods_group                 = &$methods_inner[1];
$methods_group['innerContent'] = array( '<div class="wp-block-group body-text">' );
foreach ( $methods_group['innerBlocks'] as $_i ) {
	$methods_group['innerContent'][] = null;
}
$methods_group['innerContent'][] = '</div>';

$methods_section = cil_section_block(
	array(
		'size'   => 'section',
		'band'   => 'bg-warm',
		'wrap'   => 'wrap-narrow',
		'anchor' => 'circumcision-methods',
	),
	$methods_inner
);

$marker_start = '<!-- wp:cil/home-additional-sections -->';
$marker_end   = '<!-- /wp:cil/home-additional-sections -->';

$new_markup  = $marker_start . "\n";
$new_markup .= cil_serialize_blocks( array( $why_section, $medical_section, $methods_section ) );
$new_markup .= $marker_end;

$page = cil_home_resolve_page();
if ( ! $page ) {
	fwrite( STDERR, "Could not resolve Homepage page\n" );
	exit( 1 );
}

$content = $page->post_content;

// Idempotent: remove previous insertion if re-run.
if ( false !== strpos( $content, $marker_start ) && false !== strpos( $content, $marker_end ) ) {
	$content = preg_replace(
		'/' . preg_quote( $marker_start, '/' ) . '.*?' . preg_quote( $marker_end, '/' ) . '/s',
		'',
		$content
	);
	$content = preg_replace( "/\n{3,}/", "\n\n", $content );
}

// Insert after trust-strip if present; otherwise after groups; else after hero.
$anchors = array(
	'<!-- wp:cil/trust-strip /-->',
	'<!-- wp:cil/groups',
	'<!-- wp:cil/hero',
);

$inserted = false;
foreach ( $anchors as $needle ) {
	$pos = strpos( $content, $needle );
	if ( false === $pos ) {
		continue;
	}
	if ( '<!-- wp:cil/trust-strip /-->' === $needle ) {
		$insert_at = $pos + strlen( $needle );
		$content   = substr( $content, 0, $insert_at ) . "\n\n" . $new_markup . "\n" . substr( $content, $insert_at );
		$inserted  = true;
		break;
	}
	// For opening self-closing or attribute blocks, find end of that block comment line.
	$line_end = strpos( $content, '-->', $pos );
	if ( false === $line_end ) {
		continue;
	}
	$insert_at = $line_end + 3;
	$content   = substr( $content, 0, $insert_at ) . "\n\n" . $new_markup . "\n" . substr( $content, $insert_at );
	$inserted  = true;
	break;
}

if ( ! $inserted ) {
	$content = $content . "\n\n" . $new_markup . "\n";
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

echo "Updated page ID={$page->ID} slug={$page->post_name}\n";
echo "home_url={$base}\n";

// Export fixture for Vercel destination URLs.
$manifest    = cil_content_load_manifest();
$replace_to  = ( ! is_wp_error( $manifest ) && ! empty( $manifest['replace_to'] ) ) ? $manifest['replace_to'] : 'https://circumcision-london-wp.vercel.app';
$replace_from = untrailingslashit( home_url() );

$fixture = cil_content_export_slug( $page->post_name === 'home' ? 'home' : $page->post_name, $replace_from, $replace_to );
if ( is_wp_error( $fixture ) ) {
	// Front page slug may differ; try 'home' explicitly.
	$fixture = cil_content_export_slug( 'home', $replace_from, $replace_to );
}
if ( is_wp_error( $fixture ) ) {
	fwrite( STDERR, 'Export failed: ' . $fixture->get_error_message() . "\n" );
	exit( 1 );
}

$path = cil_content_write_fixture( 'home', $fixture );
if ( is_wp_error( $path ) ) {
	fwrite( STDERR, $path->get_error_message() . "\n" );
	exit( 1 );
}

echo "Wrote fixture {$path}\n";
echo "source_sha256={$fixture['source_sha256']}\n";
echo "bytes=" . strlen( $fixture['content'] ) . "\n";
echo "OK\n";
