<?php
// This is Redeem Section tab page ...

if ( ! defined( 'ABSPATH') ) {
	exit;
}

if(isset($_POST['wcgm_generate_offine_redeem_url'])){
	global $woocommerce;
	$client_name = isset($_POST['wcgm_offine_redeem_name'])? sanitize_text_field( $_POST['wcgm_offine_redeem_name'] ):'';
	$client_email = isset($_POST['wcgm_offine_redeem_email'])? sanitize_text_field( $_POST['wcgm_offine_redeem_email'] ):'';
	$enable = isset($_POST['wcgm_offine_redeem_enable'])? sanitize_text_field( $_POST['wcgm_offine_redeem_enable'] ):'';
	$client_license_code = get_option( 'mwb_gw_lcns_key');
	$client_domain = home_url();
	$currency = get_option('woocommerce_currency');
	$client_currency = get_woocommerce_currency_symbol();	
	$curl_data = array(
		'user_name' => $client_name,
		'email' => $client_email,
		'license' => $client_license_code,
		'domain' => $client_domain,
		'currency' => $client_currency,
		
	);

	$redeem_data = get_option('giftcard_offline_redeem_link',true);

	$url ='https://gifting.makewebbetter.com/api/generate';
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_data);

    $response = curl_exec($ch);
    $response = json_decode( $response  );

 
    if( isset( $response->status) && $response->status == 'success'){
    	
    	$mwb_redeem_link['shop_url'] = $response->shop_url;
    	$mwb_redeem_link['embed_url'] = $response->embed_url;
    	$mwb_redeem_link['user_id'] = $response->user_id;
    	update_option( 'giftcard_offline_redeem_link',$mwb_redeem_link);
    }
  
	update_option( 'giftcard_offline_redeem_settings',$curl_data);
}
else if(isset($_POST['remove_giftcard_redeem_details']) ){


	global $woocommerce;
	$offine_giftcard_redeem_details = get_option( 'giftcard_offline_redeem_link');
	$userid = $offine_giftcard_redeem_details['user_id'];
		$client_domain = home_url();
		$url ='https://gifting.makewebbetter.com/api/generate/remove';
	
		$curl_data = array('user_id' =>$userid ,'domain' => $client_domain);
		
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_data);

	    $response = curl_exec($ch);
	    $response = json_decode( $response  );
	   
      	if( isset( $response->status) && $response->status == 'success'){
      		delete_option( 'giftcard_offline_redeem_link');
      		delete_option( 'giftcard_offline_redeem_settings');
      	}
		else if(isset( $response->status) && $response->status == 'error'){
			echo $response->message;
		}
	
}
else if(isset($_POST['update_giftcard_redeem_details'])){

	$offine_giftcard_redeem_details = get_option( 'giftcard_offline_redeem_link');
	$userid = $offine_giftcard_redeem_details['user_id'];
	$client_domain = home_url();
	$url ='https://gifting.makewebbetter.com/api/generate/update';

	$client_license_code = get_option( 'mwb_gw_lcns_key');
	
	if($client_license_code != ''){
		$curl_data = array('user_id' =>$userid ,'domain' => $client_domain,'license' =>$client_license_code);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_data);

		$response = curl_exec($ch);
		$response = json_decode( $response  );		
		if( isset( $response->status) && $response->status == 'success'){
			$offine_giftcard_redeem_details ['license'] =$client_license_code;
			update_option( 'giftcard_offline_redeem_link',$offine_giftcard_redeem_details);
		}
		else if(isset( $response->status) && $response->status == 'error'){
			echo $response->message;
		}
	}
}

$offline_giftcard_settings = get_option('giftcard_offline_redeem_settings',true);
$current_user = wp_get_current_user(); 
$offine_giftcard_redeem_link = get_option('giftcard_offline_redeem_link',true);

