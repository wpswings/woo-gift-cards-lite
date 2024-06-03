<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( class_exists( 'Makewebbetter_Onboarding_Helper' ) ) {

	$this->onboard = new Makewebbetter_Onboarding_Helper();

}
/*  create the settings tabs*/
$wps_wgm_setting_tab = array(
	'overview_setting' => array(
		'title' => esc_html__( 'OverView', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-overview-setting.php',
	),
	'general_setting' => array(
		'title' => esc_html__( 'General', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-general-setting.php',
	),
	'product_setting' => array(
		'title' => esc_html__( 'Product', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-product-setting.php',
	),
	'email_setting' => array(
		'title' => esc_html__( 'Email Template', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-email-template-setting.php',
	),
	'delivery_method' => array(
		'title' => esc_html__( 'Delivery Method', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-delivery-setting.php',
	),
	'other_setting' => array(
		'title' => esc_html__( 'Other Settings', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-other-setting.php',
	),
);
if ( ! wps_uwgc_pro_active() ) {
	$wps_wgm_setting_tab = array(
		'overview_setting' => array(
			'title' => esc_html__( 'OverView', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-overview-setting.php',
		),
		'general_setting' => array(
			'title' => esc_html__( 'General', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-general-setting.php',
		),
		'product_setting' => array(
			'title' => esc_html__( 'Product', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-product-setting.php',
		),
		'email_setting' => array(
			'title' => esc_html__( 'Email Template', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-email-template-setting.php',
		),
		'delivery_method' => array(
			'title' => esc_html__( 'Delivery Method', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-delivery-setting.php',
		),
		'other_setting' => array(
			'title' => esc_html__( 'Other Settings', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-other-setting.php',
		),
		'offline_setting' => array(
			'title' => esc_html__( 'Offline Giftcards', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-offline-setting.php',
		),
		'import_export_setting' => array(
			'title' => esc_html__( 'Import/ Export', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-im-export-setting.php',
		),
		'group_gifting_setting' => array(
			'title' => esc_html__( 'Group Gifting', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-group-gifting-setting.php',
		),
		'discount_setting' => array(
			'title' => esc_html__( 'Discount', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-discount-setting.php',
		),
		'thankyou_setting' => array(
			'title' => esc_html__( 'Thankyou order', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-thankyou-setting.php',
		),
		'qrcode_setting' => array(
			'title' => esc_html__( 'Qrcode / Barcode', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-qrcode-setting.php',
		),
		'customizable_setting' => array(
			'title' => esc_html__( 'Customizable Giftcard', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-customizable-setting.php',
		),
		'notification_setting' => array(
			'title' => esc_html__( 'Notification', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-notification-setting.php',
		),
		'rest_api_setting' => array(
			'title' => esc_html__( 'REST API', 'woo-gift-cards-lite' ),
			'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-rest-api-setting.php',
		),
	);
}
?>
<div class="wps-gc__popup-for-pro-wrap">
	<div class="wps-gc__popup-for-pro-shadow">

	</div>
	<div class="wps-gc__popup-for-pro">
		<span class="wps-gc__popup-for-pro-close">+</span>
		<h2 class="wps-gc__popup-for-pro-title">Unlock More Features with Pro Upgrade! </h2>
		<p class="wps-gc__popup-for-pro-content">Congratulations on discovering our premium Features! This stunning
			features is reserved for our Pro members, offering you a world of creative possibilities. Upgrade today to
			unlock it and access a wealth of exclusive features.</p>
		<div class="wps-gc__popup-for-pro-link-wrap">
			<a target="_blank"
				href="https://wpswings.com/product/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-pro&utm_medium=giftcards-org-backend&utm_campaign=go-pro"
				class="wps-gc__popup-for-pro-link">Go pro now</a>
		</div>
	</div>
</div>
<?php
			$wps_wgm_setting_tab = apply_filters( 'wps_wgm_add_gift_card_setting_tab_before', $wps_wgm_setting_tab );
			$wps_wgm_setting_tab['redeem_tab'] = array(
				'title' => esc_html__( 'Gift Card Redeem', 'woo-gift-cards-lite' ),
				'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/redeem-giftcard-settings.php',
			);
			if ( ! wps_uwgc_pro_active() ) {
				$wps_wgm_setting_tab['premium_plugin'] = array(
					'title' => esc_html__( 'Premium Features', 'woo-gift-cards-lite' ),
					'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-premium-features.php',
				);
			}
			$wps_wgm_setting_tab = apply_filters( 'wps_wgm_add_gift_card_setting_tab_after', $wps_wgm_setting_tab );
			do_action( 'wps_uwgc_show_notice' );
			?>
<div class="wrap woocommerce" id="wps_wgm_setting_wrapper">
	<input type="hidden" class="treat-button">
	<div style="display: none;" class="loading-style-bg" id="wps_wgm_loader">
		<img src="<?php echo esc_url( WPS_WGC_URL . 'assets/images/loading.gif' ); ?>">
	</div>
	<form enctype="multipart/form-data" action="" id="mainform" method="post">
		<div class="wps_wgm_header">
			<div class="wps_wgm_header_content_left">
				<div>
					<h3 class="wps_wgm_setting_title">
						<?php esc_html_e( 'Gift Card Settings', 'woo-gift-cards-lite' ); ?></h3>
				</div>
			</div>
			<div class="wps_wgm_header_content_right">
				<ul>
					<?php
					if ( wps_uwgc_pro_active() ) {
						?>
					<li class="wps_wgm_header_menu_button"><a
							href="https://wpswings.com/contact-us/?utm_source=wpswings-giftcards-contact&utm_medium=giftcards-pro-backend&utm_campaign=giftcards-contact"
							target="_blank">
							<span class="dashicons dashicons-phone"></span>
							<span
								class="wps-wgn-icon-text"><?php esc_html_e( 'CONTACT US', 'woo-gift-cards-lite' ); ?></span>
						</a>
					</li>
					<li class="wps_wgm_header_menu_button"><a
							href="https://docs.wpswings.com/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-doc&utm_medium=giftcards-pro-backend&utm_campaign=documentation"
							target="_blank">
							<span class="dashicons dashicons-media-document"></span>
							<span class="wps-wgn-icon-text"><?php esc_html_e( 'DOC', 'woo-gift-cards-lite' ); ?></span>
						</a>
					</li>
						<?php
					} else {
						?>
					<li class="wps_wgm_header_menu_button"><a
							href="https://wpswings.com/contact-us/?utm_source=wpswings-giftcards-contact&utm_medium=giftcards-org-backend&utm_campaign=contact"
							target="_blank">
							<span class="dashicons dashicons-phone"></span>
							<span
								class="wps-wgn-icon-text"><?php esc_html_e( 'CONTACT US', 'woo-gift-cards-lite' ); ?></span>
						</a>
					</li>
					<li class="wps_wgm_header_menu_button"><a
							href="https://docs.wpswings.com/woo-gift-cards-lite/?utm_source=wpswings-giftcards-doc&utm_medium=giftcards-org-backend&utm_campaign=documentation"
							target="_blank">
							<span class="dashicons dashicons-media-document"></span>
							<span class="wps-wgn-icon-text"><?php esc_html_e( 'DOC', 'woo-gift-cards-lite' ); ?></span>
						</a>
					</li>
					<li class="wps_wgm_header_menu_button wps-gc-demo-image"><a
							href="https://demo.wpswings.com/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-demo&utm_medium=giftcards-org-backend&utm_campaign=demo"
							class="wps-wgn-icon-text" title="" target="_blank">
							<img src="<?php echo esc_url( WPS_WGC_URL ); ?>assets/images/Demo.svg" class="wps-info-img"
								alt="Demo image">
							<span class="wps-wgn-icon-text"><?php esc_html_e( 'DEMO', 'woo-giftwps-wgc-nonce-cards-lite' ); ?></span>
						</a>
					</li>
					<li class="wps_wgm_header_menu_button">
						<a href="https://wpswings.com/product/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-pro&utm_medium=giftcards-org-backend&utm_campaign=go-pro"
							class="wps-wgn-icon-text" title=""
							target="_blank"><?php esc_html_e( 'GO PRO NOW', 'woo-gift-cards-lite' ); ?></a>
					</li>

						<?php
					}
					?>
				</ul>
			</div>
		</div>
		<?php
		$secure_nonce      = wp_create_nonce( 'wps-gc-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-auth-nonce' );
		if ( ! $id_nonce_verified ) {
				wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
		}
		wp_nonce_field( 'wps-wgc-nonce', 'wps-wgc-nonce' );

		?>
		<div class="wps_wgm_main_template">
			<div class="wps_wgm_body_template">
				<div class="wps_wgm_mobile_nav">
					<span class="dashicons dashicons-menu"></span>
				</div>
				<div class="wps_wgm_navigator_template">
					<div class="wps_wgm-navigations">
						<?php

						if ( isset( $wps_wgm_setting_tab ) && ! empty( $wps_wgm_setting_tab ) && is_array( $wps_wgm_setting_tab ) ) {
							foreach ( $wps_wgm_setting_tab as $key => $wps_tab ) {

								if ( isset( $_GET['tab'] ) && sanitize_key( wp_unslash( $_GET['tab'] ) ) == $key ) {

									if ( 'premium_plugin' !== $key && 'redeem_tab' !== $key && 'overview_setting' !== $key && 'general_setting' !== $key && 'product_setting' !== $key && 'product_setting' !== $key && 'email_setting' !== $key && 'delivery_method' !== $key && 'other_setting' !== $key ) {

										?>
						<div class="wps_wgm_tabs">
							<a class="wps_wgm_nav_tab nav-tab nav-tab-active wps-gift-cards-pro-tag"
								href="?post_type=giftcard&page=wps-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $wps_tab['title'] ); ?>
										<?php if ( ! wps_uwgc_pro_active() ) { ?>
								<span><?php esc_html_e( 'Pro', 'woo-gift-cards-lite' ); ?></span>
							<?php } ?>
								</a>
						</div>
										<?php
									} else {

										?>

						<div class="wps_wgm_tabs">
							<a class="wps_wgm_nav_tab nav-tab nav-tab-active"
								href="?post_type=giftcard&page=wps-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $wps_tab['title'] ); ?></a>
						</div>
										<?php
									}
								} else {
									if ( ! isset( $_GET['tab'] ) && 'overview_setting' == $key ) {
										?>
						<div class="wps_wgm_tabs">
							<a class="wps_wgm_nav_tab nav-tab nav-tab-active"
								href="?post_type=giftcard&page=wps-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $wps_tab['title'] ); ?></a>
						</div>
										<?php
									} else {

										if ( 'premium_plugin' !== $key && 'redeem_tab' !== $key && 'overview_setting' !== $key && 'general_setting' !== $key && 'product_setting' !== $key && 'product_setting' !== $key && 'email_setting' !== $key && 'delivery_method' !== $key && 'other_setting' !== $key ) {

											?>
						<div class="wps_wgm_tabs">
							<a class="wps_wgm_nav_tab nav-tab wps-gift-cards-pro-tag"
								href="?post_type=giftcard&page=wps-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $wps_tab['title'] ); ?>
											<?php if ( ! wps_uwgc_pro_active() ) { ?>
								<span><?php esc_html_e( 'Pro', 'woo-gift-cards-lite' ); ?></span>
							<?php } ?>
							</a>
						</div>
											<?php
										} else {

											?>

						<div class="wps_wgm_tabs">
							<a class="wps_wgm_nav_tab nav-tab"
								href="?post_type=giftcard&page=wps-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $wps_tab['title'] ); ?></a>
						</div>
											<?php
										}
									}
								}
							}
						}
						?>

					</div>
				</div>
				<?php
				if ( isset( $wps_wgm_setting_tab ) && ! empty( $wps_wgm_setting_tab ) && is_array( $wps_wgm_setting_tab ) ) {
					foreach ( $wps_wgm_setting_tab as $key => $wps_file ) {
						if ( isset( $_GET['tab'] ) && sanitize_key( wp_unslash( $_GET['tab'] ) ) == $key ) {
							$include_tab = isset( $wps_file['file_path'] ) ? $wps_file['file_path'] : '';
							?>
				<div class="wps_wgm_content_template">
							<?php include_once $include_tab; ?>
				</div>
							<?php
						} elseif ( ! isset( $_GET['tab'] ) && 'overview_setting' == $key ) {
							$include_tab = isset( $wps_file['file_path'] ) ? $wps_file['file_path'] : '';
							?>
				<div class="wps_wgm_content_template">
							<?php include_once $include_tab; ?>
				</div>
							<?php
							break;
						}
					}
				}
				?>
			</div>
		</div>
	</form>
</div>
