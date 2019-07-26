<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://makewebbetter.com/
 * @since             1.0.0
 * @package           Woocommerce_gift_cards_lite
 *
 * @wordpress-plugin
 * Plugin Name:       Giftware - WooCommerce Gift Card Lite
 * Plugin URI:        https://makewebbetter.com/product/giftware-woocommerce-gift-cards/?utm_source=mwb-giftcard-org&utm_medium=mwb-org&utm_campaign=giftcard-org
 * Description:       Woocommerce Gift Cards Lite allows merchants to create and sell multiple Gift Card Product having multiple price variation 
 * Version:           1.2.0
 * Author:            makewebbetter
 * Author URI:        https://makewebbetter.com/
 * License:           GPL-3.0+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       woocommerce_gift_cards_lite
 * Tested up to: 	  5.2.2
 * WC tested up to:   3.6.5
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
$activated = true;
$mwb_wgc_pro_plugin_active = false;
$mwb_gw_pro_plugin_active = false;
if ( function_exists( 'is_multisite' ) && is_multisite() ){
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) ){
		$activated = false;
	}
	if ( !is_plugin_active( 'woocommerce-ultimate-gift-card/woocommerce-ultimate-gift-card.php' ) ){
		$activated = false;
		$mwb_wgc_pro_plugin_active = true;
	}
}
else{
	if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) )) ){
		$activated = false;
	}
	if ( in_array( 'woocommerce-ultimate-gift-card/woocommerce-ultimate-gift-card.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) )) ){
		$activated = false;
		$mwb_wgc_pro_plugin_active = true;
	}
	// if ( in_array( 'giftware/giftware.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) )) ){
	// 	$activated = false;
	// 	$mwb_gw_pro_plugin_active = true;
	// }
}

/**
 * Checking if WooCommerce is active
 **/
