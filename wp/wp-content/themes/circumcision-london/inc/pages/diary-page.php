<?php
/**
 * Circumcision Diary page (/circumcision-diary/).
 *
 * Text: the patient diary published on the clinic's current website
 * (circumcisioninlondon.co.uk/circumcision-diary), verbatim, in its original
 * day-by-day order. Seeds the page on first deploy like the other managed pages;
 * after that the page is edited in Gutenberg and kept in sync by its fixture.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Catalog entry for the diary page.
 *
 * Title and description are proposed values (not in Adults.docx); flagged for approval.
 *
 * @return array<string, array<string, mixed>>
 */
function cil_diary_pages() {
	return array(
		'circumcision-diary' => array(
			'slug'        => 'circumcision-diary',
			'path'        => '/circumcision-diary',
			'name'        => 'Circumcision Diary',
			'title'       => 'Circumcision Diary | An Adult Patient\'s Account | Beverley Clinic',
			'description' => 'One adult patient\'s day-by-day diary of his re-circumcision and recovery, shared with his permission. A personal account, not medical advice.',
			'eyebrow'     => 'Patient account',
			'h1'          => 'Circumcision Diary',
			'lede'        => 'The account below was sent to us by one of our patients who had a re-circumcision done at our clinic and has agreed for his diary to be added on to our website. Some names have been changed to just the initials to protect the privacy of the individuals.',
			'crumbs'      => array(
				array(
					'label' => 'Adults',
					'href'  => cil_path_url( '/adults' ),
				),
				array(
					'label' => 'Circumcision Diary',
					'href'  => '',
				),
			),
			'faqs'        => array(),
		),
	);
}

/**
 * Diary body in order: [type, text] rows (h2, h3, p, list).
 *
 * @return array<int, array{0:string,1:mixed}>
 */
