<?php
/**
 * Failed Gift Card Operations Tracker
 *
 * This class handles logging and tracking of all failed gift card operations
 * including creation failures, email delivery failures, redemption failures, etc.
 *
 * @package    woo-gift-cards-lite
 * @author     WP Swings <webmaster@wpswings.com>
 * @link       https://www.wpswings.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPS_Gift_Card_Failure_Tracker' ) ) {

	/**
	 * Class for tracking failed gift card operations.
	 */
	class WPS_Gift_Card_Failure_Tracker {

		/**
		 * The single instance of the class.
		 *
		 * @var WPS_Gift_Card_Failure_Tracker
		 */
		private static $instance = null;

		/**
		 * Get the singleton instance.
		 *
		 * @return WPS_Gift_Card_Failure_Tracker
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor.
		 */
		private function __construct() {
			// Private constructor to enforce singleton.
		}

		/**
		 * Log a failed gift card operation.
		 *
		 * @param array $args {
		 *     Array of failure data.
		 *
		 *     @type string $failure_type      Type of failure (creation, email, redemption, payment, import, system).
		 *     @type string $severity          Severity level (critical, high, medium, low).
		 *     @type int    $order_id          Order ID (optional).
		 *     @type int    $coupon_id         Coupon ID (optional).
		 *     @type string $coupon_code       Coupon code (optional).
		 *     @type string $customer_email    Customer email (optional).
		 *     @type string $customer_name     Customer name (optional).
		 *     @type string $error_message     Error message.
		 *     @type string $error_code        Error code (optional).
		 *     @type string $stack_trace       Stack trace (optional).
		 *     @type array  $context           Additional context data (optional).
		 * }
		 * @return int|false The failure ID on success, false on error.
		 */
		public function log_failure( $args ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'wps_gift_card_failures';

			// Prepare default values.
			$defaults = array(
				'failure_type'       => 'system',
				'severity'           => 'medium',
				'status'             => 'new',
				'order_id'           => null,
				'coupon_id'          => null,
				'coupon_code'        => null,
				'customer_email'     => null,
				'customer_name'      => null,
				'error_message'      => '',
				'error_code'         => null,
				'stack_trace'        => null,
				'context'            => null,
				'retry_count'        => 0,
				'last_retry_timestamp' => null,
				'max_retries'        => 3,
				'assigned_to'        => null,
				'resolution_notes'   => null,
				'resolved_timestamp' => null,
				'created_by'         => get_current_user_id(),
			);

			$data = wp_parse_args( $args, $defaults );

			// Convert context array to JSON.
			if ( is_array( $data['context'] ) ) {
				$data['context'] = wp_json_encode( $data['context'] );
			}

			// Insert into database.
			$result = $wpdb->insert(
				$table_name,
				array(
					'failure_type'         => sanitize_text_field( $data['failure_type'] ),
					'severity'             => sanitize_text_field( $data['severity'] ),
					'status'               => sanitize_text_field( $data['status'] ),
					'order_id'             => absint( $data['order_id'] ),
					'coupon_id'            => absint( $data['coupon_id'] ),
					'coupon_code'          => sanitize_text_field( $data['coupon_code'] ),
					'customer_email'       => sanitize_email( $data['customer_email'] ),
					'customer_name'        => sanitize_text_field( $data['customer_name'] ),
					'error_message'        => sanitize_textarea_field( $data['error_message'] ),
					'error_code'           => sanitize_text_field( $data['error_code'] ),
					'stack_trace'          => $data['stack_trace'],
					'context'              => $data['context'],
					'retry_count'          => absint( $data['retry_count'] ),
					'last_retry_timestamp' => $data['last_retry_timestamp'],
					'max_retries'          => absint( $data['max_retries'] ),
					'assigned_to'          => absint( $data['assigned_to'] ),
					'resolution_notes'     => sanitize_textarea_field( $data['resolution_notes'] ),
					'resolved_timestamp'   => $data['resolved_timestamp'],
					'created_by'           => absint( $data['created_by'] ),
				),
				array(
					'%s', '%s', '%s', '%d', '%d', '%s', '%s', '%s',
					'%s', '%s', '%s', '%s', '%d', '%s', '%d', '%d',
					'%s', '%s', '%d',
				)
			);

			if ( false === $result ) {
				error_log( 'WPS Gift Card: Failed to log failure - ' . $wpdb->last_error );
				return false;
			}

			$failure_id = $wpdb->insert_id;

			// Trigger action hook for notifications.
			do_action( 'wps_wgm_failure_logged', $failure_id, $data );

			// Send alerts for critical and high severity failures.
			if ( in_array( $data['severity'], array( 'critical', 'high' ), true ) ) {
				$this->send_admin_alert( $failure_id, $data );
			}

			return $failure_id;
		}

		/**
		 * Get failure by ID.
		 *
		 * @param int $failure_id Failure ID.
		 * @return object|null Failure data or null if not found.
		 */
		public function get_failure( $failure_id ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'wps_gift_card_failures';

			$failure = $wpdb->get_row(
				$wpdb->prepare(
					"SELECT * FROM " . esc_sql($table_name) . " WHERE id = %d",
					$failure_id
				)
			);

			if ( $failure && ! empty( $failure->context ) ) {
				$failure->context = json_decode( $failure->context, true );
			}

			return $failure;
		}

		/**
		 * Update failure status.
		 *
		 * @param int    $failure_id Failure ID.
		 * @param string $status New status.
		 * @param string $notes Resolution notes (optional).
		 * @return bool True on success, false on failure.
		 */
		public function update_status( $failure_id, $status, $notes = '' ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'wps_gift_card_failures';

			$data = array(
				'status' => sanitize_text_field( $status ),
			);

			if ( ! empty( $notes ) ) {
				$data['resolution_notes'] = sanitize_textarea_field( $notes );
			}

			if ( 'resolved' === $status ) {
				$data['resolved_timestamp'] = current_time( 'mysql' );
			}

			$result = $wpdb->update(
				$table_name,
				$data,
				array( 'id' => absint( $failure_id ) ),
				array( '%s', '%s', '%s' ),
				array( '%d' )
			);

			return false !== $result;
		}

		/**
		 * Increment retry count.
		 *
		 * @param int $failure_id Failure ID.
		 * @return bool True on success, false on failure.
		 */
		public function increment_retry_count( $failure_id ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'wps_gift_card_failures';

			$result = $wpdb->query(
				$wpdb->prepare(
					"UPDATE " . esc_sql($table_name) . "
					SET retry_count = retry_count + 1,
					    last_retry_timestamp = %s
					WHERE id = %d",
					current_time( 'mysql' ),
					$failure_id
				)
			);

			return false !== $result;
		}

		/**
		 * Check if failure can be retried.
		 *
		 * @param int $failure_id Failure ID.
		 * @return bool True if can retry, false otherwise.
		 */
		public function can_retry( $failure_id ) {
			$failure = $this->get_failure( $failure_id );

			if ( ! $failure ) {
				return false;
			}

			return $failure->retry_count < $failure->max_retries;
		}

		/**
		 * Get failures by status.
		 *
		 * @param string $status Status to filter by.
		 * @param int    $limit Number of results to return (default: 100).
		 * @return array Array of failure objects.
		 */
		public function get_failures_by_status( $status, $limit = 100 ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'wps_gift_card_failures';

			$failures = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM " . esc_sql($table_name) . "
					WHERE status = %s
					ORDER BY failure_timestamp DESC
					LIMIT %d",
					$status,
					$limit
				)
			);

			return $failures;
		}

		/**
		 * Get failures by type.
		 *
		 * @param string $type Failure type.
		 * @param int    $limit Number of results to return (default: 100).
		 * @return array Array of failure objects.
		 */
		public function get_failures_by_type( $type, $limit = 100 ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'wps_gift_card_failures';

			$failures = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT * FROM " . esc_sql($table_name) . "
					WHERE failure_type = %s
					ORDER BY failure_timestamp DESC
					LIMIT %d",
					$type,
					$limit
				)
			);

			return $failures;
		}

		/**
		 * Get failure statistics.
		 *
		 * @param string $period Period for stats (24h, 7d, 30d, all).
		 * @return array Array of statistics.
		 */
		public function get_failure_stats( $period = '24h' ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'wps_gift_card_failures';

			// Determine date filter.
			$date_filter = '';
			switch ( $period ) {
				case '24h':
					$date_filter = "AND failure_timestamp >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
					break;
				case '7d':
					$date_filter = "AND failure_timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
					break;
				case '30d':
					$date_filter = "AND failure_timestamp >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
					break;
				case 'all':
				default:
					$date_filter = '';
					break;
			}

			// Get total failures.
			$total = $wpdb->get_var(
				"SELECT COUNT(*) FROM " . esc_sql($table_name) . " WHERE 1=1 " . $date_filter
			);

			// Get failures by type.
			$by_type = $wpdb->get_results(
				"SELECT failure_type, COUNT(*) as count
				FROM " . esc_sql($table_name) . "
				WHERE 1=1 " . $date_filter . "
				GROUP BY failure_type"
			);

			// Get failures by severity.
			$by_severity = $wpdb->get_results(
				"SELECT severity, COUNT(*) as count
				FROM " . esc_sql($table_name) . "
				WHERE 1=1 " . $date_filter . "
				GROUP BY severity"
			);

			// Get failures by status.
			$by_status = $wpdb->get_results(
				"SELECT status, COUNT(*) as count
				FROM " . esc_sql($table_name) . "
				WHERE 1=1 " . $date_filter . "
				GROUP BY status"
			);

			// Get critical failures.
			$critical_count = $wpdb->get_var(
				"SELECT COUNT(*) FROM " . esc_sql($table_name) . "
				WHERE severity = 'critical'
				AND status != 'resolved' " . $date_filter
			);

			// Get pending recoveries.
			$pending_recoveries = $wpdb->get_var(
				"SELECT COUNT(*) FROM " . esc_sql($table_name) . "
				WHERE status IN ('new', 'in_progress')
				AND retry_count < max_retries " . $date_filter
			);

			return array(
				'total'              => intval( $total ),
				'by_type'            => $by_type,
				'by_severity'        => $by_severity,
				'by_status'          => $by_status,
				'critical_count'     => intval( $critical_count ),
				'pending_recoveries' => intval( $pending_recoveries ),
			);
		}

		/**
		 * Send admin alert for critical failures.
		 *
		 * @param int   $failure_id Failure ID.
		 * @param array $data Failure data.
		 * @return void
		 */
		private function send_admin_alert( $failure_id, $data ) {
			// Check if alerts are enabled.
			$alerts_enabled = get_option( 'wps_wgm_enable_failure_alerts', 'yes' );
			if ( 'yes' !== $alerts_enabled ) {
				return;
			}

			$admin_email = get_option( 'admin_email' );
			$subject = sprintf(
				'[%s] Gift Card Operation Failed - %s Severity',
				get_bloginfo( 'name' ),
				ucfirst( $data['severity'] )
			);

			$message = sprintf(
				"A gift card operation has failed with %s severity.\n\n" .
				"Failure Type: %s\n" .
				"Error Message: %s\n" .
				"Order ID: %s\n" .
				"Customer Email: %s\n" .
				"Timestamp: %s\n\n" .
				"View details in admin panel:\n%s",
				strtoupper( $data['severity'] ),
				$data['failure_type'],
				$data['error_message'],
				$data['order_id'] ? $data['order_id'] : 'N/A',
				$data['customer_email'] ? $data['customer_email'] : 'N/A',
				current_time( 'mysql' ),
				admin_url( 'admin.php?page=wc-reports&tab=giftcard_report&section=failures&failure_id=' . $failure_id )
			);

			wp_mail( $admin_email, $subject, $message );

			// Allow plugins to send custom notifications.
			do_action( 'wps_wgm_failure_alert_sent', $failure_id, $data, $admin_email );
		}

		/**
		 * Auto-retry failed operations.
		 *
		 * @param int $failure_id Failure ID.
		 * @return bool True on successful retry, false otherwise.
		 */
		public function auto_retry( $failure_id ) {
			$failure = $this->get_failure( $failure_id );

			if ( ! $failure || ! $this->can_retry( $failure_id ) ) {
				return false;
			}

			// Increment retry count.
			$this->increment_retry_count( $failure_id );

			// Attempt recovery based on failure type.
			$recovered = false;
			switch ( $failure->failure_type ) {
				case 'creation':
					$recovered = $this->retry_coupon_creation( $failure );
					break;
				case 'email':
					$recovered = $this->retry_email_delivery( $failure );
					break;
				case 'redemption':
					// Log for analysis, don't auto-retry redemption failures.
					break;
				case 'payment':
					// Manual intervention required.
					break;
			}

			if ( $recovered ) {
				$this->update_status( $failure_id, 'resolved', 'Auto-recovered successfully' );
			}

			return $recovered;
		}

		/**
		 * Retry coupon creation.
		 *
		 * @param object $failure Failure object.
		 * @return bool True on success, false on failure.
		 */
		private function retry_coupon_creation( $failure ) {
			// This would integrate with your coupon creation logic.
			// For now, return false and require manual intervention.
			return apply_filters( 'wps_wgm_retry_coupon_creation', false, $failure );
		}

		/**
		 * Retry email delivery.
		 *
		 * @param object $failure Failure object.
		 * @return bool True on success, false on failure.
		 */
		private function retry_email_delivery( $failure ) {
			// This would integrate with your email delivery logic.
			// For now, return false and require manual intervention.
			return apply_filters( 'wps_wgm_retry_email_delivery', false, $failure );
		}

		/**
		 * Clean up old resolved failures.
		 *
		 * @param int $days Number of days to keep (default: 90).
		 * @return int Number of records deleted.
		 */
		public function cleanup_old_failures( $days = 90 ) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'wps_gift_card_failures';

			$deleted = $wpdb->query(
				$wpdb->prepare(
					"DELETE FROM " . esc_sql($table_name) . "
					WHERE status = 'resolved'
					AND resolved_timestamp < DATE_SUB(NOW(), INTERVAL %d DAY)",
					$days
				)
			);

			return $deleted;
		}
	}
}
