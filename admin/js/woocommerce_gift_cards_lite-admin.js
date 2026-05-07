/**
 * All of the code for notices on your admin-facing JavaScript source
 * should reside in this file.
 *
 * @package          woo-gift-cards-lite
 */

(function( $ ) {
	'use strict';

	jQuery( document ).ready(
		function(){

			var maxPercentField = $( '#wps_wgm_general_setting_giftcard_max_percent, input[name="wps_wgm_general_setting_giftcard_max_percent"]' );
			if ( maxPercentField.length ) {
				// Allow 0 to represent "no limit" without failing HTML5 min validation.
				maxPercentField.attr( 'min', '0' );
			}
		
			jQuery( '.cancel_notice' ).on(
				'click',
				function() {
					jQuery( this ).parent().hide();
				}
			);
			jQuery( document ).on(
				'click',
				'.wps_preview_links a',
				function( e ) {
					e.preventDefault();
					jQuery( this ).parent().parent( ".wps_event_template_preview" ).siblings( '.wps-popup-wrapper' ).fadeIn( "slow" );
				}
			);
			jQuery( document ).on(
				'click',
				'.wps-popup-img span',
				function( e ) {
					e.preventDefault();
					jQuery( this ).parent().parent().parent( '.wps-popup-wrapper' ).fadeOut( "slow" );
				}
			)
			
			jQuery(document).on('click', '.wps_download_template', function(e) {
				e.preventDefault();
				
				jQuery('.wps-gc__popup-for-pro-shadow').show();
				jQuery('.wps-gc__popup-for-pro').addClass('active-pro');
			})
			jQuery(document).on('click', '.wps_import_all_giftcard_templates', function(e) {
				e.preventDefault();
				
				jQuery('.wps-gc__popup-for-pro-shadow').show();
				jQuery('.wps-gc__popup-for-pro').addClass('active-pro');
			})

			jQuery(document).on('click', '.wps_wgm_content_template_pro_tag', function(e) {
				e.preventDefault();
				jQuery('.wps-gc__popup-for-pro-wrap').css('display','block')
				jQuery('.wps-gc__popup-for-pro-shadow').show();
				jQuery('.wps-gc__popup-for-pro').addClass('active-pro');
			})
			$(document).on('click', '.wps-gc__popup-for-pro-close', function() {
				$('.wps-gc__popup-for-pro-shadow').hide();
				$('.wps-gc__popup-for-pro').removeClass('active-pro');
			})
		
			$(document).on('click', '.wps-gc__popup-for-pro-shadow', function() {
				$(this).hide();
				$('.wps-gc__popup-for-pro').removeClass('active-pro');
			})

			$( '#wps_wgm_setting_wrapper .wps_wgm_content_panel p.submit' ).addClass( 'wps_wgm_floating_submit' );
			$( document ).on( 'click', '#wps_wgm_setting_wrapper .wps_wgm_dashboard_page_notice .notice-dismiss', function( e ) {
				e.preventDefault();
				$( this ).closest( '.wps_wgm_dashboard_page_notice' ).fadeOut( 150 );
			} );

			$( document ).on( 'click', '.wps_wgm_nav_tab_more', function( e ) {
				e.preventDefault();
				e.stopPropagation();

				var $wrapper = $( this ).closest( '.wps_wgm_tabs_more' );
				var isOpen = $wrapper.hasClass( 'is-open' );

				$( '.wps_wgm_tabs_more' ).removeClass( 'is-open' );
				$( '.wps_wgm_nav_tab_more' ).attr( 'aria-expanded', 'false' );

				if ( ! isOpen ) {
					$wrapper.addClass( 'is-open' );
					$( this ).attr( 'aria-expanded', 'true' );
				}
			} );

			$( document ).on( 'click', function( e ) {
				if ( ! $( e.target ).closest( '.wps_wgm_tabs_more' ).length ) {
					$( '.wps_wgm_tabs_more' ).removeClass( 'is-open' );
					$( '.wps_wgm_nav_tab_more' ).attr( 'aria-expanded', 'false' );
				}
			} );
			
			
			//////////////////
			jQuery(document).on('keyup','.wps_wgm_variation_price', function() {
				this.value = this.value.replace(/[^0-9,.]/g, '');
			});

			jQuery(document).on('click','.wps_add_more_price',function(e){
				e.preventDefault();
				var empty_warning = false;
				jQuery( '.wps_wgm_variation_text' ).each( function() {
					if(!jQuery(this).val()){				
						jQuery(this).css("border-color", "red");
						empty_warning = true;
					}
					else{				
						jQuery(this).css("border-color", "");
					}
				});
				jQuery('.wps_wgm_variation_price').each(function(){
					console.log(jQuery(this).val());
					if(!jQuery(this).val()){				
						jQuery(this).css("border-color", "red");
						empty_warning = true;
					}
					else{				
						jQuery(this).css("border-color", "");
					}		
				});
				if (empty_warning == false) {
					var shtml = '<div class="wps_wgm_variation_giftcard">\
					<input type="text" class="wps_wgm_variation_text" name="wps_wgm_variation_text[]" value="" placeholder="Enter Description">\
					<input type="text" class="wps_wgm_variation_price wc_input_price" name="wps_wgm_variation_price[]" value="" placeholder="Enter Price">\
					<a class="wps_remove_more_price button" href="javascript:void(0)">Remove</a>\
					</div>\
					<a href="#" class="wps_add_more_price button">Add</a>';
					jQuery('#wps_variable_gift').append(shtml);
					jQuery(this).remove();
				}
			});

			jQuery(document).on('click','.wps_remove_more_price',function(e){
				e.preventDefault();
				jQuery(this).parent().remove();
		
			});

			$('.wps_wgm_gc_price_range').keyup( function() {
				var minspend = parseInt( $('#wps_wgm_general_setting_giftcard_minspend').val() );
				var maxspend = parseInt( $('#wps_wgm_general_setting_giftcard_maxspend').val() );
				if (minspend > maxspend) {
					$('#wps_wgm_save_general').css('display','none');
					$('#wps_wgm_general_setting_giftcard_minspend').css('border-color','red');
					$('#wps_wgm_general_setting_giftcard_maxspend').css('border-color','red');
				} else {
					$('#wps_wgm_general_setting_giftcard_minspend').css('border-color','');
					$('#wps_wgm_general_setting_giftcard_maxspend').css('border-color','');
					$('#wps_wgm_save_general').css('display','block');
				}
			});

			$( document ).find( '.wc-pbc-show-if-not-supported' ).remove();
			$( "#wps_wgm_product_setting_exclude_category" ).select2();
			$( "#wps_wgm_email_template" ).select2();
			wps_wgc_show_and_hide_panels();
			var pricing_option = $( '#wps_wgm_pricing' ).val();
			wps_wgc_show_and_hide_pricing_option( pricing_option );
			$( '#wps_wgm_pricing' ).change(
				function() {
					var pricing_option = $( this ).val();
					wps_wgc_show_and_hide_pricing_option( pricing_option );
				}
			);
			var imageurl = $( "#wps_wgm_mail_setting_upload_logo" ).val();
			if (imageurl != null && imageurl != "") {
				$( "#wps_wgm_mail_setting_upload_image" ).attr( "src",imageurl );
				$( "#wps_wgm_mail_setting_remove_logo" ).show();

			}
			jQuery( ".wps_wgm_mail_setting_remove_logo_span" ).on ( 
				'click',
				function(){
					jQuery( "#wps_wgm_mail_setting_remove_logo" ).hide();
					jQuery( "#wps_wgm_mail_setting_upload_logo" ).val( "" );
				}
			);
			var imageurl = $( "#wps_wgm_mail_setting_upload_logo" ).val();
			if (imageurl != null && imageurl != "") {
				$( "#wps_wgm_mail_setting_upload_image" ).attr( "src",imageurl );
				$( "#wps_wgm_mail_setting_remove_logo" ).show();

			}
			jQuery( "#wps_wgm_mail_setting" ).on(
				'click',
				function() {
					var $mailSettingTab = jQuery( this );
					var $mailSettingWrapper = jQuery( "#wps_wgm_mail_setting_wrapper" );
					var isExpanded = $mailSettingTab.hasClass( 'is-open' );

					$mailSettingTab.toggleClass( 'is-open', ! isExpanded );
					$mailSettingTab.attr( 'aria-expanded', ! isExpanded ? 'true' : 'false' );
					$mailSettingWrapper.stop( true, true ).slideToggle();
				}
			);

			jQuery( 'input[type="button"].wps_wgm_mail_setting_upload_logo.button' ).on( 
				'click',
				function( e ){
					e.preventDefault();
					e.stopPropagation();
					var imageurl = $( "#wps_wgm_mail_setting_upload_logo" ).val();
					tb_show( '', 'media-upload.php?TB_iframe=true' );

					window.send_to_editor = function(html)
					{
							var imageurl = jQuery( html ).attr( 'href' );

						if (typeof imageurl == 'undefined') {
							imageurl = jQuery( html ).attr( 'src' );
						}
							var last_index = imageurl.lastIndexOf( '/' );
							var url_last_part = imageurl.substr( last_index + 1 );
						if ( url_last_part == '' ) {

							imageurl = jQuery( html ).children( "img" ).attr( "src" );
						}
							$( "#wps_wgm_mail_setting_upload_logo" ).val( imageurl );
							$( "#wps_wgm_mail_setting_upload_image" ).attr( "src",imageurl );
							$( "#wps_wgm_mail_setting_remove_logo" ).show();
							tb_remove();
					};
					return false;
				}
			);

			jQuery( 'input[type="button"].wps_wgm_mail_setting_background_logo.button' ).on( 
				'click',
				function( e )
				{
					e.preventDefault();
					e.stopPropagation();
					var imageurl = $( "#wps_mail_other_setting_background_logo_value" ).val();
					tb_show( '', 'media-upload.php?TB_iframe=true' );
					 window.send_to_editor = function(html)
					{
						var imageurl = jQuery( html ).attr( 'href' );
						if (typeof imageurl == 'undefined') {
							imageurl = jQuery( html ).attr( 'src' );
						}
						$( "#wps_wgm_mail_setting_background_logo_value" ).val( imageurl );
						$( "#wps_wgm_mail_setting_background_logo_image" ).attr( "src",imageurl );
						$( "#wps_wgm_mail_setting_remove_background" ).show();
						tb_remove();
					 };
					return false;
				}
			);

			jQuery( ".wps_wgm_mail_setting_remove_background_span" ).on( 
				'click',
				function(){
					jQuery( "#wps_wgm_mail_setting_remove_background" ).hide();
					jQuery( "#wps_wgm_mail_setting_background_logo_value" ).val( "" );
				}
			);
			var imageurl = $( "#wps_wgm_mail_setting_background_logo_value" ).val();
			if (imageurl != null && imageurl != "") {
				$( "#wps_wgm_mail_setting_background_logo_image" ).attr( "src",imageurl );
				$( "#wps_wgm_mail_setting_remove_background" ).show();

			}
			function wps_wgc_show_and_hide_panels() {
				var product_type    = $( 'select#product-type' ).val();
				var is_wps_wgm_gift = false;
				var is_tax_enable_for_gift = wps_wgc.is_tax_enable_for_gift;
				if (product_type == "wgm_gift_card") {
					is_wps_wgm_gift = true;
				}
				if (is_wps_wgm_gift) {
					
					// Hide/Show all with rules.
					var hide_classes = '.hide_if_wps_wgm_gift, .hide_if_wps_wgm_gift';
					var show_classes = '.show_if_wps_wgm_gift, .show_if_wps_wgm_gift';
					$.each(
						woocommerce_admin_meta_boxes.product_types,
						function( index, value ) {
							hide_classes = hide_classes + ', .hide_if_' + value;
							show_classes = show_classes + ', .show_if_' + value;
						}
					);
					$( hide_classes ).show();
					$( show_classes ).hide();
					// Shows rules.
					if ( is_wps_wgm_gift ) {
						$( '.show_if_wps_wgm_gift' ).show();
					}
					$( '.show_if_' + product_type ).show();
					// Hide rules.
					if ( ! is_wps_wgm_gift ) {
						$( '.show_if_wps_wgm_gift' ).hide();
					}
					$( '.hide_if_' + product_type ).hide();
					$( 'input#_manage_stock' ).change();
					// Hide empty panels/tabs after display.
					$( '.woocommerce_options_panel' ).each(
						function() {
							var $children = $( this ).children( '.options_group' );
							if ( 0 === $children.length ) {
								return;
							}
							var $invisble = $children.filter(
								function() {
									return 'none' === $( this ).css( 'display' );
								}
							);
							// Hide panel.
							if ( $invisble.length === $children.length ) {
								var $id = $( this ).prop( 'id' );
								$( '.product_data_tabs' ).find( 'li a[href="#' + $id + '"]' ).parent().hide();
							}
						}
					);
					$( "#general_product_data .show_if_simple.show_if_external.show_if_variabled" ).attr( "style", "display:block !important;" );
					if (is_tax_enable_for_gift == 'on') {
						$( document ).find( "#general_product_data .options_group.show_if_simple.show_if_external.show_if_variable" ).attr( "style", "display:block !important;" );
					}
				}
			}

			function wps_wgc_show_and_hide_pricing_option(pricing_option){
				$( '.wps_wgm_from_price_field' ).show();
				$( '.wps_wgm_to_price_field' ).show();
				$( '.wps_wgm_selected_price_field' ).show();
				$( '.wps_wgm_default_price_field' ).show();
				$( '.wps_wgm_user_price_field' ).show();
				$( '#wps_variable_gift' ).hide();

				if (pricing_option == 'wps_wgm_selected_price') {
					$( '.wps_wgm_from_price_field' ).hide();
					$( '.wps_wgm_to_price_field' ).hide();
					$( '.wps_wgm_default_price_field' ).hide();
					$( '.wps_wgm_user_price_field' ).hide();
					$( '#wps_wgm_discount' ).parent().hide();
					$( '#wps_variable_gift' ).hide();
					$( '.wps_wgm_min_user_price_field' ).hide(); 
				}
				if (pricing_option == 'wps_wgm_range_price') {
					$( '.wps_wgm_selected_price_field' ).hide();
					$( '.wps_wgm_default_price_field' ).hide();
					$( '.wps_wgm_user_price_field' ).hide();
					$( '#wps_wgm_discount' ).parent().show();
					$( '#wps_variable_gift' ).hide();
					$( '.wps_wgm_min_user_price_field').hide();
				}
				if (pricing_option == 'wps_wgm_default_price') {
					$( '.wps_wgm_from_price_field' ).hide();
					$( '.wps_wgm_to_price_field' ).hide();
					$( '.wps_wgm_selected_price_field' ).hide();
					$( '.wps_wgm_user_price_field' ).hide();
					$( '#wps_wgm_discount' ).parent().show();
					$( '#wps_variable_gift' ).hide();
					$( '.wps_wgm_min_user_price_field').hide();
				}
				if (pricing_option == 'wps_wgm_user_price') {
					$( '.wps_wgm_from_price_field' ).hide();
					$( '.wps_wgm_to_price_field' ).hide();
					$( '.wps_wgm_default_price_field' ).hide();
					$( '.wps_wgm_selected_price_field' ).hide();
					$( '#wps_wgm_discount' ).parent().show();
					$( '#wps_variable_gift' ).hide();
					$( '.wps_wgm_min_user_price_field').show();
				}
				if (pricing_option == 'wps_wgm_variable_price') {
					$( '.wps_wgm_from_price_field' ).hide(); 
					$( '.wps_wgm_to_price_field' ).hide();  
					$( '.wps_wgm_default_price_field' ).hide(); 
					$( '.wps_wgm_selected_price_field' ).hide();
					$( '#wps_wgm_discount' ).parent().hide();
					$( '.wps_wgm_user_price_field' ).hide();
					$( '#wps_variable_gift' ).show();
					$( '.wps_wgm_min_user_price_field').hide();
				}
				if (pricing_option == 'wps_wgm_selected_with_price_range') {
					$( '.wps_wgm_default_price_field' ).hide();
					$( '#wps_wgm_discount' ).parent().hide();
					$( '.wps_wgm_user_price_field' ).hide();
					$( '#wps_variable_gift' ).hide();
					$( '.wps_wgm_min_user_price_field').hide();
				}
			}

			$( '.notice-dismiss' ).click(
				function(){
					$( ".notice-success" ).remove();
				}
			);
			
			// Hide-show the instruction box.
			$( '.wps_wgm_instructions_reminder' ).on( 
				'click',
				function(){
					$( '#wps-modal-main-wrapper' ).css( 'display','block' );
				}
			);
			$( '.wps_no_thanks_general' ).on( 
				'click',
				function(){
					$( '#wps-modal-main-wrapper' ).css( 'display','none' );
				}
			);

			// Email Selection from Backend.
			var radio_on_load = $( "input[name='wps_wgm_select_email_format']:checked" ).val();
			if (radio_on_load == 'normal') {
				$( '#wps_wgm_normal_card' ).css( 'border','3px solid #808080' );
			} else if (radio_on_load == 'mom') {
				$( '#wps_wgm_mom_card' ).css( 'border','3px solid #808080' );
			}
			// On change selection for radio button border: 3px solid #808080;!

			$( '.wps_wgm_select_email' ).change(
				function(){
					var radioVal = $( this ).val();
					if (radioVal == 'normal') {
						$( '#wps_wgm_normal_card' ).css( 'border','3px solid #808080' );
						$( '#wps_wgm_mom_card' ).css( 'border','none' );
					} else if (radioVal == 'mom') {
						$( '#wps_wgm_mom_card' ).css( 'border','3px solid #808080' );
						$( '#wps_wgm_normal_card' ).css( 'border','none' );
					}
				}
			);
			jQuery( '.wps_wgm_mobile_nav .dashicons' ).on(
				'click',
				function(e) {
					e.preventDefault();
					jQuery( '.wps_wgm_navigator_template' ).toggle( 'slow' );
				}
			);

			$( document ).on(
				'click',
				'.generate_link',
				function(){
					$( '.wps_redeem_registraion_div' ).show();
				}
			);

			$( document ).on(
				'click',
				'.wps-redeem-pop-close',
				function(){
					$( '.wps_redeem_registraion_div' ).hide();
				}
			);

			$( document ).on(
				'click',
				'.remove_giftcard_redeem_details' ,
				function (e) {

					var res = confirm( "Are you sure ! want to delete the account details  " );
					if (res == true) {
						$( document ).find( '#mainform' ).submit();
					} else {
						return false;
					}
				}
			);

			var clipboard1 = new Clipboard( '.wps_link_copy' );
			var clipboard2 = new Clipboard( '.wps_embeded_copy' );
			$( document ).on(
				'click',
				'.wps_redeem_copy',
				function(event) {
					event.preventDefault();

				}
			);
			/*======================================
			=            Sticky-Sidebar            =
			======================================*/
			setTimeout(
				function()
				  {
					if ( jQuery( window ).width() >= 900 ) {
						jQuery( '.wps_wgm_navigator_template' ).stickySidebar(
							{
								topSpacing: 30,
								bottomSpacing: 10
								}
						);
					}
				},
				500
			);

			/*=====  End of Sticky-Sidebar  ======*/

			// selected price validation.

			// Regex pattern to allow only numbers and |
			if ( ',' == wps_wgc.decimal_separator ) {
				var regexPattern = /^[0-9,|]*$/;
			} else {
				var regexPattern = /^[0-9.|]*$/;
			}

			$('#wps_wgm_selected_price').on('input', function() {
				var value = $(this).val();
				if (!regexPattern.test(value)) {
					alert('Please enter only numbers and |');
					// Remove the last entered character
					$(this).val(value.slice(0, -1));
				}
			});

			// validation for from price and to price.
			$('#wps_wgm_from_price, #wps_wgm_to_price').on('change', function() {
                var fromPrice = parseFloat($('#wps_wgm_from_price').val());
                var toPrice = parseFloat($('#wps_wgm_to_price').val());

                if (fromPrice > toPrice) {
                    alert('The "From Price" should be lower than the "To Price". Please correct the values.');
                    // Clear the input fields or set default values
                    $('#wps_wgm_from_price').val('');
                    $('#wps_wgm_to_price').val('');
                }
            });

			$('#wps_wgm_min_user_price').on('change', function() {
				var default_price = parseFloat( $('#wps_wgm_default').val() );
				var min_user_price = parseFloat( $('#wps_wgm_min_user_price').val() );
				if ( default_price < min_user_price ) {
					alert('Please enter Minimum price smaller than Default price so that default price will be considered.');
					$('#wps_wgm_min_user_price').val('');
				}
			});

			$( document ).on(
				"click",
				"#wps_wgm_transfer_smart_coupons",
				function() {
					var data = {
						action:'wps_migrate_smart_coupons_to_giftcards',
						nonce : wps_wgc.wps_wgm_nonce
					};
					$("#wps_wgm_loader_other").show();

					$.ajax({
						url: wps_wgc.ajaxurl,
						data: data,
						type: "POST",
						dataType :'json',
						success: function(response) {
							$("#wps_wgm_loader_other").hide();

							if (response.success) {
								$("#wps_wgm_transfer_smart_coupons").after(
									'<div class="notice notice-success is-dismissible" style="margin:15px 40px;"><p>' 
									+ response.data.message + 
									'</p></div>'
								);
								setTimeout(function() {
									$('.notice-success').fadeOut('slow', function() {
										$(this).remove();
									});
								}, 2000);
							}
						}
					});
				}
			);
		}
	);

	$(function() {
		var modalSelector = '[data-wps-wgm-expert-modal]';
		var openTriggerSelector = '[data-wps-wgm-open-expert-modal]';
		var closeTriggerSelector = '[data-wps-wgm-expert-modal-close]';
		var formSelector = '[data-wps-wgm-expert-modal-form]';
		var statusSelector = '[data-wps-wgm-expert-modal-status]';
		var successSelector = '[data-wps-wgm-expert-modal-success]';
		var successMessageSelector = '[data-wps-wgm-expert-modal-success-message]';
		var bodyLockClass = 'wps-wgm-expert-modal-open';
		var successCloseTimer = null;

		function wpsWgmGetExpertModal() {
			return $( modalSelector ).first();
		}

		function wpsWgmSetExpertStatus( $modal, message, statusType ) {
			var $status = $modal.find( statusSelector ).first();

			if ( ! $status.length ) {
				return;
			}

			if ( ! message ) {
				$status.attr( 'hidden', true ).removeClass( 'is-success is-error' ).text( '' );
				return;
			}

			$status.removeAttr( 'hidden' ).removeClass( 'is-success is-error' ).addClass( 'is-' + statusType ).text( message );
		}

		function wpsWgmResetExpertModalState( $modal ) {
			var $form = $modal.find( formSelector ).first();
			var $success = $modal.find( successSelector ).first();
			var $successMessage = $modal.find( successMessageSelector ).first();
			var $submitButton = $form.find( 'button[type="submit"]' ).first();

			$modal.removeClass( 'is-success' );

			if ( $form.length ) {
				if ( $form.get( 0 ) && 'function' === typeof $form.get( 0 ).reset ) {
					$form.get( 0 ).reset();
				}

				$form.removeAttr( 'hidden' );
			}

			if ( $submitButton.length ) {
				$submitButton
					.prop( 'disabled', false )
					.text( $submitButton.attr( 'data-submit-label' ) || 'Submit Request' );
			}

			if ( $success.length ) {
				$success.attr( 'hidden', true ).removeClass( 'is-visible' );
			}

			if ( $successMessage.length ) {
				$successMessage.text( 'Thank you for submitting your request.' );
			}

			wpsWgmSetExpertStatus( $modal, '', '' );
		}

		function wpsWgmShowExpertSuccessState( $modal, message ) {
			var $form = $modal.find( formSelector ).first();
			var $success = $modal.find( successSelector ).first();
			var $successMessage = $modal.find( successMessageSelector ).first();
			var $dialog = $modal.find( '.wps-wgm-expert-modal__dialog' ).first();

			if ( $form.length ) {
				$form.attr( 'hidden', true );
			}

			$modal.addClass( 'is-success' );
			wpsWgmSetExpertStatus( $modal, '', '' );

			if ( $successMessage.length ) {
				$successMessage.text( message );
			}

			if ( $success.length ) {
				$success.removeAttr( 'hidden' );

				window.setTimeout( function() {
					$success.addClass( 'is-visible' );
				}, 20 );
			}

			if ( $dialog.length ) {
				$dialog.scrollTop( 0 );
			}
		}

		function wpsWgmToggleExpertModal( shouldOpen ) {
			var $modal = wpsWgmGetExpertModal();

			if ( ! $modal.length ) {
				return;
			}

			if ( successCloseTimer ) {
				window.clearTimeout( successCloseTimer );
				successCloseTimer = null;
			}

			if ( shouldOpen ) {
				$modal.removeAttr( 'hidden' );
				$( 'body' ).addClass( bodyLockClass );
				wpsWgmResetExpertModalState( $modal );
				return;
			}

			$modal.attr( 'hidden', true );
			$( 'body' ).removeClass( bodyLockClass );
			wpsWgmResetExpertModalState( $modal );
		}

		function wpsWgmNormalizeExpertPayload( formElement ) {
			var payload = {};
			var formData = new window.FormData( formElement );

			formData.forEach( function( value, key ) {
				var normalizedKey = key.replace( /\[\]$/, '' );

				if ( Object.prototype.hasOwnProperty.call( payload, normalizedKey ) ) {
					if ( ! Array.isArray( payload[ normalizedKey ] ) ) {
						payload[ normalizedKey ] = [ payload[ normalizedKey ] ];
					}

					payload[ normalizedKey ].push( value );
					return;
				}

				payload[ normalizedKey ] = value;
			} );

			return payload;
		}

		$( document ).off( 'click.wpsWgmExpertModalOpen', openTriggerSelector ).on( 'click.wpsWgmExpertModalOpen', openTriggerSelector, function(event) {
			event.preventDefault();
			wpsWgmToggleExpertModal( true );
		} );

		$( document ).off( 'click.wpsWgmExpertModalClose', closeTriggerSelector ).on( 'click.wpsWgmExpertModalClose', closeTriggerSelector, function(event) {
			event.preventDefault();
			wpsWgmToggleExpertModal( false );
		} );

		$( document ).off( 'keydown.wpsWgmExpertModal' ).on( 'keydown.wpsWgmExpertModal', function(event) {
			if ( 'Escape' === event.key ) {
				wpsWgmToggleExpertModal( false );
			}
		} );

		$( document ).off( 'submit.wpsWgmExpertModal', formSelector ).on( 'submit.wpsWgmExpertModal', formSelector, function(event) {
			var $form = $( this );
			var $modal = $form.closest( modalSelector );
			var $submitButton = $form.find( 'button[type="submit"]' ).first();
			var submitLabel = $submitButton.attr( 'data-submit-label' ) || $submitButton.text();
			var loadingLabel = $submitButton.attr( 'data-loading-label' ) || 'Sending...';

			event.preventDefault();
			wpsWgmSetExpertStatus( $modal, '', '' );
			$submitButton.prop( 'disabled', true ).text( loadingLabel );

			$.ajax( {
				url: wps_wgc.ajaxurl,
				type: 'POST',
				dataType: 'json',
				data: {
					action: wps_wgc.wps_wgm_expert_action,
					nonce: wps_wgc.wps_wgm_expert_nonce,
					form_data: JSON.stringify( wpsWgmNormalizeExpertPayload( $form.get( 0 ) ) )
				}
			} ).done( function( response ) {
				var isSuccess = !! ( response && response.success );
				var message = response && response.data && response.data.message ? response.data.message : '';

				if ( ! message ) {
					message = isSuccess ? 'Thank you for submitting your request.' : 'We could not submit your request right now. Please try again.';
				}

				if ( isSuccess && message ) {
					wpsWgmShowExpertSuccessState( $modal, message );
					return;
				}

				wpsWgmSetExpertStatus( $modal, message, 'error' );
			} ).fail( function( xhr ) {
				var message = 'We could not submit your request right now. Please try again.';

				if ( xhr && xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message ) {
					message = xhr.responseJSON.data.message;
				}

				wpsWgmSetExpertStatus( $modal, message, 'error' );
			} ).always( function() {
				$submitButton.prop( 'disabled', false ).text( submitLabel );
			} );
		} );
	});
})( jQuery );
