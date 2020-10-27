<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*  create the settings tabs*/
$mwb_wgm_setting_tab = array(
	'overview_setting' => array(
		'title' => esc_html__( 'OverView', 'woo-gift-cards-lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb-wgm-overview-setting.php',
	),
	'general_setting' => array(
		'title' => esc_html__( 'General', 'woo-gift-cards-lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb-wgm-general-setting.php',
	),
	'product_setting' => array(
		'title' => esc_html__( 'Product', 'woo-gift-cards-lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb-wgm-product-setting.php',
	),
	'email_setting' => array(
		'title' => esc_html__( 'Email Template', 'woo-gift-cards-lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb-wgm-email-template-setting.php',
	),
	'delivery_method' => array(
		'title' => esc_html__( 'Delivery Method', 'woo-gift-cards-lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb-wgm-delivery-setting.php',
	),
	'other_setting' => array(
		'title' => esc_html__( 'Other Settings', 'woo-gift-cards-lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb-wgm-other-setting.php',
	),
);
$mwb_wgm_setting_tab = apply_filters( 'mwb_wgm_add_gift_card_setting_tab_before', $mwb_wgm_setting_tab );
$mwb_wgm_setting_tab['redeem_tab'] = array(
	'title' => esc_html__( 'Gift Card Redeem', 'woo-gift-cards-lite' ),
	'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/redeem-giftcard-settings.php',
);
if ( ! mwb_uwgc_pro_active() ) {
	$mwb_wgm_setting_tab['premium_plugin'] = array(
		'title' => esc_html__( 'Premium Features', 'woo-gift-cards-lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb-wgm-premium-features.php',
	);
}
$mwb_wgm_setting_tab = apply_filters( 'mwb_wgm_add_gift_card_setting_tab_after', $mwb_wgm_setting_tab );
do_action( 'mwb_uwgc_show_notice' );
?>
<div class="wrap woocommerce" id="mwb_wgm_setting_wrapper">
	<div style="display: none;" class="loading-style-bg" id="mwb_wgm_loader">
		<img src="<?php echo esc_url( MWB_WGC_URL . 'assets/images/loading.gif' ); ?>">
	</div>
	<form enctype="multipart/form-data" action="" id="mainform" method="post">
		<div class="mwb_wgm_header">
			<div class="mwb_wgm_header_content_left">
				<div>
					<h3 class="mwb_wgm_setting_title"><?php esc_html_e( 'Gift Card Settings', 'woo-gift-cards-lite' ); ?></h3>
				</div>
			</div>
			<div class="mwb_wgm_header_content_right">
				<ul>
					<li class="mwb_wgm_header_menu_button"><a href="https://makewebbetter.com/contact-us/?utm_source=mwb-giftcard-wp-org&utm_medium=wp-org&utm_campaign=giftcard-org" target="_blank">
						<span class="dashicons dashicons-phone"></span>
						<span class="mwb-wgn-icon-text"><?php esc_html_e( 'CONTACT US', 'woo-gift-cards-lite' ); ?></span>
					</a>
					</li>
					<?php
					if ( mwb_uwgc_pro_active() ) {
						?>
						<li class="mwb_wgm_header_menu_button"><a href="https://docs.makewebbetter.com/giftware-woocommerce-gift-cards/" target="_blank">
							<span class="dashicons dashicons-media-document"></span>
							<span class="mwb-wgn-icon-text"><?php esc_html_e( 'DOC', 'woo-gift-cards-lite' ); ?></span>
						</a>
						</li>	
						<?php
					} else {
						?>
						<li class="mwb_wgm_header_menu_button"><a href="https://docs.makewebbetter.com/woocommerce-gift-cards-lite/?utm_source=MWB-giftcard-org&utm_medium=MWB-ORG-Page&utm_campaign=pluginDoc" target="_blank">
							<span class="dashicons dashicons-media-document"></span>
							<span class="mwb-wgn-icon-text"><?php esc_html_e( 'DOC', 'woo-gift-cards-lite' ); ?></span>
						</a>
						</li>
						<li class="mwb_wgm_header_menu_button">
							<a  href="https://makewebbetter.com/product/giftware-woocommerce-gift-cards/?utm_source=mwb-giftcard-org&utm_medium=mwb-org&utm_campaign=giftcard-org" class="mwb-wgn-icon-text" title="" target="_blank"><?php esc_html_e( 'GO PRO NOW', 'woo-gift-cards-lite' ); ?></a>
						</li>	
						<?php
					}
					?>
					<li>
						<a id="mwb-wgm-skype-link" href="<?php echo esc_url( 'https://join.skype.com/invite/IKVeNkLHebpC' ); ?>" target="_blank">
							<img src="<?php echo esc_url( MWB_WGC_URL . 'assets/images/skype_logo.png' ); ?>" style="height: 15px;width: 15px;" ><span class="mwb-wgn-icon-text"><?php esc_html_e( 'CHAT NOW', 'woo-gift-cards-lite' ); ?></span>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<?php wp_nonce_field( 'mwb-wgc-nonce', 'mwb-wgc-nonce' ); ?>
		<div class="mwb_wgm_main_template">
			<div class="mwb_wgm_body_template">
				<div class="mwb_wgm_mobile_nav">
					<span class="dashicons dashicons-menu"></span>
				</div>
				<div class="mwb_wgm_navigator_template">
					<div class="mwb_wgm-navigations">
						<?php
						if ( isset( $mwb_wgm_setting_tab ) && ! empty( $mwb_wgm_setting_tab ) && is_array( $mwb_wgm_setting_tab ) ) {
							foreach ( $mwb_wgm_setting_tab as $key => $mwb_tab ) {
								if ( isset( $_GET['tab'] ) && sanitize_key( wp_unslash( $_GET['tab'] ) ) == $key ) {
									?>
									<div class="mwb_wgm_tabs">
										<a class="mwb_wgm_nav_tab nav-tab nav-tab-active" href="?post_type=giftcard&page=mwb-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $mwb_tab['title'] ); ?></a>
									</div>
									<?php
								} else {
									if ( ! isset( $_GET['tab'] ) && 'overview_setting' == $key ) {
										?>
										<div class="mwb_wgm_tabs">
											<a class="mwb_wgm_nav_tab nav-tab nav-tab-active" href="?post_type=giftcard&page=mwb-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $mwb_tab['title'] ); ?></a>
										</div>
										<?php
									} else {
										?>
													
										<div class="mwb_wgm_tabs">
											<a class="mwb_wgm_nav_tab nav-tab" href="?post_type=giftcard&page=mwb-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $mwb_tab['title'] ); ?></a>
										</div>
										<?php
									}
								}
							}
						}
						?>
							
					</div>
				</div>
				<?php
				if ( isset( $mwb_wgm_setting_tab ) && ! empty( $mwb_wgm_setting_tab ) && is_array( $mwb_wgm_setting_tab ) ) {
					foreach ( $mwb_wgm_setting_tab as $key => $mwb_file ) {
						if ( isset( $_GET['tab'] ) && sanitize_key( wp_unslash( $_GET['tab'] ) ) == $key ) {
							$include_tab = isset( $mwb_file['file_path'] ) ? $mwb_file['file_path'] : '';
							?>
							<div class="mwb_wgm_content_template">
								<?php include_once $include_tab; ?>
							</div>
							<?php
						} elseif ( ! isset( $_GET['tab'] ) && 'overview_setting' == $key ) {
							$include_tab = isset( $mwb_file['file_path'] ) ? $mwb_file['file_path'] : '';
							?>
							<div class="mwb_wgm_content_template">
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
