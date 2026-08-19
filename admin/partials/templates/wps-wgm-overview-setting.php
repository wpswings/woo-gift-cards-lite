<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="wps_wgm_table_wrapper wps_wgm_overview-wrapper">
	<?php
	$wgm_support_link = 'https://wpswings.com/submit-query/?utm_source=wpswings-giftcards-support&utm_medium=giftcards-org-backend&utm_campaign=support';
	$wgm_overview_cards = array(
		array(
			'image'       => WPS_WGC_URL . 'assets/images/featur1.png',
			'image_alt'   => __( 'Gift card templates', 'woo-gift-cards-lite' ),
			'title'       => __( 'Flexible gift card templates', 'woo-gift-cards-lite' ),
			'description' => __( 'Launch branded gifting faster with editable templates, custom messaging, and product-ready gift card designs.', 'woo-gift-cards-lite' ),
		),
		array(
			'image'       => WPS_WGC_URL . 'assets/images/wps-feature2.png',
			'image_alt'   => __( 'Redeem and recharge gift cards', 'woo-gift-cards-lite' ),
			'title'       => __( 'Redeem and recharge flows', 'woo-gift-cards-lite' ),
			'description' => __( 'Let shoppers redeem balances with confidence and support reload-ready gift card workflows from one plugin.', 'woo-gift-cards-lite' ),
		),
		array(
			'image'       => WPS_WGC_URL . 'assets/images/featur3.png',
			'image_alt'   => __( 'QR code and barcode security', 'woo-gift-cards-lite' ),
			'title'       => __( 'Secure coupon delivery', 'woo-gift-cards-lite' ),
			'description' => __( 'Strengthen digital gifting with QR and barcode support for easier validation and safer coupon sharing.', 'woo-gift-cards-lite' ),
		),
		array(
			'image'       => WPS_WGC_URL . 'assets/images/featur6.png',
			'image_alt'   => __( 'Scheduled gift cards', 'woo-gift-cards-lite' ),
			'title'       => __( 'Scheduled gifting', 'woo-gift-cards-lite' ),
			'description' => __( 'Deliver gift cards on birthdays, holidays, or campaign launch dates with scheduled email-based delivery.', 'woo-gift-cards-lite' ),
		),
		array(
			'image'       => WPS_WGC_URL . 'assets/images/featur5.png',
			'image_alt'   => __( 'Gift card reporting', 'woo-gift-cards-lite' ),
			'title'       => __( 'Reporting and performance', 'woo-gift-cards-lite' ),
			'description' => __( 'Track gifting activity, coupon usage, and store-level performance to understand how gift cards drive revenue.', 'woo-gift-cards-lite' ),
		),
	);
	?>

	<div class="wps-wgm-overview">
		<div class="wps-wgm-overview__hero">
			<div class="wps-wgm-overview__icon"><?php esc_html_e( 'GC', 'woo-gift-cards-lite' ); ?></div>
			<span class="wps-wgm-overview__eyebrow"><?php esc_html_e( 'Overview', 'woo-gift-cards-lite' ); ?></span>
			<h2><?php esc_html_e( 'Digital gifting workflows built for WooCommerce stores', 'woo-gift-cards-lite' ); ?></h2>
			<p><?php esc_html_e( 'Ultimate Gift Cards for WooCommerce helps merchants launch branded gift cards, automate delivery, manage redemption, and build repeat-purchase flows through flexible gifting experiences.', 'woo-gift-cards-lite' ); ?></p>
		</div>

		<div class="wps-wgm-overview__heading-row">
			<span><?php esc_html_e( 'Top features of this plugin', 'woo-gift-cards-lite' ); ?></span>
		</div>

		<div class="wps-wgm-overview__grid">
			<?php foreach ( $wgm_overview_cards as $wgm_overview_card ) : ?>
				<div class="wps-wgm-overview-card">
					<div class="wps-wgm-overview-card__media">
						<img src="<?php echo esc_url( $wgm_overview_card['image'] ); ?>" alt="<?php echo esc_attr( $wgm_overview_card['image_alt'] ); ?>">
					</div>
					<h3><?php echo esc_html( $wgm_overview_card['title'] ); ?></h3>
					<p><?php echo esc_html( $wgm_overview_card['description'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="wps-wgm-overview__cta">
			<div>
				<strong><?php esc_html_e( 'Need help with gifting setup?', 'woo-gift-cards-lite' ); ?></strong>
				<p><?php esc_html_e( 'Reach out for support with gift card configuration, delivery setup, and advanced gifting workflows for your store.', 'woo-gift-cards-lite' ); ?></p>
			</div>
			<div class="wps-wgm-overview__cta-actions">
				<a href="<?php echo esc_url( $wgm_support_link ); ?>" target="_blank" class="wps-wgm-overview__button wps-wgm-overview__button--secondary"><?php esc_html_e( 'Contact Support', 'woo-gift-cards-lite' ); ?></a>
			</div>
		</div>
	</div>

	<?php
	if ( ! is_plugin_active( 'giftware/giftware.php' ) ) {


		?>


	<div class="wps-gift-card-pro-tmplt">
		<div class="wps-gift-card-pro-tmplt-inner">
			<span class="h4"><?php esc_html_e( 'Pro vs Free Plugin Benefits', 'woo-gift-cards-lite' ); ?></span>
			<table class="table wps-table-wrapper" style="border: 1px solid #e5e4e3; height: 884px;" width="">
				<thead>
				  
					<tr>
						<th><?php esc_html_e( 'Features', 'woo-gift-cards-lite' ); ?></th>
						<th><?php esc_html_e( 'Free Version', 'woo-gift-cards-lite' ); ?></th>
						<th><?php esc_html_e( 'Pro Version', 'woo-gift-cards-lite' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Gift Card Message Customization', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Send Gift Vouchers via Email', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Gift Card Email Notifications', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Multiple Gift Certificate Usage', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Redeem Gift Coupons At Retail Stores', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( '6 Pricing Options', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Product And Category Exclusion', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Dynamic Coupon Length', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Min/Max Amount Range', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'WPML Multilingual Support', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Gift Voucher Usage Limit', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Disable Coupon For Gift Card Products', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Minimum Limit of User Gift Card Price', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-check"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Group Gift Cards', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Recharge Coupon Codes', 'woo-gift-cards-lite' ); ?> </span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Display Coupon Code Along With Barcode/QR Code', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Send Gift Vouchers to Multiple Recipients', 'woo-gift-cards-lite' ); ?> </span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Range With Selectable Pricing', 'woo-gift-cards-lite' ); ?> </span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Display Recommended Products On Gift cards', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Physical Gift Cards', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Remove Fields from Gift Cards Product Page', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Customizable Gift Card', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'SMS notifications via Twilio', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'WhatsApp Sharing', 'woo-gift-cards-lite' ); ?> </span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Import/Export Online/Offline Coupons', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'WooCommerce Gift Card Reporting', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'QR Code/BARCode Security', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Gift Card Scheduling', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Gift Card Product Discounts', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'PDF Template Feature', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Balance Checker', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Thank You Order Coupons', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Advanced Delivery Method Settings', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
					<tr>
						<td><span style="font-weight: 400;"><?php esc_html_e( 'Purchase Products as Gift Cards', 'woo-gift-cards-lite' ); ?></span></td>
						<td>
							<div class="fas fa-times"></div>
						</td>
						<td>
							<div class="fas fa-check"></div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

		<?php
	}
	?>
</div>
