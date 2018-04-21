(function( $ ) {
	'use strict';

	 jQuery(document).ready(function(){

	 	mwb_wgc_show_and_hide_panels();
		$( 'select#product-type' ).change( function() {
			// mwb_wgc_show_and_hide_panels();
		});
		var pricing_option = $('#mwb_wgm_pricing').val();
		mwb_wgc_show_and_hide_pricing_option(pricing_option);
		$( '#mwb_wgm_pricing' ).change( function() {
			var pricing_option = $(this).val();
			mwb_wgc_show_and_hide_pricing_option(pricing_option);
		});
		function mwb_wgc_show_and_hide_panels() {
			var product_type    = $( 'select#product-type' ).val();
			var is_mwb_wgm_gift = false;
			var is_tax_enable_for_gift = mwb_wgc.is_tax_enable_for_gift;
			if(product_type == "wgm_gift_card"){
				is_mwb_wgm_gift = true;
			}	
			if(is_mwb_wgm_gift){
				// Hide/Show all with rules.
				var hide_classes = '.hide_if_mwb_wgm_gift, .hide_if_mwb_wgm_gift';
				var show_classes = '.show_if_mwb_wgm_gift, .show_if_mwb_wgm_gift';
				$.each( woocommerce_admin_meta_boxes.product_types, function( index, value ) {
					hide_classes = hide_classes + ', .hide_if_' + value;
					show_classes = show_classes + ', .show_if_' + value;
				});
				$( hide_classes ).show();
				$( show_classes ).hide();
				// Shows rules.
				if ( is_mwb_wgm_gift ) {
					$( '.show_if_mwb_wgm_gift' ).show();
				}
				$( '.show_if_' + product_type ).show();
				// Hide rules.
				if ( !is_mwb_wgm_gift ) {
					$( '.show_if_mwb_wgm_gift' ).hide();
				}
				$( '.hide_if_' + product_type ).hide();
				$( 'input#_manage_stock' ).change();
				// Hide empty panels/tabs after display.
				$( '.woocommerce_options_panel' ).each( function() {
					var $children = $( this ).children( '.options_group' );
					if ( 0 === $children.length ) {
						return;
					}
					var $invisble = $children.filter( function() {
						return 'none' === $( this ).css( 'display' );
					});
					// Hide panel.
					if ( $invisble.length === $children.length ) {
						var $id = $( this ).prop( 'id' );
						$( '.product_data_tabs' ).find( 'li a[href="#' + $id + '"]' ).parent().hide();
					}
				});
				$(".inventory_tab").attr("style", "display:block !important;");
				$("#inventory_product_data ._manage_stock_field").attr("style", "display:block !important;");
				$("#inventory_product_data .options_group").attr("style", "display:block !important;");
				$("#inventory_product_data ._sold_individually_field").attr("style", "display:block !important;");
				$("#general_product_data .show_if_simple.show_if_external.show_if_variabled").attr("style", "display:block !important;");
				if(is_tax_enable_for_gift == 'on'){
					$(document).find("#general_product_data .options_group.show_if_simple.show_if_external.show_if_variable").attr("style", "display:block !important;");
				}
			}
		}

		function mwb_wgc_show_and_hide_pricing_option(pricing_option){
			$( '.mwb_wgm_from_price_field' ).show(); 
			$( '.mwb_wgm_to_price_field' ).show();  
			$( '.mwb_wgm_selected_price_field' ).show(); 
			$( '.mwb_wgm_default_price_field' ).show(); 
			$( '.mwb_wgm_user_price_field' ).show(); 
			
			if(pricing_option == 'mwb_wgm_selected_price')
			{
				$( '.mwb_wgm_from_price_field' ).hide(); 
				$( '.mwb_wgm_to_price_field' ).hide();  
				$( '.mwb_wgm_default_price_field' ).hide(); 
				$( '.mwb_wgm_user_price_field' ).hide();
				$('#mwb_wgm_discount').parent().hide();
			}
			if(pricing_option == 'mwb_wgm_range_price')
			{
				$( '.mwb_wgm_selected_price_field' ).hide();
				$( '.mwb_wgm_default_price_field' ).hide(); 
				$( '.mwb_wgm_user_price_field' ).hide();
				$('#mwb_wgm_discount').parent().show();
			}
			if(pricing_option == 'mwb_wgm_default_price')
			{
				$( '.mwb_wgm_from_price_field' ).hide(); 
				$( '.mwb_wgm_to_price_field' ).hide();  
				$( '.mwb_wgm_selected_price_field' ).hide(); 
				$( '.mwb_wgm_user_price_field' ).hide();
				$('#mwb_wgm_discount').parent().show();
			}
			if(pricing_option == 'mwb_wgm_user_price')
			{
				$( '.mwb_wgm_from_price_field' ).hide(); 
				$( '.mwb_wgm_to_price_field' ).hide();  
				$( '.mwb_wgm_default_price_field' ).hide(); 
				$( '.mwb_wgm_selected_price_field' ).hide();
				$('#mwb_wgm_discount').parent().show();
			}
		}
		//hide-show the instruction box
		$('.mwb_wgm_instructions_reminder').click(function(){
	      $('#mwb-modal-main-wrapper').css('display','block');
	    });
	    $('.mwb_no_thanks_general').click(function(){
	      $('#mwb-modal-main-wrapper').css('display','none');
	    });
	 });

})( jQuery );
