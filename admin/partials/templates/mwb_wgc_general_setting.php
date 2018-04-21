<?php 
/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if( isset( $_POST['mwb_wgm_general_setting_save'] ) )
{	
	if( wp_verify_nonce( $_REQUEST['mwb-wgc-nonce'], 'mwb-wgc-nonce' ) ){

		unset($_POST['mwb_wgm_general_setting_save']);
		if(!isset($_POST['mwb_wgm_general_setting_enable'])){
			$_POST['mwb_wgm_general_setting_enable'] = 'off';
		}
		if(!isset($_POST['mwb_wgm_general_setting_tax_cal_enable'])){
			$_POST['mwb_wgm_general_setting_tax_cal_enable'] = 'off';
		}
		if(!isset($_POST['mwb_wgm_general_setting_shop_page_enable'])){
			$_POST['mwb_wgm_general_setting_shop_page_enable'] = 'off';
		}
		if(!isset($_POST['mwb_wgm_general_setting_giftcard_individual_use'])){
			$_POST['mwb_wgm_general_setting_giftcard_individual_use'] = 'no';
		}
		if(!isset($_POST['mwb_wgm_general_setting_giftcard_freeshipping'])){
			$_POST['mwb_wgm_general_setting_giftcard_freeshipping'] = 'no';
		}
		if(!isset($_POST['mwb_wgm_general_setting_giftcard_applybeforetx'])){
			$_POST['mwb_wgm_general_setting_giftcard_applybeforetx'] = 'no';
		}
		if(!isset($_POST['mwb_wgm_general_setting_giftcard_minspend'])){
			$_POST['mwb_wgm_general_setting_giftcard_minspend'] = '';
		}
		do_action('mwb_wgm_general_setting_save');
		$data_1 = "";
		$postdata = $_POST;
		foreach($postdata as $key=>$data)
		{
			if(isset($data) && $data != null)
			{
				$data = sanitize_text_field($data);
				update_option($key, $data);		
			}
			elseif ($data == null) {
				delete_option($key, $data);
			}		
		}
		?>
		<div class="notice notice-success is-dismissible"> 
			<p><strong><?php _e('Settings saved','woocommerce_gift_cards_lite'); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php _e('Dismiss this notice','woocommerce_gift_cards_lite'); ?></span>
			</button>
		</div>
	<?php
	}
}
$giftcard_selected_date = get_option("mwb_wgm_general_setting_enable_selected_date", false);
$selected_date = get_option("mwb_wgm_general_setting_enable_selected_format", false);
$giftcard_enable = get_option("mwb_wgm_general_setting_enable", false);
$giftcard_tax_cal_enable = get_option("mwb_wgm_general_setting_tax_cal_enable", false);
$giftcard_individual_use = get_option("mwb_wgm_general_setting_giftcard_individual_use", false);
$giftcard_freeshipping = get_option("mwb_wgm_general_setting_giftcard_freeshipping", false);
$giftcard_prefix = get_option("mwb_wgm_general_setting_giftcard_prefix", false);
$giftcard_expiry = get_option("mwb_wgm_general_setting_giftcard_expiry", 0);
$giftcard_use = get_option("mwb_wgm_general_setting_giftcard_use", 0);

$giftcard_minspend = get_option("mwb_wgm_general_setting_giftcard_minspend", false);
$giftcard_maxspend = get_option("mwb_wgm_general_setting_giftcard_maxspend", false);
$giftcard_shop_page = get_option("mwb_wgm_general_setting_shop_page_enable", false);

