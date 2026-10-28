<?php
/**
 * Site footer: brand, contact, grouped links, hours, legal.
 *
 * Link groups use <details> so mobile/tablet can collapse secondary columns
 * without removing content. Desktop keeps them expanded.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic = cil_clinic();

/**
 * Render a footer link column as an accessible accordion group.
 *
 * @param string            $label    Column heading.
 * @param string            $location Menu location.
 * @param array<int, array> $fallback Fallback links.
 */
$cil_footer_nav_group = static function ( $label, $location, $fallback ) {
	?>
	<details class="footer-accordion" open>
		<summary class="footer-accordion__summary caps"><?php echo esc_html( $label ); ?></summary>
		<div class="footer-accordion__body">
			<?php cil_link_list( $location, $fallback ); ?>
		</div>
	</details>
	<?php
};
?>
<footer class="footer">
	<div class="wrap footer-top">
		<div class="footer-grid">
			<div class="footer-col footer-col--brand">
				<?php get_template_part( 'template-parts/brand', null, array( 'context' => 'footer' ) ); ?>
				<address>
					<?php echo esc_html( $clinic['address']['street'] ); ?><br>
					<?php echo esc_html( $clinic['address']['locality'] . ', ' . $clinic['address']['postcode'] ); ?><br>
					<a href="<?php echo esc_url( $clinic['phone']['href'] ); ?>" data-track="call-footer"><?php echo esc_html( $clinic['phone']['display'] ); ?></a><br>
					<a href="<?php echo esc_url( $clinic['mobile']['href'] ); ?>" data-track="call-footer-mobile"><?php echo esc_html( $clinic['mobile']['display'] ); ?></a><br>
					<a href="<?php echo esc_url( 'mailto:' . $clinic['email'] ); ?>"><?php echo esc_html( $clinic['email'] ); ?></a>
				</address>
				<details class="footer-accordion footer-accordion--hours" open>
					<summary class="footer-accordion__summary caps"><?php esc_html_e( 'Opening hours', 'circumcision-london' ); ?></summary>
					<div class="footer-accordion__body">
						<ul class="hours">
							<?php foreach ( $clinic['hours'] as $row ) : ?>
								<li><span><?php echo esc_html( $row['days'] ); ?></span> <span><?php echo esc_html( $row['open'] . ' – ' . $row['close'] ); ?></span></li>
							<?php endforeach; ?>
						</ul>
						<p class="footer-aside"><?php echo esc_html( $clinic['hours_note'] ); ?></p>
						<p class="footer-aside"><?php esc_html_e( 'Free Parking on the street. outside congestion charge zone.', 'circumcision-london' ); ?></p>
					</div>
				</details>
			</div>
			<div class="footer-col">
				<?php $cil_footer_nav_group( __( 'Treatments', 'circumcision-london' ), 'footer-treatments', cil_default_footer_treatments() ); ?>
			</div>
			<div class="footer-col">
				<?php $cil_footer_nav_group( __( 'Patient information', 'circumcision-london' ), 'footer-patient', cil_default_footer_patient() ); ?>
			</div>
			<div class="footer-col">
				<?php $cil_footer_nav_group( __( 'About & contact', 'circumcision-london' ), 'footer-about', cil_default_footer_about() ); ?>
			</div>
			<div class="footer-col">
				<?php $cil_footer_nav_group( __( 'Locations', 'circumcision-london' ), 'footer-clinics', cil_default_footer_clinics() ); ?>
			</div>
		</div>
	</div>
	<div class="wrap">
		<div class="footer-bottom">
			<span>
				<?php
				echo esc_html(
					sprintf(
						/* translators: 1: year, 2: site name */
						__( '© %1$s %2$s. Registered with the Care Quality Commission.', 'circumcision-london' ),
						wp_date( 'Y' ),
						$clinic['name']
					)
				);
				?>
			</span>
			<?php
			$social_icons = array(
				'instagram' => array(
					'label' => __( 'Circumcision in London on Instagram (opens in a new tab)', 'circumcision-london' ),
					'svg'   => '<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" focusable="false"><path fill="currentColor" d="M12 2.2c3.2 0 3.6 0 4.8.1 1.2.1 1.8.2 2.2.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.2.4.4 1.1.4 2.2.1 1.3.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 1.2-.2 1.8-.4 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.2-1.1.4-2.2.4-1.3.1-1.6.1-4.8.1s-3.6 0-4.8-.1c-1.2-.1-1.8-.2-2.2-.4-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.2-.4-.4-1.1-.4-2.2-.1-1.3-.1-1.6-.1-4.8s0-3.6.1-4.8c.1-1.2.2-1.8.4-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.2 1.1-.4 2.2-.4 1.2-.1 1.6-.1 4.8-.1zm0 1.8c-3.1 0-3.5 0-4.7.1-1.1.1-1.7.2-2.1.4-.5.2-.9.4-1.3.8-.4.4-.6.8-.8 1.3-.2.4-.3 1-.4 2.1-.1 1.2-.1 1.6-.1 4.7s0 3.5.1 4.7c.1 1.1.2 1.7.4 2.1.2.5.4.9.8 1.3.4.4.8.6 1.3.8.4.2 1 .3 2.1.4 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c1.1-.1 1.7-.2 2.1-.4.5-.2.9-.4 1.3-.8.4-.4.6-.8.8-1.3.2-.4.3-1 .4-2.1.1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c-.1-1.1-.2-1.7-.4-2.1-.2-.5-.4-.9-.8-1.3-.4-.4-.8-.6-1.3-.8-.4-.2-1-.3-2.1-.4-1.2-.1-1.6-.1-4.7-.1zm0 3.1a4.9 4.9 0 1 1 0 9.8 4.9 4.9 0 0 1 0-9.8zm0 8.1a3.2 3.2 0 1 0 0-6.4 3.2 3.2 0 0 0 0 6.4zm6.2-8.3a1.1 1.1 0 1 1-2.3 0 1.1 1.1 0 0 1 2.3 0z"/></svg>',
				),
				'youtube'   => array(
					'label' => __( 'Circumcision in London on YouTube (opens in a new tab)', 'circumcision-london' ),
					'svg'   => '<svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" focusable="false"><path fill="currentColor" d="M23 7.2a3 3 0 0 0-2.1-2.1C19 4.6 12 4.6 12 4.6s-7 0-8.9.5A3 3 0 0 0 1 7.2C.5 9.1.5 12 .5 12s0 2.9.5 4.8a3 3 0 0 0 2.1 2.1c1.9.5 8.9.5 8.9.5s7 0 8.9-.5a3 3 0 0 0 2.1-2.1c.5-1.9.5-4.8.5-4.8s0-2.9-.5-4.8zM9.7 15.1V8.9l5.8 3.1-5.8 3.1z"/></svg>',
				),
			);
			$social_links = array();
			foreach ( $social_icons as $key => $icon ) {
				$url = isset( $clinic['social'][ $key ] ) ? trim( (string) $clinic['social'][ $key ] ) : '';
				if ( '' !== $url ) {
					$social_links[ $key ] = $url;
				}
			}
			if ( $social_links ) :
				?>
			<ul class="footer-social" aria-label="<?php esc_attr_e( 'Social media', 'circumcision-london' ); ?>">
				<?php foreach ( $social_links as $key => $url ) : ?>
					<li><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $social_icons[ $key ]['label'] ); ?>" data-track="social-<?php echo esc_attr( $key ); ?>"><?php echo $social_icons[ $key ]['svg']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></a></li>
				<?php endforeach; ?>
			</ul>
				<?php
			endif;
			$legal = cil_menu_tree( 'footer-legal', cil_default_footer_legal() );
			echo '<ul class="footer-legal spacer">';
			foreach ( $legal as $item ) {
				echo '<li><a href="' . esc_url( $item['url'] ) . '"' . cil_link_extra_attrs( $item ) . '>' . esc_html( $item['label'] ) . '</a></li>';
			}
			echo '</ul>';
			?>
		</div>
	</div>
</footer>
