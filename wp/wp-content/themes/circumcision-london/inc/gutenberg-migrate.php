<?php
/**
 * Convert seeded Custom HTML into editable CIL / core blocks.
 *
 * Used by the one-shot LocalWP CLI and by page seeders. Does not run on
 * frontend requests. Does not write the database unless a CLI/admin caller
 * asks it to.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Native heading with an optional className (prototype display sizes).
 *
 * @param string $text       Heading inner HTML.
 * @param int    $level      2–6.
 * @param string $class_name Extra class.
 * @return array<string, mixed>
 */
function cil_core_heading_class( $text, $level = 2, $class_name = '' ) {
	$level = max( 2, min( 6, (int) $level ) );
	$tag   = 'h' . $level;
	$class = trim( 'wp-block-heading ' . $class_name );
	$html  = '<' . $tag . ' class="' . esc_attr( $class ) . '">' . $text . '</' . $tag . '>';
	$attrs = array( 'level' => $level );
	if ( $class_name ) {
		$attrs['className'] = $class_name;
	}
	return array(
		'blockName'    => 'core/heading',
		'attrs'        => $attrs,
		'innerBlocks'  => array(),
		'innerHTML'    => $html,
		'innerContent' => array( $html ),
	);
}

/**
 * cil/rich-html block.
 *
 * @param string $html Markup.
 * @return array<string, mixed>
 */
function cil_rich_html_block( $html ) {
	return cil_dyn_block(
		'cil/rich-html',
		array(
			'html' => $html,
		)
	);
}

/**
 * cil/info-cards block.
 *
 * @param array<int, array<string, mixed>> $items   Cards.
 * @param string                           $columns g-2|g-3|g-4.
 * @param int                              $level   Heading level.
 * @return array<string, mixed>
 */
function cil_info_cards_block( $items, $columns = 'g-3', $level = 3 ) {
	return cil_dyn_block(
		'cil/info-cards',
		array(
			'items'        => array_values( $items ),
			'columns'      => $columns,
			'headingLevel' => (int) $level,
		)
	);
}

/**
 * First class token matching a prefix.
 *
 * @param string $class Class attribute.
 * @param string $prefix Prefix.
 * @return string
 */
function cil_class_token( $class, $prefix ) {
	foreach ( preg_split( '/\s+/', trim( $class ) ) as $token ) {
		if ( 0 === strpos( $token, $prefix ) ) {
			return $token;
		}
	}
	return '';
}

/**
 * Inner HTML of a DOM node.
 *
 * @param DOMNode $node Node.
 * @return string
 */
function cil_dom_inner_html( $node ) {
	if ( ! $node instanceof DOMNode ) {
		return '';
	}
	$html = '';
	foreach ( $node->childNodes as $child ) {
		$html .= $node->ownerDocument->saveHTML( $child );
	}
	return $html;
}

/**
 * Load a fragment into a DOM body.
 *
 * @param string $html Fragment.
 * @return DOMDocument|null
 */
function cil_dom_fragment( $html ) {
	if ( ! class_exists( 'DOMDocument' ) ) {
		return null;
	}
	$previous = libxml_use_internal_errors( true );
	$dom      = new DOMDocument();
	$wrapped  = '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body>' . $html . '</body></html>';
	$ok       = $dom->loadHTML( $wrapped, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );
	return $ok ? $dom : null;
}

/**
 * Convert a card-grid Custom HTML blob into cil/info-cards, or null.
 *
 * @param string $html Markup.
 * @return array<string, mixed>|null
 */
