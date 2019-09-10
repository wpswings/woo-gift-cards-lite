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
	public $mwb_common_fun;
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

		require_once MWB_WGC_DIRPATH .'includes/class-woocommerce_gift_cards_common_function.php';
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		$this->mwb_common_fun = new Woocommerce_gift_cards_common_function();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function mwb_wgm_enqueue_styles() {

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
		wp_enqueue_style('thickbox');
		wp_enqueue_script('select2');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce_gift_cards_lite-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function mwb_wgm_enqueue_scripts() {
		$screen = get_current_screen();
		wp_enqueue_script('thickbox');
		if(isset($screen->id)){	
			$pagescreen = $screen->id;
			if($pagescreen == 'product' || $pagescreen == 'shop_order' || $pagescreen == 'woocommerce_page_mwb-wgc-setting-lite'){

				$mwb_wgm_general_settings = get_option('mwb_wgm_general_settings', false);

				$giftcard_tax_cal_enable = $this->mwb_common_fun->mwb_wgm_get_template_data($mwb_wgm_general_settings,'mwb_wgm_general_setting_tax_cal_enable');

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
				wp_register_script($this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce_gift_cards_lite-admin.js',array('jquery','mwb_lite_select2','wc-enhanced-select','wp-color-picker'),'1.0.2',true);	


				wp_localize_script($this->plugin_name, 'mwb_wgc', $mwb_wgc );
				
				wp_enqueue_script($this->plugin_name);
				
				wp_register_script( 'woocommerce_admin', WC()->plugin_url() . '/assets/js/admin/woocommerce_admin.js', array( 'jquery', 'jquery-blockui', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip' ), WC_VERSION,false );
				wp_register_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.js', array( 'jquery' ), WC_VERSION, false );
				$locale  = localeconv();
				$decimal = isset( $locale['decimal_point'] ) ? $locale['decimal_point'] : '.';
				$params = array(
					/* translators: %s: decimal */
					'i18n_decimal_error'                => sprintf( __( 'Please enter in decimal (%s) format without thousand separators.', MWB_WGM_DOMAIN ), $decimal ),
					/* translators: %s: price decimal separator */
					'i18n_mon_decimal_error'            => sprintf( __( 'Please enter in monetary decimal (%s) format without thousand separators and currency symbols.', MWB_WGM_DOMAIN ), wc_get_price_decimal_separator() ),
					'i18n_country_iso_error'            => __( 'Please enter in country code with two capital letters.', MWB_WGM_DOMAIN ),
					'i18_sale_less_than_regular_error'  => __( 'Please enter in a value less than the regular price.', MWB_WGM_DOMAIN ),
					'decimal_point'                     => $decimal,
					'mon_decimal_point'                 => wc_get_price_decimal_separator(),
					'strings' => array(
						'import_products' => __( 'Import', MWB_WGM_DOMAIN ),
						'export_products' => __( 'Export', MWB_WGM_DOMAIN ),
					),
					'urls' => array(
						'import_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_importer' ) ),
						'export_products' => esc_url_raw( admin_url( 'edit.php?post_type=product&page=product_exporter' ) ),
					),
				);

				wp_localize_script( 'woocommerce_admin', 'woocommerce_admin', $params );
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'woocommerce_admin' );
				wp_enqueue_script('media-upload');


				$mwb_wgm = array(
					'ajaxurl' => admin_url('admin-ajax.php'),
					'append_option_val'=>__('Select the template from above field',MWB_WGM_DOMAIN),
					'mwb_wgm_nonce' =>  wp_create_nonce( "mwb-wgm-verify-nonce" )
				);

				wp_register_script($this->plugin_name.'admin-product', plugin_dir_url( __FILE__ ) . 'js/woocommerce_gift_cards_lite-product.js',array('jquery'),'1.2.0',false);

				wp_localize_script($this->plugin_name.'admin-product', 'mwb_wgm', $mwb_wgm );
				wp_enqueue_script($this->plugin_name.'admin-product');
			}
		}

		//enqueue script for admin notices.
		$mwb_wgm_notice = array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'mwb_wgm_nonce' =>  wp_create_nonce( "mwb-wgm-verify-notice-nonce" )
		);
		wp_register_script($this->plugin_name.'admin-notice', plugin_dir_url( __FILE__ ) . 'js/mwb-wgm-gift-card-notices.js',array('jquery'),'1.2.0',false);

		wp_localize_script($this->plugin_name.'admin-notice', 'mwb_wgm_notice', $mwb_wgm_notice );
		wp_enqueue_script($this->plugin_name.'admin-notice');

	}

	/**
	 * Add a submenu inside the Woocommerce Menu Page
	 * @since 1.0.0
	 * @name mwb_wgc_admin_menu()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function mwb_wgc_admin_menu(){
		add_submenu_page( "woocommerce", __("Gift Card", MWB_WGM_DOMAIN ), __("Gift Card", MWB_WGM_DOMAIN ), "manage_options", "mwb-wgc-setting-lite", array($this, "mwb_wgc_admin_setting"));
		//hooks to add sub menu 
		do_action('mwb_wgm_admin_sub_menu');
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
			$types[ 'wgm_gift_card' ] = __( 'Gift Card', MWB_WGM_DOMAIN );
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
			'mwb_wgm_default_price' => __('Default Price', MWB_WGM_DOMAIN ),
			'mwb_wgm_range_price' => __('Price Range', MWB_WGM_DOMAIN ),
			'mwb_wgm_selected_price' => __('Selected Price', MWB_WGM_DOMAIN ),
			'mwb_wgm_user_price' => __('User Price', MWB_WGM_DOMAIN ),
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
		$giftcard_enable = mwb_wgc_giftcard_enable();
		
		$default_price = "";
		$from = "";
		$to = "";
		$price = "";
		$default_price  = isset($mwb_wgm_pricing['default_price'])?$mwb_wgm_pricing['default_price']:0;
		$selectedtemplate  = isset($mwb_wgm_pricing['template']) ? $mwb_wgm_pricing['template']:false;
		$default_selected = isset($mwb_wgm_pricing['by_default_tem'])?$mwb_wgm_pricing['by_default_tem']:false;
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
		if($giftcard_enable){
			echo '<div class="options_group show_if_wgm_gift_card"><div id="mwb_wgm_loader" style="display: none;">
			<img src="'.MWB_WGC_URL.'assets/images/loading.gif">
			</div>';
			$previous_post = $post;
			$post = $previous_post;

			woocommerce_wp_text_input( array( 'id' => 'mwb_wgm_default', 'value'=>"$default_price" ,'label' => __( 'Default Price', MWB_WGM_DOMAIN ), 'placeholder' => wc_format_localized_price( 0 ), 'description' => __( 'Gift card default price.', MWB_WGM_DOMAIN ), 'data_type' => 'price', 'desc_tip' => true ) );
			woocommerce_wp_select( array( 'id' => 'mwb_wgm_pricing', 'value'=>"$selected_pricing", 'label' => __( 'Pricing type', MWB_WGM_DOMAIN ), 'options' => $this->mwb_wgc_get_pricing_type() ) );

			 //Range Price
			 //StartFrom
			woocommerce_wp_text_input( array( 'id' => 'mwb_wgm_from_price', 'value'=>"$from", 'label' => __( 'From Price', MWB_WGM_DOMAIN ), 'placeholder' => wc_format_localized_price( 0 ), 'description' => __( 'Gift card price range start from.', MWB_WGM_DOMAIN ), 'data_type' => 'price', 'desc_tip' => true ) );
			 //EndTo
			woocommerce_wp_text_input( array( 'id' => 'mwb_wgm_to_price', 'value'=>"$to", 'label' => __( 'To Price', MWB_WGM_DOMAIN ), 'placeholder' => wc_format_localized_price( 0 ), 'description' => __( 'Gift card price range end to.', MWB_WGM_DOMAIN ), 'data_type' => 'price', 'desc_tip' => true ) );

			 //Selected Price
			woocommerce_wp_textarea_input(  array( 'id' => 'mwb_wgm_selected_price', 'value'=>"$price", 'label' => __( 'Price', MWB_WGM_DOMAIN ), 'desc_tip' => 'true', 'description' => __( 'Enter an price using seperator |. Ex : (10 | 20)', MWB_WGM_DOMAIN), 'placeholder' => '10|20|30'  ) );
		 //Regular Price
			echo 	'<p class="form-field mwb_wgm_default_price_field">
			<label for="mwb_wgm_default_price_field"><b>'.__( 'Instruction', MWB_WGM_DOMAIN).'</b></label>
			<span class="description">'.__( 'WooCommerce Product regular price is used as a gift card price.', MWB_WGM_DOMAIN).'</span>
			</p>';
				//User Price
			echo 	'<p class="form-field mwb_wgm_user_price_field ">
			<label for="mwb_wgm_user_price_field"><b>'.__( 'Instruction', MWB_WGM_DOMAIN).'</b></label>
			<span class="description">'.__( 'User can purchase any amount of Gift Card.', MWB_WGM_DOMAIN).'</span>
			</p>';

			$is_customizable = get_post_meta($product_id,'woocommerce_customizable_giftware',true);
			if( empty( $is_customizable ) ){
				?>
				<p class="form-field mwb_wgm_email_template">
					<label class = "mwb_wgm_email_template" for="mwb_wgm_email_template"><?php _e('Email Template', MWB_WGM_DOMAIN);?></label>
					<select id="mwb_wgm_email_template" multiple="multiple" name="mwb_wgm_email_template[]" class="mwb_wgm_email_template">
						<?php 
						$args = array( 'post_type' => 'giftcard', 'posts_per_page' => -1);
						$loop = new WP_Query( $args );
						$template = array();
						foreach ($loop->posts as $key => $value){
							$template_id = $value->ID;

							$template_title = $value->post_title;
							$template[$template_id] = $template_title;
							$tempselect = "";
							if(is_array($selectedtemplate) && $selectedtemplate != null && in_array($template_id, $selectedtemplate))
							{
								$tempselect = "selected='selected'";
							}
							else
							{
								if($template_id == $selectedtemplate){
									$tempselect = "selected='selected'";
								}
							}
							?>
							<option value="<?php echo $template_id; ?>"<?php echo $tempselect;?>><?php echo $template_title; ?></option>
							<?php
						}
						?>
					</select>
				</p>
				<p class="form-field mwb_wgm_email_defualt_template">
					<label class = "mwb_wgm_email_defualt_template" for="mwb_wgm_email_defualt_template"><?php _e('Which template you want to be selected by default?', MWB_WGM_DOMAIN);?></label>

					<select id="mwb_wgm_email_defualt_template" name = "mwb_wgm_email_defualt_template" style="width: 50%">
						<?php

						if(empty($default_selected))
						{
							?>
							<option value=""><?php _e('Select the template from above field ',MWB_WGM_DOMAIN);?></option>
							<?php
						}
						elseif(is_array($selectedtemplate) && !empty($selectedtemplate) && !empty($default_selected))
						{	
							$args = array( 'post_type' => 'giftcard' ,'post__in' => $selectedtemplate );
							$loop = new WP_Query( $args );
							foreach ($loop->posts as $key => $value){
								$template_id = $value->ID;
								$template_title = $value->post_title;
								$alreadyselected = "";
								if(is_array($selectedtemplate) && in_array($default_selected, $selectedtemplate) && $default_selected == $template_id)
								{	
									$alreadyselected = " selected='selected'";
								}
								?>
								<option value="<?php echo $template_id;?>"<?php echo $alreadyselected;?>><?php echo $template_title; ?></option>
								<?php 
							}
						}
						?>
					</select>
				</p>
				<?php
			}
			do_action('mwb_wgm_giftcard_product_type_field' , $product_id );
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
					$general_settings = get_option('mwb_wgm_general_settings',array());
					
					$mwb_wgm_categ_enable = $this->mwb_common_fun->mwb_wgm_get_template_data($general_settings,'mwb_uwgc_general_setting_categ_enable');

					if( empty($mwb_wgm_categ_enable)){
						$term = __('Gift Card', MWB_WGM_DOMAIN );
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
						if(!isset($_POST['mwb_wgm_email_template']) || empty($_POST['mwb_wgm_email_template']))
						{
							$args = array( 'post_type' => 'giftcard', 'posts_per_page' => -1);
							$loop = new WP_Query( $args );
							$template = array();
							if( $loop->have_posts() ):
								while ( $loop->have_posts() ) : $loop->the_post(); global $product;
									$template_id = $loop->post->ID;
									$template[] = $template_id;
								endwhile;
							endif;
							$mwb_wgm_pricing['template'] = array($template[0]);
						}
						else
						{
							$mwb_wgm_pricing['template'] = $_POST['mwb_wgm_email_template'];
						}
						if (!isset($_POST['mwb_wgm_email_defualt_template']) || empty($_POST['mwb_wgm_email_defualt_template'])) {
							$mwb_wgm_pricing['by_default_tem'] =  $mwb_wgm_pricing['template'];
						}
						else{
							$mwb_wgm_pricing['by_default_tem'] = $_POST['mwb_wgm_email_defualt_template'];
						}
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

					do_action('mwb_wgm_giftcard_product_type_save_fields',$product_id);
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
				if( $key != 'general' && $key != 'advanced' && $key != 'shipping'){
					$tabs[$key]['class'][] = 'hide_if_wgm_gift_card'; 
				}
			}
			$tabs = apply_filters('mwb_wgm_product_data_tabs',$tabs);	
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
										<p style="margin:0;"><b><?php _e('Gift Coupon',MWB_WGM_DOMAIN);?> :</b>
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
									do_action('mwb_wgm_after_order_itemmeta',$item_id, $item, $_product);
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
		array_push($order_items, 'Delivery Method','Original Price','Selected Template');
		$order_items = apply_filters('mwb_wgm_giftcard_hidden_order_itemmeta',$order_items);
		return $order_items;
	}

	/**
		 * Create custom post name Giftcard for creating Giftcard Template
		 *
		 * @name mwb_wgm_giftcard_custompost
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
	public function mwb_wgm_giftcard_custom_post(){
		$labels = array(
			'name'               => __( 'Gift Cards', 'post type general name', MWB_WGM_DOMAIN ),
			'singular_name'      => __( 'Gift Card', 'post type singular name', MWB_WGM_DOMAIN ),
			'menu_name'          => __( 'Gift Cards', 'admin menu', MWB_WGM_DOMAIN ),
			'name_admin_bar'     => __( 'Gift Card', 'add new on admin bar', MWB_WGM_DOMAIN ),
			'add_new'            => __( 'Add New', MWB_WGM_DOMAIN ),
			'add_new_item'       => __( 'Add New Gift Card', MWB_WGM_DOMAIN ),
			'new_item'           => __( 'New Gift Card', MWB_WGM_DOMAIN ),
			'edit_item'          => __( 'Edit Gift Card', MWB_WGM_DOMAIN ),
			'view_item'          => __( 'View Gift Card', MWB_WGM_DOMAIN ),
			'all_items'          => __( 'All Gift Cards', MWB_WGM_DOMAIN ),
			'search_items'       => __( 'Search Gift Cards', MWB_WGM_DOMAIN ),
			'parent_item_colon'  => __( 'Parent Gift Cards:', MWB_WGM_DOMAIN ),
			'not_found'          => __( 'No giftcards found.', MWB_WGM_DOMAIN ),
			'not_found_in_trash' => __( 'No giftcards found in Trash.', MWB_WGM_DOMAIN )
		);
		$mwb_wgm_template = array(
			'create_posts' => false,
		);
		$mwb_wgm_template = apply_filters('mwb_wgm_template_capabilities', $mwb_wgm_template);
		$args = array(
			'labels'             => $labels,
			'description'        => __( 'Description.', MWB_WGM_DOMAIN ),
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'giftcard' ),
			'capability_type'    => 'post',
			'capabilities'		 => $mwb_wgm_template,
			'map_meta_cap' => true,
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor' , 'thumbnail')
		);
		
		register_post_type( 'giftcard', $args );	
	}

		/**
		 * This function is to add meta field like field for instruction how to use shortcode in email template
		 *
		 * @name mwb_wgm_edit_form_after_title
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function mwb_wgm_edit_form_after_title($post) {
			$mwb_wgm_post_type = get_post_type($post);
			if(isset($mwb_wgm_post_type) && $mwb_wgm_post_type == 'giftcard') {
				?>
				<div class="postbox" id="mwb_wgm_mail_instruction" style="display: block;">
					<h2 class="mwb_wgm_handle"><span><?php _e('Instruction for using Shortcode', MWB_WGM_DOMAIN);?></span></h2>
					<div class="mwb_wgm_inside">
						<table  class="form-table">
							<tr>
								<th><?php _e('SHORTCODE', MWB_WGM_DOMAIN);?></th>
								<th><?php _e('DESCRIPTION.', MWB_WGM_DOMAIN);?></th>			
							</tr>
							<tr>
								<td>[LOGO]</td>
								<td><?php _e('Replace with logo of company on email template.', MWB_WGM_DOMAIN);?></td>			
							</tr>
							<tr>
								<td>[TO]</td>
								<td><?php _e('Replace with email of user to which giftcard send.', MWB_WGM_DOMAIN);?></td>
							</tr>
							<tr>
								<td>[FROM]</td>
								<td><?php _e('Replace with email/name of the user who send the giftcard.', MWB_WGM_DOMAIN);?></td>
							</tr>
							<tr>
								<td>[MESSAGE]</td>
								<td><?php _e('Replace with Message of user who send the giftcard.', MWB_WGM_DOMAIN);?></td>
							</tr>
							<tr>
								<td>[AMOUNT]</td>
								<td><?php _e('Replace with Giftcard Amount.', MWB_WGM_DOMAIN);?></td>
							</tr>
							<tr>
								<td>[COUPON]</td>
								<td><?php _e('Replace with Giftcard Coupon Code.', MWB_WGM_DOMAIN);?></td>
							</tr>
							<tr>
								<td>[DEFAULTEVENT]</td>
								<td><?php _e('Replace with Default event image set on Setting.', MWB_WGM_DOMAIN);?></td>
							</tr>
							<tr>
								<td>[EXPIRYDATE]</td>
								<td><?php _e('Replace with Giftcard Expiry Date.', MWB_WGM_DOMAIN);?></td>
							</tr>
							<?php 
							do_action('mwb_wgm_template_custom_shortcode');
							?>
						</table>
					</div>
				</div>
				<?php 
			}
		}
		/*Added Mothers Day Template */
		public function mwb_wgm_mothers_day_template() {


			$mwb_wgm_template = get_option('mwb_wgm_new_mom_template', '');
			if ( empty($mwb_wgm_template ) ) {
				update_option("mwb_wgm_new_mom_template", true);
				$filename = array( MWB_WGC_DIRPATH."assets/images/mom.png");
				
				if (is_array($filename) && !empty($filename)) {
					foreach( $filename as $key => $value ){
						$upload_file = wp_upload_bits(basename($value), null, file_get_contents($value));
						if (!$upload_file['error']) {
							$filename = $upload_file['file'];
						// The ID of the post this attachment is for.

							$parent_post_id = 0;

						// Check the type of file. We'll use this as the 'post_mime_type'.
							$filetype = wp_check_filetype( basename( $filename ), null );
						// Get the path to the upload directory.
							$wp_upload_dir = wp_upload_dir();
						// Prepare an array of post data for the attachment.
							$attachment = array(
								'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
								'post_mime_type' => $filetype['type'],
								'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),

								'post_status'    => 'inherit'
							);
						// Insert the attachment.

							$attach_id = wp_insert_attachment( $attachment, $filename, 0);
						// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
							require_once( ABSPATH . 'wp-admin/includes/image.php' );

						// Generate the metadata for the attachment, and update the database record.
							$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

							wp_update_attachment_metadata( $attach_id, $attach_data );
							$arr[] = $attach_id;


						}
					}
				}
				
				$mwb_wgm_new_mom_template = '<div style="display: none; font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">(Optional) This text will appear in the inbox preview, but not the email body.</div><table class="email-container table-wrap" style="margin: auto;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0" align="center" bgcolor="#efefef;"><tbody><tr><td dir="ltr" style="border: 1px solid #00897b;" align="center" bgcolor="#efefef" width="100%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="logo-content-wrap"><tbody><tr><td class="stack-column-center logo-wrap" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding: 0px 25px; padding-left: 0;" valign="top"><p style="color: #00897b; font-size: 25px; font-family: sans-serif; margin: 0px; padding-left: 10px;"><strong>[LOGO] </strong></p></td></tr></tbody></table></td><td class="stack-column-center content-wrap" style="" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #ffffff; text-align: right !important; padding: 0px 20px;" valign="top"><span style="color: #535151; font-size: 14px; line-height: 18px; display:block;">From-[FROM]</span><span style="color: #535151; font-size: 14px; line-height: 18px; display:block;">To-[TO]</span></td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table class="email-container table-wrap" style="margin: auto;" role="presentation" border="0" width="600" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding-top: 15px;" align="center" valign="top" bgcolor="#00897B" width="100%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" class="img-content-wrap"><tbody><tr><td class="stack-column-center" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding: 0px 25px; padding-left: 0;" valign="top"><span class="img-wrap">[FEATUREDIMAGE]</span></td></tr></tbody></table></td><td class="stack-column-center" style="vertical-align: top;" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff; padding: 0px 30px; text-align: left; " valign="top"><p style="color: rgb(255, 255, 255); font-size: 46px; line-height: 60px; margin-top: 15px; margin-bottom: 15px;">I Love You Mom</p></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td dir="ltr" align="center" valign="top" bgcolor="#fff" width="100%" style="position: relative;"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="stack-column-center" style="vertical-align: top;" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" style="position:relative; z-index:999;"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #ffffff; padding: 0px 30px; text-align: left; background-color: #efefef;" valign="top"><p style="text-align: center; line-height: 25px; color: rgb(21, 21, 21); white-space: pre-line; font-size: 16px; padding: 20px;">[MESSAGE]</p></td></tr></tbody></table></td></tr><tr>[BACK]<td style="padding: 15px 10px; font-size: 26px; text-transform: uppercase; text-align: center; font-weight: bold; color: rgb(39, 39, 39); font-family: sans-serif; position: relative; z-index: 99;"><p style="letter-spacing: 1px; padding: 10px 10px; margin: 0px; text-transform: uppercase; text-align: center; color: #00897b; font-weight: bold; font-size: 13px;">coupon code</p>[COUPON]<p style="letter-spacing: 1px; padding: 15px 10px; margin: 0px; text-transform: uppercase; text-align: center; color: #00897b; font-weight: bold; font-size: 13px;">[EXPIRYDATE]</p></td></tr></tbody></table></td></tr><tr><td dir="ltr" style="padding-top: 12px; padding-bottom: 12px; background-color: #efefef;" align="center" valign="top" bgcolor="#fff" width="100%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="stack-column-center" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="padding: 0px 25px; padding-right: 0;" valign="top"><p style="font-family: sans-serif; font-size: 25px; font-weight: bold; margin: 0px; padding: 5px; color: #272727; text-align: right;">[AMOUNT]</p></td></tr></tbody></table></td><td class="stack-column-center" style="vertical-align: top;" width="50%"><table role="presentation" border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff; padding: 0px 30px; text-align: left; margin-top: 15px;" class="center-on-narrow arrow-img" valign="top">[ARROWIMAGE]</td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table><table role="presentation" border="0" cellspacing="0" cellpadding="0" style="position:relative; z-index:999; background: rgb(0, 137, 123) none repeat scroll 0% 0%; color: rgb(255, 255, 255); margin: auto;" width="600" class="table-wrap footer-wrap"><tbody><tr><td style="padding: 10px; text-align: center; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly;"><p style="font-weight: bold; padding-top: 15px; padding-bottom: 15px; font-size: 16px;">[DISCLAIMER]</p></td></tr></tbody></table><style>.img-wrap > img{width:100%;}.back_bubble_img{bottom: 0;content: "";left: 0;margin: 0 auto;position: absolute;right: 0;}.back_bubble_img >img{width:100%;}@media screen and (max-width: 600px){.email-container{width: 100% !important;margin: auto !important;}/* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */.fluid{max-width: 90% !important;height: auto !important;margin-left: auto !important;margin-right: auto !important;}/* What it does: Forces table cells into full-width rows. */<br/>.stack-column,.stack-column-center{display: block !important;width: 100% !important;max-width: 100% !important;direction: ltr !important;}/* And center justify these ones. */.stack-column-center{text-align: center !important;}/* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */.center-on-narrow{text-align: center !important;display: block !important;margin-left: auto !important;margin-right: auto !important;float: none !important;}table.center-on-narrow{display: inline-block !important;}.footer-wrap{width:100%;}}@media screen and (max-width: 500px){.img-content-wrap .stack-column-center{display: block; width: 100%;}.table-wrap{width:100%;}.logo-content-wrap .content-wrap{width:70%;}.logo-content-wrap .logo-wrap{width:30%;}.center-on-narrow.arrow-img{padding: 0 !important;}}</style>';


				$gifttemplate_new = array(
					'post_title' => __('Happy Mothers Day',MWB_WGM_DOMAIN),
					'post_content' => $mwb_wgm_new_mom_template,
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_type'		=> 'giftcard'
				);
				$parent_post_id = wp_insert_post( $gifttemplate_new );
				//update_post_meta($parent_post_id,'mwb_css_field',trim($new_mom_css));
				set_post_thumbnail( $parent_post_id, $arr[0] );

			}

		}
		/*Added New Template*/
		public function mwb_wgm_new_template() {

			$mwb_wgm_template = get_option('mwb_wgm_happy_birthday_template', '');
			if ( empty($mwb_wgm_template) ) {
				update_option("mwb_wgm_happy_birthday_template", true);
				$filename = array( MWB_WGC_DIRPATH."assets/images/birthday.png");
				if (isset($filename) && is_array($filename) && !empty($filename)) {
					foreach( $filename as $key => $value ){
						$upload_file = wp_upload_bits(basename($value), null, file_get_contents($value));
						if (!$upload_file['error']) {
							$filename = $upload_file['file'];
						// The ID of the post this attachment is for.

							$parent_post_id = 0;

						// Check the type of file. We'll use this as the 'post_mime_type'.
							$filetype = wp_check_filetype( basename( $filename ), null );
						// Get the path to the upload directory.
							$wp_upload_dir = wp_upload_dir();
						// Prepare an array of post data for the attachment.
							$attachment = array(
								'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
								'post_mime_type' => $filetype['type'],
								'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),

								'post_status'    => 'inherit'
							);
						// Insert the attachment.

							$attach_id = wp_insert_attachment( $attachment, $filename, 0);
						// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
							require_once( ABSPATH . 'wp-admin/includes/image.php' );

						// Generate the metadata for the attachment, and update the database record.
							$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

							wp_update_attachment_metadata( $attach_id, $attach_data );
							$arr[] = $attach_id;
						}
					}
				}
				
				$mwb_wgm_happy_birthday_template = '<center style="width: 100%;"> <div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;"> (Optional) This text will appear in the inbox preview, but not the email body. </div><table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin: auto" class="email-container"> <tr> <td style="text-align: center; background:#E25A9D "> <p style="color:#fff; font-size: 25px; font-family: sans-serif; padding: 15px 0 0; margin: 0px;"><strong>[LOGO]</strong></p></td></tr></table> <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="600" style="margin: auto;" class="email-container"> <tr> <td bgcolor="#E25A9D" style="padding-bottom: 15px;"> <span class="feature_img">[FEATUREDIMAGE]</span> </td></tr><tr> <td bgcolor="#FFE0EF" style="padding:18px; text-align: center; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; color: #555555; "></td><tr style="background-color: #E25A9D"><td style="color: #fff; font-size:20px; letter-spacing: 0px; margin:0; text-transform: uppercase; background-color: #E25A9D; padding:20px 10px; line-height: 0;"> <p style="border: 2px dashed #ffffff; color: #fff; font-size:20px; padding: 15px 10px; margin:0; text-transform: uppercase; background-color: #E25A9D; text-align: center; line-height: 30px;">Coupon Code<span style="display:block; font-size: 25px;">[COUPON]</span><span style="display:block;">Ed:[EXPIRYDATE]</span></p><br><br></td></tr><td bgcolor="#FFE0EF" style="padding-top: 25px; text-align: center; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; color: #555555; "> </td></tr><tr> <td bgcolor="#ffe0ef" dir="ltr" align="center" valign="top" width="100%" style="padding-bottom: 15px;"> <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%"> <tr> <td width="50%" class="stack-column-center" style="vertical-align:top;"> <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%"> <tr> <td dir="ltr" valign="top" style="padding: 0px 25px;"> [DEFAULTEVENT] </td></tr></table> </td><td width="50%" class="stack-column-center" style="vertical-align: top;"> <table role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" width="100%"> <tr> <td dir="ltr" valign="top" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff; padding: 0px 15px; text-align: left;" class="center-on-narrow"> <p style="font-size: 15px; line-height: 24px; text-align: justify; color: #535151; min-height: 200px;white-space: pre-line;">[MESSAGE] </p></td></tr><tr><td class="mail-content" style="word-wrap: break-word;font-family: sans-serif; padding: 0px 15px;"><span style="color: #535151; font-size: 15px; float: left; vertical-align: top; text-align: right; display-inline: block; ">From- </span> <span style="color: #535151; font-size: 14px; vertical-align: top; display: inline-block; float: left;">[FROM]</span></td></tr><tr><td style="word-wrap: break-word; font-family: sans-serif; padding: 0px 15px;"><span style="color:#535151; font-size: 15px; max-width: 15%; float: left; margin-right: 2%; text-align: right; width: 100%; display: inline-block; vertical-align: top;">To- </span> <span style="color: #535151; font-size: 14px; width: 180px; float: left; vertical-align: top;">[TO]</span></td></tr><tr><td style="padding: 5px 10px; word-wrap: break-word;"><span style="color: #8a2814; font-size: 23.96px; vertical-align: top;"><strong>[AMOUNT]/-</strong> </span></td></tr></table> </td></tr></table> </td></tr><tr> <td bgcolor="#e5609f"> <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"> <tr> <td style="padding: 10px; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff;"> <p style="font-weight: bold; text-align:center;"> [DISCLAIMER] </p></td></tr></table> </td></tr></table></center>';

				$gifttemplate_new = array(
					'post_title' =>  __('New Birthday Template',MWB_WGM_DOMAIN ),
					'post_content' => $mwb_wgm_happy_birthday_template,
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_type'		=> 'giftcard'
				);
				$parent_post_id = wp_insert_post( $gifttemplate_new );
				//update_post_meta($parent_post_id,'mwb_css_field',trim($new_mom_css));
				set_post_thumbnail( $parent_post_id, $arr[0] );
			}

		}
		public function mwb_wgm_insert_custom_template(){

			$mwb_wgm_template = get_option('mwb_wgm_insert_custom_template', '');
			if ( empty($mwb_wgm_template)) {
				update_option("mwb_wgm_insert_custom_template", true);
				$filename = array( MWB_WGC_DIRPATH."assets/images/giftcard.jpg");
				if (isset($filename) && is_array($filename) && !empty($filename)) {
					foreach( $filename as $key => $value ){
						$upload_file = wp_upload_bits(basename($value), null, file_get_contents($value));
						if (!$upload_file['error']) {
							$filename = $upload_file['file'];
						// The ID of the post this attachment is for.

							$parent_post_id = 0;

						// Check the type of file. We'll use this as the 'post_mime_type'.
							$filetype = wp_check_filetype( basename( $filename ), null );
						// Get the path to the upload directory.
							$wp_upload_dir = wp_upload_dir();
						// Prepare an array of post data for the attachment.
							$attachment = array(
								'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
								'post_mime_type' => $filetype['type'],
								'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),

								'post_status'    => 'inherit'
							);
						// Insert the attachment.

							$attach_id = wp_insert_attachment( $attachment, $filename, 0);
						// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
							require_once( ABSPATH . 'wp-admin/includes/image.php' );

						// Generate the metadata for the attachment, and update the database record.
							$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

							wp_update_attachment_metadata( $attach_id, $attach_data );
							$arr[] = $attach_id;
						}
					}
				}

				$mwb_wgm_custom_template_html = '<table class="email-container" style="margin: auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td style="padding-top: 20px; text-align: center; color: #f48643; font-weight: bold; padding-left: 20px; font-size: 20px; font-family: sans-serif; position: absolute;">[LOGO]</td></tr></tbody></table><table class="email-container" style="margin: auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td bgcolor="#ffffff"><span class="feature_image" style="display: block; margin: 0px auto; width: 100%;"> [FEATUREDIMAGE] </span></td></tr><tr><td style="text-align: center; font-family: sans-serif; font-size: 15px; color: #1976e7; vertical-align: middle; display: table-cell; background: #7D0404;"><h2 style="font-size: 16px; display: block; text-align: center!important; border: 5px dashed #ffffff; padding: 15px 0px; margin: 0px; color: #fff;">COUPON CODE <span style="display: block; font-size: 24px; padding: 8px 0 0 0; color: #fff;">[COUPON]</span> <span style="display: block; font-size: 16px; padding: 8px 0 0 0;">(Ed:[EXPIRYDATE])</span></h2></td></tr><tr><td dir="ltr" style="padding: 22px 10px; background: #fff;" align="center" valign="top" bgcolor="#ffb001" width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="stack-column-center" valign="top" width="50%"><table border="0" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="img_width_left_table" dir="ltr" style="padding: 0 10px 0 10px; width: 50%;" valign="top">[DEFAULTEVENT]</td></tr></tbody></table></td><td class="stack-column-center" valign="top" width="50%"><table border="0" width="100%" cellspacing="0" cellpadding="0" align="center"><tbody><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; line-height: 20px; color: #ffffff; padding: 0px 30px 0px 0px; word-wrap: break-word; text-align: left;" valign="top"><p style="color: #000; font-size: 15px; height: auto; min-height: 180px; padding: 0px 0px 20px; text-align: left; word-break: break-word; white-space: pre-line;">[MESSAGE]</p></td></tr><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #000; word-wrap: break-word;" valign="top"><p style="margin-bottom: 0px; font-size: 16px; text-align: left; color: #000;"><span style="display: inline-block; text-align: right; font-size: 15px; vertical-align: top; color: #000;">From-</span><span style="display: inline-block; text-align: left; font-size: 14px; vertical-align: top; word-break: break-all; color: #000;">[FROM]</span></p></td></tr><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; word-wrap: break-word; color: #fff;" valign="top"><p style="margin-top: 0px; font-size: 16px; line-height: 25px; text-align: left;"><span style="display: inline-block; text-align: right; font-size: 15px; vertical-align: top; color: #000;">To-</span><span style="display: inline-block; text-align: left; font-size: 14px; vertical-align: top; word-break: break-all; color: #000;">[TO]</span></p></td></tr><tr><td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #fff; word-wrap: break-word;" valign="top"><p style="text-align: left; font-weight: bold; font-size: 28px;"><span style="color: #800505; margin: 20px 0; vertical-align: top;">[AMOUNT]/- </span></p></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style="background: #7D0404;"><table border="0" width="100%" cellspacing="0" cellpadding="0"><tbody><tr><td style="padding: 40px; font-family: sans-serif; font-size: 16px; mso-height-rule: exactly; line-height: 20px; color: #fff; text-align: center;">[DISCLAIMER]</td></tr></tbody></table></td></tr></tbody></table>';

				$mwb_wgm_template = array(
					'post_title' => __('Custom Template',MWB_WGM_DOMAIN),
					'post_content' => $mwb_wgm_custom_template_html,
					'post_status' => 'publish',
					'post_author' => get_current_user_id(),
					'post_type'		=> 'giftcard'
				);
				$parent_post_id = wp_insert_post( $mwb_wgm_template );
				
				set_post_thumbnail( $parent_post_id, $arr[0] );
			}
		}
		/*Add Preview button link in giftcard post */
		public function mwb_wgm_preview_gift_template($actions, $post){
			if ( $post->post_type == 'giftcard') {
				$actions['mwb_wgm_quick_view'] = '<a href="' .admin_url( 'edit.php?post_type=giftcardpost&post_id=' . $post->ID.'&mwb_wgm_template=giftcard&TB_iframe=true&width=600&height=500' ). '" rel="permalink" class="thickbox">' .  __( 'Preview', MWB_WGM_DOMAIN ) . '</a>';
			}
			return $actions;
		}
		/*Preview of email template*/
		public function mwb_wgm_preview_email_template(){
			if (isset($_GET['mwb_wgm_template'])) {
				if (isset($_GET['mwb_wgm_template']) == 'giftcard') {
					$post_id = $_GET['post_id'];
					$todaydate = date_i18n("Y-m-d");
					$mwb_wgm_general_settings = get_option('mwb_wgm_general_settings', false);

					$expiry_date = $this->mwb_common_fun->mwb_wgm_get_template_data($mwb_wgm_general_settings,'mwb_wgm_general_setting_giftcard_expiry');

					if($expiry_date > 0 || $expiry_date === 0)
					{
						$expirydate = date_i18n( "Y-m-d", strtotime( "$todaydate +$expiry_date day" ) );
						$expirydate_format = date_create($expirydate);
						
						$selected_date = $this->mwb_common_fun->mwb_wgm_get_template_data($mwb_wgm_general_settings,'mwb_uwgc_general_setting_enable_selected_format');

						if( isset($selected_date) && $selected_date !=null && $selected_date != "")
						{	
							$selected_date = apply_filters('mwb_wgm_selected_date_format',$selected_date);

							$expirydate_format = date_i18n($selected_date,strtotime( "$todaydate +$expiry_date day" ));
						}
						else
						{
							$expirydate_format = date_format($expirydate_format,"jS M Y");
						}
					}
					else
					{
						$expirydate_format = __("No Expiration", "woocommerce_gift_cards_lite");
					}
					$mwb_wgm_coupon_length_display = $this->mwb_common_fun->mwb_wgm_get_template_data($mwb_wgm_general_settings,'mwb_wgm_general_setting_giftcard_coupon_length');

					if ($mwb_wgm_coupon_length_display == '') {
						$mwb_wgm_coupon_length_display = 5;
					}
					$password = "";
					for($i=0;$i<$mwb_wgm_coupon_length_display;$i++){
						$password.="x";
					}
					$giftcard_prefix =  $this->mwb_common_fun->mwb_wgm_get_template_data($mwb_wgm_general_settings,'mwb_wgm_general_setting_giftcard_prefix');
					$coupon = $giftcard_prefix.$password;
					$templateid = $post_id;
					
					$args['from'] = __("from@example.com","woocommerce_gift_cards_lite");
					$args['to'] = __("to@example.com","woocommerce_gift_cards_lite");
					$args['message'] = __("Your gift message will appear here which you send to your receiver. ","woocommerce_gift_cards_lite");
					$args['coupon'] = apply_filters('mwb_wgm_static_coupon_img',$coupon);
					$args['expirydate'] = $expirydate_format;
					$args['amount'] =  wc_price(100);
					$args['templateid'] = $templateid;
					$style = '<style>
					table, th, tr, td {
						border: medium none;
					}
					table, th, tr, td {
						border: 0px !important;
					}
						#mwb_gw_email {
					width: 630px !important;
				}
				</style>';
				$message = $this->mwb_common_fun->mwb_wgm_create_gift_template($args);
				echo $finalhtml = $style.$message;
				die();
			}
		}
	}

	public function mwb_wgm_append_default_template()
	{	
		check_ajax_referer( 'mwb-wgm-verify-nonce', 'mwb_nonce' );
		$response['result'] = __( 'Fail due to an error', MWB_WGM_DOMAIN);
		$template_ids = $_POST['template_ids'];

		if(isset($template_ids) && !empty($template_ids))
		{
			$args = array( 'post_type' => 'giftcard', 'posts_per_page' => -1,'post__in' => $template_ids);
			$loop = new WP_Query( $args );
			$template = array();
			if( $loop->have_posts() ):
				while ( $loop->have_posts() ) : $loop->the_post(); global $product;
					$template_id = $loop->post->ID;
					$template_title = $loop->post->post_title;
					$template[$template_id] = $template_title;
				endwhile;
			endif;
			$response['templateid'] = $template;
			$response['result'] = 'success';
		}
		else if(empty($template_ids))
		{
			$response['result'] = 'no_ids';
		}
		echo json_encode($response);
		wp_die();
	}
}
?>
