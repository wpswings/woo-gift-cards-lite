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
$overview = '';
$generaltab = '';
$producttab = '';
$tab = '';
$emailtab = '';
$offlinetab = '';
$exporttab = '';
$additionaltab = '';
$discounttab = '';
$thankyouordertab = '';
$deliverytab = '';
$qrbartab = '';
$exportcoupontabactive = false;
$generaltabactive = false;
$producttabactive = false;
$emailtabactive = false;
$offlinepaymenttabactive = false;
$additionalsettingtabactive = false;
$discounttabactive = false;
$thankyouordertabactive = false;
$deliverytabactive = false;
$qrbartabactive = false;
$overviewactive = false;
$redeem_tab = '';
$redeem_tab_active = false;

if ( isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ) {
	$tab = sanitize_key( wp_unslash( $_GET['tab'] ) );
	if ( $tab == 'overview' ) {
		$overview = 'nav-tab-active';
		$overviewactive = true;
	}
	if ( $tab == 'general-setting' ) {
		$generaltab = 'nav-tab-active';
		$generaltabactive = true;
	}
	if ( $tab == 'product-setting' ) {
		$producttab = 'nav-tab-active';
		$producttabactive = true;
	}
	if ( $tab == 'email-setting' ) {
		$emailtab = 'nav-tab-active';
		$emailtabactive = true;
	}
	if ( $tab == 'offline-giftcard' ) {
		$offlinetab = 'nav-tab-active';
		$offlinepaymenttabactive = true;
	}
	if ( $tab == 'export-coupon' ) {
		$exporttab = 'nav-tab-active';
		$exportcoupontabactive = true;
	}
	if ( $tab == 'other-additional-setting' ) {
		$additionaltab = 'nav-tab-active';
		$additionalsettingtabactive = true;
	}
	if ( $tab == 'discount-tab' ) {
		$discounttab = 'nav-tab-active';
		$discounttabactive = true;
	}
	if ( $tab == 'thankyou-tab' ) {
		$thankyouordertab = 'nav-tab-active';
		$thankyouordertabactive = true;
	}
	if ( $tab == 'qr-barcode-tab' ) {
		$qrbartab = 'nav-tab-active';
		$qrbartabactive = true;
	}
	if ( $tab == 'delivery-tab' ) {
		$deliverytab = 'nav-tab-active';
		$deliverytabactive = true;
	}
	if ( $tab == 'redeem_section' ) {
		$redeem_tab = 'nav-tab-active';
		$redeem_tab_active = true;
	}
	do_action( 'mwb_wgm_setting_tab_active' );
}
if ( empty( $tab ) ) {
	$overview = 'nav-tab-active';
	$overviewactive = true;
}
?>

