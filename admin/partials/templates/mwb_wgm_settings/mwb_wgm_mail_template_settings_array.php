 <?php
require_once MWB_WGC_DIRPATH.'admin/partials/template_settings_function/class-woocommerce_giftcard_admin_settings.php' ;
$settings_obj = new Woocommerce_Giftcard_Admin_settings();
 $mwb_wgm_mail_template_settings = array(
	'top' =>array(
				array(
				  'title' => __('Upload Default Logo', MWB_WGM_DOMAIN ),
				  'id' => 'mwb_wgm_mail_setting_upload_logo',
				  'type' => 'textWithButton',
				  'custom_attribute' => array(
				  		array(
				  			'type' => 'text',
				  			'custom_attributes' => array('readonly'=>'readonly'),
				  			 'class' => 'mwb_wgm_mail_setting_upload_logo_value mwb_wgm_new_woo_ver_style_text',
				  			  'id' => 'mwb_wgm_mail_setting_upload_logo',
				  		),
				  		array(
				  			'type' => 'button',
				  			'value' => __('Upload Logo', MWB_WGM_DOMAIN ),
				  			'class' => 	'mwb_wgm_mail_setting_upload_logo button'		  		
				  		),
				  		array(
				  			'type' => 'paragraph',
				  			'id' => 'mwb_wgm_mail_setting_remove_logo',
				  			'imgId' => 'mwb_wgm_mail_setting_upload_image',
				  			'spanX' => 'mwb_wgm_mail_setting_remove_logo_span'	  		
				  		)
				  ),
				  'class' => 'mwb_wgm_mail_setting_upload_logo_value mwb_wgm_new_woo_ver_style_text',
				  'desc_tip' =>  __('Upload the image which is used as logo on your Email Template.', MWB_WGM_DOMAIN )
				),					
				array(
				  'title' => __('Logo Height (in "px")', MWB_WGM_DOMAIN ), 
				  'id' => 'mwb_wgm_mail_setting_upload_logo_dimension_height',
				  'type' => 'number',
				  'default' => 70,
				  'class' => 'mwb_wgm_new_woo_ver_style_text',
				  'desc_tip' =>  __('Set the height of logo in email template.', MWB_WGM_DOMAIN ),
				),
				array(
				  'title' => __('Logo Width (in "px")', MWB_WGM_DOMAIN ), 
				  'id' => 'mwb_wgm_mail_setting_upload_logo_dimension_width',
				  'type' => 'number',
				  'default' => 70,
				  'class' => 'mwb_wgm_new_woo_ver_style_text',
				  'desc_tip' =>  __('Set the width of logo in email template.', MWB_WGM_DOMAIN ),
				),							
				array(
				  'title' => __('Email Default Event Image', MWB_WGM_DOMAIN ), 
				  'id' => 'mwb_wgm_mail_setting_background_logo',
				  'type' => 'textWithButton',
				  'desc_tip' =>  __('Upload image which is used as a default Event/Occasion in Email Template.', MWB_WGM_DOMAIN ),
				  'custom_attribute' => array(
				  		array(
				  			'type' => 'text',
				  			'custom_attributes' => array('readonly'=>'readonly'),
				  			 'class' => 'mwb_wgm_mail_setting_background_logo_value',
				  			  'id' => 'mwb_wgm_mail_setting_background_logo_value',
				  		),
				  		array(
				  			'type' => 'button',
				  			'value' => __('Upload Image', MWB_WGM_DOMAIN ),
				  			'class' => 	'mwb_wgm_mail_setting_background_logo button'		  		
				  		),
				  		array(
				  			'type' => 'paragraph',
				  			'id' => 'mwb_wgm_mail_setting_remove_background',
				  			'imgId' => 'mwb_wgm_mail_setting_background_logo_image',
				  			'spanX' => 'mwb_wgm_mail_setting_remove_background_span'	  		
				  		)
				  	)			  
				 ),				 
				array(
				  'title' => __('Giftcard Message Length', MWB_WGM_DOMAIN ), 
				  'id' => 'mwb_wgm_mail_setting_giftcard_message_length',
				  'type' => 'number',
				  'default' => 300,
				  'class' => 'input-text mwb_wgm_new_woo_ver_style_text',
				  'custom_attribute' => array('min'=> 0),
				  'desc_tip' =>  __('Enter the Gift Car d Message length, used to limit the number of characters entered by the customers.', MWB_WGM_DOMAIN )
				  
				 ),
				array(
				  'title' => __('Disclaimer Text', MWB_WGM_DOMAIN ), 
				  'id' => 'mwb_wgm_mail_setting_disclaimer',
				  'type' => 'wp_editor',
				  'desc_tip' =>  __('Set the Disclaimer Text for Email Template.', MWB_WGM_DOMAIN )
				)
			),
	'middle' => array(
					array(
					  'title' => __('Giftcard Email Subject', MWB_WGM_DOMAIN ), 
					  'id' => 'mwb_wgm_mail_setting_giftcard_subject',
					  'type' => 'textWithDesc',
					  'class' => 'description',
					  'desc_tip' =>  __('Email Subject for notifying receiver about Giftcard Mail send.', MWB_WGM_DOMAIN ),
					  'bottom_desc' => __( 'Use [SITENAME] shortcode as the name of the site and [BUYEREMAILADDRESS] shortcode as buyer email address to be placed dynamically.', MWB_WGM_DOMAIN )
					)
				)
		);
 $mwb_wgm_mail_template_settings = apply_filters("mwb_wgm_mail_template_settings",$mwb_wgm_mail_template_settings);