?>
<h3 class="mwb_wgm_overview_heading text-center"><?php _e('Gift Card  Redeem / Recharge ', MWB_WGM_DOMAIN )?></h3>
<div class="mwb_table">
	<div style="display: none;" class="loading-style-bg" id="mwb_wgm_loader">
		<img src="<?php echo MWB_WGC_URL;?>assets/images/loading.gif">
	</div>

	<div class="mwb_redeem_div_wrapper">
		<?php if( !isset($offine_giftcard_redeem_link ['shop_url']) ||  $offine_giftcard_redeem_link['shop_url'] == ''){  ?>
			<div>
				<div class="mwb-giftware-reddem-image text-center">
					<img src="<?php echo MWB_WGC_URL.'assets/images/giftware-redeem-image.png'?>" alt="GiftWare">
					<div class="mwb_giftware_reddem_link_wrapper">
						<a href="#" class="generate_link"><i class="fas fa-link"></i><?php _e(' Get me My FREE redeem Link ', MWB_WGM_DOMAIN )?></a>
						<span><?php _e('(you can delete your redeem link anytime)', MWB_WGM_DOMAIN )?></span>
					</div>
				</div>

				<div class="mwb_redeem_main_content">
					<h2 class="text-left"><?php _e('Hello Dear', MWB_WGM_DOMAIN )?></h2>	
					<p><?php _e(' We are thrilled to announce that we have launched a', MWB_WGM_DOMAIN )?><span class="mwb-reddem-free-text"><?php _e('FREE', MWB_WGM_DOMAIN )?></span><?php _e('service to simplify the problem of redeeming giftcards at retail store', MWB_WGM_DOMAIN )?></p>

					<p><?php _e('We have made this just on your demand so we would love your suggestion to improve it.', MWB_WGM_DOMAIN )?></p>
				</div>

				
				<h3 class="text-center"><?php _e('What it Contains', MWB_WGM_DOMAIN )?></h3>	
				<ul class="mwb_redeem_listing">	
					<li class="mwb_redeem_item scan"> <div class="mwb_redeem_content"><?php _e('Scan', MWB_WGM_DOMAIN )?></div> <div class="mwb_redeem_arrow"><i class="fas fa-arrows-alt-h"></i></div></li>	
					<li class="mwb_redeem_item redeem"> <div class="mwb_redeem_content"><?php _e('Redeem', MWB_WGM_DOMAIN )?></div> <div class="mwb_redeem_arrow"><i class="fas fa-arrows-alt-h"></i></div></li>
					<li class="mwb_redeem_item recharge"> <div class="mwb_redeem_content"><?php _e('Recharge', MWB_WGM_DOMAIN )?></div> <div class="mwb_redeem_arrow"><i class="fas fa-arrows-alt-h"></i></div></li>
					<li class="mwb_redeem_item reports"> <div class="mwb_redeem_content"><?php _e('Reports', MWB_WGM_DOMAIN )?></div></li>
				</ul>
			</div>	
		<?php  }else { ?>
			
			<div>

				<table class="mwb_redeem_details">
			
					<thead>
						<tr>
							<th colspan="2"><?php _e('Your Gift Card Redeem Details ', MWB_WGM_DOMAIN )?></th>
						</tr>
					</thead>
					<tbody>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="wcgw_plugin_enable"><?php _e('Giftcard Redeem Link', MWB_WGM_DOMAIN )?></label>
							</th>
							<td class="forminp forminp-text">
								<?php
								$attribut_description = __('please open the link to redeem the giftcard',MWB_WGM_DOMAIN );
								echo wc_help_tip( $attribut_description );
								?>
								<label for="wcgw_plugin_enable">
									<input type="text" name="wcgm_offine_redeem_link" id="wcgm_offine_redeem_link" class="input-text" value="<?php if(isset($offine_giftcard_redeem_link ['shop_url']) &&  $offine_giftcard_redeem_link['shop_url'] !== ''){ echo $offine_giftcard_redeem_link['shop_url'];  } ?>">
									<div class="mwb-giftware-copy-icon" >
										<button  class="mwb_link_copy mwb_redeem_copy" data-clipboard-target="#wcgm_offine_redeem_link" title="copy">
											<i class="far fa-copy" ></i>
										</button>
										
									</div>	
								</label>

							</td>
						</tr>

						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="wcgw_plugin_enable"><?php _e('Embedded Link', MWB_WGM_DOMAIN )?></label>
							</th>
							<td class="forminp forminp-text">
								<?php
								$attribut_description = __('Enter this code to add the redeem page in your site',MWB_WGM_DOMAIN );
								echo wc_help_tip( $attribut_description );
								?>
								<textarea cols="20" rows="3" id="mwb_gw_embeded_input_text"><?php if(isset($offine_giftcard_redeem_link ['embed_url']) &&  $offine_giftcard_redeem_link['embed_url'] !== ''){ echo trim($offine_giftcard_redeem_link['embed_url']);  } ?>
								</textarea>
								<div class="mwb-giftware-copy-icon">									
									<button  class="mwb_embeded_copy mwb_redeem_copy" data-clipboard-target="#mwb_gw_embeded_input_text" title="copy">
										<i class="far fa-copy" ></i>
									</button>
								</div>
							</td>
						</tr>
						<tr valign="top">
							<td>
								<input type="submit" name="remove_giftcard_redeem_details" class="remove_giftcard_redeem_details"  class="input-text" value = 'Remove Details' >
							</td>
							<td>
								<a target="_blank" href="<?php if(isset($offine_giftcard_redeem_link ['shop_url']) &&  $offine_giftcard_redeem_link['shop_url'] !== ''){ echo $offine_giftcard_redeem_link['shop_url'];  } ?>" class= "mwb_gw_open_redeem_link"><?php _e('Open Shop', MWB_WGM_DOMAIN )?></a>
							</td>
							
						</tr>
						<?php if($offine_giftcard_redeem_link['license'] =='') { ?>
							<tr>
								<td colspan="2">
									<?php _e('This is your limited  account so please purchase the pro and update the details .', MWB_WGM_DOMAIN )?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<p><b><?php _e('To use redeem link as it is, follow the steps below', MWB_WGM_DOMAIN )?></b></p>
				<ol>
					<li><?php _e('Click on Open Shop button and login using the credentials provided in the received email', MWB_WGM_DOMAIN )?></li>
					<li><?php _e('Start Scan/Fetch and Redeem/Recharge', MWB_WGM_DOMAIN )?></li>
				</ol>

				<p><b><?php _e('To use the redeem link on the web store follow the steps below', MWB_WGM_DOMAIN )?></b></p>
				<ol>
					<li><?php _e('Create a page', MWB_WGM_DOMAIN )?></li>
					<li><?php _e('Copy the embed link and paste it in the created page', MWB_WGM_DOMAIN )?></li>
					<li><?php _e('Login using the credentials given in the received email', MWB_WGM_DOMAIN )?></li>
					<li><?php _e('Start Scan/Fetch and Redeem/Recharge', MWB_WGM_DOMAIN )?></li>
				</ol>

				<p><b><?php _e('To use the redeem link on this POS system, follow the steps below', MWB_WGM_DOMAIN )?></b></p>
				<ol>
					<li><?php _e('Copy the embed link and paste it on any page at POS', MWB_WGM_DOMAIN )?></li>
					<li><?php _e('Login using the credentials given in the received email', MWB_WGM_DOMAIN )?></li>
					<li><?php _e('Start Scan/Fetch and Redeem/Recharge', MWB_WGM_DOMAIN )?></li>
				</ol>

			</div>
			<?php	} ?>
		
		<div class="mwb_wgm_video_wrapper">
            <h3><?php _e('See it in Action', MWB_WGM_DOMAIN )?></h3>
            <iframe height="411" src="https://www.youtube.com/embed/H1cYF4F5JA8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>		
		<!-- <div class="text-center">
			<H2>How it Work </H2>	
			<ul class="text-left mwb-readem-work-listing">
				<li><i class="far fa-check-circle"></i> Generate the Link</li>
				<li><i class="far fa-check-circle"></i> Genertaed link will be mailed to you</li>
				<li><i class="far fa-check-circle"></i> Click on the link</li>
				<li><i class="far fa-check-circle"></i> Login</li>
				<li><i class="far fa-check-circle"></i> Enjoy</li>
			</ul>
		</div> -->
		
	</div>


	<div class="mwb_redeem_registraion_div" style="display:none;">
	<div class=" mwb_gw_general_setting">
			<table class="form-table">
			
				<tbody>			
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wcgw_plugin_enable"><?php _e('Email', MWB_WGM_DOMAIN )?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$attribut_description = __('Enter the email for account creation',MWB_WGM_DOMAIN );
							echo wc_help_tip( $attribut_description );
							?>
							<label for="wcgw_plugin_enable">
								<input type="email" name="wcgm_offine_redeem_email" id="wcgm_offine_redeem_email" class="input-text" value="<?php  echo $current_user->user_email; ?> ">
							</label>						
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wcgw_plugin_enable"><?php _e('Name', MWB_WGM_DOMAIN )?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$attribut_description = __('Enter the name for account creation',MWB_WGM_DOMAIN );
							echo wc_help_tip( $attribut_description );
							?>
							<label for="wcgw_plugin_enable">
								<input type="text" name="wcgm_offine_redeem_name" id="wcgm_offine_redeem_name" class="input-text" value="<?php  echo $current_user->display_name; ?> ">
							</label>						
						</td>
					</tr>			

					<tr valign="top">
						
						<td class="forminp forminp-text text-center" colspan="2">
						
							<label for="wcgw_plugin_enable">
								<input type="submit" name="wcgm_generate_offine_redeem_url" id="wcgm_generate_offine_redeem_url" class="input-text" value = 'Generate Link'>
							</label>						
						</td>
					</tr>				
				</tbody>				
			</table>
			<span class="mwb-redeem-pop-close"><i class="fas fa-times"></i></span>
		</div>
	</div>			
</div>