$giftcard_payment_gateways = get_option("mwb_wgm_general_setting_giftcard_payment", array());
$giftcard_coupon_length = get_option("mwb_wgm_general_setting_giftcard_coupon_length", false);
?>
<div class="mwb_wgm_table_wrapper">
<table class="form-table mwb_wgm_general_setting">
	<tbody>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_general_setting_enable"><?php _e('Enable', 'woocommerce_gift_cards_lite')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php
				$attribut_description = __('Check this box to enable giftcard','woocommerce_gift_cards_lite');
				echo wc_help_tip( $attribut_description );
				?>
				<label for="mwb_wgm_general_setting_enable">
					<input type="checkbox" <?php echo ($giftcard_enable == 'on')?"checked='checked'":""?> name="mwb_wgm_general_setting_enable" id="mwb_wgm_general_setting_enable" class="input-text"> <?php _e('Enable WooCommerce Gift Card','woocommerce_gift_cards_lite');?>
				</label>						
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_general_setting_tax_cal_enable"><?php _e('Enable Tax', 'woocommerce_gift_cards_lite')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				$attribute_description = __('Check this box to enable tax for giftcard product.', 'woocommerce_gift_cards_lite');
				echo wc_help_tip( $attribute_description );
				?>
				<label for="mwb_wgm_general_setting_tax_cal_enable">
					<input type="checkbox" <?php echo ($giftcard_tax_cal_enable == 'on')?"checked='checked'":""?> name="mwb_wgm_general_setting_tax_cal_enable" id="mwb_wgm_general_setting_tax_cal_enable" class="input-text"> <?php _e('Enable Tax Calculation for Gift Card','woocommerce_gift_cards_lite');?>
				</label>						
			</td>
		</tr>		
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_general_setting_shop_page_enable"><?php _e('Enable Listing Shop Page', 'woocommerce_gift_cards_lite')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				$attribute_description = __('Check this box to enable giftcard product listing on shop page.', 'woocommerce_gift_cards_lite');
				echo wc_help_tip( $attribute_description );
				?>
				<label for="mwb_wgm_general_setting_shop_page_enable">
					<input type="checkbox" <?php echo ($giftcard_shop_page == 'on')?"checked='checked'":""?> name="mwb_wgm_general_setting_shop_page_enable" id="mwb_wgm_general_setting_shop_page_enable" class="input-text"> <?php _e('Enable Giftcard Product listing on shop page','woocommerce_gift_cards_lite');?>
				</label>						
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_general_setting_giftcard_individual_use"><?php _e('Individual Use', 'woocommerce_gift_cards_lite')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				$attribute_description = __('Check this box if the Giftcard Coupon cannot be used in conjunction with other Giftcards/Coupons.', 'woocommerce_gift_cards_lite');
				echo wc_help_tip( $attribute_description );
				?>
				<label for="mwb_wgm_general_setting_giftcard_individual_use">
					<input type="checkbox" <?php echo ($giftcard_individual_use == 'yes')?"checked='checked'":""?> name="mwb_wgm_general_setting_giftcard_individual_use" id="mwb_wgm_general_setting_giftcard_individual_use" class="input-text" value="yes"> <?php _e('Allow Giftcard to use Individually','woocommerce_gift_cards_lite');?>
				</label>
				
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_general_setting_giftcard_freeshipping"><?php _e('Free Shipping', 'woocommerce_gift_cards_lite')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				$attribute_description = __('Check this box if the coupon grants free shipping. A free shipping method must be enabled in your shipping zone and be set to require "a valid free shipping coupon" (see the "Free Shipping Requires" setting).', 'woocommerce_gift_cards_lite');
				echo wc_help_tip( $attribute_description );
				?>
				<label for="mwb_wgm_general_setting_giftcard_freeshipping">
					<input type="checkbox" <?php echo ($giftcard_freeshipping == 'yes')?"checked='checked'":""?> name="mwb_wgm_general_setting_giftcard_freeshipping" id="mwb_wgm_general_setting_giftcard_freeshipping" class="input-text" value="yes"> <?php _e('Allow Giftcard on Free Shipping','woocommerce_gift_cards_lite');?>
				</label>
			</td>
		</tr>		
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_general_setting_giftcard_coupon_length"><?php _e('Giftcard Coupon Length', 'woocommerce_gift_cards_lite')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				$attribute_description = __('Enter giftcard coupon length excluding the prefix.(Minimum length is set to 5)', 'woocommerce_gift_cards_lite');
				echo wc_help_tip( $attribute_description );
				?>
				<input type="number" min="5" max="10" value="<?php echo $giftcard_coupon_length;?>" name="mwb_wgm_general_setting_giftcard_coupon_length" id="mwb_wgm_general_setting_giftcard_coupon_length" class="input-text mwb_wgm_new_woo_ver_style_text" > 	
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_general_setting_giftcard_prefix"><?php _e('Giftcard Prefix', 'woocommerce_gift_cards_lite')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				$attribute_description = __('Enter Gift Card Prefix. Ex: PREFIX_CODE', 'woocommerce_gift_cards_lite');
				echo wc_help_tip( $attribute_description );
				?>
				<input type="text" value="<?php echo $giftcard_prefix;?>" name="mwb_wgm_general_setting_giftcard_prefix" id="mwb_wgm_general_setting_giftcard_prefix" class="input-text mwb_wgm_new_woo_ver_style_text" style="width:160px"> 	
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_general_setting_giftcard_expiry"><?php _e('Giftcard Expiry After Days', 'woocommerce_gift_cards_lite')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				$attribute_description = __('Enter number of days after purchased Giftcard is expired. Keep value "1" for one day expiry when order is completed. Keep value "0" for no expiry.', 'woocommerce_gift_cards_lite');
				echo wc_help_tip( $attribute_description );
				?>
				<input type="number" min="0" value="<?php echo $giftcard_expiry;?>" name="mwb_wgm_general_setting_giftcard_expiry" id="mwb_wgm_general_setting_giftcard_expiry" class="input-text mwb_wgm_new_woo_ver_style_text" > 	
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_general_setting_giftcard_minspend"><?php _e('Minimum Spend', 'woocommerce_gift_cards_lite')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				$attribute_description = __('This field allows you to set the minimum spend (subtotal, including taxes) allowed to use the Giftcard coupon.', 'woocommerce_gift_cards_lite');
				echo wc_help_tip( $attribute_description );
				?>
				<input type="number" min="0" value="<?php echo $giftcard_minspend;?>" name="mwb_wgm_general_setting_giftcard_minspend" id="mwb_wgm_general_setting_giftcard_minspend" class="input-text mwb_wgm_new_woo_ver_style_text" > 	
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_general_setting_giftcard_maxspend"><?php _e('Maximum Spend', 'woocommerce_gift_cards_lite')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				$attribute_description = __('This field allows you to set the maximum spend (subtotal, including taxes) allowed when using the Giftcard coupon.', 'woocommerce_gift_cards_lite');
				echo wc_help_tip( $attribute_description );
				?>
				<input type="number" min="0" value="<?php echo $giftcard_maxspend;?>" name="mwb_wgm_general_setting_giftcard_maxspend" id="mwb_wgm_general_setting_giftcard_maxspend " class="input-text mwb_wgm_new_woo_ver_style_text" > 	
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="mwb_wgm_general_setting_giftcard_use"><?php _e('Giftcard No of time usage', 'woocommerce_gift_cards_lite')?></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				$attribute_description = __('How many times this coupon can be used before Giftcard is void.', 'woocommerce_gift_cards_lite');
				echo wc_help_tip( $attribute_description );
				?>
				<input type="number"  min="0" value="<?php echo $giftcard_use;?>" name="mwb_wgm_general_setting_giftcard_use" id="mwb_wgm_general_setting_giftcard_use" class="input-text mwb_wgm_new_woo_ver_style_text" > 	
			</td>
		</tr>	
	</tbody>
</table>
</div>
<div class="clear"></div>
<p class="submit">
	<input type="submit" value="<?php _e('Save changes', 'woocommerce_gift_cards_lite'); ?>" class="button-primary woocommerce-save-button" name="mwb_wgm_general_setting_save" id="mwb_wgm_general_setting_save" >
</p>
