<?php
/**
 * Gift card expiry reminder emails.
 *
 * Moved here from the Giftware (pro) plugin. Setting keys, the cron hook and the
 * "reminder sent" coupon meta keep their original names, so saved settings and
 * scheduled events carry over unchanged.
 *
 * @link       https://wpswings.com/
 * @since      3.2.13
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sends a reminder to the gift card recipient a set number of days before the card expires.
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/includes
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Wps_Wgm_Expiry_Reminder {

	/**
	 * Daily cron event. Same name Giftware used, so existing schedules keep running.
	 */
	const CRON_HOOK = 'wps_daily_giftcard_reminder_event';

	/**
	 * Coupon meta recording that the reminder went out.
	 */
	const SENT_META = '_giftcard_reminder_sent';

	/**
	 * Key of the settings section inside the mail template settings array.
	 */
	const SETTINGS_KEY = 'coupon_expiry_notification_mail_setting';

	/**
	 * The common function object.
	 *
	 * @var Woocommerce_Gift_Cards_Common_Function
	 */
	public $wps_common_fun;

	/**
	 * Whether an older Giftware release already supplied the settings section.
	 *
	 * @var bool
	 */
	private $settings_from_pro = false;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->wps_common_fun = new Woocommerce_Gift_Cards_Common_Function();
	}

	/**
	 * Schedule the daily event. Runs on every request so plugin updates, which do not
	 * fire the activation hook, still get the schedule.
	 *
	 * @return void
	 */
	public function wps_wgm_schedule_event() {
		if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
			wp_schedule_event( time(), 'daily', self::CRON_HOOK );
		}
	}

	/**
	 * Remove the daily event.
	 *
	 * @return void
	 */
	public static function wps_wgm_clear_event() {
		wp_clear_scheduled_hook( self::CRON_HOOK );
	}

	/**
	 * Whether an older Giftware release that still sends its own reminders is active.
	 * In that case Giftware keeps handling them, to avoid two different senders.
	 *
	 * @return bool
	 */
	private function is_handled_by_pro() {
		global $wp_filter;
		if ( empty( $wp_filter[ self::CRON_HOOK ] ) ) {
			return false;
		}
		foreach ( $wp_filter[ self::CRON_HOOK ]->callbacks as $callbacks ) {
			foreach ( $callbacks as $callback ) {
				if ( is_array( $callback['function'] ) && 'wps_check_and_send_giftcard_reminders' === $callback['function'][1] ) {
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Default reminder email body.
	 *
	 * @return string
	 */
	public static function wps_wgm_default_body() {
		return '<table style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;" border="0" width="100%" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td align="center">
				<table style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 0 5px rgba(0,0,0,0.1);" border="0" width="600" cellspacing="0" cellpadding="20">
					<tbody>
						<tr>
							<td style="font-size: 16px; color: #333333;">
								<p style="font-size: 18px; font-family: sans-serif;">Hello,</p>
								<p style="font-size: 18px; font-family: sans-serif;">This is a friendly reminder from <strong>[SITENAME]</strong> – your gift card is about to expire!</p>

								<table style="width: 100%; background-color: #f2f2f2; border-radius: 5px; margin: 20px 0;" border="0" cellspacing="0" cellpadding="10">
									<tbody>
										<tr>
											<td><strong>Gift Card Code:</strong></td>
											<td><span style="font-family: monospace;">[COUPONCODE]</span></td>
										</tr>
										<tr>
											<td><strong>Remaining Value:</strong></td>
											<td>[COUPONAMOUNT]</td>
										</tr>
										<tr>
											<td><strong>Expires On:</strong></td>
											<td>[EXPIRYDATE]</td>
										</tr>
									</tbody>
								</table>
								Please redeem it before the expiry date. Don’t miss your chance to use this value on your next order!
								<p style="text-align: center; margin: 30px 0;">
									<a style="display: inline-block; padding: 12px 25px; background-color: #0073aa; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold;" href="[SITEURL]">
										Use Your Gift Card Now
									</a>
								</p>
								<hr style="border: none; border-top: 1px solid #dddddd; margin: 40px 0;" />
								<p style="font-size: 14px; color: #666666;">[DISCLAIMER]</p>
								Thank you,
								The <strong>[SITENAME]</strong> Team
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>';
	}

	/**
	 * Add the reminder fields to the email settings.
	 *
	 * @param array $settings Mail template settings.
	 * @return array
	 */
	public function wps_wgm_add_settings( $settings ) {
		if ( isset( $settings[ self::SETTINGS_KEY ] ) ) {
			// An older Giftware release added the section already and also renders it.
			$this->settings_from_pro = true;
			return $settings;
		}

		$mail_settings = wps_wgm_get_plugin_option( 'wps_wgm_mail_settings' );
		$saved_body    = $this->wps_common_fun->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_reminder_mail_body' );

		$settings[ self::SETTINGS_KEY ] = array(
			array(
				'title'    => esc_html__( 'Enable Reminder Send Before Coupon Expiry', 'woo-gift-cards-lite' ),
				'id'       => 'wps_wgm_reminder_send_before_coupon_expiry',
				'type'     => 'checkbox',
				'class'    => 'input-text',
				'desc_tip' => esc_html__( 'Check this if you want to email the gift card recipient before the gift card expires.', 'woo-gift-cards-lite' ),
				'desc'     => esc_html__( 'Enable Reminder Send Before Coupon Expiry', 'woo-gift-cards-lite' ),
			),
			array(
				'title'            => esc_html__( 'Reminder Send Days Before Coupon Expiry', 'woo-gift-cards-lite' ),
				'id'               => 'wps_wgm_reminder_day_before_coupon_expiry',
				'type'             => 'number',
				'custom_attribute' => array( 'min' => '1' ),
				'class'            => 'input-text wps_wgm_new_woo_ver_style_text',
				'desc_tip'         => esc_html__( 'Enter the number of days before coupon expiry to send reminder email.', 'woo-gift-cards-lite' ),
			),
			array(
				'title'       => esc_html__( 'Reminder Email Subject', 'woo-gift-cards-lite' ),
				'id'          => 'wps_wgm_reminder_mail_subject',
				'type'        => 'textWithDesc',
				'class'       => 'description wps_ml-35',
				'desc_tip'    => esc_html__( 'Subject for reminder emails.', 'woo-gift-cards-lite' ),
				'bottom_desc' => esc_html__( 'Use [SITENAME] shortcode as the name of the site to be placed dynamically', 'woo-gift-cards-lite' ),
			),
			array(
				'title'           => esc_html__( 'Reminder Email Body', 'woo-gift-cards-lite' ),
				'id'              => 'wps_wgm_reminder_mail_body',
				'type'            => 'wp_editor',
				'content'         => '' !== $saved_body ? $saved_body : self::wps_wgm_default_body(),
				'additional_info' => esc_html__( 'Use [SITEURL] as url of the site, [SITENAME] shortcode as the name of the site. Use [COUPONAMOUNT] shortcode as coupon amount to be placed dynamically. [COUPONCODE] Shortcode is for display the Coupon Code. [EXPIRYDATE] shows the expiry date. Here the [DISCLAIMER] shortcode would be replaced by above Disclaimer text field', 'woo-gift-cards-lite' ),
				'desc_tip'        => esc_html__( 'Write the Email Content to notify the user about their coupon expiry.', 'woo-gift-cards-lite' ),
			),
		);
		return $settings;
	}

	/**
	 * Render the reminder settings section on the email settings tab.
	 *
	 * @param array $settings      Mail template settings.
	 * @param array $mail_settings Saved mail settings.
	 * @return void
	 */
	public function wps_wgm_render_settings( $settings, $mail_settings ) {
		if ( $this->settings_from_pro || empty( $settings[ self::SETTINGS_KEY ] ) ) {
			return;
		}
		$settings_obj = new Woocommerce_Giftcard_Admin_Settings();
		?>
		<h3 id="wps_wgm_coupon_expiry_notification_mail_setting" class="wps_wgm_mail_setting_tab" aria-expanded="false" data-target="#wps_wgm_coupon_expiry_notification_mail_setting_wrapper" role="button" tabindex="0" onclick="var target=jQuery('#wps_wgm_coupon_expiry_notification_mail_setting_wrapper');var expanded=this.getAttribute('aria-expanded')==='true';jQuery(this).toggleClass('is-open',!expanded).attr('aria-expanded',!expanded?'true':'false');target.stop(true,true).slideToggle();" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();this.click();}">
			<span class="wps_wgm_mail_setting_tab_label"><?php esc_html_e( 'Coupon Expiry Notification Mail Settings', 'woo-gift-cards-lite' ); ?></span>
			<span class="wps_wgm_mail_setting_toggle" aria-hidden="true"></span>
		</h3>
		<div id="wps_wgm_coupon_expiry_notification_mail_setting_wrapper" class="wps_wgm_table_wrapper">
			<table class="form-table wps_wgm_general_setting">
				<tbody>
					<?php $settings_obj->wps_wgm_generate_common_settings( $settings[ self::SETTINGS_KEY ], $mail_settings ); ?>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Whether a coupon is a gift card that should get a reminder.
	 *
	 * Matches the rule Giftware used: online and offline gift card orders, plus imported
	 * coupons only when they were sold as gift cards.
	 *
	 * @param WP_Post $coupon Coupon post.
	 * @return bool
	 */
	private function is_reminder_giftcard( $coupon ) {
		if ( false !== strpos( $coupon->post_content, 'GIFTCARD ORDER #' ) ) {
			return true;
		}
		return false !== strpos( $coupon->post_content, 'Imported Coupon' )
			&& 'purchased' === get_post_meta( $coupon->ID, 'wps_wgm_imported_coupon', true );
	}

	/**
	 * Cron callback: email recipients whose gift card expires within the reminder window.
	 *
	 * @return void
	 */
	public function wps_wgm_send_reminders() {
		if ( ! wps_wgm_giftcard_enable() || $this->is_handled_by_pro() ) {
			return;
		}

		$mail_settings = get_option( 'wps_wgm_mail_settings', array() );
		if ( 'on' !== $this->wps_common_fun->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_reminder_send_before_coupon_expiry' ) ) {
			return;
		}
		$reminder_days = absint( $this->wps_common_fun->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_reminder_day_before_coupon_expiry' ) );
		if ( ! $reminder_days ) {
			return;
		}

		// Cover everything from now to the end of the target day, not just the target day,
		// so a skipped cron run or a newly enabled setting does not miss cards.
		$window_end = new DateTime( "+$reminder_days days", wp_timezone() );
		$window_end->setTime( 23, 59, 59 );

		$coupons = get_posts(
			array(
				'post_type'      => 'shop_coupon',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'     => 'date_expires',
						'value'   => array( time(), $window_end->getTimestamp() ),
						'compare' => 'BETWEEN',
						'type'    => 'NUMERIC',
					),
				),
			)
		);

		$subject_template = $this->wps_common_fun->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_reminder_mail_subject' );
		$body_template    = $this->wps_common_fun->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_reminder_mail_body' );
		$disclaimer       = $this->wps_common_fun->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_disclaimer' );
		if ( '' === $subject_template ) {
			$subject_template = __( "Don't Miss Out – Your [SITENAME] Gift Card Is About to Expire", 'woo-gift-cards-lite' );
		}
		if ( '' === $body_template ) {
			$body_template = self::wps_wgm_default_body();
		}
		$site_name = get_bloginfo( 'name' );

		foreach ( $coupons as $coupon ) {
			$coupon_id = $coupon->ID;
			$expires   = (int) get_post_meta( $coupon_id, 'date_expires', true );
			$sent      = get_post_meta( $coupon_id, self::SENT_META, true );

			// The meta stores the expiry it was sent for, so an extended card is reminded again.
			// "1" is the flag older Giftware releases stored.
			if ( '1' === (string) $sent || (int) $sent === $expires ) {
				continue;
			}
			if ( ! $this->is_reminder_giftcard( $coupon ) || 'no' === get_post_meta( $coupon_id, '_wps_giftcard_enabled', true ) ) {
				continue;
			}

			$balance   = (float) get_post_meta( $coupon_id, 'coupon_amount', true );
			$recipient = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_mail_to', true );
			if ( $balance <= 0 || ! is_email( $recipient ) ) {
				continue;
			}

			$replacements = array(
				'[SITENAME]'     => $site_name,
				'[SITEURL]'      => home_url(),
				'[COUPONCODE]'   => $coupon->post_title,
				'[COUPONAMOUNT]' => wc_price( $balance ),
				'[EXPIRYDATE]'   => date_i18n( get_option( 'date_format' ), $expires ),
				'[DISCLAIMER]'   => $disclaimer,
			);
			$message = str_replace( array_keys( $replacements ), array_values( $replacements ), $body_template );
			$subject = str_replace( '[SITENAME]', $site_name, $subject_template );

			$message = apply_filters( 'wps_wgm_expiry_reminder_message', $message, $coupon_id );
			$subject = apply_filters( 'wps_wgm_expiry_reminder_subject', $subject, $coupon_id );

			if ( wc_mail( $recipient, $subject, $message ) !== false ) {
				update_post_meta( $coupon_id, self::SENT_META, $expires );
			}
		}
	}
}
