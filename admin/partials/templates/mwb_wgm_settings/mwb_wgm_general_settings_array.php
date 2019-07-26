 <?php
 $mwb_wgm_general_setting = array(
	array(
	  'label' => __('Enable','woocommerce_gift_cards_lite'),
	  'id' => 'mwb_wgm_general_setting_enable',
	  'type' => 'checkbox',
	  'class' => 'input-text',
	  'default' => 'off',
	  'attribute_description' =>  __('Check this box to enable giftcard','woocommerce_gift_cards_lite'),
	  'description' =>  __('Enable WooCommerce Gift Card','woocommerce_gift_cards_lite')
	),					
	array(
	  'label' => __('Enable Tax','woocommerce_gift_cards_lite'), 
	  'id' => 'mwb_wgm_general_setting_tax_cal_enable',
	  'type' => 'checkbox',
	  'default' => 'off',
	  'class' => 'input-text',
	  'attribute_description' =>  __('Check this box to enable tax for giftcard product.','woocommerce_gift_cards_lite'),
	  'description' => __('Enable Tax Calculation for Gift Card','woocommerce_gift_cards_lite')
	),							
	array(
	  'label' => __('Enable Listing Shop Page','woocommerce_gift_cards_lite'), 
	  'id' => 'mwb_wgm_general_setting_shop_page_enable',
	  'default' => 'off',
	  'type' => 'checkbox',
	  'class' => 'input-text',
	  'attribute_description' =>  __('Check this box to enable giftcard product listing on shop page.', 'giftware','woocommerce_gift_cards_lite'),
	  'description' => __('Enable Giftcard Product listing on shop page','woocommerce_gift_cards_lite')
	 ),		
	array(
	  'label' => __('Individual Use','woocommerce_gift_cards_lite'), 
	  'id' => 'mwb_wgm_general_setting_giftcard_individual_use',
	  'type' => 'checkbox',
	  'default' => 'no',
	  'class' => 'input-text',
	  'attribute_description' =>  __('Check this box if the Giftcard Coupon cannot be used in conjunction with other Giftcards/Coupons.','woocommerce_gift_cards_lite'),
	  'description' => __('Allow Giftcard to use Individually','woocommerce_gift_cards_lite')
	),										
	array(
	  'label' => __('Free Shipping','woocommerce_gift_cards_lite'), 
	  'id' => 'mwb_wgm_general_setting_giftcard_freeshipping',
	  'type' => 'checkbox',
	  'default' => 'no',
	  'class' => 'input-text',
	  'attribute_description' =>  __('Check this box if the coupon grants free shipping. A free shipping method must be enabled in your shipping zone and be set to require "a valid free shipping coupon" (see the "Free Shipping Requires" setting).','woocommerce_gift_cards_lite'),
	  'description' => __('Allow Giftcard on Free Shipping','woocommerce_gift_cards_lite')
	),	
	array(
	  'label' => __('Giftcard Coupon Length','woocommerce_gift_cards_lite'), 
	  'id' => 'mwb_wgm_general_setting_giftcard_coupon_length',
	  'type' => 'number',
	  'custom_attribute' => array( 'min' => '"5"','max' => '"10"'),			 
	  'class' => 'input-text mwb_wgm_new_woo_ver_style_text',
	  'attribute_description' =>  __('Enter giftcard coupon length excluding the prefix.(Minimum length is set to 5)','woocommerce_gift_cards_lite')
	),		
	array(
	  'label' => __('Giftcard Prefix','woocommerce_gift_cards_lite'), 
	  'id' => 'mwb_wgm_general_setting_giftcard_prefix',
	  'type' => 'text',
	  'class' => 'input-text mwb_wgm_new_woo_ver_style_text',
	  'style' => 'width:160px',
	  'attribute_description' => __('Enter Gift Card Prefix. Ex: PREFIX_CODE', 'woocommerce_gift_cards_lite')			 
	 ),		
	array(
	  'label' => __('Giftcard Expiry After Days','woocommerce_gift_cards_lite'), 
	  'id' => 'mwb_wgm_general_setting_giftcard_expiry',
	  'type' => 'number',
	  'custom_attribute' => array( 'min' => '0'),		
	  'class' => 'input-text mwb_wgm_new_woo_ver_style_text',
	  'attribute_description' => __('Enter number of days after purchased Giftcard is expired. Keep value "1" for one day expiry when order is completed. Keep value "0" for no expiry.', 'woocommerce_gift_cards_lite'),
	 ),	
	array(
	  'label' => __('Minimum Spend','woocommerce_gift_cards_lite'), 
	  'id' => 'mwb_wgm_general_setting_giftcard_minspend',
	  'type' => 'number',
	  'custom_attribute' => array( 'min' => '0'),		
	  'class' => 'input-text mwb_wgm_new_woo_ver_style_text',
	  'attribute_description' => __('This field allows you to set the minimum spend (subtotal, including taxes) allowed to use the Giftcard coupon.','woocommerce_gift_cards_lite')
	),
	array(
	  'label' => __('Maximum Spend','woocommerce_gift_cards_lite'), 
	  'id' => 'mwb_wgm_general_setting_giftcard_maxspend',
	  'type' => 'number',
	  'custom_attribute' => array( 'min' => '0'),
	  'class' => 'input-text mwb_wgm_new_woo_ver_style_text',
	  'attribute_description' => __('This field allows you to set the maximum spend (subtotal, including taxes) allowed when using the Giftcard coupon.','woocommerce_gift_cards_lite')
	),		
	array(
	  'label' => __('Giftcard No of time usage','woocommerce_gift_cards_lite'), 
	  'id' => 'mwb_wgm_general_setting_giftcard_use',
	  'type' => 'number',
	  'custom_attribute' => array( 'min' => '0'),
	  'class' => 'input-text mwb_gw_new_woo_ver_style_text',
	  'attribute_description' => __('How many times this coupon can be used before Giftcard is void.', 'woocommerce_gift_cards_lite')
	)
	);
 $mwb_wgm_general_setting = apply_filters("mwb_wgm_general_setting",$mwb_wgm_general_setting);