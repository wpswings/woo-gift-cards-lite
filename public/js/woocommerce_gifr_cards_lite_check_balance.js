(function( $ ) {

	'use strict';
	jQuery( document ).ready(
		function($){
            $( document ).on(
                'click',
                '#wps_check_balance',
                function(){

                    var email = $( '#gift_card_balance_email' ).val();
                    var coupon = $( '#gift_card_code' ).val();

                    $( "#wps_wgm_loader" ).show();
                    var data = {
                        action:'wps_uwgc_check_gift_balance_org',
                        email:email,
                        coupon:coupon,
                        wps_wgm_nonce_check:wps_wgm_check_balance.wps_nonce_check
                    };
                    $.ajax(
                        {
                            url: wps_wgm_check_balance.ajaxurl,
                            type: "POST",
                            data: data,
                            dataType :'json',
                            success: function(response) {
                                console.log(response)
                                $( "#wps_wgm_loader" ).hide();
                                if (response.result == true) {
                                    var html = response.html;
                                    var html_balance = response.html;
                                    $('#wps_myaccount_balance_notice').remove()
                                } else {
                                    var message = response.message;
                                    var html = '<b style="color:red; margin-left:2%">' + message + '</b>';
                                    var html_notice = '<div class="woocommerce-error" id="wps_myaccount_balance_notice" role="alert">'+message+'</div>';
                                }
                                $( "#wps_notification" ).html( html );

                                $('#wps_balance').html(html_balance)
                                $("#wps-check-balamce-error").html(html_notice)
                            }
                        }
                    );
                }
            );

            // PAR compatibility.
            jQuery(document).on('click', '#wps_wgm_redeem_coupon', function(){
                
                jQuery(this).prop('disabled', true);
                jQuery('#wps_wgm_coupon_redeem_notify').html('');
                jQuery('.wps_wgm_coupon_redeem_loader').css( 'display', 'inline-block' );
                var coupon_code = jQuery('#wps_wgm_coupon_redeem_value').val().trim();
                if ( '' == coupon_code ) {

                    jQuery('#wps_wgm_coupon_redeem_notify').show();
                    jQuery('#wps_wgm_coupon_redeem_notify').html('<b style="color:red;">' + wps_wgm_check_balance.empty_msg + '</b>');
                    jQuery(this).prop('disabled', false);
                    jQuery('.wps_wgm_coupon_redeem_loader').hide();
                } else {

                    var data = {
                        'action'              : 'redeem_gift_card_coupon',
                        'wps_wgm_nonce_check' :wps_wgm_check_balance.wps_nonce_check,
                        'coupon_code'         : coupon_code,
                    };

                    jQuery.ajax({
                        'method' : 'POST',
                        'url'    : wps_wgm_check_balance.ajaxurl,
                        'data'   : data,
                        success  : function(response) {
                            jQuery('#wps_wgm_coupon_redeem_notify').show();
                            jQuery('#wps_wgm_redeem_coupon').prop('disabled', false);
                            jQuery('.wps_wgm_coupon_redeem_loader').hide();
                            if ( true == response.result ) {

                                jQuery('#wps_wgm_coupon_redeem_notify').html('<b style="color:green;">' + response.msg + '</b>');
                            } else {

                                jQuery('#wps_wgm_coupon_redeem_notify').html('<b style="color:red;">' + response.msg + '</b>');
                            }
                        }
                    });
                }
            });
        }
    );

})( jQuery );