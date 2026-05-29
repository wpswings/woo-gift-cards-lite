<?php
/**
 * Provide a admin area view for the plugin.
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

$is_pro_active = wps_uwgc_pro_active();

$wps_wgm_setting_tab = array(
	'overview_setting' => array(
		'title'     => esc_html__( 'Overview', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-overview-setting.php',
	),
	'general_setting'  => array(
		'title'     => esc_html__( 'General', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-general-setting.php',
	),
	'product_setting'  => array(
		'title'     => esc_html__( 'Product', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-product-setting.php',
	),
	'email_setting'    => array(
		'title'     => esc_html__( 'Email Template', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-email-template-setting.php',
	),
	'delivery_method'  => array(
		'title'     => esc_html__( 'Delivery Method', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-delivery-setting.php',
	),
	'other_setting'    => array(
		'title'     => esc_html__( 'Other Settings', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-other-setting.php',
	),
);

if ( ! $is_pro_active ) {
	$wps_wgm_setting_tab['offline_setting'] = array(
		'title'     => esc_html__( 'Offline Giftcards', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-offline-setting.php',
	);
	$wps_wgm_setting_tab['import_export_setting'] = array(
		'title'     => esc_html__( 'Import/ Export', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-im-export-setting.php',
	);
	$wps_wgm_setting_tab['group_gifting_setting'] = array(
		'title'     => esc_html__( 'Group Gifting', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-group-gifting-setting.php',
	);
	$wps_wgm_setting_tab['discount_setting'] = array(
		'title'     => esc_html__( 'Discount', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-discount-setting.php',
	);
	$wps_wgm_setting_tab['thankyou_setting'] = array(
		'title'     => esc_html__( 'Thankyou order', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-thankyou-setting.php',
	);
	$wps_wgm_setting_tab['qrcode_setting'] = array(
		'title'     => esc_html__( 'Qrcode / Barcode', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-qrcode-setting.php',
	);
	$wps_wgm_setting_tab['customizable_setting'] = array(
		'title'     => esc_html__( 'Customizable Giftcard', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-customizable-setting.php',
	);
	$wps_wgm_setting_tab['notification_setting'] = array(
		'title'     => esc_html__( 'Notification', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-notification-setting.php',
	);
	$wps_wgm_setting_tab['rest_api_setting'] = array(
		'title'     => esc_html__( 'REST API', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-rest-api-setting.php',
	);
}
?>
<div class="wps-gc__popup-for-pro-wrap">
	<div class="wps-gc__popup-for-pro-shadow"></div>
	<div class="wps-gc__popup-for-pro">
		<span class="wps-gc__popup-for-pro-close">+</span>
		<h2 class="wps-gc__popup-for-pro-title"><?php esc_html_e( 'Unlock More Features with Pro Upgrade!', 'woo-gift-cards-lite' ); ?></h2>
		<p class="wps-gc__popup-for-pro-content"><?php esc_html_e( 'Congratulations on discovering our premium Features! This stunning features is reserved for our Pro members, offering you a world of creative possibilities. Upgrade today to unlock it and access a wealth of exclusive features.', 'woo-gift-cards-lite' ); ?></p>
		<div class="wps-gc__popup-for-pro-link-wrap">
			<a
				class="wps-gc__popup-for-pro-link"
				href="https://wpswings.com/product/gift-cards-for-woocommerce-pro/?utm_source=wpswings-giftcards-pro&utm_medium=giftcards-org-backend&utm_campaign=go-pro"
				target="_blank"
			><?php esc_html_e( 'Go pro now', 'woo-gift-cards-lite' ); ?></a>
		</div>
	</div>
</div>
<?php
$wps_wgm_setting_tab = apply_filters( 'wps_wgm_add_gift_card_setting_tab_before', $wps_wgm_setting_tab );
$wps_wgm_setting_tab['redeem_tab'] = array(
	'title'     => esc_html__( 'Gift Card Redeem', 'woo-gift-cards-lite' ),
	'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/redeem-giftcard-settings.php',
);

if ( ! $is_pro_active ) {
	$wps_wgm_setting_tab['premium_plugin'] = array(
		'title'     => esc_html__( 'Premium Features', 'woo-gift-cards-lite' ),
		'file_path' => WPS_WGC_DIRPATH . 'admin/partials/templates/wps-wgm-premium-features.php',
	);
}

$wps_wgm_setting_tab = apply_filters( 'wps_wgm_add_gift_card_setting_tab_after', $wps_wgm_setting_tab );

$lite_visible_tabs = array(
	'overview_setting',
	'general_setting',
	'product_setting',
	'email_setting',
	'delivery_method',
	'other_setting',
	'redeem_tab',
	'premium_plugin',
);

$visible_setting_tabs = $wps_wgm_setting_tab;

$default_tab = 'overview_setting';
$requested_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : $default_tab;
$active_tab = array_key_exists( $requested_tab, $visible_setting_tabs ) ? $requested_tab : $default_tab;
$active_tab_data = isset( $visible_setting_tabs[ $active_tab ] ) ? $visible_setting_tabs[ $active_tab ] : array();
$active_tab_title = isset( $active_tab_data['title'] ) ? $active_tab_data['title'] : esc_html__( 'Gift Card Settings', 'woo-gift-cards-lite' );
$tab_descriptions = array(
	'overview_setting'      => esc_html__( 'Review plugin activity, shortcuts, and essential setup details for your store.', 'woo-gift-cards-lite' ),
	'general_setting'       => esc_html__( 'Control the base plugin behavior, delivery rules, and request availability windows.', 'woo-gift-cards-lite' ),
	'product_setting'       => esc_html__( 'Manage gift card product rules, field visibility, pricing, and purchase configuration.', 'woo-gift-cards-lite' ),
	'email_setting'         => esc_html__( 'Configure email templates, sender details, and delivery messages for gift cards.', 'woo-gift-cards-lite' ),
	'delivery_method'       => esc_html__( 'Control how gift cards are sent, scheduled, and delivered to recipients.', 'woo-gift-cards-lite' ),
	'other_setting'         => esc_html__( 'Manage additional gift card behavior, restrictions, and supporting options.', 'woo-gift-cards-lite' ),
	'offline_setting'       => esc_html__( 'Manage offline gift card workflows, imports, and delivery handling.', 'woo-gift-cards-lite' ),
	'import_export_setting' => esc_html__( 'Import or export gift card data, templates, and bulk configuration records.', 'woo-gift-cards-lite' ),
	'group_gifting_setting' => esc_html__( 'Configure collaborative gifting flows and related purchase controls.', 'woo-gift-cards-lite' ),
	'discount_setting'      => esc_html__( 'Manage discount-related gift card options and reward settings.', 'woo-gift-cards-lite' ),
	'thankyou_setting'      => esc_html__( 'Customize thank you order workflows and post-purchase gift card messaging.', 'woo-gift-cards-lite' ),
	'qrcode_setting'        => esc_html__( 'Configure QR and barcode behavior for scanning and redemption workflows.', 'woo-gift-cards-lite' ),
	'customizable_setting'  => esc_html__( 'Manage customer-facing customization choices for gift card presentation.', 'woo-gift-cards-lite' ),
	'notification_setting'  => esc_html__( 'Manage reminder, expiry, and event-based notification settings.', 'woo-gift-cards-lite' ),
	'rest_api_setting'      => esc_html__( 'Configure REST API access and gift card integration settings.', 'woo-gift-cards-lite' ),
	'redeem_tab'            => esc_html__( 'Control redeem, recharge, and balance workflows from one place.', 'woo-gift-cards-lite' ),
	'premium_plugin'        => esc_html__( 'Explore premium-only modules available for advanced gift card workflows.', 'woo-gift-cards-lite' ),
);
$active_tab_description = isset( $tab_descriptions[ $active_tab ] ) ? $tab_descriptions[ $active_tab ] : esc_html__( 'Configure and manage all gift card options from this section.', 'woo-gift-cards-lite' );
$plugin_display_version = defined( 'WPS_WGC_VERSION' ) ? WPS_WGC_VERSION : '3.2.7';
if ( $is_pro_active && defined( 'WPS_UWGC_PLUGIN_VERSION' ) ) {
	$plugin_display_version = WPS_UWGC_PLUGIN_VERSION;
}
$plugin_version_label = sprintf(
	/* translators: 1: plugin version, 2: edition label */
	esc_html__( 'v%1$s %2$s', 'woo-gift-cards-lite' ),
	$plugin_display_version,
	$is_pro_active ? esc_html__( 'Pro', 'woo-gift-cards-lite' ) : esc_html__( 'Lite', 'woo-gift-cards-lite' )
);

