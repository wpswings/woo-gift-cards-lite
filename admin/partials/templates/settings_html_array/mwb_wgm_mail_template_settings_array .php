 <?php
 $mail_template_settings = array(
			array(
			  'label' => __('Upload Default Logo','woocommerce_gift_cards_lite'),
			  'id' => 'mwb_gw_other_setting_upload_logo',
			  'type' => 'textWithButton',
			  'attribute_description' =>  __('Upload the image which is used as logo on your Email Template.','woocommerce_gift_cards_lite')
			),					
			array(
			  'label' => __('Logo Dimension','woocommerce_gift_cards_lite'), 
			  'id' => 'mwb_gw_other_setting_upload_logo',
			  'type' => 'numberGroup',
			  'custom_attribute' => array(
			  	 'data-multiple' => 'true',
				  'style' => 'width:50%;',
				  'data-placeholder' => __('Search for a product&hellip;','woocommerce_gift_cards_lite'),
				  'data-action' => 'woocommerce_json_search_products_and_variations')			 
			),							
			array(
			  'label' => __('Email Default Event Image','woocommerce_gift_cards_lite'), 
			  'id' => 'mwb_gw_other_setting_background_logo',
			  'type' => 'textWithButton',
			  'attribute_description' =>  __('Upload image which is used as a default Event/Occasion in Email Template.','woocommerce_gift_cards_lite')
			  
			 ),
			array(
			  'label' => __('Email Background Color','woocommerce_gift_cards_lite'), 
			  'id' => 'mwb_gw_other_setting_background_color',
			  'type' => 'text',
			  'class' => 'mwb_gw_new_woo_ver_style_text',
			  'attribute_description' =>  __('Set the background color of Email Template.','woocommerce_gift_cards_lite')
			  
			 ),
			 
			array(
			  'label' => __('Giftcard Message Length','woocommerce_gift_cards_lite'), 
			  'id' => 'mwb_gw_other_setting_giftcard_message_length',
			  'type' => 'number',
			  'class' => 'input-text mwb_gw_new_woo_ver_style_text',
			  'custom_attribute' => array('min'=> 0),
			  'attribute_description' =>  __('Enter the Gift Card Message length, used to limit the number of characters entered by the customers.','woocommerce_gift_cards_lite')
			  
			 )
		 );
 $mail_template_settings = apply_filters("mail_template_settings",$mail_template_settings);