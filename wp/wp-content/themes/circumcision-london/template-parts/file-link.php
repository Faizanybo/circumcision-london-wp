<?php
/**
 * Media Library document link (PDF). Renders nothing without a valid file.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$file_id = isset( $args['file_id'] ) ? cil_attachment_id( $args['file_id'] ) : 0;
$href    = isset( $args['url'] ) ? esc_url_raw( $args['url'] ) : '';
$text    = isset( $args['text'] ) ? $args['text'] : '';
$title   = isset( $args['title'] ) ? $args['title'] : '';

if ( $file_id && cil_attachment_is_document( $file_id ) ) {
	$href = cil_attachment_url( $file_id, 'document' );
	if ( ! $text ) {
		$text = get_the_title( $file_id );
	}
}

if ( ! $href ) {
	return;
}

if ( ! $text ) {
	$text = $title ? $title : __( 'Download document', 'circumcision-london' );
}
?>
<p class="btn-row cil-file-link">
	<a class="btn btn-ghost" href="<?php echo esc_url( $href ); ?>" rel="noopener" target="_blank">
		<?php echo esc_html( $text ); ?>
	</a>
</p>
