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
    <h3 class="wps_wgm_overview_heading wps_wgm_heading">Discount Settings</h3>
        <div class="wps_table">
            <table class="form-table wps_wgm_general_setting">
                <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_discount_enable">Enable Discount on Gift Card Products</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Check this box to enable Discount for Gift card Products', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_discount_enable">
                                <input type="checkbox" name="wps_wgm_discount_enable" id="wps_wgm_discount_enable"
                                    class="input-text"> Enable Discount on Gift card Products </label>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_discount_type">Select Discount Type</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Choose the Discount Type for Gift Card Products', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <select name="wps_wgm_discount_type"
                                class="wps_wgm_new_woo_ver_style_select">
                                <option value="Fixed" selected="selected">Fixed</option>
                                <option value="Percentage">Percentage</option>

                            </select>

                        </td>
                    </tr>
                    <tr valign="top" class="wps_wgm_discount_row" style="display: none;" id="DiscountBox">
                        <th>
                            <label for="wps_wgm_discount_fields">Enter Discount within Price Range</label>
                        </th>
                        <td class="forminp forminp-text">
                            <table class="form-table wp-list-table widefat fixed striped wps_wgm_discount_table">
                                <tbody class="wps_wgm_discount_tbody">
                                    <tr valign="top">
                                        <th>Minimum</th>
                                        <th>Maximum</th>
                                        <th>Discount Amount</th>
                                        <th class="wps_wgm_remove_discount_content">Action</th>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="button" value="Add More" class="wps_wgm_add_more button wps_ml-35"
                                id="wps_wgm_add_more">
                        </td>
                    </tr>



                </tbody>
            </table>
        </div>
    <p class="submit">
        <input type="submit" value="Save changes" class="wps_wgm_save_button" name="wps_uwgc_save_discount"
            id="wps_uwgc_save_discount">
    </p>
    <div class="clear"></div>
</div>