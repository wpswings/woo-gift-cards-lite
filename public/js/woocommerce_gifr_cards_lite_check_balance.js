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
        }
    );

})( jQuery );