function cil_html_to_info_cards( $html ) {
	if ( ! preg_match( '/class="[^"]*\bgrid\b[^"]*\b(g-[234])\b/', $html, $cols ) ) {
		return null;
	}
	if ( false === strpos( $html, 'class="card' ) && false === strpos( $html, "class='card" ) ) {
		return null;
	}

	$dom = cil_dom_fragment( $html );
	if ( ! $dom ) {
		return null;
	}

	$xpath = new DOMXPath( $dom );
	$cards = $xpath->query( '//*[contains(concat(" ", normalize-space(@class), " "), " card ")]' );
	if ( ! $cards || ! $cards->length ) {
		return null;
	}

	$items = array();
	foreach ( $cards as $card ) {
		$item = array(
			'eyebrow'   => '',
			'title'     => '',
			'html'      => '',
			'ctaLabel'  => '',
			'ctaUrl'    => '',
			'ctaClass'  => 'btn',
			'cta2Label' => '',
			'cta2Url'   => '',
			'cta2Class' => 'btn btn-ghost',
		);

		$eyebrow = $xpath->query( './/*[contains(concat(" ", normalize-space(@class), " "), " eyebrow ")]', $card )->item( 0 );
		if ( $eyebrow ) {
			$item['eyebrow'] = trim( $eyebrow->textContent );
			$eyebrow->parentNode->removeChild( $eyebrow );
		}

		$heading = $xpath->query( './/h1|.//h2|.//h3', $card )->item( 0 );
		if ( $heading ) {
			$item['title'] = trim( $heading->textContent );
			$heading->parentNode->removeChild( $heading );
		}

		$buttons = $xpath->query( './/a[contains(concat(" ", normalize-space(@class), " "), " btn ")]', $card );
		$btn_i   = 0;
		if ( $buttons ) {
			foreach ( $buttons as $btn ) {
				$label = trim( $btn->textContent );
				$href  = $btn->getAttribute( 'href' );
				$class = trim( $btn->getAttribute( 'class' ) );
				if ( 0 === $btn_i ) {
					$item['ctaLabel'] = $label;
					$item['ctaUrl']   = $href;
					$item['ctaClass'] = $class ? $class : 'btn';
				} elseif ( 1 === $btn_i ) {
					$item['cta2Label'] = $label;
					$item['cta2Url']   = $href;
					$item['cta2Class'] = $class ? $class : 'btn btn-ghost';
				}
				$btn_i++;
				if ( $btn->parentNode && 'div' === strtolower( $btn->parentNode->nodeName ) && false !== strpos( (string) $btn->parentNode->getAttribute( 'class' ), 'btn-row' ) ) {
					$row = $btn->parentNode;
					$row->removeChild( $btn );
					if ( ! $row->hasChildNodes() && $row->parentNode ) {
						$row->parentNode->removeChild( $row );
					}
				} else {
					$btn->parentNode->removeChild( $btn );
				}
			}
		}

		$item['html'] = trim( cil_dom_inner_html( $card ) );
		$items[]      = $item;
	}

	if ( ! $items ) {
		return null;
	}

	return cil_info_cards_block( $items, $cols[1], 2 );
}

/**
 * Convert a section-head HTML blob into cil/section-head, or null.
 *
 * @param string $html Markup.
 * @return array<string, mixed>|null
 */
function cil_html_to_section_head( $html ) {
	if ( false === strpos( $html, 'section-head' ) ) {
		return null;
	}
	$dom = cil_dom_fragment( $html );
	if ( ! $dom ) {
		return null;
	}
	$xpath   = new DOMXPath( $dom );
	$heading = $xpath->query( '//h1|//h2|//h3' )->item( 0 );
	$eyebrow = $xpath->query( '//*[contains(concat(" ", normalize-space(@class), " "), " eyebrow ")]' )->item( 0 );
	$lede    = $xpath->query( '//*[contains(concat(" ", normalize-space(@class), " "), " lede ")]' )->item( 0 );
	if ( ! $heading && ! $lede && ! $eyebrow ) {
		return null;
	}
	$display = 'd-2';
	if ( $heading ) {
		$found = cil_class_token( $heading->getAttribute( 'class' ), 'd-' );
		if ( $found ) {
			$display = $found;
		}
	}
	return cil_dyn_block(
		'cil/section-head',
		array(
			'eyebrow' => $eyebrow ? trim( $eyebrow->textContent ) : '',
			'heading' => $heading ? trim( $heading->textContent ) : '',
			'lede'    => $lede ? trim( $lede->textContent ) : '',
			'display' => $display,
		)
	);
}