$max_primary_tabs = 8;
$primary_setting_tabs = array_slice( $visible_setting_tabs, 0, $max_primary_tabs, true );
$overflow_setting_tabs = array_slice( $visible_setting_tabs, $max_primary_tabs, null, true );
$is_overflow_active = array_key_exists( $active_tab, $overflow_setting_tabs );

$help_links = array(
	array(
		'label' => esc_html__( 'Watch Video', 'woo-gift-cards-lite' ),
		'url'   => 'https://www.youtube.com/watch?v=g6JLA3ewph8',
	),
	array(
		'label' => esc_html__( 'Documentation', 'woo-gift-cards-lite' ),
		'url'   => $is_pro_active
			? 'https://docs.wpswings.com/gift-cards-for-woocommerce/?utm_source=wpswings-giftcards-doc&utm_medium=giftcards-pro-backend&utm_campaign=documentation'
			: 'https://docs.wpswings.com/woo-gift-cards-lite/?utm_source=wpswings-giftcards-doc&utm_medium=giftcards-org-backend&utm_campaign=documentation',
	),
	array(
		'label' => esc_html__( 'Contact Us', 'woo-gift-cards-lite' ),
		'url'   => $is_pro_active
			? 'https://wpswings.com/contact-us/?utm_source=wpswings-giftcards-contact&utm_medium=giftcards-pro-backend&utm_campaign=giftcards-contact'
			: 'https://wpswings.com/contact-us/?utm_source=wpswings-giftcards-contact&utm_medium=giftcards-org-backend&utm_campaign=contact',
	),
	array(
		'label' => esc_html__( 'Explore More Plugins', 'woo-gift-cards-lite' ),
		'url'   => 'https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-giftcards-shop&utm_medium=giftcards-org-backend&utm_campaign=shop-page',
	),
);

