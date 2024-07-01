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
    <h3 class="wps_wgm_overview_heading wps_wgm_heading">QR/BAR Code Settings</h3>
    <div class="wps_wgm_table_wrapper">
        <div class="wps_table">
            <table class="form-table wps_wgm_general_setting">
                <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_qrcode_setting_enable">Enable QRCode</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Check this box to enable QRCode. QRCode will be displayed instead of coupon Code', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_qrcode_setting_enable">
                                <input value="qrcode" type="radio" name="wps_wgm_qrcode_enable"
                                    id="wps_wgm_qrcode_setting_enable" class="input-text"> Enable QRCode to display in
                                Email Template </label>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_qrcode_display_enable">Display Coupon Code</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Check this box to display Coupon Code below Qrcode.', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_qrcode_display_enable">
                                <input type="checkbox" name="wps_wgm_qrcode_display_enable"
                                    id="wps_wgm_qrcode_display_enable" class="input-text"> Enable this to display Coupon
                                Code below Qrcode </label>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_qrcode_ecc_level">Select ECC Level</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'ECC (Error Correction Capability) level. This compensates for dirt, damage or fuzziness of the barcode.', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <select name="wps_wgm_qrcode_ecc_level"
                                class="wps_wgm_new_woo_ver_style_select">
                                <option value="L" selected="selected">L-Smallest</option>
                                <option value="M">M</option>
                                <option value="Q">Q</option>
                                <option value="H">H-Best</option>

                            </select>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_qrcode_size">Size of QR Code</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'It is the Size of QR Code', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_qrcode_size">
                                <input type="number" min="1" value="3" name="wps_wgm_qrcode_size"
                                    id="wps_wgm_qrcode_size" class="input-text wps_wgm_new_woo_ver_style_text"> </label>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_qrcode_margin">Margin of QR Code</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'It is the Margin of QR Code', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_qrcode_margin">
                                <input type="number" min="1" value="4" name="wps_wgm_qrcode_margin"
                                    id="wps_wgm_qrcode_margin" class="input-text wps_wgm_new_woo_ver_style_text">
                            </label>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_barcode_enable">Enable Barcode</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Check this box to enable Barcode. A QR code will be displayed instead of a coupon code.', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_barcode_enable">
                                <input value="barcode" type="radio" name="wps_wgm_qrcode_enable"
                                    id="wps_wgm_barcode_enable" class="input-text"> Enable Barcode to display in Email
                                Template </label>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_barcode_display_enable">Display Code</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Check this box to display Coupon Code below Barcode.', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_barcode_display_enable">
                                <input type="checkbox" name="wps_wgm_barcode_display_enable"
                                    id="wps_wgm_barcode_display_enable" class="input-text"> Enable this to display
                                Coupon Code </label>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_barcode_codetype">Select Code type</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'It is the Code Type of Barcode', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <select name="wps_wgm_barcode_codetype"
                                class="wps_wgm_new_woo_ver_style_select">
                                <option value="code39" selected="selected">code39</option>
                                <option value="code25">code25</option>
                                <option value="codabar">codabar</option>
                                <option value="code128">code128</option>
                                <option value="code128a">code128a</option>
                                <option value="code128b">code128b</option>

                            </select>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_barcode_size">Size of bar Code</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'It is the Size of Barcode', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_barcode_size">
                                <input type="number" min="1" value="40" name="wps_wgm_barcode_size"
                                    id="wps_wgm_barcode_size" class="input-text wps_wgm_new_woo_ver_style_text">
                            </label>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="wps_wgm_button_wrapper">
        <p class="submit">
            <input type="submit" value="Save changes" class="wps_wgm_save_button" name="wps_uwgc_save_qrcode"
                id="wps_uwgc_save_qrcode_org">
                <input type="submit" value="Reset" class="button-primary woocommerce-reset-button"
            name="wps_uwgc_qrcode_reset_save" id="wps_uwgc_qrcode_setting_save">
        </p>
      
    </div>
    <div class="clear"></div>
</div>