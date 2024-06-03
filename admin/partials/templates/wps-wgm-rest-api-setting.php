<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to create rest api for viewing and managing giftcard
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Ultimate Woocommerce Gift Cards
 * @subpackage Ultimate Woocommerce Gift Cards/admin/partials/templates/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wps_wgm_content_template_pro_tag">
    <h3 class="wps_wgm_overview_heading wps_wgm_heading">REST API Settings</h3>
    <div class="wps_wgm_table_wrapper">	
        <div class="wps_table">
            <table class="form-table wps-uwgc-giftcard-rest-api">
                <tbody>
                    <form action="" method="POST" > 
                        <div class="wpg-secion-wrap">
                            <div class="wps-form-group wps-form-group2">
                                <div class="wps-form-group__control">
                                    <p>REST API allows external apps to view and manage Giftcard. Access is granted only to those with valid API keys.</p>
                                    <input type="button" class="wps-btn wps_wgm_save_button wps-btn__filled" name="wps_uwgc_generate_api_key"  value="Generate API key">
                                </div>
                            </div>
                        </div>
                    </form>
                </tbody>
            </table>
        </div>
    </div>

	<h3>REST API details</h3>
	<p>
	<strong>Base Url for accessing Gift Card Coupons : </strong>
	{home_url}/wp-json/api/v1/giftcard/
	</p>
	<p>
    Your Base Url : 
	https://your-site.com
	/wp-json/api/v1/giftcard/
	</p>
	<h4>Authentication</h4> 
	<p>
    For authentication you need Consumer Key 
	<strong>consumer_key</strong>
	 and Consumer Secret  
	<strong>consumer_secret </strong>
	keys. Response on wrong api details:
	</p>
    
    <pre>
    {
        "code": "rest_forbidden",
        "message": "Sorry, your key details are incorrect.",
        "data": {
            "status": 401
        }
    }
	</pre>

	<h4>Retrieve GiftCard Coupon Details : </h4> 
	<p><strong><code>POST {home_url}/wp-json/api/v1/giftcard/coupon-details</code></strong><p>
	<p>
    Your Url : 
	https://your-site.com
	/wp-json/api/v1/giftcard/coupon-details
	</p>
	<p>
		Required Parameters : consumer_key, consumer_secret, coupon_code
	</p>
	<p>JSON request example:</p>
	<pre>
    {
        "consumer_key":"XXXXX",
        "consumer_secret":"XXXXX",
        "coupon_code":"wps-uwsqb"
    }
	</pre>
	<p>JSON response example:</p>
    <pre>
    {
        "code": "success",
        "message": "There is Giftcard Coupon Details",
        "remaining_amount": "10",
        "discount_type": "fixed_cart",
        "usage_count": 2,
        "usage_limit": 10,
        "description": "GIFTCARD ORDER #491",
        "coupon_expiry": "15/05/2025"
    }
	</pre>

	<h4>Gift Card Coupon Redeem : </h4> 
	<p><strong><code>POST {home_url}/wp-json/api/v1/giftcard/redeem-coupon</code></strong><p>
	<p>
    Your Url : 
	https://your-site.com
	/wp-json/api/v1/giftcard/redeem-coupon
	</p>
	<p>
		Required Parameters : consumer_key, consumer_secret, coupon_code, redeem_amount
	</p>
	<p>JSON request example:</p>
    <pre>
    {
        "consumer_key":"XXXXX",
        "consumer_secret":"XXXXX",
        "coupon_code":"wps-uwsqb",
        "redeem_amount":5
    }
	</pre>
	<p>JSON response example:</p>
	<pre>
    {
        "code": "success",
        "message": "Coupon is successfully Redeemed",
        "remaining_amount": 12,
        "discount_type": "fixed_cart",
        "usage_count": 3,
        "usage_limit": 10,
        "description": "GIFTCARD ORDER #491",
        "coupon_expiry": "30/05/2024"
    }
	</pre>

	<h4>Gift Card Coupon Recharge/Update Details ( Coupon Amount, Coupon Expiry, Usage Count, Usage Limit ) : </h4> 
	<p><strong><code>POST {home_url}/wp-json/api/v1/giftcard/recharge-coupon</code></strong><p>
	<p>
	Your Url : 
	https://your-site.com
	/wp-json/api/v1/giftcard/recharge-coupon
	</p>
	<p>
		Required Parameters : consumer_key, consumer_secret, coupon_code, recharge_amount
	</p>
	<p>
		Optional Parameters : coupon_expiry( in timestamp ), usage_count, usage_limit
	</p>
	<p>JSON request example:</p>
    <pre>
    {
        "consumer_key":"XXXXX",
        "consumer_secret":"XXXXX",
        "coupon_code":"wps-uwsqb",
        "recharge_amount":6,
        "coupon_expiry":"1717050604",
        "usage_count":2,
        "usage_limit":10
    }
	</pre>
	<p>JSON response example:</p>
    <pre>
    {
        "code": "success",
        "message": "Coupon is successfully Recharged",
        "remaining_amount": 18,
        "discount_type": "fixed_cart",
        "usage_count": 2,
        "usage_limit": 10,
        "description": "GIFTCARD ORDER #491",
        "coupon_expiry": "30/05/2024"
    }
	</pre>
</div>