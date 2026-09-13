<?php
/**
 * Privacy notice and accessibility pages from prototype src/pages/misc.js.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prototype data for /privacy-notice and /accessibility.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_legal_pages() {
	return array(
		'privacy-notice' => array(
			'slug'        => 'privacy-notice',
			'path'        => '/privacy-notice',
			'name'        => 'Privacy notice',
			'title'       => 'Privacy Notice | Circumcision Clinic in London',
			'description' => 'How the clinic collects, uses and protects your personal and health information, how long we keep it, and the rights you have over it under UK GDPR.',
			'eyebrow'     => 'Last updated 5 September 2026',
			'h1'          => 'Privacy notice',
			'lede'        => 'What we collect, why, how long we keep it and what you can ask us to do about it. Written to be read, not scrolled past.',
			'crumbs'      => array(
				array(
					'label' => 'Privacy notice',
					'href'  => '',
				),
			),
		),
		'accessibility'  => array(
			'slug'        => 'accessibility',
			'path'        => '/accessibility',
			'name'        => 'Accessibility',
			'title'       => 'Accessibility | Circumcision Clinic in London',
			'description' => 'How this website is built to be usable: keyboard navigation, contrast, tap targets and reduced motion, and how to tell us if something does not work for you.',
			'eyebrow'     => 'Last reviewed 5 September 2026',
			'h1'          => 'Accessibility',
			'lede'        => 'Accessibility was part of the brief here, not a checklist at the end. This is what that means in practice, and how to tell us where we fell short.',
			'crumbs'      => array(
				array(
					'label' => 'Accessibility',
					'href'  => '',
				),
			),
		),
	);
}

/**
 * Block markup for a legal page.
 *
 * @param string $slug Page slug.
 * @return string
 */
function cil_legal_page_blocks( $slug ) {
	if ( 'privacy-notice' === $slug ) {
		return cil_privacy_page_blocks();
	}
	if ( 'accessibility' === $slug ) {
		return cil_accessibility_page_blocks();
	}
	return '';
}

/**
 * Shared page-head + narrow body-text section used by both legal pages.
 *
 * @param array<string, mixed> $page Page data.
 * @param string               $html Prototype body HTML.
 * @return string
 */
function cil_legal_page_from_html( $page, $html ) {
	$blocks   = array();
	$blocks[] = cil_dyn_block(
		'cil/page-head',
		array(
			'eyebrow' => $page['eyebrow'],
			'title'   => $page['h1'],
			'lede'    => $page['lede'],
			'crumbs'  => $page['crumbs'],
		)
	);
	$blocks[] = cil_section_block(
		array(
			'size' => 'section-sm',
			'band' => '',
			'wrap' => 'wrap-narrow',
		),
		array(
			cil_html_block( '<div class="body-text" data-reveal>' . $html . '</div>' ),
		)
	);

	return cil_serialize_blocks( $blocks );
}

/**
 * Gutenberg markup for /privacy-notice.
 *
 * @return string
 */
