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
	
	<div class="wps_wgm_overview_content">
	 
		<h3 class="wps_wgm_overview_heading">
			<?php esc_html_e( 'Connect With Us and Explore More About Ultimate Gift Cards For WooCommerce', 'woo-gift-cards-lite' ); ?>
		</h3>
		
		<p><?php esc_html_e( 'Ultimate Gift Cards For WooCommerce is the plugin that allows merchants (admin) to manage store with digital gifting solutions like this. Here the merchant can create gifts cards according to his desires and wishes after selection of the price selection. This digital certificate e-solution comes with ample number benefits like capable to increase sales, encourage an easy and desire gifting solution for your customers, initiate e-gifting via emails. ', 'woo-gift-cards-lite' ); ?>
		</p>
	</div>
	<?php
	if ( ! wps_uwgc_pro_active() ) {
		?>
		<div class="wps_wgm_video_wrapper">
			<iframe height="411" src="https://www.youtube.com/embed/g6JLA3ewph8?si=mzCftUBqh8AJFit2" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
		<?php
	} else {
		?>
		<div class="wps_wgm_video_wrapper">
			<iframe height="411" src="https://www.youtube.com/embed/zxMqtV-HJLQ?si=WhZtX5-JCFCEG2fT" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
		<?php
	}


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

</div>
		<?php
	}
