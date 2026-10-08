<?php
/**
 * My Account > My Gift Cards.
 *
 * This template can be overridden by copying it to yourtheme/woo-gift-cards-lite/myaccount/wps-wgm-my-gift-cards.php.
 *
 * @package woo-gift-cards-lite
 * @since   3.2.13
 *
 * @var array[] $received  Cards sent to the current user.
 * @var array[] $purchased Cards the current user bought for someone else.
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

$wps_wgm_sections = array(
	'received'  => array(
		'title' => __( 'Gift cards you received', 'woo-gift-cards-lite' ),
		'cards' => $received,
	),
	'purchased' => array(
		'title' => __( 'Gift cards you sent', 'woo-gift-cards-lite' ),
		'cards' => $purchased,
	),
);

if ( empty( $received ) && empty( $purchased ) ) : ?>
	<div class="woocommerce-info">
		<?php esc_html_e( 'You don\'t have any gift cards yet.', 'woo-gift-cards-lite' ); ?>
		<a class="woocommerce-Button button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Browse products', 'woo-gift-cards-lite' ); ?></a>
	</div>
	<?php
	return;
endif;

foreach ( $wps_wgm_sections as $wps_wgm_section_key => $wps_wgm_section ) :
	if ( empty( $wps_wgm_section['cards'] ) ) {
		continue;
	}
	$wps_wgm_is_purchased = 'purchased' === $wps_wgm_section_key;
	?>
	<section class="wps-wgm-mgc-section wps-wgm-mgc-<?php echo esc_attr( $wps_wgm_section_key ); ?>">
		<h3><?php echo esc_html( $wps_wgm_section['title'] ); ?></h3>
		<table class="woocommerce-orders-table shop_table shop_table_responsive my_account_orders wps-wgm-mgc-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Card', 'woo-gift-cards-lite' ); ?></th>
					<th><?php esc_html_e( 'Balance', 'woo-gift-cards-lite' ); ?></th>
					<th><?php esc_html_e( 'Expires', 'woo-gift-cards-lite' ); ?></th>
					<th><?php esc_html_e( 'Status', 'woo-gift-cards-lite' ); ?></th>
					<th><?php echo $wps_wgm_is_purchased ? esc_html__( 'Sent to', 'woo-gift-cards-lite' ) : esc_html__( 'From', 'woo-gift-cards-lite' ); ?></th>
					<th><span class="screen-reader-text"><?php esc_html_e( 'Actions', 'woo-gift-cards-lite' ); ?></span></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $wps_wgm_section['cards'] as $wps_wgm_card ) : ?>
					<tr class="wps-wgm-mgc-row wps-wgm-mgc-status-<?php echo esc_attr( $wps_wgm_card['status'] ); ?>">
						<td data-title="<?php esc_attr_e( 'Card', 'woo-gift-cards-lite' ); ?>">
							<code class="wps-wgm-mgc-code"><?php echo esc_html( strtoupper( $wps_wgm_card['code'] ) ); ?></code>
							<button type="button" class="wps-wgm-mgc-copy" data-code="<?php echo esc_attr( strtoupper( $wps_wgm_card['code'] ) ); ?>"><?php esc_html_e( 'Copy', 'woo-gift-cards-lite' ); ?></button>
							<?php if ( $wps_wgm_card['order'] ) : ?>
								<br><small>
									<?php
									printf(
										/* translators: %s: order number link. */
										esc_html__( 'Bought in order %s', 'woo-gift-cards-lite' ),
										'<a href="' . esc_url( $wps_wgm_card['order']->get_view_order_url() ) . '">#' . esc_html( $wps_wgm_card['order']->get_order_number() ) . '</a>'
									);
									?>
								</small>
							<?php endif; ?>
						</td>
						<td data-title="<?php esc_attr_e( 'Balance', 'woo-gift-cards-lite' ); ?>">
							<strong><?php echo wp_kses_post( wc_price( $wps_wgm_card['balance'] ) ); ?></strong>
							<?php if ( $wps_wgm_card['original'] > 0 && $wps_wgm_card['original'] !== $wps_wgm_card['balance'] ) : ?>
								<small>
									<?php
									/* translators: %s: original gift card amount. */
									echo wp_kses_post( sprintf( __( 'of %s', 'woo-gift-cards-lite' ), wc_price( $wps_wgm_card['original'] ) ) );
									?>
								</small>
							<?php endif; ?>
						</td>
						<td data-title="<?php esc_attr_e( 'Expires', 'woo-gift-cards-lite' ); ?>">
							<?php
							if ( $wps_wgm_card['expires'] ) {
								echo '<time datetime="' . esc_attr( $wps_wgm_card['expires']->date( 'c' ) ) . '">' . esc_html( wc_format_datetime( $wps_wgm_card['expires'] ) ) . '</time>';
							} else {
								esc_html_e( 'Never', 'woo-gift-cards-lite' );
							}
							?>
						</td>
						<td data-title="<?php esc_attr_e( 'Status', 'woo-gift-cards-lite' ); ?>">
							<mark class="wps-wgm-mgc-badge"><?php echo esc_html( $wps_wgm_status_labels[ $wps_wgm_card['status'] ] ); ?></mark>
						</td>
						<td data-title="<?php echo $wps_wgm_is_purchased ? esc_attr__( 'Sent to', 'woo-gift-cards-lite' ) : esc_attr__( 'From', 'woo-gift-cards-lite' ); ?>">
							<?php echo esc_html( $wps_wgm_is_purchased ? $wps_wgm_card['mail_to'] : $wps_wgm_card['from'] ); ?>
						</td>
						<td class="wps-wgm-mgc-actions">
							<?php if ( $wps_wgm_card['can_resend'] ) : ?>
								<button type="button" class="woocommerce-button button wps-wgm-mgc-resend" data-coupon-id="<?php echo esc_attr( $wps_wgm_card['id'] ); ?>"><?php esc_html_e( 'Resend email', 'woo-gift-cards-lite' ); ?></button>
								<span class="wps-wgm-mgc-notice" role="status" aria-live="polite"></span>
							<?php endif; ?>
						</td>
					</tr>
					<tr class="wps-wgm-mgc-history-row">
						<td colspan="6">
							<details class="wps-wgm-mgc-history">
								<summary>
									<?php
									$wps_wgm_uses = count( $wps_wgm_card['history'] );
									echo esc_html(
										$wps_wgm_uses
											/* translators: %d: number of times the gift card was used. */
											? sprintf( _n( 'Usage history (%d use)', 'Usage history (%d uses)', $wps_wgm_uses, 'woo-gift-cards-lite' ), $wps_wgm_uses )
											: __( 'Usage history (not used yet)', 'woo-gift-cards-lite' )
									);
									?>
								</summary>
								<?php if ( $wps_wgm_uses ) : ?>
									<ul>
										<?php foreach ( $wps_wgm_card['history'] as $wps_wgm_use ) : ?>
											<li>
												<?php echo esc_html( $wps_wgm_use['date'] ? wc_format_datetime( $wps_wgm_use['date'] ) : '' ); ?>
												&mdash;
												<?php echo wp_kses_post( wc_price( $wps_wgm_use['amount'], array( 'currency' => $wps_wgm_use['currency'] ) ) ); ?>
												<?php if ( $wps_wgm_use['order_url'] ) : ?>
													<?php
													printf(
														/* translators: %s: order number link. */
														esc_html__( 'on order %s', 'woo-gift-cards-lite' ),
														'<a href="' . esc_url( $wps_wgm_use['order_url'] ) . '">#' . esc_html( $wps_wgm_use['order_number'] ) . '</a>'
													);
													?>
												<?php endif; ?>
											</li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</details>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</section>
<?php endforeach; ?>
