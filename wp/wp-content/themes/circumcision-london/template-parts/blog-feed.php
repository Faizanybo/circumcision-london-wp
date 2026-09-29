<?php
/**
 * Alternating blog feed: image | copy, then copy | image.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$per_page   = isset( $args['per_page'] ) ? max( 1, (int) $args['per_page'] ) : 12;
$more_label = ( isset( $args['more_label'] ) && '' !== $args['more_label'] ) ? $args['more_label'] : __( 'Read more', 'circumcision-london' );
$category   = isset( $args['category'] ) ? (string) $args['category'] : 'clinic-articles';

$query_args = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => $per_page,
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);

if ( $category ) {
	$query_args['category_name'] = $category;
}

$q = new WP_Query( $query_args );
?>
<section class="section-sm cil-blog-feed">
	<div class="wrap">
		<?php if ( ! $q->have_posts() ) : ?>
			<p class="muted"><?php esc_html_e( 'No articles published yet.', 'circumcision-london' ); ?></p>
		<?php else : ?>
			<div class="cil-blog-feed__list">
				<?php
				$i = 0;
				while ( $q->have_posts() ) :
					$q->the_post();
					$swap      = ( 1 === ( $i % 2 ) );
					$row_class = 'split cil-blog-row' . ( $swap ? ' cil-blog-row--swap' : '' );
					$thumb_id  = get_post_thumbnail_id();
					$cats      = get_the_category();
					$eyebrow   = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : __( 'Clinic articles', 'circumcision-london' );
					?>
					<article <?php post_class( $row_class ); ?> data-reveal>
						<?php if ( ! $swap ) : ?>
							<figure class="figure ratio-4-3 cil-blog-row__media">
								<?php if ( $thumb_id ) : ?>
									<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
										<?php
										echo wp_get_attachment_image(
											$thumb_id,
											'cil-figure',
											false,
											array(
												'loading'  => 'lazy',
												'decoding' => 'async',
												'alt'      => the_title_attribute( array( 'echo' => false ) ),
											)
										);
										?>
									</a>
								<?php else : ?>
									<div class="cil-blog-row__placeholder" aria-hidden="true"></div>
								<?php endif; ?>
							</figure>
						<?php endif; ?>

						<div class="cil-blog-row__body">
							<span class="caps eyebrow"><?php echo esc_html( $eyebrow ); ?></span>
							<h2 class="display d-2">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
							<?php if ( has_excerpt() || get_the_excerpt() ) : ?>
								<p class="cil-blog-row__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
							<?php endif; ?>
							<p class="btn-row" style="margin-top:26px">
								<a class="btn" href="<?php the_permalink(); ?>"><?php echo esc_html( $more_label ); ?></a>
							</p>
						</div>

						<?php if ( $swap ) : ?>
							<figure class="figure ratio-4-3 cil-blog-row__media">
								<?php if ( $thumb_id ) : ?>
									<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
										<?php
										echo wp_get_attachment_image(
											$thumb_id,
											'cil-figure',
											false,
											array(
												'loading'  => 'lazy',
												'decoding' => 'async',
												'alt'      => the_title_attribute( array( 'echo' => false ) ),
											)
										);
										?>
									</a>
								<?php else : ?>
									<div class="cil-blog-row__placeholder" aria-hidden="true"></div>
								<?php endif; ?>
							</figure>
						<?php endif; ?>
					</article>
					<?php
					$i++;
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
