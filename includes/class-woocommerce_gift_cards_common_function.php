<?php
if (!defined('ABSPATH')) {
	exit();
}
if (!class_exists('Woocommerce_gift_cards_common_function')) {

	class Woocommerce_gift_cards_common_function
	{
		public function mwb_wgm_create_gift_template($args){

			if( isset( $args ) && is_array( $args ) && !empty( $args ) ) {

				$templateid = $args['templateid'];

				$template = get_post($templateid, ARRAY_A);
				$templatehtml = $template['post_content'];

				$giftcard_logo_html = "";
				
				$giftcard_featured = "";
				$giftcard_event_html = "";

				
				$mwb_wgm_mail_settings = get_option("mwb_wgm_mail_settings", array());
				$giftcard_upload_logo = $this->mwb_wgm_get_template_data($mwb_wgm_mail_settings,'mwb_wgm_mail_setting_upload_logo');

				$giftcard_logo_height = $this->mwb_wgm_get_template_data($mwb_wgm_mail_settings,'mwb_wgm_mail_setting_upload_logo_dimension_height');

				$giftcard_logo_width = $this->mwb_wgm_get_template_data($mwb_wgm_mail_settings,'mwb_wgm_mail_setting_upload_logo_dimension_width');
				if(empty($giftcard_logo_height))
				{
					$giftcard_logo_height = 70;
				}	
				if(empty($giftcard_logo_width))
				{
					$giftcard_logo_width = 70;
				}

				if(isset($giftcard_upload_logo) && !empty($giftcard_upload_logo))
				{
					$giftcard_logo_html = "<img src='$giftcard_upload_logo' width='".$giftcard_logo_width."px' height='".$giftcard_logo_height."px'/>";
				}

				$giftcard_disclaimer = $this->mwb_wgm_get_template_data($mwb_wgm_mail_settings,'mwb_wgm_mail_setting_disclaimer');
				$giftcard_disclaimer = stripcslashes($giftcard_disclaimer);
				
				$background_image = $this->mwb_wgm_get_template_data($mwb_wgm_mail_settings,'mwb_wgm_mail_setting_background_logo_value');
				
				$featured_image = wp_get_attachment_url( get_post_thumbnail_id($templateid) );

				if(isset($background_image) && !empty($background_image))
				{

					$giftcard_event_html = "<img src='$background_image' 
					width='100%' />";
				}

				$giftcard_event_html = apply_filters('mwb_wgm_default_events_html',$giftcard_event_html,$args);
				
				if(isset($featured_image) && !empty($featured_image))
				{
					$giftcard_featured = "<img src='$featured_image'/>";
				}
				$template_css = '';
				$template_css = apply_filters('mwb_wgm_template_custom_css',$template_css,$templateid);
				if( $template_css != null && $template_css != ""){
					$giftcard_css = "<style>$template_css</style>";
				}
				else
				{
					$giftcard_css = "<style>
					table{
						background-color: transparent;
					}
					</style>";
				}
				if(isset($args['message']) && !empty($args['message']))
				{
					$templatehtml = str_replace('[MESSAGE]', $args['message'], $templatehtml);
				}
				else
				{
					$templatehtml = str_replace('[MESSAGE]', '', $templatehtml);
				}
				if(isset($args['to']) && !empty($args['to']))
				{
					$templatehtml = str_replace('[TO]', $args['to'], $templatehtml);
				}
				else
				{
					$templatehtml = str_replace('To:', '', $templatehtml);
					$templatehtml = str_replace('To :', '', $templatehtml);
					$templatehtml = str_replace('To-', '', $templatehtml);
					$templatehtml = str_replace('[TO]', '', $templatehtml);
				}
				if(isset($args['from']) && !empty($args['from'])){
					$templatehtml = str_replace('[FROM]', $args['from'], $templatehtml);
				}
				else
				{
					$templatehtml = str_replace('From :', '', $templatehtml);
					$templatehtml = str_replace('From-', '', $templatehtml);
					$templatehtml = str_replace('From:', '', $templatehtml);
					$templatehtml = str_replace('[FROM]', '', $templatehtml);
				}

				//Background Image for Mothers Day
				$mothers_day_backimg = MWB_WGC_URL.'assets/images/back.png';

				$mothers_day_backimg = "<span class='back_bubble_img'><img src='$mothers_day_backimg'/></span>";

				//Arrow Image for Mothers Day
				$arrow_img = MWB_WGC_URL.'assets/images/arrow.png';
				$arrow_img = "<img src='$arrow_img'  class='center-on-narrow' style='height: auto;font-family: sans-serif; font-size: 15px; line-height: 20px; color: rgb(85, 85, 85); border-radius: 5px;' width='135' height='170' border='0'>";

				$bgimg = "background='$featured_image'";

				$templatehtml = str_replace('[ARROWIMAGE]', $arrow_img, $templatehtml);
				$templatehtml = str_replace('[BACK]', $mothers_day_backimg, $templatehtml);
				$templatehtml = str_replace('[LOGO]', $giftcard_logo_html, $templatehtml);
				$templatehtml = str_replace('[AMOUNT]', $args['amount'], $templatehtml);
				$templatehtml = str_replace('[COUPON]', $args['coupon'], $templatehtml);
				$templatehtml = str_replace('[EXPIRYDATE]', $args['expirydate'], $templatehtml);
				$templatehtml = str_replace('[DISCLAIMER]', $giftcard_disclaimer, $templatehtml);
				$templatehtml = str_replace('[DEFAULTEVENT]', $giftcard_event_html, $templatehtml);
				$templatehtml = str_replace('[FEATUREDIMAGE]', $giftcard_featured, $templatehtml);
				$templatehtml = str_replace('[BGIMAGE]', $bgimg, $templatehtml);
				$templatehtml = $giftcard_css.$templatehtml;	
				
				$templatehtml = apply_filters("mwb_wgm_email_template_html", $templatehtml,$args);

				return $templatehtml;
			}
		}
		
		/*Function to get template data from data base*/
		public function mwb_wgm_get_template_data($mwb_wgm_settings,$key){
			$mwb_wgm_data = '';

			if (isset($mwb_wgm_settings) && is_array($mwb_wgm_settings) && !empty($mwb_wgm_settings)) {
				if (array_key_exists($key, $mwb_wgm_settings)) {
					$mwb_wgm_data = $mwb_wgm_settings[$key];
				}
				return $mwb_wgm_data;
			}
			else{
				return $mwb_wgm_data;
			}
		}

		/**
		 * Create the Gift Certificate(Coupon)
		 * @since 1.0.0
		 * @name mwb_wgc_create_gift_coupon()
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link https://www.makewebbetter.com/
		 */
		function mwb_wgc_create_gift_coupon( $gift_couponnumber, $couponamont, $order_id, $product_id,$to ){
			$mwb_wgc_enable = mwb_wgc_giftcard_enable();
			if( $mwb_wgc_enable ){
				$alreadycreated = get_post_meta( $order_id, 'mwb_wgm_order_giftcard', true );
				if( $alreadycreated != 'send' ){
					$coupon_code = $gift_couponnumber; // Code
					$amount = $couponamont; // Amount
					$discount_type = 'fixed_cart'; 
					$coupon_description = "GIFTCARD ORDER #$order_id";
					$coupon = array(
						'post_title' => $coupon_code,
						'post_content' => $coupon_description,
						'post_excerpt' => $coupon_description,
						'post_status' => 'publish',
						'post_author' => get_current_user_id(),
						'post_type'		=> 'shop_coupon'
					);
					$new_coupon_id = wp_insert_post( $coupon );
					$general_settings = get_option( 'mwb_wgm_general_settings' , array());
					$product_settings = get_option( 'mwb_wgm_product_settings' , array());
					$individual_use = $this->mwb_wgm_get_template_data($general_settings,'mwb_wgm_general_setting_giftcard_individual_use');
					$individual_use = ($individual_use == 'on') ? 'yes':'no';

					$usage_limit = $this->mwb_wgm_get_template_data($general_settings,'mwb_wgm_general_setting_giftcard_use');
					$usage_limit = (empty($usage_limit)) ? 1:$usage_limit;

					$expiry_date = $this->mwb_wgm_get_template_data($general_settings,'mwb_wgm_general_setting_giftcard_expiry');
					$expiry_date = ($expiry_date == '') ? 1:$expiry_date;
					
					$free_shipping = $this->mwb_wgm_get_template_data($general_settings,'mwb_wgm_general_setting_giftcard_freeshipping');
					$free_shipping = ($free_shipping == 'on') ? 'yes':'no';

					$apply_before_tax = $this->mwb_wgm_get_template_data($general_settings,'mwb_wgm_general_setting_giftcard_applybeforetx');
					if(!isset($apply_before_tax) || empty( $apply_before_tax )){
						$apply_before_tax = 'yes';
					}

					$minimum_amount = $this->mwb_wgm_get_template_data($general_settings,'mwb_wgm_general_setting_giftcard_minspend');
					if(!isset($minimum_amount) || empty( $minimum_amount )){
						$minimum_amount = ' ';
					}
					$maximum_amount = $this->mwb_wgm_get_template_data($general_settings,'mwb_wgm_general_setting_giftcard_maxspend');
					if(!isset($maximum_amount) || empty( $maximum_amount )){
						$maximum_amount = ' ';
					}
					$exclude_sale_items = $this->mwb_wgm_get_template_data($product_settings,'mwb_wgm_product_setting_giftcard_ex_sale');
					$exclude_sale_items = ($exclude_sale_items == 'on') ? 'yes':'no';
					
					$exclude_products = $this->mwb_wgm_get_template_data($product_settings,'mwb_wgm_product_setting_exclude_product');
					$exclude_products = (is_array($exclude_products) && !empty($exclude_products)) ? implode(',', $exclude_products):'';
					$exclude_category = $this->mwb_wgm_get_template_data($product_settings,'mwb_wgm_product_setting_exclude_category');
					if(!isset($exclude_category) || empty( $exclude_category )){
						$exclude_category = ' ';
					}
					
					$mwb_wgm_extra_data = array();
					$mwb_wgm_extra_data = apply_filters('mwb_wgm_add_more_coupon_fields', $mwb_wgm_extra_data, $new_coupon_id,$product_id);

					if (isset($mwb_wgm_extra_data['expiry_date']) && !empty($mwb_wgm_extra_data['expiry_date'])) {
						$expirydate = $mwb_wgm_extra_data['expiry_date'];
					}
					else{
						$todaydate = date_i18n("Y-m-d");
						if( $expiry_date > 0 || $expiry_date === 0 ){
							$expirydate = date_i18n( "Y-m-d", strtotime( "$todaydate +$expiry_date day" ) );
						}
						else{
							$expirydate = "";
						}
					}

					// Add meta
					//price based on country
					if( class_exists('WCPBC_Pricing_Zone')){

						update_post_meta( $new_coupon_id, 'zone_pricing_type', 'exchange_rate' );  	
					}
					update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
					update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
					update_post_meta( $new_coupon_id, 'individual_use', $individual_use );
					update_post_meta( $new_coupon_id, 'usage_limit', $usage_limit );
					update_post_meta( $new_coupon_id, 'apply_before_tax', $apply_before_tax );
					update_post_meta( $new_coupon_id, 'free_shipping', $free_shipping );
					update_post_meta( $new_coupon_id, 'minimum_amount', $minimum_amount );
					update_post_meta( $new_coupon_id, 'maximum_amount', $maximum_amount );
					update_post_meta( $new_coupon_id, 'exclude_sale_items', $exclude_sale_items );
					update_post_meta( $new_coupon_id, 'mwb_wgm_giftcard_coupon', $order_id );
					update_post_meta( $new_coupon_id, 'mwb_wgm_giftcard_coupon_unique', "online" );
					update_post_meta( $new_coupon_id, 'mwb_wgm_giftcard_coupon_product_id', $product_id );
					update_post_meta( $new_coupon_id, 'mwb_wgm_giftcard_coupon_mail_to', $to );

					
					//exclude products.
					if(isset($mwb_wgm_extra_data['exclude_per_products']) && $mwb_wgm_extra_data['exclude_per_products'] != ''){
						update_post_meta( $new_coupon_id, 'exclude_product_ids', $mwb_wgm_extra_data['exclude_per_products'] );
					}else{
						update_post_meta( $new_coupon_id, 'exclude_product_ids', $exclude_products );
					}

					//exclude category
					if (isset($mwb_wgm_extra_data['exclude_per_product_category']) && is_array($mwb_wgm_extra_data['exclude_per_product_category']) && !empty($mwb_wgm_extra_data['exclude_per_product_category'])) {
						update_post_meta( $new_coupon_id, 'exclude_product_categories', $mwb_wgm_extra_data['exclude_per_product_category'] );
						
					}
					else{
						update_post_meta( $new_coupon_id, 'exclude_product_categories', $exclude_category );
					}


					$woo_ver = WC()->version;

					if($woo_ver < '3.6.0') {
						update_post_meta( $new_coupon_id, 'expiry_date', $expirydate );
					}
					else {
						$expirydate = strtotime($expirydate);
						update_post_meta( $new_coupon_id, 'date_expires', $expirydate );
					}
					
					return true;
				}
				else{
					return false;
				}
			}
		}

		/**
		 * Some common mail functionality handles here
		 * @since 1.0.0
		 * @name mwb_wgc_common_functionality()
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link https://www.makewebbetter.com/
		 */
		public function mwb_wgc_common_functionality($mwb_wgm_common_arr,$order){

			if(!empty($mwb_wgm_common_arr)){
				$to = $mwb_wgm_common_arr['to'];
				$from = $mwb_wgm_common_arr['from'];
				$item_id = $mwb_wgm_common_arr['item_id'];
				$product_id = $mwb_wgm_common_arr['product_id'];
				$mwb_wgm_pricing = get_post_meta( $product_id, 'mwb_wgm_pricing', true );
				$templateid = $mwb_wgm_pricing['template'];
				$args['from'] = $from;
				$args['to'] = $to;
				$args['message'] = stripcslashes($mwb_wgm_common_arr['gift_msg']);
				$args['coupon'] = apply_filters('mwb_wgm_qrcode_coupon',$mwb_wgm_common_arr['gift_couponnumber']);
				$args['expirydate'] = $mwb_wgm_common_arr['expirydate_format'];
				//$args['amount'] =  wc_price($mwb_wgm_common_arr['couponamont']);
				//price based on country
				if( class_exists('WCPBC_Pricing_Zones')){
					
					$billing_country = $order->get_billing_country();
					//$shipping_country = $order->get_shipping_country();
					
					$wcpbc_the_zone = WCPBC_Pricing_Zones::get_zone_by_country( $billing_country );
					if (isset($wcpbc_the_zone) && $wcpbc_the_zone != null) {
						$cur = $wcpbc_the_zone->get_currency();
						$amt = $wcpbc_the_zone->get_exchange_rate_price($mwb_wgm_common_arr['couponamont']);
						$args['amount'] = get_woocommerce_currency_symbol($cur).$amt;
					}
					else {
						$args['amount'] =  wc_price($mwb_wgm_common_arr['couponamont']);
					}	  	
				}
				else {
					$args['amount'] =  wc_price($mwb_wgm_common_arr['couponamont']);
				}
				$args['templateid'] = isset($mwb_wgm_common_arr['selected_template']) && !empty($mwb_wgm_common_arr['selected_template']) ? $mwb_wgm_common_arr['selected_template'] : $templateid;
				$args['product_id'] = $product_id;

				$args = apply_filters('mwb_wgm_common_functionality_template_args',$args,$mwb_wgm_common_arr);

				$message = apply_filters( 'mwb_wgm_customizable_email_template', $this->mwb_wgm_create_gift_template($args), $args);

				$order_id = $mwb_wgm_common_arr['order_id'];
				$mwb_wgm_pre_gift_num = get_post_meta($order_id, "$order_id#$item_id", true);
				
				if(is_array($mwb_wgm_pre_gift_num) && !empty($mwb_wgm_pre_gift_num)){
					$mwb_wgm_pre_gift_num[] = $mwb_wgm_common_arr['gift_couponnumber'];
					update_post_meta($order_id, "$order_id#$item_id", $mwb_wgm_pre_gift_num);
				}else{
					$mwb_wgm_code_arr = array();
					$mwb_wgm_code_arr[] = $mwb_wgm_common_arr['gift_couponnumber'];
					update_post_meta($order_id, "$order_id#$item_id", $mwb_wgm_code_arr);
				}

				$headers = array('Content-Type: text/html; charset=UTF-8');
				
				$mwb_wgm_common_arr = apply_filters('mwb_wgm_add_pdf_settings',$mwb_wgm_common_arr,$message);

				$attachments = isset($mwb_wgm_common_arr['attachments'])? $mwb_wgm_common_arr['attachments']:array();
				$to = isset($mwb_wgm_common_arr['to'])? $mwb_wgm_common_arr['to']:$to;
				$headers = isset($mwb_wgm_common_arr['header'])?$mwb_wgm_common_arr['header']:$headers;
				$disable_buyer_notice = isset($mwb_wgm_common_arr['disable_buyer_notice'])?$mwb_wgm_common_arr['disable_buyer_notice']:'off';
				
				$get_mail_status = true;
				$get_mail_status = apply_filters('mwb_send_mail_status',$get_mail_status);

				if($get_mail_status)
				{	
					$mwb_wgm_mail_settings = get_option( 'mwb_wgm_mail_settings' , array());

					$send_subject = $this->mwb_wgm_get_template_data($mwb_wgm_mail_settings,'mwb_wgm_mail_setting_giftcard_subject');

					if (isset($mwb_wgm_common_arr['send_subject']) && !empty($mwb_wgm_common_arr['send_subject'])) {
						$send_subject = $mwb_wgm_common_arr['send_subject'];
					}

					$bloginfo = get_bloginfo();

					if(empty($send_subject))
					{
						
						$send_subject = "$bloginfo:";
						$send_subject.=__(" Hurry!!! Giftcard is Received", MWB_WGM_DOMAIN);
					}
					$send_subject = str_replace('[SITENAME]', $bloginfo, $send_subject);
					$send_subject = str_replace('[BUYEREMAILADDRESS]', $from, $send_subject);
					$send_subject = stripcslashes($send_subject);
					$send_subject = html_entity_decode($send_subject,ENT_QUOTES, "UTF-8");
					if(isset($mwb_wgm_common_arr['delivery_method']))
					{
						
						if($mwb_wgm_common_arr['delivery_method'] == 'Mail to recipient')
						{	
							$woo_ver = WC()->version;
							if( $woo_ver < '3.0.0')
							{
								$from=$order->billing_email;
							}
							else
							{
								$from=$order->get_billing_email();
							}
						}
						if($mwb_wgm_common_arr['delivery_method'] == 'Downloadable')
						{
							$woo_ver = WC()->version;
							if( $woo_ver < '3.0.0')
							{
								$to=$order->billing_email;
							}
							else
							{
								$to=$order->get_billing_email();
							}
						}
						if ($mwb_wgm_common_arr['delivery_method'] == 'shipping') {
							$to = $to;
						}
					}

					wc_mail($to, $send_subject, $message, $headers, $attachments);
					do_action('mwb_wgm_send_mail_to_others',$send_subject,$message,$attachments);
					
					if (isset($mwb_wgm_common_arr['receive_subject']) && !empty($mwb_wgm_common_arr['receive_subject'])) {
						$receive_subject = $mwb_wgm_common_arr['receive_subject'];
					}
					else{
						$receive_subject = "$bloginfo:";
						$receive_subject.=__( " Gift Card is Sent Successfully","woocommerce_gift_cards_lite" );
					}

					if (isset($mwb_wgm_common_arr['receive_message']) && !empty($mwb_wgm_common_arr['receive_message'])) {
						$receive_message = $mwb_wgm_common_arr['receive_message'];
					}
					else{
						$receive_message = "$bloginfo:";
						$receive_message.= __(" Gift Card is Sent Successfully to the Email Id: [TO]","woocommerce_gift_cards_lite");

					}
					
					$receive_message = stripcslashes( $receive_message );
					$receive_message = str_replace( '[TO]', $to, $receive_message );
					$receive_subject = stripcslashes( $receive_subject );

					if ($disable_buyer_notice == 'off') {
						wc_mail( $from, $receive_subject, $receive_message );
					}
					
				}
				return true;
			}
			else{
				return false;
			}
		}

		/**
		 * Check the Expiry Date for priniting this out inside the Email template
		 * @since 1.0.0
		 * @name mwb_wgc_check_expiry_date()
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link https://www.makewebbetter.com/
		 */
		public function mwb_wgc_check_expiry_date( $expiry_date ){
			
			$todaydate = date_i18n("Y-m-d");
			if(isset($expiry_date) && !empty($expiry_date)){
				if($expiry_date > 0 || $expiry_date === 0){
					
					$general_settings = get_option('mwb_wgm_general_settings', array());
					$selected_date = $this->mwb_wgm_get_template_data($general_settings,'mwb_uwgc_general_setting_enable_selected_format');

					if( isset($selected_date) && $selected_date != null && $selected_date != "")
					{
							$selected_date = apply_filters('mwb_wgm_selected_date_format',$selected_date);
							$expirydate_format = date_i18n($selected_date,strtotime( "$todaydate +$expiry_date day" ));
					}
					else
					{
						$expirydate_format = date_i18n('Y-m-d',strtotime( "$todaydate +$expiry_date day" ));
					}
				}
			}
			else{
				$expirydate_format = __("No Expiration", MWB_WGM_DOMAIN);
			}
			return $expirydate_format;
		}
	}
}
