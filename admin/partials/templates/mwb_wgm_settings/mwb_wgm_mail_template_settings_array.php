<?php
require_once MWB_WGC_DIRPATH . 'admin/partials/template_settings_function/class-woocommerce_giftcard_admin_settings.php';
$settings_obj = new Woocommerce_Giftcard_Admin_settings();
 $mwb_wgm_mail_template_settings = array(
	 'top' => array(
		 array(
			 'title' => esc_html__( 'Upload Default Logo', 'woocommerce_gift_cards_lite' ),
			 'id' => 'mwb_wgm_mail_setting_upload_logo',
			 'type' => 'textWithButton',
			 'custom_attribute' => array(
				 array(
					 'type' => 'text',
					 'custom_attributes' => array( 'readonly' => 'readonly' ),
					 'class' => 'mwb_wgm_mail_setting_upload_logo_value mwb_wgm_new_woo_ver_style_text',
					 'id' => 'mwb_wgm_mail_setting_upload_logo',
				 ),
				 array(
					 'type' => 'button',
					 'value' => esc_html__( 'Upload Logo', 'woocommerce_gift_cards_lite' ),
					 'class' => 'mwb_wgm_mail_setting_upload_logo button',
				 ),
				 array(
					 'type' => 'paragraph',
					 'id' => 'mwb_wgm_mail_setting_remove_logo',
					 'imgId' => 'mwb_wgm_mail_setting_upload_image',
					 'spanX' => 'mwb_wgm_mail_setting_remove_logo_span',
				 ),
			 ),
			 'class' => 'mwb_wgm_mail_setting_upload_logo_value mwb_wgm_new_woo_ver_style_text',
			 'desc_tip' => esc_html__( 'Upload the image which is used as logo on your Email Template.', 'woocommerce_gift_cards_lite' ),
		 ),
		 array(
			 'title' => esc_html__( 'Logo Height (in "px")', 'woocommerce_gift_cards_lite' ),
			 'id' => 'mwb_wgm_mail_setting_upload_logo_dimension_height',
			 'type' => 'number',
			 'default' => 70,
			 'class' => 'mwb_wgm_new_woo_ver_style_text',
			 'desc_tip' => esc_html__( 'Set the height of logo in email template.', 'woocommerce_gift_cards_lite' ),
		 ),
		 array(
			 'title' => esc_html__( 'Logo Width (in "px")', 'woocommerce_gift_cards_lite' ),
			 'id' => 'mwb_wgm_mail_setting_upload_logo_dimension_width',
			 'type' => 'number',
			 'default' => 70,
			 'class' => 'mwb_wgm_new_woo_ver_style_text',
			 'desc_tip' => esc_html__( 'Set the width of logo in email template.', 'woocommerce_gift_cards_lite' ),
		 ),
		 array(
			 'title' => esc_html__( 'Email Default Event Image', 'woocommerce_gift_cards_lite' ),
			 'id' => 'mwb_wgm_mail_setting_background_logo',
			 'type' => 'textWithButton',
			 'desc_tip' => esc_html__( 'Upload image which is used as a default Event/Occasion in Email Template.', 'woocommerce_gift_cards_lite' ),
			 'custom_attribute' => array(
				 array(
					 'type' => 'text',
					 'custom_attributes' => array( 'readonly' => 'readonly' ),
					 'class' => 'mwb_wgm_mail_setting_background_logo_value',
					 'id' => 'mwb_wgm_mail_setting_background_logo_value',
				 ),
				 array(
					 'type' => 'button',
					 'value' => esc_html__( 'Upload Image', 'woocommerce_gift_cards_lite' ),
					 'class' => 'mwb_wgm_mail_setting_background_logo button',
				 ),
				 array(
					 'type' => 'paragraph',
					 'id' => 'mwb_wgm_mail_setting_remove_background',
					 'imgId' => 'mwb_wgm_mail_setting_background_logo_image',
					 'spanX' => 'mwb_wgm_mail_setting_remove_background_span',
				 ),
			 ),
		 ),
		 array(
			 'title' => esc_html__( 'Giftcard Message Length', 'woocommerce_gift_cards_lite' ),
			 'id' => 'mwb_wgm_mail_setting_giftcard_message_length',
			 'type' => 'number',
			 'default' => 300,
			 'class' => 'input-text mwb_wgm_new_woo_ver_style_text',
			 'custom_attribute' => array( 'min' => 0 ),
			 'desc_tip' => esc_html__( 'Enter the Gift Car d Message length, used to limit the number of characters entered by the customers.', 'woocommerce_gift_cards_lite' ),

		 ),
		 array(
			 'title' => esc_html__( 'Disclaimer Text', 'woocommerce_gift_cards_lite' ),
			 'id' => 'mwb_wgm_mail_setting_disclaimer',
			 'type' => 'wp_editor',
			 'desc_tip' => esc_html__( 'Set the Disclaimer Text for Email Template.', 'woocommerce_gift_cards_lite' ),
		 ),
	 ),
	 'middle' => array(
		 array(
			 'title' => esc_html__( 'Giftcard Email Subject', 'woocommerce_gift_cards_lite' ),
			 'id' => 'mwb_wgm_mail_setting_giftcard_subject',
			 'type' => 'textWithDesc',
			 'class' => 'description',
			 'desc_tip' => esc_html__( 'Email Subject for notifying receiver about Giftcard Mail send.', 'woocommerce_gift_cards_lite' ),
			 'bottom_desc' => esc_html__( 'Use [SITENAME] shortcode as the name of the site and [BUYEREMAILADDRESS] shortcode as buyer email address to be placed dynamically.', 'woocommerce_gift_cards_lite' ),
		 ),
	 ),
 );
 $mwb_wgm_mail_template_settings = apply_filters( 'mwb_wgm_mail_template_settings', $mwb_wgm_mail_template_settings );
