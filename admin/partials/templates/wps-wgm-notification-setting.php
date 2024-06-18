<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wps_wgm_content_template_pro_tag">
    <h3 class="wps_wgm_overview_heading wps_wgm_heading">Notification Settings</h3>
    <div class="wps_wgm_table_wrapper">
        <div class="wps_table">
            <table class="form-table wps_wgm_general_setting">
                <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_share_pdf_link">Enable PDF Link</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Check this box to enable pdf link sharing', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_share_pdf_link">
                                <input type="checkbox" name="wps_wgm_share_pdf_link" id="wps_wgm_share_pdf_link"
                                    class="input-text"> Enable PDF Link Sharing ( First you have to enable pdf feature
                                in other setting tab ) </label>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_share_on_whatsapp">Enable Whatsapp Sharing</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Check this box to enable WhatsApp sharing', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_share_on_whatsapp">
                                <input type="checkbox" name="wps_wgm_share_on_whatsapp" id="wps_wgm_share_on_whatsapp"
                                    class="input-text"> Enable Whatsapp Sharing Notification </label>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_whatsapp_message">Message Content</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Write the message you want to send to the user', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <span class="description"></span>
                            <label for="wps_wgm_general_text_points" class="wps_wgm_label">
                                <textarea rows="7" name="wps_wgm_whatsapp_message" id="wps_wgm_whatsapp_message"
                                    class="input-text">Hello [TO],
									[MESSAGE] 
									You have received a gift card from [FROM]
									Coupon code : [COUPONCODE]
									Amount : [AMOUNT]
									Expiry Date : [EXPIRYDATE]		</textarea>
                            </label>
                            <p class="description">Use [TO],[FROM],[MESSAGE],[COUPONCODE],[AMOUNT],[EXPIRYDATE]
                                shortcodes to be placed dynamically</p>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_enable_sms_notification">Enable SMS Notification</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Check this box to enable SMS Notification', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_enable_sms_notification">
                                <input type="checkbox" name="wps_wgm_enable_sms_notification"
                                    id="wps_wgm_enable_sms_notification" class="input-text"> Enable SMS Notification on
                                Phone </label>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for=""></label>
                        </th>
                        <td class="forminp forminp-text">
                        </td>
                    </tr>
                    <tr valign="top">
                    </tr>
                </tbody>
            </table>
            <table class="form-table wp-list-table widefat fixed striped">
                <tbody class="twilo_credentials" style="display: none;">
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_gw_notice">Notice </label>
                        </th>
                        <td>
                            <p>To view Twilio API credentials visit&nbsp;<a
                                    href="https://www.twilio.com/user/account/voice-sms-mms">Twilio Website</a></p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_account_sid">Account SID</label>
                        </th>
                        <td class="forminp forminp-text">
                            <span class="woocommerce-help-tip" aria-label="Enter a valid Twilio Account SID"></span>
                            <input type="text" value="" name="wps_wgm_account_sid" id="wps_wgm_account_sid"
                                class="input-text wps_gw_new_woo_ver_style_text">
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_auth_token">Account Auth Token</label>
                        </th>
                        <td class="forminp forminp-text">
                            <span class="woocommerce-help-tip" aria-label="Enter valid Auth Token"></span> <input
                                type="text" value="" name="wps_wgm_auth_token" id="wps_wgm_auth_token"
                                class="input-text wps_gw_new_woo_ver_style_text">
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_twilio_number">Twilio Number</label>
                        </th>
                        <td class="forminp forminp-text">
                            <span class="woocommerce-help-tip"
                                aria-label="Enter a valid Twilio number to send messages from."></span> <input
                                type="text" value="" name="wps_wgm_twilio_number" id="wps_wgm_twilio_number"
                                class="input-text wps_gw_new_woo_ver_style_text">
                            <p>Enter a valid twilio number to send messages from. To Buy a Twilio Number&nbsp;<a
                                    href="https://www.twilio.com/console/phone-numbers/search">Click</a>&nbsp;Here</p>
                        </td>
                    </tr>
                </tbody>
            </table>






        </div>
    </div>
    <p class="submit">
        <input type="submit" value="Save changes" class="wps_wgm_save_button" name="wps_uwgc_save_sms_notifiication"
            id="wps_uwgc_save_sms_notifiication">
    </p>
</div>