function cil_diary_entries() {
	return array(
		array( 'h2', 'My re-circumcision' ),
		array( 'p', 'I had been concerned for some time that my circumcision in December 1982 was not totally satisfactory. It was not until I researched the internet, having just installed broadband, in late January that I was able to ascertain the problem and discover sources and methods to improve the situation. Following my wife and I return from holiday on 1st March, I contacted 3 clinics in the London area where the necessary procedure could be carried out under local anaesthetic. The 3 were:-' ),
		array( 'list', array( 'The Circumcision Centre – based in Luton.', 'Circumcision in London – based in North London.', 'London Circumcision Clinic – based in East London.' ) ),
		array( 'p', 'There were others as well including Harley Street and Hurlingham which I rejected. I emailed the 3 chosen, all replied and I gave the date of 17th March. Only 1, Circumcision in London, was able to undertake the procedure on that date. I then looked up their website and was very satisfied with all the information available including the testimonials. My only concern, if that is not too strong a word, was that there was no testimonial from a male of my age – 74 years. I spoke to Dr Samir Al-Ali, the resident owner, on 8th March, and confirmed details of date, time, preparation and price - £600. I felt very relieved that all the arrangements were finished.' ),
		array( 'h3', 'Day 1' ),
		array( 'p', 'I found my way easily on the underground to the clinic at 78, Beverley Drive, Queensbury for my 2pm appointment where I was met, in the reception, by Dr Al-Ali and introduced to his assistant Mohamed. There was a consent form to complete – agreeing to my circumcision. I paid over the £600. I was led past a small waiting room into the operating room – all very clinical, well organised – both wore surgical gowns. There was a TV set and toys for the younger children. All very homely. I removed all my clothing except my socks and shirt and lay on the operating table. I then felt very vulnerable!! I had shaved my genitals and my penis looked like a plucked chicken!! It was then that Dr Ali was able to see my circumcision and my reason for some cosmetic adjustment. He explained that it had not been a very neat job and this could complicate the procedure to make the necessary corrections. He took photographs of my penis for his records. All this was explained in detail with a marker pen to identify the proposed realignment, to which I agreed. I found all this explanation very reassuring and Dr Ali’s approach gave me total confidence. Mohamed cleansed the whole area with surgical spray. By now the time was 2:15pm and I was then given the local anaesthetic – an injection in the base of my penis. It was then necessary to prepare the penis for cutting by surrounding it with surgical material and applying a tourniquet to the base to control the blood flow. After a while the whole penis was numb, and the procedure started using manual excising (free hand). Dr Ali and I chatted all the time whilst he worked assisted by Mohamed. Our subjects ranged from his life and extensive medical career in Iraq, his speciality as a plastic surgeon, his time in the UK with qualifications in Edinburgh and his English language, and my background. He was interested as to why I had the circumcision in 1982. At around 2:55pm, he advised that all the cutting was finished and it was now the time to stitch – this would take longer than usual because of the lack of underskin as a result of the previous circumcision. Both he and Mohamed worked meticulously as a team throughout on either side of the operating table – Mohamed is also an Iraqi but does not have the same excellent command of English. The stitching took over 40minutes and then the need to pad the penis to control any bleeding and swelling. In order to prevent slippage, Dr Ali placed a stitch on the pad so holding it together. By 3:50pm the procedure was finished. At no time had I felt any pain or discomfort. There were some prostatic droplets from the penis which Mohamed dried with tissue. He then helped me to dress – suggesting I wore 2 pairs of tight briefs to keep the bandaged penis erect and close to my body. Very good advice. I was given 2 paracetemol to dampen any subsequent pain although I felt nothing during the procedure. By now it was 4pm – I had been at the clinic for 2 hours – time had passed very quickly thanks to the relaxing surroundings and the very friendly approach by Dr Ali and Mohamed. Before departure, Dr Ali gave me a sheet containing practical information regarding aftercare, all very informative. There was no discomfort walking back to the underground and I was soon on the train back to the local station where I had left the car. Driving was no problem. Before going to bed, I took another 2 paracetemol (as advised) and had an excellent night’s sleep with no problem with any erection – I continued to wear the 2 pairs of pants in addition to pyjama trousers.' ),
		array( 'h3', 'Day 2' ),
		array( 'p', 'No problem this morning. Up before 7am and after coffee with in town, I did some clearing up in the courtyard. After lunch I drove over to withmy wife to visit her sister, no discomfort. The taped bandage on the penis is holding up well, thanks to the stitch, with urination no problem. I took 2 paracetemol before going to sleep just in case. I woke up in the morning with an erection – it was not uncomfortable thanks to the wearing of the underpants, no pain felt.' ),
		array( 'h3', 'Day 3' ),
		array( 'p', 'No problem this morning. There is no evidence of any blood seeping through the bandaging. We drove into the city in the morning for shopping and in the afternoon I completed the audit of the local church accounts which were collected late. There was other paper work to do. The bandaged penis is holding up well. Wore underpants again in addition to pyjamas.' ),
		array( 'h3', 'Day 4' ),
		array( 'p', 'I had another erection in the night but no pain. The bandage is holding up well. Went shopping in town and then did some gardening although it is misty, light rain but still warm. Watched 6 Nations Rugby Union in the afternoon. I felt so comfortable and as I am usually a very quick healer, decided to have a bath as directed on the post operation programme – adding salt to the bath water. The dressing, after soaking for 30 minutes, came off cleanly and there was very little blood. No swelling so I was quite relieved. Too early to see the handiwork. It was a relief not to have my penis bandaged. Decided to carry on wearing the tighter pants.' ),
		array( 'h3', 'Day 5' ),
		array( 'p', 'An excellent night’s rest. No problem even when there was an erection. No pain or bleeding. Still wearing the tighter underwear. Able to lead normal activities. Went to 09:30am church service – we had neighbours into lunch. Took the rubbish up to the dump in the car then had another bath added salt. Decided not to wear the tight pants today.' ),
		array( 'h3', 'Day 6' ),
		array( 'p', 'Slept just in pyjamas and had an erection but no pain. Some prostatic fluid seepage but no swelling or soreness though there is a slight bruising in the groin around the testicles. Ida and I are going away today for 4 days as part of her birthday treat.' ),
		array( 'h3', 'Day 7' ),
		array( 'p', 'No new problems. All is healing well. The stitches are still obvious. Having a shower every day whilst we are away as there is no bath in the hotel.' ),
		array( 'h3', 'Day 8' ),
		array( 'p', 'Still very comfortable even when I experienced a hard and long erection in the night.' ),
		array( 'h3', 'Day 9' ),
		array( 'p', 'Despite a fair amount of walking, no problems.' ),
		array( 'h3', 'Day 10' ),
		array( 'p', 'All very comfortable. Back at home and had a long soak in the bath. Stitches still in place.' ),
		array( 'h3', 'Day 11' ),
		array( 'p', 'The enjoyment of my own bed must have brought on a long erection over night.' ),
		array( 'h3', 'Day 12' ),
		array( 'p', 'All still very comfortable. Emailed Dr Samir suggesting a follow up visit on Tuesday 27th April but this not possible in his reply.' ),
		array( 'h3', 'Day 13' ),
		array( 'p', 'A couple of stitches have come out and the new cut edge is very even. Much better than before. A great psychological improvement. Continue to bathe my penis in salt water.' ),
		array( 'h3', 'Day 14' ),
		array( 'p', 'Stitches on the top are out at the site of the fraenulum. Had another erection in the night. No discomfort. Went to the gym for the first time since the procedure. No problems. Rearranged my follow-up meeting with Dr Al Ali for 1:30pm on Saturday 22nd May.' ),
		array( 'h3', 'Day 15' ),
		array( 'p', 'Several erections in the night. I think my penis has been renewed!! Still a few stitches remaining.' ),
		array( 'h3', 'Day 16' ),
		array( 'p', 'All very comfortable. Bathed the penis in salty water. Worked in the Abbey Garden in the afternoon. Then a bath – all the stitches have gone.' ),
		array( 'h3', 'Day 17' ),
		array( 'p', 'My penis was erected for a long time and this seemed to cause some uncomfort around the new cuts. Instead of soaking in salt water, I applied medical powder when I washed this morning.' ),
		array( 'h3', 'Day 18' ),
		array( 'p', 'All seems OK. Healing just about complete. My penis certainly looks better!! Worked in the garden this morning.' ),
		array( 'h3', 'Day 19' ),
		array( 'p', 'The last day of my diary for this procedure. All is well I am very pleased with the result. My pubic and genital hair is growing again. I certainly look and feel like a new man. It has been a great psychological leap over a 28year barrier. All thanks to Dr Samir Al Ali and his assistant colleague Mohamed. I shall look forward to meeting them again on 22nd May.' ),
		array( 'h3', 'POST SCRIPT' ),
		array( 'p', 'Two months later, I called on Dr Al-Ali by appointment, and we discussed the aftermath of the procedure. He examined his handiwork took photos and was able to compare before and after. We were both very satisfied with the outcome. My erectile dysfunction had been cured by this re-circumcision correction. I gave Dr Al-Ali and Mohamed a tea towel each of local county scenes as a thank you.' ),
		array( 'p', 'The End' ),
	);
}

