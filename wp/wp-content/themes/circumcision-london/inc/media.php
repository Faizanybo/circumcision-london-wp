<?php
/**
 * Media Library helpers. Theme-bundled assets remain the fallback.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize a value to a published/inherit attachment ID, or 0.
 *
 * @param mixed $value Raw ID.
 * @return int
 */
function cil_attachment_id( $value ) {
	$id = absint( $value );
	if ( ! $id ) {
		return 0;
	}
	$post = get_post( $id );
	if ( ! $post || 'attachment' !== $post->post_type ) {
		return 0;
	}
	return $id;
}

/**
 * Allowed document MIME types for the file-link block.
 *
 * @return string[]
 */
function cil_document_mimes() {
	return array( 'application/pdf' );
}

/**
 * Whether an attachment is an image.
 *
 * @param int $id Attachment ID.
 * @return bool
 */
function cil_attachment_is_image( $id ) {
	$id = cil_attachment_id( $id );
	return $id && wp_attachment_is_image( $id );
}

/**
 * Whether an attachment is a video WordPress will serve.
 *
 * @param int $id Attachment ID.
 * @return bool
 */
function cil_attachment_is_video( $id ) {
	$id   = cil_attachment_id( $id );
	$mime = $id ? (string) get_post_mime_type( $id ) : '';
	return $id && ( 0 === strpos( $mime, 'video/' ) );
}

/**
 * Whether an attachment is an allowlisted document (PDF).
 *
 * @param int $id Attachment ID.
 * @return bool
 */
function cil_attachment_is_document( $id ) {
	$id   = cil_attachment_id( $id );
	$mime = $id ? (string) get_post_mime_type( $id ) : '';
	return $id && in_array( $mime, cil_document_mimes(), true );
}

/**
 * Public URL for a validated attachment, or empty string.
 *
 * @param int    $id   Attachment ID.
 * @param string $kind image, video or document.
 * @return string
 */
function cil_attachment_url( $id, $kind = 'image' ) {
	$id = cil_attachment_id( $id );
	if ( ! $id ) {
		return '';
	}
	if ( 'image' === $kind && ! cil_attachment_is_image( $id ) ) {
		return '';
	}
	if ( 'video' === $kind && ! cil_attachment_is_video( $id ) ) {
		return '';
	}
	if ( 'document' === $kind && ! cil_attachment_is_document( $id ) ) {
		return '';
	}
	$url = wp_get_attachment_url( $id );
	return $url ? $url : '';
}

/**
 * Attachment alt text, with optional override.
 *
 * @param int    $id       Attachment ID.
 * @param string $fallback Override or fallback.
 * @return string
 */
function cil_attachment_alt( $id, $fallback = '' ) {
	if ( '' !== $fallback ) {
		return $fallback;
	}
	$id = cil_attachment_id( $id );
	if ( ! $id ) {
		return '';
	}
	$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
	if ( is_string( $alt ) && $alt ) {
		return $alt;
	}
	$title = get_the_title( $id );
	return is_string( $title ) ? $title : '';
}

/**
 * Image markup from a Media Library attachment.
 *
 * @param int                  $id    Attachment ID.
 * @param string               $size  Registered size.
 * @param array<string, mixed> $attrs img attributes.
 * @return string
 */
function cil_attachment_image_html( $id, $size = 'cil-figure', $attrs = array() ) {
	if ( ! cil_attachment_is_image( $id ) ) {
		return '';
	}
	if ( empty( $attrs['alt'] ) ) {
		$attrs['alt'] = cil_attachment_alt( $id );
	}

	$file = get_attached_file( $id );
	$ver  = ( $file && file_exists( $file ) ) ? (string) filemtime( $file ) : (string) get_post_modified_time( 'U', true, $id );

	$bust = function ( $attr, $attachment ) use ( $id, $ver ) {
		if ( (int) $attachment->ID !== (int) $id ) {
			return $attr;
		}
		if ( ! empty( $attr['src'] ) ) {
			$attr['src'] = add_query_arg( 'ver', $ver, $attr['src'] );
		}
		if ( ! empty( $attr['srcset'] ) ) {
			$attr['srcset'] = preg_replace_callback(
				'/(\S+)(\s+\d+[wx])/',
				function ( $m ) use ( $ver ) {
					return add_query_arg( 'ver', $ver, $m[1] ) . $m[2];
				},
				$attr['srcset']
			);
		}
		return $attr;
	};

	add_filter( 'wp_get_attachment_image_attributes', $bust, 10, 2 );
	$html = wp_get_attachment_image( (int) $id, $size, false, $attrs );
	remove_filter( 'wp_get_attachment_image_attributes', $bust, 10 );

	return is_string( $html ) ? $html : '';
}

/**
 * Attachment ID a figure block should render.
 *
 * @param array<string, mixed> $attrs   Block attrs.
 * @param int                  $post_id Current post.
 * @return int
 */
function cil_figure_attachment_id( $attrs, $post_id = 0 ) {
	if ( ! empty( $attrs['useFeaturedImage'] ) ) {
		if ( ! $post_id ) {
			$post_id = (int) get_the_ID();
		}
		$featured = $post_id ? cil_attachment_id( get_post_thumbnail_id( $post_id ) ) : 0;
		if ( $featured ) {
			return $featured;
		}
	}
	return cil_attachment_id( isset( $attrs['imageId'] ) ? $attrs['imageId'] : 0 );
}

/**
 * Poster ID stored on the front-page hero block, if any.
 *
 * @return int
 */
function cil_front_hero_poster_id() {
	$front = (int) get_queried_object_id();
	if ( ! $front ) {
		$front = (int) get_option( 'page_on_front' );
	}
	$post = get_post( $front );
	if ( ! $post ) {
		return 0;
	}
	$blocks = parse_blocks( $post->post_content );
	foreach ( $blocks as $block ) {
		if ( empty( $block['blockName'] ) || 'cil/hero' !== $block['blockName'] ) {
			continue;
		}
		$attrs = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();
		return cil_attachment_id( isset( $attrs['posterId'] ) ? $attrs['posterId'] : 0 );
	}
	return 0;
}

/**
 * Resolve video-grid item URLs from attachment IDs when URL fields are empty.
 *
 * @param array<string, mixed> $item Item.
 * @return array<string, mixed>
 */
function cil_resolve_video_item( $item ) {
	if ( ! is_array( $item ) ) {
		return array();
	}
	if ( empty( $item['poster'] ) && ! empty( $item['posterId'] ) ) {
		$item['poster'] = cil_attachment_url( $item['posterId'], 'image' );
	}
	if ( empty( $item['mp4'] ) && ! empty( $item['mp4Id'] ) ) {
		$item['mp4'] = cil_attachment_url( $item['mp4Id'], 'video' );
	}
	if ( empty( $item['webm'] ) && ! empty( $item['webmId'] ) ) {
		$item['webm'] = cil_attachment_url( $item['webmId'], 'video' );
	}
	return $item;
}
