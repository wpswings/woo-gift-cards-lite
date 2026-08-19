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
	<h3 class="wps_wgm_heading">Import Offline Coupons</h3>
	<div class="wps_wgm_import_giftcoupons">
		<table class="form-table wps_wgm_general_setting">
			<tbody>
				<tr valign="top">
					<td colspan="3" class="wps_wgm_instructions_tabledata">
						<h3>Instructions</h3>
						<p>1- It just provides you the way from where you can import your coupons in bulk and can
							provide them Manually to your Customers. You need to choose a CSV file and click Import</p>
						<p>2- CSV for Offline Coupons must have 4 columns in this order ( Coupon Code, Expiry Date,
							Usage Limit, Price. Also first row must be the respective headings ) </p>
						<p>3- You may leave the Expiry Date field empty if you want to set your gift coupons with "No
							Expiration". The Expiry Date format must be in (YYYY-MM-DD), also may leave Usage Limit for
							setting this for "No Usage Limit" </p>
					</td>
				</tr>
				<tr>
					<th>Choose a CSV file: </th>
					<td>
						<input class="wps_wgm_csv_offlinecoupon_import" name="offlinecoupon_csv_import"
							id="offlinecoupon_csv_import" type="file" size="25" value="" aria-required="true">

						<input type="hidden" value="134217728" name="max_file_size">
						<small>Maximum size:128 MB</small>
					</td>
					<td>
						<a
							href="<?php echo esc_attr( WPS_WGC_URL ); ?>admin/uploads/wps_wgm_offline_coupon_import.csv">Export
							Demo CSV <span class="wps_sample_export_org"><img
									src="<?php echo esc_attr( WPS_WGC_URL ); ?>assets/images/download.png"></span>
						</a>
					</td>
				</tr>
				<tr>
					<td>
						<p><input name="wps_wgm_csv_offlinecoupon_import" id="wps_wgm_csv_offlinecoupon_import"
								class="button-primary woocommerce-save-button" type="submit" value="Import"></p>
					</td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
	<h3 class="wps_wgm_heading" id="wps_wgm_add_new_card_heading">Offline Gift Card List</h3><br>
	<table class="wps_gift-ware__offline-table form-table">
		<tbody>
			<tr valign="top">
				<td class="forminput">

					<h3>Instructions</h3>
					<p>1- Import Offline Gift Card CSV for sending offline Gift Cards. You need to choose a CSV file and
						click Upload</p>
					<p>2- CSV for Offline Gift Card must have 5 columns in this order ( To, From, Message, Price,
						Schedule Date. Also first row must be the respective headings ) </p>
					<p>3- You may leave Schedule Date field empty if you want to send Gift Card today. The Schedule Date
						format must be in (YYYY-MM-DD) </p>
				</td>
			</tr>
			<tr>
				<td>
					<table class="wps_gift-ware__offline widefat">
						<tbody>
							<tr>
								<th scope="row" class="titledesc">
									<label for="wps_wgm_offline_gift_template">Gift card</label>
								</th>
								<td class="forminp forminp-text">
										<?php
											$attribute_description = __( 'Select the Gift card product.', 'woo-gift-cards-lite' );
											echo wp_kses_post( wc_help_tip( $attribute_description ) );
										?><label
										for="wps_wgm_offline_gift_template">
										<select name="wps_wgm_offline_gift_template" id="wps_wgm_offline_gift_template"
											class="input-text wps_wgm_new_woo_ver_style_select">
											<option value="28">Gift card product</option>
										</select>

									</label>
								</td>
							</tr>
							<tr>
								<th>Choose a CSV file: </th>
								<td>
									<input class="wps_wgm_csv_custom_import" name="csv_import" id="csv_import"
										type="file" size="25" value="" aria-required="true">

									<input type="hidden" value="134217728" name="max_file_size">
									<small>Maximum size:128 MB</small>
								</td>
								<td>
									<a
										href="<?php echo esc_attr( WPS_WGC_URL ); ?>admin/uploads/wps_wgm_gift_card_sample.csv">Export
										Demo CSV <span class="wps_sample_export_org"><img
												src="<?php echo esc_attr( WPS_WGC_URL ); ?>assets/images/download.png"></span>

									</a>
								</td>
							</tr>
							<tr>
								<td>
									<p><input name="wps_wgm_csv_custom_import" id="wps_wgm_import_button"
											class="button-primary woocommerce-save-button" type="submit" value="Import">
									</p>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<a id="wps_wgm_add_new_card_button"
		class="wps_wgm_add_new_card_button_org page-title-action button button-primary button-large"
		href="#">Add
		New</a>

	<input type="hidden" name="page" value="ttest_list_table">
	<input type="hidden" id="_wpnonce" name="_wpnonce" value="74f3d0fbf8"><input type="hidden" name="_wp_http_referer"
		value="/wp-admin/edit.php?post_type=giftcard&amp;page=wps-wgc-setting-lite&amp;tab=offline-giftcard">
	<div class="tablenav top">

		<div class="tablenav-pages no-pages"><span class="displaying-num">0 items</span>
			<span class="pagination-links"><span class="tablenav-pages-navspan button disabled"
					aria-hidden="true">«</span>
				<span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
				<span class="paging-input"><label for="current-page-selector" class="screen-reader-text">Current
						Page</label><input class="current-page" id="current-page-selector" type="text" name="paged"
						value="1" size="1" aria-describedby="table-paging"><span class="tablenav-paging-text"> of <span
							class="total-pages">0</span></span></span>
				<a class="next-page button"
					href="#"><span
						class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
				<a class="last-page button"
					href="#"><span
						class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a></span>
		</div>
		<br class="clear">
	</div>
	<table class="wp-list-table widefat fixed striped table-view-list giftcard_page_wps-wgc-setting-lite">
		<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column"><label class="label-covers-full-cell"
						for="cb-select-all-1"><span class="screen-reader-text">Select All</span></label><input
						id="cb-select-all-1" type="checkbox"></td>
				<th scope="col" id="id" class="manage-column column-id column-primary sortable desc"><a
						href="#"><span>ID</span><span
							class="sorting-indicators"><span class="sorting-indicator asc"
								aria-hidden="true"></span><span class="sorting-indicator desc"
								aria-hidden="true"></span></span> <span class="screen-reader-text">Sort
							ascending.</span></a></th>
				<th scope="col" id="date" class="manage-column column-date sortable desc"><a
						href="#"><span>Order
							Date</span><span class="sorting-indicators"><span class="sorting-indicator asc"
								aria-hidden="true"></span><span class="sorting-indicator desc"
								aria-hidden="true"></span></span> <span class="screen-reader-text">Sort
							ascending.</span></a></th>
				<th scope="col" id="to" class="manage-column column-to">To</th>
				<th scope="col" id="from" class="manage-column column-from">From</th>
				<th scope="col" id="message" class="manage-column column-message">Message</th>
				<th scope="col" id="amount" class="manage-column column-amount">Price</th>
				<th scope="col" id="coupon" class="manage-column column-coupon">Gift card Coupon</th>
				<th scope="col" id="schedule" class="manage-column column-schedule">Schedule Date</th>
				<th scope="col" id="resend" class="manage-column column-resend">Resend</th>
			</tr>
		</thead>

		<tbody id="the-list">
			<tr class="no-items">
				<td class="colspanchange" colspan="10">No items found.</td>
			</tr>
		</tbody>

		<tfoot>
			<tr>
				<td class="manage-column column-cb check-column"><label class="label-covers-full-cell"
						for="cb-select-all-2"><span class="screen-reader-text">Select All</span></label><input
						id="cb-select-all-2" type="checkbox"></td>
				<th scope="col" class="manage-column column-id column-primary sortable desc"><a
						href="#"><span>ID</span><span
							class="sorting-indicators"><span class="sorting-indicator asc"
								aria-hidden="true"></span><span class="sorting-indicator desc"
								aria-hidden="true"></span></span> <span class="screen-reader-text">Sort
							ascending.</span></a></th>
				<th scope="col" class="manage-column column-date sortable desc"><a
						href="#"><span>Order
							Date</span><span class="sorting-indicators"><span class="sorting-indicator asc"
								aria-hidden="true"></span><span class="sorting-indicator desc"
								aria-hidden="true"></span></span> <span class="screen-reader-text">Sort
							ascending.</span></a></th>
				<th scope="col" class="manage-column column-to">To</th>
				<th scope="col" class="manage-column column-from">From</th>
				<th scope="col" class="manage-column column-message">Message</th>
				<th scope="col" class="manage-column column-amount">Price</th>
				<th scope="col" class="manage-column column-coupon">Gift card Coupon</th>
				<th scope="col" class="manage-column column-schedule">Schedule Date</th>
				<th scope="col" class="manage-column column-resend">Resend</th>
			</tr>
		</tfoot>

	</table>
	<div class="tablenav bottom">

		<div class="tablenav-pages no-pages"><span class="displaying-num">0 items</span>
			<span class="pagination-links"><span class="tablenav-pages-navspan button disabled"
					aria-hidden="true">«</span>
				<span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
				<span class="screen-reader-text">Current Page</span><span id="table-paging" class="paging-input"><span
						class="tablenav-paging-text">1 of <span class="total-pages">0</span></span></span>
				<a class="next-page button"
					href="#"><span
						class="screen-reader-text">Next page</span><span aria-hidden="true">›</span></a>
				<a class="last-page button"
					href="#"><span
						class="screen-reader-text">Last page</span><span aria-hidden="true">»</span></a></span>
		</div>
		<br class="clear">
	</div>

</div>
