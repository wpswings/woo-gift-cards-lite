<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_gift_cards_lite
 * @subpackage Woocommerce_gift_cards_lite/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*  create the settings tabs*/
$mwb_wgm_setting_tab = array(
	'overview_setting' => array(
		'title' => esc_html__( 'OverView', 'woocommerce_gift_cards_lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb_wgm_overview_setting.php',
	),
	'general_setting' => array(
		'title' => esc_html__( 'General', 'woocommerce_gift_cards_lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb_wgm_general_setting.php',
	),
	'product_setting' => array(
		'title' => esc_html__( 'Product', 'woocommerce_gift_cards_lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb_wgm_product_setting.php',
	),
	'email_setting' => array(
		'title' => esc_html__( 'Email Template', 'woocommerce_gift_cards_lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb_wgm_email_template_setting.php',
	),
	'delivery_method' => array(
		'title' => esc_html__( 'Delivery Method', 'woocommerce_gift_cards_lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb_wgm_delivery_setting.php',
	),
	'other_setting' => array(
		'title' => esc_html__( 'Other Settings', 'woocommerce_gift_cards_lite' ),
		'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/mwb_wgm_other_setting.php',
	),
);
$mwb_wgm_setting_tab = apply_filters( 'mwb_wgm_add_gift_card_setting_tab_before', $mwb_wgm_setting_tab );
$mwb_wgm_setting_tab['redeem_tab'] = array(
	'title' => esc_html__( 'Gift Card Redeem', 'woocommerce_gift_cards_lite' ),
	'file_path' => MWB_WGC_DIRPATH . 'admin/partials/templates/redeem-giftcard-settings.php',
);
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
					<h3 class="mwb_wgm_setting_title"><?php esc_html_e( 'GiftCard Settings', 'woocommerce_gift_cards_lite' ); ?></h3>
				</div>
			</div>
			<div class="mwb_wgm_header_content_right">
				<ul>
					<?php
					if ( mwb_uwgc_pro_active() ) {
						?>
						<li><a href="?page=mwb-wgc-setting-lite&tab=redeem_tab" class="mwb_wgm_redeem_link"><span><?php esc_html_e( 'New', 'woocommerce_gift_cards_lite' ); ?></span><?php esc_html_e( 'Giftcard Redeem Feature', 'woocommerce_gift_cards_lite' ); ?></a></li>
						<li><a href="https://makewebbetter.com/contact-us/" target="_blank">
							<span class="dashicons dashicons-phone"></span></a>
						</li>
						<li><a href="https://docs.makewebbetter.com/giftware-woocommerce-gift-cards/" target="_blank">
							<span class="dashicons dashicons-media-document"></span></a>
						</li>	
						<?php
					} else {
						?>
						<li><a href="?page=mwb-wgc-setting-lite&tab=redeem_tab" class="mwb_wgm_redeem_link"><span><?php esc_html_e( 'New', 'woocommerce_gift_cards_lite' ); ?></span><?php esc_html_e( 'Giftcard Redeem Feature', 'woocommerce_gift_cards_lite' ); ?></a></li>
						<li><a href="https://makewebbetter.com/contact-us/" target="_blank">
							<span class="dashicons dashicons-phone"></span>
						</a>
					</li>
					<li><a href="https://docs.makewebbetter.com/woocommerce-gift-cards-lite/" target="_blank">
						<span class="dashicons dashicons-media-document"></span>
					</a>
				</li>
				<li class="mwb_wgm_header_menu_button">
					<a  href="https://makewebbetter.com/product/giftware-woocommerce-gift-cards/?utm_source=mwb-giftcard-org&utm_medium=mwb-org&utm_campaign=giftcard-org" class="" title="" target="_blank"><?php esc_html_e( 'GO PRO NOW', 'woocommerce_gift_cards_lite' ); ?></a>
				</li>	
						<?php
					}
					?>
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
								<a class="mwb_wgm_nav_tab nav-tab nav-tab-active" href="?page=mwb-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $mwb_tab['title'] ); ?></a>
							</div>
							<?php
						} else {
							if ( ! isset( $_GET['tab'] ) && $key == 'overview_setting' ) {
								?>
								<div class="mwb_wgm_tabs">
									<a class="mwb_wgm_nav_tab nav-tab nav-tab-active" href="?page=mwb-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $mwb_tab['title'] ); ?></a>
								</div>
								<?php
							} else {
								?>
											
								<div class="mwb_wgm_tabs">
									<a class="mwb_wgm_nav_tab nav-tab" href="?page=mwb-wgc-setting-lite&tab=<?php echo esc_attr( $key ); ?>"><?php echo esc_attr( $mwb_tab['title'] ); ?></a>
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
				} elseif ( ! isset( $_GET['tab'] ) && $key == 'overview_setting' ) {
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
