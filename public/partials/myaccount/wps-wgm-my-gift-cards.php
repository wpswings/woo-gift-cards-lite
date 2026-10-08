<?php
/**
 * My Account > My Gift Cards.
 *
 * This template can be overridden by copying it to yourtheme/woo-gift-cards-lite/myaccount/wps-wgm-my-gift-cards.php.
 *
 * @package woo-gift-cards-lite
 * @since   3.2.13
 *
 * @var array[] $received  Cards sent to the current user, usable cards first.
 * @var array[] $purchased Cards the current user bought for someone else.
 * @var array   $summary   Totals for the received cards: balance, active, expiring.
 * @var string  $accent    Hex color used for the card face.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wps_wgm_status_labels = array(
	'active'   => __( 'Active', 'woo-gift-cards-lite' ),
	'used'     => __( 'Fully used', 'woo-gift-cards-lite' ),
	'expired'  => __( 'Expired', 'woo-gift-cards-lite' ),
	'disabled' => __( 'Disabled', 'woo-gift-cards-lite' ),
);

$wps_wgm_icon_gift  = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M20 12v9H4v-9M2 7h20v5H2zM12 21V7M12 7H7.5a2.5 2.5 0 1 1 0-5C11 2 12 7 12 7zM12 7h4.5a2.5 2.5 0 1 0 0-5C13 2 12 7 12 7z"/></svg>';
$wps_wgm_icon_copy  = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>';
$wps_wgm_icon_mail  = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/></svg>';
$wps_wgm_icon_clock = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>';
$wps_wgm_svg_tags   = array(
	'svg'    => array(
		'viewbox'     => true,
		'aria-hidden' => true,
		'focusable'   => true,
	),
	'path'   => array( 'd' => true ),
	'rect'   => array(
		'x'      => true,
		'y'      => true,
		'width'  => true,
		'height' => true,
		'rx'     => true,
	),
	'circle' => array(
		'cx' => true,
		'cy' => true,
		'r'  => true,
	),
);
?>
<div class="wps-wgm-mgc" style="--wps-mgc-accent: <?php echo esc_attr( $accent ); ?>;">

<?php if ( empty( $received ) && empty( $purchased ) ) : ?>
	<div class="wps-wgm-mgc-empty">
		<span class="wps-wgm-mgc-empty-icon"><?php echo wp_kses( $wps_wgm_icon_gift, $wps_wgm_svg_tags ); ?></span>
		<h3><?php esc_html_e( 'No gift cards yet', 'woo-gift-cards-lite' ); ?></h3>
		<p><?php esc_html_e( 'Gift cards you buy for others, or receive from them, will show up here with their balance and expiry date.', 'woo-gift-cards-lite' ); ?></p>
		<a class="woocommerce-Button button wps-wgm-mgc-btn wps-wgm-mgc-btn-primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Browse gift cards', 'woo-gift-cards-lite' ); ?></a>
	</div>
	<?php
else :
	if ( ! empty( $received ) ) :
		?>
		<div class="wps-wgm-mgc-summary">
			<div class="wps-wgm-mgc-stat wps-wgm-mgc-stat-main">
				<span class="wps-wgm-mgc-stat-label"><?php esc_html_e( 'Available to spend', 'woo-gift-cards-lite' ); ?></span>
				<span class="wps-wgm-mgc-stat-value"><?php echo wp_kses_post( wc_price( $summary['balance'] ) ); ?></span>
			</div>
			<div class="wps-wgm-mgc-stat">
				<span class="wps-wgm-mgc-stat-label"><?php esc_html_e( 'Active cards', 'woo-gift-cards-lite' ); ?></span>
				<span class="wps-wgm-mgc-stat-value"><?php echo esc_html( number_format_i18n( $summary['active'] ) ); ?></span>
			</div>
			<div class="wps-wgm-mgc-stat<?php echo $summary['expiring'] ? ' is-warning' : ''; ?>">
				<span class="wps-wgm-mgc-stat-label"><?php esc_html_e( 'Expiring soon', 'woo-gift-cards-lite' ); ?></span>
				<span class="wps-wgm-mgc-stat-value"><?php echo esc_html( number_format_i18n( $summary['expiring'] ) ); ?></span>
			</div>
		</div>
		<?php
	endif;

	$wps_wgm_sections = array(
		'received'  => array(
			'title' => __( 'Received', 'woo-gift-cards-lite' ),
			'cards' => $received,
		),
		'purchased' => array(
			'title' => __( 'Sent', 'woo-gift-cards-lite' ),
			'cards' => $purchased,
		),
	);

	foreach ( $wps_wgm_sections as $wps_wgm_section_key => $wps_wgm_section ) :
		if ( empty( $wps_wgm_section['cards'] ) ) {
			continue;
		}
		$wps_wgm_is_purchased = 'purchased' === $wps_wgm_section_key;
		?>
		<section class="wps-wgm-mgc-section wps-wgm-mgc-<?php echo esc_attr( $wps_wgm_section_key ); ?>">
			<h3 class="wps-wgm-mgc-section-title">
				<?php echo esc_html( $wps_wgm_section['title'] ); ?>
				<span class="wps-wgm-mgc-count"><?php echo esc_html( number_format_i18n( count( $wps_wgm_section['cards'] ) ) ); ?></span>
			</h3>
			<ul class="wps-wgm-mgc-grid">
				<?php
				foreach ( $wps_wgm_section['cards'] as $wps_wgm_card ) :
					$wps_wgm_code    = strtoupper( $wps_wgm_card['code'] );
					$wps_wgm_percent = $wps_wgm_card['original'] > 0 ? max( 0, min( 100, round( $wps_wgm_card['balance'] / $wps_wgm_card['original'] * 100 ) ) ) : 0;
					$wps_wgm_uses    = count( $wps_wgm_card['history'] );
					?>
					<li class="wps-wgm-mgc-card wps-wgm-mgc-status-<?php echo esc_attr( $wps_wgm_card['status'] ); ?>">
						<div class="wps-wgm-mgc-face">
							<div class="wps-wgm-mgc-face-top">
								<span class="wps-wgm-mgc-brand"><?php echo wp_kses( $wps_wgm_icon_gift, $wps_wgm_svg_tags ); ?><?php esc_html_e( 'Gift card', 'woo-gift-cards-lite' ); ?></span>
								<span class="wps-wgm-mgc-badge"><?php echo esc_html( $wps_wgm_status_labels[ $wps_wgm_card['status'] ] ); ?></span>
							</div>
							<div class="wps-wgm-mgc-balance">
								<span class="wps-wgm-mgc-amount"><?php echo wp_kses_post( wc_price( $wps_wgm_card['balance'] ) ); ?></span>
								<span class="wps-wgm-mgc-of">
									<?php
									/* translators: %s: original gift card amount. */
									echo wp_kses_post( sprintf( __( 'left of %s', 'woo-gift-cards-lite' ), wc_price( $wps_wgm_card['original'] ) ) );
									?>
								</span>
							</div>
							<div class="wps-wgm-mgc-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo esc_attr( $wps_wgm_percent ); ?>" aria-label="<?php esc_attr_e( 'Balance remaining', 'woo-gift-cards-lite' ); ?>">
								<span style="width: <?php echo esc_attr( $wps_wgm_percent ); ?>%;"></span>
							</div>
							<div class="wps-wgm-mgc-code-row">
								<code class="wps-wgm-mgc-code"><?php echo esc_html( $wps_wgm_code ); ?></code>
								<button type="button" class="wps-wgm-mgc-copy" data-code="<?php echo esc_attr( $wps_wgm_code ); ?>">
									<?php echo wp_kses( $wps_wgm_icon_copy, $wps_wgm_svg_tags ); ?>
									<span class="wps-wgm-mgc-copy-label"><?php esc_html_e( 'Copy', 'woo-gift-cards-lite' ); ?></span>
									<span class="screen-reader-text"><?php esc_html_e( 'gift card code', 'woo-gift-cards-lite' ); ?></span>
								</button>
							</div>
						</div>

						<div class="wps-wgm-mgc-body">
							<dl class="wps-wgm-mgc-meta">
								<div>
									<dt><?php echo $wps_wgm_is_purchased ? esc_html__( 'Sent to', 'woo-gift-cards-lite' ) : esc_html__( 'From', 'woo-gift-cards-lite' ); ?></dt>
									<dd><?php echo esc_html( $wps_wgm_is_purchased ? $wps_wgm_card['mail_to'] : $wps_wgm_card['from'] ); ?></dd>
								</div>
								<div>
									<dt><?php esc_html_e( 'Expires', 'woo-gift-cards-lite' ); ?></dt>
									<dd>
										<?php
										if ( $wps_wgm_card['expires'] ) {
											echo '<time datetime="' . esc_attr( $wps_wgm_card['expires']->date( 'c' ) ) . '">' . esc_html( wc_format_datetime( $wps_wgm_card['expires'] ) ) . '</time>';
										} else {
											esc_html_e( 'Never', 'woo-gift-cards-lite' );
										}
										?>
									</dd>
								</div>
								<?php if ( $wps_wgm_card['order'] ) : ?>
									<div>
										<dt><?php esc_html_e( 'Order', 'woo-gift-cards-lite' ); ?></dt>
										<dd><a href="<?php echo esc_url( $wps_wgm_card['order']->get_view_order_url() ); ?>">#<?php echo esc_html( $wps_wgm_card['order']->get_order_number() ); ?></a></dd>
									</div>
								<?php endif; ?>
							</dl>

							<?php if ( $wps_wgm_card['expiring_soon'] ) : ?>
								<p class="wps-wgm-mgc-alert">
									<?php echo wp_kses( $wps_wgm_icon_clock, $wps_wgm_svg_tags ); ?>
									<?php
									echo esc_html(
										$wps_wgm_card['days_left'] <= 0
											? __( 'Expires today', 'woo-gift-cards-lite' )
											/* translators: %d: number of days until the gift card expires. */
											: sprintf( _n( 'Expires in %d day', 'Expires in %d days', $wps_wgm_card['days_left'], 'woo-gift-cards-lite' ), $wps_wgm_card['days_left'] )
									);
									?>
								</p>
							<?php endif; ?>

							<?php if ( $wps_wgm_card['can_resend'] ) : ?>
								<div class="wps-wgm-mgc-actions">
									<button type="button" class="wps-wgm-mgc-btn wps-wgm-mgc-resend" data-coupon-id="<?php echo esc_attr( $wps_wgm_card['id'] ); ?>">
										<?php echo wp_kses( $wps_wgm_icon_mail, $wps_wgm_svg_tags ); ?>
										<span class="wps-wgm-mgc-resend-label"><?php esc_html_e( 'Resend email', 'woo-gift-cards-lite' ); ?></span>
									</button>
									<span class="wps-wgm-mgc-notice" role="status" aria-live="polite"></span>
								</div>
							<?php endif; ?>

							<details class="wps-wgm-mgc-history">
								<summary>
									<?php
									echo esc_html(
										$wps_wgm_uses
											/* translators: %s: number of times the gift card was used. */
											? sprintf( __( 'Usage history (%s)', 'woo-gift-cards-lite' ), number_format_i18n( $wps_wgm_uses ) )
											: __( 'Not used yet', 'woo-gift-cards-lite' )
									);
									?>
								</summary>
								<?php if ( $wps_wgm_uses ) : ?>
									<ol class="wps-wgm-mgc-timeline">
										<?php foreach ( $wps_wgm_card['history'] as $wps_wgm_use ) : ?>
											<li>
												<span class="wps-wgm-mgc-timeline-date">
													<?php echo esc_html( $wps_wgm_use['date'] ? wc_format_datetime( $wps_wgm_use['date'] ) : '' ); ?>
													<?php if ( $wps_wgm_use['order_url'] ) : ?>
														&middot; <a href="<?php echo esc_url( $wps_wgm_use['order_url'] ); ?>">#<?php echo esc_html( $wps_wgm_use['order_number'] ); ?></a>
													<?php endif; ?>
												</span>
												<span class="wps-wgm-mgc-timeline-amount">&minus;<?php echo wp_kses_post( wc_price( $wps_wgm_use['amount'], array( 'currency' => $wps_wgm_use['currency'] ) ) ); ?></span>
											</li>
										<?php endforeach; ?>
									</ol>
								<?php endif; ?>
							</details>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
		<?php
	endforeach;
endif;
?>
</div>
