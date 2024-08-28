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
	<div class="wps-uwgc-balance-summary">
		<table>
			<tr>
				<th><?php esc_html_e( 'Outstanding Balance', 'woo-gift-cards-lite' ); ?></th>
				<th><?php esc_html_e( 'Expired Balance', 'woo-gift-cards-lite' ); ?></th>
			</tr>
			<tr>
				<?php
				if ( isset( $wps_total_balance ) && ! empty( $wps_total_balance ) && is_array( $wps_total_balance ) ) {
					foreach ( $wps_total_balance as $key => $value ) {
						?>
						<td><?php echo wp_kses_post( wc_price( $value ) ); ?></td>
						<?php
					}
				}
				?>
			</tr>
		</table>
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
	 * Get column value.
	 *
	 * @param mixed  $item item.
	 * @param string $column_name column.
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'giftcard_code':
				$html = '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $item['coupon_id'] ) ) . '&action=edit' ) . '">' . $item[ $column_name ] . '</a>';
				return $html;
			case 'order_id':
				$html = '<a href="' . esc_url( admin_url( 'post.php?post=' . absint( $item['order_id'] ) ) . '&action=edit' ) . '">' . $item[ $column_name ] . '</a>';
				return $html;
			case 'coupon_amount':
				return $item[ $column_name ];
			case 'expiry_date':
				return $item[ $column_name ];
			case 'buyer_email':
				$html = '<a href="mailto:' . $item[ $column_name ] . '">' . $item[ $column_name ] . '</a>';
				return $html;
			case 'action':
				$text = __( 'View Details', 'woo-gift-cards-lite' );
				$html = '<input type="button" value="' . $text . '" data-coupon-id="' . $item['coupon_id'] . '" data-order-id="' . $item['order_id'] . '" class="button wps_uwgc_gift_report_view">';
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
			'cb'      => '<input type="checkbox" />',
			'giftcard_code' => __( 'Gift Card Code', 'woo-gift-cards-lite' ),
			'order_id'  => __( 'Order Id', 'woo-gift-cards-lite' ),
			'coupon_amount' => __( 'Balance', 'woo-gift-cards-lite' ),
			'expiry_date'     => __( 'Expiry Date', 'woo-gift-cards-lite' ),
			'buyer_email'   => __( 'Buyer Email', 'woo-gift-cards-lite' ),
			'action' => __( 'Action', 'woo-gift-cards-lite' ),
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
			'order_id'    => array( 'order_id', false ),
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
		$this->example_data = $this->wps_uwgc_giftcard_report_data();
		$data = $this->example_data;
		usort( $data, array( $this, 'wps_uwgc_usort_reorder_report' ) );
		$current_page = $this->get_pagenum();
		$total_items = count( $data );
		$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );
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

		$args = array(
			'posts_per_page'   => -1,
			'post_type'        => 'shop_coupon',
			'post_status'      => 'publish',
		);

		if ( isset( $_POST['wps_gc_date_filter_1'] ) && isset( $_POST['wps_gc_date_filter_2'] ) ) {
			$nonce = ( isset( $_POST['wps_wgm_report_nonce'] ) ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_report_nonce'] ) ) : '';
			if ( ! wp_verify_nonce( $nonce ) ) {
				return false;
			}
			$gc_date_1 = sanitize_text_field( wp_unslash( $_POST['wps_gc_date_filter_1'] ) );
			$gc_date_2 = sanitize_text_field( wp_unslash( $_POST['wps_gc_date_filter_2'] ) );

			$args['date_query'] = array(
				array(
					'after'     => $gc_date_1,
					'before'    => $gc_date_2,
					'inclusive' => true,
				),
			);
		}

		$coupons = get_posts( $args );

		$coupon_codes = array();
		if ( isset( $coupons ) && is_array( $coupons ) && ! empty( $coupons ) ) {
			foreach ( $coupons as $coupon ) {
				$couponcontent = $coupon->post_content;
				if ( strpos( $couponcontent, 'GIFTCARD ORDER #' ) !== false || ( strpos( $couponcontent, 'Imported Coupon' ) !== false && 'purchased' === get_post_meta( $coupon->ID, 'wps_wgm_imported_coupon', true ) ) ) {
					$coupon_name = strtolower( $coupon->post_title );
					array_push( $coupon_codes, $coupon_name );
				}
			}
		}

		$wps_uwgc_data = array();

		if ( is_array( $coupon_codes ) && ! empty( $coupon_codes ) && count( $coupon_codes ) ) {

			foreach ( $coupon_codes as $key => $value ) {
				$coupon_obj = new WC_Coupon( $value );
				$order_id = get_post_meta( $coupon_obj->get_id(), 'wps_wgm_giftcard_coupon', true );
				if ( isset( $order_id ) && ! empty( $order_id ) ) {
					$order = wc_get_order( $order_id );

					global $wpdb;

					$offline_giftcard = get_option( 'wps_wgm_offline_giftcard', false );

					if ( isset( $offline_giftcard ) && ! empty( $offline_giftcard ) ) {
						$giftresults = $wpdb->get_results( 
							$wpdb->prepare(
							"SELECT * FROM {$wpdb->prefix}offline_giftcard WHERE `id` = %d",
							$order_id
							),
							ARRAY_A
						);
					}

					if ( ! empty( $order ) ) {
						$user_email = $order->get_billing_email();
						$coupon_amount = get_post_meta( $coupon_obj->get_id(), 'coupon_amount', true );
						$expiry_date = $coupon_obj->get_date_expires();
						$expiry_date = 	isset( $expiry_date ) ? gmdate('F j, Y', strtotime('-1 day', strtotime($expiry_date))): esc_html__( 'No Expiry', 'woo-gift-cards-lite' );
						$wps_uwgc_data[] = array(
							'coupon_id' => $coupon_obj->get_id(),
							'giftcard_code' => $value,
							'order_id'  => $order_id,
							'coupon_amount' => $coupon_amount,
							'expiry_date' => $expiry_date,
							'buyer_email' => $user_email,
						);
					} else if ( isset( $giftresults[0] ) ) {
						$giftresult = $giftresults[0];
						$user_email = $giftresult['from'];
						$coupon_amount = get_post_meta( $coupon_obj->get_id(), 'coupon_amount', true );
						$expiry_date = $coupon_obj->get_date_expires();
						$expiry_date = 	isset( $expiry_date ) ? gmdate('F j, Y', strtotime('-1 day', strtotime($expiry_date))): esc_html__( 'No Expiry', 'woo-gift-cards-lite' );
						$wps_uwgc_data[] = array(
							'coupon_id' => $coupon_obj->get_id(),
							'giftcard_code' => $value,
							'order_id'  => $order_id,
							'coupon_amount' => $coupon_amount,
							'expiry_date' => $expiry_date,
							'buyer_email' => $user_email,
						);
					}
				}
			}

			$wps_uwgc_data = $this->wps_uwgc_search_option( $wps_uwgc_data );
		}
		return $wps_uwgc_data;
	}

	/**
	 * Function is used to search gift cards.
	 *
	 * @param array $wps_uwgc_data Array of data.
	 */
	public function wps_uwgc_search_option( $wps_uwgc_data ) {
		$wps_uwgc_search_arr = array();
		if ( isset( $_REQUEST['s'] ) && ! empty( $_REQUEST['s'] ) ) {
			$search_coupon = strtolower( sanitize_text_field( wp_unslash( $_REQUEST['s'] ) ) );

			if ( isset( $wps_uwgc_data ) && ! empty( $wps_uwgc_data ) && is_array( $wps_uwgc_data ) ) {
				foreach ( $wps_uwgc_data as $key => $value ) {
					if ( in_array( $search_coupon, $value ) ) {
						array_push( $wps_uwgc_search_arr, $value );
					}
				}
			}
			return $wps_uwgc_search_arr;
		} else {
			return $wps_uwgc_data;
		}
	}

	/**
	 * This function is used to get total balance.
	 */
	public function wps_uwgc_total_balance() {
		$total_balance = 0;
		$expire_giftcard = 0;
		$args = array(
			'posts_per_page'   => -1,
			'post_type'        => 'shop_coupon',
			'post_status'      => 'publish',
		);
		$coupons = get_posts( $args );
		if ( isset( $coupons ) && is_array( $coupons ) && ! empty( $coupons ) ) {
			foreach ( $coupons as $coupon ) {
				$couponcontent = $coupon->post_content;
				if ( strpos( $couponcontent, 'GIFTCARD ORDER #' ) !== false || ( strpos( $couponcontent, 'Imported Coupon' ) !== false && 'purchased' === get_post_meta( $coupon->ID, 'wps_wgm_imported_coupon', true ) ) ) {

					$coupon_id = $coupon->ID;
					$coupon_obj = new WC_Coupon( $coupon_id );

					if ( $coupon_obj->get_usage_limit() == 0 && $this->wps_uwgc_validate_expiry( $coupon_obj ) ) {
						$coupon_amount = get_post_meta( $coupon_obj->get_id(), 'coupon_amount', true );
						$total_balance = $total_balance + $coupon_amount;

					} else if ( $coupon_obj->get_usage_limit() > 0 && $coupon_obj->get_usage_limit() > $coupon_obj->get_usage_count() && $this->wps_uwgc_validate_expiry( $coupon_obj ) ) {
						$coupon_amount = get_post_meta( $coupon_obj->get_id(), 'coupon_amount', true );
						$total_balance = $total_balance + $coupon_amount;
					} else {
							$order_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true );
						if ( isset( $order_id ) && ! empty( $order_id ) ) {
							$coupon_amount = get_post_meta( $coupon_id, 'coupon_amount', true );
							$expiry_date = get_post_meta( $coupon_id, 'date_expires', true );

							if ( isset( $expiry_date ) && ! empty( $expiry_date ) ) {

								$now_date = current_time( 'timestamp' );
								$diff = $expiry_date - $now_date;
								if ( $diff < 0 ) {
									$expire_giftcard = $expire_giftcard + $coupon_amount;
								}
							}
						}
					}
				}
			}
		}

		$wps_common_array = array(
			'total_balance' => $total_balance,
			'expire_giftcard' => $expire_giftcard,
		);
		return $wps_common_array;
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
