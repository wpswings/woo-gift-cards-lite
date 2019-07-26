 <?php
 $delivery_settings = array(
			array(
			  'label' => __('Enable Email To Recipient','woocommerce_gift_cards_lite'),
			  'id' => 'mwb_gw_email_to_recipient_setting_enable',
			  'type' => 'radio',
			  'name' => 'mwb_gw_send_giftcard',
			  'value' => 'normal_mail',
			  'class' => 'mwb_gw_send_giftcard',
			  'attribute_description' =>  __('Check this box to enable normal functionality for sending mails to recipients on Gift Card Products.','woocommerce_gift_cards_lite'),
			  'description' =>  __('Enable Email To Recipient.','woocommerce_gift_cards_lite')
			),					
			array(
			  'label' => __('Enable Downloadable','woocommerce_gift_cards_lite'),
			  'id' => 'mwb_gw_downladable_setting_enable',
			  'type' => 'radio',
			  'name' => 'mwb_gw_send_giftcard',
			  'value' => 'download',
			  'class' => 'mwb_gw_send_giftcard',
			  'attribute_description' =>  __('Check this box to enable downladable feature for  Gift Card Products.','woocommerce_gift_cards_lite'),
			  'description' =>  __('Enable Downloadable feature','woocommerce_gift_cards_lite')
			)
		 );