/**
 * Convert a callout HTML blob into cil/callout, or null.
 *
 * @param string $html Markup.
 * @return array<string, mixed>|null
 */
function cil_html_to_callout( $html ) {
	if ( ! preg_match( '/class="[^"]*\bcallout\b/', $html ) ) {
		return null;
	}
	if ( false !== strpos( $html, '<form' ) ) {
		return null;
	}
	$dom = cil_dom_fragment( $html );
	if ( ! $dom ) {
		return null;
	}
	$xpath   = new DOMXPath( $dom );
	$heading = $xpath->query( '//h1|//h2|//h3' )->item( 0 );
	$title   = $heading ? trim( $heading->textContent ) : '';
	if ( $heading && $heading->parentNode ) {
		$heading->parentNode->removeChild( $heading );
	}
	$body = trim( cil_dom_inner_html( $xpath->query( '//body' )->item( 0 ) ) );
	$body = preg_replace( '/^<div[^>]*class="[^"]*callout[^"]*"[^>]*>/i', '', $body );
	$body = preg_replace( '/<\/div>\s*$/i', '', $body );
	return cil_dyn_block(
		'cil/callout',
		array(
			'title'  => $title,
			'body'   => trim( $body ),
			'urgent' => false !== strpos( $html, 'urgent' ),
			'level'  => 2,
		)
	);
}

/**
 * Convert a callback form card into cil/callback-card, or null.
 *
 * @param string $html Markup.
 * @return array<string, mixed>|null
 */
function cil_html_to_callback_card( $html ) {
	if ( false === strpos( $html, 'class="form-grid' ) && false === strpos( $html, "data-form=" ) ) {
		return null;
	}
	$dom = cil_dom_fragment( $html );
	if ( ! $dom ) {
		return null;
	}
	$xpath   = new DOMXPath( $dom );
	$form    = $xpath->query( '//form' )->item( 0 );
	$heading = $xpath->query( '//h1|//h2|//h3' )->item( 0 );
	$eyebrow = $xpath->query( '//*[contains(concat(" ", normalize-space(@class), " "), " eyebrow ")]' )->item( 0 );
	$form_id = $form ? $form->getAttribute( 'id' ) : 'callback';
	$subject = $form ? $form->getAttribute( 'data-form' ) : 'general enquiry';
	return cil_dyn_block(
		'cil/callback-card',
		array(
			'eyebrow' => $eyebrow ? trim( $eyebrow->textContent ) : 'Request a call back',
			'title'   => $heading ? trim( $heading->textContent ) : 'Ask us first',
			'formId'  => $form_id ? $form_id : 'callback',
			'subject' => $subject ? $subject : 'general enquiry',
			'urgent'  => false,
		)
	);
}

/**
 * Convert prose HTML into core heading / paragraph / list blocks.
 *
 * @param string $html Markup.
 * @return array<int, array<string, mixed>>
 */
