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
		wp_enqueue_script('select2');
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
						'ajaxurl' => admin_url('admin-ajax.php'),
						'is_tax_enable_for_gift' => $giftcard_tax_cal_enable,
				);
				wp_enqueue_script('mwb_lite_select2', plugin_dir_url( __FILE__ ),'js/select2.min.js',array('jquery'),'1.0.3', false);
				
				wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array(), WC_VERSION );
				
				wp_enqueue_style( 'woocommerce_admin_menu_styles' );
				
				wp_enqueue_style( 'woocommerce_admin_styles' );
				
				wp_register_script($this->plugin_name.'clipboard', plugin_dir_url( __FILE__ )."js/clipboard.min.js");
			
				wp_enqueue_script($this->plugin_name.'clipboard' );
				
				wp_register_script($this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce_gift_cards_lite-admin.js',array('jquery','mwb_lite_select2','wc-enhanced-select'),'1.0.2',false);
				
				wp_localize_script($this->plugin_name, 'mwb_wgc', $mwb_wgc );
				
				wp_enqueue_script($this->plugin_name);
				
				wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip' ), WC_VERSION,false );
				wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.js', array( 'jquery' ), WC_VERSION, false );
					$locale  = localeconv();
					$decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';
					$params = array(
						/* translators: %s: decimal */
						'i18n_decimal_error'                => sprintf( __( 'Please enter in decimal (%s) format without thousand separators.', 'woocommerce_gift_cards_lite' ), $decimal ),
						/* translators: %s: price decimal separator */
						'i18n_mon_decimal_error'            => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', 'woocommerce_gift_cards_lite' ), wc_get_price_decimal_separator() ),
						'i18n_country_iso_error'            => __( 'Please enter in country code with two capital letters.', 'woocommerce_gift_cards_lite' ),
						'i18_sale_less_than_regular_error'  => __( 'Please enter in a value less than the regular price.', 'woocommerce_gift_cards_lite' ),
						'decimal_point'                     => $decimal,
						'mon_decimal_point'                 => wc_get_price_decimal_separator(),
						'strings' => array(
							'import_products' => __( 'Import', 'woocommerce_gift_cards_lite' ),
							'export_products' => __( 'Export', 'woocommerce_gift_cards_lite' ),
						),
						'urls' => array(
							'import_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_importer' ) ),
							'export_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_exporter' ) ),
						),
					);

					wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );
					wp_enqueue_script( 'woocommerce_admin' );
					wp_enqueue_script('media-upload');
				    wp_enqueue_script('thickbox');
				    wp_enqueue_style('thickbox');
			}
		}
	}

	/**
	 * Add a submenu inside the Woocommerce Menu Page
	 * @since 1.0.0
	 * @name mwb_wgc_admin_menu()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgc_admin_menu(){
		add_submenu_page( "woocommerce", __("Giftware Lite Manager","woocommerce_gift_cards_lite"), __("Giftware Lite Manager","woocommerce_gift_cards_lite"), "manage_options", "mwb-wgc-setting-lite", array($this, "mwb_wgc_admin_setting"));
	}

	/**
	 * Including a File for displaying the required setting page for setup the plugin
	 * @since 1.0.0
	 * @name mwb_wgc_admin_setting()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wgc_admin_setting(){
		include_once MWB_WGC_DIRPATH.'/admin/partials/woocommerce_gift_cards_lite-admin-display.php';
	}

	/**
	 * Create a custom Product Type for Gift Card
	 * @since 1.0.0
	 * @name mwb_wgc_gift_card_product()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
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
	 * @link https://www.makewebbetter.com/
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
	 * @link https://www.makewebbetter.com/
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
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgc_save_post(){
		global $post;
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
	 * @link https://www.makewebbetter.com/
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
	 * @link https://www.makewebbetter.com/
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
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgc_woocommerce_hidden_order_itemmeta( $order_items ){
		if ( ! current_user_can( 'edit_shop_orders' ) ){
			return;
		}
		array_push($order_items, 'Delivery Method','Original Price');
        return $order_items;
	}
	/**
	 * Generate input type text fields
	 * @since 1.0.0
	 * @name mwb_wgm_generate_input_text_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_generate_input_text_html( $value)
	{
		$inputTextvalue = get_option($value['id'], false);
		?>
		<input type="text" name = "<?php echo $value['id']?>" id = "<?php echo $value['id']?>" value = "<?php echo $inputTextvalue;?>" class = "<?php echo $value['class']?>" 
		<?php
		if (array_key_exists('custom_attribute', $value)) {
			foreach ($value['custom_attribute'] as $attribute_name => $attribute_val) {
				echo  $attribute_name ;
				echo  "=$attribute_val"; 
			}
		}
		?>
		<?php
		if (array_key_exists('custom_html', $value)) {
			echo $value['custom_html'];
		}
		?> 
		><?php
	}
	/**
	 * Generate input type number fields
	 * @since 1.0.0
	 * @name mwb_wgm_generate_input_number_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_generate_input_number_html( $value)
	{ 
		$inputNumbervalue = get_option($value['id'], false);
		?>
		<input type="number" value = "<?php echo $inputNumbervalue;?>" name = "<?php echo $value['id'];?>" id = "<?php echo $value['id'];?>" class="<?php echo $value['class']?>"
		<?php
		if (array_key_exists('custom_attribute', $value)) {
			foreach ($value['custom_attribute'] as $attribute_name => $attribute_val) {
				echo  $attribute_name ;
				echo  "=$attribute_val"; 
			}
		}
		?> >
		<?php
	}
	/**
	 * Generate input type checkbox fields
	 * @since 1.0.0
	 * @name mwb_wgm_generate_checkbox_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_generate_checkbox_html( $value)
	{
		$checkboxvalue = get_option($value['id'], false);
		?>
		<input type="checkbox" <?php echo ( $checkboxvalue == 'on')?"checked='checked'":""?> name = "<?php echo $value['id']?>" id = "<?php echo $value['id']?>" class = "<?php echo $value['class']?>"  > <?php echo $value['description'];?>
		<?php
	}
	/**
	 * Generate Drop down menu fields
	 * @since 1.0.0
	 * @name mwb_wgm_generate_searchSelect_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_generate_searchSelect_html( $value)
	{
		$selectedvalue = get_option( $value['id'], array() );	
		/*print_r($value['options']);
		echo "<br>";
		print_r($selectedvalue);*/	
		?>
		<select name = "<?php echo $value['id'];?>[]" id = "<?php echo $value['id'];?>" multiple = "<?php echo $value['multiple'];?>"
			<?php
			if (array_key_exists('custom_attribute', $value)) {
				foreach ($value['custom_attribute'] as $attribute_name => $attribute_val) {
					echo  $attribute_name.'='.$attribute_val ;					
				}
			}
			if(is_array($value['options']) && !empty($value['options'])){
				foreach($value['options'] as $option){
					$select = 0;
					if(is_array($selectedvalue) && in_array($option['id'], $selectedvalue)){
						$select = 1;
					}
					?>
					><option value="<?php echo $option['id'];?>" <?php echo selected(1, $select);?> ><?php echo $option['name']; ?></option>
					<?php
				}
			}	
			?>
			</select>
		</label>
	<?php	
	}
	/**
	 * Generate input type checkbox fields
	 * @since 1.0.0
	 * @name mwb_wgm_generate_checkbox_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_generate_input_text_with_button_html( $value)
	{
		
		print_r($value);
		$selectedphoto = get_option($value['id'], false);
		?>
		<!-- <input type="text" readonly class="mwb_gw_other_setting_upload_logo_value mwb_gw_new_woo_ver_style_text" id="mwb_gw_other_setting_upload_logo" name="mwb_gw_other_setting_upload_logo" value="<?php echo $giftcard_upload_logo;?>"/>
		<input class="mwb_gw_other_setting_upload_logo button"  type="button" value="<?php _e('Upload Logo','giftware');?>" />
				
		<p id="mwb_gw_other_setting_remove_logo">
			<span class="mwb_gw_other_setting_remove_logo">
				<img src="" width="150px" height="150px" id="mwb_gw_other_setting_upload_image">
				<span class="mwb_gw_other_setting_remove_logo_span">X</span>
			</span>
		</p> -->
		<?php
	}

	/**
	 * Get the entire category in store
	 * @since 1.0.0
	 * @name mwb_wgm_get_category()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_get_category()
	{
		$args = array('taxonomy'=>'product_cat');
		$categories = get_terms($args);
		$category_data = $this->mwb_wgm_show_category($categories);
		return $category_data;	
	}
	/**
	 * Returns the category id and name
	 * @since 1.0.0
	 * @name mwb_wgm_show_category()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_show_category($categories){
		if(isset($categories) && !empty($categories))
		{
			$category = array();
			foreach($categories as $cat)
			{
				$category[]=array(
					'id' => $cat->term_id,
					'name' => $cat->name
				);
			}
			return $category;
		}
	}
	/**
	 * Returns globally excluded products 
	 * @since 1.0.0
	 * @name mwb_wgm_get_product()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_get_product($id){
		$mwb_wgm_exclude_product = get_option( $id, array());
		if( isset( $mwb_wgm_exclude_product ) && !empty( $mwb_wgm_exclude_product ) ){
			$mwb_wgm_get_product = array();
			foreach($mwb_wgm_exclude_product as $pro_id){
				$product      = wc_get_product( $pro_id ); 
				$mwb_wgm_get_product[] = array(
					'id' => $pro_id,
					'name' => $product->get_formatted_name()
				);
			}
			return $mwb_wgm_get_product;
		}
	}
	/**
	 * Generate save button html for setting page
	 * @since 1.0.0
	 * @name mwb_wgm_save_button_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_save_button_html($name){
		?>
		<p class="submit">
			<input type="submit" value="<?php _e('Save changes', 'woocommerce_gift_cards_lite'); ?>" class="button-primary woocommerce-save-button" name="<?php echo $name;?>" id="<?php echo $name;?>" >
		</p><?php
	}
	/**
	 * Generate success notice div on saving the setting
	 * @since 1.0.0
	 * @name mwb_wgm_success_notice_html()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgm_success_notice_html(){
		?>
		<div class="notice notice-success is-dismissible"> 
			<p><strong><?php _e('Settings saved','woocommerce_gift_cards_lite'); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php _e('Dismiss this notice','woocommerce_gift_cards_lite'); ?></span>
			</button>
		</div>
		<?php
	}
	public function get_html_of_buton() {
 	?>
 	<button type="button">Save</button>
 	<?php
 }
}
