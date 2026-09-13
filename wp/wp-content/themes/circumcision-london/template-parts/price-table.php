<?php
/**
 * Price rows. Each row: name, optional desc, price, optional href, cta label.
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
<div class="price-table" data-reveal>
	<?php foreach ( $rows as $row ) : ?>
		<?php
		$name = isset( $row['name'] ) ? $row['name'] : '';
		$desc = isset( $row['desc'] ) ? $row['desc'] : '';
		$href = isset( $row['href'] ) ? $row['href'] : '';
		$cta  = isset( $row['cta'] ) && $row['cta'] ? $row['cta'] : __( 'Book this', 'circumcision-london' );
		$url  = isset( $row['url'] ) && $row['url'] ? $row['url'] : cil_book_url();
		?>
		<div class="price-row">
			<div>
				<h3>
					<?php if ( $href ) : ?>
						<a href="<?php echo esc_url( $href ); ?>" style="text-decoration:none;color:inherit"><?php echo esc_html( $name ); ?></a>
					<?php else : ?>
						<?php echo esc_html( $name ); ?>
					<?php endif; ?>
				</h3>
				<?php if ( $desc ) : ?>
					<p><?php echo esc_html( $desc ); ?></p>
				<?php endif; ?>
			</div>
			<span class="amount"><?php echo esc_html( isset( $row['price'] ) ? $row['price'] : '' ); ?></span>
			<a class="btn" href="<?php echo esc_url( $url ); ?>" data-track="book-price-row"><?php echo esc_html( $cta ); ?></a>
		</div>
	<?php endforeach; ?>
</div>
