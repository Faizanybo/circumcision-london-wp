<?php
/**
 * Breadcrumb trail. Home is always first. The last crumb is the current page.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$crumbs = ( isset( $args['crumbs'] ) && is_array( $args['crumbs'] ) ) ? $args['crumbs'] : array();
if ( ! $crumbs ) {
	return;
}

$count = count( $crumbs );
?>
<ol class="breadcrumb">
	<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'circumcision-london' ); ?></a></li>
	<?php foreach ( $crumbs as $i => $crumb ) : ?>
		<?php if ( $i === $count - 1 ) : ?>
			<li aria-current="page"><?php echo esc_html( $crumb['label'] ); ?></li>
		<?php else : ?>
			<li><a href="<?php echo esc_url( isset( $crumb['href'] ) ? $crumb['href'] : '' ); ?>"><?php echo esc_html( $crumb['label'] ); ?></a></li>
		<?php endif; ?>
	<?php endforeach; ?>
</ol>
