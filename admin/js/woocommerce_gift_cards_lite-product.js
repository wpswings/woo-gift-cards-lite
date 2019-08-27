jQuery(document).ready(function($){
	$(document).on('change','#mwb_wgm_email_template',function(){
		var template_ids = $(this).val();

			jQuery('#mwb_wgm_loader').show();
			var data = {
				action:'mwb_wgm_append_default_template',
				template_ids:template_ids,
				mwb_nonce:mwb_wgm.mwb_wgm_nonce
			};
	      	$.ajax({
	  			url: mwb_wgm.ajaxurl, 
	  			type: "POST",  
	  			data: data,
	  			dataType :'json',
	  			success: function(response) 
	  			{	
		  			if(response.result == 'success')
                    {
                        var templateid = response.templateid;
                        var option = '';
                        for(key in templateid)
                        {
                            option += '<option value="'+key+'">'+templateid[key]+'</option>';
                        } 
                        jQuery("#mwb_wgm_email_defualt_template").html(option);
                    	jQuery("#mwb_wgm_loader").hide();
                    }
                    else if(response.result == 'no_ids')
                    {
                    	 var option = '';
                    	 option = '<option value="">'+mwb_wgm.append_option_val+'</option>';
                    	 jQuery("#mwb_wgm_email_defualt_template").html(option);
                    	jQuery("#mwb_wgm_loader").hide();
                    }
	  			}
	  		});
	});

});