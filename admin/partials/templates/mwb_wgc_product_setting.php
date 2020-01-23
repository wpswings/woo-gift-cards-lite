<?php
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( isset( $_POST['mwb_wgm_product_setting_save'] ) ) {

	if ( isset( $_REQUEST['mwb-wgc-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mwb-wgc-nonce'] ) ), 'mwb-wgc-nonce' ) ) {
		unset( $_POST['mwb_wgm_product_setting_save'] );

		if ( isset( $_POST['mwb_wgm_product_setting_exclude_product'] ) ) {
			$giftcard_exclude_products = sanitize_text_field( wp_unslash( $_POST['mwb_wgm_product_setting_exclude_product'] ) );
			$woo_ver = WC()->version;
			if ( $woo_ver < '3.0.0' ) {
				if ( isset( $giftcard_exclude_products ) && ! empty( $giftcard_exclude_products ) ) {
					update_option( 'mwb_wgm_product_setting_exclude_product_format', $giftcard_exclude_products );
				}
			} else {
				if ( isset( $giftcard_exclude_products ) && ! empty( $giftcard_exclude_products ) ) {
					$giftcard_exclude_product_string = '';
					foreach ( $giftcard_exclude_products as $giftcard_exclude_product ) {
						$giftcard_exclude_product_string .= $giftcard_exclude_product . ',';
					}
					$giftcard_exclude_product_string = rtrim( $giftcard_exclude_product_string, ',' );
					update_option( 'mwb_wgm_product_setting_exclude_product_format', $giftcard_exclude_product_string );
				}
			}
		} else {
			$_POST['mwb_wgm_product_setting_exclude_product'] = '';
			update_option( 'mwb_wgm_product_setting_exclude_product_format', '' );
		}
		if ( ! isset( $_POST['mwb_wgm_product_setting_exclude_category'] ) ) {
			$_POST['mwb_wgm_product_setting_exclude_category'] = '';
		}
		if ( ! isset( $_POST['mwb_wgm_product_setting_giftcard_ex_sale'] ) ) {
			$_POST['mwb_wgm_product_setting_giftcard_ex_sale'] = 'no';
		}

		do_action( 'mwb_wgm_product_setting_save' );
		$postdata = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
		foreach ( $postdata as $key => $data ) {
			update_option( $key, $data );
		}
		?>
		<div class="notice notice-success is-dismissible"> 
			<p><strong><?php esc_html_e( 'Settings saved', 'woocommerce_gift_cards_lite' ); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice', 'woocommerce_gift_cards_lite' ); ?></span>
			</button>
		</div>
		<?php
	}
}
$giftcard_exclude_product = get_option( 'mwb_wgm_product_setting_exclude_product', array() );
$giftcard_exclude_category = get_option( 'mwb_wgm_product_setting_exclude_category', array() );
$giftcard_ex_sale = get_option( 'mwb_wgm_product_setting_giftcard_ex_sale', false );
?>
<h3 class="mwb_wgm_overview_heading"><?php esc_html_e( 'Product Settings', 'woocommerce_gift_cards_lite' ); ?></h3>
<div class="mwb_wgm_table_wrapper">
	<table class="form-table mwb_wgm_product_setting">
		<tbody>

			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="mwb_wgm_product_setting_giftcard_ex_sale"><?php esc_html_e( 'Exclude Sale Items', 'woocommerce_gift_cards_lite' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<?php
					$attribute_description = __( 'Check this box if the Giftcard Coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are no sale items in the cart.', 'woocommerce_gift_cards_lite' );
					echo wp_kses_post( wc_help_tip( $attribute_description ) );
					?>
					<label for="mwb_wgm_product_setting_giftcard_ex_sale">
						<input type="checkbox" <?php echo esc_attr( $giftcard_ex_sale == 'yes' ) ? "checked='checked'" : ''; ?> name="mwb_wgm_product_setting_giftcard_ex_sale" id="mwb_wgm_product_setting_giftcard_ex_sale" class="input-text" value="yes"> <?php esc_html_e( 'Enable to exclude Sale Items', 'woocommerce_gift_cards_lite' ); ?>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="mwb_wgm_product_setting_exclude_product"><?php esc_html_e( 'Exclude Products', 'woocommerce_gift_cards_lite' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<?php
					$attribute_description = __( 'Products which must not be in the cart to use Giftcard coupon or, for "Product Discounts", which products are not discounted.', 'woocommerce_gift_cards_lite' );
					echo wp_kses_post( wc_help_tip( $attribute_description ) );
					$woo_ver = WC()->version;
					if ( $woo_ver < '3.0.0' ) {
						?>
						<p class="form-field">
							<input type="hidden" class="wc-product-search" data-multiple="true" style="width: 50%;" name="mwb_wgm_product_setting_exclude_product" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce_gift_cards_lite' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-selected="
							<?php
							$product_ids = array_filter( array_map( 'absint', explode( ',', $giftcard_exclude_product ) ) );
							$json_ids    = array();
							if ( isset( $product_ids ) && ! empty( $product_ids ) ) {
								foreach ( $product_ids as $product_id ) {
									$product = wc_get_product( $product_id );
									if ( is_object( $product ) ) {
										$json_ids[ $product_id ] = wp_kses_post( $product->get_formatted_name() );
									}
								}
							}
							echo esc_attr( json_encode( $json_ids ) );
							?>
							" value="<?php echo esc_attr( implode( ',', array_keys( $json_ids ) ) ); ?>" />
						</p>
						<?php
					} else {
						?>
						<label for="mwb_wgm_product_setting_exclude_product">
							<select class="wc-product-search" multiple="multiple" style="width: 50%;" name="mwb_wgm_product_setting_exclude_product[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce_gift_cards_lite' ); ?>" data-action="woocommerce_json_search_products_and_variations" id="mwb_wgm_product_setting_exclude_product" >
								<?php
								if ( isset( $giftcard_exclude_product ) && ! empty( $giftcard_exclude_product ) ) {
									foreach ( $giftcard_exclude_product as $pro_id ) {
										$product      = wc_get_product( $pro_id );
										$product_title = $product->get_formatted_name();
										echo '<option value="' . esc_attr( $pro_id ) . '" selected="selected">' . esc_html( $product_title ) . '</option>';
									}
								}
								?>
							</select>
						</label>
						<?php
					}
					?>

				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="mwb_wgm_product_setting_exclude_category"><?php esc_html_e( 'Exclude Product Category', 'woocommerce_gift_cards_lite' ); ?></label>
				</th>
				<td class="forminp forminp-text">
					<?php
					$attribute_description = __( 'Product must not be in this category for the Giftcard coupon to remain valid or, for "Product Discounts", products in these categories will not be discounted.', 'woocommerce_gift_cards_lite' );
					echo wp_kses_post( wc_help_tip( $attribute_description ) );
					?>
					<label for="mwb_wgm_product_setting_exclude_category">
						<select id="mwb_wgm_product_setting_exclude_category" multiple="multiple" name="mwb_wgm_product_setting_exclude_category[]">
							<?php
							$args = array( 'taxonomy' => 'product_cat' );
							$categories = get_terms( $args );
							if ( isset( $categories ) && ! empty( $categories ) ) {
								foreach ( $categories as $category ) {
									$catid = $category->term_id;
									$catname = $category->name;
									$catselect = '';

									if ( is_array( $giftcard_exclude_category ) && in_array( $catid, $giftcard_exclude_category ) ) {
										$catselect = "selected='selected'";
									}
									?>
									<option value="<?php echo esc_attr( $catid ); ?>" <?php echo esc_attr( $catselect ); ?>><?php echo esc_attr( $catname ); ?></option>
									<?php
								}
							}
							?>
						</select>	
					</label>						
				</td>
			</tr>
			<?php
			do_action( 'mwb_wgm_product_setting' );
			?>
		</tbody>
	</table>
</div>
<p class="submit">
	<input type="submit" value="<?php esc_attr_e( 'Save changes', 'woocommerce_gift_cards_lite' ); ?>" class="button-primary woocommerce-save-button" name="mwb_wgm_product_setting_save">
</p>

<div class="clear"></div>

