<?php
/**
 * Inner-page heading: eyebrow, H1, lede, optional reviewer line.
 * Breadcrumb trails are not shown in the page head (kept out of the visible UI).
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title        = isset( $args['title'] ) ? $args['title'] : '';
$lede         = isset( $args['lede'] ) ? $args['lede'] : '';
$eyebrow      = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$reviewed_by  = isset( $args['reviewed_by'] ) ? $args['reviewed_by'] : '';
$lede_is_html = ! empty( $args['lede_html'] );
$reviewed_pre = isset( $args['reviewed_label'] ) ? (string) $args['reviewed_label'] : '';
?>
<section class="page-head">
	<div class="wrap">
		<?php if ( $eyebrow ) : ?>
			<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
		<?php endif; ?>
		<h1 class="display d-1"><?php echo esc_html( $title ); ?></h1>
		<?php if ( $lede ) : ?>
			<p class="lede" style="margin-top:24px"><?php echo $lede_is_html ? cil_rich_text( $lede ) : esc_html( $lede ); ?></p>
		<?php endif; ?>
		<?php if ( $reviewed_by && '' !== $reviewed_pre ) : ?>
			<p class="reviewed-by"><?php echo wp_kses_post( $reviewed_pre . ' ' . $reviewed_by . '.' ); ?></p>
		<?php elseif ( $reviewed_by ) : ?>
			<p class="reviewed-by"><?php echo wp_kses_post( sprintf( /* translators: %s: reviewer name, possibly linked */ __( 'Clinically reviewed by %s.', 'circumcision-london' ), $reviewed_by ) ); ?></p>
		<?php endif; ?>
	</div>
</section>
