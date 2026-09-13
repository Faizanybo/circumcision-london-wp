<?php
/**
 * Site footer: brand, contact, who we see, reasons, hours, legal.
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic = cil_clinic();
?>
<footer class="footer">
	<div class="wrap footer-top">
		<div class="footer-grid">
			<div>
				<?php get_template_part( 'template-parts/brand' ); ?>
				<address>
					<?php echo esc_html( $clinic['address']['street'] ); ?><br>
					<?php echo esc_html( $clinic['address']['locality'] . ', ' . $clinic['address']['postcode'] ); ?><br>
					<a href="<?php echo esc_url( $clinic['phone']['href'] ); ?>" data-track="call-footer"><?php echo esc_html( $clinic['phone']['display'] ); ?></a><br>
					<a href="<?php echo esc_url( $clinic['mobile']['href'] ); ?>" data-track="call-footer-mobile"><?php echo esc_html( $clinic['mobile']['display'] ); ?></a><br>
					<a href="<?php echo esc_url( 'mailto:' . $clinic['email'] ); ?>"><?php echo esc_html( $clinic['email'] ); ?></a>
				</address>
			</div>
			<div>
				<span class="caps"><?php esc_html_e( 'Who we see', 'circumcision-london' ); ?></span>
				<?php cil_link_list( 'footer-who', cil_default_footer_who() ); ?>
			</div>
			<div>
				<span class="caps"><?php esc_html_e( 'Reasons', 'circumcision-london' ); ?></span>
				<?php cil_link_list( 'footer-reasons', cil_default_footer_reasons() ); ?>
			</div>
			<div>
				<span class="caps"><?php esc_html_e( 'Opening hours', 'circumcision-london' ); ?></span>
				<ul class="hours">
					<?php foreach ( $clinic['hours'] as $row ) : ?>
						<li><span><?php echo esc_html( $row['days'] ); ?></span> <span><?php echo esc_html( $row['open'] . ' – ' . $row['close'] ); ?></span></li>
					<?php endforeach; ?>
				</ul>
				<p class="footer-aside"><?php echo esc_html( $clinic['hours_note'] ); ?></p>
				<p class="footer-aside"><?php esc_html_e( 'Free parking on site. Outside the Congestion Charge and ULEZ zones.', 'circumcision-london' ); ?></p>
			</div>
		</div>
	</div>
	<div class="wrap">
		<div class="footer-bottom">
			<span>
				<?php
				echo esc_html(
					sprintf(
						/* translators: 1: year, 2: legal name */
						__( '© %1$s %2$s. Registered with the Care Quality Commission.', 'circumcision-london' ),
						wp_date( 'Y' ),
						$clinic['legal_name']
					)
				);
				?>
			</span>
			<?php
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