$wps_wgm_services_link = Woocommerce_Gift_Cards_Lite_Talk_To_Expert_Form::wps_wgm_get_services_landing_url();
$wps_wgm_marketing_services = array(
	array(
		'icon'        => 'seo',
		'title'       => esc_html__( 'SEO Services', 'woo-gift-cards-lite' ),
		'description' => esc_html__( 'Improve rankings & organic traffic', 'woo-gift-cards-lite' ),
	),
	array(
		'icon'        => 'ads',
		'title'       => esc_html__( 'Google Ads Setup And G4 Setup', 'woo-gift-cards-lite' ),
		'description' => esc_html__( 'Run profitable ad campaigns', 'woo-gift-cards-lite' ),
	),
	array(
		'icon'        => 'speed',
		'title'       => esc_html__( 'Speed Optimization', 'woo-gift-cards-lite' ),
		'description' => esc_html__( 'Faster store, happier customers', 'woo-gift-cards-lite' ),
	),
	array(
		'icon'        => 'dev',
		'title'       => esc_html__( 'WooCommerce Development Services', 'woo-gift-cards-lite' ),
		'description' => esc_html__( 'Custom Solution For your store needs', 'woo-gift-cards-lite' ),
	),
);

$secure_nonce = wp_create_nonce( 'wps-gc-auth-nonce' );
$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-auth-nonce' );
if ( ! $id_nonce_verified ) {
	wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
}

