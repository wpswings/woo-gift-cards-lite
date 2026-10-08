/**
 * My Account > My Gift Cards: copy code and resend email.
 *
 * @package woo-gift-cards-lite
 */
(function () {
	'use strict';

	var settings = window.wps_wgm_my_gift_cards;
	if ( ! settings ) {
		return;
	}

	document.addEventListener( 'click', function ( event ) {
		var copy = event.target.closest( '.wps-wgm-mgc-copy' );
		if ( copy && navigator.clipboard ) {
			var label = copy.textContent;
			navigator.clipboard.writeText( copy.getAttribute( 'data-code' ) ).then( function () {
				copy.textContent = settings.copied;
				setTimeout( function () {
					copy.textContent = label;
				}, 1500 );
			} );
			return;
		}

		var button = event.target.closest( '.wps-wgm-mgc-resend' );
		if ( ! button || button.disabled || ! window.confirm( settings.confirm ) ) {
			return;
		}

		var notice      = button.parentNode.querySelector( '.wps-wgm-mgc-notice' );
		var buttonLabel = button.textContent;
		var data        = new FormData();
		data.append( 'action', 'wps_wgm_my_account_resend_giftcard' );
		data.append( 'nonce', settings.nonce );
		data.append( 'coupon_id', button.getAttribute( 'data-coupon-id' ) );

		button.disabled    = true;
		button.textContent = settings.sending;
		notice.textContent = '';

		fetch( settings.ajaxurl, { method: 'POST', credentials: 'same-origin', body: data } )
			.then( function ( response ) {
				return response.json();
			} )
			.then( function ( response ) {
				notice.textContent = ( response.data && response.data.message ) || settings.error;
				notice.className   = 'wps-wgm-mgc-notice ' + ( response.success ? 'is-success' : 'is-error' );
			} )
			.catch( function () {
				notice.textContent = settings.error;
				notice.className   = 'wps-wgm-mgc-notice is-error';
			} )
			.then( function () {
				button.disabled    = false;
				button.textContent = buttonLabel;
			} );
	} );
})();
