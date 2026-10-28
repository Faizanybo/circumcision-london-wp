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
			'eyebrow'     => 'Last updated 28 October 2026',
			'h1'          => 'Privacy notice',
			'lede'        => 'This Privacy Notice explains what information we collect, how we use and protect it, and your rights under UK data-protection law.',
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
		cil_html_to_prose_blocks( $html )
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

	$html = '<p>At Circumcision in London and Beverley Clinic, we take the privacy and confidentiality of our patients and their families seriously.</p>
  <p>We understand that medical information is sensitive, particularly when it relates to children or includes intimate clinical photographs and videos.</p>
  <h2 class="display d-2">About us</h2>
  <p><strong>Data controller:</strong> LMCS Limited, trading as Beverley Clinic and Circumcision in London<br>'
  . esc_html( $clinic['address']['street'] ) . ', ' . esc_html( $clinic['address']['locality'] ) . ', ' . esc_html( $clinic['address']['postcode'] ) . '<br>'
  . 'Telephone: <a href="' . esc_url( $clinic['phone']['href'] ) . '">' . esc_html( $clinic['phone']['display'] ) . '</a><br>'
  . 'WhatsApp: <a href="' . esc_url( $clinic['whatsapp']['href'] ) . '" rel="noopener noreferrer" target="_blank">' . esc_html( $clinic['whatsapp']['display'] ) . '</a><br>'
  . 'Email: <a href="mailto:' . esc_attr( $clinic['email'] ) . '">' . esc_html( $clinic['email'] ) . '</a><br>'
  . 'Website: <a href="https://www.circumcisioninlondon.co.uk">www.circumcisioninlondon.co.uk</a></p>
  <h2 class="display d-2">Information we collect</h2>
  <p>We collect information necessary to arrange appointments, assess patients, provide circumcision treatment and offer appropriate aftercare.</p>
  <p>This may include:</p>
  <ul>
    <li>Patient and parent or guardian names, dates of birth and contact details.</li>
    <li>Medical history, medications, allergies and relevant health conditions.</li>
    <li>Appointment information, consent forms and procedure records.</li>
    <li>Clinical examination findings and aftercare notes.</li>
    <li>Clinical photographs and videos taken before circumcision, during healing and after healing.</li>
    <li>Messages, photographs and videos received through WhatsApp or email.</li>
    <li>Payment and administrative information.</li>
  </ul>
  <p>We only collect information relevant to providing our services or meeting our legal and professional responsibilities.</p>
  <h2 class="display d-2">How we use your information</h2>
  <p>We use personal information to:</p>
  <ul>
    <li>Respond to enquiries and manage appointments.</li>
    <li>Assess patients and provide circumcision treatment.</li>
    <li>Maintain accurate medical records.</li>
    <li>Monitor healing and provide aftercare advice.</li>
    <li>Communicate with patients, parents and guardians.</li>
    <li>Manage payments and clinic administration.</li>
    <li>Meet legal, regulatory and safeguarding obligations.</li>
  </ul>
  <p>We do not sell patient information or share it with third parties for their own marketing purposes.</p>
  <h2 class="display d-2">Clinical photographs and videos</h2>
  <p>As part of our clinical care, we may take photographs or videos of the penis and surrounding anatomy before circumcision, during healing and after healing.</p>
  <p>These recordings help us document anatomy, assess healing, monitor concerns and maintain accurate medical records.</p>
  <p>We explain the purpose of clinical photography and obtain appropriate permission before taking recordings.</p>
  <p>For children, we follow applicable requirements concerning parental responsibility, consent, the child\'s understanding and safeguarding.</p>
  <p>Clinical photographs and videos are treated as confidential medical information.</p>
  <p>We do not use identifiable patient photographs or videos for advertising, social media, website content or unrelated teaching without separate, appropriate permission.</p>
  <h2 class="display d-2">How we store clinical photographs and videos</h2>
  <p>Photographs and videos taken at the clinic are generally recorded using a clinic mobile phone and uploaded to Cliniko, our electronic patient-records system.</p>
  <p>Patients and parents may also send photographs or videos by WhatsApp or email, particularly when asking questions about healing.</p>
  <p>Relevant images and messages are added to the patient\'s Cliniko record.</p>
  <p>Sometimes an image or video must be temporarily saved on a device before it can be uploaded to Cliniko.</p>
  <p>We aim to minimise unnecessary copies and securely remove temporary files when they are no longer needed.</p>
  <h2 class="display d-2">WhatsApp and clinical reviews</h2>
  <p>Our reception team may receive photographs, videos or questions through the clinic\'s WhatsApp account.</p>
  <p>Where clinical advice is needed, relevant information may be forwarded to a private WhatsApp group used by our authorised doctors, including on their personal mobile phones.</p>
  <p>This allows our practitioners to review healing, assess concerns and provide appropriate advice.</p>
  <p>Once the information has been reviewed and added to Cliniko, the photographs and videos are deleted from the doctors\' WhatsApp group using WhatsApp\'s Delete for Everyone function.</p>
  <p>Automatic saving of WhatsApp photographs and videos to phone galleries is disabled on our clinic and practitioners\' phones.</p>
  <p>We also aim to remove any unnecessary copies temporarily saved on devices.</p>
  <p>Deleting a WhatsApp message does not necessarily remove copies that have already been downloaded, backed up or otherwise saved.</p>
  <p>Only authorised staff and practitioners should access patient information, and they are required to maintain confidentiality.</p>
  <p>Although we take steps to protect communications, WhatsApp and ordinary email may carry privacy and security risks.</p>
  <h2 class="display d-2">Sharing information with your GP</h2>
  <p>We do not routinely contact or send information directly to a patient\'s GP following circumcision.</p>
  <p>Where appropriate, we provide the patient or their parent or guardian with a letter about the procedure, which they may choose to give to their GP.</p>
  <p>In certain circumstances, we may need to share relevant information directly with another healthcare professional, hospital or appropriate authority where necessary for patient safety, safeguarding or another lawful reason.</p>
  <h2 class="display d-2">Children\'s medical information</h2>
  <p>We provide circumcision services for babies, children and teenagers and take particular care when handling their personal information.</p>
  <p>Their records may include medical histories, consent documentation, examination findings, procedure notes, photographs, videos and aftercare correspondence.</p>
  <p>We consider the child\'s age, understanding, confidentiality rights and the responsibilities of parents or guardians when collecting, using or disclosing information.</p>
  <h2 class="display d-2">Who can access your information?</h2>
  <p>Patient information may be accessed by authorised practitioners, reception staff and other personnel who need it to provide treatment, arrange appointments or manage clinical records.</p>
  <p>We use trusted service providers, including Cliniko, to help manage patient records and clinic operations.</p>
  <p>We may also use providers for website hosting, appointment booking, payments, email and messaging.</p>
  <p>Where necessary, we share information with these providers under appropriate data-protection arrangements.</p>
  <p>Some providers may process information outside the UK. Where this happens, we use applicable legal safeguards for international data transfers.</p>
  <h2 class="display d-2">How we protect your information</h2>
  <p>We take the security and confidentiality of patient information seriously, particularly sensitive medical records, clinical photographs and videos.</p>
  <p>We use the following security measures to help protect patient information:</p>
  <ul>
    <li>Clinic mobile phones and practitioners\' personal mobile phones are protected using Face ID.</li>
    <li>Our electronic patient-records system, Cliniko, is password-protected and uses two-factor authentication (2FA).</li>
    <li>Access to patient records is restricted to authorised staff and practitioners.</li>
    <li>Automatic saving of WhatsApp photographs and videos to phone galleries is disabled.</li>
    <li>Clinical photographs and videos are uploaded to Cliniko as part of the patient\'s medical record.</li>
    <li>Where photographs or videos are shared in our authorised doctors\' WhatsApp group for clinical review, they are deleted using WhatsApp\'s "Delete for Everyone" function after review and upload to Cliniko.</li>
    <li>Any temporary copies saved on devices are removed when no longer required.</li>
  </ul>
  <p>All staff and practitioners are expected to maintain patient confidentiality and handle personal information securely.</p>
  <p>Although we take appropriate precautions, no electronic communication or storage system can be guaranteed to be completely secure.</p>
  <h2 class="display d-2">How long we keep your information</h2>
  <p>We retain patient medical records in accordance with our records-retention policy, taking account of NHS England\'s Records Management Code of Practice and applicable legal and professional requirements.</p>
  <p>Our standard retention periods are:</p>
  <ul>
    <li><strong>Adult patients:</strong> At least 8 years after the conclusion of treatment.</li>
    <li><strong>Children:</strong> Until their 25th birthday.</li>
    <li><strong>Children who were aged 17 when treatment ended:</strong> Until their 26th birthday.</li>
  </ul>
  <p>Clinical photographs and videos retained as part of the medical record are subject to the same retention periods.</p>
  <p>Records may be kept longer where necessary for ongoing care, complaints, legal proceedings, safeguarding or other legitimate reasons.</p>
  <p>Once the relevant retention period has ended, records are reviewed and securely deleted or destroyed when they are no longer required.</p>
  <p>Temporary photographs and videos held outside Cliniko are removed when no longer necessary for clinical review or transfer.</p>
  <h2 class="display d-2">Our legal basis for using information</h2>
  <p>We process personal information in accordance with the UK General Data Protection Regulation (UK GDPR) and the Data Protection Act 2018.</p>
  <p>Depending on the purpose, our lawful basis under Article 6 may include providing contracted services, complying with legal obligations, legitimate interests or consent where required.</p>
  <p>For medical information, which is classified as special category data, we generally rely on Article 9(2)(h), relating to the provision and management of healthcare, together with applicable UK legal and confidentiality requirements.</p>
  <p>Where consent is required for a separate activity, such as optional promotional use of photographs, we seek that permission separately.</p>
  <h2 class="display d-2">Your rights</h2>
  <p>Under UK data-protection law, you may have the right to:</p>
  <ul>
    <li>Request access to your personal information.</li>
    <li>Ask us to correct inaccurate information.</li>
    <li>Request deletion of information in certain circumstances.</li>
    <li>Request restrictions on how information is used.</li>
    <li>Object to certain types of processing.</li>
    <li>Request data portability where applicable.</li>
    <li>Withdraw consent where consent is the legal basis for processing.</li>
  </ul>
  <p>Some rights are subject to legal limitations, particularly where medical records must be retained.</p>
  <p>To make a request, please contact the clinic using the details above.</p>
  <p>We normally respond within one calendar month, subject to any lawful extension.</p>
  <h2 class="display d-2">Website cookies and enquiries</h2>
  <p>When you send an enquiry from this website, the form asks for your name, telephone number, the patient\'s date of birth and any message you choose to write. Sending the form opens your email program so the message reaches the clinic. This website does not store that enquiry in its own database, and we do not add enquiries to a mailing list.</p>
  <p>This website does not set advertising cookies and does not embed social-media trackers. There is no separate cookie policy and no cookie-settings control. Images and fonts are stored with the website.</p>
  <p>The clinic map is a Google Maps frame. It loads when you reach that part of a page, and Google may set its own cookies then. Online booking uses Cliniko in a separate frame on the booking page, and Cliniko may set its own cookies there. This website does not load an analytics service of its own.</p>
  <h2 class="display d-2">Changes to this privacy notice</h2>
  <p>We may update this notice to reflect changes in our services, systems or legal requirements.</p>
  <p>The latest version will be available on our website.</p>
  <h2 class="display d-2">Questions or complaints</h2>
  <p>If you have any questions or concerns about how we handle your personal information, please contact Beverley Clinic directly. We welcome the opportunity to discuss and resolve any concerns promptly.</p>
  <p>LMCS Limited, trading as Beverley Clinic and Circumcision in London<br>'
  . esc_html( $clinic['address']['street'] ) . ', ' . esc_html( $clinic['address']['locality'] ) . ', ' . esc_html( $clinic['address']['postcode'] ) . '<br>'
  . 'Telephone: <a href="' . esc_url( $clinic['phone']['href'] ) . '">' . esc_html( $clinic['phone']['display'] ) . '</a><br>'
  . 'Email: <a href="mailto:' . esc_attr( $clinic['email'] ) . '">' . esc_html( $clinic['email'] ) . '</a></p>
  <p>You also have the right to raise a complaint with the Information Commissioner\'s Office (ICO), the UK\'s independent data-protection regulator, at <a href="https://www.ico.org.uk" rel="noopener" target="_blank">www.ico.org.uk</a>.</p>';

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
