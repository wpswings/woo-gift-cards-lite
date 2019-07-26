 <?php
 $mwb_wgm_product_settings = array(
			array(
			  'label' => __('Exclude Sale Items','woocommerce_gift_cards_lite'),
			  'id' => 'mwb_wgm_product_setting_giftcard_ex_sale',
			  'type' => 'checkbox',
			  'class' => 'input-text',
			  'attribute_description' =>  __('Check this box if the Giftcard Coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are no sale items in the cart.','woocommerce_gift_cards_lite'),
			  'description' =>  __('Enable to exclude Sale Items','woocommerce_gift_cards_lite')
			),					
			array(
			  'label' => __('Exclude Products','woocommerce_gift_cards_lite'), 
			  'id' => 'mwb_wgm_product_setting_exclude_product',
			  'type' => 'search&select',
			  'multiple' => 'multiple',
			  'custom_attribute' => array(
			  		'style' => '"width:50%;"',
			  		'class' => '"wc-product-search"',		
			  		'data-action' => '"woocommerce_json_search_products_and_variations"',
			  		'data-placeholder' => __( 'Search for a product', 'woocommerce_gift_cards_lite' )
			  ),
			  'attribute_description' =>  __('Products which must not be in the cart to use Giftcard coupon or, for "Product Discounts", which products are not discounted.','woocommerce_gift_cards_lite'),
			  'options' => $this->mwb_wgm_get_product("mwb_wgm_product_setting_exclude_product")
			),							
			array(
			  'label' => __('Exclude Product Category','woocommerce_gift_cards_lite'), 
			  'id' => 'mwb_wgm_product_setting_exclude_category',
			  'type' => 'search&select',
			  'multiple' => 'multiple',
			  'attribute_description' =>  __('Product must not be in this category for the Giftcard coupon to remain valid or, for "Product Discounts", products in these categories will not be discounted.','woocommerce_gift_cards_lite'),
			  'options' => $this->mwb_wgm_get_category()
			  
			 )	
		 );
 
 $mwb_wgm_product_settings = apply_filters("mwb_wgm_product_settings",$mwb_wgm_product_settings);