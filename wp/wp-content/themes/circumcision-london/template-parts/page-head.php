<?php
/**
 * Inner-page heading: breadcrumbs, eyebrow, H1, lede, optional reviewer line.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$title        = isset( $args['title'] ) ? $args['title'] : '';
$lede         = isset( $args['lede'] ) ? $args['lede'] : '';
$eyebrow      = isset( $args['eyebrow'] ) ? $args['eyebrow'] : '';
$crumbs       = ( isset( $args['crumbs'] ) && is_array( $args['crumbs'] ) ) ? $args['crumbs'] : array();
$reviewed_by  = isset( $args['reviewed_by'] ) ? $args['reviewed_by'] : '';
$lede_is_html = ! empty( $args['lede_html'] );
?>
<section class="page-head">
	<div class="wrap">
		<?php if ( $crumbs ) : ?>
			<?php get_template_part( 'template-parts/breadcrumbs', null, array( 'crumbs' => $crumbs ) ); ?>
		<?php endif; ?>
		<?php if ( $eyebrow ) : ?>
			<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
		<?php endif; ?>
		<h1 class="display d-1"><?php echo esc_html( $title ); ?></h1>
		<?php if ( $lede ) : ?>
			<p class="lede" style="margin-top:24px"><?php echo $lede_is_html ? cil_rich_text( $lede ) : esc_html( $lede ); ?></p>
		<?php endif; ?>
		<?php if ( $reviewed_by ) : ?>
			<p class="reviewed-by"><?php echo wp_kses_post( sprintf( /* translators: %s: reviewer name, possibly linked */ __( 'Clinically reviewed by %s.', 'circumcision-london' ), $reviewed_by ) ); ?></p>
		<?php endif; ?>
	</div>
</section>
