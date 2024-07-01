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
<div class="wps_wgm_content_template_pro_tag">
	<h3 class="wps_wgm_overview_heading wps_wgm_heading">Export Coupons</h3>
	<div class="wps_wgm_import_giftcoupons wps_wgm_export_giftcoupons">
		<table class="form-table wps_wgm_general_setting">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="wps_wugc_export_coupon">Export Online Coupons Details</label>
					</th>
					<td class="forminp forminp-text">
						<?php
							$attribute_description = __( 'You can export CSV report of all the generated coupons from the orders.', 'woo-gift-cards-lite' );
							echo wp_kses_post( wc_help_tip( $attribute_description ) );
						?>
						<a href="admin.php?page=wps-wgc-setting-lite&amp;wps_wugc_export_csv=wps_woo_gift_card_report"
							class="wps_wgm_small_button" target="_blank">Export CSV </a>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="wps_wugc_export_coupon">Export Offline Coupons Details</label>
					</th>
					<td class="forminp forminp-text">
						<?php
							$attribute_description = __( 'You can export all the offline generated coupons from the orders.', 'woo-gift-cards-lite' );
							echo wp_kses_post( wc_help_tip( $attribute_description ) );
						?> <a
							href="admin.php?page=wps-wgc-setting-lite&amp;wps_wugc_export_csv=wps_woo_offline_gift_card_report"
							class="wps_wgm_small_button" target="_blank">Export CSV </a>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="wps_wgm_import_giftcoupons">
		<h3 class="wps_wgm_heading">Import Gift Coupons</h3>
		<table class="form-table wps_wgm_general_setting">
			<tbody>
				<tr valign="top">
					<td colspan="3" class="wps_wgm_instructions_tabledata">
						<h3>Instructions</h3>
						<p> 1- Import Gift Coupons for sending your pre-defined codes rather than system generated one,
							we do not provide the Price field here as it will take the value of Gift card on purchasing.
							You need to choose a CSV file and click Import</p>
						<p>2- CSV for Gift Coupons must have 3 columns in this order ( Coupon Code, Expiry Date, Usage
							Limit. Also first row must be the respective headings ) </p>
						<p>3- You may leave the Expiry Date field empty if you want to set your gift coupons with "No
							Expiry". The Expiry Date should be in days, which will be calculated after it will get
							assigned to a particular product </p>
					</td>
				</tr>
				<tr>
					<th>Choose a CSV file: </th>
					<td>
						<input class="wps_wgm_csv_custom_giftcoupon_import" name="giftcoupon_csv_import"
							id="giftcoupon_csv_import" type="file" size="25" value="" aria-required="true">

						<input type="hidden" value="134217728" name="max_file_size">
						<small>Maximum size:128 MB</small>
					</td>
					<td>
						<a
							href="<?php echo esc_attr( WPS_WGC_URL ); ?> admin/uploads/wps_wgm_custom_gift_sample.csv">Export
							Demo CSV <span class="wps_sample_export_org"><img
									src="<?php echo esc_attr( WPS_WGC_URL ); ?>/assets/images/download.png"></span>
						</a>
					</td>
				</tr>
				<tr>
					<td>
						<p><input name="wps_wgm_csv_custom_giftcoupon_import" id="wps_wgm_csv_custom_giftcoupon_import"
								class="wps_wgm_small_button" type="submit" value="Import"></p>
					</td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="wps_wgm_import_giftproducts">
		<h3 class="wps_wgm_heading">Import Gift Products</h3>
		<table class="form-table wps_wgm_general_setting">
			<tbody>
				<tr valign="top">
					<td colspan="3" class="wps_wgm_instructions_tabledata">
						<h3>Instructions</h3>
						<p> 1- Import Gift Products along with your own custom codes. You need to choose a CSV file and
							click Import</p>
						<p>2- CSV for Gift Products must have 6 columns in this order (Product Image URL, Giftcard Name,
							Giftcard Price, Giftcard Codes, Giftcard Expiry, Giftcard Template Name. Also first row must
							have the respective headings ) </p>
						<p>3- You may leave Giftcard Expiry field empty if you want to set your "Giftcard Expiry" with
							"No Expiry" &amp; also you may leave blank "Giftcard Codes" if you want to assign that
							product with system generated code </p>
						<p>4- Gift card Expiry should be in Days(i.e 2 or 3). It will be calculated after purchasing
						</p>
					</td>
				</tr>
				<tr>
					<th>Choose a CSV file: </th>
					<td>
						<input class="wps_wgm_csv_custom_giftproduct_import" name="giftprod_csv_import"
							id="giftprod_csv_import" type="file" size="25" value="" aria-required="true">

						<input type="hidden" value="134217728" name="max_file_size">
						<small>Maximum size:128 MB</small>
					</td>
					<td>
						<a
							href="<?php echo esc_attr( WPS_WGC_URL ); ?>/admin/uploads/wps_wgm_giftproduct_sample.csv">Export
							Demo CSV <span class="wps_sample_export_org"><img
									src="<?php echo esc_attr( WPS_WGC_URL ); ?>/assets/images/download.png"></span>
						</a>
					</td>
				</tr>
				<tr>
					<td>
						<p><input name="wps_wgm_csv_custom_giftproduct_import"
								id="wps_wgm_csv_custom_giftproduct_import" class="wps_wgm_small_button" type="submit"
								value="Import"></p>
					</td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
