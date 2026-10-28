<?php
/**
 * Aftercare videos: landing cards, the three age-group video pages
 * (0–2y, 3–13y, 14+ yrs and adults) and the Arabic, Turkish and Farsi
 * 0–2y pages, mirroring the client's previous Wix site.
 *
 * Patient-facing wording is copied from the Wix pages with the same URLs:
 *   /babies-toddlers-circumcision-videos, /children-circumcision-videos,
 *   /adult-circumcision-videos, /arabic-babies-toddlers-circumcision-videos,
 *   /turkish-babies-toddlers-circumcision-videos, /farsi-babies-toddlers-circumcision-videos
 *
 * Media: videos and aftercare-sheet PDFs live in the external media store and
 * are referenced by storage KEY only (e.g. aftercare/videos/consultation-0-2.mp4).
 * cil_media_url() (inc/media.php) turns a key into a public URL using the base
 * URL in content-fixtures/media/media-config.json. Nothing here depends on Wix.
 * Poster thumbnails and checklist images are small theme assets.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Storage key of an aftercare video.
 *
 * @param string $key Key in cil_aftercare_videos().
 * @return string
 */
function cil_aftercare_video_key( $key ) {
	$videos = cil_aftercare_videos();
	return isset( $videos[ $key ] ) ? $videos[ $key ]['file'] : '';
}

/**
 * Public URL of an aftercare video (empty until the media store is configured).
 *
 * @param string $key Key in cil_aftercare_videos().
 * @return string
 */
function cil_aftercare_video_url( $key ) {
	return cil_media_url( cil_aftercare_video_key( $key ) );
}

/**
 * Public URL of an aftercare instructions sheet (empty until configured).
 *
 * @param string $key Key in cil_aftercare_docs().
 * @return string
 */
function cil_aftercare_doc_url( $key ) {
	$docs = cil_aftercare_docs();
	return isset( $docs[ $key ] ) ? cil_media_url( $docs[ $key ] ) : '';
}

/**
 * Theme URL of a poster or checklist image.
 *
 * @param string $relative Path under assets/images/aftercare/.
 * @return string
 */
function cil_aftercare_image_url( $relative ) {
	return cil_asset( 'images/aftercare/' . ltrim( $relative, '/' ) );
}

/**
 * Aftercare instructions sheets: key => storage key.
 *
 * The Wix 0–2y and 3–13y pages linked two copies of the same forceps sheet
 * (identical SHA-256); one stored file serves both links.
 *
 * @return array<string, string>
 */
function cil_aftercare_docs() {
	return array(
		'ring'           => 'aftercare/documents/ring-method-aftercare-sheet.pdf',
		'forceps-child'  => 'aftercare/documents/forceps-guided-children-aftercare-sheet.pdf',
		'forceps-adult'  => 'aftercare/documents/forceps-guided-adults-aftercare-sheet.pdf',
	);
}

/**
 * Every aftercare video once: key => storage file + length.
 *
 * @return array<string, array<string, string>>
 */
function cil_aftercare_videos() {
	$v = static function ( $file, $duration ) {
		return array(
			'file'     => 'aftercare/videos/' . $file . '.mp4',
			'duration' => $duration,
		);
	};
	return array(
		'preparation-0-2'            => $v( 'preparation-0-2', '1:46' ),
		'consultation-0-2'           => $v( 'consultation-0-2', '3:09' ),
		'ring-method-aftercare'      => $v( 'ring-method-aftercare', '4:54' ),
		'vaseline-nappy-patient'     => $v( 'vaseline-nappy-patient', '0:49' ),
		'vaseline-nappy-mannequin'   => $v( 'vaseline-nappy-mannequin', '2:26' ),
		'burp-baby'                  => $v( 'burp-baby', '0:17' ),
		'clean-penis'                => $v( 'clean-penis', '0:54' ),
		'skin-adhesions'             => $v( 'skin-adhesions', '0:58' ),
		'ring-bleeding-doctors'      => $v( 'ring-bleeding-doctors', '4:31' ),
		'cut-ring-off'               => $v( 'cut-ring-off', '0:49' ),
		'forceps-aftercare-children' => $v( 'forceps-aftercare-children', '3:22' ),
		'forceps-bleeding-0-2'       => $v( 'forceps-bleeding-0-2', '0:52' ),
		'preparation-3-13'           => $v( 'preparation-3-13', '1:11' ),
		'consultation-3-13'          => $v( 'consultation-3-13', '2:25' ),
		'vaseline-underwear'         => $v( 'vaseline-underwear', '1:30' ),
		'stop-bleeding'              => $v( 'stop-bleeding', '1:04' ),
		'preparation-14-adults'      => $v( 'preparation-14-adults', '0:42' ),
		'consultation-14-adults'     => $v( 'consultation-14-adults', '3:22' ),
		'forceps-aftercare-adults'   => $v( 'forceps-aftercare-adults', '3:54' ),
		// Translated 0–2y videos.
		'ar-preparation'             => $v( 'arabic/preparation', '1:46' ),
		'ar-consultation'            => $v( 'arabic/consultation', '3:09' ),
		'ar-ring-method-aftercare'   => $v( 'arabic/ring-method-aftercare', '2:32' ),
		'ar-vaseline-nappy'          => $v( 'arabic/vaseline-nappy', '0:49' ),
		'ar-burp-baby'               => $v( 'arabic/burp-baby', '0:17' ),
		'ar-clean-penis'             => $v( 'arabic/clean-penis', '2:35' ),
		'tr-preparation'             => $v( 'turkish/preparation', '1:46' ),
		'tr-consultation'            => $v( 'turkish/consultation', '3:09' ),
		'tr-ring-method-aftercare'   => $v( 'turkish/ring-method-aftercare', '2:32' ),
		'tr-vaseline-nappy'          => $v( 'turkish/vaseline-nappy', '0:49' ),
		'tr-burp-baby'               => $v( 'turkish/burp-baby', '0:17' ),
		'tr-clean-penis'             => $v( 'turkish/clean-penis', '2:35' ),
		'fa-preparation'             => $v( 'farsi/preparation', '1:46' ),
		'fa-consultation'            => $v( 'farsi/consultation', '3:09' ),
		'fa-ring-method-aftercare'   => $v( 'farsi/ring-method-aftercare', '2:32' ),
		'fa-vaseline-nappy'          => $v( 'farsi/vaseline-nappy', '0:49' ),
		'fa-burp-baby'               => $v( 'farsi/burp-baby', '0:17' ),
		// The Wix Farsi page reuses the Arabic "clean the penis" video file.
		'fa-clean-penis'             => $v( 'arabic/clean-penis', '2:35' ),
	);
}

