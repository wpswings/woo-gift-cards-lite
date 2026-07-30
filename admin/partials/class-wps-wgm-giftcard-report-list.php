<?php
/**
 * Exit if accessed directly
 *
 * @package Ultimate Woocommerce Gift Cards
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
?>
	<?php 
	$wps_wgm_giftcard_report = new Wps_WGM_Giftcard_Report_List();
	$wps_total_balance       = $wps_wgm_giftcard_report->wps_uwgc_total_balance();
	?>
	<!-- Advanced Gift Card Report Dashboard -->
	<div class="wps-uwgc-advanced-dashboard">

		<!-- Enhanced Summary Metrics -->
		<div class="wps-uwgc-metrics-grid">
			<?php
			$wps_advanced_metrics = $wps_wgm_giftcard_report->wps_uwgc_get_advanced_metrics();

			if ( isset( $wps_advanced_metrics ) && ! empty( $wps_advanced_metrics ) && is_array( $wps_advanced_metrics ) ) :
				?>

				<!-- Total Gift Cards Issued -->
				<div class="wps-uwgc-metric-card wps-metric-primary">
					<div class="wps-metric-icon"><span class="dashicons dashicons-tickets-alt"></span></div>
					<div class="wps-metric-content">
						<h3><?php echo esc_html( $wps_advanced_metrics['total_issued']['count'] ); ?></h3>
						<p><?php esc_html_e( 'Total Gift Cards Issued', 'woo-gift-cards-lite' ); ?></p>
						<span class="wps-metric-trend <?php echo esc_attr( $wps_advanced_metrics['total_issued']['trend_class'] ); ?>">
							<?php echo esc_html( $wps_advanced_metrics['total_issued']['trend'] ); ?>
						</span>
					</div>
				</div>

				<!-- Outstanding Balance -->
				<div class="wps-uwgc-metric-card wps-metric-success">
					<div class="wps-metric-icon"><span class="dashicons dashicons-chart-area"></span></div>
					<div class="wps-metric-content">
						<h3><?php echo wp_kses_post( wc_price( $wps_advanced_metrics['outstanding_balance']['amount'] ) ); ?></h3>
						<p><?php esc_html_e( 'Outstanding Balance', 'woo-gift-cards-lite' ); ?></p>
						<span class="wps-metric-percentage"><?php echo esc_html( $wps_advanced_metrics['outstanding_balance']['percentage'] ); ?>% <?php esc_html_e( 'of total issued', 'woo-gift-cards-lite' ); ?></span>
					</div>
				</div>

				<!-- Redeemed Balance -->
				<div class="wps-uwgc-metric-card wps-metric-info">
					<div class="wps-metric-icon"><span class="dashicons dashicons-money-alt"></span></div>
					<div class="wps-metric-content">
						<h3><?php echo wp_kses_post( wc_price( $wps_advanced_metrics['redeemed_balance']['amount'] ) ); ?></h3>
						<p><?php esc_html_e( 'Redeemed Balance (Lifetime)', 'woo-gift-cards-lite' ); ?></p>
						<span class="wps-metric-percentage"><?php echo esc_html( $wps_advanced_metrics['redeemed_balance']['rate'] ); ?>% <?php esc_html_e( 'redemption rate', 'woo-gift-cards-lite' ); ?></span>
					</div>
				</div>

				<!-- Expired Balance -->
				<div class="wps-uwgc-metric-card wps-metric-warning">
					<div class="wps-metric-icon"><span class="dashicons dashicons-clock"></span></div>
					<div class="wps-metric-content">
						<h3><?php echo wp_kses_post( wc_price( $wps_advanced_metrics['expired_balance']['amount'] ) ); ?></h3>
						<p><?php esc_html_e( 'Expired Balance', 'woo-gift-cards-lite' ); ?></p>
						<span class="wps-metric-count"><?php echo esc_html( $wps_advanced_metrics['expired_balance']['count'] ); ?> <?php esc_html_e( 'cards expired', 'woo-gift-cards-lite' ); ?></span>
					</div>
				</div>

				<!-- Active Gift Cards -->
				<div class="wps-uwgc-metric-card wps-metric-success">
					<div class="wps-metric-icon"><span class="dashicons dashicons-yes-alt"></span></div>
					<div class="wps-metric-content">
						<h3><?php echo esc_html( $wps_advanced_metrics['active_cards']['count'] ); ?></h3>
						<p><?php esc_html_e( 'Active Gift Cards', 'woo-gift-cards-lite' ); ?></p>
						<span class="wps-metric-average"><?php esc_html_e( 'Avg:', 'woo-gift-cards-lite' ); ?> <?php echo wp_kses_post( wc_price( $wps_advanced_metrics['active_cards']['avg_balance'] ) ); ?></span>
					</div>
				</div>

				<!-- Partially Redeemed -->
				<div class="wps-uwgc-metric-card wps-metric-info">
					<div class="wps-metric-icon"><span class="dashicons dashicons-update"></span></div>
					<div class="wps-metric-content">
						<h3><?php echo esc_html( $wps_advanced_metrics['partially_redeemed']['count'] ); ?></h3>
						<p><?php esc_html_e( 'Partially Redeemed Cards', 'woo-gift-cards-lite' ); ?></p>
						<span class="wps-metric-value"><?php echo wp_kses_post( wc_price( $wps_advanced_metrics['partially_redeemed']['remaining_value'] ) ); ?> <?php esc_html_e( 'remaining', 'woo-gift-cards-lite' ); ?></span>
					</div>
				</div>

				<!-- Average Gift Card Value -->
				<div class="wps-uwgc-metric-card wps-metric-primary">
					<div class="wps-metric-icon"><span class="dashicons dashicons-calculator"></span></div>
					<div class="wps-metric-content">
						<h3><?php echo wp_kses_post( wc_price( $wps_advanced_metrics['average_value']['amount'] ) ); ?></h3>
						<p><?php esc_html_e( 'Average Gift Card Value', 'woo-gift-cards-lite' ); ?></p>
						<span class="wps-metric-range"><?php echo wp_kses_post( wc_price( $wps_advanced_metrics['average_value']['min'] ) ); ?> - <?php echo wp_kses_post( wc_price( $wps_advanced_metrics['average_value']['max'] ) ); ?></span>
					</div>
				</div>

				<!-- Redemption Rate -->
				<div class="wps-uwgc-metric-card wps-metric-success">
					<div class="wps-metric-icon"><span class="dashicons dashicons-chart-line"></span></div>
					<div class="wps-metric-content">
						<h3><?php echo esc_html( $wps_advanced_metrics['redemption_rate']['percentage'] ); ?>%</h3>
						<p><?php esc_html_e( 'Redemption Rate', 'woo-gift-cards-lite' ); ?></p>
						<div class="wps-metric-gauge">
							<div class="wps-gauge-fill" style="width: <?php echo esc_attr( $wps_advanced_metrics['redemption_rate']['percentage'] ); ?>%"></div>
						</div>
					</div>
				</div>

				<!-- Expiring Soon -->
				<div class="wps-uwgc-metric-card wps-metric-danger">
					<div class="wps-metric-icon"><span class="dashicons dashicons-warning"></span></div>
					<div class="wps-metric-content">
						<h3><?php echo esc_html( $wps_advanced_metrics['expiring_soon']['count'] ); ?></h3>
						<p><?php esc_html_e( 'Expiring Soon (30 days)', 'woo-gift-cards-lite' ); ?></p>
						<span class="wps-metric-value"><?php echo wp_kses_post( wc_price( $wps_advanced_metrics['expiring_soon']['value'] ) ); ?> <?php esc_html_e( 'at risk', 'woo-gift-cards-lite' ); ?></span>
					</div>
				</div>

				<!-- Breakage Revenue -->
				<div class="wps-uwgc-metric-card wps-metric-warning">
					<div class="wps-metric-icon"><span class="dashicons dashicons-analytics"></span></div>
					<div class="wps-metric-content">
						<h3><?php echo wp_kses_post( wc_price( $wps_advanced_metrics['breakage_revenue']['amount'] ) ); ?></h3>
						<p><?php esc_html_e( 'Breakage Revenue', 'woo-gift-cards-lite' ); ?></p>
						<span class="wps-metric-percentage"><?php echo esc_html( $wps_advanced_metrics['breakage_revenue']['percentage'] ); ?>% <?php esc_html_e( 'unredeemed', 'woo-gift-cards-lite' ); ?></span>
					</div>
				</div>

			<?php endif; ?>
		</div>

	</div>
<?php

/**
 * Giftcard Coupon Report
 *
 * @author     WP Swings <webmaster@wpswings.com>
 * @package    Ultimate Woocommerce Gift Cards
 * @version    2.2.1
 */