<div class="wrap woocommerce" id="mwb_wgm_setting_wrapper">
	<form enctype="multipart/form-data" action="" id="mainform" method="post">
		<div class="mwb_wgm_header">
			<div class="mwb_wgm_header_content_left">
				<div>
					<h3 class="mwb_wgm_setting_title"><?php esc_html_e( 'GiftWare Lite Settings', 'woocommerce_gift_cards_lite' ); ?></h3>
				</div>
			</div>
			<div class="mwb_wgm_header_content_right">
				<ul>
					<li><a href="https://makewebbetter.com/contact-us/" target="_blank">
						<span class="dashicons dashicons-phone"></span>
					</a>
				</li>
				<li><a href="https://docs.makewebbetter.com/woocommerce-gift-cards-lite/" target="_blank">
					<span class="dashicons dashicons-media-document"></span>
				</a>
			</li>
			<li class="mwb_wgm_header_menu_button"><a  href="https://makewebbetter.com/product/giftware-woocommerce-gift-cards/?utm_source=mwb-giftcard-org&utm_medium=mwb-org&utm_campaign=giftcard-org" class="" title="" target="_blank">GO PRO NOW</a></li>
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
			<div class="hubwoo-navigations">
				<div class="mwb_wgm_tabs">
					<a class="mwb_gw_nav_tab nav-tab <?php echo esc_attr( $overview ); ?>" href="?page=mwb-wgc-setting-lite&tab=overview"><?php esc_html_e( 'OverView', 'woocommerce_gift_cards_lite' ); ?></a>
				</div>
				<div class="mwb_wgm_tabs">
					<a class="mwb_gw_nav_tab nav-tab <?php echo esc_attr( $generaltab ); ?>" href="?page=mwb-wgc-setting-lite&tab=general-setting"><?php esc_html_e( 'General', 'woocommerce_gift_cards_lite' ); ?></a>
				</div>
				<div class="mwb_wgm_tabs">
					<a class="mwb_gw_nav_tab nav-tab <?php echo esc_attr( $producttab ); ?>" href="?page=mwb-wgc-setting-lite&tab=product-setting"><?php esc_html_e( 'Products', 'woocommerce_gift_cards_lite' ); ?></a>
				</div>
				<div class="mwb_wgm_tabs">
					<a class="mwb_gw_nav_tab nav-tab <?php echo esc_attr( $emailtab ); ?>" href="?page=mwb-wgc-setting-lite&tab=email-setting"><?php esc_html_e( 'Email Template', 'woocommerce_gift_cards_lite' ); ?></a>
				</div>
				<div class="mwb_wgm_tabs">
					<a class="mwb_gw_nav_tab nav-tab <?php echo esc_attr( $offlinetab ); ?>" href="?page=mwb-wgc-setting-lite&tab=offline-giftcard"><?php esc_html_e( 'Offline Giftcard', 'woocommerce_gift_cards_lite' ); ?></a>
				</div>
				<div class="mwb_wgm_tabs">
					<a class="mwb_gw_nav_tab nav-tab <?php echo esc_attr( $exporttab ); ?>" href="?page=mwb-wgc-setting-lite&tab=export-coupon"><?php esc_html_e( 'Import/Export', 'woocommerce_gift_cards_lite' ); ?></a>
				</div>
				<div class="mwb_wgm_tabs">
					<a class="mwb_gw_nav_tab nav-tab <?php echo esc_attr( $additionaltab ); ?>" href="?page=mwb-wgc-setting-lite&tab=other-additional-setting"><?php esc_html_e( 'Other Setting', 'woocommerce_gift_cards_lite' ); ?></a>
				</div>
				<div class="mwb_wgm_tabs">
					<a class="mwb_gw_nav_tab nav-tab <?php echo esc_attr( $discounttab ); ?>" href="?page=mwb-wgc-setting-lite&tab=discount-tab"><?php esc_html_e( 'Discount', 'woocommerce_gift_cards_lite' ); ?></a>
				</div>
				<div class="mwb_wgm_tabs">
					<a class="mwb_gw_nav_tab nav-tab <?php echo esc_attr( $thankyouordertab ); ?>" href="?page=mwb-wgc-setting-lite&tab=thankyou-tab"><?php esc_html_e( 'Thank You Order', 'woocommerce_gift_cards_lite' ); ?></a>
				</div>
				<div class="mwb_wgm_tabs">
					<a class="mwb_gw_nav_tab nav-tab <?php echo esc_attr( $qrbartab ); ?>" href="?page=mwb-wgc-setting-lite&tab=qr-barcode-tab"><?php esc_html_e( 'QRCode/BarCode', 'woocommerce_gift_cards_lite' ); ?></a>
				</div>
				<div class="mwb_wgm_tabs">	
					<a class="mwb_gw_nav_tab nav-tab <?php echo esc_attr( $deliverytab ); ?>" href="?page=mwb-wgc-setting-lite&tab=delivery-tab"><?php esc_html_e( 'Delivery Method', 'woocommerce_gift_cards_lite' ); ?></a>	
				</div>	
				<div class="mwb_wgm_tabs">
					<a class="mwb_gw_nav_tab nav-tab <?php echo esc_attr( $redeem_tab ); ?>" href="?page=mwb-wgc-setting-lite&tab=redeem_section"><?php esc_html_e( 'Gift Card Redeem', 'woocommerce_gift_cards_lite' ); ?><span class="mwb_gw_new_badge">New</span></a>
				</div>
				<?php
				do_action( 'mwb_wgm_setting_tab' );
				?>
					
			</div>
		</div>
		<div class="mwb_wgm_content_template">
			<?php
			if ( $generaltabactive == true ) {
				include_once 'templates/mwb_wgc_general_setting.php';
			}
			if ( $producttabactive == true ) {
				include_once 'templates/mwb_wgc_product_setting.php';
			}
			if ( $emailtabactive == true ) {
				include_once 'templates/mwb_wgc_email_template_setting.php';
			}
			if ( $offlinepaymenttabactive == true ) {
				include_once 'templates/mwb_wgc_pro_version.php';
			}
			if ( $exportcoupontabactive == true ) {
				include_once 'templates/mwb_wgc_pro_version.php';
			}
			if ( $additionalsettingtabactive == true ) {
				include_once 'templates/mwb_wgc_pro_version.php';
			}
			if ( $discounttabactive == true ) {
				include_once 'templates/mwb_wgc_pro_version.php';
			}
			if ( $thankyouordertabactive == true ) {
				include_once 'templates/mwb_wgc_pro_version.php';
			}
			if ( $qrbartabactive == true ) {
				include_once 'templates/mwb_wgc_pro_version.php';
			}
			if ( $deliverytabactive == true ) {
				include_once 'templates/mwb_wgc_pro_version.php';
			}
			if ( $overviewactive == true ) {
				include_once 'templates/mwb_wgc_overview_setting.php';
			}
			if ( $redeem_tab_active == true ) {
				include_once 'templates/redeem-giftware-settings.php';
			}
			?>
						
		</div>
	</div>
</div>
</form>
</div>
