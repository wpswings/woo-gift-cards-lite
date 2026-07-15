<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

require_once WPS_WGC_DIRPATH . 'admin/partials/template_settings_function/class-woocommerce-giftcard-admin-settings.php';
$settings_obj = new Woocommerce_Giftcard_Admin_Settings();
$wps_wgm_delivery_settings = array(
	array(
		'title'         => esc_html__( 'Enable Email To Recipient', 'woo-gift-cards-lite' ),
		'id'            => 'wps_wgm_email_to_recipient_setting_enable',
		'type'          => 'radio',
		'class'         => 'wps_wgm_send_giftcard',
		'name'          => 'wps_wgm_send_giftcard',
		'value'         => 'Mail to recipient',
		'desc_tip'      => esc_html__( 'Check this box to enable normal functionality for sending mails to recipients on Gift Card Products.', 'woo-gift-cards-lite' ),
		'desc'          => esc_html__( 'Enable Email To Recipient', 'woo-gift-cards-lite' ),
		'default_value' => 1,
	),
	array(
		'title'         => esc_html__( 'Enable Downloadable', 'woo-gift-cards-lite' ),
		'id'            => 'wps_wgm_downladable_setting_enable',
		'type'          => 'radio',
		'name'          => 'wps_wgm_send_giftcard',
		'class'         => 'wps_wgm_send_giftcard',
		'value'         => 'Downloadable',
		'desc_tip'      => esc_html__( 'Check this box to enable the downloadable feature for  Gift Card Products.', 'woo-gift-cards-lite' ),
		'desc'          => esc_html__( 'Enable Downloadable feature', 'woo-gift-cards-lite' ),
		'default_value' => 0,
	),
	array(
		'title'    => esc_html__( 'Allow Free Shipping on Zero Amount', 'woo-gift-cards-lite' ),
		'id'       => 'wps_wgm_delivery_auto_freeshipping_zero_total',
		'type'     => 'checkbox',
		'class'    => 'input-text',
		'desc_tip' => esc_html__( 'When enabled, if a gift card coupon is applied at checkout and the order total becomes $0, free shipping will be automatically enabled on that gift card coupon. After payment is complete, the free shipping will be automatically disabled on the coupon. This prevents orders from failing when gift cards cover the full order amount but shipping remains. Note: The "Free Shipping" setting in General Settings must also be enabled, and a free shipping method must be configured in your shipping zones.', 'woo-gift-cards-lite' ),
		'desc'     => esc_html__( 'Automatically enable free shipping when gift card makes total $0, then disable after payment complete', 'woo-gift-cards-lite' ),
	),
);
 $wps_wgm_delivery_settings = apply_filters( 'wps_wgm_delivery_settings', $wps_wgm_delivery_settings );
