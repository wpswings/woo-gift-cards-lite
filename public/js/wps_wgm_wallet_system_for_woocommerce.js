(function( $ ) {

	'use strict';
	jQuery( document ).ready(
		function($){
            $( 'body' ).on( 'click', '#wps_recharge_wallet_giftcard',
                function() {
                    $( '.error' ).hide();
                    var wps_gc_code = $( '#wps_giftcard_code' ).val();
                    var wps_wgm_nonce = wps_wgm.wps_wgm_nonce;
                    $.ajax({
                        url: wps_wgm.ajaxurl,
                        type: 'POST',
                        data: { wps_gc_code : wps_gc_code, wps_wgm_nonce: wps_wgm_nonce, action: 'wps_recharge_wallet_via_giftcard' },
                        dataType: 'json',
                        success: function( response ) {
                            if ( response['status'] == 'success' ){
                                $( '.success' ).css( 'color', 'green' );
                                $( '.success' ).html( 'Wallet Recharge with amount of ' + wps_wgm.wps_currency + response['message'] );
                                setTimeout(location.reload.bind(location), 3000);
                            } else if( response['status'] == 'failed' ) {
                                $( '.error' ).show();
                                $( '.error' ).html( response['message'] );
                            }
                        }
                    });
                }
            );

            $( '#wps_giftcard_code' ).keyup(function() {
                $( '.error' ).hide();
            });
        }
    );

})( jQuery );
    