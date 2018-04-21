<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_gift_cards_lite
 * @subpackage Woocommerce_gift_cards_lite/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_gift_cards_lite
 * @subpackage Woocommerce_gift_cards_lite/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Woocommerce_gift_cards_lite_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce_gift_cards_lite-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();
		if(isset($screen->id)){	
			$pagescreen = $screen->id;
			if($pagescreen == 'product' || $pagescreen == 'shop_order' || $pagescreen == 'woocommerce_page_mwb-wgc-setting-lite'){
				$giftcard_tax_cal_enable = get_option("mwb_wgm_general_setting_tax_cal_enable", "off");
				$mwb_wgc = array(
						'is_tax_enable_for_gift' => $giftcard_tax_cal_enable,
				);
				wp_register_script($this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce_gift_cards_lite-admin.js');
				wp_localize_script($this->plugin_name, 'mwb_wgc', $mwb_wgc );
				wp_enqueue_script($this->plugin_name );
			}
		}
	}

	/**
	 * Add a submenu inside the Woocommerce Menu Page
	 * @since 1.0.0
	 * @name mwb_wgc_admin_menu()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_admin_menu(){
		add_submenu_page( "woocommerce", __("WooCommerce Gift Manager","woocommerce_gift_cards_lite"), __("Gift Manager Lite","woocommerce_gift_cards_lite"), "manage_options", "mwb-wgc-setting-lite", array($this, "mwb_wgc_admin_setting"));
	}

	/**
	 * Including a File for displaying the required setting page for setup the plugin
	 * @since 1.0.0
	 * @name mwb_wgc_admin_setting()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	function mwb_wgc_admin_setting(){
		include_once MWB_WGC_DIRPATH.'/admin/partials/woocommerce_gift_cards_lite-admin-display.php';
	}

	/**
	 * Create a custom Product Type for Gift Card
	 * @since 1.0.0
	 * @name mwb_wgc_gift_card_product()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_gift_card_product( $types ){
		$mwb_wgc_enable = mwb_wgc_giftcard_enable();
		if($mwb_wgc_enable){
			$types[ 'wgm_gift_card' ] = __( 'Gift Card', 'woocommerce_gift_cards_lite');
		}
		return $types;
	}

	/**
	 * Provide multiple Price variations for Gift Card Product
	 * @since 1.0.0
	 * @name mwb_wgc_get_pricing_type()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	function mwb_wgc_get_pricing_type(){
		$pricing_options = array(
				'mwb_wgm_default_price' => __('Default Price','woocommerce_gift_cards_lite'),
				'mwb_wgm_range_price' => __('Price Range','woocommerce_gift_cards_lite'),
				'mwb_wgm_selected_price' => __('Selected Price','woocommerce_gift_cards_lite'),
				'mwb_wgm_user_price' => __('User Price','woocommerce_gift_cards_lite'),
		);
		return apply_filters('mwb_wgm_pricing_type', $pricing_options);
	}

	/**
	 * Add some required fields (data-tabs) for Gift Card product
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_product_options_general_product_data()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_product_options_general_product_data(){
		global $post;
		$product_id = $post->ID;
		if(isset($product_id)){
			if ( ! current_user_can( 'edit_post',$product_id ) ) {
	    		return;
	   		}
	   	}
		$mwb_wgm_pricing = get_post_meta($product_id, 'mwb_wgm_pricing', true);
		$selected_pricing = isset($mwb_wgm_pricing['type'])?$mwb_wgm_pricing['type']:false;
		$giftcard_enable = get_option("mwb_wgm_general_setting_enable", false);
		$default_price = "";
		$from = "";
		$to = "";
		$price = "";
		$default_price  = isset($mwb_wgm_pricing['default_price'])?$mwb_wgm_pricing['default_price']:0;
		if($selected_pricing){
			switch ($selected_pricing)
			{
				case 'mwb_wgm_range_price':
					$from = isset($mwb_wgm_pricing['from'])?$mwb_wgm_pricing['from']:0;
					$to = isset($mwb_wgm_pricing['to'])?$mwb_wgm_pricing['to']:0;
					break;
				case 'mwb_wgm_selected_price':
					$price = isset($mwb_wgm_pricing['price'])?$mwb_wgm_pricing['price']:0;
					break;
				default:
					//nothing for default
			}
		}
		if($giftcard_enable == 'on'){
			echo '<div class="options_group show_if_wgm_gift_card"><div id="mwb_wgm_loader" style="display: none;">
							<img src="'.MWB_WGC_URL.'assets/images/loading.gif">
						</div>';
			$previous_post = $post;
			$post = $previous_post;
			woocommerce_wp_text_input( array( 'id' => 'mwb_wgm_default', 'value'=>"$default_price" ,'label' => __( 'Default Price', 'woocommerce_gift_cards_lite' ), 'placeholder' => wc_format_localized_price( 0 ), 'description' => __( 'Gift card default price.', 'woocommerce_gift_cards_lite' ), 'data_type' => 'price', 'desc_tip' => true ) );
			woocommerce_wp_select( array( 'id' => 'mwb_wgm_pricing', 'value'=>"$selected_pricing", 'label' => __( 'Pricing type', 'woocommerce_gift_cards_lite' ), 'options' => $this->mwb_wgc_get_pricing_type() ) );
		
			//Range Price
			//StartFrom
			woocommerce_wp_text_input( array( 'id' => 'mwb_wgm_from_price', 'value'=>"$from", 'label' => __( 'From Price', 'woocommerce_gift_cards_lite' ), 'placeholder' => wc_format_localized_price( 0 ), 'description' => __( 'Gift card price range start from.', 'woocommerce_gift_cards_lite' ), 'data_type' => 'price', 'desc_tip' => true ) );
			//EndTo
			woocommerce_wp_text_input( array( 'id' => 'mwb_wgm_to_price', 'value'=>"$to", 'label' => __( 'To Price', 'woocommerce_gift_cards_lite' ), 'placeholder' => wc_format_localized_price( 0 ), 'description' => __( 'Gift card price range end to.', 'woocommerce_gift_cards_lite' ), 'data_type' => 'price', 'desc_tip' => true ) );
		
			//Selected Price
			woocommerce_wp_textarea_input(  array( 'id' => 'mwb_wgm_selected_price', 'value'=>"$price", 'label' => __( 'Price', 'woocommerce_gift_cards_lite' ), 'desc_tip' => 'true', 'description' => __( 'Enter an price using seperator |. Ex : (10 | 20)', 'woocommerce_gift_cards_lite'), 'placeholder' => '10|20|30'  ) );
			echo '</div>';		
		}
	}

	/**
	 * Saves the all required details for each product
	 * @since 1.0.0
	 * @name mwb_wgc_save_post()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_save_post(){
		if(isset($post->ID))
		{	
			if ( ! current_user_can( 'edit_post', $post->ID ) ) {
				return;
			}
			$product_id = $post->ID;
			if( isset( $_POST['product-type'] ) ){
				$_POST['product-type'] = sanitize_text_field($_POST['product-type']);
				if( $_POST['product-type'] == 'wgm_gift_card' ){	
					$mwb_wgm_categ_enable = get_option('mwb_wgm_general_setting_categ_enable','off');
					if( $mwb_wgm_categ_enable == 'off' ){
						$term = __('Gift Card', 'woocommerce_gift_cards_lite' );
						$taxonomy = 'product_cat';
						$term_exist = term_exists( $term, $taxonomy);
						if ($term_exist == 0 || $term_exist == null){
							$args['slug'] = "mwb_wgm_giftcard";
							$term_exist = wp_insert_term( $term, $taxonomy, $args );
						}
						wp_set_object_terms( $product_id, $_POST['product-type'], 'product_type' );
						wp_set_post_terms( $product_id, $term_exist, $taxonomy);
					}
					$mwb_wgm_pricing = array();
					$selected_pricing = isset( $_POST['mwb_wgm_pricing'] ) ? sanitize_text_field( $_POST['mwb_wgm_pricing'] ) : false;
					if( $selected_pricing ){
						$default_price = isset( $_POST['mwb_wgm_default'] ) ? sanitize_text_field( $_POST['mwb_wgm_default'] ):0;
						update_post_meta( $product_id, "_regular_price", $default_price );
						update_post_meta( $product_id, "_price", $default_price );
						$mwb_wgm_pricing['default_price'] = $default_price;
						$mwb_wgm_pricing['type'] = $selected_pricing;
						switch ( $selected_pricing ){
							case 'mwb_wgm_range_price':
								$from = isset( $_POST['mwb_wgm_from_price'] ) ? sanitize_text_field( $_POST['mwb_wgm_from_price'] ) : 0;
								$to = isset( $_POST['mwb_wgm_to_price'] ) ? sanitize_text_field( $_POST['mwb_wgm_to_price'] ) : 0;
								$mwb_wgm_pricing['type'] = $selected_pricing;
								$mwb_wgm_pricing['from'] = $from;
								$mwb_wgm_pricing['to'] = $to;
								break;
							case 'mwb_wgm_selected_price':
								$price = isset( $_POST['mwb_wgm_selected_price'] ) ? sanitize_text_field( $_POST['mwb_wgm_selected_price'] ) : 0;
								$mwb_wgm_pricing['type'] = $selected_pricing;
								$mwb_wgm_pricing['price'] = $price;
								break;
									
							case 'mwb_wgm_user_price':
								$mwb_wgm_pricing['type'] = $selected_pricing;
								break;
							default:
								//nothing for default
						}
					}
					do_action( 'mwb_wgm_product_pricing', $mwb_wgm_pricing );
					$mwb_wgm_pricing = apply_filters( 'mwb_wgm_product_pricing', $mwb_wgm_pricing );
					update_post_meta( $product_id, 'mwb_wgm_pricing', $mwb_wgm_pricing );
				}
			}
		}
	}

	/**
	 * Hides some of the tabs if the Product is Gift Card
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_product_data_tabs()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_product_data_tabs( $tabs ){
		if( isset( $tabs ) && !empty( $tabs ) ){
			foreach( $tabs as $key=>$tab ){	
				if( $key != 'general' && $key != 'advanced' && $key != 'inventory' && $key != 'shipping'){
					$tabs[$key]['class'][] = 'hide_if_wgm_gift_card'; 
				}
			}	
		}
		return $tabs;
	}

	/**
	 * Add the Gift Card Coupon code as an item meta for each Gift Card Order
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_after_order_itemmeta()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_after_order_itemmeta( $item_id, $item, $_product ){
		if ( ! current_user_can( 'edit_shop_orders' ) ){
			return;
		}
		$mwb_wgc_enable = mwb_wgc_giftcard_enable();
		if( $mwb_wgc_enable ){
			if( isset( $_GET['post'] ) ){	
				$order_id = sanitize_text_field( $_GET['post'] );
				$order = new WC_Order($order_id);
				$order_status = $order->get_status();
				if( $order_status == 'completed' || $order_status == 'processing' ){
					if( $_product != null ){
						$product_id = $_product->get_id();						
						if( isset( $product_id ) && !empty( $product_id ) ){
							$product_types = wp_get_object_terms( $product_id, 'product_type' );
							if( isset($product_types[0]) ){
								$product_type = $product_types[0]->slug;
								if( $product_type == 'wgm_gift_card' ){
									$giftcoupon = get_post_meta($order_id, "$order_id#$item_id", true);
									if( empty( $giftcoupon ) ){
										$giftcoupon = get_post_meta( $order_id, "$order_id#$product_id", true );
									}
									if( is_array( $giftcoupon ) && !empty( $giftcoupon ) ){	
										?>
										<p style="margin:0;"><b><?php _e('Gift Coupon','woocommerce_gift_cards_lite');?> :</b>
										<?php
										foreach ( $giftcoupon as $key => $value ){
											?>
											<span style="background: rgb(0, 115, 170) none repeat scroll 0% 0%; color: white; padding: 1px 5px 1px 6px; font-weight: bolder; margin-left: 10px;"><?php echo $value;?></span>
											<?php
										}
										?>
										</p>
										<?php
									}
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Hides the non-required Item Meta
	 * @since 1.0.0
	 * @name mwb_wgc_woocommerce_hidden_order_itemmeta()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_hidden_order_itemmeta( $order_items ){
		if ( ! current_user_can( 'edit_shop_orders' ) ){
			return;
		}
		array_push($order_items, 'Delivery Method','Original Price');
        return $order_items;
	}
}
