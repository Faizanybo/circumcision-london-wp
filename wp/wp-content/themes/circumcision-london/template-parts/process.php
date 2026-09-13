<?php
/**
 * Process section wrapping the reusable steps list.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="section bg-card edge">
	<div class="wrap">
		<div class="section-head" data-reveal>
			<span class="caps eyebrow"><?php esc_html_e( 'What happens', 'circumcision-london' ); ?></span>
			<h2 class="display d-1"><?php esc_html_e( 'Four steps, and no surprises in any of them', 'circumcision-london' ); ?></h2>
			<p class="lede"><?php esc_html_e( 'This is the whole process. If anything on the day differs from what is written here, we will have told you why before it happens.', 'circumcision-london' ); ?></p>
		</div>
		<div data-reveal>
			<?php get_template_part( 'template-parts/steps' ); ?>
		</div>
	</div>
</section>
