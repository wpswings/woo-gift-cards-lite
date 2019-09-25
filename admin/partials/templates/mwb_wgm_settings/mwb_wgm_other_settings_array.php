<?php
require_once MWB_WGC_DIRPATH . 'admin/partials/template_settings_function/class-woocommerce_giftcard_admin_settings.php';
$settings_obj = new Woocommerce_Giftcard_Admin_settings();
 $mwb_wgm_other_setting = array(
	 array(
		 'title' => esc_html__( 'Disable Apply Coupon Fields', 'woocommerce_gift_cards_lite' ),
		 'id' => 'mwb_wgm_additional_apply_coupon_disable',
		 'type' => 'checkbox',
		 'class' => 'input-text',
		 'desc_tip' => esc_html__( 'Check this if you want to disable Apply Coupon Fields if there only GifCard Products are in Cart/Checkout Page', 'woocommerce_gift_cards_lite' ),
		 'desc' => esc_html__( 'Disable Apply Coupon Fields on Cart/Checkout page', 'woocommerce_gift_cards_lite' ),
	 ),
	 array(
		 'title' => esc_html__( 'Disable Preview Button', 'woocommerce_gift_cards_lite' ),
		 'id' => 'mwb_wgm_additional_preview_disable',
		 'type' => 'checkbox',
		 'class' => 'input-text',
		 'desc_tip' => esc_html__( 'Check this if you want to disable Preview Button At Front End', 'woocommerce_gift_cards_lite' ),
		 'desc' => esc_html__( 'Disable Preview Button At Front End', 'woocommerce_gift_cards_lite' ),
	 ),
 );
 $mwb_wgm_other_setting = apply_filters( 'mwb_wgm_other_setting', $mwb_wgm_other_setting );
