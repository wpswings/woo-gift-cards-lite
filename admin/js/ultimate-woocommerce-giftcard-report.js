/**
 * All of the code for report of generated coupons
 * should reside in this file.
 *
 * @package           Ultimate Woocommerce Gift Cards
 */

jQuery( document ).ready(
	function($){
		// giftcard reporting feature - View Details
		jQuery( document ).on(
			'click',
			'.wps_uwgc_gift_report_view',
			function(e){
				e.preventDefault();
				var coupon_id = jQuery( this ).attr( 'data-coupon-id' );
				var order_id = jQuery( this ).attr( 'data-order-id' );
				var data = {
					action:'wps_uwgc_gift_card_details',
					coupon_id:coupon_id,
					order_id:order_id,
					wps_uwgc_nonce:ajax_object.wps_uwgc_report_nonce
				};

				$.ajax(
					{
						url:ajax_object.ajaxurl,
						type:'POST',
						data:data,
						success: function(response) {
							tb_show( " ",response );
						}
					}
				);

			}
		);

		// Copy gift card code to clipboard
		jQuery( document ).on(
			'click',
			'.wps-copy-code',
			function(e){
				e.preventDefault();
				e.stopPropagation();

				var code = jQuery( this ).attr( 'data-code' );
				var $button = jQuery( this );

				// Create temporary input
				var $temp = jQuery('<input>');
				jQuery('body').append($temp);
				$temp.val(code).select();

				try {
					document.execCommand('copy');
					$temp.remove();

					// Show success feedback
					var originalIcon = $button.find('.dashicons').attr('class');
					$button.find('.dashicons').attr('class', 'dashicons dashicons-yes');
					$button.css('color', '#27ae60');

					setTimeout(function() {
						$button.find('.dashicons').attr('class', originalIcon);
						$button.css('color', '');
					}, 1500);

				} catch(err) {
					$temp.remove();
					console.error('Failed to copy code:', err);
				}
			}
		);

		// Resend gift card email
		jQuery( document ).on(
			'click',
			'.wps-resend-email',
			function(e){
				e.preventDefault();
				e.stopPropagation();

				var coupon_id = jQuery( this ).attr( 'data-coupon-id' );
				var $button = jQuery( this );

				if ( $button.hasClass('sending') ) {
					return;
				}

				if ( ! confirm( 'Are you sure you want to resend this gift card email?' ) ) {
					return;
				}

				$button.addClass('sending');
				var originalIcon = $button.find('.dashicons').attr('class');
				$button.find('.dashicons').attr('class', 'dashicons dashicons-update wps-spinning');

				var data = {
					action: 'wps_uwgc_resend_gift_card_email',
					coupon_id: coupon_id,
					wps_uwgc_nonce: ajax_object.wps_uwgc_report_nonce
				};

				$.ajax({
					url: ajax_object.ajaxurl,
					type: 'POST',
					data: data,
					success: function(response) {
						$button.removeClass('sending');

						if ( response.success ) {
							// Show success
							$button.find('.dashicons').attr('class', 'dashicons dashicons-yes');
							$button.css('color', '#27ae60');

							setTimeout(function() {
								$button.find('.dashicons').attr('class', originalIcon);
								$button.css('color', '');
							}, 2000);
						} else {
							// Show error
							$button.find('.dashicons').attr('class', 'dashicons dashicons-no');
							$button.css('color', '#e74c3c');

							setTimeout(function() {
								$button.find('.dashicons').attr('class', originalIcon);
								$button.css('color', '');
							}, 2000);

							alert( response.data.message || 'Failed to resend email.' );
						}
					},
					error: function() {
						$button.removeClass('sending');
						$button.find('.dashicons').attr('class', originalIcon);
						alert( 'An error occurred. Please try again.' );
					}
				});
			}
		);

		// Animate metrics on load
		if ( jQuery('.wps-uwgc-metrics-grid').length ) {
			jQuery('.wps-uwgc-metric-card').each(function(index) {
				var $card = jQuery(this);
				setTimeout(function() {
					$card.css({
						'opacity': '0',
						'transform': 'translateY(20px)'
					}).animate({
						'opacity': '1'
					}, 300).css('transform', 'translateY(0)');
				}, index * 50);
			});
		}

		// Add spinning animation CSS if not exists
		if ( ! jQuery('style#wps-report-animations').length ) {
			jQuery('<style id="wps-report-animations">')
				.text('@keyframes wps-spin { to { transform: rotate(360deg); } } .wps-spinning { animation: wps-spin 1s linear infinite; }')
				.appendTo('head');
		}
	}
);
