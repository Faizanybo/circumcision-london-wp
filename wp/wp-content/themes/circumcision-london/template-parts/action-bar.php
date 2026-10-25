<?php
/**
 * Mobile/tablet quick-action bar and back-to-top control.
 *
 * Call, WhatsApp and Book always show. Instagram / YouTube reuse clinic
 * social URLs from Appearance > Customize (empty URLs are omitted).
 *
 * @package Circumcision_London
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$clinic = cil_clinic();

$instagram_url = isset( $clinic['social']['instagram'] ) ? trim( (string) $clinic['social']['instagram'] ) : '';
$youtube_url   = isset( $clinic['social']['youtube'] ) ? trim( (string) $clinic['social']['youtube'] ) : '';
if ( '' === $youtube_url ) {
	// Temporary general destination until the clinic channel URL is confirmed.
	$youtube_url = 'https://www.youtube.com/';
}

$actions = array(
	array(
		'href'  => $clinic['phone']['href'],
		'label' => __( 'Call', 'circumcision-london' ),
		'icon'  => 'phone',
		'track' => 'call-bar',
		'class' => '',
		'extra' => array(),
	),
	array(
		'href'  => $clinic['whatsapp']['href'],
		'label' => __( 'WhatsApp', 'circumcision-london' ),
		'icon'  => 'whatsapp',
		'track' => 'whatsapp-bar',
		'class' => '',
		'extra' => array(
			'rel'    => 'noopener noreferrer',
			'target' => '_blank',
		),
	),
);

if ( '' !== $instagram_url ) {
	$actions[] = array(
		'href'  => $instagram_url,
		'label' => __( 'Instagram', 'circumcision-london' ),
		'icon'  => 'instagram',
		'track' => 'social-instagram-bar',
		'class' => '',
		'extra' => array(
			'rel'        => 'noopener noreferrer',
			'target'     => '_blank',
			'aria-label' => __( 'Circumcision in London on Instagram (opens in a new tab)', 'circumcision-london' ),
		),
	);
}

if ( '' !== $youtube_url ) {
	$actions[] = array(
		'href'  => $youtube_url,
		'label' => __( 'YouTube', 'circumcision-london' ),
		'icon'  => 'youtube',
		'track' => 'social-youtube-bar',
		'class' => '',
		'extra' => array(
			'rel'        => 'noopener noreferrer',
			'target'     => '_blank',
			'aria-label' => __( 'Circumcision in London on YouTube (opens in a new tab)', 'circumcision-london' ),
		),
	);
}

$actions[] = array(
	'href'  => cil_book_url(),
	'label' => __( 'Book', 'circumcision-london' ),
	'icon'  => 'calendar',
	'track' => 'book-bar',
	'class' => 'primary',
	'extra' => array(),
);

$action_count = count( $actions );
?>
<div class="action-bar" role="navigation" aria-label="<?php esc_attr_e( 'Quick actions', 'circumcision-london' ); ?>">
	<ul style="--action-count: <?php echo (int) $action_count; ?>">
		<?php foreach ( $actions as $action ) : ?>
			<li>
				<a
					href="<?php echo esc_url( $action['href'] ); ?>"
					<?php echo $action['class'] ? 'class="' . esc_attr( $action['class'] ) . '" ' : ''; ?>
					data-track="<?php echo esc_attr( $action['track'] ); ?>"
					<?php
					foreach ( $action['extra'] as $attr => $value ) {
						echo esc_attr( $attr ) . '="' . esc_attr( $value ) . '" ';
					}
					?>
				><?php echo cil_icon( $action['icon'] ); ?><span><?php echo esc_html( $action['label'] ); ?></span></a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
<button class="to-top" id="toTop" type="button" aria-label="<?php esc_attr_e( 'Back to top', 'circumcision-london' ); ?>"><?php echo cil_icon( 'arrow-up' ); ?></button>
