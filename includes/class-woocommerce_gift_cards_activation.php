<?php
/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if( !class_exists( 'MWB_WGM_GIFTCARD_ACTIVATION_FUNCTION' ) )
{
	/**
	 * This is class to restore the saved data on particular keys.
	 *
	 * @name    MWB_WGM_GIFTCARD_ACTIVATION_FUNCTION
	 * @category Class
	 * @author   makewebbetter <webmaster@makewebbetter.com>
	 */
	class MWB_WGM_GIFTCARD_ACTIVATION_FUNCTION{
		/**
		 * This function is used to restore the overall functionality of plugin
		 * 
		 * @name mwb_wgm_restore_data
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
        function mwb_wgm_restore_data() {
	        /*General setting tab data*/
			$giftcard_enable = get_option("mwb_wgm_general_setting_enable", false);
			$giftcard_tax_cal_enable = get_option("mwb_wgm_general_setting_tax_cal_enable", false);
			$giftcard_shop_page = get_option("mwb_wgm_general_setting_shop_page_enable", false);
			$giftcard_individual_use = get_option("mwb_wgm_general_setting_giftcard_individual_use", false);
			$giftcard_freeshipping = get_option("mwb_wgm_general_setting_giftcard_freeshipping", false);
			$giftcard_coupon_length = get_option("mwb_wgm_general_setting_giftcard_coupon_length", false);
			$giftcard_prefix = get_option("mwb_wgm_general_setting_giftcard_prefix", false);
			$giftcard_prefix_sanitize = preg_replace('/\\\\/', '', $giftcard_prefix);
			$giftcard_prefix_sanitize = sanitize_text_field($giftcard_prefix_sanitize);
			$giftcard_expiry = get_option("mwb_wgm_general_setting_giftcard_expiry", 0);
			$giftcard_minspend = get_option("mwb_wgm_general_setting_giftcard_minspend", false);
			$giftcard_maxspend = get_option("mwb_wgm_general_setting_giftcard_maxspend", false);
			$giftcard_use = get_option("mwb_wgm_general_setting_giftcard_use", 0);	
			$mwb_wgm_general_settings = array(
				'mwb_wgm_general_setting_enable' => $giftcard_enable,
			    'mwb_wgm_general_setting_tax_cal_enable' => $giftcard_tax_cal_enable,
			    'mwb_wgm_general_setting_shop_page_enable' => $giftcard_shop_page,
			    'mwb_wgm_general_setting_giftcard_individual_use' => $giftcard_individual_use,
			    'mwb_wgm_general_setting_giftcard_freeshipping' => $giftcard_freeshipping,
			    'mwb_wgm_general_setting_giftcard_coupon_length' => $giftcard_coupon_length,
			    'mwb_wgm_general_setting_giftcard_prefix' => $giftcard_prefix_sanitize,
			    'mwb_wgm_general_setting_giftcard_expiry' => $giftcard_expiry,
			    'mwb_wgm_general_setting_giftcard_minspend' => $giftcard_minspend,
			    'mwb_wgm_general_setting_giftcard_maxspend' => $giftcard_maxspend,
			    'mwb_wgm_general_setting_giftcard_use' => $giftcard_use
			);
			update_option('mwb_wgm_general_settings' , $mwb_wgm_general_settings);
			delete_option('mwb_wgm_general_setting_enable');
			delete_option('mwb_wgm_general_setting_tax_cal_enable');
			delete_option('mwb_wgm_general_setting_shop_page_enable');
			delete_option('mwb_wgm_general_setting_giftcard_individual_use');
			delete_option('mwb_wgm_general_setting_giftcard_freeshipping');
			delete_option('mwb_wgm_general_setting_giftcard_coupon_length');
			delete_option('mwb_wgm_general_setting_giftcard_prefix');
			delete_option('mwb_wgm_general_setting_giftcard_expiry');
			delete_option('mwb_wgm_general_setting_giftcard_minspend');
			delete_option('mwb_wgm_general_setting_giftcard_maxspend');
			delete_option('mwb_wgm_general_setting_giftcard_use');
			/*Removed fields in new lite plugin*/
			delete_option('mwb_wgm_general_setting_select_template');
			delete_option('mwb_wgm_general_setting_giftcard_applybeforetx');
			delete_option('mwb_wgm_select_email_format');

			/*Product setting tab data*/
			$giftcard_exclude_product = get_option("mwb_wgm_product_setting_exclude_product", array());
			$giftcard_exclude_category = get_option("mwb_wgm_product_setting_exclude_category", array());
			$giftcard_ex_sale = get_option("mwb_wgm_product_setting_giftcard_ex_sale", false);
			$giftcard_exclude_product_comma_seperated = get_option("mwb_wgm_product_setting_exclude_product_format", '');
			$mwb_wgm_product_settings = array(
			    'mwb_wgm_product_setting_giftcard_ex_sale' => $giftcard_ex_sale,
			    'mwb_wgm_product_setting_exclude_product' => $giftcard_exclude_product,
			    'mwb_wgm_product_setting_exclude_category' => $giftcard_exclude_category
			);
			update_option('mwb_wgm_product_settings', $mwb_wgm_product_settings );
			update_option('mwb_wgm_product_setting_exclude_product_format', $giftcard_exclude_product_comma_seperated);
			delete_option('mwb_wgm_product_setting_giftcard_ex_sale');
			delete_option('mwb_wgm_product_setting_exclude_product');
			delete_option('mwb_wgm_product_setting_exclude_category');
			delete_option('mwb_wgm_product_setting_exclude_product_format');


			/*Email setting tab data*/
			$mwb_wgm_other_setting_upload_logo = get_option("mwb_wgm_other_setting_upload_logo", false);
			$giftcard_giftcard_subject = get_option("mwb_wgm_other_setting_giftcard_subject", false);
			$giftcard_giftcard_subject = stripcslashes($giftcard_giftcard_subject);
			$mwb_wgm_mail_settings = array(
				 'mwb_wgm_mail_setting_upload_logo' => $mwb_wgm_other_setting_upload_logo,
				 'mwb_wgm_mail_setting_giftcard_subject' => $giftcard_giftcard_subject
			);
			update_option('mwb_wgm_mail_settings' , $mwb_wgm_mail_settings);
			delete_option('mwb_wgm_other_setting_upload_logo');
			delete_option('mwb_wgm_other_setting_giftcard_subject');
			delete_option('mwb_wgm_other_setting_giftcard_html');

			/*Delivery setting tab data*/
			$mwb_wgm_delivery_setting_method = get_option('mwb_wgm_delivery_setting_method',false);
			$mwb_wgm_delivery_settings = array(
				'mwb_wgm_send_giftcard' => $mwb_wgm_delivery_setting_method
			);
			update_option( 'mwb_wgm_delivery_settings', $mwb_wgm_delivery_settings);
			delete_option('mwb_wgm_delivery_setting_method');

			/*Other setting data tab*/
			$mwb_wgm_apply_coupon_disable = get_option('mwb_wgm_additional_apply_coupon_disable',false);
			$mwb_wgm_other_settings =  array(
				'mwb_wgm_additional_apply_coupon_disable' => $mwb_wgm_apply_coupon_disable
			);
			update_option( 'mwb_wgm_other_settings' , $mwb_wgm_other_settings );
			delete_option('mwb_wgm_additional_apply_coupon_disable');	     
        }
    }
}
?>