if ($activated && !$mwb_wgc_pro_plugin_active){
	define( 'MWB_WGC_DIRPATH', plugin_dir_path( __FILE__ ) );
	define( 'MWB_WGC_URL', plugin_dir_url( __FILE__ ) );
	define( 'MWB_WGC_ADMIN_URL', admin_url() );
	include_once MWB_WGC_DIRPATH.'/Shipping/giftcard-shipping-addon.php';

	/**
	* Check whether the wordpress version is greater than 4.9.6
	*/
	global $wp_version;
	if($wp_version >= '4.9.6'){
		include_once MWB_WGC_DIRPATH.'mwb_wgc_lite_gdpr.php';
	}
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce_gift_cards_lite.php';

	/**
	add link for settings
	*/
	add_filter ( 'plugin_action_links','mwb_wgc_admin_settings', 10, 5 );

	/**
	 * Adds the Setting Links
	 * 
	 * @name mwb_wgc_admin_settings
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wgc_admin_settings($actions, $plugin_file){
		static $plugin;

		if (! isset ( $plugin )) {
			$plugin = plugin_basename ( __FILE__ );
		}
		if ($plugin == $plugin_file) {
			$settings = array (
					'settings' => '<a href="' . admin_url ( 'admin.php?page=mwb-wgc-setting-lite' ) . '">' . __ ( 'Settings', 'woocommerce_gift_cards_lite' ) . '</a>',
					'get_paid_version' => '<a href="https://makewebbetter.com/product/giftware-woocommerce-gift-cards/?utm_source=mwb-giftcard-org&utm_medium=mwb-org&utm_campaign=giftcard-org" target="_blank">' . __ ( 'Get Premium Version', 'woocommerce_gift_cards_lite' ) . '</a>'
			);
			$actions = array_merge ( $settings, $actions );
		}
		return $actions;
	}

	/**
	 * Checks the Plugin is enable or not
	 * 
	 * @name mwb_wgc_giftcard_enable
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wgc_giftcard_enable()
	{
		$giftcard_enable = get_option("mwb_wgm_general_setting_enable", false);
		if($giftcard_enable == "on"){
			return true;
		}	
		else{
			return false;
		}	
	}

	register_activation_hook(__FILE__, 'mwb_wgc_create_gift_card_taxonomy');

	/**
	 * Create the Taxonomy for Gift Card Product at activation
	 * 
	 * @name mwb_wgc_create_gift_card_taxonomy
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wgc_create_gift_card_taxonomy(){
		$page_taxonomy_created = get_option("mwb_wgc_create_gift_card_taxonomy", false);
		if($page_taxonomy_created == false){
			update_option("mwb_wgc_create_gift_card_taxonomy", true);
			$term = __('Gift Card', 'woocommerce_gift_cards_lite' );
			$taxonomy = 'product_cat';
			$term_exist = term_exists( $term, $taxonomy);
			if ($term_exist == 0 || $term_exist == null){
				$args['slug'] = "mwb_wgm_giftcard";
				$term_exist = wp_insert_term( $term, $taxonomy, $args );
			}
			$terms = get_term( $term_exist['term_id'], $taxonomy, ARRAY_A);
			$giftcard_category = $terms['slug'];
			$giftcard_content = "[product_category category='$giftcard_category']";
			$customer_reports = array(
				'post_author'    => get_current_user_id(),
				'post_name'      => __('Gift Card', 'woocommerce_gift_cards_lite'),
				'post_title'     => __('Gift Card', 'woocommerce_gift_cards_lite'),
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'post_content'	 => $giftcard_content		
			);
			$page_id = wp_insert_post($customer_reports);
		}
	}

	//on plugin load
	add_action( 'plugins_loaded', 'mwb_wgc_register_gift_card_product_type' );
	/**
	 * Saving the Product Type by creating the Instance of this
	 * 
	 * @name mwb_wgc_register_gift_card_product_type
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wgc_register_gift_card_product_type()
	{
		class WC_Product_Wgm_gift_card extends WC_Product {
			/**
			 * Initialize simple product.
			 *
			 * @param mixed $product
			 */
			public function __construct( $product ) {
				$this->product_type = 'wgm_gift_card';
				parent::__construct( $product );
			}

		}
	}

	/**
	 * Generate the Dynamic code for Gift Cards
	 * 
	 * @name mwb_wgc_coupon_generator
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wgc_coupon_generator($length = 5){
		if( $length == "" ){
			$length = 5;
		}
		$password = '';
		$alphabets = range('A','Z');
		$numbers = range('0','9');
		$final_array = array_merge($alphabets,$numbers);
		while($length--)
		{
			$key = array_rand($final_array);
			$password .= $final_array[$key];
		}
		
		$giftcard_prefix = get_option('mwb_wgm_general_setting_giftcard_prefix', '');
		$password = $giftcard_prefix.$password;
		$password = apply_filters('mwb_wgm_custom_coupon', $password);
		return $password;
	}
	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_woocommerce_gift_cards_lite(){
		$plugin = new Woocommerce_gift_cards_lite();
		$plugin->run();
	}
	run_woocommerce_gift_cards_lite();

	function mwb_wgc_admin_update_notices() {
	    $user_id = get_current_user_id();
	    if ( !get_user_meta( $user_id, 'giftware_notice_dismissed' ) ) 
	        echo '<div class="update-nag mwb_gw_admin_notices"><p>Check New Giftcard Redeem feature <a href="'.admin_url('admin.php').'?page=mwb-gw-setting&tab=redeem_section"> check now </a> </p><a href="?gifware-dismissed">Dismiss</a></div>';
	}
	add_action( 'admin_notices', 'mwb_wgc_admin_update_notices' );

	function mwb_wgc_dismiss_admin_notices() {
	    $user_id = get_current_user_id();
	    if ( isset( $_GET['gifware-dismissed'] ) )
	        add_user_meta( $user_id, 'giftware_notice_dismissed', 'true', true );
	}

	add_action( 'admin_init', 'mwb_wgc_dismiss_admin_notices' );
	include(MWB_WGC_DIRPATH.'/includes/giftcard_redeem_api_addon.php');
}
else{
	if($mwb_wgc_pro_plugin_active){
		//deactivate if Woocommerce Ultimate Gift Card is already active
		add_action( 'admin_init', 'mwb_wgc_deactivate_pro_is_activated' ); 
	}else{
		//deactivate if Woocommerce is not activated
		add_action( 'admin_init', 'mwb_wgc_plugin_deactivate' ); 
	}

	/**
	 * Show warning message if woocommerce is not install
	 * @since 1.0.0
	 * @name mwb_wgc_plugin_error_notice()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wgc_plugin_error_notice(){ 
	?>
 		 <div class="error notice is-dismissible">
 			<p><?php _e( 'Woocommerce is not activated, Please activate Woocommerce first to install WooCommerce Gift Cards Lite.', 'woocommerce_gift_cards_lite' ); ?></p>
   		</div>
   		<style>
   			#message{display:none;}
   		</style>
   	<?php 
 	}

 	/**
	 * Show warning message if Woocommerce Ultimate Gift Card is already active
	 * @since 1.0.0
	 * @name mwb_wgc_plugin_error_notice_for_pro()
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
 	function mwb_wgc_plugin_error_notice_for_pro(){
 	?>
 		<div class="error notice is-dismissible">
 			<p><?php _e( 'Woocommerce Gift Cards Lite deactivated successfully, As you have activated its Pro Version(Woocommerce Ultimate Gift Card)', 'woocommerce_gift_cards_lite' ); ?></p>
   		</div>
   		<style>
   			#message{display:none;}
   		</style>
 	<?php
 	}

 	/**
 	 * Call Admin notices for Woocommerce
 	 * 
 	 * @name mwb_wgc_plugin_deactivate()
 	 * @author makewebbetter<webmaster@makewebbetter.com>
 	 * @link https://www.makewebbetter.com/
 	 */ 	
  	function mwb_wgc_plugin_deactivate(){
	   deactivate_plugins( plugin_basename( __FILE__ ) );
	   add_action( 'admin_notices', 'mwb_wgc_plugin_error_notice' );
	}

	/**
 	 * Call Admin notices for Pro version
 	 * 
 	 * @name mwb_wgc_deactivate_pro_is_activated()
 	 * @author makewebbetter<webmaster@makewebbetter.com>
 	 * @link https://www.makewebbetter.com/
 	 */ 
	function mwb_wgc_deactivate_pro_is_activated(){

		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'mwb_wgc_plugin_error_notice_for_pro' );
	}
}

