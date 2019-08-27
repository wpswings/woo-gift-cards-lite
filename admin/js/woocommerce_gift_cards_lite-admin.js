(function( $ ) {
	'use strict';

	jQuery(document).ready(function(){

	 	$(document).find('.wc-pbc-show-if-not-supported').remove();
	 	$("#mwb_wgm_product_setting_exclude_category").select2();
	 	$("#mwb_wgm_email_template").select2();
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
		var imageurl = $("#mwb_wgm_mail_setting_upload_logo").val();
		if(imageurl != null && imageurl != "")
		{	
			$("#mwb_wgm_mail_setting_upload_image").attr("src",imageurl);
		    $("#mwb_wgm_mail_setting_remove_logo").show();
		    
		}
			jQuery(".mwb_wgm_mail_setting_remove_logo_span").click(function(){
			jQuery("#mwb_wgm_mail_setting_remove_logo").hide();
			jQuery("#mwb_wgm_mail_setting_upload_logo").val("");
		});
		var imageurl = $("#mwb_wgm_mail_setting_upload_logo").val();
		if(imageurl != null && imageurl != "")
		{	
			$("#mwb_wgm_mail_setting_upload_image").attr("src",imageurl);
		    $("#mwb_wgm_mail_setting_remove_logo").show();
		    
		}
		jQuery("#mwb_wgm_mail_setting").click(function(){
			jQuery("#mwb_wgm_mail_setting_wrapper").slideToggle();
		});

	jQuery('.mwb_wgm_mail_setting_upload_logo').click(function(){
	    var imageurl = $("#mwb_wgm_mail_setting_upload_logo").val();
	        tb_show('', 'media-upload.php?TB_iframe=true');

	        window.send_to_editor = function(html)
	        {
	           var imageurl = jQuery(html).attr('href');
	          
	           if(typeof imageurl == 'undefined')
	           {
	             imageurl = jQuery(html).attr('src');
	           }
	           var last_index = imageurl.lastIndexOf('/');
	            var url_last_part = imageurl.substr(last_index+1);
	            if( url_last_part == '' ){
	              
	              imageurl = jQuery(html).children("img").attr("src");  
	            }   
	           $("#mwb_wgm_mail_setting_upload_logo").val(imageurl);
	           $("#mwb_wgm_mail_setting_upload_image").attr("src",imageurl);
	           $("#mwb_wgm_mail_setting_remove_logo").show();
	           tb_remove();
	        };
	        return false;
	});

	jQuery('.mwb_wgm_mail_setting_background_logo').click(function()
	{
		var imageurl = $("#mwb_mail_other_setting_background_logo_value").val();
     	tb_show('', 'media-upload.php?TB_iframe=true');
         window.send_to_editor = function(html)
        {
           var imageurl = jQuery(html).attr('href');
           if(typeof imageurl == 'undefined')
           {
        	   imageurl = jQuery(html).attr('src');
           }	  
            $("#mwb_wgm_mail_setting_background_logo_value").val(imageurl);
            $("#mwb_wgm_mail_setting_background_logo_image").attr("src",imageurl);
            $("#mwb_wgm_mail_setting_remove_background").show();
           tb_remove();
        };
       return false;
	});

  	jQuery(".mwb_wgm_mail_setting_remove_background_span").click(function(){
		jQuery("#mwb_wgm_mail_setting_remove_background").hide();
		jQuery("#mwb_wgm_mail_setting_background_logo_value").val("");
	});
	var imageurl = $("#mwb_wgm_mail_setting_background_logo_value").val();
	if(imageurl != null && imageurl != "")
	{	
		$("#mwb_wgm_mail_setting_background_logo_image").attr("src",imageurl);
	    $("#mwb_wgm_mail_setting_remove_background").show();
	    
	}

  	$("#mwb_wgm_mail_setting_background_color").wpColorPicker();

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

    //Email Selection from Backend
    var radio_on_load = $("input[name='mwb_wgm_select_email_format']:checked").val();
    if(radio_on_load == 'normal'){
    	$('#mwb_wgm_normal_card').css('border','3px solid #808080');
    }else if(radio_on_load == 'mom'){
    	$('#mwb_wgm_mom_card').css('border','3px solid #808080');
    }
    //On change selection for radio button border: 3px solid #808080;

    $( '.mwb_wgm_select_email' ).change( function(){
		var radioVal = $(this).val();
		if(radioVal == 'normal'){
	    	$('#mwb_wgm_normal_card').css('border','3px solid #808080');
	    	$('#mwb_wgm_mom_card').css('border','none');
	    }else if(radioVal == 'mom'){
	    	$('#mwb_wgm_mom_card').css('border','3px solid #808080');
	    	$('#mwb_wgm_normal_card').css('border','none');
	    }
	});
	jQuery('.mwb_wgm_mobile_nav .dashicons').on('click', function(e) {
		e.preventDefault();
		jQuery('.mwb_wgm_navigator_template').toggle('slow');
	});		

	$(document).on('click','.generate_link',function(){
	    $('.mwb_redeem_registraion_div').show();
  	});


  	$(document).on('click','.mwb-redeem-pop-close',function(){
	    $('.mwb_redeem_registraion_div').hide();
  	});

  	$(document).on('click', '.remove_giftcard_redeem_details' , function (e) {
	   
	    var res = confirm("Are you sure ! want to delete the account details  ");
	    if (res == true) {
	      $(document).find('#mainform').submit();
	    } else {
	      return false;
	    } 
  	});
	  
    var clipboard1 = new Clipboard('.mwb_link_copy');
    var clipboard2 = new Clipboard('.mwb_embeded_copy');
    // document.execCommand("copy");
    $(document).on('click', '.mwb_redeem_copy', function(event) {
      event.preventDefault();
      
    });
 });

})( jQuery );
