<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpswings.com/
 * @since             1.0.0
 * @package           woo-gift-cards-lite
 *
 * @wordpress-plugin
 * Plugin Name:       Ultimate Gift Cards For WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/woo-gift-cards-lite/?utm_source=wpswings-giftcards-org&utm_medium=giftcards-org-backend&utm_campaign=org
 * Description:       <code><strong>Ultimate Gift Cards For WooCommerce</strong></code> allows merchants to create and sell fascinating Gift Card Product with multiple price variation. <a href="https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-giftcards-shop&utm_medium=giftcards-org-backend&utm_campaign=shop-page" target="_blank"> Elevate your e-commerce store by exploring more on <strong> WP Swings </strong></a>.
 * Version:           3.0.1
 * Author:            WP Swings
 * Author URI:        https://wpswings.com/?utm_source=wpswings-giftcards-official&utm_medium=giftcards-org-backend&utm_campaign=official
 * License:           GPL-3.0+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       woo-gift-cards-lite
 * Requires Plugins:  woocommerce
 * WP Tested up to:   6.5.5
 * WP requires at least: 5.5.0
 * WC tested up to:   9.0.2
 * WC requires at least: 5.5.0
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Automattic\WooCommerce\Utilities\OrderUtil;

$activated = false;
/**
 * Checking if WooCommerce is active.
 */
if ( function_exists( 'is_multisite' ) && is_multisite() ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
		$activated = true;
	}
} else {
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
		$activated = true;
	}
}

add_action( 'before_woocommerce_init', 'wps_wgm_declare_hpos_compatibility' );
/**
 * Hpos and cart/checkout block compatibility.
 */
function wps_wgm_declare_hpos_compatibility() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
}

