<?php
/**
 * Testimonial grid with optional numbered pagination.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$quotes   = ( isset( $args['quotes'] ) && is_array( $args['quotes'] ) ) ? $args['quotes'] : cil_testimonials();
$per_page = isset( $args['per_page'] ) ? (int) $args['per_page'] : 0;
$total    = count( $quotes );
$page     = 1;
$pages    = 1;
$slice    = $quotes;

if ( $per_page > 0 && $total > $per_page ) {
	$pages = (int) ceil( $total / $per_page );
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- public pagination query arg.
	$requested = isset( $_GET['tp'] ) ? absint( wp_unslash( $_GET['tp'] ) ) : 1;
	$page      = max( 1, min( $pages, $requested ? $requested : 1 ) );
	$offset    = ( $page - 1 ) * $per_page;
	$slice     = array_slice( $quotes, $offset, $per_page );
}
?>
<div class="cil-quotes-paginated" id="testimonials-list">
	<div class="grid g-3 cil-quotes-grid">
		<?php foreach ( $slice as $i => $quote ) : ?>
			<?php
			get_template_part(
				'template-parts/quote',
				null,
				array(
					'name'    => isset( $quote['name'] ) ? $quote['name'] : '',
					'context' => isset( $quote['context'] ) ? $quote['context'] : '',
					'quote'   => isset( $quote['quote'] ) ? $quote['quote'] : '',
					'sample'  => ! empty( $quote['sample'] ),
					'delay'   => ( $i % 3 ) * 90,
				)
			);
			?>
		<?php endforeach; ?>
	</div>
	<?php if ( $per_page > 0 && $pages > 1 ) : ?>
		<nav class="cil-pagination" aria-label="<?php esc_attr_e( 'Testimonials pages', 'circumcision-london' ); ?>">
			<ul class="cil-pagination__list">
				<?php for ( $n = 1; $n <= $pages; $n++ ) : ?>
					<?php
					$url = esc_url( add_query_arg( 'tp', $n, get_permalink() ) . '#testimonials-list' );
					$is_current = ( $n === $page );
					?>
					<li>
						<?php if ( $is_current ) : ?>
							<span class="cil-pagination__item is-current" aria-current="page"><?php echo esc_html( (string) $n ); ?></span>
						<?php else : ?>
							<a class="cil-pagination__item" href="<?php echo $url; ?>"><?php echo esc_html( (string) $n ); ?></a>
						<?php endif; ?>
					</li>
				<?php endfor; ?>
			</ul>
			<p class="cil-pagination__meta muted">
				<?php
				printf(
					/* translators: 1: current page, 2: total pages, 3: total reviews */
					esc_html__( 'Page %1$d of %2$d · %3$d reviews', 'circumcision-london' ),
					(int) $page,
					(int) $pages,
					(int) $total
				);
				?>
			</p>
		</nav>
	<?php endif; ?>
</div>