/**
 * One video-list item for the cil/video-grid block. Stores the storage key
 * (mediaKey), not a URL, so the content is identical in every environment.
 *
 * @param string $key         Key in cil_aftercare_videos().
 * @param string $title       Heading, copied from the Wix page.
 * @param string $description Optional note (simple HTML), copied from the Wix page.
 * @param string $poster      Poster file under assets/images/aftercare/posters/ (default: $key).
 * @return array<string, mixed>
 */
function cil_aftercare_item( $key, $title, $description = '', $poster = '' ) {
	$videos = cil_aftercare_videos();
	return array(
		'title'       => $title,
		'description' => $description,
		'duration'    => $videos[ $key ]['duration'],
		'mediaKey'    => $videos[ $key ]['file'],
		'poster'      => cil_aftercare_image_url( 'posters/' . ( $poster ? $poster : $key ) . '.jpg' ),
		'mp4'         => '',
		'posterId'    => 0,
		'mp4Id'       => 0,
	);
}

/**
 * Age-group cards for the Aftercare landing page and the "other age groups" strip.
 *
 * @return array<int, array<string, string>>
 */
function cil_aftercare_age_cards() {
	return array(
		array(
			'href'  => cil_path_url( '/babies-toddlers-circumcision-videos' ),
			'age'   => 'Babies and toddlers',
			'title' => '0–2y',
			'price' => '',
			'blurb' => '',
			'cta'   => 'Watch the 0–2y videos',
		),
		array(
			'href'  => cil_path_url( '/children-circumcision-videos' ),
			'age'   => 'Children',
			'title' => '3–13y',
			'price' => '',
			'blurb' => '',
			'cta'   => 'Watch the 3–13y videos',
		),
		array(
			'href'  => cil_path_url( '/adult-circumcision-videos' ),
			'age'   => 'Teenagers and adults',
			'title' => '14+ yrs and adults',
			'price' => '',
			'blurb' => '',
			'cta'   => 'Watch the 14+ yrs and adults videos',
		),
	);
}

