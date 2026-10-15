<?php
/**
 * "My Gift Cards" tab on the WooCommerce My Account page.
 *
 * Lists gift cards the customer bought or received, with remaining balance,
 * expiry date, usage history and a button to resend the gift card email.
 *
 * @link       https://wpswings.com/
 * @since      3.2.13
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/public
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * My Account gift cards endpoint.
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/public
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Wps_Wgm_My_Gift_Cards {

	/**
	 * Option that records which plugin version last flushed rewrite rules for the endpoint.
	 */
	const REWRITE_OPTION = 'wps_wgm_my_gift_cards_rewrite_version';

	/**
	 * Nonce action for the resend request.
	 */
	const NONCE_ACTION = 'wps-wgm-my-gift-cards';

	/**
	 * The common function object.
	 *
	 * @var Woocommerce_Gift_Cards_Common_Function
	 */
	public $wps_common_fun;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->wps_common_fun = new Woocommerce_Gift_Cards_Common_Function();
	}

	/**
	 * Endpoint slug used under My Account.
	 *
	 * @return string
	 */
	public function get_endpoint() {
		return apply_filters( 'wps_wgm_my_gift_cards_endpoint', 'my-gift-cards' );
	}

	/**
	 * Whether the tab should be shown. Off until the admin enables it under General settings.
	 *
	 * @return bool
	 */
	public function is_enabled() {
		$general_settings = wps_wgm_get_plugin_option( 'wps_wgm_general_settings' );
		$enabled          = 'on' === $this->wps_common_fun->wps_wgm_get_template_data( $general_settings, 'wps_wgm_general_setting_enable_my_account_tab' );
		return wps_wgm_giftcard_enable() && apply_filters( 'wps_wgm_my_gift_cards_enabled', $enabled );
	}

	/**
	 * Register the endpoint with WooCommerce, which adds both the query var and the rewrite rule.
	 *
	 * @param array $query_vars WooCommerce query vars.
	 * @return array
	 */
	public function wps_wgm_add_query_var( $query_vars ) {
		if ( $this->is_enabled() ) {
			$query_vars[ $this->get_endpoint() ] = $this->get_endpoint();
		}
		return $query_vars;
	}

	/**
	 * Flush rewrite rules once per plugin version so existing installs pick up the endpoint
	 * without needing to re-save permalinks.
	 *
	 * @return void
	 */
	public function wps_wgm_maybe_flush_rewrite_rules() {
		$state = WPS_WGC_VERSION . ':' . ( $this->is_enabled() ? $this->get_endpoint() : 'off' );
		if ( get_option( self::REWRITE_OPTION ) !== $state ) {
			flush_rewrite_rules( false );
			update_option( self::REWRITE_OPTION, $state );
		}
	}

	/**
	 * Add the tab to the My Account menu, just before Logout.
	 *
	 * @param array $items Menu items.
	 * @return array
	 */
	public function wps_wgm_add_menu_item( $items ) {
		if ( ! $this->is_enabled() ) {
			return $items;
		}
		$label  = __( 'My Gift Cards', 'woo-gift-cards-lite' );
		$logout = isset( $items['customer-logout'] ) ? $items['customer-logout'] : null;
		unset( $items['customer-logout'] );
		$items[ $this->get_endpoint() ] = $label;
		if ( null !== $logout ) {
			$items['customer-logout'] = $logout;
		}
		return $items;
	}

	/**
	 * Page title for the endpoint.
	 *
	 * @return string
	 */
	public function wps_wgm_endpoint_title() {
		return __( 'My Gift Cards', 'woo-gift-cards-lite' );
	}

	/**
	 * Load the tab's assets only on the tab itself.
	 *
	 * @return void
	 */
	public function wps_wgm_enqueue_assets() {
		if ( ! $this->is_enabled() || ! is_account_page() || ! is_wc_endpoint_url( $this->get_endpoint() ) ) {
			return;
		}
		wp_enqueue_style( 'wps-wgm-my-gift-cards', WPS_WGC_URL . 'public/css/wps-wgm-my-gift-cards.css', array(), WPS_WGC_VERSION );
		wp_enqueue_script( 'wps-wgm-my-gift-cards', WPS_WGC_URL . 'public/js/wps-wgm-my-gift-cards.js', array(), WPS_WGC_VERSION, true );
		wp_localize_script(
			'wps-wgm-my-gift-cards',
			'wps_wgm_my_gift_cards',
			array(
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( self::NONCE_ACTION ),
				'confirm'  => __( 'Resend this gift card email to the recipient?', 'woo-gift-cards-lite' ),
				'sending'  => __( 'Sending…', 'woo-gift-cards-lite' ),
				'error'    => __( 'Could not resend the email. Please try again later.', 'woo-gift-cards-lite' ),
				'copied'   => __( 'Copied!', 'woo-gift-cards-lite' ),
			)
		);
	}

	/**
	 * Render the tab content.
	 *
	 * @return void
	 */
	public function wps_wgm_render_endpoint() {
		$user_id = get_current_user_id();
		$cards   = $this->wps_wgm_get_user_gift_cards( $user_id );

		// Usable cards first, newest first within each group.
		usort(
			$cards,
			function ( $a, $b ) {
				$a_active = 'active' === $a['status'];
				$b_active = 'active' === $b['status'];
				return $a_active === $b_active ? $b['id'] - $a['id'] : ( $a_active ? -1 : 1 );
			}
		);

		$received  = array();
		$purchased = array();
		$summary   = array(
			'balance'  => 0,
			'active'   => 0,
			'expiring' => 0,
		);
		foreach ( $cards as $card ) {
			if ( $card['is_recipient'] ) {
				$received[] = $card;
				if ( 'active' === $card['status'] ) {
					$summary['balance'] += $card['balance'];
					++$summary['active'];
					$summary['expiring'] += $card['expiring_soon'] ? 1 : 0;
				}
			} else {
				$purchased[] = $card;
			}
		}

		$general_settings = wps_wgm_get_plugin_option( 'wps_wgm_general_settings' );
		$accent           = sanitize_hex_color( $this->wps_common_fun->wps_wgm_get_template_data( $general_settings, 'wps_wgm_giftcard_dashboard_color' ) );
		$accent           = sanitize_hex_color( apply_filters( 'wps_wgm_my_gift_cards_accent_color', $accent ? $accent : '#5b3cc4' ) );

		wc_get_template(
			'myaccount/wps-wgm-my-gift-cards.php',
			array(
				'received'  => $received,
				'purchased' => $purchased,
				'summary'   => $summary,
				'accent'    => $accent ? $accent : '#5b3cc4',
			),
			'woo-gift-cards-lite/',
			WPS_WGC_DIRPATH . 'public/partials/'
		);
	}

	/**
	 * Collect the gift cards a user bought or received.
	 *
	 * Received cards are matched on the recipient user ID stored at issue time, not on the
	 * account email, so changing an account email cannot expose someone else's card (#45219).
	 *
	 * @param int $user_id User ID.
	 * @return array[] Card data, newest first.
	 */
	public function wps_wgm_get_user_gift_cards( $user_id ) {
		if ( ! $user_id ) {
			return array();
		}

		$coupon_ids = array();

		$order_ids = wc_get_orders(
			array(
				'customer_id' => $user_id,
				'return'      => 'ids',
				'limit'       => -1,
			)
		);
		if ( ! empty( $order_ids ) ) {
			$coupon_ids = get_posts(
				array(
					'post_type'      => 'shop_coupon',
					'post_status'    => 'publish',
					'posts_per_page' => -1,
					'fields'         => 'ids',
					'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
						array(
							'key'     => 'wps_wgm_giftcard_coupon',
							'value'   => array_map( 'strval', $order_ids ),
							'compare' => 'IN',
						),
					),
				)
			);
		}

		$received_ids = get_posts(
			array(
				'post_type'      => 'shop_coupon',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_key'       => 'wps_wgm_giftcard_coupon_recipient_user_id', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'     => (string) $user_id, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);

		$coupon_ids = array_unique( array_map( 'absint', array_merge( $coupon_ids, $received_ids ) ) );
		rsort( $coupon_ids );

		$cards = array();
		foreach ( $coupon_ids as $coupon_id ) {
			$card = $this->wps_wgm_get_card_data( $coupon_id, $user_id );
			if ( $card ) {
				$cards[] = $card;
			}
		}

		$history = $this->wps_wgm_get_usage_history( wp_list_pluck( $cards, 'code' ), $user_id );
		foreach ( $cards as &$card ) {
			$key             = strtolower( $card['code'] );
			$card['history'] = isset( $history[ $key ] ) ? $history[ $key ] : array();
		}
		unset( $card );

		return apply_filters( 'wps_wgm_my_gift_cards', $cards, $user_id );
	}

	/**
	 * Status of a gift card: active, used, expired or disabled.
	 *
	 * @param WC_Coupon $coupon Gift card coupon.
	 * @return string
	 */
	public static function wps_wgm_get_card_status( $coupon ) {
		$expires = $coupon->get_date_expires();
		$limit   = $coupon->get_usage_limit();

		if ( 'no' === get_post_meta( $coupon->get_id(), '_wps_giftcard_enabled', true ) ) {
			return 'disabled';
		}
		if ( $expires && $expires->getTimestamp() < time() ) {
			return 'expired';
		}
		if ( (float) $coupon->get_amount() <= 0 || ( $limit && $coupon->get_usage_count() >= $limit ) ) {
			return 'used';
		}
		return 'active';
	}

	/**
	 * Build the display data for one gift card, as seen by the given user.
	 *
	 * @param int $coupon_id Coupon ID.
	 * @param int $user_id   Viewing user ID.
	 * @return array|null Null when the coupon is not a gift card the user bought or received.
	 */
	public function wps_wgm_get_card_data( $coupon_id, $user_id ) {
		$order_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true );
		$order    = $order_id ? wc_get_order( $order_id ) : false;
		if ( ! $order || 'publish' !== get_post_status( $coupon_id ) ) {
			return null;
		}

		$is_buyer     = (int) $order->get_customer_id() === (int) $user_id;
		$is_recipient = (int) get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_recipient_user_id', true ) === (int) $user_id;
		if ( ! $is_buyer && ! $is_recipient ) {
			return null;
		}

		$coupon   = new WC_Coupon( $coupon_id );
		$balance  = (float) $coupon->get_amount();
		$original = get_post_meta( $coupon_id, 'wps_wgm_coupon_amount', true );
		$original = '' !== $original ? (float) $original : $balance;
		$expires  = $coupon->get_date_expires();
		$status   = self::wps_wgm_get_card_status( $coupon );

		$from = '';
		$item = $this->wps_common_fun->wps_wgm_get_giftcard_order_item( $order, $coupon->get_code(), get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_product_id', true ) );
		if ( $item ) {
			$from = $item->get_meta( 'From', true );
		}
		if ( '' === $from ) {
			$from = trim( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() );
		}

		$mail_to   = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_mail_to', true );
		$days_left = $expires ? (int) ceil( ( $expires->getTimestamp() - time() ) / DAY_IN_SECONDS ) : null;

		return array(
			'days_left'     => $days_left,
			'expiring_soon' => 'active' === $status && null !== $days_left && $days_left <= apply_filters( 'wps_wgm_my_gift_cards_expiring_days', 30 ),
			'id'           => $coupon_id,
			'code'         => $coupon->get_code(),
			'balance'      => $balance,
			'original'     => $original,
			'expires'      => $expires,
			'status'       => $status,
			'is_buyer'     => $is_buyer,
			'is_recipient' => $is_recipient,
			'from'         => $from,
			'mail_to'      => $mail_to,
			'order'        => $is_buyer ? $order : null,
			'can_resend'   => 'active' === $status && is_email( $mail_to ),
		);
	}

	/**
	 * Look up the orders each gift card was redeemed on.
	 *
	 * Coupon line items live in the woocommerce_order_items table for both HPOS and
	 * legacy post storage, so one query covers every card.
	 *
	 * @param string[] $codes      Gift card codes.
	 * @param int      $user_id    Viewing user ID; order links are only shown for their own orders.
	 * @param bool     $all_orders Show every order number, for store staff.
	 * @return array Usage rows keyed by lowercase code.
	 */
	public function wps_wgm_get_usage_history( $codes, $user_id, $all_orders = false ) {
		global $wpdb;

		$codes = array_values( array_unique( array_filter( array_map( 'wc_format_coupon_code', $codes ) ) ) );
		if ( empty( $codes ) ) {
			return array();
		}

		$placeholders = implode( ', ', array_fill( 0, count( $codes ), '%s' ) );
		$rows         = $wpdb->get_results( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
			$wpdb->prepare(
				"SELECT order_item_id, order_item_name, order_id FROM {$wpdb->prefix}woocommerce_order_items WHERE order_item_type = 'coupon' AND order_item_name IN ( $placeholders ) ORDER BY order_id DESC", // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				$codes
			)
		);

		// Balance is restored on failed and cancelled orders, so those uses no longer count.
		$skip_statuses = array( 'failed', 'cancelled', 'checkout-draft', 'trash' );
		$history       = array();
		foreach ( $rows as $row ) {
			$order = wc_get_order( $row->order_id );
			if ( ! $order || in_array( $order->get_status(), $skip_statuses, true ) ) {
				continue;
			}
			$item = $order->get_item( $row->order_item_id );
			if ( ! $item instanceof WC_Order_Item_Coupon ) {
				continue;
			}

			// Mirrors how the balance is deducted in wps_wgm_woocommerce_new_order_item().
			$amount = (float) $item->get_discount();
			if ( wc_prices_include_tax() ) {
				$amount += (float) $item->get_discount_tax();
			}

			$own_order = $all_orders || (int) $order->get_customer_id() === (int) $user_id;

			$history[ strtolower( $row->order_item_name ) ][] = array(
				'date'         => $order->get_date_created(),
				'amount'       => $amount,
				'currency'     => $order->get_currency(),
				'order_number' => $own_order ? $order->get_order_number() : '',
				'order_url'    => $own_order ? $order->get_view_order_url() : '',
			);
		}

		return $history;
	}

	/**
	 * AJAX: resend a gift card email from the My Account tab.
	 *
	 * @return void
	 */
	public function wps_wgm_ajax_resend() {
		check_ajax_referer( self::NONCE_ACTION, 'nonce' );

		$user_id   = get_current_user_id();
		$coupon_id = isset( $_POST['coupon_id'] ) ? absint( $_POST['coupon_id'] ) : 0;
		$card      = ( $user_id && $coupon_id && $this->is_enabled() ) ? $this->wps_wgm_get_card_data( $coupon_id, $user_id ) : null;

		if ( ! $card ) {
			wp_send_json_error( array( 'message' => __( 'Gift card not found.', 'woo-gift-cards-lite' ) ), 403 );
		}
		if ( ! $card['can_resend'] ) {
			wp_send_json_error( array( 'message' => __( 'This gift card can no longer be resent.', 'woo-gift-cards-lite' ) ) );
		}

		// Throttle per card so the button cannot be used to flood the recipient's inbox.
		$throttle_key = 'wps_wgm_resend_' . $coupon_id;
		if ( get_transient( $throttle_key ) ) {
			wp_send_json_error( array( 'message' => __( 'This gift card email was sent recently. Please wait a few minutes before trying again.', 'woo-gift-cards-lite' ) ) );
		}
		set_transient( $throttle_key, 1, apply_filters( 'wps_wgm_my_gift_cards_resend_interval', 10 * MINUTE_IN_SECONDS ) );

		// The buyer's "card sent" confirmation would only confuse them when the recipient resends.
		$overrides = $card['is_buyer'] ? array() : array( 'disable_buyer_notice' => 'on' );
		$result    = $this->wps_common_fun->wps_wgm_resend_giftcard_email( $coupon_id, $overrides );
		if ( is_wp_error( $result ) ) {
			delete_transient( $throttle_key );
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		$order = wc_get_order( get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true ) );
		if ( $order ) {
			$order->add_order_note(
				sprintf(
					/* translators: 1: gift card code, 2: recipient email. */
					__( 'Gift card %1$s email resent to %2$s by the customer from My Account.', 'woo-gift-cards-lite' ),
					$card['code'],
					$card['mail_to']
				)
			);
		}

		wp_send_json_success(
			array(
				/* translators: %s: recipient email. */
				'message' => sprintf( __( 'Gift card email sent to %s.', 'woo-gift-cards-lite' ), $card['mail_to'] ),
			)
		);
	}
}
