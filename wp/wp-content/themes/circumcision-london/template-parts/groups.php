<?php
/**
 * Overlapping age-group band used under the homepage hero.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$level = isset( $args['level'] ) ? (int) $args['level'] : 2;
$items = ( isset( $args['items'] ) && is_array( $args['items'] ) ) ? $args['items'] : array();
?>
<div class="groups">
	<?php
	get_template_part(
		'template-parts/group-cards',
		null,
		array(
			'level' => $level,
			'items' => $items,
		)
	);
	?>
</div>
