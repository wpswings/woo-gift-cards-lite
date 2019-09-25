<?php
/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'MWB_WGM_GIFTCARD_ACTIVATION_FUNCTION' ) ) {
	/**
	 * This is class to restore the saved data on particular keys.
	 *
	 * @name    MWB_WGM_GIFTCARD_ACTIVATION_FUNCTION
	 * @category Class
	 * @author   makewebbetter <webmaster@makewebbetter.com>
	 */
	class MWB_WGM_GIFTCARD_ACTIVATION_FUNCTION {
		/**
		 * This function is used to restore the overall functionality of plugin
		 *
		 * @name mwb_wgm_restore_data
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function mwb_wgm_restore_data() {
			/*General setting tab data*/
			$mwb_check_enable = false;
			$giftcard_enable = get_option( 'mwb_wgm_general_setting_enable', false );
			if ( isset( $giftcard_enable ) && $giftcard_enable == 'on' ) {
				$mwb_check_enable = true;
			}
			if ( $mwb_check_enable ) {
				$general_process_completion_flag = false;
				$general_flag = false;
				$product_flag = false;
				$mail_flag = false;
				$delivery_flag = false;
				$other_flag = false;

				$general_flag = $this->restore_general_settings_data( $general_process_completion_flag );
				if ( $general_flag ) {
					$product_process_completion_flag = false;
					$product_flag = $this->restore_product_settings_data( $product_process_completion_flag );
				}
				if ( $product_flag ) {
					$mail_process_completion_flag = false;
					$mail_flag = $this->restore_mail_settings_data( $mail_process_completion_flag );
				}
				if ( $mail_flag ) {
					$delivery_process_completion_flag = false;
					$delivery_flag = $this->restore_delivery_settings_data( $delivery_process_completion_flag );
				}
				if ( $delivery_flag ) {
					$other_process_completion_flag = false;
					$other_flag = $this->restore_other_settings_data( $other_process_completion_flag );
				}
				if ( $other_flag ) {
					$this->delete_additional_data();
				}
			}
		}

		/*General setting tab data*/
		public function restore_general_settings_data( $general_process_completion_flag ) {
			$mwb_general_settings = get_option( 'mwb_wgm_general_settings', array() );
			$general_setting_flag = false;
			if ( empty( $mwb_general_settings ) ) {
				$giftcard_enable = get_option( 'mwb_wgm_general_setting_enable', false );
				$giftcard_tax_cal_enable = get_option( 'mwb_wgm_general_setting_tax_cal_enable', false );
				$giftcard_shop_page = get_option( 'mwb_wgm_general_setting_shop_page_enable', false );
				$giftcard_individual_use = get_option( 'mwb_wgm_general_setting_giftcard_individual_use', false );
				$giftcard_freeshipping = get_option( 'mwb_wgm_general_setting_giftcard_freeshipping', false );
				$giftcard_coupon_length = get_option( 'mwb_wgm_general_setting_giftcard_coupon_length', false );
				$giftcard_prefix = get_option( 'mwb_wgm_general_setting_giftcard_prefix', false );
				$giftcard_prefix_sanitize = preg_replace( '/\\\\/', '', $giftcard_prefix );
				$giftcard_prefix_sanitize = sanitize_text_field( $giftcard_prefix_sanitize );
				$giftcard_expiry = get_option( 'mwb_wgm_general_setting_giftcard_expiry', 0 );
				$giftcard_minspend = get_option( 'mwb_wgm_general_setting_giftcard_minspend', false );
				$giftcard_maxspend = get_option( 'mwb_wgm_general_setting_giftcard_maxspend', false );
				$giftcard_use = get_option( 'mwb_wgm_general_setting_giftcard_use', 0 );
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
					'mwb_wgm_general_setting_giftcard_use' => $giftcard_use,
				);
				update_option( 'mwb_wgm_general_settings', $mwb_wgm_general_settings );
				$general_setting_flag = true;
			}
			if ( $general_setting_flag ) {
				delete_option( 'mwb_wgm_general_setting_enable' );
				delete_option( 'mwb_wgm_general_setting_tax_cal_enable' );
				delete_option( 'mwb_wgm_general_setting_shop_page_enable' );
				delete_option( 'mwb_wgm_general_setting_giftcard_individual_use' );
				delete_option( 'mwb_wgm_general_setting_giftcard_freeshipping' );
				delete_option( 'mwb_wgm_general_setting_giftcard_coupon_length' );
				delete_option( 'mwb_wgm_general_setting_giftcard_prefix' );
				delete_option( 'mwb_wgm_general_setting_giftcard_expiry' );
				delete_option( 'mwb_wgm_general_setting_giftcard_minspend' );
				delete_option( 'mwb_wgm_general_setting_giftcard_maxspend' );
				delete_option( 'mwb_wgm_general_setting_giftcard_use' );
				$general_process_completion_flag = true;
			}
			return $general_process_completion_flag;
		}

		/*Product setting tab data*/
		public function restore_product_settings_data( $product_process_completion_flag ) {
			$product_setting_flag = false;
			$mwb_product_settings = get_option( 'mwb_wgm_product_settings', array() );
			if ( empty( $mwb_product_settings ) ) {
				$giftcard_exclude_product = get_option( 'mwb_wgm_product_setting_exclude_product', array() );
				$giftcard_exclude_category = get_option( 'mwb_wgm_product_setting_exclude_category', array() );
				$giftcard_ex_sale = get_option( 'mwb_wgm_product_setting_giftcard_ex_sale', false );
				$mwb_wgm_product_settings = array(
					'mwb_wgm_product_setting_giftcard_ex_sale' => $giftcard_ex_sale,
					'mwb_wgm_product_setting_exclude_product' => $giftcard_exclude_product,
					'mwb_wgm_product_setting_exclude_category' => $giftcard_exclude_category,
				);
				update_option( 'mwb_wgm_product_settings', $mwb_wgm_product_settings );
				$product_setting_flag = true;
			}
			if ( $product_setting_flag ) {
				delete_option( 'mwb_wgm_product_setting_giftcard_ex_sale' );
				delete_option( 'mwb_wgm_product_setting_exclude_product' );
				delete_option( 'mwb_wgm_product_setting_exclude_category' );
				$product_process_completion_flag = true;
			}
			return $product_process_completion_flag;
		}

		/*Email setting tab data*/
		public function restore_mail_settings_data( $mail_process_completion_flag ) {

			$mail_setting_flag = false;
			$mwb_mail_settings = get_option( 'mwb_wgm_mail_settings', array() );
			if ( empty( $mwb_mail_settings ) ) {
				$mwb_wgm_other_setting_upload_logo = get_option( 'mwb_wgm_other_setting_upload_logo', false );
				$giftcard_giftcard_subject = get_option( 'mwb_wgm_other_setting_giftcard_subject', false );
				$giftcard_giftcard_subject = stripcslashes( $giftcard_giftcard_subject );
				$mwb_wgm_mail_settings = array(
					'mwb_wgm_mail_setting_upload_logo' => $mwb_wgm_other_setting_upload_logo,
					'mwb_wgm_mail_setting_giftcard_subject' => $giftcard_giftcard_subject,
				);
				update_option( 'mwb_wgm_mail_settings', $mwb_wgm_mail_settings );
				$mail_setting_flag = true;
			}
			if ( $mail_setting_flag ) {
				delete_option( 'mwb_wgm_other_setting_upload_logo' );
				delete_option( 'mwb_wgm_other_setting_giftcard_subject' );
				delete_option( 'mwb_wgm_other_setting_giftcard_html' );
				$mail_process_completion_flag = true;
			}
			return $mail_process_completion_flag;
		}

		/*Delivery setting tab data*/
		public function restore_delivery_settings_data( $delivery_process_completion_flag ) {
			$delivery_setting_flag = false;
			$mwb_delivery_settings = get_option( 'mwb_wgm_delivery_settings', array() );
			if ( empty( $mwb_delivery_settings ) ) {
				$mwb_wgm_delivery_setting_method = get_option( 'mwb_wgm_delivery_setting_method', false );
				if ( $mwb_wgm_delivery_setting_method == 'Mail_To_Recipient' ) {
					$mwb_wgm_delivery_setting_method = 'Mail to recipient';
				}
				$mwb_wgm_delivery_settings = array(
					'mwb_wgm_send_giftcard' => $mwb_wgm_delivery_setting_method,
				);
				update_option( 'mwb_wgm_delivery_settings', $mwb_wgm_delivery_settings );
				$delivery_setting_flag = true;
			}
			if ( $delivery_setting_flag ) {
				delete_option( 'mwb_wgm_delivery_setting_method' );
				$delivery_process_completion_flag = true;
			}
			return $delivery_process_completion_flag;
		}

		/*Other setting data tab*/
		public function restore_other_settings_data( $other_process_completion_flag ) {
			$other_setting_flag = false;
			$mwb_other_settings = get_option( 'mwb_wgm_other_settings', array() );
			if ( empty( $mwb_other_settings ) ) {
				$mwb_wgm_apply_coupon_disable = get_option( 'mwb_wgm_additional_apply_coupon_disable', false );
				$mwb_wgm_other_settings = array(
					'mwb_wgm_additional_apply_coupon_disable' => $mwb_wgm_apply_coupon_disable,
				);
				update_option( 'mwb_wgm_other_settings', $mwb_wgm_other_settings );
				$other_setting_flag = true;
			}
			if ( $other_setting_flag ) {
				delete_option( 'mwb_wgm_additional_apply_coupon_disable' );
				$other_process_completion_flag = true;
			}
			return $other_process_completion_flag;
		}

		/*Removed fields in new lite plugin*/
		public function delete_additional_data() {
			delete_option( 'mwb_wgm_general_setting_select_template' );
			delete_option( 'mwb_wgm_general_setting_giftcard_applybeforetx' );
			delete_option( 'mwb_wgm_select_email_format' );
			delete_option( 'mwb_wgm_product_setting_exclude_product_format' );
		}
	}
}