if ( $activated ) {

	define( 'WPS_WGC_DIRPATH', plugin_dir_path( __FILE__ ) );
	define( 'WPS_WGC_URL', plugin_dir_url( __FILE__ ) );
	define( 'WPS_WGC_ADMIN_URL', admin_url() );
	define( 'WPS_WGC_VERSION', '3.0.1' );
	define( 'WPS_WGC_ONBOARD_PLUGIN_NAME', 'Ultimate Gift Cards For WooCommerce' );
	define( 'WPS_GIFT_TEMPLATE_URL', 'https://demo.wpswings.com/client-notification/' );
	/**
	* Check whether the WordPress version is greater than 4.9.6
	*/
	global $wp_version;
	if ( $wp_version >= '4.9.6' ) {
		include_once WPS_WGC_DIRPATH . 'wps-wgc-lite-gdpr.php';
	}
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-gift-cards-lite.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-gift-cards-activation.php';

	/**
	 *Add link for settings
	*/
	add_filter( 'plugin_action_links', 'wps_wgm_admin_settings', 10, 4 );

	/**
	 * Add the Setting Links
	 *
	 * @since 1.0.0
	 * @name wps_wgm_admin_settings
	 * @param array  $actions actions.
	 * @param string $plugin_file plugin file name.
	 * @param array  $plugin_data plugin_data.
	 * @param string $context context.
	 * @return $actions
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	function wps_wgm_admin_settings( $actions, $plugin_file, $plugin_data, $context ) {
		static $plugin;
		if ( ! isset( $plugin ) ) {
			$plugin = plugin_basename( __FILE__ );
		}
		if ( $plugin === $plugin_file ) {
			$settings = array();
			if ( ! wps_uwgc_pro_active() ) {
				$settings['settings']         = '<a href="' . esc_url( admin_url( 'edit.php?post_type=giftcard&page=wps-wgc-setting-lite' ) ) . '">' . esc_html__( 'Settings', 'woo-gift-cards-lite' ) . '</a>';
				$settings['get_paid_version'] = '<a class="wps-wgm-go-pro" href="https://wpswings.com/product/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-pro&utm_medium=giftcards-org-backend&utm_campaign=go-pro" target="_blank">' . esc_html__( 'GO PRO', 'woo-gift-cards-lite' ) . '</a>';
				$actions                      = array_merge( $settings, $actions );
			}
		}
		return $actions;
	}

	/**
	 * This function is used to check if premium plugin is activated.
	 *
	 * @since 1.0.0
	 * @name wps_uwgc_pro_active
	 * @return boolean
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	function wps_uwgc_pro_active() {
		return apply_filters( 'wps_uwgc_pro_active', false );
	}
	if ( ! function_exists( 'wps_wgm_giftcard_enable' ) ) {
		/**
		 * This function is used to check if the giftcard plugin is activated.
		 *
		 * @since 1.0.0
		 * @name wps_wgm_giftcard_enable
		 * @return boolean
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		function wps_wgm_giftcard_enable() {
			$giftcard_enable = get_option( 'wps_wgm_general_settings', array() );
			if ( ! empty( $giftcard_enable ) && array_key_exists( 'wps_wgm_general_setting_enable', $giftcard_enable ) ) {
				$check_enable = $giftcard_enable['wps_wgm_general_setting_enable'];
				if ( isset( $check_enable ) && ! empty( $check_enable ) ) {
					if ( 'on' === $check_enable ) {
						return true;
					} else {
						return false;
					}
				}
			}
		}
	}
	register_activation_hook( __FILE__, 'wps_wgm_create_gift_card_taxonomy' );


	/**
	 * Create the Taxonomy for Gift Card Product at activation.
	 *
	 * @return void
	 */
	function wps_create_giftcard_page() {
		$page_taxonomy_created = get_option( 'wps_wgc_create_gift_card_taxonomy', false );
		if ( false == $page_taxonomy_created ) {
			update_option( 'wps_wgc_create_gift_card_taxonomy', true );
			$term       = esc_html__( 'Gift Card', 'woo-gift-cards-lite' );
			$taxonomy   = 'product_cat';
			$term_exist = term_exists( $term, $taxonomy );
			if ( 0 == $term_exist || null == $term_exist ) {
				$args['slug'] = 'wps_wgm_giftcard';
				$term_exist   = wp_insert_term( $term, $taxonomy, $args );
			}
			$terms             = get_term( $term_exist['term_id'], $taxonomy, ARRAY_A );
			$giftcard_category = $terms['slug'];
			$giftcard_content  = "[product_category category='$giftcard_category']";
			$customer_reports  = array(
				'post_author'  => get_current_user_id(),
				'post_name'    => esc_html__( 'Gift Card', 'woo-gift-cards-lite' ),
				'post_title'   => esc_html__( 'Gift Card', 'woo-gift-cards-lite' ),
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_content' => $giftcard_content,
			);
			$page_id           = wp_insert_post( $customer_reports );
		}
	}


	/**
	 * Create the Taxonomy for Gift Card Product at activation.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_create_gift_card_taxonomy
	 * @param boolean $network_wide for multisite.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	function wps_wgm_create_gift_card_taxonomy( $network_wide ) {
		global $wpdb;
		// check if the plugin has been activated on the network.
		if ( is_multisite() && $network_wide ) {
			// Get all blogs in the network and activate plugins on each one.
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				wps_create_giftcard_page();
				restore_current_blog();
			}
		} else {
			// activated on a single site, in a multi-site or on a single site.
			wps_create_giftcard_page();
		}
		$restore_data = new Woocommerce_Gift_Cards_Activation();
		$restore_data->wps_wgm_restore_data( $network_wide );
		set_transient( 'wps-wgm-giftcard-setting-notice', true, 5 );

	}

	// on plugin load.
	add_action( 'plugins_loaded', 'wps_wgc_register_gift_card_product_type' );

	/**
	 * Saving the Product Type by creating the Instance of this.
	 *
	 * @since 1.0.0
	 * @name wps_wgc_register_gift_card_product_type.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	function wps_wgc_register_gift_card_product_type() {

		if ( ! class_exists( 'WC_Product_Wgm_Gift_Card' ) ) {
			/**
			 * Set the giftcard product type.
			 *
			 * @since 1.0.0
			 * @author WP Swings <webmaster@wpswings.com>
			 * @link https://www.wpswings.com/
			 */
			class WC_Product_Wgm_Gift_Card extends WC_Product {
				/**
				 * Simple product.
				 *
				 * @var product_type product_type.
				 */
				public $product_type;
				/**
				 * Initialize simple product.
				 *
				 * @param mixed $product product.
				 */
				public function __construct( $product ) {
					$this->product_type = 'wgm_gift_card';
					parent::__construct( $product );
				}

			}
		}
	}

	if ( ! function_exists( 'wps_wgm_coupon_generator' ) ) {
		/**
		 * Generate the Dynamic code for Gift Cards.
		 *
		 * @since 1.0.0
		 * @name wps_wgm_coupon_generator
		 * @param int $length length of coupon code.
		 * @return string $password.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.wpswings.com/
		 */
		function wps_wgm_coupon_generator( $length = 5 ) {
			$password    = '';
			$alphabets   = range( 'A', 'Z' );
			$numbers     = range( '0', '9' );
			$final_array = array_merge( $alphabets, $numbers );
			while ( $length-- ) {
				$key       = array_rand( $final_array );
				$password .= $final_array[ $key ];
			}

			$general_settings = get_option( 'wps_wgm_general_settings', array() );
			if ( ! empty( $general_settings ) && array_key_exists( 'wps_wgm_general_setting_giftcard_prefix', $general_settings ) ) {
				$giftcard_prefix = $general_settings['wps_wgm_general_setting_giftcard_prefix'];
			} else {
				$giftcard_prefix = '';
			}
			$password = $giftcard_prefix . $password;
			$password = apply_filters( 'wps_wgm_custom_coupon', $password );
			return $password;
		}
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
	function run_woocommerce_gift_cards_lite() {
		$plugin = new Woocommerce_Gift_Cards_Lite();
		$plugin->run();
	}
	run_woocommerce_gift_cards_lite();

	register_deactivation_hook( __FILE__, 'wps_uwgc_remove_cron_for_notification_update' );

	/**
	 * Clear the cron set for giftcard notification updates.
	 *
	 * @since    2.0.0
	 */
	function wps_uwgc_remove_cron_for_notification_update() {
		wp_clear_scheduled_hook( 'wps_wgm_check_for_notification_update' );
	}

	include_once WPS_WGC_DIRPATH . 'includes/giftcard-redeem-api-addon.php';

	// Multisite Compatibilty for new site creation.
	add_action( 'wp_initialize_site', 'wps_wgc_standard_plugin_on_create_blog', 900 );

	/**
	 * Compatibilty with multisite.
	 *
	 * @param object $new_site subsite.
	 * @return void
	 */
	function wps_wgc_standard_plugin_on_create_blog( $new_site ) {
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}
		// check if the plugin has been activated on the network.
		if ( is_plugin_active_for_network( 'woo-gift-cards-lite/woocommerce_gift_cards_lite.php' ) ) {
			$wps_lcns_status = get_option( 'wps_gw_lcns_status' );
			$wps_license_key = get_option( 'wps_gw_lcns_key' );
			$timestamp       = get_option( 'wps_gw_lcns_thirty_days' );
			$blog_id         = $new_site->blog_id;
			// switch to newly created site.
			switch_to_blog( $blog_id );
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-gift-cards-activation.php';
			// code to be executed when site is created, call any function from activation file.
			wps_create_giftcard_page();
			$restore_data = new Woocommerce_Gift_Cards_Activation();
			$restore_data->on_activation();
			do_action( 'wps_wgm_standard_plugin_on_create_blog', $wps_lcns_status, $wps_license_key, $timestamp );
			restore_current_blog();
		}
	}
	
	add_action( 'admin_notices', 'wps_wgm_new_layout_notice' );
	
	if ( ! function_exists( 'wps_wgm_new_layout_notice' ) ) {
		/**
		 * Layout setting .
		 * 
		 * @since    3.0.0
		 */
		function wps_wgm_new_layout_notice() {
			global $pagenow;
			$screen = get_current_screen();
			$other_settings = get_option( 'wps_wgm_other_settings', array() );
			$wps_obj = new Woocommerce_Gift_Cards_Common_Function();

			$wps_new_layout = $wps_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_new_gift_card_page_layout' );

			if ( 'plugins.php' == $pagenow || ( 'giftcard_page_wps-wgc-setting-lite' === $screen->id ) ) {

				if ( 'on' != $wps_new_layout ) {
					echo '<div  class="notice notice-success is-dismissible wps-gc-activate_notice wps-new-setting-notice update-nag">
					
					Check out our new gift card page layout setting in <strong> Gift Cards For WooCommerce Pro plugin</strong>. Enable it now and see the difference.
						<a href="' . esc_url( admin_url( 'edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=other_setting' ) ) . '">check </a>
						
					
				</div>';
				}
			}
		}
	}

	add_action( 'admin_init', 'wps_uwgc_create_giftcard_template_org' );

	/**
	 * Function to create giftcard template.
	 */
	function wps_uwgc_create_giftcard_template_org() {

		/* ===== ====== Create the Check Gift Card Page ====== ======*/
		if ( ! get_option( 'check_balance_page_created_org', false ) && ! get_option( 'check_balance_page_created', false ) ) {

			$balance_content = '[wps_check_your_gift_card_balance]';

			$check_balance = array(
				'post_author'  => get_current_user_id(),
				'post_name'    => __( 'Gift Card Balance', 'woo-gift-cards-lite' ),
				'post_title'   => __( 'Gift Card Balance', 'woo-gift-cards-lite' ),
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_content' => $balance_content,
			);
			$page_id       = wp_insert_post( $check_balance );
			update_option( 'check_balance_page_created_org', true );
			/* ===== ====== End of Create the Gift Card Page ====== ======*/
		}
		if ( ! get_option( 'giftcard_balance' ) ) {
			$mypost = get_page_by_path( 'gift-card-balance', '', 'page' );
			if ( isset( $mypost ) ) {
				update_option( 'giftcard_balance', $mypost->ID );
			}
		}
	}

	if ( ! function_exists( 'str_contains' ) ) {

		/**
		 * String contains.
		 *
		 * @param string $haystack haystack.
		 * @param string $needle needle.
		 * @return boolean
		 */
		function str_contains( $haystack, $needle ) {
			return '' !== $needle && false !== mb_strpos( $haystack, $needle );
		}
	}

	// HPOS - High Performance Order System.

	/**
	 * This function is used to check hpos enable.
	 *
	 * @return boolean
	 */
	function wps_wgm_is_hpos_enabled() {

		$is_hpos_enable = false;
		if ( class_exists( 'Automattic\WooCommerce\Utilities\OrderUtil' ) && OrderUtil::custom_orders_table_usage_is_enabled() ) {

			$is_hpos_enable = true;
		}
		return $is_hpos_enable;
	}

	/**
	 * This function is used to get post meta data.
	 *
	 * @param  string $id        id.
	 * @param  string $meta_key  meta key.
	 * @param  bool   $bool meta bool.
	 * @return string
	 */
	function wps_wgm_hpos_get_meta_data( $id, $meta_key, $bool ) {

		$meta_value = '';
		if ( 'shop_order' === OrderUtil::get_order_type( $id ) && wps_wgm_is_hpos_enabled() ) {

			$order      = wc_get_order( $id );
			$meta_value = $order->get_meta( $meta_key, $bool );
		} else {

			$meta_value = get_post_meta( $id, $meta_key, $bool );
		}
		return $meta_value;
	}

	/**
	 * This function is used to update meta data.
	 *
	 * @param string $id id.
	 * @param string $meta_key meta_key.
	 * @param string $meta_value meta_value.
	 * @return void
	 */
	function wps_wgm_hpos_update_meta_data( $id, $meta_key, $meta_value ) {

		if ( 'shop_order' === OrderUtil::get_order_type( $id ) && wps_wgm_is_hpos_enabled() ) {

			$order = wc_get_order( $id );
			$order->update_meta_data( $meta_key, $meta_value );
			$order->save();
		} else {

			update_post_meta( $id, $meta_key, $meta_value );
		}
	}

	/**
	 * This function is used delete meta data.
	 *
	 * @param string $id       id.
	 * @param string $meta_key meta_key.
	 * @return void
	 */
	function wps_wgm_hpos_delete_meta_data( $id, $meta_key ) {

		if ( 'shop_order' === OrderUtil::get_order_type( $id ) && wps_wgm_is_hpos_enabled() ) {

			$order = wc_get_order( $id );
			$order->delete_meta_data( $meta_key );
			$order->save();
		} else {

			delete_post_meta( $id, $meta_key );
		}
	}
} else {
	add_action( 'admin_init', 'wps_wgm_plugin_deactivate' );

	/**
	 * Deactivate plugin.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_plugin_deactivate()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	function wps_wgm_plugin_deactivate() {

		unset( $_GET['activate'] );
		deactivate_plugins( plugin_basename( __FILE__ ) );
		?>
		<!-- Show warning message if woocommerce is not install -->
		<div class="error notice is-dismissible">
			<p><?php esc_html_e( 'Woocommerce is not activated, Please activate Woocommerce first to install Ultimate Gift Cards For WooCommerce', 'woo-gift-cards-lite' ); ?></p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'wps_banner_notification_plugin_html' );

if ( ! function_exists( 'wps_banner_notification_plugin_html' ) ) {
	/**
	 * Notification.
	 */
	function wps_banner_notification_plugin_html() {
		$secure_nonce      = wp_create_nonce( 'wps-gc-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-auth-nonce' );
		if ( ! $id_nonce_verified ) {
				wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
		}
		$screen = get_current_screen();
		if ( isset( $screen->id ) ) {
			$pagescreen = $screen->id;
		}
		if ( ( isset( $pagescreen ) && 'plugins' === $pagescreen ) || ( 'wp-swings_page_home' == $pagescreen ) ) {
			$notification_id = get_option( 'wps_wgm_notify_new_msg_id', false );
			$banner_id = get_option( 'wps_wgm_notify_new_banner_id', false );
			if ( isset( $banner_id ) && '' !== $banner_id ) {
				$hidden_banner_id            = get_option( 'wps_wgm_notify_hide_baneer_notification', false );
				$banner_image = get_option( 'wps_wgm_notify_new_banner_image', '' );
				$banner_url = get_option( 'wps_wgm_notify_new_banner_url', '' );
				if ( isset( $hidden_banner_id ) && $hidden_banner_id < $banner_id ) {

					if ( '' !== $banner_image && '' !== $banner_url ) {

						?>
							<div class="wps-offer-notice notice notice-warning is-dismissible">
								<div class="notice-container">
									<a href="<?php echo esc_url( $banner_url ); ?>" target="_blank"><img src="<?php echo esc_url( $banner_image ); ?>" alt="Gift cards"/></a>
								</div>
								<button type="button" class="notice-dismiss dismiss_banner" id="dismiss-banner"><span class="screen-reader-text">Dismiss this notice.</span></button>
							</div>
							
						<?php
					}
				}
			}
		}
	}
}