/**
 * Plain text block helpers for the diary (core blocks, editable in Gutenberg).
 *
 * @param string $kind h2|h3|p.
 * @param string $text Text.
 * @return array<string, mixed>
 */
function cil_diary_text_block( $kind, $text ) {
	if ( 'p' === $kind ) {
		$html = '<p>' . esc_html( $text ) . '</p>';
		return array(
			'blockName'    => 'core/paragraph',
			'attrs'        => array(),
			'innerBlocks'  => array(),
			'innerHTML'    => $html,
			'innerContent' => array( $html ),
		);
	}
	$level = ( 'h2' === $kind ) ? 2 : 3;
	$class = ( 2 === $level ) ? 'wp-block-heading display d-2' : 'wp-block-heading display d-3';
	$html  = '<h' . $level . ' class="' . $class . '">' . esc_html( $text ) . '</h' . $level . '>';
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
 * Block markup for the diary page.
 *
 * @param string $slug Page slug.
 * @return string
 */
function cil_diary_page_blocks( $slug ) {
	$pages = cil_diary_pages();
	if ( empty( $pages[ $slug ] ) ) {
		return '';
	}
	$page = $pages[ $slug ];

	$body = array();
	foreach ( cil_diary_entries() as $row ) {
		if ( 'list' === $row[0] ) {
			$inner   = array();
			$content = array( '<ul class="wp-block-list">' );
			foreach ( $row[1] as $item ) {
				$li        = '<li>' . esc_html( $item ) . '</li>';
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
			$body[]    = array(
				'blockName'    => 'core/list',
				'attrs'        => array(),
				'innerBlocks'  => $inner,
				'innerHTML'    => '',
				'innerContent' => $content,
			);
			continue;
		}
		$body[] = cil_diary_text_block( $row[0], $row[1] );
	}

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
		cil_section_block(
			array(
				'size' => 'section-sm',
				'band' => '',
				'wrap' => 'wrap',
			),
			array(
				// Proposed editorial note (not from the source); flagged for client approval.
				cil_dyn_block(
					'cil/callout',
					array(
						'title'  => '',
						'body'   => '<p>' . esc_html( 'This is one patient\'s personal account of his re-circumcision and recovery. Individual experiences vary, and it is not medical advice. Please follow the aftercare instructions we give you.' ) . '</p>',
						'urgent' => false,
						'level'  => 2,
					)
				),
			)
		),
		cil_section_block(
			array(
				'size'   => 'section',
				'band'   => '',
				'wrap'   => 'wrap',
				'anchor' => 'diary',
			),
			array(
				array(
					'blockName'    => 'core/group',
					'attrs'        => array( 'className' => 'body-text cil-diary' ),
					'innerBlocks'  => $body,
					'innerHTML'    => '',
					'innerContent' => array_merge( array( '<div class="wp-block-group body-text cil-diary">' ), array_fill( 0, count( $body ), null ), array( '</div>' ) ),
				),
			)
		),
		cil_section_block(
			array(
				'size' => 'section-sm',
				'band' => 'bg-card edge',
				'wrap' => 'wrap',
			),
			array(
				array(
					'blockName'    => 'core/buttons',
					'attrs'        => array(),
					'innerBlocks'  => array(
						array(
							'blockName'    => 'core/button',
							'attrs'        => array(),
							'innerBlocks'  => array(),
							'innerHTML'    => '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( cil_path_url( '/adults' ) ) . '">' . esc_html( 'Back to adult circumcision' ) . '</a></div>',
							'innerContent' => array( '<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url( cil_path_url( '/adults' ) ) . '">' . esc_html( 'Back to adult circumcision' ) . '</a></div>' ),
						),
					),
					'innerHTML'    => '',
					'innerContent' => array( '<div class="wp-block-buttons">', null, '</div>' ),
				),
			)
		),
	);
	return cil_serialize_blocks( $blocks );
}
