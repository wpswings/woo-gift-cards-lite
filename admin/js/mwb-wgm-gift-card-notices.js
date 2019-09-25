jQuery(document).ready(function($){
	$(document).on('click','#dismiss_notice',function(e){
			e.preventDefault();
			var data = {
				action:'mwb_wgm_dismiss_notice',
				mwb_nonce:mwb_wgm_notice.mwb_wgm_nonce
			};
	      	$.ajax({
	  			url: mwb_wgm_notice.ajaxurl, 
	  			type: "POST",  
	  			data: data,
	  			success: function(response) 
	  			{	
	  				window.location.reload();
	  			}
	  		});
	});
});