class Wps_WGM_Giftcard_Report_List extends WP_List_Table {
	/**
	 * Eample_data
	 *
	 * @var [type]
	 */
	public $example_data;

	/**
	 * This is variable which is used for the total count.
	 *
	 * @var array $wps_total_count variable for total count.
	 */
	public $wps_total_count;

	/**
	 * Get column value.
	 *
	 * @param mixed  $item item.
	 * @param string $column_name column.
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'status':
				$status_class = 'wps-status-' . sanitize_html_class( $item['status_class'] );
				$html = '<span class="wps-gc-status-badge ' . $status_class . '">' . esc_html( $item['status_label'] ) . '</span>';
				return $html;
			case 'giftcard_code':
				$html = '<div class="wps-gc-code-wrapper">';
				$html .= '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $item['coupon_id'] ) ) . '&action=edit' ) . '" class="wps-gc-code-link">' . esc_html( $item[ $column_name ] ) . '</a>';
				$html .= '<button type="button" class="button-link wps-copy-code" data-code="' . esc_attr( $item[ $column_name ] ) . '" title="' . esc_attr__( 'Copy code', 'woo-gift-cards-lite' ) . '"><span class="dashicons dashicons-clipboard"></span></button>';
				$html .= '</div>';
				return $html;
			case 'order_id':
				$html = '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $item['order_id'] ) ) . '&action=edit' ) . '">#' . absint( $item[ $column_name ] ) . '</a>';
				return $html;
			case 'initial_amount':
				return wp_kses_post( wc_price( $item[ $column_name ] ) );
			case 'coupon_amount':
				$amount_html = wp_kses_post( wc_price( $item[ $column_name ] ) );
				if ( isset( $item['initial_amount'] ) && $item['initial_amount'] > $item[ $column_name ] ) {
					$redeemed = $item['initial_amount'] - $item[ $column_name ];
					$amount_html .= '<br><small class="wps-redeemed-indicator">-' . wp_kses_post( wc_price( $redeemed ) ) . ' ' . esc_html__( 'used', 'woo-gift-cards-lite' ) . '</small>';
				}
				return $amount_html;
			case 'redemption_count':
				$count = absint( $item[ $column_name ] );
				$html = '<span class="wps-redemption-count">' . $count . '</span>';
				if ( $count > 0 ) {
					$html .= '<br><small>' . esc_html__( 'times', 'woo-gift-cards-lite' ) . '</small>';
				}
				return $html;
			case 'purchase_date':
				return esc_html( $item[ $column_name ] );
			case 'expiry_date':
				$html = esc_html( $item[ $column_name ] );
				if ( isset( $item['is_expiring_soon'] ) && $item['is_expiring_soon'] ) {
					$html .= '<br><span class="wps-expiring-warning"><span class="dashicons dashicons-warning"></span> ' . esc_html__( 'Expiring Soon', 'woo-gift-cards-lite' ) . '</span>';
				}
				return $html;
			case 'days_to_expiry':
				$days = $item[ $column_name ];
				if ( $days === 'No Expiry' ) {
					return '<span class="wps-no-expiry">' . esc_html__( 'No Expiry', 'woo-gift-cards-lite' ) . '</span>';
				} elseif ( $days < 0 ) {
					return '<span class="wps-expired">' . esc_html__( 'Expired', 'woo-gift-cards-lite' ) . '</span>';
				} elseif ( $days <= 30 ) {
					return '<span class="wps-expiring-soon">' . absint( $days ) . ' ' . esc_html__( 'days', 'woo-gift-cards-lite' ) . '</span>';
				} else {
					return '<span class="wps-active-expiry">' . absint( $days ) . ' ' . esc_html__( 'days', 'woo-gift-cards-lite' ) . '</span>';
				}
			case 'buyer_email':
				$html = '<a href="mailto:' . esc_attr( $item[ $column_name ] ) . '">' . esc_html( $item[ $column_name ] ) . '</a>';
				return $html;
			case 'source_type':
				$source = $item[ $column_name ];
				$source_icons = array(
					'Online' => 'cart',
					'Offline' => 'admin-tools',
					'Imported' => 'download',
					'Promotional' => 'megaphone',
				);
				$icon = isset( $source_icons[ $source ] ) ? $source_icons[ $source ] : 'tickets-alt';
				$html = '<span class="wps-source-badge"><span class="dashicons dashicons-' . esc_attr( $icon ) . '"></span> ' . esc_html( $source ) . '</span>';
				return $html;
			case 'action':
				$html = '<div class="wps-gc-actions">';
				$html .= '<button type="button" class="button-link wps_uwgc_gift_report_view wps-view-details-icon" data-coupon-id="' . absint( $item['coupon_id'] ) . '" data-order-id="' . absint( $item['order_id'] ) . '" title="' . esc_attr__( 'View Details', 'woo-gift-cards-lite' ) . '"><span class="dashicons dashicons-visibility"></span></button>';
				$html .= '<div class="wps-quick-actions">';
				$html .= '<button type="button" class="button-link wps-resend-email" data-coupon-id="' . absint( $item['coupon_id'] ) . '" title="' . esc_attr__( 'Resend Email', 'woo-gift-cards-lite' ) . '"><span class="dashicons dashicons-email"></span></button>';
				$html .= '</div>';
				$html .= '</div>';
				return $html;

			default:
				// Apply custom filter for other columns.
				$html = apply_filters( 'wps_wgm_add_analytics_coupons', false, $column_name, $item );
				return $html;
		}
	}

	/**
	 * Get list columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array(
			'cb'              => '<input type="checkbox" />',
			'status'          => __( 'Status', 'woo-gift-cards-lite' ),
			'giftcard_code'   => __( 'Gift Card Code', 'woo-gift-cards-lite' ),
			'order_id'        => __( 'Order Id', 'woo-gift-cards-lite' ),
			'initial_amount'  => __( 'Initial Amount', 'woo-gift-cards-lite' ),
			'coupon_amount'   => __( 'Current Balance', 'woo-gift-cards-lite' ),
			'redemption_count' => __( 'Uses', 'woo-gift-cards-lite' ),
			'purchase_date'   => __( 'Purchase Date', 'woo-gift-cards-lite' ),
			'expiry_date'     => __( 'Expiry Date', 'woo-gift-cards-lite' ),
			'days_to_expiry'  => __( 'Days to Expiry', 'woo-gift-cards-lite' ),
			'buyer_email'     => __( 'Buyer Email', 'woo-gift-cards-lite' ),
			'source_type'     => __( 'Source', 'woo-gift-cards-lite' ),
			'action'          => __( 'Action', 'woo-gift-cards-lite' ),
		);
		$columns = apply_filters( 'wps_wgm_add_analytics_coupons_column', $columns );
		return $columns;
	}

	/**
	 * Get a list of sortable columns.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'order_id'         => array( 'order_id', false ),
			'initial_amount'   => array( 'initial_amount', false ),
			'coupon_amount'    => array( 'coupon_amount', false ),
			'purchase_date'    => array( 'purchase_date', false ),
			'expiry_date'      => array( 'expiry_date', false ),
			'days_to_expiry'   => array( 'days_to_expiry', false ),
			'redemption_count' => array( 'redemption_count', false ),
		);
		return $sortable_columns;
	}

	/**
	 * Column cb.
	 *
	 * @param  array $item Key data.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="wps_coupon_ids[]" value="%s" />',
			$item['coupon_id']
		);
	}

	/**
	 * Process bulk actions.
	 */
	public function process_bulk_action() {
		$secure_nonce      = wp_create_nonce( 'wps-gc-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-auth-nonce' );
		if ( ! $id_nonce_verified ) {
				wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
		}
		if ( 'bulk-delete' === $this->current_action() ) {
			if ( isset( $_POST['wps_coupon_ids'] ) && ! empty( $_POST['wps_coupon_ids'] ) ) {
				$coupon_ids = map_deep( wp_unslash( $_POST['wps_coupon_ids'] ), 'sanitize_text_field' );
				global $wpdb;
				if ( isset( $coupon_ids ) && ! empty( $coupon_ids ) && is_array( $coupon_ids ) ) {
					foreach ( $coupon_ids as $key => $value ) {
						wp_delete_post( $value );
					}
				}
			}
			?>
			<div class="notice notice-success is-dismissible"> 
				<p><strong><?php esc_html_e( 'Gift Card Deleted', 'woo-gift-cards-lite' ); ?></strong></p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'woo-gift-cards-lite' ); ?></span>
				</button>
			</div>
			<?php
		}
	}

	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = array(
			'bulk-delete' => __( 'Delete', 'woo-gift-cards-lite' ),
		);
		return $actions;
	}


	/**
	 * Prepare table list items.
	 */
	public function prepare_items() {
		global $wpdb;
		$per_page = 10;
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->process_bulk_action();
		$current_page = $this->get_pagenum();
		$this->example_data = $this->wps_uwgc_giftcard_report_data();
		$data = $this->example_data;
		usort( $data, array( $this, 'wps_uwgc_usort_reorder_report' ) );
		$total_items = $this->wps_total_count;
		$this->items = $data;
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( $total_items / $per_page ),
			)
		);

	}

	/**
	 * Search box.
	 *
	 * @param  array $cloumna Column A.
	 * @param  array $cloumnb Column B.
	 */
	public function wps_uwgc_usort_reorder_report( $cloumna, $cloumnb ) {
		$secure_nonce      = wp_create_nonce( 'wps-gc-report-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-report-nonce' );
		if ( ! $id_nonce_verified ) {
			wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
		}
		$orderby = ( ! empty( $_REQUEST['orderby'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['orderby'] ) ) : 'order_id';
		$order = ( ! empty( $_REQUEST['order'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['order'] ) ) : 'dsc';
		$result = strcmp( $cloumna[ $orderby ], $cloumnb[ $orderby ] );
		return ( 'asc' === $order ) ? $result : -$result;
	}

	/**
	 * Extra box for date filter and Export Report.
	 *
	 * @param  array $which location.
	 */
	public function extra_tablenav( $which ) {
		if ( 'top' === $which ) {
        	do_action( 'wps_wgm_gc_report_extra_tablenav', $which );
		}
    }

	/**
	 * Function is used to show giftcard coupons.
	 */
	public function wps_uwgc_giftcard_report_data() {
		global $wpdb;
		$current_page = isset( $_GET['paged'] ) ? sanitize_text_field( wp_unslash( $_GET['paged'] ) ) : 1;
		$per_page     = 10;
		$offset       = ( $current_page - 1 ) * $per_page;

		$sql = "
		SELECT p.ID, p.post_title
		FROM {$wpdb->posts} p
		WHERE p.post_type = 'shop_coupon'
		AND p.post_status = 'publish'
		";

		if ( ! empty( $_POST['wps_gc_date_filter_1'] ) && ! empty( $_POST['wps_gc_date_filter_2'] ) ) {
			$nonce = isset( $_POST['wps_wgm_report_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_report_nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce ) ) {
				return false;
			}
			$gc_date_1 = sanitize_text_field( wp_unslash( $_POST['wps_gc_date_filter_1'] ) );
			$gc_date_2 = sanitize_text_field( wp_unslash( $_POST['wps_gc_date_filter_2'] ) );

			$sql .= $wpdb->prepare(
				" AND p.post_date BETWEEN %s AND %s",
				$gc_date_1 . ' 00:00:00',
				$gc_date_2 . ' 23:59:59'
			);
		}

		if ( ! empty( $_REQUEST['s'] ) ) {
			$search = '%' . $wpdb->esc_like( sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) ) . '%';
			$sql   .= $wpdb->prepare( " AND p.post_title LIKE %s", $search );
		}

		$sql .= "
			AND (
				p.post_content LIKE '%GIFTCARD ORDER #%'
				OR (
					p.post_content LIKE '%Imported Coupon%'
					AND EXISTS (
						SELECT 1 FROM {$wpdb->postmeta} pm
						WHERE pm.post_id = p.ID
						AND pm.meta_key = 'wps_wgm_imported_coupon'
						AND pm.meta_value = 'purchased'
					)
				)
			)
		";

		$sql .= ' ORDER BY p.ID DESC';

		$valid_offset = $offset;
		$raw_offset   = 0;
		$batch_size   = $per_page;
		$skipped      = 0;

		$offline_giftcard = get_option( 'wps_wgm_offline_giftcard', false );
		$wps_uwgc_data    = array();
		$wps_uwgc_data_count = 0;

		while ( $wps_uwgc_data_count < $per_page ) {
			$paged_sql = $sql . $wpdb->prepare( ' LIMIT %d OFFSET %d', $batch_size, $raw_offset );
			$results   = $wpdb->get_results( $paged_sql );

			if ( empty( $results ) ) {
				break;
			}

			$raw_offset += $batch_size;

			foreach ( $results as $row ) {
				$coupon_code = strtolower( $row->post_title );
				$coupon_obj  = new WC_Coupon( $coupon_code );
				$coupon_id   = $coupon_obj->get_id();

				if ( empty( $coupon_id ) ) {
					continue;
				}

				$order_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true );

				if ( empty( $order_id ) ) {
					continue;
				}

				$order       = wc_get_order( $order_id );
				$giftresults = array();

				if ( empty( $order ) && ! empty( $offline_giftcard ) ) {
					$cache_key   = 'wps_wgm_offline_giftcard_' . intval( $order_id );
					$giftresults = wp_cache_get( $cache_key, 'wps_wgm' );

					if ( false === $giftresults ) {
						$giftresults = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT * FROM {$wpdb->prefix}offline_giftcard WHERE `id` = %d",
								intval( $order_id )
							),
							ARRAY_A
						);

						wp_cache_set( $cache_key, $giftresults, 'wps_wgm', HOUR_IN_SECONDS );
					}
				}

				if ( empty( $order ) && empty( $giftresults ) ) {
					continue;
				}

				if ( $skipped < $valid_offset ) {
					$skipped++;
					continue;
				}

				if ( ! empty( $order ) ) {
					$user_email = $order->get_billing_email();
				} elseif ( isset( $giftresults[0] ) ) {
					$user_email = $giftresults[0]['from'];
				} else {
					$user_email = '';
				}

				// Get coupon details
				$coupon_amount = (float) get_post_meta( $coupon_id, 'coupon_amount', true );
				$initial_amount = (float) get_post_meta( $coupon_id, 'wps_wgm_initial_amount', true );

				// Use current amount as initial if not stored
				if ( empty( $initial_amount ) ) {
					$initial_amount = $coupon_amount;
				}

				$usage_count = $coupon_obj->get_usage_count();
				$expiry_date_obj = $coupon_obj->get_date_expires();
				$expiry_date = isset( $expiry_date_obj ) ? gmdate( 'F j, Y', strtotime( '-1 day', strtotime( $expiry_date_obj ) ) ) : esc_html__( 'No Expiry', 'woo-gift-cards-lite' );

				// Calculate days to expiry
				$days_to_expiry = 'No Expiry';
				$is_expiring_soon = false;
				if ( $expiry_date_obj ) {
					$expiry_timestamp = $expiry_date_obj->getTimestamp();
					$current_time = current_time( 'timestamp' );
					$days_diff = floor( ( $expiry_timestamp - $current_time ) / DAY_IN_SECONDS );
					$days_to_expiry = $days_diff;

					if ( $days_diff > 0 && $days_diff <= 30 ) {
						$is_expiring_soon = true;
					}
				}

				// Determine status
				$is_valid = $this->wps_uwgc_validate_expiry( $coupon_obj );
				if ( ! $is_valid ) {
					$status_label = __( 'Expired', 'woo-gift-cards-lite' );
					$status_class = 'expired';
				} elseif ( $usage_count > 0 && $coupon_amount > 0 ) {
					$status_label = __( 'Partially Used', 'woo-gift-cards-lite' );
					$status_class = 'partial';
				} elseif ( $usage_count > 0 && $coupon_amount == 0 ) {
					$status_label = __( 'Fully Redeemed', 'woo-gift-cards-lite' );
					$status_class = 'redeemed';
				} else {
					$status_label = __( 'Active', 'woo-gift-cards-lite' );
					$status_class = 'active';
				}

				// Determine source type
				$post_content = get_post_field( 'post_content', $coupon_id );
				if ( strpos( $post_content, 'Imported Coupon' ) !== false ) {
					$source_type = __( 'Imported', 'woo-gift-cards-lite' );
				} elseif ( ! empty( $giftresults ) ) {
					$source_type = __( 'Offline', 'woo-gift-cards-lite' );
				} else {
					$source_type = __( 'Online', 'woo-gift-cards-lite' );
				}

				// Purchase date
				$purchase_date = get_the_date( 'F j, Y', $coupon_id );

				$wps_uwgc_data[] = array(
					'coupon_id'        => $coupon_id,
					'giftcard_code'    => $coupon_code,
					'order_id'         => $order_id,
					'initial_amount'   => $initial_amount,
					'coupon_amount'    => $coupon_amount,
					'redemption_count' => $usage_count,
					'purchase_date'    => $purchase_date,
					'expiry_date'      => $expiry_date,
					'days_to_expiry'   => $days_to_expiry,
					'is_expiring_soon' => $is_expiring_soon,
					'buyer_email'      => $user_email,
					'source_type'      => $source_type,
					'status_label'     => $status_label,
					'status_class'     => $status_class,
				);
				$wps_uwgc_data_count++;

				if ( count( $wps_uwgc_data ) >= $per_page ) {
					break 2;
				}
			}
		}

		// Count total number of matching gift card coupon codes.
		$args2 = array(
			'posts_per_page' => -1,
			'post_type'      => 'shop_coupon',
			'post_status'    => 'publish',
			'fields'         => 'ids',
		);

		if ( isset( $_POST['wps_gc_date_filter_1'], $_POST['wps_gc_date_filter_2'] ) ) {
			$nonce = isset( $_POST['wps_wgm_report_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_report_nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce ) ) {
				return false;
			}
			$gc_date_1 = sanitize_text_field( wp_unslash( $_POST['wps_gc_date_filter_1'] ) );
			$gc_date_2 = sanitize_text_field( wp_unslash( $_POST['wps_gc_date_filter_2'] ) );

			$args2['date_query'] = array(
				array(
					'after'     => $gc_date_1,
					'before'    => $gc_date_2,
					'inclusive' => true,
				),
			);
		}

		if ( ! empty( $_REQUEST['s'] ) ) {
			$search_coupon = sanitize_text_field( wp_unslash( $_REQUEST['s'] ) );
			$args2['s'] = $search_coupon;
		}

		$coupon_ids = get_posts( $args2 );

		$total_count = 0;

		$offline_giftcard = get_option( 'wps_wgm_offline_giftcard', false );

		if ( ! empty( $coupon_ids ) ) {
			foreach ( $coupon_ids as $coupon_id ) {
				$content = get_post_field( 'post_content', $coupon_id );
				$is_giftcard = strpos( $content, 'GIFTCARD ORDER #' ) !== false
					|| ( strpos( $content, 'Imported Coupon' ) !== false
						&& get_post_meta( $coupon_id, 'wps_wgm_imported_coupon', true ) === 'purchased' );

				if ( ! $is_giftcard ) {
					continue;
				}

				$order_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true );
				if ( empty( $order_id ) ) {
					continue;
				}

				$order_exists = false;
				$order = wc_get_order( $order_id );
				if ( ! empty( $order ) ) {
					$order_exists = true;
				} elseif ( $offline_giftcard ) {
					global $wpdb;
					$cache_key = 'wps_wgm_offline_giftcard_' . intval( $order_id );
					$giftresults = wp_cache_get( $cache_key, 'wps_wgm' );

					if ( false === $giftresults ) {
						$giftresults = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT id FROM {$wpdb->prefix}offline_giftcard WHERE `id` = %d",
								intval( $order_id )
							)
						);

						wp_cache_set( $cache_key, $giftresults, 'wps_wgm', HOUR_IN_SECONDS );
					}

					if ( ! empty( $giftresults ) ) {
						$order_exists = true;
					}
				}

				if ( $order_exists ) {
					$total_count++;
				}
			}
		}

		$this->wps_total_count = $total_count;

		return $wps_uwgc_data;
	}

	/**
	 * This function is used to get total balance.
	 */
	public function wps_uwgc_total_balance() {
		global $wpdb;
	
		$cache_key = 'wps_uwgc_total_balance';
		$cached    = wp_cache_get( $cache_key, 'wps_wgm' );
	
		if ( false !== $cached ) {
			return $cached;
		}
	
		$total_balance   = 0;
		$expire_giftcard = 0;
		$current_time    = current_time( 'timestamp' );
	
		$coupons = $wpdb->get_results( "
			SELECT ID, post_content
			FROM {$wpdb->posts}
			WHERE post_type = 'shop_coupon'
			  AND post_status = 'publish'
		", ARRAY_A );
	
		if ( ! empty( $coupons ) ) {
			foreach ( $coupons as $coupon ) {
				$coupon_id   = (int) $coupon['ID'];
				$content     = $coupon['post_content'];
				$is_giftcard = false;
	
				if ( strpos( $content, 'GIFTCARD ORDER #' ) !== false ) {
					$is_giftcard = true;
				} elseif (
					strpos( $content, 'Imported Coupon' ) !== false &&
					get_post_meta( $coupon_id, 'wps_wgm_imported_coupon', true ) === 'purchased'
				) {
					$is_giftcard = true;
				}
	
				if ( ! $is_giftcard ) {
					continue;
				}
	
				$coupon_obj   = new WC_Coupon( $coupon_id );
				$usage_limit  = (int) $coupon_obj->get_usage_limit();
				$usage_count  = (int) $coupon_obj->get_usage_count();
				$coupon_value = (float) get_post_meta( $coupon_id, 'coupon_amount', true );
	
				$is_valid = $this->wps_uwgc_validate_expiry( $coupon_obj );
				if ( $is_valid && ( 0 === $usage_limit || $usage_count < $usage_limit ) ) {
					$total_balance += $coupon_value;
					continue;
				}
	
				$expiry_timestamp = (int) get_post_meta( $coupon_id, 'date_expires', true );
				if ( $expiry_timestamp > 0 && $expiry_timestamp < $current_time ) {
					$expire_giftcard += $coupon_value;
				}
			}
		}
	
		$result = array(
			'total_balance'   => $total_balance,
			'expire_giftcard' => $expire_giftcard,
		);

		wp_cache_set( $cache_key, $result, 'wps_wgm', HOUR_IN_SECONDS );
	
		return $result;
	}

	/**
	 * Get advanced metrics for gift card dashboard
	 *
	 * @return array Advanced metrics data
	 */
	public function wps_uwgc_get_advanced_metrics() {
		global $wpdb;

		$cache_key = 'wps_uwgc_advanced_metrics';
		$cached    = wp_cache_get( $cache_key, 'wps_wgm' );

		if ( false !== $cached ) {
			return $cached;
		}

		// Initialize metrics
		$total_issued = 0;
		$total_value = 0;
		$outstanding_balance = 0;
		$redeemed_balance = 0;
		$expired_balance = 0;
		$expired_count = 0;
		$active_count = 0;
		$active_total_balance = 0;
		$partially_redeemed_count = 0;
		$partially_redeemed_value = 0;
		$used_cards_count = 0;
		$min_value = 999999;
		$max_value = 0;
		$expiring_soon_count = 0;
		$expiring_soon_value = 0;
		$current_time = current_time( 'timestamp' );
		$thirty_days_future = $current_time + (30 * DAY_IN_SECONDS);

		// Get all gift card coupons
		$coupons = $wpdb->get_results( "
			SELECT ID, post_content, post_date
			FROM {$wpdb->posts}
			WHERE post_type = 'shop_coupon'
			  AND post_status = 'publish'
		", ARRAY_A );

		if ( ! empty( $coupons ) ) {
			foreach ( $coupons as $coupon ) {
				$coupon_id   = (int) $coupon['ID'];
				$content     = $coupon['post_content'];
				$is_giftcard = false;

				// Check if it's a gift card
				if ( strpos( $content, 'GIFTCARD ORDER #' ) !== false ) {
					$is_giftcard = true;
				} elseif (
					strpos( $content, 'Imported Coupon' ) !== false &&
					get_post_meta( $coupon_id, 'wps_wgm_imported_coupon', true ) === 'purchased'
				) {
					$is_giftcard = true;
				}

				if ( ! $is_giftcard ) {
					continue;
				}

				$total_issued++;
				$coupon_obj   = new WC_Coupon( $coupon_id );
				$usage_limit  = (int) $coupon_obj->get_usage_limit();
				$usage_count  = (int) $coupon_obj->get_usage_count();
				$coupon_value = (float) get_post_meta( $coupon_id, 'coupon_amount', true );
				$initial_amount = (float) get_post_meta( $coupon_id, 'wps_wgm_initial_amount', true );

				// Use current value as initial if not stored
				if ( empty( $initial_amount ) ) {
					$initial_amount = $coupon_value;
				}

				$total_value += $initial_amount;

				// Min/Max tracking
				if ( $initial_amount > 0 ) {
					if ( $initial_amount < $min_value ) {
						$min_value = $initial_amount;
					}
					if ( $initial_amount > $max_value ) {
						$max_value = $initial_amount;
					}
				}

				$is_valid = $this->wps_uwgc_validate_expiry( $coupon_obj );
				$expiry_timestamp = (int) get_post_meta( $coupon_id, 'date_expires', true );

				// Expiring soon check
				if ( $is_valid && $expiry_timestamp > 0 && $expiry_timestamp <= $thirty_days_future && $coupon_value > 0 ) {
					$expiring_soon_count++;
					$expiring_soon_value += $coupon_value;
				}

				// Active cards
				if ( $is_valid && ( 0 === $usage_limit || $usage_count < $usage_limit ) && $coupon_value > 0 ) {
					$outstanding_balance += $coupon_value;
					$active_count++;
					$active_total_balance += $coupon_value;
				}

				// Expired cards
				if ( ! $is_valid && $expiry_timestamp > 0 && $expiry_timestamp < $current_time ) {
					$expired_balance += $coupon_value;
					$expired_count++;
				}

				// Partially redeemed cards
				if ( $usage_count > 0 && $coupon_value > 0 && ( 0 === $usage_limit || $usage_count < $usage_limit ) ) {
					$partially_redeemed_count++;
					$partially_redeemed_value += $coupon_value;
				}

				// Used cards count
				if ( $usage_count > 0 ) {
					$used_cards_count++;
				}

				// Calculate redeemed amount
				if ( $usage_count > 0 ) {
					$redeemed_balance += ( $initial_amount - $coupon_value );
				}
			}
		}

		// Calculate percentages and averages
		$avg_value = $total_issued > 0 ? ( $total_value / $total_issued ) : 0;
		$redemption_rate = $total_issued > 0 ? round( ( $used_cards_count / $total_issued ) * 100, 2 ) : 0;
		$avg_active_balance = $active_count > 0 ? ( $active_total_balance / $active_count ) : 0;
		$outstanding_percentage = $total_value > 0 ? round( ( $outstanding_balance / $total_value ) * 100, 2 ) : 0;
		$redeemed_rate = $total_value > 0 ? round( ( $redeemed_balance / $total_value ) * 100, 2 ) : 0;
		$breakage_percentage = $total_value > 0 ? round( ( $expired_balance / $total_value ) * 100, 2 ) : 0;

		// Get trend (comparing to previous period - simplified for now)
		$trend = '+0';
		$trend_class = 'wps-trend-neutral';

		// Fix min_value if no cards found
		if ( $min_value == 999999 ) {
			$min_value = 0;
		}

		$metrics = array(
			'total_issued' => array(
				'count' => $total_issued,
				'trend' => $trend,
				'trend_class' => $trend_class,
			),
			'outstanding_balance' => array(
				'amount' => $outstanding_balance,
				'percentage' => $outstanding_percentage,
			),
			'redeemed_balance' => array(
				'amount' => $redeemed_balance,
				'rate' => $redeemed_rate,
			),
			'expired_balance' => array(
				'amount' => $expired_balance,
				'count' => $expired_count,
			),
			'active_cards' => array(
				'count' => $active_count,
				'avg_balance' => $avg_active_balance,
			),
			'partially_redeemed' => array(
				'count' => $partially_redeemed_count,
				'remaining_value' => $partially_redeemed_value,
			),
			'average_value' => array(
				'amount' => $avg_value,
				'min' => $min_value,
				'max' => $max_value,
			),
			'redemption_rate' => array(
				'percentage' => $redemption_rate,
			),
			'expiring_soon' => array(
				'count' => $expiring_soon_count,
				'value' => $expiring_soon_value,
			),
			'breakage_revenue' => array(
				'amount' => $expired_balance,
				'percentage' => $breakage_percentage,
			),
		);

		wp_cache_set( $cache_key, $metrics, 'wps_wgm', HOUR_IN_SECONDS );

		return $metrics;
	}

	/**
	 * Function is used to check expiry date of coupon.
	 *
	 * @param array $coupon_obj Object of coupon.
	 */
	public function wps_uwgc_validate_expiry( $coupon_obj ) {

		if ( $coupon_obj->get_date_expires() && time() > $coupon_obj->get_date_expires()->getTimestamp() ) {
			return false;
		} else {
			return true;
		}
	}
}

$secure_nonce      = wp_create_nonce( 'wps-gc-report-nonce' );
$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-report-nonce' );
if ( ! $id_nonce_verified ) {
	wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
}
?>
<form method="post">
	<input type="hidden" name="page" value="<?php echo esc_attr( isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '' ); ?>">
	<?php
	$wps_report_list = new Wps_WGM_Giftcard_Report_List();
	$wps_report_list->prepare_items();
	$wps_report_list->search_box( __( 'Search Gift Cards', 'woo-gift-cards-lite' ), 'giftcard_code' );
	$wps_report_list->display();

	?>
</form>
<?php
