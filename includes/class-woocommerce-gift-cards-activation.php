<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Woocommerce_Gift_Cards_Activation' ) ) {
	/**
	 * This is class to restore the saved data on particular keys.
	 *
	 * @name    Woocommerce_Gift_Cards_Activation
	 * @category Class
	 * @author   WP Swings <webmaster@wpswings.com>
	 */
	class Woocommerce_Gift_Cards_Activation {
		/**
		 * This function is used to restore the overall functionality of plugin.
		 *
		 * @name wps_wgm_restore_data
		 * @param boolean $network_wide for multisite.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
		 */
		public function wps_wgm_restore_data( $network_wide ) {
			/*General setting tab data*/
			global $wpdb;
			// check if the plugin has been activated on the network.
			if ( is_multisite() && $network_wide ) {
				// Get all blogs in the network and activate plugins on each one.
				$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->on_activation();
					restore_current_blog();
				}
			} else {
				// activated on a single site, in a multi-site or on a single site.
				$this->on_activation();
			}
		}

		/**
		 * Function for Product setting tab data
		 *
		 * @name restore_general_settings_data
		 * @param boolean $general_process_completion_flag contains the flag value.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
		 */
		public function restore_general_settings_data( $general_process_completion_flag ) {
			$wps_general_settings = get_option( 'wps_wgm_general_settings', array() );
			$general_setting_flag = false;
			if ( empty( $wps_general_settings ) ) {
				$giftcard_enable = get_option( 'wps_wgm_general_setting_enable', false );
				$giftcard_tax_cal_enable = get_option( 'wps_wgm_general_setting_tax_cal_enable', false );
				$giftcard_shop_page = get_option( 'wps_wgm_general_setting_shop_page_enable', false );
				$giftcard_individual_use = get_option( 'wps_wgm_general_setting_giftcard_individual_use', false );
				$giftcard_freeshipping = get_option( 'wps_wgm_general_setting_giftcard_freeshipping', false );
				$giftcard_coupon_length = get_option( 'wps_wgm_general_setting_giftcard_coupon_length', false );
				$giftcard_prefix = get_option( 'wps_wgm_general_setting_giftcard_prefix', false );
				$giftcard_prefix_sanitize = preg_replace( '/\\\\/', '', $giftcard_prefix );
				$giftcard_prefix_sanitize = sanitize_text_field( $giftcard_prefix_sanitize );
				$giftcard_expiry = get_option( 'wps_wgm_general_setting_giftcard_expiry', 0 );
				$giftcard_minspend = get_option( 'wps_wgm_general_setting_giftcard_minspend', false );
				$giftcard_maxspend = get_option( 'wps_wgm_general_setting_giftcard_maxspend', false );
				$giftcard_use = get_option( 'wps_wgm_general_setting_giftcard_use', 0 );
				$wps_wgm_general_settings = array(
					'wps_wgm_general_setting_enable' => $giftcard_enable,
					'wps_wgm_general_setting_tax_cal_enable' => $giftcard_tax_cal_enable,
					'wps_wgm_general_setting_shop_page_enable' => $giftcard_shop_page,
					'wps_wgm_general_setting_giftcard_individual_use' => $giftcard_individual_use,
					'wps_wgm_general_setting_giftcard_freeshipping' => $giftcard_freeshipping,
					'wps_wgm_general_setting_giftcard_coupon_length' => $giftcard_coupon_length,
					'wps_wgm_general_setting_giftcard_prefix' => $giftcard_prefix_sanitize,
					'wps_wgm_general_setting_giftcard_expiry' => $giftcard_expiry,
					'wps_wgm_general_setting_giftcard_minspend' => $giftcard_minspend,
					'wps_wgm_general_setting_giftcard_maxspend' => $giftcard_maxspend,
					'wps_wgm_general_setting_giftcard_use' => $giftcard_use,
				);
				update_option( 'wps_wgm_general_settings', $wps_wgm_general_settings );
				$general_setting_flag = true;
			}
			if ( $general_setting_flag ) {
				delete_option( 'wps_wgm_general_setting_enable' );
				delete_option( 'wps_wgm_general_setting_tax_cal_enable' );
				delete_option( 'wps_wgm_general_setting_shop_page_enable' );
				delete_option( 'wps_wgm_general_setting_giftcard_individual_use' );
				delete_option( 'wps_wgm_general_setting_giftcard_freeshipping' );
				delete_option( 'wps_wgm_general_setting_giftcard_coupon_length' );
				delete_option( 'wps_wgm_general_setting_giftcard_prefix' );
				delete_option( 'wps_wgm_general_setting_giftcard_expiry' );
				delete_option( 'wps_wgm_general_setting_giftcard_minspend' );
				delete_option( 'wps_wgm_general_setting_giftcard_maxspend' );
				delete_option( 'wps_wgm_general_setting_giftcard_use' );
				$general_process_completion_flag = true;
			}
			return $general_process_completion_flag;
		}
		/**
		 * Function for Product setting tab data
		 *
		 * @name restore_product_settings_data
		 * @param boolean $product_process_completion_flag contains the flag value.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
		 */
		public function restore_product_settings_data( $product_process_completion_flag ) {
			$product_setting_flag = false;
			$wps_product_settings = get_option( 'wps_wgm_product_settings', array() );
			if ( empty( $wps_product_settings ) ) {
				$giftcard_exclude_product = get_option( 'wps_wgm_product_setting_exclude_product', array() );
				$giftcard_exclude_category = get_option( 'wps_wgm_product_setting_exclude_category', array() );
				$giftcard_ex_sale = get_option( 'wps_wgm_product_setting_giftcard_ex_sale', false );
				$wps_wgm_product_settings = array(
					'wps_wgm_product_setting_giftcard_ex_sale' => $giftcard_ex_sale,
					'wps_wgm_product_setting_exclude_product' => $giftcard_exclude_product,
					'wps_wgm_product_setting_exclude_category' => $giftcard_exclude_category,
				);
				update_option( 'wps_wgm_product_settings', $wps_wgm_product_settings );
				$product_setting_flag = true;
			}
			if ( $product_setting_flag ) {
				delete_option( 'wps_wgm_product_setting_giftcard_ex_sale' );
				delete_option( 'wps_wgm_product_setting_exclude_product' );
				delete_option( 'wps_wgm_product_setting_exclude_category' );
				$product_process_completion_flag = true;
			}
			return $product_process_completion_flag;
		}

		/**
		 * Function for Email setting tab data.
		 *
		 * @name restore_mail_settings_data
		 * @param boolean $mail_process_completion_flag contains the flag value.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
		 */
		public function restore_mail_settings_data( $mail_process_completion_flag ) {

			$mail_setting_flag = false;
			$wps_mail_settings = get_option( 'wps_wgm_mail_settings', array() );
			if ( empty( $wps_mail_settings ) ) {
				$wps_wgm_other_setting_upload_logo = get_option( 'wps_wgm_other_setting_upload_logo', false );
				$giftcard_giftcard_subject = get_option( 'wps_wgm_other_setting_giftcard_subject', false );
				$giftcard_giftcard_subject = stripcslashes( $giftcard_giftcard_subject );
				$wps_wgm_mail_settings = array(
					'wps_wgm_mail_setting_upload_logo' => $wps_wgm_other_setting_upload_logo,
					'wps_wgm_mail_setting_giftcard_subject' => $giftcard_giftcard_subject,
				);
				update_option( 'wps_wgm_mail_settings', $wps_wgm_mail_settings );
				$mail_setting_flag = true;
			}
			if ( $mail_setting_flag ) {
				delete_option( 'wps_wgm_other_setting_upload_logo' );
				delete_option( 'wps_wgm_other_setting_giftcard_subject' );
				delete_option( 'wps_wgm_other_setting_giftcard_html' );
				$mail_process_completion_flag = true;
			}
			return $mail_process_completion_flag;
		}

		/**
		 * Function for Delivery setting tab data.
		 *
		 * @name restore_delivery_settings_data
		 * @param boolean $delivery_process_completion_flag contains the flag value.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
		 */
		public function restore_delivery_settings_data( $delivery_process_completion_flag ) {
			$delivery_setting_flag = false;
			$wps_delivery_settings = get_option( 'wps_wgm_delivery_settings', array() );
			if ( empty( $wps_delivery_settings ) ) {
				$wps_wgm_delivery_setting_method = get_option( 'wps_wgm_delivery_setting_method', false );
				if ( 'Mail_To_Recipient' == $wps_wgm_delivery_setting_method ) {
					$wps_wgm_delivery_setting_method = 'Mail to recipient';
				}
				$wps_wgm_delivery_settings = array(
					'wps_wgm_send_giftcard' => $wps_wgm_delivery_setting_method,
				);
				update_option( 'wps_wgm_delivery_settings', $wps_wgm_delivery_settings );
				$delivery_setting_flag = true;
			}
			if ( $delivery_setting_flag ) {
				delete_option( 'wps_wgm_delivery_setting_method' );
				$delivery_process_completion_flag = true;
			}
			return $delivery_process_completion_flag;
		}

		/**
		 * Function for Other setting tab data.
		 *
		 * @name restore_other_settings_data
		 * @param boolean $other_process_completion_flag contains the flag value.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
		 */
		public function restore_other_settings_data( $other_process_completion_flag ) {
			$other_setting_flag = false;
			$wps_other_settings = get_option( 'wps_wgm_other_settings', array() );
			if ( empty( $wps_other_settings ) ) {
				$wps_wgm_apply_coupon_disable = get_option( 'wps_wgm_additional_apply_coupon_disable', false );
				$wps_wgm_other_settings = array(
					'wps_wgm_additional_apply_coupon_disable' => $wps_wgm_apply_coupon_disable,
				);
				update_option( 'wps_wgm_other_settings', $wps_wgm_other_settings );
				$other_setting_flag = true;
			}
			if ( $other_setting_flag ) {
				delete_option( 'wps_wgm_additional_apply_coupon_disable' );
				$other_process_completion_flag = true;
			}
			return $other_process_completion_flag;
		}

		/**
		 * Removed fields in new lite plugin
		 *
		 * @name delete_additional_data
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
		 */
		public function delete_additional_data() {
			delete_option( 'wps_wgm_general_setting_giftcard_applybeforetx' );
			delete_option( 'wps_wgm_product_setting_exclude_product_format' );
		}

		/**
		 * This function is used to restore the overall functionality of plugin
		 *
		 * @return void
		 */
		public function on_activation() {
			$this->upgrade_wp_postmeta();
			$this->upgrade_wp_options();
			$wps_check_enable = false;
			$giftcard_enable  = get_option( 'wps_wgm_general_setting_enable', false );
			if ( isset( $giftcard_enable ) && 'on' == $giftcard_enable ) {
				$wps_check_enable = true;
			}
			if ( $wps_check_enable ) {
				$general_process_completion_flag = false;
				$general_flag                    = false;
				$product_flag                    = false;
				$mail_flag                       = false;
				$delivery_flag                   = false;
				$other_flag                      = false;

				$general_flag = $this->restore_general_settings_data( $general_process_completion_flag );
				if ( $general_flag ) {
					$product_process_completion_flag = false;
					$product_flag                    = $this->restore_product_settings_data( $product_process_completion_flag );
				}
				if ( $product_flag ) {
					$mail_process_completion_flag = false;
					$mail_flag                    = $this->restore_mail_settings_data( $mail_process_completion_flag );
				}
				if ( $mail_flag ) {
					$delivery_process_completion_flag = false;
					$delivery_flag                    = $this->restore_delivery_settings_data( $delivery_process_completion_flag );
				}
				if ( $delivery_flag ) {
					$other_process_completion_flag = false;
					$other_flag                    = $this->restore_other_settings_data( $other_process_completion_flag );
				}
				if ( $other_flag ) {
					$this->delete_additional_data();
				}
			}
		}

		/**
		 * Upgrade_wp_postmeta. (use period)
		 *
		 * Upgrade_wp_postmeta.
		 *
		 * @since    1.0.0
		 */
		public static function upgrade_wp_postmeta() {

			$post_meta_keys = array(
				'mwb_wgm_pricing',
				'mwb_wgm_giftcard_coupon',
				'mwb_wgm_giftcard_coupon_unique',
				'mwb_wgm_giftcard_coupon_product_id',
				'mwb_wgm_giftcard_coupon_mail_to',
				'mwb_wgm_coupon_amount',
				'mwb_wgm_order_giftcard',

				);

			foreach ( $post_meta_keys as $key => $meta_keys ) {
					$products = get_posts(
						array(
							'numberposts' => -1,
							'post_status' => 'publish',
							'fields'      => 'ids', // return only ids.
							'meta_key'    => $meta_keys, //phpcs:ignore
							'post_type'   => 'product',
							'order'       => 'ASC',
						)
					);

				if ( ! empty( $products ) && is_array( $products ) ) {
					foreach ( $products as $k => $product_id ) {
						$values   = get_post_meta( $product_id, $meta_keys, true );
						$new_key = str_replace( 'mwb_', 'wps_', $meta_keys );

						if ( ! empty( get_post_meta( $product_id, $new_key, true ) ) ) {
							continue;
						}
					
						$arr_val_post = array();
						if ( is_array( $values  )) {
							foreach ( $values  as $key => $value){
								$keys = str_replace( 'mwb_', 'wps_', $key );
						
								$new_key1 = str_replace( 'mwb_', 'wps_', $value );
								$arr_val_post[ $key ] = $new_key1;
							}
							update_post_meta( $product_id, $new_key, $arr_val_post );
						} else {
							update_post_meta( $product_id, $new_key, $values );
						}
					}
				}
			}
		}

		/**
		 * Upgrade_wp_options. (use period)
		 *
		 * Upgrade_wp_options.
		 *
		 * @since    1.0.0
		 */
		public static function upgrade_wp_options() {
			$wp_options = array(
				'mwb_wgm_general_settings' => '',
				'mwb_gw_lcns_status'  => '',
				'mwb_gw_lcns_key'  => '',
				'mwb_gw_lcns_thirty_days'  => '',
				'mwb_wgc_create_gift_card_taxonomy'  => '',
				'mwb_uwgc_templateid'  => '',
				'mwb_wgm_new_mom_template'  => '',
				'mwb_wgm_gift_for_you'  => '',
				'mwb_wgm_insert_custom_template'  => '',
				'mwb_wgm_merry_christmas_template'  => '',
				'mwb_wgm_notify_new_msg_id'  => '',
				'mwb_wgm_notify_hide_notification'  => '',
				'mwb_wgm_notify_new_message'  => '',
				'mwb_wgm_delivery_settings'  => '',
				'mwb_wgm_email_to_recipient_setting_enable'  => '',
				'mwb_wgm_downladable_setting_enable'  => '',
				'mwb_wgm_mail_settings'  => '',
				'mwb_wgm_mail_setting_upload_logo'  => '',
				'mwb_wgm_mail_setting_upload_logo_dimension_height'  => '',
				'mwb_wgm_mail_setting_upload_logo_dimension_width'  => '',
				'mwb_wgm_mail_setting_background_logo'  => '',
				'mwb_wgm_mail_setting_giftcard_message_length'  => '',
				'mwb_wgm_mail_setting_default_message'  => '',
				'mwb_wgm_mail_setting_disclaimer'  => '',
				'mwb_wgm_mail_setting_giftcard_subject'  => '',
				'mwb_wgm_other_settings'  => '',
				'mwb_wgm_product_settings'  => '',
				'mwb_wgm_general_setting_enable'  => '',
				'mwb_wgm_general_setting_tax_cal_enable'  => '',
				'mwb_wgm_general_setting_shop_page_enable'  => '',
				'mwb_wgm_general_setting_giftcard_individual_use'  => '',
				'mwb_wgm_general_setting_giftcard_freeshipping'  => '',
				'mwb_wgm_general_setting_giftcard_coupon_length'  => '',
				'mwb_wgm_general_setting_giftcard_prefix'  => '',
				'mwb_wgm_general_setting_giftcard_expiry'  => '',
				'mwb_wgm_general_setting_giftcard_minspend'  => '',
				'mwb_wgm_general_setting_giftcard_maxspend'  => '',
				'mwb_wgm_general_setting_giftcard_use'  => '',
				'mwb_wgm_product_setting_exclude_product'  => '',
				'mwb_wgm_product_setting_exclude_category'  => '',
				'mwb_wgm_product_setting_giftcard_ex_sale'  => '',
				'mwb_wgm_other_setting_upload_logo'  => '',
				'mwb_wgm_other_setting_giftcard_subject'  => '',
				'mwb_wgm_additional_preview_disable'  => '',
				'mwb_wgm_delivery_setting_method'  => '',
				'mwb_wgm_additional_apply_coupon_disable'  => '',
				'mwb_wgm_select_email_format'  => '',
				'mwb_wgm_general_setting_select_template'  => '',
				'mwb_wsfw_enable_email_notification_for_wallet_update'  => '',	
			);

			foreach ( $wp_options as $key => $value ) {

				$new_key = str_replace( 'mwb_', 'wps_', $key );	
				if ( ! empty( get_option( $new_key ) ) ) {
					continue;
				}
				$new_value = get_option( $key, $value );

				$arr_val = array();
				if ( is_array( $new_value )) {
					foreach ( $new_value as $key => $value) {
						$new_key1 = str_replace( 'mwb_', 'wps_', $key );
						$arr_val[ $new_key1 ] = $value;
					}
					update_option( $new_key, $arr_val );
				}
				else {
					update_option( $new_key, $new_value );
				}
			}
		}

	}
}
