 <?php
require_once MWB_WGC_DIRPATH.'admin/partials/template_settings_function/class-woocommerce_giftcard_admin_settings.php' ;
$settings_obj = new Woocommerce_Giftcard_Admin_settings();
 $mwb_wgm_general_setting = array(
	array(
	  'title' => __('Enable',MWB_WGM_DOMAIN),
	  'id' => 'mwb_wgm_general_setting_enable',
	  'type' => 'checkbox',
	  'class' => 'input-text',
	  'desc_tip' =>  __('Check this box to enable giftcard',MWB_WGM_DOMAIN),
	  'desc' =>  __('Enable WooCommerce Gift Card',MWB_WGM_DOMAIN)
	),					
	array(
	  'title' => __('Enable Tax', MWB_WGM_DOMAIN ), 
	  'id' => 'mwb_wgm_general_setting_tax_cal_enable',
	  'type' => 'checkbox',
	  'class' => 'input-text',
	  'desc_tip' =>  __('Check this box to enable tax for giftcard product.', MWB_WGM_DOMAIN ),
	  'desc' => __('Enable Tax Calculation for Gift Card', MWB_WGM_DOMAIN )
	),							
	array(
	  'title' => __('Enable Listing Shop Page', MWB_WGM_DOMAIN ), 
	  'id' => 'mwb_wgm_general_setting_shop_page_enable',
	  'type' => 'checkbox',
	  'class' => 'input-text',
	  'desc_tip' =>  __('Check this box to enable giftcard product listing on shop page.', 'giftware', MWB_WGM_DOMAIN ),
	  'desc' => __('Enable Giftcard Product listing on shop page', MWB_WGM_DOMAIN )
	 ),		
	array(
	  'title' => __('Individual Use', MWB_WGM_DOMAIN ), 
	  'id' => 'mwb_wgm_general_setting_giftcard_individual_use',
	  'type' => 'checkbox',
	  'class' => 'input-text',
	  'desc_tip' =>  __('Check this box if the Giftcard Coupon cannot be used in conjunction with other Giftcards/Coupons.', MWB_WGM_DOMAIN ),
	  'desc' => __('Allow Giftcard to use Individually', MWB_WGM_DOMAIN )
	),										
	array(
	  'title' => __('Free Shipping', MWB_WGM_DOMAIN ), 
	  'id' => 'mwb_wgm_general_setting_giftcard_freeshipping',
	  'type' => 'checkbox',
	  'class' => 'input-text',
	  'desc_tip' =>  __('Check this box if the coupon grants free shipping. A free shipping method must be enabled in your shipping zone and be set to require "a valid free shipping coupon" (see the "Free Shipping Requires" setting).', MWB_WGM_DOMAIN ),
	  'desc' => __('Allow Giftcard on Free Shipping', MWB_WGM_DOMAIN )
	),	
	array(
	  'title' => __('Giftcard Coupon Length', MWB_WGM_DOMAIN ), 
	  'id' => 'mwb_wgm_general_setting_giftcard_coupon_length',
	  'type' => 'number',
	  'custom_attribute' => array( 'min' => '"5"','max' => '"10"'),			 
	  'class' => 'input-text mwb_wgm_new_woo_ver_style_text',
	  'default' => 5,
	  'desc_tip' =>  __('Enter giftcard coupon length excluding the prefix.(Minimum length is set to 5)', MWB_WGM_DOMAIN )
	),		
	array(
	  'title' => __('Giftcard Prefix', MWB_WGM_DOMAIN ), 
	  'id' => 'mwb_wgm_general_setting_giftcard_prefix',
	  'type' => 'text',
	  'class' => 'input-text mwb_wgm_new_woo_ver_style_text',
	  'style' => 'width:160px',
	  'desc_tip' => __('Enter Gift Card Prefix. Ex: PREFIX_CODE', MWB_WGM_DOMAIN )			 
	 ),		
	array(
	  'title' => __('Giftcard Expiry After Days', MWB_WGM_DOMAIN ), 
	  'id' => 'mwb_wgm_general_setting_giftcard_expiry',
	  'type' => 'number',
	  'custom_attribute' => array( 'min' => '0'),		
	  'class' => 'input-text mwb_wgm_new_woo_ver_style_text',
	  'desc_tip' => __('Enter number of days after purchased Giftcard is expired. Keep value "1" for one day expiry when order is completed. Keep value "0" for no expiry.', MWB_WGM_DOMAIN ),
	 ),	
	array(
	  'title' => __('Minimum Spend', MWB_WGM_DOMAIN ), 
	  'id' => 'mwb_wgm_general_setting_giftcard_minspend',
	  'type' => 'number',
	  'custom_attribute' => array( 'min' => '0'),		
	  'class' => 'input-text mwb_wgm_new_woo_ver_style_text',
	  'desc_tip' => __('This field allows you to set the minimum spend (subtotal, including taxes) allowed to use the Giftcard coupon.', MWB_WGM_DOMAIN )
	),
	array(
	  'title' => __('Maximum Spend', MWB_WGM_DOMAIN ), 
	  'id' => 'mwb_wgm_general_setting_giftcard_maxspend',
	  'type' => 'number',
	  'custom_attribute' => array( 'min' => '0'),
	  'class' => 'input-text mwb_wgm_new_woo_ver_style_text',
	  'desc_tip' => __('This field allows you to set the maximum spend (subtotal, including taxes) allowed when using the Giftcard coupon.', MWB_WGM_DOMAIN )
	),		
	array(
	  'title' => __('Giftcard No of time usage', MWB_WGM_DOMAIN ), 
	  'id' => 'mwb_wgm_general_setting_giftcard_use',
	  'type' => 'number',
	  'default' => 1,
	  'custom_attribute' => array( 'min' => '1'),
	  'class' => 'input-text mwb_gw_new_woo_ver_style_text',
	  'desc_tip' => __('How many times this coupon can be used before Giftcard is void.', MWB_WGM_DOMAIN )
	)
	);
 $mwb_wgm_general_setting = apply_filters("mwb_wgm_general_setting",$mwb_wgm_general_setting);