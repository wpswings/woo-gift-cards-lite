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

		$sql .= $wpdb->prepare( " ORDER BY p.ID DESC LIMIT %d OFFSET %d", $per_page, $offset );

		$results = $wpdb->get_results( $sql );

		$coupon_codes = array();
		if ( ! empty( $results ) ) {
			foreach ( $results as $row ) {
				$coupon_codes[] = strtolower( $row->post_title );
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
						$cache_key = 'wps_wgm_offline_giftcard_' . intval( $order_id );
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

		if ( ! empty( $coupon_ids ) ) {
			foreach ( $coupon_ids as $coupon_id ) {
				$content = get_post_field( 'post_content', $coupon_id );
				$is_giftcard = strpos( $content, 'GIFTCARD ORDER #' ) !== false
					|| ( strpos( $content, 'Imported Coupon' ) !== false
						&& get_post_meta( $coupon_id, 'wps_wgm_imported_coupon', true ) === 'purchased' );

				if ( $is_giftcard ) {
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
