<?php
/**
 * Gift card transaction log / balance audit trail.
 *
 * Every change to a gift card balance is written to one table with the balance before
 * and after, the amount, the order, the user who made the change, where it came from
 * and when. Code that changes a balance either labels the change first with
 * Wps_Wgm_Transactions::set_context() (the meta listener then writes the row) or calls
 * Wps_Wgm_Transactions::log() itself. A change nobody labelled is still logged, as an
 * "adjustment" from an "unknown" source, so no balance change goes unrecorded.
 *
 * @link       https://wpswings.com/
 * @since      3.3.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Records and reads gift card balance changes.
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/includes
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Wps_Wgm_Transactions {

	/**
	 * Schema version of the transactions table.
	 */
	const DB_VERSION = '1.0.0';

	/**
	 * Option holding the schema version. Separate from wps_wgm_db_version, which the
	 * failure-tracker table owns and plugin activation resets.
	 */
	const DB_VERSION_OPTION = 'wps_wgm_transactions_db_version';

	/**
	 * Table name without the WordPress prefix.
	 */
	const TABLE = 'wps_gc_transactions';

	/**
	 * Background action that writes opening-balance rows for existing cards.
	 */
	const BACKFILL_HOOK = 'wps_wgm_transactions_backfill';

	/**
	 * Daily cron event that logs cards which expired with a balance left.
	 */
	const EXPIRY_HOOK = 'wps_wgm_transactions_expiry_check';

	/**
	 * Cards handled per backfill or expiry batch.
	 */
	const BATCH_SIZE = 200;

	/**
	 * Labels pushed by code that is about to change a balance.
	 *
	 * @var array[]
	 */
	private static $context = array();

	/**
	 * Balance of each coupon read just before its coupon_amount meta is updated.
	 *
	 * @var array
	 */
	private static $before = array();

	/**
	 * Expiry timestamp of each coupon read before the admin coupon screen saves it.
	 *
	 * @var array
	 */
	private static $expiry_before = array();

	/**
	 * Whether the table exists at the current schema version (cached per request).
	 *
	 * @var bool|null
	 */
	private static $ready = null;

	/**
	 * Register the hooks. Called once from the core plugin class.
	 *
	 * @return void
	 */
	public static function init() {
		static $done = false;
		if ( $done ) {
			return;
		}
		$done = true;

		add_action( 'init', array( __CLASS__, 'maybe_upgrade' ), 5 );
		add_action( 'init', array( __CLASS__, 'schedule_events' ) );
		add_action( self::BACKFILL_HOOK, array( __CLASS__, 'run_backfill' ) );
		add_action( self::EXPIRY_HOOK, array( __CLASS__, 'run_expiry_check' ) );

		// Balance listener: snapshot the old value, then log the change.
		add_filter( 'update_post_metadata', array( __CLASS__, 'snapshot_balance' ), PHP_INT_MAX, 5 );
		add_action( 'updated_post_meta', array( __CLASS__, 'balance_updated' ), 10, 4 );

		// Admin edits on the coupon screen. WooCommerce saves the coupon at priority 10.
		add_action( 'woocommerce_process_shop_coupon_meta', array( __CLASS__, 'before_admin_coupon_save' ), 1 );
		add_action( 'woocommerce_process_shop_coupon_meta', array( __CLASS__, 'after_admin_coupon_save' ), 99 );

		// Personal data export and erasure.
		add_filter( 'wp_privacy_personal_data_exporters', array( __CLASS__, 'register_exporter' ) );
		add_filter( 'wp_privacy_personal_data_erasers', array( __CLASS__, 'register_eraser' ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Schema
	|--------------------------------------------------------------------------
	*/

	/**
	 * Full table name.
	 *
	 * @return string
	 */
	public static function table_name() {
		global $wpdb;
		return $wpdb->prefix . self::TABLE;
	}

	/**
	 * Create or update the table.
	 *
	 * @return void
	 */
	public static function create_table() {
		global $wpdb;

		$table           = self::table_name();
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			coupon_id bigint(20) unsigned NOT NULL DEFAULT 0,
			coupon_code varchar(191) NOT NULL DEFAULT '',
			type varchar(32) NOT NULL DEFAULT '',
			amount_change decimal(19,4) NOT NULL DEFAULT 0,
			balance_before decimal(19,4) NOT NULL DEFAULT 0,
			balance_after decimal(19,4) NOT NULL DEFAULT 0,
			currency char(3) NOT NULL DEFAULT '',
			order_id bigint(20) unsigned DEFAULT NULL,
			user_id bigint(20) unsigned DEFAULT NULL,
			source varchar(32) NOT NULL DEFAULT '',
			note text,
			meta longtext,
			created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY  (id),
			KEY coupon_id (coupon_id),
			KEY order_id (order_id),
			KEY user_id (user_id),
			KEY type (type),
			KEY created_at (created_at)
		) {$charset_collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}

	/**
	 * Create the table when the stored schema version is older. Runs on every request
	 * because plugin updates do not fire the activation hook.
	 *
	 * @return void
	 */
	public static function maybe_upgrade() {
		$installed = get_option( self::DB_VERSION_OPTION, '' );
		if ( $installed && version_compare( $installed, self::DB_VERSION, '>=' ) ) {
			return;
		}

		self::create_table();
		if ( ! self::table_exists() ) {
			return;
		}

		update_option( self::DB_VERSION_OPTION, self::DB_VERSION );
		self::$ready = true;
		self::queue_backfill( 0 );
	}

	/**
	 * Whether the table is in the database.
	 *
	 * @return bool
	 */
	private static function table_exists() {
		global $wpdb;
		$table = self::table_name();
		return $table === $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table ) ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}

	/**
	 * Whether rows can be written.
	 *
	 * @return bool
	 */
	public static function is_ready() {
		if ( null === self::$ready ) {
			$installed   = get_option( self::DB_VERSION_OPTION, '' );
			self::$ready = $installed && version_compare( $installed, self::DB_VERSION, '>=' );
		}
		return self::$ready;
	}

	/**
	 * Schedule the daily expiry check.
	 *
	 * @return void
	 */
	public static function schedule_events() {
		if ( ! wp_next_scheduled( self::EXPIRY_HOOK ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', self::EXPIRY_HOOK );
		}
	}

	/**
	 * Remove the scheduled events. Called on deactivation.
	 *
	 * @return void
	 */
	public static function clear_events() {
		wp_clear_scheduled_hook( self::EXPIRY_HOOK );
	}

	/*
	|--------------------------------------------------------------------------
	| Types, sources and gift card detection
	|--------------------------------------------------------------------------
	*/

	/**
	 * Transaction types and their labels.
	 *
	 * @return array
	 */
	public static function get_types() {
		return apply_filters(
			'wps_wgm_transaction_types',
			array(
				'opening_balance'   => __( 'Opening balance', 'woo-gift-cards-lite' ),
				'issued'            => __( 'Issued', 'woo-gift-cards-lite' ),
				'redeemed'          => __( 'Redeemed', 'woo-gift-cards-lite' ),
				'refund_restore'    => __( 'Refund restored', 'woo-gift-cards-lite' ),
				'cancelled_restore' => __( 'Cancelled order restored', 'woo-gift-cards-lite' ),
				'admin_edit'        => __( 'Admin edit', 'woo-gift-cards-lite' ),
				'recharge'          => __( 'Recharge', 'woo-gift-cards-lite' ),
				'expired'           => __( 'Expired', 'woo-gift-cards-lite' ),
				'reactivated'       => __( 'Reactivated', 'woo-gift-cards-lite' ),
				'adjustment'        => __( 'Adjustment', 'woo-gift-cards-lite' ),
			)
		);
	}

	/**
	 * Sources and their labels.
	 *
	 * @return array
	 */
	public static function get_sources() {
		return apply_filters(
			'wps_wgm_transaction_sources',
			array(
				'checkout'  => __( 'Checkout', 'woo-gift-cards-lite' ),
				'order'     => __( 'Order status change', 'woo-gift-cards-lite' ),
				'admin'     => __( 'Admin', 'woo-gift-cards-lite' ),
				'rest'      => __( 'REST API', 'woo-gift-cards-lite' ),
				'cron'      => __( 'Scheduled task', 'woo-gift-cards-lite' ),
				'wallet'    => __( 'Wallet transfer', 'woo-gift-cards-lite' ),
				'points'    => __( 'Points conversion', 'woo-gift-cards-lite' ),
				'account'   => __( 'My Account', 'woo-gift-cards-lite' ),
				'import'    => __( 'Import', 'woo-gift-cards-lite' ),
				'bulk'      => __( 'Bulk generator', 'woo-gift-cards-lite' ),
				'migration' => __( 'Upgrade', 'woo-gift-cards-lite' ),
				'unknown'   => __( 'Unknown', 'woo-gift-cards-lite' ),
			)
		);
	}

	/**
	 * Label for a type or source key, falling back to the key itself.
	 *
	 * @param string $key  Type or source key.
	 * @param string $kind 'type' or 'source'.
	 * @return string
	 */
	public static function label( $key, $kind = 'type' ) {
		$labels = 'source' === $kind ? self::get_sources() : self::get_types();
		return isset( $labels[ $key ] ) ? $labels[ $key ] : $key;
	}

	/**
	 * Coupon meta keys that mark a coupon as a gift card.
	 *
	 * @return string[]
	 */
	public static function gift_card_meta_keys() {
		return apply_filters( 'wps_wgm_transactions_gift_card_meta_keys', array( 'wps_wgm_giftcard_coupon', 'wps_wgm_giftcard_coupon_unique' ) );
	}

	/**
	 * Whether a coupon is a gift card.
	 *
	 * @param int $coupon_id Coupon ID.
	 * @return bool
	 */
	public static function is_gift_card( $coupon_id ) {
		$is_gift_card = false;
		foreach ( self::gift_card_meta_keys() as $key ) {
			$value = get_post_meta( $coupon_id, $key, true );
			if ( '' !== $value && null !== $value && false !== $value ) {
				$is_gift_card = true;
				break;
			}
		}
		return (bool) apply_filters( 'wps_wgm_transactions_is_gift_card', $is_gift_card, $coupon_id );
	}

	/*
	|--------------------------------------------------------------------------
	| Context
	|--------------------------------------------------------------------------
	*/

	/**
	 * Label the balance changes that follow. Call clear_context() when done.
	 *
	 * @param string $type Transaction type.
	 * @param array  $args Optional order_id, source, note, meta, user_id, coupon_id (limits the label to one card).
	 * @return void
	 */
	public static function set_context( $type, $args = array() ) {
		$args['type']    = $type;
		self::$context[] = $args;
	}

	/**
	 * Remove the most recent label.
	 *
	 * @return void
	 */
	public static function clear_context() {
		array_pop( self::$context );
	}

	/**
	 * The label that applies to a coupon, if any.
	 *
	 * @param int $coupon_id Coupon ID.
	 * @return array
	 */
	public static function get_context( $coupon_id = 0 ) {
		for ( $i = count( self::$context ) - 1; $i >= 0; $i-- ) {
			$context = self::$context[ $i ];
			if ( empty( $context['coupon_id'] ) || (int) $context['coupon_id'] === (int) $coupon_id ) {
				return $context;
			}
		}
		return array();
	}

	/**
	 * Best guess of the source when nobody labelled the change.
	 *
	 * @return string
	 */
	private static function detect_source() {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return 'rest';
		}
		if ( wp_doing_cron() ) {
			return 'cron';
		}
		if ( is_admin() && ! wp_doing_ajax() ) {
			return 'admin';
		}
		return 'unknown';
	}

	/*
	|--------------------------------------------------------------------------
	| Writing rows
	|--------------------------------------------------------------------------
	*/

	/**
	 * Write a transaction row.
	 *
	 * @param array $args {
	 *     @type int    $coupon_id      Required.
	 *     @type string $type           One of get_types().
	 *     @type float  $balance_before Balance before the change.
	 *     @type float  $balance_after  Balance after the change.
	 *     @type float  $amount_change  Defaults to after minus before.
	 *     @type int    $order_id       Related order.
	 *     @type int    $user_id        Who made the change. Defaults to the current user.
	 *     @type string $source         One of get_sources().
	 *     @type string $note           Free text.
	 *     @type array  $meta           Extra data, stored as JSON.
	 *     @type string $currency       Defaults to the store currency.
	 *     @type string $created_at     GMT datetime. Defaults to now.
	 * }
	 * @return int|false Row ID.
	 */
	public static function log( $args ) {
		global $wpdb;

		if ( ! self::is_ready() ) {
			return false;
		}

		// Logging must never break the flow that changed the balance, or print database
		// errors into an AJAX or REST response.
		$suppress = $wpdb->suppress_errors( true );
		try {
			$id = self::write_row( $args );
		} catch ( Throwable $e ) {
			$id = false;
		}
		$wpdb->suppress_errors( $suppress );

		return $id;
	}

	/**
	 * Insert the row. Called by log() with database errors suppressed.
	 *
	 * @param array $args See log().
	 * @return int|false
	 */
	private static function write_row( $args ) {
		global $wpdb;

		$coupon_id = isset( $args['coupon_id'] ) ? absint( $args['coupon_id'] ) : 0;
		if ( ! $coupon_id ) {
			return false;
		}

		$before = isset( $args['balance_before'] ) ? (float) $args['balance_before'] : 0.0;
		$after  = isset( $args['balance_after'] ) ? (float) $args['balance_after'] : 0.0;
		$type   = ! empty( $args['type'] ) ? sanitize_key( $args['type'] ) : 'adjustment';

		// The first row for a card that existed before the log carries its starting balance.
		if ( ! in_array( $type, array( 'issued', 'opening_balance' ), true ) && ! self::has_rows( $coupon_id ) ) {
			self::insert_opening_balance( $coupon_id, $before );
		}

		$code = isset( $args['coupon_code'] ) ? $args['coupon_code'] : get_the_title( $coupon_id );
		$meta = isset( $args['meta'] ) && is_array( $args['meta'] ) && ! empty( $args['meta'] ) ? wp_json_encode( $args['meta'] ) : null;

		$row = array(
			'coupon_id'      => $coupon_id,
			'coupon_code'    => wc_format_coupon_code( $code ),
			'type'           => $type,
			'amount_change'  => isset( $args['amount_change'] ) ? (float) $args['amount_change'] : round( $after - $before, 4 ),
			'balance_before' => $before,
			'balance_after'  => $after,
			'currency'       => ! empty( $args['currency'] ) ? strtoupper( substr( sanitize_text_field( $args['currency'] ), 0, 3 ) ) : get_woocommerce_currency(),
			'order_id'       => ! empty( $args['order_id'] ) ? absint( $args['order_id'] ) : null,
			'user_id'        => isset( $args['user_id'] ) ? ( absint( $args['user_id'] ) ? absint( $args['user_id'] ) : null ) : ( get_current_user_id() ? get_current_user_id() : null ),
			'source'         => ! empty( $args['source'] ) ? sanitize_key( $args['source'] ) : self::detect_source(),
			'note'           => isset( $args['note'] ) ? sanitize_textarea_field( $args['note'] ) : '',
			'meta'           => $meta,
			'created_at'     => ! empty( $args['created_at'] ) ? $args['created_at'] : current_time( 'mysql', true ),
		);

		$row = apply_filters( 'wps_wgm_transaction_row', $row, $args );
		if ( empty( $row ) ) {
			return false;
		}

		$inserted = $wpdb->insert( self::table_name(), $row ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		if ( ! $inserted ) {
			return false;
		}

		$row['id'] = (int) $wpdb->insert_id;

		/**
		 * Fires after a gift card transaction is logged.
		 *
		 * @param int   $id  Row ID.
		 * @param array $row Row data.
		 */
		do_action( 'wps_wgm_transaction_logged', $row['id'], $row );

		return $row['id'];
	}

	/**
	 * Log a change, taking type, order, source and note from the current label.
	 *
	 * @param int   $coupon_id Coupon ID.
	 * @param float $before    Balance before.
	 * @param float $after     Balance after.
	 * @param array $args      Values that override the label.
	 * @return int|false
	 */
	public static function log_change( $coupon_id, $before, $after, $args = array() ) {
		$context = self::get_context( $coupon_id );
		unset( $context['coupon_id'] );

		$args = array_merge(
			array( 'type' => 'adjustment' ),
			$context,
			$args,
			array(
				'coupon_id'      => $coupon_id,
				'balance_before' => $before,
				'balance_after'  => $after,
			)
		);
		return self::log( $args );
	}

	/**
	 * Log a newly created gift card.
	 *
	 * @param int    $coupon_id Coupon ID.
	 * @param array  $args      Optional order_id, source, note, meta.
	 * @return int|false
	 */
	public static function log_issued( $coupon_id, $args = array() ) {
		$amount = (float) get_post_meta( $coupon_id, 'coupon_amount', true );
		return self::log(
			array_merge(
				array(
					'source' => 'checkout',
				),
				$args,
				array(
					'coupon_id'      => $coupon_id,
					'type'           => 'issued',
					'balance_before' => 0,
					'balance_after'  => $amount,
				)
			)
		);
	}

	/**
	 * Whether a coupon has any rows.
	 *
	 * @param int $coupon_id Coupon ID.
	 * @return bool
	 */
	public static function has_rows( $coupon_id ) {
		global $wpdb;
		$table = self::table_name();
		return (bool) $wpdb->get_var( $wpdb->prepare( "SELECT 1 FROM {$table} WHERE coupon_id = %d LIMIT 1", $coupon_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}

	/**
	 * Write the opening-balance row for a card that predates the log.
	 *
	 * @param int   $coupon_id Coupon ID.
	 * @param float $balance   Balance at that point.
	 * @return int|false
	 */
	private static function insert_opening_balance( $coupon_id, $balance ) {
		return self::log(
			array(
				'coupon_id'      => $coupon_id,
				'type'           => 'opening_balance',
				'balance_before' => $balance,
				'balance_after'  => $balance,
				'amount_change'  => 0,
				'source'         => 'migration',
				'user_id'        => 0,
			)
		);
	}

	/*
	|--------------------------------------------------------------------------
	| Balance listener
	|--------------------------------------------------------------------------
	*/

	/**
	 * Read the current balance just before coupon_amount is updated.
	 *
	 * @param null|bool $check      Short-circuit value from earlier filters.
	 * @param int       $object_id  Post ID.
	 * @param string    $meta_key   Meta key.
	 * @param mixed     $meta_value New value.
	 * @param mixed     $prev_value Previous value condition.
	 * @return null|bool
	 */
	public static function snapshot_balance( $check, $object_id, $meta_key, $meta_value, $prev_value ) {
		if ( null !== $check || 'coupon_amount' !== $meta_key || ! self::is_ready() ) {
			return $check;
		}
		if ( 'shop_coupon' !== get_post_type( $object_id ) ) {
			return $check;
		}

		try {
			self::$before[ $object_id ] = self::read_balance( $object_id );
		} catch ( Throwable $e ) {
			unset( self::$before[ $object_id ] );
		}
		return $check;
	}

	/**
	 * Log the change once coupon_amount has been updated.
	 *
	 * @param int    $meta_id    Meta ID.
	 * @param int    $object_id  Post ID.
	 * @param string $meta_key   Meta key.
	 * @param mixed  $meta_value New value.
	 * @return void
	 */
	public static function balance_updated( $meta_id, $object_id, $meta_key, $meta_value ) {
		if ( 'coupon_amount' !== $meta_key || ! array_key_exists( $object_id, self::$before ) ) {
			return;
		}

		$before = self::$before[ $object_id ];
		unset( self::$before[ $object_id ] );

		try {
			if ( null === $before || ! self::is_gift_card( $object_id ) ) {
				return;
			}

			$after = self::to_amount( $meta_value );
			if ( abs( $after - $before ) < 0.00001 ) {
				return;
			}

			self::log_change( $object_id, $before, $after );
		} catch ( Throwable $e ) {
			return;
		}
	}

	/**
	 * Current balance straight from the database, bypassing the object cache.
	 *
	 * @param int $coupon_id Coupon ID.
	 * @return float|null Null when the coupon has no amount yet.
	 */
	public static function read_balance( $coupon_id ) {
		global $wpdb;
		$value = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = 'coupon_amount' LIMIT 1", $coupon_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		return null === $value ? null : self::to_amount( $value );
	}

	/**
	 * Convert a stored amount (which may use the store decimal separator) to a float.
	 *
	 * @param mixed $value Raw value.
	 * @return float
	 */
	private static function to_amount( $value ) {
		if ( is_numeric( $value ) ) {
			return (float) $value;
		}
		$separator = get_option( 'woocommerce_price_decimal_sep', '.' );
		return (float) str_replace( $separator, '.', (string) $value );
	}

	/*
	|--------------------------------------------------------------------------
	| Admin coupon screen
	|--------------------------------------------------------------------------
	*/

	/**
	 * Label the save as an admin edit and remember the expiry date.
	 *
	 * @param int $post_id Coupon ID.
	 * @return void
	 */
	public static function before_admin_coupon_save( $post_id ) {
		if ( ! self::is_ready() || ! self::is_gift_card( $post_id ) ) {
			return;
		}
		self::$expiry_before[ $post_id ] = (int) get_post_meta( $post_id, 'date_expires', true );
		self::set_context(
			'admin_edit',
			array(
				'coupon_id' => $post_id,
				'source'    => 'admin',
			)
		);
	}

	/**
	 * Clear the label, and log a reactivation when an expired card gets a later expiry date.
	 *
	 * @param int $post_id Coupon ID.
	 * @return void
	 */
	public static function after_admin_coupon_save( $post_id ) {
		if ( ! array_key_exists( $post_id, self::$expiry_before ) ) {
			return;
		}
		self::clear_context();
		unset( self::$expiry_before[ $post_id ] );

		global $wpdb;
		$suppress = $wpdb->suppress_errors( true );
		$last     = self::get_last_row( $post_id );
		$wpdb->suppress_errors( $suppress );
		if ( ! $last || 'expired' !== $last->type ) {
			return;
		}

		clean_post_cache( $post_id );
		$expiry  = (int) get_post_meta( $post_id, 'date_expires', true );
		$balance = (float) self::read_balance( $post_id );
		if ( ( 0 === $expiry || $expiry > time() ) && $balance > 0 ) {
			self::log(
				array(
					'coupon_id'      => $post_id,
					'type'           => 'reactivated',
					'balance_before' => 0,
					'balance_after'  => $balance,
					'source'         => 'admin',
					'note'           => $expiry ? sprintf( 'New expiry: %s', gmdate( 'Y-m-d', $expiry ) ) : 'Expiry removed',
				)
			);
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Expiry
	|--------------------------------------------------------------------------
	*/

	/**
	 * Log cards that expired with a balance left. The stored coupon amount is not changed:
	 * the row records the value lost (breakage) with balance_after 0.
	 *
	 * @return void
	 */
	public static function run_expiry_check() {
		if ( ! self::is_ready() ) {
			return;
		}

		$batches = (int) apply_filters( 'wps_wgm_transactions_expiry_batches', 10 );
		for ( $i = 0; $i < $batches; $i++ ) {
			$ids = self::find_newly_expired( self::BATCH_SIZE );
			foreach ( $ids as $coupon_id ) {
				$balance = (float) self::read_balance( $coupon_id );
				$expiry  = (int) get_post_meta( $coupon_id, 'date_expires', true );
				self::log(
					array(
						'coupon_id'      => $coupon_id,
						'type'           => 'expired',
						'balance_before' => $balance,
						'balance_after'  => 0,
						'source'         => 'cron',
						'user_id'        => 0,
						'note'           => sprintf( 'Expired on %s', gmdate( 'Y-m-d', $expiry ) ),
					)
				);
			}
			if ( count( $ids ) < self::BATCH_SIZE ) {
				break;
			}
		}
	}

	/**
	 * Gift cards past their expiry date, with a balance, whose last row is not "expired".
	 *
	 * @param int $limit Maximum IDs.
	 * @return int[]
	 */
	private static function find_newly_expired( $limit ) {
		global $wpdb;

		$table = self::table_name();
		$keys  = self::gift_card_meta_keys();
		$in    = implode( ', ', array_fill( 0, count( $keys ), '%s' ) );

		// phpcs:disable WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		$ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT p.ID FROM {$wpdb->posts} p
				INNER JOIN {$wpdb->postmeta} e ON e.post_id = p.ID AND e.meta_key = 'date_expires'
				INNER JOIN {$wpdb->postmeta} a ON a.post_id = p.ID AND a.meta_key = 'coupon_amount'
				WHERE p.post_type = 'shop_coupon' AND p.post_status = 'publish'
				AND e.meta_value <> '' AND CAST( e.meta_value AS UNSIGNED ) BETWEEN 1 AND %d
				AND CAST( a.meta_value AS DECIMAL(19,4) ) > 0
				AND EXISTS ( SELECT 1 FROM {$wpdb->postmeta} g WHERE g.post_id = p.ID AND g.meta_key IN ( {$in} ) AND g.meta_value <> '' )
				AND COALESCE( ( SELECT t.type FROM {$table} t WHERE t.coupon_id = p.ID ORDER BY t.id DESC LIMIT 1 ), '' ) <> 'expired'
				ORDER BY p.ID ASC
				LIMIT %d",
				array_merge( array( time() ), $keys, array( $limit ) )
			)
		);
		// phpcs:enable

		return array_map( 'absint', $ids );
	}

	/*
	|--------------------------------------------------------------------------
	| Backfill
	|--------------------------------------------------------------------------
	*/

	/**
	 * Queue the next backfill batch.
	 *
	 * @param int $last_id Last coupon ID handled.
	 * @return void
	 */
	private static function queue_backfill( $last_id ) {
		$args = array( 'last_id' => (int) $last_id );
		if ( function_exists( 'as_enqueue_async_action' ) ) {
			as_enqueue_async_action( self::BACKFILL_HOOK, $args, 'wps-wgm' );
		} elseif ( ! wp_next_scheduled( self::BACKFILL_HOOK, $args ) ) {
			wp_schedule_single_event( time() + 10, self::BACKFILL_HOOK, $args );
		}
	}

	/**
	 * Write one opening-balance row for each existing card that has no rows yet.
	 *
	 * @param int $last_id Last coupon ID handled by the previous batch.
	 * @return void
	 */
	public static function run_backfill( $last_id = 0 ) {
		global $wpdb;

		if ( ! self::is_ready() ) {
			return;
		}

		$keys = self::gift_card_meta_keys();
		$in   = implode( ', ', array_fill( 0, count( $keys ), '%s' ) );

		// phpcs:disable WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
		$ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT DISTINCT p.ID FROM {$wpdb->posts} p
				INNER JOIN {$wpdb->postmeta} g ON g.post_id = p.ID AND g.meta_key IN ( {$in} ) AND g.meta_value <> ''
				WHERE p.post_type = 'shop_coupon' AND p.ID > %d
				ORDER BY p.ID ASC
				LIMIT %d",
				array_merge( $keys, array( (int) $last_id, self::BATCH_SIZE ) )
			)
		);
		// phpcs:enable

		foreach ( $ids as $coupon_id ) {
			$coupon_id = (int) $coupon_id;
			if ( self::has_rows( $coupon_id ) ) {
				continue;
			}
			self::insert_opening_balance( $coupon_id, (float) self::read_balance( $coupon_id ) );
		}

		if ( count( $ids ) === self::BATCH_SIZE ) {
			self::queue_backfill( (int) end( $ids ) );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Reading rows
	|--------------------------------------------------------------------------
	*/

	/**
	 * Last row of a card.
	 *
	 * @param int $coupon_id Coupon ID.
	 * @return object|null
	 */
	public static function get_last_row( $coupon_id ) {
		global $wpdb;
		$table = self::table_name();
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE coupon_id = %d ORDER BY id DESC LIMIT 1", $coupon_id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}

	/**
	 * Build the WHERE clause for a filtered query.
	 *
	 * @param array $filters coupon_id, coupon_ids, code, type, source, order_id, user_id, date_from, date_to (Y-m-d, store time).
	 * @return array SQL string and its values.
	 */
	private static function build_where( $filters ) {
		global $wpdb;

		$where  = array( '1=1' );
		$values = array();

		if ( ! empty( $filters['coupon_id'] ) ) {
			$where[]  = 'coupon_id = %d';
			$values[] = absint( $filters['coupon_id'] );
		}
		if ( ! empty( $filters['coupon_ids'] ) && is_array( $filters['coupon_ids'] ) ) {
			$ids      = array_map( 'absint', $filters['coupon_ids'] );
			$where[]  = 'coupon_id IN ( ' . implode( ', ', array_fill( 0, count( $ids ), '%d' ) ) . ' )';
			$values   = array_merge( $values, $ids );
		}
		if ( ! empty( $filters['codes'] ) && is_array( $filters['codes'] ) ) {
			$codes    = array_map( 'wc_format_coupon_code', $filters['codes'] );
			$where[]  = 'coupon_code IN ( ' . implode( ', ', array_fill( 0, count( $codes ), '%s' ) ) . ' )';
			$values   = array_merge( $values, $codes );
		}
		if ( ! empty( $filters['code'] ) ) {
			$where[]  = 'coupon_code LIKE %s';
			$values[] = '%' . $wpdb->esc_like( wc_format_coupon_code( $filters['code'] ) ) . '%';
		}
		if ( ! empty( $filters['type'] ) ) {
			$where[]  = 'type = %s';
			$values[] = sanitize_key( $filters['type'] );
		}
		if ( ! empty( $filters['exclude_types'] ) && is_array( $filters['exclude_types'] ) ) {
			$types    = array_map( 'sanitize_key', $filters['exclude_types'] );
			$where[]  = 'type NOT IN ( ' . implode( ', ', array_fill( 0, count( $types ), '%s' ) ) . ' )';
			$values   = array_merge( $values, $types );
		}
		if ( ! empty( $filters['source'] ) ) {
			$where[]  = 'source = %s';
			$values[] = sanitize_key( $filters['source'] );
		}
		if ( ! empty( $filters['order_id'] ) ) {
			$where[]  = 'order_id = %d';
			$values[] = absint( $filters['order_id'] );
		}
		if ( ! empty( $filters['date_from'] ) && strtotime( $filters['date_from'] ) ) {
			$where[]  = 'created_at >= %s';
			$values[] = get_gmt_from_date( gmdate( 'Y-m-d 00:00:00', strtotime( $filters['date_from'] ) ) );
		}
		if ( ! empty( $filters['date_to'] ) && strtotime( $filters['date_to'] ) ) {
			$where[]  = 'created_at <= %s';
			$values[] = get_gmt_from_date( gmdate( 'Y-m-d 23:59:59', strtotime( $filters['date_to'] ) ) );
		}

		return array( implode( ' AND ', $where ), $values );
	}

	/**
	 * Query rows.
	 *
	 * @param array $filters See build_where(), plus per_page, page, order ('ASC'|'DESC').
	 * @return object[]
	 */
	public static function query( $filters = array() ) {
		global $wpdb;

		if ( ! self::is_ready() ) {
			return array();
		}

		list( $where, $values ) = self::build_where( $filters );

		$order    = isset( $filters['order'] ) && 'ASC' === strtoupper( $filters['order'] ) ? 'ASC' : 'DESC';
		$per_page = isset( $filters['per_page'] ) ? (int) $filters['per_page'] : 20;
		$page     = isset( $filters['page'] ) ? max( 1, (int) $filters['page'] ) : 1;
		$table    = self::table_name();
		$sql      = "SELECT * FROM {$table} WHERE {$where} ORDER BY id {$order}";

		if ( $per_page > 0 ) {
			$sql     .= ' LIMIT %d OFFSET %d';
			$values[] = $per_page;
			$values[] = ( $page - 1 ) * $per_page;
		}

		return $values ? $wpdb->get_results( $wpdb->prepare( $sql, $values ) ) : $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Count rows.
	 *
	 * @param array $filters See build_where().
	 * @return int
	 */
	public static function count( $filters = array() ) {
		global $wpdb;

		if ( ! self::is_ready() ) {
			return 0;
		}

		list( $where, $values ) = self::build_where( $filters );

		$table = self::table_name();
		$sql   = "SELECT COUNT(*) FROM {$table} WHERE {$where}";
		return (int) ( $values ? $wpdb->get_var( $wpdb->prepare( $sql, $values ) ) : $wpdb->get_var( $sql ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.NotPrepared
	}

	/**
	 * Date of the first row of each code, keyed by lower-case code.
	 *
	 * @param string[] $codes Coupon codes.
	 * @return array GMT datetimes.
	 */
	public static function first_dates( $codes ) {
		global $wpdb;

		$codes = array_values( array_filter( array_map( 'wc_format_coupon_code', (array) $codes ) ) );
		if ( empty( $codes ) || ! self::is_ready() ) {
			return array();
		}

		$table = self::table_name();
		$in    = implode( ', ', array_fill( 0, count( $codes ), '%s' ) );
		$rows  = $wpdb->get_results( $wpdb->prepare( "SELECT coupon_code, MIN(created_at) AS first_at FROM {$table} WHERE coupon_code IN ( {$in} ) GROUP BY coupon_code", $codes ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare

		$dates = array();
		foreach ( $rows as $row ) {
			$dates[ strtolower( $row->coupon_code ) ] = $row->first_at;
		}
		return $dates;
	}

	/**
	 * Row date in the store time zone.
	 *
	 * @param object $row Row.
	 * @return WC_DateTime|null
	 */
	public static function row_date( $row ) {
		return wc_string_to_datetime( $row->created_at . '+00:00' );
	}

	/*
	|--------------------------------------------------------------------------
	| Privacy
	|--------------------------------------------------------------------------
	*/

	/**
	 * Register the personal data exporter.
	 *
	 * @param array $exporters Exporters.
	 * @return array
	 */
	public static function register_exporter( $exporters ) {
		$exporters['wps-wgm-transactions'] = array(
			'exporter_friendly_name' => __( 'Gift card transactions', 'woo-gift-cards-lite' ),
			'callback'               => array( __CLASS__, 'export_personal_data' ),
		);
		return $exporters;
	}

	/**
	 * Register the personal data eraser.
	 *
	 * @param array $erasers Erasers.
	 * @return array
	 */
	public static function register_eraser( $erasers ) {
		$erasers['wps-wgm-transactions'] = array(
			'eraser_friendly_name' => __( 'Gift card transactions', 'woo-gift-cards-lite' ),
			'callback'             => array( __CLASS__, 'erase_personal_data' ),
		);
		return $erasers;
	}

	/**
	 * Export the transactions a user made.
	 *
	 * @param string $email_address Email.
	 * @param int    $page          Page.
	 * @return array
	 */
	public static function export_personal_data( $email_address, $page = 1 ) {
		global $wpdb;

		$user = get_user_by( 'email', $email_address );
		if ( ! $user || ! self::is_ready() ) {
			return array(
				'data' => array(),
				'done' => true,
			);
		}

		$limit = 100;
		$table = self::table_name();
		$rows  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table} WHERE user_id = %d ORDER BY id ASC LIMIT %d OFFSET %d", $user->ID, $limit, ( max( 1, (int) $page ) - 1 ) * $limit ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		$data = array();
		foreach ( $rows as $row ) {
			$data[] = array(
				'group_id'    => 'wps-wgm-transactions',
				'group_label' => __( 'Gift card transactions', 'woo-gift-cards-lite' ),
				'item_id'     => 'wps-wgm-transaction-' . $row->id,
				'data'        => array(
					array(
						'name'  => __( 'Gift card code', 'woo-gift-cards-lite' ),
						'value' => $row->coupon_code,
					),
					array(
						'name'  => __( 'Type', 'woo-gift-cards-lite' ),
						'value' => self::label( $row->type ),
					),
					array(
						'name'  => __( 'Amount', 'woo-gift-cards-lite' ),
						'value' => $row->amount_change . ' ' . $row->currency,
					),
					array(
						'name'  => __( 'Date', 'woo-gift-cards-lite' ),
						'value' => $row->created_at,
					),
				),
			);
		}

		return array(
			'data' => $data,
			'done' => count( $rows ) < $limit,
		);
	}

	/**
	 * Remove the user link from a user's transactions. The rows stay, so balances still add up.
	 *
	 * @param string $email_address Email.
	 * @param int    $page          Page.
	 * @return array
	 */
	public static function erase_personal_data( $email_address, $page = 1 ) {
		global $wpdb;

		$user    = get_user_by( 'email', $email_address );
		$removed = false;
		if ( $user && self::is_ready() ) {
			$removed = (bool) $wpdb->update( self::table_name(), array( 'user_id' => null ), array( 'user_id' => $user->ID ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		}

		return array(
			'items_removed'  => $removed,
			'items_retained' => false,
			'messages'       => array(),
			'done'           => true,
		);
	}
}

if ( ! function_exists( 'wps_wgm_update_coupon_amount_with_context' ) ) {
	/**
	 * Update a gift card balance with a transaction-log label. Behaves exactly like
	 * update_post_meta() on coupon_amount; the label only names the change in the log.
	 *
	 * @param int    $coupon_id Coupon ID.
	 * @param mixed  $amount    New balance.
	 * @param string $type      Transaction type.
	 * @param string $source    Source.
	 * @param array  $args      Optional order_id, note, meta.
	 * @return void
	 */
	function wps_wgm_update_coupon_amount_with_context( $coupon_id, $amount, $type, $source = '', $args = array() ) {
		$args['coupon_id'] = $coupon_id;
		if ( $source ) {
			$args['source'] = $source;
		}
		Wps_Wgm_Transactions::set_context( $type, $args );
		try {
			update_post_meta( $coupon_id, 'coupon_amount', $amount );
		} finally {
			Wps_Wgm_Transactions::clear_context();
		}
	}
}

if ( ! function_exists( 'wps_wgm_log_transaction' ) ) {
	/**
	 * Write a gift card transaction row. See Wps_Wgm_Transactions::log() for the arguments.
	 *
	 * @param array $args Row data.
	 * @return int|false Row ID.
	 */
	function wps_wgm_log_transaction( $args ) {
		return Wps_Wgm_Transactions::log( $args );
	}
}
