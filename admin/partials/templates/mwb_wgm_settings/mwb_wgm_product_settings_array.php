 <?php
require_once MWB_WGC_DIRPATH.'admin/partials/template_settings_function/class-woocommerce_giftcard_admin_settings.php' ;
$settings_obj = new Woocommerce_Giftcard_Admin_settings();
 $mwb_wgm_product_settings = array(
			array(
			  'title' => __('Exclude Sale Items', MWB_WGM_DOMAIN ),
			  'id' => 'mwb_wgm_product_setting_giftcard_ex_sale',
			  'type' => 'checkbox',
			  'class' => 'input-text',
			  'desc_tip' =>  __('Check this box if the Giftcard Coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are no sale items in the cart.', MWB_WGM_DOMAIN ),
			  'desc' =>  __('Enable to exclude Sale Items', MWB_WGM_DOMAIN )
			),					
			array(
			  'title' => __('Exclude Products', MWB_WGM_DOMAIN ), 
			  'id' => 'mwb_wgm_product_setting_exclude_product',
			  'type' => 'search&select',
			  'multiple' => 'multiple',
			  'custom_attribute' => array(
			  		'style' => '"width:50%;"',
			  		'class' => '"wc-product-search"',		
			  		'data-action' => '"woocommerce_json_search_products_and_variations"',
			  		'data-placeholder' => __( 'Search for a product', MWB_WGM_DOMAIN )
			  ),
			  'desc_tip' =>  __('Products which must not be in the cart to use Giftcard coupon or, for "Product Discounts", which products are not discounted.',MWB_WGM_DOMAIN ),
			  'options' => $settings_obj->mwb_wgm_get_product("mwb_wgm_product_setting_exclude_product","mwb_wgm_product_settings")
			),							
			array(
			  'title' => __('Exclude Product Category', MWB_WGM_DOMAIN ), 
			  'id' => 'mwb_wgm_product_setting_exclude_category',
			  'type' => 'search&select',
			  'multiple' => 'multiple',
			  'desc_tip' =>  __('Product must not be in this category for the Giftcard coupon to remain valid or, for "Product Discounts", products in these categories will not be discounted.', MWB_WGM_DOMAIN ),
			  'options' => $settings_obj->mwb_wgm_get_category()
			  
			 )	
		);
 $mwb_wgm_product_settings = apply_filters("mwb_wgm_product_settings",$mwb_wgm_product_settings);