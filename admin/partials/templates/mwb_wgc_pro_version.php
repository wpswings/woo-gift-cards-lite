<?php
/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if( isset($_GET['tab']) && !empty($_GET['tab'] ) )
{	
	$mwb_wgc_product_setting_tab = false;
	$mwb_wgc_email_setting_tab = false;
	$mwb_wgc_offline_giftcard_tab = false;
	$mwb_wgc_export_coupon_tab = false;
	$mwb_wgc_other_add_tab = false;
	$mwb_wgc_discount_tab = false;
	$mwb_wgc_thankyou_tab = false;
	$mwb_wgc_delivery_tab = false;
	$mwb_wgc_qrbarcode_tab = false;
	$tab = sanitize_text_field( $_GET['tab'] );
	if( $tab == 'product-setting' ){
		$mwb_wgc_product_setting_tab = true;
	}
	if( $tab == 'email-setting' ){
		$mwb_wgc_email_setting_tab = true;
	}
	if( $tab == 'offline-giftcard' ){
		$mwb_wgc_offline_giftcard_tab = true;
	}
	if( $tab == 'export-coupon' ){
		$mwb_wgc_export_coupon_tab = true;
	}
	if( $tab == 'other-additional-setting' ){
		$mwb_wgc_other_add_tab = true;
	}
	if( $tab == 'discount-tab' ){
		$mwb_wgc_discount_tab = true;
	}
	if($tab == 'thankyou-tab'){
		$mwb_wgc_thankyou_tab = true;
	}
	if( $tab == 'qr-barcode-tab' ){
		$mwb_wgc_qrbarcode_tab = true;
	}
	if( $tab == 'delivery-tab' ){
		$mwb_wgc_delivery_tab = true;
	}
}

if( $mwb_wgc_product_setting_tab ){
	$mwb_wgc_about_pro_version = __('Here you may exclude your product as well as the list of categories for getting applied the Gift Card for, Check the screenshot below: ', 'woocommerce_gift_cards_lite');
	$mwb_wgc_setting_image = '<img src="'.MWB_WGC_URL.'assets/images/mwb_exclude_product.png">';
}
if( $mwb_wgc_email_setting_tab ){
	$mwb_wgc_about_pro_version = __('From here you may edit your whole Email Template along with the custom messages you want, Check the screenshot below:', 'woocommerce_gift_cards_lite');
	$mwb_wgc_setting_image = '<img src="'.MWB_WGC_URL.'assets/images/mwb_email_template.png">';
}
if( $mwb_wgc_offline_giftcard_tab ){
	$mwb_wgc_about_pro_version = __('What about to provide Gift Cards Manually to someone who do not want to purchase the product online, Check the screenshot below:', 'woocommerce_gift_cards_lite');
	$mwb_wgc_setting_image = '<img src="'.MWB_WGC_URL.'assets/images/mwb_offline_templates.png">';
}
if( $mwb_wgc_export_coupon_tab ){
	$mwb_wgc_about_pro_version = __('Want to export Purchased Gift Cards in CSV format? As well you may Import your Gift Cards in BULK, Check the screenshot below:', 'woocommerce_gift_cards_lite');
	$mwb_wgc_setting_image = '<img src="'.MWB_WGC_URL.'assets/images/mwb_export_import.png">';
}
if( $mwb_wgc_other_add_tab ){
	$mwb_wgc_about_pro_version = __('Here all the required settings can be turned on/off along with PDF version you may enable from here, Check the screenshot below:', 'woocommerce_gift_cards_lite');
	$mwb_wgc_setting_image = '<img src="'.MWB_WGC_URL.'assets/images/mwb_other_setting_tab.png">';
}
if( $mwb_wgc_discount_tab ){
	$mwb_wgc_about_pro_version = __('Want to give some discount on your Gift Card Products? Check the screenshot below:', 'woocommerce_gift_cards_lite');
	$mwb_wgc_setting_image = '<img src="'.MWB_WGC_URL.'assets/images/mwb_discount.png">';
}
if( $mwb_wgc_thankyou_tab ){
	$mwb_wgc_about_pro_version = __('What about to provide some kind of "Thank You Order Gift Cards" to your Loyal Customers who had made some required purchase on your site, Check the screenshot below:', 'woocommerce_gift_cards_lite');
	$mwb_wgc_setting_image = '<img src="'.MWB_WGC_URL.'assets/images/mwb_thankyou.png">';
}
if( $mwb_wgc_qrbarcode_tab ){
	$mwb_wgc_about_pro_version = __('Coupon code can be set in QRCode Or in BarCode Form, Have the look of below Screen-shot: ', 'woocommerce_gift_cards_lite');
	$mwb_wgc_setting_image = '<img src="'.MWB_WGC_URL.'assets/images/qr_bar_code.png">';
}
if( $mwb_wgc_delivery_tab ){
	$mwb_wgc_about_pro_version = __('Want to provide multiple ways to your customer for delivering the Gift Cards? Check the screenshot below: ', 'woocommerce_gift_cards_lite');
	$mwb_wgc_setting_image = '<img src="'.MWB_WGC_URL.'assets/images/mwb_delivery_method.png">';
}
?>
<div class="mwb_wgc_pro_version_wrapper">
	<p class="mwb_wgc_about_pro_version">
		<?php echo $mwb_wgc_about_pro_version;?>
	</p>
	<div class="mwb_wgc_pro_version_image_section">
		<a href="https://codecanyon.net/item/woocommerce-ultimate-gift-card/19191057?s_rank=24"><?php echo $mwb_wgc_setting_image;?></a>
	</div>
	
	<div class="mwb_wgc_get_it_now">
		<a href="https://codecanyon.net/item/woocommerce-ultimate-gift-card/19191057?s_rank=24" class="mwb_wgc_get_it_button">Go for Premium</a>
	</div>
</div>