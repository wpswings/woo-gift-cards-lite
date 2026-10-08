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

	// The Clipboard API only exists on HTTPS pages, so fall back to execCommand elsewhere.
	function copyText( text ) {
		if ( navigator.clipboard && window.isSecureContext ) {
			return navigator.clipboard.writeText( text );
		}
		return new Promise( function ( resolve, reject ) {
			var field = document.createElement( 'textarea' );
			field.value = text;
			field.setAttribute( 'readonly', '' );
			field.style.position = 'fixed';
			field.style.opacity  = '0';
			document.body.appendChild( field );
			field.select();
			var ok = document.execCommand( 'copy' );
			document.body.removeChild( field );
			( ok ? resolve : reject )();
		} );
	}

	document.addEventListener( 'click', function ( event ) {
		var copy = event.target.closest( '.wps-wgm-mgc-copy' );
		if ( copy ) {
			var copyLabel = copy.querySelector( '.wps-wgm-mgc-copy-label' );
			var original  = copyLabel.textContent;
			copyText( copy.getAttribute( 'data-code' ) ).then( function () {
				copyLabel.textContent = settings.copied;
				copy.classList.add( 'is-copied' );
				setTimeout( function () {
					copyLabel.textContent = original;
					copy.classList.remove( 'is-copied' );
				}, 1500 );
			} );
			return;
		}

		var button = event.target.closest( '.wps-wgm-mgc-resend' );
		if ( ! button || button.disabled || ! window.confirm( settings.confirm ) ) {
			return;
		}

		var notice      = button.parentNode.querySelector( '.wps-wgm-mgc-notice' );
		var label       = button.querySelector( '.wps-wgm-mgc-resend-label' );
		var buttonLabel = label.textContent;
		var data        = new FormData();
		data.append( 'action', 'wps_wgm_my_account_resend_giftcard' );
		data.append( 'nonce', settings.nonce );
		data.append( 'coupon_id', button.getAttribute( 'data-coupon-id' ) );

		button.disabled    = true;
		label.textContent  = settings.sending;
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
				button.disabled   = false;
				label.textContent = buttonLabel;
			} );
	} );
})();
