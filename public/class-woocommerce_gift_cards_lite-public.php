<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_gift_cards_lite
 * @subpackage Woocommerce_gift_cards_lite/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_gift_cards_lite
 * @subpackage Woocommerce_gift_cards_lite/public
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Woocommerce_gift_cards_lite_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_gift_cards_lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_gift_cards_lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce_gift_cards_lite-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$giftcard_message_length = trim(get_option("mwb_wgm_other_setting_giftcard_message_length", 300));
		$mwb_wgm_render_product_custom_page = get_option('mwb_wgm_render_product_custom_page','off');
		if( empty($giftcard_message_length) )
		{
			$giftcard_message_length = 300;
		}
		$mwb_wgm = array(
					'pricing_type' => array(),
					'product_id'=>0,
					'price_field'=>sprintf( __("Price: %sField is empty",'woocommerce_gift_cards_lite'),"</b>"),
					'to_empty'=>sprintf( __("Recipient Email: %sField is empty.",'woocommerce_gift_cards_lite'),"</b>"),
					'to_invalid'=>sprintf( __("Recipient Email: %sInvalid email format.",'woocommerce_gift_cards_lite'),"</b>"),
					'from_empty'=>sprintf( __("From: %sField is empty.",'woocommerce_gift_cards_lite'),"</b>"),
					'msg_empty'=>sprintf( __("Message: %sField is empty.",'woocommerce_gift_cards_lite'),"</b>"),
					'msg_length_err'=>sprintf( __("Message: %sMessage length cannot exceed %s characters.",'woocommerce_gift_cards_lite'),"</b>",$giftcard_message_length),
					'msg_length'=>$giftcard_message_length,
					'price_range'=>sprintf( __("Price Range: %sPlease enter price within Range.",'woocommerce_gift_cards_lite'),"</b>")
					
			);
		if( is_product() ){	
			global $post;
			$product_id = $post->ID;
			$product_types = wp_get_object_terms( $product_id, 'product_type' );
			if(isset($product_types[0])){
				$product_type = $product_types[0]->slug;
				if($product_type == 'wgm_gift_card'){
					$mwb_wgm_pricing = get_post_meta( $product_id, 'mwb_wgm_pricing', true );
					$mwb_wgm_method_enable = get_option( "mwb_wgm_send_giftcard", false );
					if( $mwb_wgm_method_enable == false ){
						$mwb_wgm_method_enable = 'normal_mail';
					}
					$mwb_wgm['pricing_type'] = $mwb_wgm_pricing;
					$mwb_wgm['product_id'] = $product_id;
					wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce_gift_cards_lite-public.js', array('jquery') );
					wp_localize_script( $this->plugin_name, 'mwb_wgm', $mwb_wgm );
					wp_enqueue_script( $this->plugin_name );
				}
			}	
		}
	}

	/**
	 * Adds some of the fields for Gift Card Product
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_before_add_to_cart_button()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_before_add_to_cart_button(){
		global $product;
		if( isset( $product ) && !empty( $product ) ){
			$mwb_wgc_enable = mwb_wgc_giftcard_enable();
			if($mwb_wgc_enable){
				$product_id = $product->get_id();
				if( isset( $product_id ) && !empty( $product_id ) ){
					$product_types = wp_get_object_terms( $product_id, 'product_type' );
					$product_type = $product_types[0]->slug;
					if( $product_type == 'wgm_gift_card' ){
						$product_pricing = get_post_meta( $product_id, 'mwb_wgm_pricing', true );
						if( isset( $product_pricing ) && !empty( $product_pricing ) ){
							?>
							<div class="mwb_wgm_added_wrapper">
								<?php 
								if( isset( $product_pricing['type'] ) ){
									$product_pricing_type = $product_pricing['type'];
									if($product_pricing_type == 'mwb_wgm_range_price'){	
										$default_price=$product_pricing['default_price'];
										$from_price = $product_pricing['from'];
										$to_price = $product_pricing['to'];
										$text_box_price = ($default_price >= $from_price && $default_price<=$to_price) ? $default_price : $from_price;
										?>
										<p class="mwb_wgm_section">
											<label><?php _e('Enter Price Within Above Range:','woocommerce_gift_cards_lite');?></label>	
											<input type="number" class="input-text mwb_wgm_price" id="mwb_wgm_price" name="mwb_wgm_price" value="<?php echo ($default_price >= $from_price && $default_price<=$to_price) ? $default_price : $from_price; ?>" max="<?php echo $to_price;?>" min="<?php echo $from_price;?>">
										</p>
										<?php
									}
								
									if($product_pricing_type == 'mwb_wgm_default_price'){
										$default_price = $product_pricing['default_price'];?>
										<input type="hidden" class="mwb_wgm_price" id="mwb_wgm_price" name="mwb_wgm_price" value="<?php echo $default_price?>">
										<?php
									}
									if($product_pricing_type == 'mwb_wgm_selected_price'){
										$default_price = $product_pricing['default_price'];
										$selected_price = $product_pricing['price'];
										if( !empty( $selected_price ) ){
										?>
										<p class="mwb_wgm_section">
											<label><?php _e('Choose Gift Card Selected Price:','woocommerce_gift_cards_lite');?></label><br/>
											<?php 
											$selected_prices = explode('|', $selected_price);
											if( isset( $selected_prices ) && !empty( $selected_prices ) ){?>
												<select name="mwb_wgm_price" class="mwb_wgm_price" id="mwb_wgm_price" >
												<?php 
												foreach($selected_prices as $price){	
													if($price == $default_price){
														?>
														<option  value="<?php echo $price; ?>" selected><?php echo wc_price( $price )?></option>
														<?php
													}
													else{
														?>
														<option  value="<?php echo $price; ?>"><?php echo wc_price( $price )?></option>
														<?php
													}
												}?>
												</select>
												<?php 
											}?>
											</p>	
										<?php 
										}
									}
									if($product_pricing_type == 'mwb_wgm_user_price'){
										$default_price = $product_pricing['default_price'];
										?>
										<p class="mwb_wgm_section">
											<label><?php _e('Enter Gift Card Price','woocommerce_gift_cards_lite');?>:</label>	

											<input type="number" class="mwb_wgm_price" id="mwb_wgm_price" name="mwb_wgm_price" min="1" value=<?php echo $default_price?>>
										</p>
										<?php
									}
								}
								?>
								<p class="mwb_wgm_section">
									<label class="mwb_wgc_label"><?php _e('From','woocommerce_gift_cards_lite');?>:</label>	
									<input type="text"  name="mwb_wgm_from_name" id="mwb_wgm_from_name" class="mwb_wgm_from_name" placeholder="<?php _e('Enter the sender name','woocommerce_gift_cards_lite'); ?>" required="required">
								</p>
								<p class="mwb_wgm_section">
									<label class="mwb_wgc_label"><?php _e('Gift Message:','woocommerce_gift_cards_lite');?></label>	
									<textarea name="mwb_wgm_message" id="mwb_wgm_message" class="mwb_wgm_message"></textarea>
									<?php 
										$giftcard_message_length = trim(get_option("mwb_wgm_other_setting_giftcard_message_length", 300));
										if( empty( $giftcard_message_length ) )
										{
											$giftcard_message_length = 300;
										}
										_e('Characters:','woocommerce_gift_cards_lite');
									 ?>
									 (<span id="mwb_box_char">0</span>/<?php _e($giftcard_message_length); ?>)
								</p>
								<div class="mwb_wgm_delivery_method mwb_wgm_section">
									<input type="radio" name="mwb_wgm_send_giftcard" value="Mail to recipient" class="mwb_wgm_send_giftcard" checked="checked" id="mwb_wgm_to_email_send" style="display: none;">
									<div class="mwb_wgm_delivery_via_email">
									<label class="mwb_wgc_label"><?php _e('To:','woocommerce_gift_cards_lite');?></label>
									<input type="email"  name="mwb_wgm_to_email" id="mwb_wgm_to_email" class="mwb_wgm_to_email" placeholder="<?php _e('Enter the Recipient Email (Required)','woocommerce_gift_cards_lite'); ?>" required="required"></div>
								</div>
							</div>
							<?php
						}
					}
				}
			}
		}
	}

	/**
	 * Adds the meta data into the Cart Item
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_add_cart_item_data()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_add_cart_item_data($the_cart_data, $product_id){
		$mwb_wgc_enable = mwb_wgc_giftcard_enable();
		if($mwb_wgc_enable){
			$product_types = wp_get_object_terms( $product_id, 'product_type' );
			if( isset( $product_types[0] ) ){
				$product_type = $product_types[0]->slug;
				if($product_type == 'wgm_gift_card'){
					if( isset( $_POST['mwb_wgm_to_email'] ) && !empty( $_POST['mwb_wgm_to_email'] ) ){
						$product_pricing = get_post_meta($product_id, 'mwb_wgm_pricing', true);
						if( isset( $product_pricing ) && !empty( $product_pricing ) ){
							$item_meta['mwb_wgm_to_email'] = sanitize_text_field( $_POST['mwb_wgm_to_email'] );
							$item_meta['mwb_wgm_from_name'] = sanitize_text_field( $_POST['mwb_wgm_from_name'] );
							if(isset( $_POST['mwb_wgm_message'] )  && !empty( $_POST['mwb_wgm_message'] ) ){
								$item_meta['mwb_wgm_message'] = sanitize_text_field( $_POST['mwb_wgm_message'] );
							}
							$item_meta['delivery_method'] = sanitize_text_field( $_POST['mwb_wgm_send_giftcard'] );
							if( isset( $_POST['mwb_wgm_price'] ) ){
								$item_meta['mwb_wgm_price'] = sanitize_text_field( $_POST['mwb_wgm_price'] );
							}
							$the_cart_data ['product_meta'] = array( 'meta_data' => $item_meta );
						}
					}
				}
			}
		}
		return $the_cart_data;
	}

	/**
	 * List out the Meta Data into the Cart Items
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_get_item_data()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_get_item_data($item_meta, $existing_item_meta){
		$mwb_wgc_enable = mwb_wgc_giftcard_enable();
		if( $mwb_wgc_enable ){
			if(isset( $existing_item_meta ['product_meta' ]['meta_data'] ) ){
				foreach ( $existing_item_meta['product_meta'] ['meta_data'] as $key => $val ){
					if( $key == 'mwb_wgm_to_email' ){
						$item_meta [] = array (
								'name' => __( 'To','woocommerce_gift_cards_lite' ),
								'value' => stripslashes( $val ),
						);
					}
					if( $key == 'mwb_wgm_from_name' ){
						$item_meta [] = array (
								'name' => __( 'From','woocommerce_gift_cards_lite' ),
								'value' => stripslashes( $val ),
						);
					}
					if( $key == 'mwb_wgm_message' ){
						$item_meta [] = array (
								'name' => __( 'Gift Message','woocommerce_gift_cards_lite' ),
								'value' => stripslashes( $val ),
						);
					}
				}
			}
		}
		return $item_meta;
	}

	/**
	 * Set the Gift Card Price into Cart
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_before_calculate_totals()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_before_calculate_totals($cart){
		$mwb_wgc_enable = mwb_wgc_giftcard_enable();
		if( $mwb_wgc_enable ){
			if( isset( $cart ) && !empty( $cart ) ){
				foreach ( $cart->cart_contents as $key => $value ){
					$product_id = $value['product_id'];
					$pro_quant = $value['quantity'];
					if( isset( $value['product_meta']['meta_data'] ) ){
						if( isset( $value['product_meta']['meta_data']['mwb_wgm_price'] ) ){
							$gift_price = $value['product_meta']['meta_data']['mwb_wgm_price'];
							$value['data']->set_price($gift_price);
						}
					}
				}
			}
		}
	}

	/**
	 * Displays the Different Price type for Gift Cards into single product page
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_get_price_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_get_price_html( $price_html, $product ){
		$mwb_wgc_enable = mwb_wgc_giftcard_enable();
		if( $mwb_wgc_enable ){
			$product_id = $product->get_id();
			if( isset( $product_id ) ){
				$product_types = wp_get_object_terms( $product_id, 'product_type' );
				if( isset( $product_types[0] ) ){
					$product_type = $product_types[0]->slug;
					if( $product_type == 'wgm_gift_card' ){
						$product_pricing = get_post_meta($product_id, 'mwb_wgm_pricing', true);
						if( isset( $product_pricing ) && !empty( $product_pricing ) ){
							if( isset( $product_pricing['type'] ) ){
								$product_pricing_type = $product_pricing['type'];
								if( $product_pricing_type == 'mwb_wgm_default_price' ){
									$new_price = "";
									$default_price = $product_pricing['default_price'];
									$price_html	= $price_html;
								}
								if($product_pricing_type == 'mwb_wgm_range_price'){
									$price_html = "";
									$from_price = $product_pricing['from'];
									$to_price = $product_pricing['to'];
									$price_html .= '<ins><span class="woocommerce-Price-amount amount">'.wc_price($from_price).' - '.wc_price($to_price).'</span></ins>';
								}
								if($product_pricing_type == 'mwb_wgm_selected_price'){
									$selected_price = $product_pricing['price'];
									if(!empty($selected_price))
									{
										$selected_prices = explode('|', $selected_price);
										if(isset($selected_prices) && !empty($selected_prices)){
											$price_html = '';
											$price_html .= '<ins><span class="woocommerce-Price-amount amount">';
											$last_range = end($selected_prices);
											$price_html .= wc_price($selected_prices[0]).'-'.wc_price($last_range);
											$price_html .= '</span></ins>';
										}
									}
								}
								if($product_pricing_type == 'mwb_wgm_user_price'){
									$price_html = apply_filters("mwb_wgm_user_price_text", __('Enter Giftcard Value:','woocommerce_gift_cards_lite'));
								}
							}
						}
					}
				}
			}
			return $price_html;
		}
	}

	/**
	 * Handles Coupon Generation process on the order Status Changed process
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_order_status_changed()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_order_status_changed( $order_id, $old_status, $new_status ){
		$mwb_wgc_enable = mwb_wgc_giftcard_enable();
		if( $mwb_wgc_enable ){
			if($old_status != $new_status){
				if($new_status == 'completed' || $new_status == 'processing'){
					$mailalreadysend = get_post_meta( $order_id, 'mwb_wgm_order_giftcard', true );
					if($mailalreadysend == "send"){
						return;	
					}
					$gift_msg = "";
					$to = "";
					$from = "";
					$gift_order = false;
					$original_price = 0;
					$order = wc_get_order( $order_id );
					foreach( $order->get_items() as $item_id => $item ){
						$mailsend = false;
						$item_quantity = wc_get_order_item_meta($item_id, '_qty', true);
						$product=$item->get_product();
						$pro_id = $product->get_id();
						$item_meta_data = $item->get_meta_data();
						$gift_date_check = false;
						$gift_date = "";
						$original_price = 0;
						foreach ( $item_meta_data as $key => $value ){
							if( isset( $value->key) && $value->key=="To" && !empty($value->value ) ){
								$mailsend = true;
								$to = $value->value;
							}
							if( isset( $value->key ) && $value->key=="From" && !empty( $value->value ) ){
								$mailsend = true;
								$from = $value->value;
							}
							if( isset( $value->key ) && $value->key=="Message" && !empty($value->value ) ){
								$mailsend = true;
								$gift_msg = $value->value;
							}
							if( isset( $value->key ) && $value->key=="Delivery Method" && !empty( $value->value ) ){
								$mailsend = true;
								$delivery_method = $value->value;				
							}
							if( isset( $value->key ) && $value->key=="Original Price" && !empty($value->value ) ){
								$mailsend = true;
								$original_price = $value->value;				
							}
						}
						if( $mailsend ){
							$gift_order = true;
							$inc_tax_status = get_option('woocommerce_prices_include_tax',false);
							if( $inc_tax_status == "yes" ){
								$inc_tax_status = true;
							}
							else{
								$inc_tax_status = false;
							}
							$couponamont = $original_price;
							$giftcard_coupon_length = get_option("mwb_wgm_general_setting_giftcard_coupon_length", 5);
								for ($i=1; $i <= $item_quantity; $i++) { 
								$gift_couponnumber = mwb_wgc_coupon_generator($giftcard_coupon_length);
									if($this->mwb_wgc_create_gift_coupon($gift_couponnumber, $couponamont, $order_id, $item['product_id'],$to)){
									$todaydate = date_i18n("Y-m-d");
									$expiry_date = get_option("mwb_wgm_general_setting_giftcard_expiry", false);
									$expirydate_format = $this->mwb_wgc_check_expiry_date($expiry_date);
									$mwb_wgm_common_arr['order_id'] = $order_id;
									$mwb_wgm_common_arr['product_id'] = $pro_id;
									$mwb_wgm_common_arr['to'] = $to;
									$mwb_wgm_common_arr['from'] = $from;
									$mwb_wgm_common_arr['gift_couponnumber'] = $gift_couponnumber;
									$mwb_wgm_common_arr['gift_msg'] = $gift_msg;
									$mwb_wgm_common_arr['expirydate_format'] = $expirydate_format;
									$mwb_wgm_common_arr['couponamont'] = $couponamont;
									$mwb_wgm_common_arr['delivery_method'] = $delivery_method;
									$mwb_wgm_common_arr['item_id'] = $item_id;
									if( $this->mwb_wgc_common_functionality( $mwb_wgm_common_arr,$order ) ){
									}
								}								
							}
						}
					}
					if( $gift_order ){
						update_post_meta( $order_id, 'mwb_wgm_order_giftcard', "send" );
					}
				}
			}
		}
	}

	/**
	 * Create the Gift Certificate(Coupon)
	 * @since 1.0.0
	 * @name mwb_wgc_create_gift_coupon()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
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
				$individual_use = get_option("mwb_wgm_general_setting_giftcard_individual_use", "no");
				$usage_limit = get_option("mwb_wgm_general_setting_giftcard_use", 1);
				$expiry_date = get_option("mwb_wgm_general_setting_giftcard_expiry", 1);
				$free_shipping = get_option("mwb_wgm_general_setting_giftcard_freeshipping", 1);
				$apply_before_tax = get_option("mwb_wgm_general_setting_giftcard_applybeforetx", 'yes');
				$minimum_amount = get_option("mwb_wgm_general_setting_giftcard_minspend", '');
				$maximum_amount = get_option("mwb_wgm_general_setting_giftcard_maxspend", '');
				$exclude_sale_items = get_option("mwb_wgm_general_setting_giftcard_ex_sale", "no");
				$todaydate = date_i18n("Y-m-d");
				if( $expiry_date > 0 || $expiry_date === 0 ){
					$expirydate = date_i18n( "Y-m-d", strtotime( "$todaydate +$expiry_date day" ) );
				}
				else{
					$expirydate = "";
				}
				// Add meta
				update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
				update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
				update_post_meta( $new_coupon_id, 'individual_use', $individual_use );
				update_post_meta( $new_coupon_id, 'usage_limit', $usage_limit );
				update_post_meta( $new_coupon_id, 'expiry_date', $expirydate );
				update_post_meta( $new_coupon_id, 'apply_before_tax', $apply_before_tax );
				update_post_meta( $new_coupon_id, 'free_shipping', $free_shipping );
				update_post_meta( $new_coupon_id, 'minimum_amount', $minimum_amount );
				update_post_meta( $new_coupon_id, 'maximum_amount', $maximum_amount );
				update_post_meta( $new_coupon_id, 'exclude_sale_items', $exclude_sale_items );
				update_post_meta( $new_coupon_id, 'mwb_wgm_giftcard_coupon', $order_id );
				update_post_meta( $new_coupon_id, 'mwb_wgm_giftcard_coupon_unique', "online" );
				update_post_meta( $new_coupon_id, 'mwb_wgm_giftcard_coupon_product_id', $product_id );
				update_post_meta( $new_coupon_id, 'mwb_wgm_giftcard_coupon_mail_to', $to );
				return true;
			}
			else{
				return false;
			}
		}
	}

	/**
	 * Some commomn mail functionality handles here
	 * @since 1.0.0
	 * @name mwb_wgc_common_functionality()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_common_functionality($mwb_wgm_common_arr,$order){
		if(!empty($mwb_wgm_common_arr)){
			$to = $mwb_wgm_common_arr['to'];
			$from = $mwb_wgm_common_arr['from'];
			$item_id = $mwb_wgm_common_arr['item_id'];
			$product_id = $mwb_wgm_common_arr['product_id'];
			$args['from'] = $from;
			$args['to'] = $to;
			$args['message'] = stripcslashes($mwb_wgm_common_arr['gift_msg']);
			$args['coupon'] = apply_filters('mwb_wgm_qrcode_coupon',$mwb_wgm_common_arr['gift_couponnumber']);
			$args['expirydate'] = $mwb_wgm_common_arr['expirydate_format'];
			$args['amount'] =  wc_price($mwb_wgm_common_arr['couponamont']);
			$args['product_id'] = $product_id;
			$message = $this->mwb_wgc_giftttemplate($args);
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
			$get_mail_status = true;
			$get_mail_status = apply_filters('mwb_send_mail_status',$get_mail_status);

			if($get_mail_status)
			{	
				
				$bloginfo = get_bloginfo();
				$subject = "$bloginfo:";
				$subject.=__(" Hurry!!! Giftcard is Received",'woocommerce_gift_cards_lite');
				$subject = stripcslashes($subject);
				$subject = html_entity_decode($subject,ENT_QUOTES, "UTF-8");
				if(isset($mwb_wgm_common_arr['delivery_method'])){
					if($mwb_wgm_common_arr['delivery_method'] == 'Mail to recipient'){	
						$from=$order->get_billing_email();
					}
				}
				$headers = array('Content-Type: text/html; charset=UTF-8');
				wc_mail($to, $subject, $message, $headers);
				$subject = "$bloginfo:";
				$subject.=__( " Gift Card is Sent Successfully","woocommerce_gift_cards_lite" );
				$message = "$bloginfo:";
				$message.=__(" Gift Card is Sent Successfully to the Email Id: [TO]","woocommerce_gift_cards_lite");
				$message = stripcslashes( $message );
				$message = str_replace( '[TO]', $to, $message );
				$subject = stripcslashes( $subject );
				wc_mail( $from, $subject, $message );
			}
			return true;
		}
		else{
			return false;
		}
	}

	/**
	 * Returns an HTML Email Template for sending Gift Card Mails
	 * @since 1.0.0
	 * @name mwb_wgc_giftttemplate()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	function mwb_wgc_giftttemplate($args){
		$mwb_wgc_enable = mwb_wgc_giftcard_enable();
		if( $mwb_wgc_enable ){
			$templatehtml = '<style>/* What it does: Remove spaces around the email design added by some email clients. */ /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */ html, body{margin: 0 auto !important; padding: 0 !important; height: 100% !important; width: 100% !important;}body *{box-sizing: border-box;}/* What it does: Stops email clients resizing small text. */ *{-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;}/* What is does: Centers email on Android 4.4 */ div[style*="margin: 16px 0"]{margin:0 !important;}/* What it does: Stops Outlook from adding extra spacing to tables. */ table, td{mso-table-lspace: 0pt !important; mso-table-rspace: 0pt !important;}/* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */ table{border-spacing: 0 !important; border-collapse: collapse !important; table-layout: fixed !important; margin: 0 auto !important;}table table table{table-layout: auto;}/* What it does: Uses a better rendering method when resizing images in IE. */ img{-ms-interpolation-mode:bicubic; width: 100%;}/* What it does: A work-around for iOS meddling in triggered links. */ .mobile-link--footer a, a[x-apple-data-detectors]{color:inherit !important; text-decoration: underline !important;}/* What it does: Prevents underlining the button text in Windows 10 */ .button-link{text-decoration: none !important;}</style><style>/* What it does: Hover styles for buttons */ .button-td, .button-a{transition: all 100ms ease-in;}.button-td:hover, .button-a:hover{background: #555555 !important; border-color: #555555 !important;}/* Media Queries */ @media screen and (max-width: 599px){.email-container{width: 100% !important; margin: auto !important;}/* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */ .fluid{max-width: 100% !important; height: auto !important; margin-left: auto !important; margin-right: auto !important;}/* What it does: Forces table cells into full-width rows. */ .stack-column, .stack-column-center{display: block !important; width: 100% !important; max-width: 100% !important; direction: ltr !important;}/* And center justify these ones. */ .stack-column-center{text-align: center !important;}/* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */ .center-on-narrow{text-align: center !important; display: block !important; margin-left: auto !important; margin-right: auto !important; float: none !important;}table.center-on-narrow{display: inline-block !important;}}</style><center style="width: 100%; background: #222222;"></center><div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">(Optional) This text will appear in the inbox preview, but not the email body.</div><table class="email-container" style="margin: auto;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td align="center" bgcolor="#ffffff">[FEATUREDIMAGE]</td></tr><tr><td dir="ltr" align="center" valign="top" bgcolor="#ffffff" width="100%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="line-height: 0; overflow: hidden; height: 30px;"></td></tr><tr><td class="stack-column-center" style="padding: 20px 0px; vertical-align: top; border-right: 1px solid #dddddd !important;" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #fff; padding: 0 20px 20px;" valign="top"><p style="margin: 10px 0 30px 0; text-align: left; font-weight: bold; font-size: 28px;"><span style="color: #333333; margin: 20px 0;">[AMOUNT]</span></p></td></tr><tr><td dir="ltr" style="padding: 30px 20px 0 20px;" valign="top"><p style="color: #333333; font-family: sans-serif; margin: 0px; font-size: 16px;"><span style="font-weight: bold; display: inline-block; text-align: left; font-size: 14px; width: 130px;">COUPON CODE:</span><span style="font-weight: bold; text-transform: uppercase; display: inline-block; text-align: left; font-size: 14px;">[COUPON]</span></p><p style="color: #333333; font-family: sans-serif; margin-bottom: 30px; font-size: 16px;"><span style="font-weight: bold; display: inline-block; text-align: left; font-size: 14px; width: 130px;">EXPIRY DATE:</span><span style="font-weight: bold; text-transform: uppercase; display: inline-block; text-align: left; font-size: 14px;">[EXPIRYDATE]</span></p></td></tr></tbody></table></td><td class="stack-column-center" style="padding: 20px 0px;" valign="top" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #fff; padding: 0px 30px 0 20px; min-height: 170px; height: auto;" valign="top"><p style="color: #333333; font-size: 15px;margin-bottom: 30px">[MESSAGE]</p></td></tr><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; padding: 0 0 0 20px; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #333333;" valign="top"><p style="margin-bottom: 0px; font-size: 16px; margin-top: 20px"><span style="font-weight: bold; display: inline-block; width: 20%; font-size: 15px;">From-</span><span style="display: inline-block; width: 75%; text-align: left; font-size: 14px;">[FROM]</span></p></td></tr><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; padding: 0 0 0 20px; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #333333;" valign="top"><p style="margin-top: 0px; font-size: 16px; line-height: 25px;"><span style="font-weight: bold; display: inline-block; width: 20%; font-size: 15px;">To-</span><span style="display: inline-block; width: 75%; text-align: left; font-size: 14px;">[TO]</span></p></td></tr></tbody></table></td></tr><tr><td style="line-height: 0; overflow: hidden; height: 30px;"></td></tr></tbody></table></td></tr><tr><td bgcolor="#ffffff"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="text-align: center; padding: 10px; border-top: 1px solid #dddddd !important; font-family: sans-serif; font-size: 16px; mso-height-rule: exactly; line-height: 20px; color: #333333;">[DISCLAIMER]</td></tr></tbody></table></td></tr></tbody></table>';
			$featured_image = MWB_WGC_URL.'assets/images/giftcard.jpg';
			$giftcard_featured = "";
			if(isset($featured_image) && !empty($featured_image)){
				$giftcard_featured = "<img src='$featured_image'/>";
			}
			$message = isset( $args['message'] ) ? $args['message'] : '';
			$templatehtml = str_replace('[MESSAGE]', $message, $templatehtml);
			$templatehtml = str_replace('[AMOUNT]', $args['amount'], $templatehtml);
			$templatehtml = str_replace('[COUPON]', $args['coupon'], $templatehtml);
			$templatehtml = str_replace('[EXPIRYDATE]', $args['expirydate'], $templatehtml);
			$templatehtml = str_replace('[TO]', $args['to'], $templatehtml);
			$templatehtml = str_replace('[FROM]', $args['from'], $templatehtml);
			$templatehtml = str_replace('[FEATUREDIMAGE]', $giftcard_featured, $templatehtml);
			$templatehtml = str_replace('[DISCLAIMER]', '', $templatehtml);
			return $templatehtml;
		}
	}

	/**
	 * Adds the Order-item-meta inside the each gift card orders
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_checkout_create_order_line_item()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_checkout_create_order_line_item( $item,$cart_key,$values ){
		$mwb_wgc_enable = mwb_wgc_giftcard_enable();
		if( $mwb_wgc_enable ){
			if (isset ( $values ['product_meta'] )){
				foreach ( $values ['product_meta'] ['meta_data'] as $key => $val ){	
					$order_val = stripslashes( $val );
					if($val){
						if($key == "mwb_wgm_to_email"){
							$item->add_meta_data('To',$order_val);
						}
						if($key == "mwb_wgm_from_name"){
							$item->add_meta_data('From',$order_val);
						}
						if($key == "mwb_wgm_message"){
							$item->add_meta_data('Message',$order_val);
						}
						if($key == 'mwb_wgm_price'){
							$item->add_meta_data('Original Price',$order_val);
						}
						if($key == 'delivery_method'){
							$item->add_meta_data('Delivery Method',$order_val);
						}
					}
				}
			}
		}
	}

	/**
	 * Check the Expiry Date for priniting this out inside the Email template
	 * @since 1.0.0
	 * @name mwb_wgc_check_expiry_date()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_check_expiry_date( $expiry_date ){
		$todaydate = date_i18n("Y-m-d");
		if(isset($expiry_date) && !empty($expiry_date)){
			if($expiry_date > 0 || $expiry_date === 0){
				$expirydate = date_i18n( "Y-m-d", strtotime( "$todaydate +$expiry_date day" ) );
				$expirydate_format = date_create($expirydate);
				$selected_date = get_option("mwb_wgm_general_setting_enable_selected_format_1", false);
				if( isset($selected_date) && $selected_date !=null && $selected_date != "")
				{

					$expirydate_format = date_format($expirydate_format,$selected_date);
				}
				else
				{
					$expirydate_format = date_format($expirydate_format,"jS M Y");
				}
			}
		}
		else{
			$expirydate_format = __("No Expiration", "woocommerce_gift_cards_lite");
		}
		return $expirydate_format;
	}

	/**
	 * Removes Add to cart button and Adds View Card Button
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_loop_add_to_cart_link()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_loop_add_to_cart_link( $link, $product ){
		$mwb_wgc_enable = mwb_wgc_giftcard_enable();
		if( $mwb_wgc_enable ){
			$product_id = $product->get_id();
			if( isset( $product_id ) ){
				$product_types = wp_get_object_terms( $product_id, 'product_type' );
				if( isset( $product_types[0] ) ){
					$product_type = $product_types[0]->slug;
					if( $product_type == 'wgm_gift_card' ){
						$product_pricing = get_post_meta($product_id, 'mwb_wgm_pricing', true);
						if( isset( $product_pricing ) && !empty( $product_pricing ) ){
							$link = sprintf( '<a rel="nofollow" href="%s" class="%s">%s</a>',
									esc_url( get_the_permalink() ),
									esc_attr( isset( $class ) ? $class : 'button' ),
									esc_html( apply_filters("mwb_wgm_view_card_text",__("VIEW CARD","woocommerce_gift_cards_lite"))) 
							);
						}
					}
				}
			}
		}
		return $link;
	}

	/**
	 * Enable the Taxes for Gift Card if the required setting is enabled
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_product_is_taxable()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_product_is_taxable($taxable, $product){
		$mwb_wgc_enable = mwb_wgc_giftcard_enable();
		if( $mwb_wgc_enable ){
			$giftcard_tax_cal_enable = get_option("mwb_wgm_general_setting_tax_cal_enable", "off");
			if($giftcard_tax_cal_enable == 'off'){
				$product_id = $product->get_id();
				$product_types = wp_get_object_terms( $product_id, 'product_type' );
				if(isset($product_types[0])){
					$product_type = $product_types[0]->slug;
					if($product_type == 'wgm_gift_card'){
						$taxable = false;
					}
				}
			}
		}
		return $taxable;
	}

	/**
	 * Set the error notice div on single product page
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_before_main_content()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_before_main_content(){
		global $post;
		if( isset($post->ID ) ){
			$product_id = $post->ID;
			$product_types = wp_get_object_terms( $product_id, 'product_type' );
			if( isset( $product_types[0] ) ){
				$product_type = $product_types[0]->slug;
				if($product_type == 'wgm_gift_card'){
					?>
					<div class="woocommerce-error" id="mwb_wgm_error_notice" style="display:none;"></div>
					<?php 
				}
			}
		}
	}

	/**
	 * Show/Hide Gift Card product from shop page depending upon the required setting
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_product_query()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_product_query($query, $query_object){
		$mwb_wgc_enable = mwb_wgc_giftcard_enable();
		if($mwb_wgc_enable){
			$giftcard_shop_page = get_option("mwb_wgm_general_setting_shop_page_enable", "off");
			if($giftcard_shop_page != "on"){
				if(is_shop()){
					$args = array( 
									'post_type' => 'product', 
									'posts_per_page' => -1, 
									'meta_key' => 'mwb_wgm_pricing' 
								);
					$gift_products = array();
					$loop = new WP_Query( $args );
					if( $loop->have_posts() ):
						while ( $loop->have_posts() ) : $loop->the_post(); 
							global $product;
							$product_id = $loop->post->ID;
							$product_types = wp_get_object_terms( $product_id, 'product_type' );
							if(isset($product_types[0])){
								$product_type = $product_types[0]->slug;
								if($product_type == 'wgm_gift_card'){
									$gift_products[] = $product_id;
								}
							}	
						endwhile;
					endif;	
					$query->set('post__not_in',$gift_products);
				}
			}
		}
	}

	/**
	 * Adjust the Gift Card Amount, when it has been applied to any product for getting discount
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_new_order_item()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_new_order_item( $item_id,$item ){
		if(get_class($item)=='WC_Order_Item_Coupon'){
			$coupon_code=$item->get_code();
			$the_coupon = new WC_Coupon( $coupon_code );
			$coupon_id = $the_coupon->get_id();
			if(isset($coupon_id)){
				$giftcardcoupon = get_post_meta( $coupon_id, 'mwb_wgm_giftcard_coupon', true );
				if( !empty($giftcardcoupon) ){	
					$mwb_wgc_discount=$item->get_discount();
					$mwb_wgc_discount_tax=$item->get_discount_tax();
					$amount = get_post_meta( $coupon_id, 'coupon_amount', true );
					$total_discount = $mwb_wgc_discount+$mwb_wgc_discount_tax;
					if( $amount < $total_discount ){
						$remaining_amount = 0;
					}
					else{
						$remaining_amount = $amount - $total_discount;
						$remaining_amount = round($remaining_amount,2);
					}		
					update_post_meta( $coupon_id, 'coupon_amount', $remaining_amount );
				}
			}
		}
	}

	/**
	 * Disable the Shipping fee if there is only Gift Card Product
	 * @since 1.0.0
	 * @name mwb_wgc_wc_shipping_enabled()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_wc_shipping_enabled($enable){
		if(is_checkout() || is_cart()){	
			global $woocommerce;
			$gift_bool = false;
			$other_bool = false;
			$gift_bool_ship = false;
			if(isset(WC()->cart) && !empty(WC()->cart)){
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ){	
					$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
					$product_types = wp_get_object_terms( $product_id, 'product_type' );
					if(isset($product_types[0])){
						$product_type = $product_types[0]->slug;
						if($product_type == 'wgm_gift_card'){
							if($cart_item['product_meta']['meta_data']['delivery_method'] == 'Mail to recipient' || $cart_item['product_meta']['meta_data']['delivery_method'] == 'Downloadable'){
								$gift_bool = true;
							}
							elseif($cart_item['product_meta']['meta_data']['delivery_method'] == 'Shipping'){
								$gift_bool_ship = true;
							}
						}
						else if(!$cart_item['data']->is_virtual()){
							$other_bool = true;
						}
					}
				}
				if($gift_bool && !$gift_bool_ship && !$other_bool){
					$enable = false;
				}
				else{
					$enable = true;
				}
			}
		}
		return $enable;
	}
}