$dashboard_top_notice = array();
if (
	'POST' === strtoupper( isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : '' ) &&
	isset( $_REQUEST['wps-wgc-nonce'] ) &&
	wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps-wgc-nonce'] ) ), 'wps-wgc-nonce' )
) {
	$posted_keys = array_keys( wp_unslash( $_POST ) );
	foreach ( $posted_keys as $posted_key ) {
		if ( preg_match( '/^(wps_wgm_save_|wps_uwgc_save_|wps_uwgc_generate_api_key$)/', $posted_key ) ) {
			$dashboard_top_notice = array(
				'type'    => 'success',
				'message' => esc_html__( 'Settings saved !', 'woo-gift-cards-lite' ),
			);
			break;
		}
		if ( preg_match( '/reset_save$/', $posted_key ) ) {
			$dashboard_top_notice = array(
				'type'    => 'success',
				'message' => esc_html__( 'Settings saved !', 'woo-gift-cards-lite' ),
			);
			break;
		}
	}
}

$dashboard_top_notice = apply_filters( 'wps_uwgc_dashboard_top_notice', $dashboard_top_notice, $active_tab );
?>
<div class="wrap woocommerce" id="wps_wgm_setting_wrapper">
	<input class="treat-button" type="hidden">
	<div class="loading-style-bg" id="wps_wgm_loader" style="display: none;">
		<img alt="<?php esc_attr_e( 'Loading', 'woo-gift-cards-lite' ); ?>" src="<?php echo esc_url( WPS_WGC_URL . 'assets/images/loading.gif' ); ?>">
	</div>
	<form action="" enctype="multipart/form-data" id="mainform" method="post">
		<?php wp_nonce_field( 'wps-wgc-nonce', 'wps-wgc-nonce' ); ?>

		<div class="wps_wgm_dashboard_shell">

			<hr class="wp-header-end" style="display:none;visibility:hidden;height:0;margin:0;border:0;" />

			<?php if ( ! empty( $dashboard_top_notice ) ) : ?>
				<div class="wps_wgm_dashboard_page_notice wps_wgm_dashboard_page_notice_<?php echo esc_attr( $dashboard_top_notice['type'] ); ?>">
					<p><?php echo esc_html( $dashboard_top_notice['message'] ); ?></p>
					<button type="button" class="notice-dismiss">
						<span class="screen-reader-text"><?php esc_html_e( 'Dismiss notice.', 'woo-gift-cards-lite' ); ?></span>
					</button>
				</div>
			<?php endif; ?>

			<div class="wps_wgm_dashboard_notice_stack">
				<div class="wps_wgm_dashboard_notice wps_wgm_dashboard_notice_status">
					<div class="wps_wgm_dashboard_notice_badge wps_wgm_dashboard_notice_badge_dark">
						<?php echo esc_html( $is_pro_active ? __( 'PRO ACTIVE', 'woo-gift-cards-lite' ) : __( 'FREE ACTIVE', 'woo-gift-cards-lite' ) ); ?>
					</div>
					<div class="wps_wgm_dashboard_notice_copy">
						<strong><?php echo esc_html( $is_pro_active ? __( 'Gift Cards for WooCommerce Pro', 'woo-gift-cards-lite' ) : __( 'Gift Card Settings Dashboard', 'woo-gift-cards-lite' ) ); ?></strong>
					</div>
					<div class="wps_wgm_dashboard_notice_meta">
						<?php echo esc_html( $is_pro_active ? $plugin_version_label : __( 'Lite', 'woo-gift-cards-lite' ) ); ?>
					</div>
				</div>
			</div>

			<div class="wps_wgm_dashboard_notice_row">
				<?php do_action( 'wps_uwgc_show_notice' ); ?>
			</div>

			<div class="wps_wgm_main_template">
				<div class="wps_wgm_dashboard_tabs" role="tablist" aria-label="<?php esc_attr_e( 'Gift card settings tabs', 'woo-gift-cards-lite' ); ?>">
					<div class="wps_wgm_tabs_meta">
						<span class="wps_wgm_tabs_version"><?php echo esc_html( $plugin_version_label ); ?></span>
					</div>
					<?php foreach ( $primary_setting_tabs as $key => $wps_tab ) : ?>
						<?php
						$is_active = ( $active_tab === $key );
						$is_locked = ( ! $is_pro_active && ! in_array( $key, $lite_visible_tabs, true ) );
						$tab_classes = array( 'wps_wgm_nav_tab', 'nav-tab' );

						if ( $is_active ) {
							$tab_classes[] = 'nav-tab-active';
						}
						if ( $is_locked ) {
							$tab_classes[] = 'wps-gift-cards-pro-tag';
							$tab_classes[] = 'wps_wgm_nav_tab_locked';
						}
						?>
							<div class="wps_wgm_tabs">
								<a
									class="<?php echo esc_attr( implode( ' ', $tab_classes ) ); ?>"
									data-locked="<?php echo esc_attr( $is_locked ? 'yes' : 'no' ); ?>"
									href="<?php echo esc_url( admin_url( 'edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=' . $key ) ); ?>"
								>
									<span class="wps_wgm_nav_tab_title"><?php echo esc_html( $wps_tab['title'] ); ?></span>
									<?php if ( $is_locked ) : ?>
									<span class="wps_wgm_nav_badge"><?php esc_html_e( 'Pro', 'woo-gift-cards-lite' ); ?></span>
								<?php endif; ?>
							</a>
						</div>
					<?php endforeach; ?>

					<?php if ( ! empty( $overflow_setting_tabs ) ) : ?>
						<div class="wps_wgm_tabs wps_wgm_tabs_more">
							<button
								type="button"
								class="<?php echo esc_attr( implode( ' ', array_filter( array( 'wps_wgm_nav_tab', 'wps_wgm_nav_tab_more', 'nav-tab', $is_overflow_active ? 'nav-tab-active' : '' ) ) ) ); ?>"
								aria-expanded="false"
							>
								<span class="wps_wgm_nav_tab_title"><?php esc_html_e( 'More', 'woo-gift-cards-lite' ); ?></span>
								<span class="wps_wgm_more_caret">▼</span>
							</button>
							<div class="wps_wgm_more_menu">
									<?php foreach ( $overflow_setting_tabs as $key => $wps_tab ) : ?>
										<?php $is_locked = ( ! $is_pro_active && ! in_array( $key, $lite_visible_tabs, true ) ); ?>
										<a
											class="wps_wgm_more_menu_link<?php echo esc_attr( $active_tab === $key ? ' is-active' : '' ); ?><?php echo esc_attr( $is_locked ? ' is-locked' : '' ); ?>"
											href="<?php echo esc_url( admin_url( 'edit.php?post_type=giftcard&page=wps-wgc-setting-lite&tab=' . $key ) ); ?>"
										>
											<?php echo esc_html( $wps_tab['title'] ); ?>
											<?php if ( $is_locked ) : ?>
											<span class="wps_wgm_nav_badge"><?php esc_html_e( 'Pro', 'woo-gift-cards-lite' ); ?></span>
										<?php endif; ?>
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<div class="wps_wgm_content_template">
					<div class="wps_wgm_content_shell">
						<header class="wps_wgm_content_header">
							<div class="wps_wgm_content_header_copy">
								<span class="wps_wgm_content_header_eyebrow"><?php esc_html_e( 'Settings', 'woo-gift-cards-lite' ); ?></span>
								<h2><?php echo esc_html( $active_tab_title ); ?></h2>
								<p><?php echo esc_html( $active_tab_description ); ?></p>
							</div>
							<div class="wps_wgm_content_header_actions">
								<a class="wps_wgm_dashboard_action" href="<?php echo esc_url( $help_links[1]['url'] ); ?>" target="_blank"><?php esc_html_e( 'Read Documentation', 'woo-gift-cards-lite' ); ?></a>
							</div>
						</header>

						<div class="wps_wgm_content_layout">
								<div class="wps_wgm_content_panel">
									<?php
									foreach ( $visible_setting_tabs as $key => $wps_file ) {
										if ( $active_tab !== $key ) {
											continue;
										}

									$include_tab = isset( $wps_file['file_path'] ) ? $wps_file['file_path'] : '';
									if ( ! empty( $include_tab ) ) {
										include_once $include_tab;
									}
									break;
								}
								?>
							</div>

							<aside class="wps_wgm_dashboard_sidebar">
								<div class="wps_wgm_sidebar_card">
									<h3><?php esc_html_e( 'Need help with this plugin?', 'woo-gift-cards-lite' ); ?></h3>
									<div class="wps_wgm_sidebar_links">
										<a class="wps_wgm_sidebar_link" href="<?php echo esc_url( $help_links[0]['url'] ); ?>" target="_blank"><?php echo esc_html( $help_links[0]['label'] ); ?></a>
										<a class="wps_wgm_sidebar_link" href="<?php echo esc_url( $help_links[1]['url'] ); ?>" target="_blank"><?php echo esc_html( $help_links[1]['label'] ); ?></a>
										<a class="wps_wgm_sidebar_link" href="<?php echo esc_url( $help_links[2]['url'] ); ?>" target="_blank"><?php echo esc_html__( 'Support', 'woo-gift-cards-lite' ); ?></a>
									</div>
								</div>

								<div class="wps_wgm_sidebar_card wps_wgm_sidebar_card_services">
									<div class="wps_wgm_sidebar_services_header">
										<h3><?php esc_html_e( 'Grow Your Store With WP Swings', 'woo-gift-cards-lite' ); ?></h3>
										<span class="wps_wgm_sidebar_services_badge" aria-hidden="true"></span>
									</div>
									<p><?php esc_html_e( "Expert solutions to boost your store's performance.", 'woo-gift-cards-lite' ); ?></p>
									<div class="wps_wgm_service_rail">
										<?php foreach ( $wps_wgm_marketing_services as $wps_wgm_marketing_service ) : ?>
											<a class="wps_wgm_service_rail_item" href="<?php echo esc_url( $wps_wgm_services_link ); ?>" target="_blank" rel="noopener noreferrer">
												<span class="wps_wgm_service_rail_icon wps_wgm_service_rail_icon_<?php echo esc_attr( $wps_wgm_marketing_service['icon'] ); ?>" aria-hidden="true"></span>
												<span class="wps_wgm_service_rail_content">
													<span class="wps_wgm_service_rail_title"><?php echo esc_html( $wps_wgm_marketing_service['title'] ); ?></span>
													<span class="wps_wgm_service_rail_description"><?php echo esc_html( $wps_wgm_marketing_service['description'] ); ?></span>
												</span>
												<span class="wps_wgm_service_rail_arrow" aria-hidden="true">&rsaquo;</span>
											</a>
										<?php endforeach; ?>
									</div>
									<button type="button" class="wps_wgm_sidebar_services_button" data-wps-wgm-open-expert-modal><?php esc_html_e( 'Talk to an Expert', 'woo-gift-cards-lite' ); ?></button>
									<div class="wps_wgm_service_rail_footer"><?php esc_html_e( 'Services by WP Swings', 'woo-gift-cards-lite' ); ?></div>
								</div>
								<div class="wps_wgm_sidebar_card wps_wgm_sidebar_card_tint">
									<h3><?php esc_html_e( 'Still facing problems?', 'woo-gift-cards-lite' ); ?></h3>
									<p><?php esc_html_e( 'We are ready to resolve workflow, styling, and integration issues across your store setup.', 'woo-gift-cards-lite' ); ?></p>
									<div class="wps_wgm_sidebar_links">
										<a class="wps_wgm_sidebar_link wps_wgm_sidebar_link_primary" href="<?php echo esc_url( $help_links[2]['url'] ); ?>" target="_blank"><?php echo esc_html( $help_links[2]['label'] ); ?></a>
									</div>
								</div>

								<div class="wps_wgm_sidebar_card">
									<h3><?php esc_html_e( 'Explore more plugins', 'woo-gift-cards-lite' ); ?></h3>
									<p><?php esc_html_e( 'Discover additional commerce and automation plugins from the same product family.', 'woo-gift-cards-lite' ); ?></p>
									<div class="wps_wgm_sidebar_links">
										<a class="wps_wgm_sidebar_link" href="<?php echo esc_url( $help_links[3]['url'] ); ?>" target="_blank"><?php echo esc_html__( 'View More Plugins', 'woo-gift-cards-lite' ); ?></a>
									</div>
								</div>
							</aside>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<?php Woocommerce_Gift_Cards_Lite_Talk_To_Expert_Form::wps_wgm_render_modal(); ?>
</div>
