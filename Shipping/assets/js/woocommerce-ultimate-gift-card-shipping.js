jQuery(document).ready(function($){
$( '.mwb_gw_send_giftcard' ).change( function(){

		var radioVal = $(this).val();
		if(radioVal == "normal_mail"){
				
         	$('.mwb_name_fieldss').hide();
	     }
	     else if( radioVal == "download" ){
	     	$('.mwb_name_fieldss').show(); 
	     }
	     else if( radioVal == "shipping" ){
	     	$('.mwb_name_fieldss').show();
	     }
	     else if( radioVal == "customer_choose" ){
	     	$('.mwb_name_fieldss').show();
	     }
		
	} );
});