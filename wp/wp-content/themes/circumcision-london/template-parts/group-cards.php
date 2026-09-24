<?php
/**
 * Age-group cards. Pass $args['level'] (2 or 3) and optional $args['exclude'] href.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$level   = isset( $args['level'] ) ? (int) $args['level'] : 3;
$level   = in_array( $level, array( 2, 3 ), true ) ? $level : 3;
$exclude = isset( $args['exclude'] ) ? $args['exclude'] : '';
$groups  = ( isset( $args['items'] ) && is_array( $args['items'] ) && $args['items'] ) ? $args['items'] : cil_groups();
$tag     = 'h' . $level;

$visible = array();
foreach ( $groups as $group ) {
	if ( $exclude && untrailingslashit( $group['href'] ) === untrailingslashit( $exclude ) ) {
		continue;
	}
	$visible[] = $group;
}

$count      = count( $visible );
$grid_class = 'grid g-3';
if ( 2 === $count ) {
	$grid_class = 'grid g-2 card-groups-2';
} elseif ( 1 === $count ) {
	$grid_class = 'grid g-1 card-groups-1';
}

$i = 0;
?>
<div class="<?php echo esc_attr( $grid_class ); ?>">
	<?php foreach ( $visible as $group ) : ?>
		<?php
		$delay = $i * 90;
		$i++;
		?>
		<a class="card-group" href="<?php echo esc_url( $group['href'] ); ?>" data-reveal data-reveal-delay="<?php echo esc_attr( (string) $delay ); ?>">
			<span class="caps age"><?php echo esc_html( $group['age'] ); ?></span>
			<<?php echo tag_escape( $tag ); ?> class="display d-3"><?php echo esc_html( $group['title'] ); ?></<?php echo tag_escape( $tag ); ?>>
			<span class="price"><?php echo esc_html( $group['price'] ); ?></span>
			<p><?php echo esc_html( $group['blurb'] ); ?></p>
			<span class="go"><?php echo esc_html( $group['cta'] ); ?></span>
		</a>
	<?php endforeach; ?>
</div>
