 <?php
require_once MWB_WGC_DIRPATH.'admin/partials/template_settings_function/class-woocommerce_giftcard_admin_settings.php' ;
$settings_obj = new Woocommerce_Giftcard_Admin_settings();
 $mwb_wgm_delivery_settings = array(
			array(
			  'title' => __('Enable Email To Recipient', MWB_WGM_DOMAIN ),
			  'id' => 'mwb_wgm_email_to_recipient_setting_enable',
			  'type' => 'radio',
			  'class' => 'mwb_wgm_send_giftcard',
			  'name' => 'mwb_wgm_send_giftcard',
			  'value' => 'Mail to recipient',
			  'desc_tip' =>  __('Check this box to enable normal functionality for sending mails to recipients on Gift Card Products.', MWB_WGM_DOMAIN ),
			  'desc' =>  __('Enable Email To Recipient.', MWB_WGM_DOMAIN ),
			  'default_value' => 1
			),	
			array(
			  'title' => __('Enable Downloadable', MWB_WGM_DOMAIN ),
			  'id' => 'mwb_wgm_downladable_setting_enable',
			  'type' => 'radio',
			  'name' => 'mwb_wgm_send_giftcard',
			  'class' => 'mwb_wgm_send_giftcard',
			  'value' => 'Downloadable',
			  'desc_tip' =>  __('Check this box to enable downladable feature for  Gift Card Products.', MWB_WGM_DOMAIN ),
			  'desc' =>  __('Enable Downloadable feature', MWB_WGM_DOMAIN ),
			  'default_value' => 0
			)				
			
		);
 $mwb_wgm_delivery_settings = apply_filters("mwb_wgm_delivery_settings",$mwb_wgm_delivery_settings);