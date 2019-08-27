<?php
require_once MWB_WGC_DIRPATH.'admin/partials/template_settings_function/class-woocommerce_giftcard_admin_settings.php' ;
$settings_obj = new Woocommerce_Giftcard_Admin_settings();
 $mwb_wgm_other_setting = array(
	array(
	  'title' => __('Disable Apply Coupon Fields',MWB_WGM_DOMAIN),
	  'id' => 'mwb_wgm_additional_apply_coupon_disable',
	  'type' => 'checkbox',
	  'class' => 'input-text',
	  'desc_tip' =>  __('Check this if you want to disable Apply Coupon Fields if there only GifCard Products are in Cart Page',MWB_WGM_DOMAIN),
	  'desc' =>  __('Disable Apply Coupon Fields',MWB_WGM_DOMAIN)
	),
	array(
	  'title' => __('Disable Preview Button',MWB_WGM_DOMAIN),
	  'id' => 'mwb_wgm_additional_preview_disable',
	  'type' => 'checkbox',
	  'class' => 'input-text',
	  'desc_tip' =>  __('Check this if you want to disable Preview Button At Front End',MWB_WGM_DOMAIN),
	  'desc' =>  __('Disable Preview Button',MWB_WGM_DOMAIN)
	)
	);
 $mwb_wgm_other_setting = apply_filters("mwb_wgm_other_setting",$mwb_wgm_other_setting);