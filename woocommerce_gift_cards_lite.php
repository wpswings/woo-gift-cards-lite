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
 * Description:       <code><strong>Ultimate Gift Cards For WooCommerce</strong></code> allows merchants to create and sell fascinating Gift Card Product with multiple price variation. <a href="https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-gift-cards&utm_medium=giftcards-org-backend&utm_campaign=official" target="_blank"> Elevate your e-commerce store by exploring more on <strong> WP Swings </strong></a>.
 * Version:           2.3.1
 * Author:            WP Swings
 * Author URI:        https://wpswings.com/?utm_source=wpswings-giftcards-official&utm_medium=giftcards-org-backend&utm_campaign=official
 * License:           GPL-3.0+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       woo-gift-cards-lite
 * Tested up to:      5.8.3
 * WC tested up to:   6.1.0
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
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
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		$activated = true;
	}
}

if ( $activated ) {
	define( 'MWB_WGC_DIRPATH', plugin_dir_path( __FILE__ ) );
	define( 'MWB_WGC_URL', plugin_dir_url( __FILE__ ) );
	define( 'MWB_WGC_ADMIN_URL', admin_url() );
	define( 'MWB_WGC_VERSION', '2.3.1' );
	/**
	* Check whether the WordPress version is greater than 4.9.6
	*/
	global $wp_version;
	if ( $wp_version >= '4.9.6' ) {
		include_once MWB_WGC_DIRPATH . 'mwb-wgc-lite-gdpr.php';
	}
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-gift-cards-lite.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-gift-cards-activation.php';


	/**
	Add link for settings
	*/
	add_filter( 'plugin_action_links', 'mwb_wgm_admin_settings', 10, 4 );

	/**
	 * Add the Setting Links
	 *
	 * @since 1.0.0
	 * @name mwb_wgm_admin_settings
	 * @param array  $actions actions.
	 * @param string $plugin_file plugin file name.
	 * @param array  $plugin_data plugin_data.
	 * @param string $context context.
	 * @return $actions
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wgm_admin_settings( $actions, $plugin_file, $plugin_data, $context ) {
		static $plugin;
		if ( ! isset( $plugin ) ) {
			$plugin = plugin_basename( __FILE__ );
		}
		if ( $plugin == $plugin_file ) {
			$settings = array();
			if ( ! mwb_uwgc_pro_active() ) {
				$settings['settings']         = '<a href="' . esc_url( admin_url( 'edit.php?post_type=giftcard&page=mwb-wgc-setting-lite' ) ) . '">' . esc_html__( 'Settings', 'woo-gift-cards-lite' ) . '</a>';
				$settings['get_paid_version'] = '<a class="mwb-wgm-go-pro" href="https://wpswings.com/product/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-pro&utm_medium=giftcards-org-backend&utm_campaign=go-pro" target="_blank">' . esc_html__( 'GO PRO', 'woo-gift-cards-lite' ) . '</a>';
				$actions                      = array_merge( $settings, $actions );
			}
		}
		return $actions;
	}

	/**
	 * This function is used to check if premium plugin is activated.
	 *
	 * @since 1.0.0
	 * @name mwb_uwgc_pro_active
	 * @return boolean
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_uwgc_pro_active() {
		return apply_filters( 'mwb_uwgc_pro_active', false );
	}
	if ( ! function_exists( 'mwb_wgm_giftcard_enable' ) ) {
		/**
		 * This function is used to check if the giftcard plugin is activated.
		 *
		 * @since 1.0.0
		 * @name mwb_wgm_giftcard_enable
		 * @return boolean
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
		 */
		function mwb_wgm_giftcard_enable() {
			$giftcard_enable = get_option( 'mwb_wgm_general_settings', array() );
			if ( ! empty( $giftcard_enable ) && array_key_exists( 'mwb_wgm_general_setting_enable', $giftcard_enable ) ) {
				$check_enable = $giftcard_enable['mwb_wgm_general_setting_enable'];
				if ( isset( $check_enable ) && ! empty( $check_enable ) ) {
					if ( 'on' == $check_enable ) {
						return true;
					} else {
						return false;
					}
				}
			}
		}
	}
	register_activation_hook( __FILE__, 'mwb_wgm_create_gift_card_taxonomy' );

	/**
	 * Create the Taxonomy for Gift Card Product at activation.
	 *
	 * @since 1.0.0
	 * @name mwb_wgm_create_gift_card_taxonomy
	 * @param boolean $network_wide for multisite.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wgm_create_gift_card_taxonomy( $network_wide ) {
		global $wpdb;
		// check if the plugin has been activated on the network.
		if ( is_multisite() && $network_wide ) {
			// Get all blogs in the network and activate plugins on each one.
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				mwb_create_giftcard_page();
				restore_current_blog();
			}
		} else {
			// activated on a single site, in a multi-site or on a single site.
			mwb_create_giftcard_page();
		}
		$restore_data = new Woocommerce_Gift_Cards_Activation();
		$restore_data->mwb_wgm_restore_data( $network_wide );
		set_transient( 'mwb-wgm-giftcard-setting-notice', true, 5 );

	}

	// on plugin load.
	add_action( 'plugins_loaded', 'mwb_wgc_register_gift_card_product_type' );

	/**
	 * Saving the Product Type by creating the Instance of this.
	 *
	 * @since 1.0.0
	 * @name mwb_wgc_register_gift_card_product_type
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wgc_register_gift_card_product_type() {
		/**
		 * Set the giftcard product type.
		 *
		 * @since 1.0.0
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
		 */
		class WC_Product_Wgm_Gift_Card extends WC_Product {
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

	if ( ! function_exists( 'mwb_wgm_coupon_generator' ) ) {
		/**
		 * Generate the Dynamic code for Gift Cards.
		 *
		 * @since 1.0.0
		 * @name mwb_wgm_coupon_generator
		 * @param int $length length of coupon code.
		 * @return string $password.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
		 */
		function mwb_wgm_coupon_generator( $length = 5 ) {
			$password    = '';
			$alphabets   = range( 'A', 'Z' );
			$numbers     = range( '0', '9' );
			$final_array = array_merge( $alphabets, $numbers );
			while ( $length-- ) {
				$key       = array_rand( $final_array );
				$password .= $final_array[ $key ];
			}

			$general_settings = get_option( 'mwb_wgm_general_settings', array() );
			if ( ! empty( $general_settings ) && array_key_exists( 'mwb_wgm_general_setting_giftcard_prefix', $general_settings ) ) {
				$giftcard_prefix = $general_settings['mwb_wgm_general_setting_giftcard_prefix'];
			} else {
				$giftcard_prefix = '';
			}
			$password = $giftcard_prefix . $password;
			$password = apply_filters( 'mwb_wgm_custom_coupon', $password );
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

	register_deactivation_hook( __FILE__, 'mwb_uwgc_remove_cron_for_notification_update' );


	/**
	 * Clear the cron set for giftcard notification updates.
	 *
	 * @since    2.0.0
	 */
	function mwb_uwgc_remove_cron_for_notification_update() {
		wp_clear_scheduled_hook( 'mwb_wgm_check_for_notification_update' );
	}

	include_once MWB_WGC_DIRPATH . 'includes/giftcard-redeem-api-addon.php';

	// Multisite Compatibilty for new site creation.
	add_action( 'wp_initialize_site', 'mwb_wgc_standard_plugin_on_create_blog', 900 );

	/**
	 * Compatibilty with multisite.
	 *
	 * @param object $new_site subsite.
	 * @return void
	 */
	function mwb_wgc_standard_plugin_on_create_blog( $new_site ) {
		if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}
		// check if the plugin has been activated on the network.
		if ( is_plugin_active_for_network( 'woo-gift-cards-lite/woocommerce_gift_cards_lite.php' ) ) {
			$mwb_lcns_status = get_option( 'mwb_gw_lcns_status' );
			$mwb_license_key = get_option( 'mwb_gw_lcns_key' );
			$timestamp       = get_option( 'mwb_gw_lcns_thirty_days' );
			$blog_id         = $new_site->blog_id;
			// switch to newly created site.
			switch_to_blog( $blog_id );
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-gift-cards-activation.php';
			// code to be executed when site is created, call any function from activation file.
			mwb_create_giftcard_page();
			$restore_data = new Woocommerce_Gift_Cards_Activation();
			$restore_data->on_activation();
			do_action( 'mwb_wgm_standard_plugin_on_create_blog', $mwb_lcns_status, $mwb_license_key, $timestamp );
			restore_current_blog();
		}
	}
	/**
	 * Migration to new domain notice.
	 *
	 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data.
	 * @param string $status Status filter currently applied to the plugin list.
	 */
	function mwb_wgm_upgrade_notice( $plugin_file, $plugin_data, $status ) {

		?>
			<tr class="plugin-update-tr active notice-warning notice-alt">
			<td colspan="4" class="plugin-update colspanchange">
				<div class="notice notice-success inline update-message notice-alt">
					<div class='wps-notice-title wps-notice-section'>
						<p><strong>IMPORTANT NOTICE:</strong></p>
					</div>
					<div class='wps-notice-content wps-notice-section'>
						<p>From this update <strong>Version 2.3.1</strong> onwards, the plugin and its support will be handled by <strong>WP Swings</strong>.</p><p><strong>WP Swings</strong> is just our improvised and rebranded version with all quality solutions and help being the same, so no worries at your end.
						Please connect with us for all setup, support, and update related queries without hesitation.</p>
					</div>
				</div>
			</td>
		</tr>
		<style>
			.wps-notice-section > p:before {
				content: none;
			}
		</style>
		<?php

	}
	add_action( 'after_plugin_row_' . plugin_basename( __FILE__ ), 'mwb_wgm_upgrade_notice', 0, 3 );

	add_action( 'admin_notices', 'mwb_wgm_updgrade_notice' );

	/**
	 * Migration to new domain notice.
	 */
	function mwb_wgm_updgrade_notice() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$tab = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

		if ( 'mwb-wgc-setting-lite' === $tab ) {
			?>

		<tr class="plugin-update-tr active notice-warning notice-alt">
			<td colspan="4" class="plugin-update colspanchange">
				<div class="notice notice-success inline update-message notice-alt">
					<div class='wps-notice-title wps-notice-section'>
						<p><strong>IMPORTANT NOTICE:</strong></p>
					</div>
					<div class='wps-notice-content wps-notice-section'>
						<p>From this update <strong>Version 2.3.1</strong> onwards, the plugin and its support will be handled by <strong>WP Swings</strong>.</p><p><strong>WP Swings</strong> is just our improvised and rebranded version with all quality solutions and help being the same, so no worries at your end.
						Please connect with us for all setup, support, and update related queries without hesitation.</p>
					</div>
				</div>
			</td>
		</tr>
		<style>
			.wps-notice-section > p:before {
				content: none;
			}
		</style>
			<?php
		}
	}
} else {
	add_action( 'admin_init', 'mwb_wgm_plugin_deactivate' );

	/**
	 * Deactivate plugin.
	 *
	 * @since 1.0.0
	 * @name mwb_wgm_plugin_deactivate()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wgm_plugin_deactivate() {
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

/**
 * Create the Taxonomy for Gift Card Product at activation.
 *
 * @return void
 */
function mwb_create_giftcard_page() {
	$page_taxonomy_created = get_option( 'mwb_wgc_create_gift_card_taxonomy', false );
	if ( false == $page_taxonomy_created ) {
		update_option( 'mwb_wgc_create_gift_card_taxonomy', true );
		$term       = esc_html__( 'Gift Card', 'woo-gift-cards-lite' );
		$taxonomy   = 'product_cat';
		$term_exist = term_exists( $term, $taxonomy );
		if ( 0 == $term_exist || null == $term_exist ) {
			$args['slug'] = 'mwb_wgm_giftcard';
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
