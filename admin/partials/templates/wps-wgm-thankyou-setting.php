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
    <h3 class="wps_wgm_overview_heading wps_wgm_heading">Thankyou Order Settings</h3>
    <div class="wps_wgm_table_wrapper">
        <div class="wps_table">
            <table class="form-table wps_wgm_general_setting">
                <tbody>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_thankyouorder_enable">Want to give ThankYou Gift coupon to your
                                customers ?</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Check this box to enable gift coupon for those customers who had placed orders in your site', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_thankyouorder_enable">
                                <input type="checkbox" name="wps_wgm_thankyouorder_enable"
                                    id="wps_wgm_thankyouorder_enable" class="input-text"> Enable ThankYou Gift Coupon to
                                Customers </label>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_thankyouorder_time">Select the Order Status</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Select the status when the ThankYou Gift Coupon would be send', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <select name="wps_wgm_thankyouorder_time"
                                class="wps_wgm_new_woo_ver_style_select">
                                <option value="wps_wgm_order_creation">Order Creation</option>
                                <option value="wps_wgm_order_processing">Order is in Processing</option>
                                <option value="wps_wgm_order_completed" selected="selected">Order is in Complete
                                </option>

                            </select>

                        </td>
                    </tr>
                    <tr valign="top" class="wps_wgm_thankyouorder_user_roles">			
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_thankyouorder_user_roles">Eligible User Roles</label>		
                        </th>
                        <td class="forminp forminp-text">
                            <span class="woocommerce-help-tip"></span>
                                <select name="wps_wgm_thankyouorder_user_roles[]" id="wps_wgm_thankyouorder_user_roles" multiple="" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true">
                                </select>
                            <span class="select2 select2-container select2-container--default" dir="ltr" style="width: 350px;"><span class="selection"><span class="select2-selection select2-selection--multiple" aria-haspopup="true" aria-expanded="false" tabindex="-1"><ul class="select2-selection__rendered" aria-live="polite" aria-relevant="additions removals" aria-atomic="true"><li class="select2-search select2-search--inline"><input class="select2-search__field" type="text" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="textbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>												
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_thankyouorder_number">Number of Orders, after which the thankyou gift
                                card would be sent</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Enter the number of orders, after that, you want to give a thank you Gift Card to your customers', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_thankyouorder_number">
                                <input type="number" min="1" value="1" name="wps_wgm_thankyouorder_number"
                                    id="wps_wgm_thankyouorder_number" class="input-text wps_wgm_new_woo_ver_style_text">
                            </label>

                        </td>
                    </tr>
                    <tr valign="top" class="wps_wgm_thankyouorder_first_purchase_only">			
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_thankyouorder_first_purchase_only">Only First-Time Buyers</label>		
                        </th>
                        <td class="forminp forminp-text">
                            <span class="woocommerce-help-tip"></span>
                            <label for="wps_wgm_thankyouorder_first_purchase_only">
                                <input type="checkbox" name="wps_wgm_thankyouorder_first_purchase_only" id="wps_wgm_thankyouorder_first_purchase_only" class="input-text">Restrict ThankYou coupon to first-time buyers		
                            </label>                                            
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_thnku_giftcard_expiry">ThankYou Coupon Expiry</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __(
                                    'Enter number of days for Coupon Expiry,  Keep value "1" for one-day expiry 
                        after generating coupon, Keep value "0" for no expiry.',
                                    'woo-gift-cards-lite'
                                );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_thnku_giftcard_expiry">
                                <input type="number" min="0" value="0" name="wps_wgm_thnku_giftcard_expiry"
                                    id="wps_wgm_thnku_giftcard_expiry"
                                    class="input-text wps_wgm_new_woo_ver_style_text"> </label>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_thankyouorder_type">Select ThankYou Gift Coupon Type</label>
                        </th>
                        <td class="forminp forminp-text">
                            <?php
                                $attribute_description = __( 'Choose the ThankYou Gift Coupon Type for Customers', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <select name="wps_wgm_thankyouorder_type"
                                class="wps_wgm_new_woo_ver_style_select">
                                <option value="wps_wgm_fixed_thankyou" selected="selected">Fixed</option>
                                <option value="wps_wgm_percentage_thankyou">Percentage</option>

                            </select>

                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row" class="titledesc">
                            <label for="wps_wgm_thankyou_message">Enter a Thankyou Message for your customers</label>
                        </th>
                        <td class="forminp forminp-text">

                            <label for="wps_wgm_general_text_points" class="wps_wgm_label">
                            <?php
                                $attribute_description = __( 'This message will print inside the Thankyou Gift coupon Template', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <span class="description"></span>
                                <span class="description"></span>
                                <textarea rows="3" name="wps_wgm_thankyou_message" id="wps_wgm_thankyou_message_org"
                                    class="input-text">You have received a coupon [COUPONCODE], having amount of [COUPONAMOUNT] with the expiration date of [COUPONEXPIRY]		</textarea>
                            </label>
                            <p class="description"></p>

                        </td>
                    </tr>
                    <tr valign="top" class="wps_uwgc_thankyouorder_row" id="thankyou_box" style="display: none;">
                        <th>
                            <label for="wps_uwgc_thankyouorder_fields">Enter Coupon Amount within Order Range</label>
                        </th>
                        <td class="forminp forminp-text">
                            <table class="form-table wp-list-table widefat fixed striped">
                                <tbody class="wps_uwgc_thankyouorder_tbody">
                                    <tr valign="top">
                                        <th>Minimum</th>
                                        <th>Maximum</th>
                                        <th>ThankYou Gift Coupon Amount</th>
                                        <th class="wps_uwgc_remove_thankyouorder_content">Action</th>
                                    </tr>
                                </tbody>
                            </table>
                            <input type="button" value="Add More" class="wps_uwgc_add_more button"
                                id="wps_uwgc_add_more">
                        </td>
                    </tr>



                </tbody>
            </table>
        </div>
    </div>
    <p class="submit">
        <input type="submit" value="Save changes" class="wps_wgm_save_button" name="wps_uwgc_save_thankyou_order"
            id="wps_uwgc_save_thankyou_order">
    </p>
    <div class="clear"></div>
</div>