/**
 * Translated 0–2y pages: language metadata and verbatim Wix content.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_aftercare_translations() {
	$en_intro = 'Below are a few videos which we would like you to watch. They include a consultation explaining what to expect on the day of circumcision, aftercare video for the ring method, how to change the nappy and an explanation of how to clean the penis after the ring comes off. Please note that every patient is different and whilst this explanation will apply to 99% of patients, if there is anything different which your son may need, we will advise you accordingly.';
	$en_note  = 'Please also note that this translation has been carried out using computer software and we cannot guarantee the accuracy of the translation, so please also refer to the main English videos.';
	$titles   = array( 'Preparation for circumcision', 'Consultation', 'ring method aftercare instructions', 'How to apply Vaseline to the Nappy', 'How to burp the baby', 'How to clean the penis after the ring comes off' );
	$videos   = array( 'preparation', 'consultation', 'ring-method-aftercare', 'vaseline-nappy', 'burp-baby', 'clean-penis' );

	return array(
		'arabic'  => array(
			'slug'        => 'arabic-babies-toddlers-circumcision-videos',
			'label'       => 'Arabic - عربي',
			'lang'        => 'ar',
			'dir'         => 'rtl',
			'prefix'      => 'ar',
			'title'       => 'Arabic | Babies + Toddlers Circumcision | UK Best Circumcision Clinic London',
			'description' => 'Arabic translation- The best and safest circumcisions for babies, and toddlers. Learn about circumcision for infants and toddlers, including our commitment to comfort and precision.  Babies and toddlers circumcision videos consultation. guide and aftercare instructions.',
			'h1'          => 'Arabic translation Babies and Toddlers (0-2 years old)',
			'h1_native'   => 'الترجمة العربية الرضع والأطفال الصغار الذين تتراوح أعمارهم بين 0 و2 سنة',
			'en_intro'    => $en_intro,
			'en_note'     => $en_note,
			'intro'       => 'فيما يلي بعض مقاطع الفيديو التي نود مشاهدتها. وتتضمن استشارة توضح ما يمكن توقعه في يوم الختان، وفيديو للعناية اللاحقة بطريقة الحلقة، وكيفية تغيير الحفاض، وشرح لكيفية تنظيف القضيب بعد نزع الحلقة. يرجى ملاحظة أن كل مريض يختلف عن الآخر، وبينما ينطبق هذا الشرح على 99% من المرضى، إذا كان هناك أي شيء مختلف قد يحتاجه ابنك، فسوف ننصحك وفقًا لذلك.',
			'note'        => 'يرجى أيضًا ملاحظة أن هذه الترجمة تم إجراؤها باستخدام برامج الكمبيوتر ولا يمكننا ضمان دقة الترجمة، لذا يرجى أيضًا الرجوع إلى مقاطع الفيديو الرئيسية باللغة الإنجليزية.',
			'titles'      => $titles,
			'native'      => array( 'التحضير للختان', 'استشارة', 'الرعاية بعد الختان', 'طريقة وضع الفازلين على الحفاض', 'كيفية تجشؤ الطفل', 'كيفية تنظيف القضيب بعد سقوط الحلقة' ),
			'videos'      => $videos,
		),
		'turkish' => array(
			'slug'        => 'turkish-babies-toddlers-circumcision-videos',
			'label'       => 'Turkish - Türkçe',
			'lang'        => 'tr',
			'dir'         => 'ltr',
			'prefix'      => 'tr',
			'title'       => 'Turkish | Babies and Toddlers | Best Circumcision Clinic in London',
			'description' => 'Turkish Translation- The best and safest circumcisions for babies, and toddlers. Learn about circumcision for infants and toddlers, including our commitment to comfort and precision.  Babies + toddlers circumcision videos of the consultation and aftercare instructions',
			'h1'          => 'Turkish translation Babies and Toddlers (0-2 years old)',
			'h1_native'   => 'Türkçe çeviri Bebekler ve Küçük Çocuklar (0-2 yaş)',
			'en_intro'    => $en_intro,
			'en_note'     => $en_note,
			'intro'       => 'İzlemenizi istediğimiz birkaç videoyu aşağıda bulabilirsiniz. Bunlar arasında sünnet gününde neler beklenmesi gerektiğini açıklayan bir konsültasyon, Plastibell (halka) yöntemi için bakım sonrası video, bebek bezinin nasıl değiştirileceği ve halka çıktıktan sonra penisin nasıl temizleneceğine dair bir açıklama yer alır. Her hastanın farklı olduğunu ve bu açıklamanın hastaların %99\'u için geçerli olacağını lütfen unutmayın, ancak oğlunuzun ihtiyaç duyabileceği farklı bir şey varsa size buna göre tavsiyelerde bulunacağız.',
			'note'        => 'Lütfen bu çevirinin bilgisayar yazılımı kullanılarak yapıldığını ve çevirinin doğruluğunu garanti edemediğimizi unutmayın; bu nedenle lütfen ana İngilizce videolara da bakın.',
			'titles'      => $titles,
			'native'      => array( 'Sünnet için hazırlık', 'Danışma', 'Halka yöntemi bakım sonrası talimatlar', 'Bebek Bezine Vazelin nasıl uygulanır?', 'Bebeğin geğirmesi nasıl yapılır', 'Halka çıktıktan sonra penis nasıl temizlenir' ),
			'videos'      => $videos,
		),
		'farsi'   => array(
			'slug'        => 'farsi-babies-toddlers-circumcision-videos',
			'label'       => 'Farsi - فارسی',
			'lang'        => 'fa',
			'dir'         => 'rtl',
			'prefix'      => 'fa',
			'title'       => 'Farsi | Babies and Toddlers | London’s Best Circumcision Clinic',
			'description' => 'Farsi translation- The best and safest circumcisions for babies, and toddlers. Learn about circumcision for infants and toddlers, including our commitment to comfort and precision. Babies + toddlers circumcision videos of the consultation and aftercare instructions',
			'h1'          => 'Farsi translation Babies and Toddlers (0-2 years old)',
			'h1_native'   => 'ترجمه فارسی نوزادان و کودکان نوپا (0-2 سال)',
			'en_intro'    => $en_intro,
			'en_note'     => $en_note,
			'intro'       => 'در زیر چند ویدیو وجود دارد که مایلیم آنها را تماشا کنید. آنها شامل مشاوره در مورد آنچه در روز ختنه باید انتظار داشته باشید، فیلم مراقبت بعد از روش (حلقه)، نحوه تعویض پوشک و توضیح نحوه تمیز کردن آلت تناسلی پس از جدا شدن حلقه را شامل می شود. لطفاً توجه داشته باشید که هر بیمار متفاوت است و در حالی که این توضیح برای 99٪ بیماران اعمال می شود، اگر فرزند شما به چیز دیگری نیاز داشته باشد، ما به شما توصیه می کنیم.',
			'note'        => 'همچنین توجه داشته باشید که این ترجمه با استفاده از نرم افزار کامپیوتری انجام شده است و ما نمی توانیم صحت ترجمه را تضمین کنیم، بنابراین لطفا به ویدیوهای اصلی انگلیسی نیز مراجعه کنید.',
			'titles'      => $titles,
			'native'      => array( 'آمادگی برای ختنه', 'مشاوره', 'مراقبت های بعدی دستورالعمل ها', 'نحوه زدن وازلین روی پوشک', 'نحوه آروغ زدن نوزاد', 'نحوه تمیز کردن آلت تناسلی بعد از جدا شدن حلقه' ),
			'videos'      => $videos,
		),
	);
}

/**
 * Managed-page catalog for the age-group and translated video pages.
 *
 * Titles and descriptions are the Wix SEO values for the same URLs.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_aftercare_video_pages() {
	$aftercare = cil_path_url( '/aftercare' );
	$babies    = cil_path_url( '/babies-toddlers-circumcision-videos' );
	$crumb     = static function ( $label, $mid = null ) use ( $aftercare ) {
		$crumbs = array(
			array(
				'label' => 'Aftercare',
				'href'  => $aftercare,
			),
		);
		if ( $mid ) {
			$crumbs[] = $mid;
		}
		$crumbs[] = array(
			'label' => $label,
			'href'  => '',
		);
		return $crumbs;
	};

	$pages = array(
		'babies-toddlers-circumcision-videos' => array(
			'slug'        => 'babies-toddlers-circumcision-videos',
			'name'        => '0–2y',
			'title'       => 'Babies + Toddlers Circumcision | UK Best Circumcision Clinic London',
			'description' => 'The best and safest circumcisions for babies, and toddlers. Learn about circumcision for infants and toddlers, including our commitment to comfort and precision. Babies + toddlers circumcision videos of the consultation and aftercare instructions',
			'eyebrow'     => 'Aftercare videos · 0–2y',
			'h1'          => 'Babies and Toddlers (0-2 years old)',
			'lede'        => 'Below are a few videos which we would like you to watch. They include a consultation explaining what to expect on the day of circumcision, aftercare video for the Plastibell/ Circumplast (ring) method, how to change the nappy and an explanation of how to clean the penis after the ring comes off. Please note that every patient is different and whilst this explanation will apply to most of patients, if there is anything different which your son may need, we will advise you accordingly. For example, we sometimes use the Forceps Guided (traditional) method instead of the ring and we have included the aftercare for this method on this page as well.',
			'crumbs'      => $crumb( '0–2y' ),
			'faqs'        => array(),
		),
		'children-circumcision-videos'        => array(
			'slug'        => 'children-circumcision-videos',
			'name'        => '3–13y',
			'title'       => 'Children Circumcision | Aftercare | Circumcision Clinic in London',
			'description' => 'Our experienced team ensures a safe, comfortable and efficient cirumcisions for children. Discover our approach to paediatric circumcision, including the latest techniques and care practices, to support your child’s health and well-being.Children circumcision consultation and aftercare instructions videos',
			'eyebrow'     => 'Aftercare videos · 3–13y',
			'h1'          => 'Children 3-13 years old',
			'lede'        => 'Below are a few videos which we would like you to watch. They include a consultation explaining what to expect on the day of circumcision, aftercare video for the Forceps guided (traditional) method, how to apply the Vaseline when wearing underwear and an explanation of buried penis which some patients may already have or develop in the future. Please note that every patient is different and whilst this explanation will apply to the majority of patients, if there is anything different which your son may need, we will advise you accordingly.',
			'crumbs'      => $crumb( '3–13y' ),
			'faqs'        => array(),
		),
		'adult-circumcision-videos'           => array(
			'slug'        => 'adult-circumcision-videos',
			'name'        => '14+ yrs and adults',
			'title'       => 'Adult Circumcision | Aftercare | Circumcision Clinic in London',
			'description' => 'Explore adult circumcision videos at London\'s best circumcision clinic. We offer expert insights into  circumcision procedures for adults. Our London Circumcision Clinic provides comprehensive, affordable, and painless options for adult circumcision. Watch our videos to learn more about the best practices and expert care in London.',
			'eyebrow'     => 'Aftercare videos · 14+ yrs and adults',
			'h1'          => '14+ years old and Adults',
			'lede'        => 'Below are a few videos which we would like you to watch. They include a consultation explaining what to expect on the day of circumcision, aftercare video for the Forceps guided (traditional) method and how to apply Vaseline when wearing underwear. Please note that every patient is different and whilst this explanation will apply to 99% of patients, if there is anything different, we will advise you accordingly.',
			'crumbs'      => $crumb( '14+ yrs and adults' ),
			'faqs'        => array(),
		),
	);

	foreach ( cil_aftercare_translations() as $t ) {
		$pages[ $t['slug'] ] = array(
			'slug'        => $t['slug'],
			'name'        => $t['label'],
			'title'       => $t['title'],
			'description' => $t['description'],
			'eyebrow'     => 'Aftercare videos · 0–2y · ' . $t['label'],
			'h1'          => $t['h1'],
			'lede'        => $t['en_intro'],
			'crumbs'      => $crumb(
				$t['label'],
				array(
					'label' => '0–2y',
					'href'  => $babies,
				)
			),
			'faqs'        => array(),
			'translation' => $t,
		);
	}

	return $pages;
}

/**
 * Plain paragraph block.
 *
 * @param string $html  Inner HTML (already escaped).
 * @param string $lang  Optional language code.
 * @param string $dir   Optional text direction.
 * @return array<string, mixed>
 */
