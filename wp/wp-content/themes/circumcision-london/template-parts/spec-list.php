<?php
/**
 * Key/value specification list. Values may contain allowlisted HTML (links).
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rows = ( isset( $args['rows'] ) && is_array( $args['rows'] ) ) ? $args['rows'] : array();
if ( ! $rows ) {
	return;
}
?>
<dl class="spec">
	<?php foreach ( $rows as $row ) : ?>
		<li>
			<dt class="k"><?php echo esc_html( isset( $row['k'] ) ? $row['k'] : '' ); ?></dt>
			<dd class="v"><?php echo cil_rich_text( isset( $row['v'] ) ? $row['v'] : '' ); ?></dd>
		</li>
	<?php endforeach; ?>
</dl>
