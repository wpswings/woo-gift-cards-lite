(function( $ ) {

	'use strict';
	jQuery(document).ready(function($){
		$("#mwb_wgm_message").keyup(function(){
			var msg_length = $(document).find('#mwb_wgm_message').val().length;
			if(msg_length == 0){
				
				$('#mwb_box_char').text(0);
			}
			else{
				$('#mwb_box_char').text(msg_length);
			}
			
		});
		/*
		Adds the Validation for some required fields for Single Product Page
		*/
		jQuery(".single_add_to_cart_button").click(function(e){
			if(typeof mwb_wgm.pricing_type.type != 'undefined')
		    {	
				// e.preventDefault();
				$("#mwb_wgm_error_notice").hide();
		        var from_mail = $("#mwb_wgm_from_name").val();
		        var message = $("#mwb_wgm_message").val();
		        message = message.trim();
		        var price = $("#mwb_wgm_price").val();
		        var error = false;
		        var product_type = mwb_wgm.pricing_type.type;
		        var html = "";
		        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,5})+$/;
		         html = "<ul>";
		        if($("#mwb_wgm_to_email_send").val()=='Mail to recipient')
		        {
		        	var to_mail = $("#mwb_wgm_to_email").val();
		        	if(to_mail == null || to_mail == "")
			        {
			        	error = true;
			        	$("#mwb_wgm_to_email").addClass("mwb_wgm_error");
			        	html+="<li><b>";
			        	html+=mwb_wgm.to_empty;
			        	html+="</li>";
			        } 
			        else if(!to_mail.match(mailformat))
			        {
			        	error = true;
			        	$("#mwb_wgm_to_email").addClass("mwb_wgm_error");
			        	html+="<li><b>";
			        	html+=mwb_wgm.to_invalid;
			        	html+="</li>";
			        }
		        }
		        else{
		        	var to_mail = $("#mwb_wgm_to_download").val();
		        	if(to_mail == null || to_mail == "")
			        {
			        	error = true;
			        	$("#mwb_wgm_to_download").addClass("mwb_wgm_error");
			        	html+="<li><b>";
			        	html+=mwb_wgm.to_empty_name;
			        	html+="</li>";
			        } 
		        }
		        
		        if(price == null || price == ""){
		        	error = true;
		        	$("#mwb_wgm_price").addClass("mwb_wgm_error");
		        	html+="<li><b>";
		        	html+=mwb_wgm.price_field;
		        	html+="</li>";
		        }
	        	
				if(from_mail == null || from_mail == ""){
		        	error = true;
		        	$("#mwb_wgm_from_name").addClass("mwb_wgm_error");
		        	html+="<li><b>";
		        	html+=mwb_wgm.from_empty;
		        	html+="</li>";
		        }      
		        if(message == null || message == ""){
		        	error = true;
		        	$("#mwb_wgm_message").addClass("mwb_wgm_error");
		        	html+="<li><b>";
		        	html+=mwb_wgm.msg_empty;
		        	html+="</li>";
		        }
		        else if( message.length > mwb_wgm.msg_length ){
		        	error = true;
		        	$("#mwb_wgm_message").addClass("mwb_wgm_error");
		        	html+="<li><b>";
		        	html+=mwb_wgm.msg_length_err;
		        	html+="</li>";
		        }			   
		        if(product_type == "mwb_wgm_range_price"){
		        	 //var from = parseInt(mwb_wgm.pricing_type.from);
		        	 //var to = parseInt(mwb_wgm.pricing_type.to);
		        	 var from = mwb_wgm.pricing_type.from;
		        	 var to = mwb_wgm.pricing_type.to;
		        	 if(price > to || price < from){
		        		error = true;
		 	        	$("#mwb_wgm_price").addClass("mwb_wgm_error");
		 	        	html+="<li><b>";
		 	        	html+=mwb_wgm.price_range;
		 	        	html+="</li>";
		        	 } 	 
		        }	
		        html += "</ul>";
		        if(error){
		        	$("#mwb_wgm_error_notice").html(html);
		        	$("#mwb_wgm_error_notice").show();
		        	jQuery('html, body').animate({
				        scrollTop: jQuery(".woocommerce-page").offset().top
				    }, 800);
				    $(".single_add_to_cart_button").removeClass("loading");
		        }
		        else{
		        	$("#mwb_wgm_error_notice").html("");
		        	$("#mwb_wgm_error_notice").hide();
		        	// $(this).closest("form.cart" ).submit();
		        	return true;
		        }
		        	
		     }
    	});

    /* Adds the Preview Validtion Here*/

	$( '#mwg_wgm_preview_email' ).click( function() {
		
		$("#mwb_wgm_error_notice").hide();
        var from_mail = $("#mwb_wgm_from_name").val();
        var message = $("#mwb_wgm_message").val();
        message = message.trim();
        var regex = /(<([^>]+)>)/ig;
        var message = message.replace(regex,'');
        var price = $("#mwb_wgm_price").val();
        var error = false;
        var product_type = mwb_wgm.pricing_type.type;
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,5})+$/;
        var html = "<ul>";

        if($("#mwb_wgm_to_email_send").val()=='Mail to recipient')
        {
        	var to_mail = $("#mwb_wgm_to_email").val();
        	if(to_mail == null || to_mail == "")
	        {
	        	error = true;
	        	$("#mwb_wgm_to_email").addClass("mwb_wgm_error");
	        	html+="<li><b>";
	        	html+=mwb_wgm.to_empty;
	        	html+="</li>";
	        } 
	        else if(!to_mail.match(mailformat))
	        {
	        	error = true;
	        	$("#mwb_wgm_to_email").addClass("mwb_wgm_error");
	        	html+="<li><b>";
	        	html+=mwb_wgm.to_invalid;
	        	html+="</li>";
	        }
        }
        else{
        	var to_mail = $("#mwb_wgm_to_download").val();
        	if(to_mail == null || to_mail == "")
			        {
			        	error = true;
			        	$("#mwb_wgm_to_download").addClass("mwb_wgm_error");
			        	html+="<li><b>";
			        	html+=mwb_wgm.to_empty_name;
			        	html+="</li>";
			        }
        }      

        if(price == null || price == "")
        {
        	error = true;
        	$("#mwb_wgm_price").addClass("mwb_wgm_error");
        	html+="<li><b>";
        	html+=mwb_wgm.price_field;
        	html+="</li>";
        }
        	
        if(from_mail == null || from_mail == "")
        {
        	error = true;
        	$("#mwb_wgm_from_name").addClass("mwb_wgm_error");
        	html+="<li><b>";
        	html+=mwb_wgm.from_empty;
        	html+="</li>";
        }
        if(message == null || message == "")
        {
        	error = true;
        	$("#mwb_wgm_message").addClass("mwb_wgm_error");
        	html+="<li><b>";
        	html+=mwb_wgm.msg_empty;
        	html+="</li>";
        }
        else if( message.length > mwb_wgm.msg_length )
        {
        	error = true;
        	$("#mwb_wgm_message").addClass("mwb_wgm_error");
        	html+="<li><b>";
        	html+=mwb_wgm.msg_length_err;
        	html+="</li>";
        }
        if(product_type == "mwb_wgm_range_price")
        {
        	 var from = mwb_wgm.pricing_type.from;
        	 var to = mwb_wgm.pricing_type.to;
        	 //to = parseInt(to);
        	 //from = parseInt(from);
        	 //price = parseInt(price);
        	 
        	 if(price > to || price < from)
        	 {
        		error = true;
 	        	$("#mwb_wgm_price").addClass("mwb_wgm_error");
 	        	html+="<li><b>";
 	        	html+=mwb_wgm.price_range;
 	        	html+="</li>";
        	 } 	 
        }	
        html += "</ul>";
        if(error)
        {
        	$("#mwb_wgm_error_notice").html(html);
        	$("#mwb_wgm_error_notice").show();
        	//MWB code for woodmart theme
        	$("#mwb_wgm_error_notice").removeClass('hidden-notice');
        	//MWB code for woodmart theme
        	jQuery('html, body').animate({
		        scrollTop: jQuery(".woocommerce-page").offset().top
		    }, 800);
        }
        else
        {	
	    	var product_id = mwb_wgm.product_id;
			var data = {
			      action:'mwb_wgc_preview_thickbox_rqst',
				  price:price,
				  to:to_mail,
				  from:from_mail,
				  message:message,
				  product_id:product_id,
				  mwb_nonce:mwb_wgm.mwb_wgm_nonce
			   };
	        	$.ajax({
	    			url: mwb_wgm.ajaxurl, 
	    			type: "POST",  
	    			data: data,
	    			success: function(response) 
	    			{
	    				$("#mwg_wgm_preview_email").show();
	    				tb_show("", response);
	    			}
	    		});
        }	
	});    	
	});
})( jQuery );