function cil_aftercare_p( $html, $lang = '', $dir = '' ) {
	// lang/dir go on an inner span: core/paragraph does not save a lang
	// attribute on <p>, so putting it there would invalidate the block.
	if ( $lang || $dir ) {
		$html = '<span' . ( $lang ? ' lang="' . esc_attr( $lang ) . '"' : '' ) . ( $dir ? ' dir="' . esc_attr( $dir ) . '"' : '' ) . '>' . $html . '</span>';
	}
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
 * Body-text group wrapping inner blocks.
 *
 * @param array<int, array<string, mixed>> $inner Blocks.
 * @return array<string, mixed>
 */
function cil_aftercare_group( $inner ) {
	return array(
		'blockName'    => 'core/group',
		'attrs'        => array( 'className' => 'body-text' ),
		'innerBlocks'  => $inner,
		'innerHTML'    => '',
		'innerContent' => array_merge( array( '<div class="wp-block-group body-text">' ), array_fill( 0, count( $inner ), null ), array( '</div>' ) ),
	);
}

/**
 * Plain bulleted list.
 *
 * @param array<int, string> $items Item HTML (already escaped).
 * @return array<string, mixed>
 */
function cil_aftercare_list( $items ) {
	$inner   = array();
	$content = array( '<ul class="wp-block-list">' );
	foreach ( $items as $item ) {
		$li        = '<li>' . $item . '</li>';
		$inner[]   = array(
			'blockName'    => 'core/list-item',
			'attrs'        => array(),
			'innerBlocks'  => array(),
			'innerHTML'    => $li,
			'innerContent' => array( $li ),
		);
		$content[] = null;
	}
	$content[] = '</ul>';
	return array(
		'blockName'    => 'core/list',
		'attrs'        => array(),
		'innerBlocks'  => $inner,
		'innerHTML'    => '',
		'innerContent' => $content,
	);
}

/**
 * Checklist image (core/image, theme asset).
 *
 * @param string $file    File under assets/images/aftercare/.
 * @param string $alt     Alt text (transcribes the checklist).
 * @param string $caption Visible caption.
 * @return array<string, mixed>
 */
function cil_aftercare_checklist_image( $file, $alt, $caption ) {
	$html = '<figure class="wp-block-image size-large cil-aftercare-checklist"><img src="' . esc_url( cil_aftercare_image_url( $file ) ) . '" alt="' . esc_attr( $alt ) . '" width="1575" height="1275" loading="lazy" decoding="async"/><figcaption class="wp-element-caption">' . esc_html( $caption ) . '</figcaption></figure>';
	return array(
		'blockName'    => 'core/image',
		'attrs'        => array(
			'sizeSlug'        => 'large',
			'linkDestination' => 'none',
			'className'       => 'cil-aftercare-checklist',
		),
		'innerBlocks'  => array(),
		'innerHTML'    => $html,
		'innerContent' => array( $html ),
	);
}

/**
 * Video list (cil/video-grid in list layout, no section wrapper).
 *
 * @param array<int, array<string, mixed>> $items Items from cil_aftercare_item().
 * @return array<string, mixed>
 */
function cil_aftercare_video_list( $items ) {
	return cil_dyn_block(
		'cil/video-grid',
		array(
			'items'     => $items,
			'layout'    => 'list',
			'itemLevel' => 3,
			'showHead'  => false,
		)
	);
}

/**
 * Aftercare-sheet download button (media-store key, no URL in content).
 *
 * @param string $text    Link text.
 * @param string $doc_key Key in cil_aftercare_docs().
 * @return array<string, mixed>
 */
function cil_aftercare_doc_link( $text, $doc_key ) {
	$docs = cil_aftercare_docs();
	return cil_dyn_block(
		'cil/file-link',
		array(
			'text'     => $text,
			'mediaKey' => $docs[ $doc_key ],
		)
	);
}

/**
 * "Download our aftercare instructions sheet" section.
 *
 * @param array<int, array{0: string, 1: string}> $sheets [ label, doc key ] pairs.
 * @return array<string, mixed>
 */
function cil_aftercare_sheets_section( $sheets ) {
	$inner = array(
		cil_dyn_block(
			'cil/section-head',
			array(
				'eyebrow' => 'Aftercare sheets',
				'heading' => 'Download our aftercare instructions sheet',
				'lede'    => 'You will be given this aftercare sheet after the circumcision but you can also download it by clicking on the link below:',
				'display' => 'd-3',
			)
		),
	);
	foreach ( $sheets as $sheet ) {
		$inner[] = cil_aftercare_doc_link( $sheet[0], $sheet[1] );
	}
	return cil_section_block(
		array(
			'size'   => 'section-sm',
			'band'   => 'bg-card edge',
			'wrap'   => 'wrap',
			'anchor' => 'aftercare-sheets',
		),
		$inner
	);
}

/**
 * Videos section heading.
 *
 * @param string $eyebrow Eyebrow.
 * @param string $heading Heading.
 * @param string $lede    Optional lede.
 * @return array<string, mixed>
 */
function cil_aftercare_videos_head( $eyebrow, $heading, $lede = '' ) {
	$attrs = array(
		'eyebrow' => $eyebrow,
		'heading' => $heading,
		'display' => 'd-2',
	);
	if ( $lede ) {
		$attrs['lede'] = $lede;
	}
	return cil_dyn_block( 'cil/section-head', $attrs );
}

/**
 * Closing blocks shared by the age and translation pages.
 *
 * @return array<int, array<string, mixed>>
 */
function cil_aftercare_closing_blocks() {
	return array(
		cil_section_block(
			array(
				'size' => 'section',
				'band' => 'bg-warm',
				'wrap' => 'wrap',
			),
			array(
				cil_dyn_block(
					'cil/section-head',
					array(
						'eyebrow' => 'Aftercare',
						'heading' => 'Videos for other age groups',
						'display' => 'd-2',
					)
				),
				cil_dyn_block(
					'cil/group-cards',
					array(
						'level'  => 3,
						'banded' => false,
						'items'  => cil_aftercare_age_cards(),
					)
				),
			)
		),
		cil_dyn_block(
			'cil/cta-band',
			array(
				'title'      => 'Already had the procedure and something is worrying you?',
				'text'       => 'Call the clinic. Follow-up appointments during the healing period are included where needed.',
				'eyebrow'    => 'Talk to us',
				'ctaLabel'   => 'Book a consultation',
				'ctaUrl'     => cil_path_url( '/book' ),
				'phoneLabel' => 'Call 020 8951 3794',
				'phoneUrl'   => 'tel:+442089513794',
				'cardTitle'  => 'Would you rather write than speak?',
				'cardHtml'   => '<p style="margin-top:10px">A phone call asks you to be fluent and composed in the moment. <a href="https://wa.me/447886779958" rel="noopener" target="_blank" data-track="whatsapp-cta" style="color:var(--blue-deep)">WhatsApp</a> does not. You can take your time, translate, and forward the answer to whoever else in the family needs to see it.</p><p style="margin-top:12px">We reply during opening hours, and we are used to the questions people feel awkward asking out loud.</p>',
			)
		),
	);
}

/**
 * Blocks for one translated 0–2y page.
 *
 * @param array<string, mixed> $page Catalog entry with 'translation'.
 * @return array<int, array<string, mixed>>
 */
function cil_aftercare_translation_blocks( $page ) {
	$t      = $page['translation'];
	$native = '<span lang="' . esc_attr( $t['lang'] ) . '" dir="' . esc_attr( $t['dir'] ) . '">';
	$items  = array();
	foreach ( $t['videos'] as $i => $video ) {
		$items[] = cil_aftercare_item(
			$t['prefix'] . '-' . $video,
			$t['titles'][ $i ],
			$native . esc_html( $t['native'][ $i ] ) . '</span>',
			$t['prefix'] . '-' . $video
		);
	}
	return array(
		cil_section_block(
			array(
				'size' => 'section-sm',
				'band' => '',
				'wrap' => 'wrap',
			),
			array(
				cil_aftercare_group(
					array(
						cil_aftercare_p( '<strong>' . esc_html( $t['h1_native'] ) . '</strong>', $t['lang'], $t['dir'] ),
						cil_aftercare_p( esc_html( $t['en_note'] ) ),
						cil_aftercare_p( esc_html( $t['intro'] ), $t['lang'], $t['dir'] ),
						cil_aftercare_p( esc_html( $t['note'] ), $t['lang'], $t['dir'] ),
					)
				),
			)
		),
		cil_section_block(
			array(
				'size'   => 'section',
				'band'   => 'bg-card edge',
				'wrap'   => 'wrap',
				'anchor' => 'videos',
			),
			array(
				cil_aftercare_videos_head( 'Aftercare videos · 0–2y · ' . $t['label'], 'Videos for babies and toddlers' ),
				cil_aftercare_video_list( $items ),
			)
		),
	);
}

/**
 * Gutenberg markup for one aftercare video page.
 *
 * @param string $slug Page slug.
 * @return string
 */
function cil_aftercare_video_page_blocks( $slug ) {
	$pages = cil_aftercare_video_pages();
	if ( empty( $pages[ $slug ] ) ) {
		return '';
	}
	$page   = $pages[ $slug ];
	$blocks = array(
		cil_dyn_block(
			'cil/page-head',
			array(
				'eyebrow' => $page['eyebrow'],
				'title'   => $page['h1'],
				'lede'    => $page['lede'],
				'crumbs'  => $page['crumbs'],
			)
		),
	);

	if ( ! empty( $page['translation'] ) ) {
		$blocks = array_merge( $blocks, cil_aftercare_translation_blocks( $page ) );
	} elseif ( 'babies-toddlers-circumcision-videos' === $slug ) {
		$links = array();
		foreach ( cil_aftercare_translations() as $t ) {
			$links[] = '<a href="' . esc_url( cil_path_url( '/' . $t['slug'] ) ) . '" hreflang="' . esc_attr( $t['lang'] ) . '">' . esc_html( $t['label'] ) . '</a>';
		}
		$blocks[] = cil_section_block(
			array(
				'size' => 'section-sm',
				'band' => '',
				'wrap' => 'wrap',
			),
			array(
				cil_aftercare_group(
					array(
						cil_aftercare_p( 'We are adding this information in other languages by clicking the links below. Please note that these translations has been done with AI software and we cannot guarantee the accuracy of the translations, so please also refer to these main English videos.' ),
						cil_aftercare_list( $links ),
					)
				),
			)
		);
		$blocks[] = cil_aftercare_sheets_section(
			array(
				array( 'Ring method aftercare instructions sheet', 'ring' ),
				array( 'Forceps Guided (traditional) method aftercare instructions sheet - Only use this if we have advised you', 'forceps-child' ),
			)
		);
		$blocks[] = cil_section_block(
			array(
				'size'   => 'section',
				'band'   => '',
				'wrap'   => 'wrap',
				'anchor' => 'videos',
			),
			array(
				cil_aftercare_videos_head( 'Aftercare videos · 0–2y', 'Videos for babies and toddlers' ),
				cil_aftercare_video_list( array( cil_aftercare_item( 'preparation-0-2', 'Preparation for circumcision' ) ) ),
				cil_aftercare_checklist_image(
					'checklist-0-2.jpg',
					'Circumcision Checklist 0-2 years old: Fill out the consent form sent you by email. Bring IDs for your son, mother and father. Tight nappies. Baby wipes. Pacifier/ dummy - even if he does not take one, please buy one for the circumcision. Bottle of milk - if your son takes one. Vaseline - 250ml.',
					'Circumcision checklist, 0-2 years old'
				),
				cil_aftercare_video_list(
					array(
						cil_aftercare_item( 'consultation-0-2', 'Consultation' ),
						cil_aftercare_item( 'ring-method-aftercare', 'Plastibell/ Circumplast (ring) method aftercare instructions' ),
						cil_aftercare_item( 'vaseline-nappy-patient', 'How to apply Vaseline to the nappy (real patient example).', 'Apply the nappy tightly' ),
						cil_aftercare_item( 'vaseline-nappy-mannequin', 'How to apply Vaseline to the nappy (mannequin example).', 'Apply the nappy tightly' ),
						cil_aftercare_item( 'burp-baby', 'How to burp the baby.', 'Please do this on a flat surface, not your legs (your legs can press on the penis).' ),
						cil_aftercare_item( 'clean-penis', 'How to clean the penis after the ring comes off (buried penis and normal penis)' ),
						cil_aftercare_item( 'skin-adhesions', 'How to separate skin adhesions if they occur' ),
						cil_aftercare_item( 'ring-bleeding-doctors', 'How to stop post-operative bleeding following Plastibell/ Circumplast (ring) circumcision.', 'Only for Doctors to watch and attempt' ),
						cil_aftercare_item( 'cut-ring-off', 'How to cut the ring off if it does not come off within 14 days.', 'This should be done at our clinic. However, if you live far and cannot attend, show this to the doctors in A&amp;E who should be able to cut the ring off. <strong>DO NOT attempt this at home.</strong>' ),
					)
				),
			)
		);
		$blocks[] = cil_section_block(
			array(
				'size'   => 'section',
				'band'   => 'bg-card edge',
				'wrap'   => 'wrap',
				'anchor' => 'forceps-guided-method',
			),
			array(
				cil_aftercare_videos_head( 'Forceps guided (traditional) method', 'If we have told you we used the forceps guided method' ),
				cil_aftercare_group(
					array(
						cil_aftercare_p( 'The videos below relate to the forceps guided (traditional) method. Only watch these videos if you have been told this is the method we have used for your son.' ),
						cil_aftercare_p( 'Some of the above videos on how to change the nappy and burp your son apply to both methods.' ),
						cil_aftercare_p( 'You can download the aftercare sheet for this method by clicking below.' ),
					)
				),
				cil_aftercare_doc_link( 'Forceps guided (traditional) method - children - aftercare sheet', 'forceps-child' ),
				cil_aftercare_video_list(
					array(
						cil_aftercare_item( 'forceps-aftercare-children', 'Forceps guided (traditional) method aftercare instructions.', 'This is not the common method for this age. Please only watch this if you have been told by us.' ),
						cil_aftercare_item( 'forceps-bleeding-0-2', 'How to stop bleeding with Forceps guided (traditional) method.', 'This is not the common method for this age. Please only watch this if you have been told by us.' ),
					)
				),
			)
		);
	} elseif ( 'children-circumcision-videos' === $slug ) {
		$docs     = cil_aftercare_docs();
		$blocks[] = cil_aftercare_sheets_section(
			array(
				array( 'Forceps guided (children) aftercare instructions sheet', 'forceps-child' ),
			)
		);
		$blocks[] = cil_section_block(
			array(
				'size'   => 'section',
				'band'   => '',
				'wrap'   => 'wrap',
				'anchor' => 'videos',
			),
			array(
				cil_aftercare_videos_head( 'Aftercare videos · 3–13y', 'Videos for children aged 3 to 13' ),
				cil_aftercare_video_list( array( cil_aftercare_item( 'preparation-3-13', 'Preparation for circumcision' ) ) ),
				cil_aftercare_checklist_image(
					'checklist-3-13.jpg',
					'Circumcision Checklist 3-13 years old: Fill out the consent form sent you by email. Bring IDs for your son, mother and father. Tight underwear. Children above 10 years old - please shave clean any pubic hair. Calpol depending on the age. Vaseline - 250ml.',
					'Circumcision checklist, 3-13 years old'
				),
				cil_aftercare_video_list(
					array(
						cil_aftercare_item( 'consultation-3-13', 'Consultation' ),
						cil_aftercare_item( 'forceps-aftercare-children', 'Children Forceps guided (traditional) method aftercare instructions' ),
						cil_aftercare_item( 'vaseline-underwear', 'How to apply vaseline when putting on underwear' ),
						cil_aftercare_item( 'clean-penis', 'How to clean the penis (buried penis or normal penis)' ),
						cil_aftercare_item( 'skin-adhesions', 'How to separate skin adhesions if they occur' ),
						cil_aftercare_item( 'stop-bleeding', 'How to stop bleeding' ),
						cil_aftercare_item( 'ring-method-aftercare', 'Plastibell/ Circumplast (ring method) aftercare instructions.', 'This is not the common method for this age. please only watch this if you have been told by us. You can download the aftercare sheet by clicking <a href="media:' . esc_attr( $docs['ring'] ) . '" rel="noopener" target="_blank">here.</a>' ),
					)
				),
			)
		);
	} elseif ( 'adult-circumcision-videos' === $slug ) {
		$blocks[] = cil_aftercare_sheets_section(
			array(
				array( 'Forceps guided (adults) aftercare instructions sheet', 'forceps-adult' ),
			)
		);
		$blocks[] = cil_section_block(
			array(
				'size'   => 'section',
				'band'   => '',
				'wrap'   => 'wrap',
				'anchor' => 'videos',
			),
			array(
				cil_aftercare_videos_head( 'Aftercare videos · 14+ yrs and adults', 'Videos for teenagers and adults' ),
				cil_aftercare_video_list( array( cil_aftercare_item( 'preparation-14-adults', 'Preparation for circumcision' ) ) ),
				cil_aftercare_checklist_image(
					'checklist-14-adults.jpg',
					'Circumcision Checklist 14+ years old & Adults: Fill out the consent form sent you by email. Bring IDs for patient (under 18 years old also need mother’s and father’s ID). Tight underwear. Shave clean the pubic hair. Paracetamol. Vaseline - 250ml.',
					'Circumcision checklist, 14+ years old & adults'
				),
				cil_aftercare_video_list(
					array(
						cil_aftercare_item( 'consultation-14-adults', 'Consultation' ),
						cil_aftercare_item( 'forceps-aftercare-adults', 'Adult Forceps guided (traditional) method aftercare instructions' ),
						cil_aftercare_item( 'vaseline-underwear', 'How to apply vaseline when wearing underwear' ),
						cil_aftercare_item( 'stop-bleeding', 'How to stop bleeding' ),
					)
				),
			)
		);
		$blocks[] = cil_section_block(
			array(
				'size' => 'section',
				'band' => 'bg-card edge',
				'wrap' => 'wrap',
			),
			array(
				cil_dyn_block(
					'cil/section-head',
					array(
						'eyebrow' => 'Patients',
						'heading' => 'In their own words',
						'display' => 'd-2',
					)
				),
				cil_dyn_block(
					'cil/quotes-grid',
					array(
						'quotes'  => array(
							array(
								'name'    => 'Sam',
								'context' => '36 years old',
								'quote'   => 'I am a health professional who has, and continues to work with many health professionals from all over the world. I can confidently say Dr Al-Ali and his very efficient assistant are easily some of the best practitioners in the business. I underwent the circumcision yesterday, I have to admit I was anxious before the procedure. Dr Al-Ali and his assistant took time to explain the procedure answer all my questions and allay my anxiety. They told me it was going to be painless. He administered the local anaesthesia, and he asked me I could feel my penis, I couldn’t, all the sensation was gone. He started the procedure and it was totally painless, I even fell asleep at some point. The procedure was successful, I drove home thereafter. Even after the local anaesthesia had worn off, it was totally painless. As I write this comment, I am in day 1 after the operation, I am pain free, I will be going out to do some shopping in a minute. Dr Al-Ali and your very good assistant keep up the good.',
							),
							array(
								'name'    => 'Giles',
								'context' => '32 years old',
								'quote'   => 'Cannot recommend this place enough. I was certainly concerned about having the procedure done as any guy my age would be. The sheer clinical efficiency and professional standards of Dr Al-ali and his staff put me completely at ease. Post procedure of course is no walk in the park, there is tenderness that one would expect with this sort of operation. But the dressing and instructions from the team made this time so much easier. I didn\'t miss a day of work thanks to this, nor did I feel I couldn\'t be active through the day (although I did miss football for a 4 weeks, but this was to be expected!) All in all 5 months later there is no scaring and no worries, would 100% recommend this practice.',
							),
						),
						'perPage' => 0,
					)
				),
			)
		);
	}

	return cil_serialize_blocks( array_merge( $blocks, cil_aftercare_closing_blocks() ) );
}

/**
 * Section inserted at the top of the existing /aftercare/ page: the three
 * age-group cards (as on the Wix "Explanation videos" page).
 *
 * @return array<string, mixed>
 */
function cil_aftercare_landing_cards_section() {
	return cil_section_block(
		array(
			'size'   => 'section',
			'band'   => 'bg-card edge',
			'wrap'   => 'wrap',
			'anchor' => 'aftercare-videos',
		),
		array(
			cil_dyn_block(
				'cil/section-head',
				array(
					'eyebrow' => 'Aftercare videos',
					'heading' => 'Explanation videos',
					'lede'    => 'Please click on the ages below to watch a collection of videos relevant to that particular age group',
					'display' => 'd-2',
				)
			),
			cil_dyn_block(
				'cil/group-cards',
				array(
					'level'  => 3,
					'banded' => false,
					'items'  => cil_aftercare_age_cards(),
				)
			),
		)
	);
}
