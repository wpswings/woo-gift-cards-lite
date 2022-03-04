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
 * Version:           2.3.2
 * Author:            WP Swings
 * Author URI:        https://wpswings.com/?utm_source=wpswings-giftcards-official&utm_medium=giftcards-org-backend&utm_campaign=official
 * License:           GPL-3.0+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       woo-gift-cards-lite
 * Tested up to:      5.9.1
 * WC tested up to:   6.2.1
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
include_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( is_plugin_active( 'giftware/giftware.php' ) ) {

	$plug = get_plugins();
	if ( isset( $plug['giftware/giftware.php'] ) ) {
		if ( $plug['giftware/giftware.php']['Version'] < '3.4.2' ) {
			unset( $_GET['activate'] );
			deactivate_plugins( plugin_basename( 'giftware/giftware.php' ) );
		}
	}
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
	define( 'WPS_WGC_DIRPATH', plugin_dir_path( __FILE__ ) );
	define( 'WPS_WGC_URL', plugin_dir_url( __FILE__ ) );
	define( 'WPS_WGC_ADMIN_URL', admin_url() );
	define( 'WPS_WGC_VERSION', '2.3.1' );
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
	 * @link https://www.makewebbetter.com/
	 */
	function wps_wgm_admin_settings( $actions, $plugin_file, $plugin_data, $context ) {
		static $plugin;
		if ( ! isset( $plugin ) ) {
			$plugin = plugin_basename( __FILE__ );
		}
		if ( $plugin == $plugin_file ) {
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
	 * @link https://www.makewebbetter.com/
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
		 * @link https://www.makewebbetter.com/
		 */
		function wps_wgm_giftcard_enable() {
			$giftcard_enable = get_option( 'wps_wgm_general_settings', array() );
			if ( ! empty( $giftcard_enable ) && array_key_exists( 'wps_wgm_general_setting_enable', $giftcard_enable ) ) {
				$check_enable = $giftcard_enable['wps_wgm_general_setting_enable'];
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
	register_activation_hook( __FILE__, 'wps_wgm_create_gift_card_taxonomy' );

	/**
	 * Create the Taxonomy for Gift Card Product at activation.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_create_gift_card_taxonomy
	 * @param boolean $network_wide for multisite.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
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
	 * @name wps_wgc_register_gift_card_product_type
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	function wps_wgc_register_gift_card_product_type() {
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

	if ( ! function_exists( 'wps_wgm_coupon_generator' ) ) {
		/**
		 * Generate the Dynamic code for Gift Cards.
		 *
		 * @since 1.0.0
		 * @name wps_wgm_coupon_generator
		 * @param int $length length of coupon code.
		 * @return string $password.
		 * @author WP Swings <webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
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
	/**
	 * Migration to new domain notice.
	 *
	 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data.
	 * @param string $status Status filter currently applied to the plugin list.
	 */
	function wps_wgm_upgrade_notice( $plugin_file, $plugin_data, $status ) {

		?>
			<tr class="plugin-update-tr active notice-warning notice-alt">
			<td colspan="4" class="plugin-update colspanchange">
				<div class="notice notice-success inline update-message notice-alt">
					<div class='wps-notice-title wps-notice-section'>
						<p><strong><?php esc_html_e( 'IMPORTANT NOTICE:', 'woo-gift-cards-lite' ); ?></strong></p>
					</div>
					<div class='wps-notice-content wps-notice-section'>
						<p><?php esc_html_e( 'From update', 'woo-gift-cards-lite' ); ?><strong><?php esc_html_e( ' Version 2.3.1', 'woo-gift-cards-lite' ); ?></strong><?php esc_html_e( ' onwards, the plugin and its support will be handled by', 'woo-gift-cards-lite' ); ?><strong><?php esc_html_e( ' WP Swings', 'woo-gift-cards-lite' ); ?></strong>.</p><p><strong><?php esc_html_e( 'WP Swings', 'woo-gift-cards-lite' ); ?></strong><?php esc_html_e( ' is just our improvised and rebranded version with all quality solutions and help being the same, so no worries at your end.', 'woo-gift-cards-lite' ); ?>
						<?php esc_html_e( 'Please connect with us for all setup, support, and update related queries without hesitation.', 'woo-gift-cards-lite' ); ?>
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
	add_action( 'after_plugin_row_' . plugin_basename( __FILE__ ), 'wps_wgm_upgrade_notice', 0, 3 );

	add_action( 'admin_notices', 'wps_wgm_updgrade_notice' );

	/**
	 * Migration to new domain notice.
	 */
	function wps_wgm_updgrade_notice() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		$tab = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

		if ( 'wps-wgc-setting-lite' === $tab ) {
			?>

		<tr class="plugin-update-tr active notice-warning notice-alt">
			<td colspan="4" class="plugin-update colspanchange">
				<div class="notice notice-success inline update-message notice-alt">
					<div class='wps-notice-title wps-notice-section'>
						<p><strong><?php esc_html_e( 'IMPORTANT NOTICE:', 'woo-gift-cards-lite' ); ?></strong></p>
					</div>
					<div class='wps-notice-content wps-notice-section'>
						<p><?php esc_html_e( 'From this update', 'woo-gift-cards-lite' ); ?><strong><?php esc_html_e( ' Version 2.3.1', 'woo-gift-cards-lite' ); ?></strong><?php esc_html_e( ' onwards, the plugin and its support will be handled by', 'woo-gift-cards-lite' ); ?><strong><?php esc_html_e( ' WP Swings', 'woo-gift-cards-lite' ); ?></strong>.</p><p><strong><?php esc_html_e( 'WP Swings', 'woo-gift-cards-lite' ); ?></strong><?php esc_html_e( ' is just our improvised and rebranded version with all quality solutions and help being the same, so no worries at your end.', 'woo-gift-cards-lite' ); ?>
						<?php esc_html_e( 'Please connect with us for all setup, support, and update related queries without hesitation.', 'woo-gift-cards-lite' ); ?></p>
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
	add_action( 'admin_init', 'wps_wgm_plugin_deactivate' );

	/**
	 * Deactivate plugin.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_plugin_deactivate()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
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
add_action( 'admin_init', 'wps_migration_func' );
/**
 * Migration code function
 */
function wps_migration_func() {
	$migration_val_updated = get_option( 'wps_org_migration_value_updated', 'no' );

	if ( $migration_val_updated == 'no' ) {

		wps_org_upgrade_wp_postmeta();
		wps_org_upgrade_wp_options();
		wps_update_terms();
		wps_org_replace_mwb_to_wps_in_shortcodes();
		update_option( 'wps_org_migration_value_updated', 'yes' );
	}
}
/**
 * Upgrade_wp_postmeta. (use period)
 *
 * Upgrade_wp_postmeta.
 *
 * @since    1.0.0
 */
function wps_org_upgrade_wp_postmeta() {

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
					if ( is_array( $values ) ) {
						foreach ( $values  as $key => $value ) {
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
function wps_org_upgrade_wp_options() {

		$wp_options = array(
			'mwb_wgm_general_settings' => '',
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
			'mwb_wgm_other_settings'  => '',
			'mwb_wgm_product_settings'  => '',
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
			if ( is_array( $new_value ) ) {
				foreach ( $new_value as $key => $value ) {
					$new_key2 = str_replace( 'mwb_', 'wps_', $key );
					$new_key1 = str_replace( 'mwb-', 'wps-', $new_key2 );

					$value_1 = str_replace( 'mwb_', 'wps_', $value );
					$value_2 = str_replace( 'mwb-', 'wps-', $value_1 );

					$arr_val[ $new_key1 ] = $value_2;
				}
				update_option( $new_key, $arr_val );
			} else {
				update_option( $new_key, $new_value );
			}
		}
}
		/**
		 * Update terms data mwb keys
		 */
function wps_update_terms() {

	global $wpdb;
	$term_table = $wpdb->prefix . 'terms';
	if ( $wpdb->query( $wpdb->prepare( "SELECT * FROM %1s WHERE  `name` = 'Gift Card'", $term_table ) ) ) {
			$wpdb->query(
				$wpdb->prepare(
					"UPDATE %1s SET `slug`='wps_wgm_giftcard'
					WHERE  `name` = 'Gift Card'",
					$term_table
				)
			);
	}
}
		/**
		 * Update terms data mwb keys
		 */
function wps_org_replace_mwb_to_wps_in_shortcodes() {
		$all_product_ids = get_posts(
			array(
				'post_type' => 'product',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'fields' => 'ids',
			)
		);
		$all_post_ids = get_posts(
			array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'fields' => 'ids',
			)
		);
		$all_page_ids = get_posts(
			array(
				'post_type' => 'page',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'fields' => 'ids',
			)
		);
		$result = array_merge( $all_product_ids, $all_post_ids, $all_page_ids );
	foreach ( $result as $id ) {
				$post = get_post( $id );
				$content = $post->post_content;

				$array = explode( ' ', $content );

		foreach ( $array as $key => $val ) {
					$split_val = explode( '=', $val );
			if ( count( $split_val ) > 1 ) {
				if ( 'category' == $split_val[0] ) {

					update_option( 'existing_gift_card_page', $id );
					$html = str_replace( 'mwb_', 'wps_', $content );
					$my_post = array(
						'ID'           => $id,
						'post_content' => $html,
					);
						wp_update_post( $my_post );
				}
			} else {
						$html = str_replace( 'mwb_', 'wps_', $content );
						$my_post = array(
							'ID'           => $id,
							'post_content' => $html,
						);
						wp_update_post( $my_post );
			}
		}
	}
}