function cil_html_to_prose_blocks( $html ) {
	$html = trim( $html );
	$html = preg_replace( '/^<div([^>]*)>(.*)<\/div>$/s', '$2', $html );
	$dom  = cil_dom_fragment( $html );
	if ( ! $dom ) {
		return array( cil_rich_html_block( $html ) );
	}

	$body = $dom->getElementsByTagName( 'body' )->item( 0 );
	if ( ! $body ) {
		return array( cil_rich_html_block( $html ) );
	}

	$blocks = array();
	foreach ( $body->childNodes as $node ) {
		if ( XML_TEXT_NODE === $node->nodeType ) {
			$text = trim( $node->textContent );
			if ( $text ) {
				$blocks[] = cil_core_paragraph( esc_html( $text ) );
			}
			continue;
		}
		if ( XML_ELEMENT_NODE !== $node->nodeType ) {
			continue;
		}
		$tag = strtolower( $node->nodeName );
		if ( in_array( $tag, array( 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ) {
			$level     = (int) substr( $tag, 1 );
			$class     = cil_class_token( $node->getAttribute( 'class' ), 'd-' );
			$class_name = trim( 'display ' . $class );
			$blocks[]  = cil_core_heading_class( cil_dom_inner_html( $node ), $level, $class ? $class_name : '' );
			continue;
		}
		if ( 'p' === $tag ) {
			$blocks[] = cil_core_paragraph( $dom->saveHTML( $node ) );
			continue;
		}
		if ( 'ul' === $tag || 'ol' === $tag ) {
			$items = array();
			foreach ( $node->getElementsByTagName( 'li' ) as $li ) {
				$items[] = cil_dom_inner_html( $li );
			}
			if ( $items ) {
				$blocks[] = cil_core_list( $items );
			}
			continue;
		}
		if ( 'div' === $tag ) {
			$inner = trim( cil_dom_inner_html( $node ) );
			if ( $inner ) {
				$blocks = array_merge( $blocks, cil_html_to_prose_blocks( $inner ) );
			}
			continue;
		}
		$blocks[] = cil_rich_html_block( $dom->saveHTML( $node ) );
	}

	if ( ! $blocks ) {
		return array( cil_rich_html_block( $html ) );
	}

	$only_rich = 1 === count( $blocks ) && ! empty( $blocks[0]['blockName'] ) && 'cil/rich-html' === $blocks[0]['blockName'];
	if ( $only_rich ) {
		return $blocks;
	}

	return array(
		cil_layout_block(
			'core/group',
			array(
				'className' => 'body-text',
			),
			$blocks,
			'<div class="wp-block-group body-text">',
			'</div>'
		),
	);
}

/**
 * Turn one core/html block into one or more editable blocks.
 *
 * @param array<string, mixed> $block Block.
 * @return array<int, array<string, mixed>>
 */
function cil_convert_html_block( $block ) {
	$html = '';
	if ( ! empty( $block['innerHTML'] ) ) {
		$html = $block['innerHTML'];
	} elseif ( ! empty( $block['innerContent'][0] ) ) {
		$html = $block['innerContent'][0];
	}
	$html = trim( $html );
	if ( '' === $html ) {
		return array();
	}

	$cards = cil_html_to_info_cards( $html );
	if ( $cards ) {
		return array( $cards );
	}

	$form = cil_html_to_callback_card( $html );
	if ( $form ) {
		return array( $form );
	}

	$head = cil_html_to_section_head( $html );
	if ( $head ) {
		return array( $head );
	}

	$callout = cil_html_to_callout( $html );
	if ( $callout ) {
		return array( $callout );
	}

	return cil_html_to_prose_blocks( $html );
}

/**
 * Recursively replace core/html children.
 *
 * @param array<int, array<string, mixed>> $blocks Blocks.
 * @return array{0: array<int, array<string, mixed>>, 1: int}
 */
function cil_replace_html_blocks( $blocks ) {
	$out     = array();
	$changed = 0;
	foreach ( $blocks as $block ) {
		if ( empty( $block['blockName'] ) ) {
			$out[] = $block;
			continue;
		}
		if ( 'core/html' === $block['blockName'] ) {
			$converted = cil_convert_html_block( $block );
			$changed  += count( $converted );
			foreach ( $converted as $item ) {
				$out[] = $item;
			}
			continue;
		}
		if ( ! empty( $block['innerBlocks'] ) ) {
			list( $inner, $inner_changed ) = cil_replace_html_blocks( $block['innerBlocks'] );
			if ( $inner_changed ) {
				$block['innerBlocks'] = $inner;
				$open                 = isset( $block['innerContent'][0] ) ? $block['innerContent'][0] : '';
				$close                = '';
				if ( is_array( $block['innerContent'] ) && count( $block['innerContent'] ) ) {
					$close = $block['innerContent'][ count( $block['innerContent'] ) - 1 ];
				}
				$block['innerContent'] = array( $open );
				foreach ( $inner as $_unused ) {
					$block['innerContent'][] = null;
				}
				$block['innerContent'][] = $close;
				$changed                += $inner_changed;
			}
		}
		$out[] = $block;
	}
	return array( $out, $changed );
}

/**
 * Convert a full post_content string.
 *
 * @param string $content post_content.
 * @return array{0: string, 1: int}
 */
function cil_migrate_post_content( $content ) {
	if ( false === strpos( $content, '<!-- wp:html' ) ) {
		return array( $content, 0 );
	}
	$blocks = parse_blocks( $content );
	list( $next, $changed ) = cil_replace_html_blocks( $blocks );
	if ( ! $changed ) {
		return array( $content, 0 );
	}
	return array( serialize_blocks( $next ), $changed );
}

/**
 * Backup directory for pre-migration post_content.
 *
 * @return string
 */
function cil_gutenberg_backup_dir() {
	return get_template_directory() . '/content-fixtures/backups';
}

/**
 * Write a raw post_content backup. Local-only; do not copy to Vercel.
 *
 * @param WP_Post $post Page.
 * @return string|WP_Error
 */
function cil_gutenberg_backup_post( $post ) {
	$dir = cil_gutenberg_backup_dir();
	if ( ! is_dir( $dir ) && ! wp_mkdir_p( $dir ) ) {
		return new WP_Error( 'cil_backup_dir', 'Could not create backup directory.' );
	}
	$path = $dir . '/' . (int) $post->ID . '-' . $post->post_name . '-' . gmdate( 'Ymd-His' ) . '.html';
	if ( false === file_put_contents( $path, $post->post_content ) ) {
		return new WP_Error( 'cil_backup_write', 'Could not write ' . $path );
	}
	return $path;
}

/**
 * Migrate one published page. Creates a WordPress revision.
 *
 * @param int $post_id Page ID.
 * @return array<string, mixed>|WP_Error
 */
function cil_migrate_page_html( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post || 'page' !== $post->post_type ) {
		return new WP_Error( 'cil_migrate_missing', 'Not a page: ' . $post_id );
	}

	list( $next, $changed ) = cil_migrate_post_content( $post->post_content );
	if ( ! $changed ) {
		return array(
			'id'      => (int) $post->ID,
			'slug'    => $post->post_name,
			'changed' => false,
			'replaced' => 0,
		);
	}

	$backup = cil_gutenberg_backup_post( $post );
	if ( is_wp_error( $backup ) ) {
		return $backup;
	}

	kses_remove_filters();
	remove_filter( 'content_save_pre', 'convert_invalid_entities' );
	remove_filter( 'content_save_pre', 'balanceTags', 50 );
	$result = wp_update_post(
		wp_slash(
			array(
				'ID'           => (int) $post->ID,
				'post_content' => $next,
			)
		),
		true
	);
	add_filter( 'content_save_pre', 'convert_invalid_entities' );
	add_filter( 'content_save_pre', 'balanceTags', 50 );
	kses_init_filters();

	if ( is_wp_error( $result ) ) {
		return $result;
	}

	update_post_meta( (int) $post->ID, '_cil_gutenberg_editability', '0.13.0' );

	return array(
		'id'       => (int) $post->ID,
		'slug'     => $post->post_name,
		'changed'  => true,
		'replaced' => $changed,
		'backup'   => $backup,
	);
}

/**
 * Migrate every published page that still contains core/html.
 *
 * @return array<int, array<string, mixed>>
 */
function cil_migrate_all_page_html() {
	$pages  = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);
	$report = array();
	foreach ( $pages as $page ) {
		if ( false === strpos( (string) $page->post_content, '<!-- wp:html' ) ) {
			continue;
		}
		$report[] = cil_migrate_page_html( (int) $page->ID );
	}
	return $report;
}
