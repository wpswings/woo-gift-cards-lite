<?php
/**
 * Admin screens for the gift card transaction log.
 *
 * Adds Gift Cards > Transactions (filterable list + CSV export) and a History box
 * on the gift card coupon edit screen.
 *
 * @link       https://wpswings.com/
 * @since      3.3.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Transaction log admin screens.
 */
class Wps_Wgm_Transactions_Admin {

	/**
	 * Admin page slug.
	 */
	const PAGE = 'wps-wgm-transactions';

	/**
	 * admin-post action and nonce for the CSV export.
	 */
	const EXPORT_ACTION = 'wps_wgm_export_transactions';

	/**
	 * Capability needed to see the log.
	 */
	const CAPABILITY = 'manage_woocommerce';

	/**
	 * Register the hooks.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ), 20 );
		add_action( 'admin_post_' . self::EXPORT_ACTION, array( __CLASS__, 'export_csv' ) );
		add_action( 'add_meta_boxes_shop_coupon', array( __CLASS__, 'add_meta_box' ) );
	}

	/**
	 * Gift Cards > Transactions.
	 *
	 * @return void
	 */
	public static function add_menu() {
		add_submenu_page(
			'edit.php?post_type=giftcard',
			__( 'Gift Card Transactions', 'woo-gift-cards-lite' ),
			__( 'Transactions', 'woo-gift-cards-lite' ),
			self::CAPABILITY,
			self::PAGE,
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * Filters from the request.
	 *
	 * @param array $source Request data.
	 * @return array
	 */
	public static function get_filters( $source ) {
		$filters = array();
		foreach ( array( 'code', 'type', 'source', 'date_from', 'date_to' ) as $key ) {
			if ( isset( $source[ $key ] ) && '' !== $source[ $key ] ) {
				$filters[ $key ] = sanitize_text_field( wp_unslash( $source[ $key ] ) );
			}
		}
		if ( ! empty( $source['coupon_id'] ) ) {
			$filters['coupon_id'] = absint( $source['coupon_id'] );
		}
		if ( ! empty( $source['order_id'] ) ) {
			$filters['order_id'] = absint( $source['order_id'] );
		}
		if ( isset( $filters['type'] ) && ! array_key_exists( $filters['type'], Wps_Wgm_Transactions::get_types() ) ) {
			unset( $filters['type'] );
		}
		if ( isset( $filters['source'] ) && ! array_key_exists( $filters['source'], Wps_Wgm_Transactions::get_sources() ) ) {
			unset( $filters['source'] );
		}
		return $filters;
	}

	/**
	 * Render Gift Cards > Transactions.
	 *
	 * @return void
	 */
	public static function render_page() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( esc_html__( 'You do not have permission to view this page.', 'woo-gift-cards-lite' ) );
		}

		require_once WPS_WGC_DIRPATH . 'admin/partials/class-wps-wgm-transactions-list.php';

		// Read-only filters on a GET screen; no state changes here.
		$filters = self::get_filters( $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$table   = new Wps_Wgm_Transactions_List( $filters );
		$table->prepare_items();

		$export_url = wp_nonce_url(
			add_query_arg( array_merge( array( 'action' => self::EXPORT_ACTION ), $filters ), admin_url( 'admin-post.php' ) ),
			self::EXPORT_ACTION
		);
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Gift Card Transactions', 'woo-gift-cards-lite' ); ?></h1>
			<a href="<?php echo esc_url( $export_url ); ?>" class="page-title-action"><?php esc_html_e( 'Export CSV', 'woo-gift-cards-lite' ); ?></a>
			<hr class="wp-header-end">
			<p><?php esc_html_e( 'Every change to a gift card balance: issue, redemption, restore, admin edit, recharge and expiry.', 'woo-gift-cards-lite' ); ?></p>
			<form method="get" action="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>">
				<input type="hidden" name="post_type" value="giftcard" />
				<input type="hidden" name="page" value="<?php echo esc_attr( self::PAGE ); ?>" />
				<?php $table->display(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Stream the filtered rows as CSV.
	 *
	 * @return void
	 */
	public static function export_csv() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( esc_html__( 'You do not have permission to export gift card transactions.', 'woo-gift-cards-lite' ), 403 );
		}
		check_admin_referer( self::EXPORT_ACTION );

		$filters = self::get_filters( $_GET );

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=gift-card-transactions-' . gmdate( 'Y-m-d' ) . '.csv' );

		$out = fopen( 'php://output', 'w' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fopen
		fputcsv(
			$out,
			array(
				'ID',
				__( 'Date', 'woo-gift-cards-lite' ),
				__( 'Gift card code', 'woo-gift-cards-lite' ),
				__( 'Type', 'woo-gift-cards-lite' ),
				__( 'Amount', 'woo-gift-cards-lite' ),
				__( 'Balance before', 'woo-gift-cards-lite' ),
				__( 'Balance after', 'woo-gift-cards-lite' ),
				__( 'Currency', 'woo-gift-cards-lite' ),
				__( 'Order', 'woo-gift-cards-lite' ),
				__( 'User', 'woo-gift-cards-lite' ),
				__( 'Source', 'woo-gift-cards-lite' ),
				__( 'Note', 'woo-gift-cards-lite' ),
			)
		);

		$page = 1;
		do {
			$rows = Wps_Wgm_Transactions::query(
				array_merge(
					$filters,
					array(
						'per_page' => 500,
						'page'     => $page,
						'order'    => 'ASC',
					)
				)
			);
			foreach ( $rows as $row ) {
				$date = Wps_Wgm_Transactions::row_date( $row );
				$user = $row->user_id ? get_userdata( $row->user_id ) : false;
				fputcsv(
					$out,
					array(
						$row->id,
						$date ? $date->date_i18n( 'Y-m-d H:i:s' ) : $row->created_at,
						self::csv_safe( $row->coupon_code ),
						self::csv_safe( Wps_Wgm_Transactions::label( $row->type ) ),
						wc_format_decimal( $row->amount_change, wc_get_price_decimals() ),
						wc_format_decimal( $row->balance_before, wc_get_price_decimals() ),
						wc_format_decimal( $row->balance_after, wc_get_price_decimals() ),
						$row->currency,
						$row->order_id ? $row->order_id : '',
						$user ? self::csv_safe( $user->user_login ) : '',
						self::csv_safe( Wps_Wgm_Transactions::label( $row->source, 'source' ) ),
						self::csv_safe( (string) $row->note ),
					)
				);
			}
			++$page;
		} while ( count( $rows ) === 500 );

		fclose( $out ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_fclose
		exit;
	}

	/**
	 * Stop spreadsheet apps from running a cell as a formula.
	 *
	 * @param string $value Cell value.
	 * @return string
	 */
	private static function csv_safe( $value ) {
		$value = (string) $value;
		if ( '' !== $value && in_array( $value[0], array( '=', '+', '-', '@', "\t", "\r" ), true ) ) {
			return "'" . $value;
		}
		return $value;
	}

	/**
	 * History box on gift card coupons.
	 *
	 * @param WP_Post $post Coupon post.
	 * @return void
	 */
	public static function add_meta_box( $post ) {
		if ( ! current_user_can( self::CAPABILITY ) || ! $post || ! Wps_Wgm_Transactions::is_gift_card( $post->ID ) ) {
			return;
		}
		add_meta_box(
			'wps-wgm-transaction-history',
			__( 'Gift Card History', 'woo-gift-cards-lite' ),
			array( __CLASS__, 'render_meta_box' ),
			'shop_coupon',
			'normal',
			'low'
		);
	}

	/**
	 * Render the last 20 rows of a card.
	 *
	 * @param WP_Post $post Coupon post.
	 * @return void
	 */
	public static function render_meta_box( $post ) {
		$rows = Wps_Wgm_Transactions::query(
			array(
				'coupon_id' => $post->ID,
				'per_page'  => 20,
			)
		);
		if ( empty( $rows ) ) {
			echo '<p>' . esc_html__( 'No balance changes recorded yet.', 'woo-gift-cards-lite' ) . '</p>';
			return;
		}
		?>
		<table class="widefat striped">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Date', 'woo-gift-cards-lite' ); ?></th>
					<th><?php esc_html_e( 'Type', 'woo-gift-cards-lite' ); ?></th>
					<th><?php esc_html_e( 'Amount', 'woo-gift-cards-lite' ); ?></th>
					<th><?php esc_html_e( 'Balance', 'woo-gift-cards-lite' ); ?></th>
					<th><?php esc_html_e( 'Order', 'woo-gift-cards-lite' ); ?></th>
					<th><?php esc_html_e( 'By', 'woo-gift-cards-lite' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $rows as $row ) : ?>
					<tr>
						<td><?php echo esc_html( self::format_date( $row ) ); ?></td>
						<td><?php echo esc_html( Wps_Wgm_Transactions::label( $row->type ) ); ?></td>
						<td><?php echo wp_kses_post( self::format_change( $row ) ); ?></td>
						<td><?php echo wp_kses_post( wc_price( $row->balance_after, array( 'currency' => $row->currency ) ) ); ?></td>
						<td><?php echo wp_kses_post( self::order_link( $row->order_id ) ); ?></td>
						<td><?php echo esc_html( self::user_name( $row->user_id, $row->source ) ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<p>
			<a href="<?php echo esc_url( self::page_url( array( 'coupon_id' => $post->ID ) ) ); ?>"><?php esc_html_e( 'View full history', 'woo-gift-cards-lite' ); ?></a>
		</p>
		<?php
	}

	/**
	 * URL of the transactions page.
	 *
	 * @param array $args Query args.
	 * @return string
	 */
	public static function page_url( $args = array() ) {
		return add_query_arg(
			array_merge(
				array(
					'post_type' => 'giftcard',
					'page'      => self::PAGE,
				),
				$args
			),
			admin_url( 'edit.php' )
		);
	}

	/**
	 * Row date for display.
	 *
	 * @param object $row Row.
	 * @return string
	 */
	public static function format_date( $row ) {
		$date = Wps_Wgm_Transactions::row_date( $row );
		return $date ? $date->date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) ) : $row->created_at;
	}

	/**
	 * Signed amount for display.
	 *
	 * @param object $row Row.
	 * @return string HTML.
	 */
	public static function format_change( $row ) {
		$amount = (float) $row->amount_change;
		$price  = wc_price( abs( $amount ), array( 'currency' => $row->currency ) );
		if ( $amount > 0 ) {
			return '<span style="color:#008a20;">+' . $price . '</span>';
		}
		if ( $amount < 0 ) {
			return '<span style="color:#b32d2e;">&minus;' . $price . '</span>';
		}
		return $price;
	}

	/**
	 * Link to an order's edit screen (works with HPOS and legacy storage).
	 *
	 * @param int $order_id Order ID.
	 * @return string HTML.
	 */
	public static function order_link( $order_id ) {
		if ( ! $order_id ) {
			return '&ndash;';
		}
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			return '#' . absint( $order_id );
		}
		return '<a href="' . esc_url( $order->get_edit_order_url() ) . '">#' . esc_html( $order->get_order_number() ) . '</a>';
	}

	/**
	 * Who made the change.
	 *
	 * @param int    $user_id User ID.
	 * @param string $source  Source key.
	 * @return string
	 */
	public static function user_name( $user_id, $source ) {
		if ( $user_id ) {
			$user = get_userdata( $user_id );
			if ( $user ) {
				return $user->display_name;
			}
		}
		return in_array( $source, array( 'cron', 'migration' ), true ) ? __( 'System', 'woo-gift-cards-lite' ) : __( 'Guest', 'woo-gift-cards-lite' );
	}
}