function cil_privacy_page_blocks() {
	$page   = cil_legal_pages()['privacy-notice'];
	$clinic = cil_clinic();

	$html = '<h2 class="display d-2" style="margin:30px 0 12px">Who we are</h2>
  <p>' . esc_html( $clinic['legal_name'] ) . ', trading as ' . esc_html( $clinic['name'] ) . ', ' . esc_html( $clinic['address']['street'] ) . ', ' . esc_html( $clinic['address']['locality'] ) . ',
  ' . esc_html( $clinic['address']['postcode'] ) . ', is the data controller. Reach us on <a href="' . esc_url( $clinic['phone']['href'] ) . '">' . esc_html( $clinic['phone']['display'] ) . '</a>
  or at <a href="mailto:' . esc_attr( $clinic['email'] ) . '">' . esc_html( $clinic['email'] ) . '</a>.</p>

  <h2 class="display d-2" style="margin:30px 0 12px">What we collect</h2>
  <p>If you use a form on this website we collect the name, telephone number, who the appointment is for and any
  message you write. That is everything the website itself collects about you.</p>
  <p>If you or your child become a patient, we additionally hold the clinical record required for care: medical
  history, the consent given, notes from the consultation and procedure, and correspondence with your GP. That is
  special category health data and is treated accordingly.</p>

  <h2 class="display d-2" style="margin:30px 0 12px">Why we are allowed to hold it</h2>
  <p>For enquiries, because you asked us to contact you and we have a legitimate interest in replying. For clinical
  records, because processing is necessary for the provision of health care under Article 9(2)(h) of the UK GDPR,
  and because we are required by law to keep adequate records.</p>

  <h2 class="display d-2" style="margin:30px 0 12px">Who we share it with</h2>
  <p>Your GP, where you have agreed to a letter. Anyone involved in onward care, where necessary. Our regulators,
  where they are legally entitled to see records. We do not sell your data, we do not share it for advertising, and
  we do not add enquiries to a marketing list.</p>

  <h2 class="display d-2" style="margin:30px 0 12px">How long we keep it</h2>
  <p>Website enquiries that do not become appointments are deleted within twelve months. Clinical records are kept
  in line with the NHS Records Management Code of Practice: generally eight years after treatment concludes for an
  adult, and until a child\'s twenty-fifth birthday where the patient was a child.</p>

  <h2 class="display d-2" style="margin:30px 0 12px">Cookies and measurement</h2>
  <p>This site sets no advertising cookies and embeds no social media trackers. It loads no interactive map until you
  choose to open one, and it hosts its own images and fonts instead of calling a third-party service for them. Where
  analytics are in use they are limited to understanding which pages are used and which enquiry routes people take,
  and you will be asked before any non-essential cookie is set.</p>

  <h2 class="display d-2" style="margin:30px 0 12px">Your rights</h2>
  <p>You can ask for a copy of what we hold, ask us to correct anything inaccurate, ask us to delete information we
  no longer need, object to particular processing, and ask us to restrict what we do with it while a question is
  resolved. Clinical records generally cannot be deleted on request because we are required to keep them. Write to
  <a href="mailto:' . esc_attr( $clinic['email'] ) . '">' . esc_html( $clinic['email'] ) . '</a> and we will respond within one month.</p>
  <p>If you are unhappy with our response you can complain to the Information Commissioner\'s Office at
  <a href="https://ico.org.uk" rel="noopener" target="_blank">ico.org.uk</a>.</p>';

	return cil_legal_page_from_html( $page, $html );
}

/**
 * Gutenberg markup for /accessibility.
 *
 * @return string
 */
function cil_accessibility_page_blocks() {
	$page   = cil_legal_pages()['accessibility'];
	$clinic = cil_clinic();

	$html = '<h2 class="display d-2" style="margin:30px 0 12px">What we have done</h2>
  <ul>
    <li>One heading structure per page that reflects the content, with a single H1 describing that page.</li>
    <li>Body text meets or exceeds a 4.5:1 contrast ratio against its background.</li>
    <li>Every interactive control is at least 44 pixels tall, and the fixed contact bar on small screens sits in its
      own reserved strip so it can never cover a button, a form field or a phone number.</li>
    <li>The whole site works by keyboard, with a visible focus outline and a skip link to the main content.</li>
    <li>Icon links carry text labels. There are no links whose only description is an icon.</li>
    <li>Images carry alt text that describes the image; decorative images carry empty alt attributes.</li>
    <li>Animation is a short fade on scroll and nothing else, switched off entirely if your device asks for reduced
      motion. Nothing moves, flashes or auto-plays.</li>
    <li>No pop-ups, interstitials or overlays anywhere on this website.</li>
    <li>The layout responds to the width of your window, not to the device you are using, so zooming or resizing
      gives you the layout that fits.</li>
    <li>Body text is set at 17px, a little larger than usual, because people read this site while anxious.</li>
  </ul>

  <h2 class="display d-2" style="margin:30px 0 12px">Where we fall short</h2>
  <p>We link out to Google Maps for directions. That is deliberate, because an embedded map is slow and sets
  third-party cookies before you have agreed to them, but it does mean the map itself is outside our control.</p>
  <p>We have not commissioned a formal WCAG 2.2 audit. What is above reflects care taken during the build; it is not
  a certified conformance statement, and we would rather say so.</p>

  <h2 class="display d-2" style="margin:30px 0 12px">Tell us</h2>
  <p>If something here does not work for you, please tell us on <a href="' . esc_url( $clinic['phone']['href'] ) . '">' . esc_html( $clinic['phone']['display'] ) . '</a>
  or at <a href="mailto:' . esc_attr( $clinic['email'] ) . '">' . esc_html( $clinic['email'] ) . '</a>. If you cannot use the site at all, call and we will do
  whatever you needed to do on it for you over the phone.</p>';

	return cil_legal_page_from_html( $page, $html );
}