add_action( 'admin_notices', 'wps_giftcard_notification_plugin_html' );


if ( ! function_exists( 'wps_giftcard_notification_plugin_html' ) ) {
	/**
	 * Notification html.
	 * 
	 * @since 3.0.0
	 */
	function wps_giftcard_notification_plugin_html() {

		$secure_nonce      = wp_create_nonce( 'wps-gc-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-auth-nonce' );
		if ( ! $id_nonce_verified ) {
				wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
		}

		$screen = get_current_screen();
		if ( isset( $screen->id ) ) {
			$pagescreen = $screen->id;
		}
		if ( ( isset( $_GET['page'] ) && 'wps-wgc-setting-lite' === $_GET['page'] ) || ( isset( $_GET['post_type'] ) && 'giftcard' === $_GET['post_type'] ) ) {
			$notification_id = get_option( 'wps_wgm_notify_new_msg_id', false );
			$banner_id = get_option( 'wps_wgm_notify_new_banner_id', false );
			if ( isset( $banner_id ) && '' !== $banner_id ) {
				$hidden_banner_id            = get_option( 'wps_wgm_notify_hide_baneer_notification', false );
				$banner_image = get_option( 'wps_wgm_notify_new_banner_image', '' );
				$banner_url = get_option( 'wps_wgm_notify_new_banner_url', '' );
				if ( isset( $hidden_banner_id ) && $hidden_banner_id < $banner_id ) {

					if ( '' !== $banner_image && '' !== $banner_url ) {

						?>
								<div class="wps-offer-notice notice notice-warning is-dismissible">
									<div class="notice-container">
										<a href="<?php echo esc_url( $banner_url ); ?>" target="_blank"><img src="<?php echo esc_url( $banner_image ); ?>" alt="Gift cards"/></a>
									</div>
									<button type="button" class="notice-dismiss dismiss_banner" id="dismiss-banner"><span class="screen-reader-text">Dismiss this notice.</span></button>
								</div>
								
							<?php
					}
				}
			}
		}

	}
}