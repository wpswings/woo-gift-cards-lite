jQuery(document).ready( function($) {

	const ajaxUrl  		 = localised.ajaxurl;
	const nonce    		 = localised.nonce;
	const action          = localised.callback;
	const pending_count  = localised.pending_count;
	const pending_orders = localised.pending_orders;
	const completed_orders = localised.completed_orders;
	const searchHTML = '<style>input[type=number], select, numberarea{width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-top: 6px; margin-bottom: 16px; resize: vertical;}input[type=submit]{background-color: #04AA6D; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer;}.container{border-radius: 5px; background-color: #f2f2f2; padding: 20px;}</style></head><div class="container"> <label for="ordername">Order Id</label> <input type="number" id="ordername" name="firstname" placeholder="Order ID to search.."></div>';

	/* Close Button Click */
	jQuery( document ).on( 'click','.treat-button',function(e){
		e.preventDefault();
		Swal.fire({
			icon: 'warning',
			title: 'We Have got ' + pending_count + ' Orders!',
			text: 'Click to start import',
			footer: 'Please do not reload/close this page until prompted',
			showCloseButton: true,
			showCancelButton: true,
			focusConfirm: false,
			confirmButtonText:
			  '<i class="fa fa-thumbs-up"></i> Start',
			confirmButtonAriaLabel: 'Thumbs up',
			cancelButtonText:
			  '<i class="fa fa-thumbs-down">Cancel</i>',
			cancelButtonAriaLabel: 'Thumbs down'
		}).then((result) => {
			if (result.isConfirmed) {

				Swal.fire({
					title   : 'Orders are being imported!',
					html    : 'Do not reload/close this tab.',
					footer  : '<span class="order-progress-report">' + pending_count + ' are left to import',
					didOpen: () => {
						Swal.showLoading()
					}
				});
			
				startImport( pending_orders );
			} else if (result.isDismissed) {
			  Swal.fire('Import Stopped', '', 'info');
			}
		})
	});

	const startImport = ( orders ) => {
		var event   = 'import_single_order';
		var request = { action, event, nonce, orders };
		jQuery.post( ajaxUrl , request ).done(function( response ){
			orders = JSON.parse( response );
		}).then(
		function( orders ) {
			orders = JSON.parse( orders ).orders;
			count = Object.keys(orders).length;
			jQuery('.order-progress-report').text( count + ' are left to import' );
			if( ! jQuery.isEmptyObject(orders) ) {
				startImport(orders);
			} else {
				// All orders imported!
				Swal.fire({
					title   : 'Data are migrated successfully!',
				})
			}
		}, function(error) {
			console.error(error);
		});
	}

	// End of scripts.
});
