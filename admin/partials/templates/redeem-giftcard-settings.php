<?php
/**
 * Exit if accessed directly
 *
 * @package    Woocommerce_gift_cards_lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Redeem Settings Template
 */
if ( isset( $_POST['wcgm_generate_offine_redeem_url'] ) ) {
	if ( isset( $_REQUEST['mwb-redeem-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mwb-redeem-nonce'] ) ), 'mwb-redeem-nonce' ) ) {
		global $woocommerce;
		$client_name = isset( $_POST['wcgm_offine_redeem_name'] ) ? sanitize_text_field( wp_unslash( $_POST['wcgm_offine_redeem_name'] ) ) : '';
		$client_email = isset( $_POST['wcgm_offine_redeem_email'] ) ? sanitize_text_field( wp_unslash( $_POST['wcgm_offine_redeem_email'] ) ) : '';
		$enable = isset( $_POST['wcgm_offine_redeem_enable'] ) ? sanitize_text_field( wp_unslash( $_POST['wcgm_offine_redeem_enable'] ) ) : '';
		$client_license_code = get_option( 'mwb_uwgc-license-key', '' );
		$client_domain = home_url();
		$currency = get_option( 'woocommerce_currency' );
		$client_currency = get_woocommerce_currency_symbol();
		$curl_data = array(
			'user_name' => $client_name,
			'email' => $client_email,
			'license' => $client_license_code,
			'domain' => $client_domain,
			'currency' => $client_currency,
		);
		$redeem_data = get_option( 'giftcard_offline_redeem_link', true );
		$url = 'https://gifting.makewebbetter.com/api/generate';
		$response = wp_remote_post(
			$url,
			array(
				'method'     => 'POST',
				'timeout'    => 50,
				'user-agent' => '',
				'sslverify'  => false,
				'body'       => $curl_data,
			)
		);
		if ( is_array( $response ) && ! empty( $response ) ) {
			$response = $response['body'];
			$response = json_decode( $response );
			if ( 'error' == $response->status ) {
				$mwb_wgm_error_message = $response->message;
			} else {
				if ( isset( $response->status ) && 'success' == $response->status ) {
					$mwb_redeem_link['shop_url'] = $response->shop_url;
					$mwb_redeem_link['embed_url'] = $response->embed_url;
					$mwb_redeem_link['user_id'] = $response->user_id;
					update_option( 'giftcard_offline_redeem_link', $mwb_redeem_link );
				}
			}
		}
		update_option( 'giftcard_offline_redeem_settings', $curl_data );
	}
} else if ( isset( $_POST['remove_giftcard_redeem_details'] ) ) {
	if ( isset( $_REQUEST['mwb-remove-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mwb-remove-nonce'] ) ), 'mwb-remove-nonce' ) ) {
		global $woocommerce;
		$offine_giftcard_redeem_details = get_option( 'giftcard_offline_redeem_link' );
		$userid = $offine_giftcard_redeem_details['user_id'];
		$client_domain = home_url();
		$url = 'https://gifting.makewebbetter.com/api/generate/remove';

		$curl_data = array(
			'user_id' => $userid,
			'domain' => $client_domain,
		);

		$response = wp_remote_post(
			$url,
			array(
				'timeout' => 50,
				'user-agent' => '',
				'sslverify' => false,
				'body' => $curl_data,
			)
		);

		if ( is_array( $response ) && ! empty( $response ) ) {
			$response = $response['body'];
			$response = json_decode( $response );
			if ( 'error' == $response->status ) {
				$mwb_wgm_error_message = $response->message;
			} else {
				if ( isset( $response->status ) && 'success' == $response->status ) {
					delete_option( 'giftcard_offline_redeem_link' );
					delete_option( 'giftcard_offline_redeem_settings' );
				}
			}
		}
	}
} else if ( isset( $_POST['update_giftcard_redeem_details'] ) ) {
	$offine_giftcard_redeem_details = get_option( 'giftcard_offline_redeem_link' );
	$userid = $offine_giftcard_redeem_details['user_id'];
	$client_domain = home_url();
	$url = 'https://gifting.makewebbetter.com/api/generate/update';
	$client_license_code = get_option( 'mwb_gw_lcns_key', '' );

	if ( '' !== $client_license_code ) {
		$curl_data = array(
			'user_id' => $userid,
			'domain' => $client_domain,
			'license' => $client_license_code,
		);

		$response = wp_remote_post(
			$url,
			array(
				'timeout' => 50,
				'user-agent' => '',
				'sslverify' => false,
				'body' => $curl_data,
			)
		);
		if ( is_array( $response ) && ! empty( $response ) ) {
			$response = $response['body'];
			$response = json_decode( $response );
			if ( 'error' == $response->status ) {
				$mwb_wgm_error_message = $response->message;
			} else {
				if ( isset( $response->status ) && 'success' == $response->status ) {
					$offine_giftcard_redeem_details ['license'] = $client_license_code;
					update_option( 'giftcard_offline_redeem_link', $offine_giftcard_redeem_details );
				}
			}
		}
	}
}
$mwb_current_user = wp_get_current_user();
$offine_giftcard_redeem_link = get_option( 'giftcard_offline_redeem_link', true );
if ( isset( $mwb_wgm_error_message ) && null != $mwb_wgm_error_message ) {
	?>
<div class="notice notice-success is-dismissible"> 
	<p><strong><?php echo wp_kses_post( $mwb_wgm_error_message, 'woocommerce_gift_cards_lite' ); ?></strong></p>
	<button type="button" class="notice-dismiss">
		<span class="screen-reader-text"><?php echo wp_kses_post( 'Dismiss this notice', 'woocommerce_gift_cards_lite' ); ?></span>
	</button>
</div>
	<?php
}
?>
<h3 class="mwb_wgm_overview_heading text-center"><?php esc_html_e( 'Gift Card  Redeem / Recharge ', 'woocommerce_gift_cards_lite' ); ?></h3>
<div class="mwb_table">
	<div style="display: none;" class="loading-style-bg" id="mwb_gw_loader">
		<img src="<?php echo esc_url( MWB_WGC_URL . 'assets/images/loading.gif' ); ?>">
	</div>
	<div class="mwb_redeem_div_wrapper">
		<?php if ( ! isset( $offine_giftcard_redeem_link ['shop_url'] ) || '' == $offine_giftcard_redeem_link['shop_url'] ) { ?>
			<div>
				<div class="mwb-giftware-reddem-image text-center">
					
					<img src="<?php echo esc_url( MWB_WGC_URL . 'assets/images/giftware-redeem-image.png' ); ?>" alt="GiftWare">
					<div class="mwb_giftware_reddem_link_wrapper">
						<a href="#" class="generate_link"><i class="fas fa-link"></i><?php esc_html_e( 'Get me My FREE redeem Link', 'woocommerce_gift_cards_lite' ); ?> </a>
						<span><?php esc_html_e( '(you can delete your redeem link anytime)', 'woocommerce_gift_cards_lite' ); ?></span>
					</div>
				</div>

				<div class="mwb_redeem_main_content">
					<h3 class="text-left"><?php esc_html_e( 'Hello Dear', 'woocommerce_gift_cards_lite' ); ?></h3>	
					<p><?php esc_html_e( 'We are thrilled to announce that we have launched a', 'woocommerce_gift_cards_lite' ); ?><span class="mwb-reddem-free-text"><?php esc_html_e( 'FREE', 'woocommerce_gift_cards_lite' ); ?></span><?php esc_html_e( 'service to simplify the problem of redeeming giftcards at retail store ', 'woocommerce_gift_cards_lite' ); ?></p>

					<p><?php esc_html_e( 'We have made this just on your demand so we would love your suggestion to improve it.', 'woocommerce_gift_cards_lite' ); ?></p>
				</div>

				
				<h3 class="text-center"><?php esc_html_e( 'What it Contains', 'woocommerce_gift_cards_lite' ); ?></h3>	
				<ul class="mwb_redeem_listing">	
					<li class="mwb_redeem_item scan"> <div class="mwb_redeem_content"><?php esc_html_e( 'Scan', 'woocommerce_gift_cards_lite' ); ?></div> <div class="mwb_redeem_arrow"><i class="fas fa-arrows-alt-h"></i></div></li>	
					<li class="mwb_redeem_item redeem"> <div class="mwb_redeem_content"><?php esc_html_e( 'Redeem', 'woocommerce_gift_cards_lite' ); ?></div> <div class="mwb_redeem_arrow"><i class="fas fa-arrows-alt-h"></i></div></li>
					<li class="mwb_redeem_item recharge"> <div class="mwb_redeem_content"><?php esc_html_e( 'Recharge', 'woocommerce_gift_cards_lite' ); ?></div> <div class="mwb_redeem_arrow"><i class="fas fa-arrows-alt-h"></i></div></li>
					<li class="mwb_redeem_item reports"> <div class="mwb_redeem_content"><?php esc_html_e( 'Reports', 'woocommerce_gift_cards_lite' ); ?></div></li>
				</ul>
			</div>	
		<?php } else { ?>
			
			<div>

				<table class="mwb_redeem_details">

					<thead>
						<tr>
							<th colspan="2"><?php esc_html_e( 'Your Gift Card Redeem Details', 'woocommerce_gift_cards_lite' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr valign="top">
							<th scope="row" class="titledesc">
								<label for="wcgw_plugin_enable"><?php esc_html_e( 'Giftcard Redeem Link', 'woocommerce_gift_cards_lite' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<?php
								$attribut_description = __( 'please open the link to redeem the giftcard', 'woocommerce_gift_cards_lite' );/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
								echo wp_kses_post( wc_help_tip( $attribut_description ) ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
								?>
								<label for="wcgw_plugin_enable">
									<input type="text" name="wcgm_offine_redeem_link" id="wcgm_offine_redeem_link" class="input-text" value="<?php
									if ( isset( $offine_giftcard_redeem_link ['shop_url'] ) && '' !== $offine_giftcard_redeem_link['shop_url'] ) {
										echo esc_html( $offine_giftcard_redeem_link['shop_url'] );  }
									?>">
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
								<label for="wcgw_plugin_enable"><?php esc_html_e( 'Embedded Link', 'woocommerce_gift_cards_lite' ); ?></label>
							</th>
							<td class="forminp forminp-text">
								<?php
								$attribut_description = __( 'Enter this code to add the redeem page in your site', 'woocommerce_gift_cards_lite' ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
								echo wc_help_tip( $attribut_description ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
								?>
									<textarea cols="20" rows="3" id="mwb_gw_embeded_input_text"><?php
									if ( isset( $offine_giftcard_redeem_link ['embed_url'] ) && '' !== $offine_giftcard_redeem_link['embed_url'] ) {
										echo esc_html( $offine_giftcard_redeem_link['embed_url'] );//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped.
									}
									?></textarea>
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
									<?php wp_nonce_field( 'mwb-remove-nonce', 'mwb-remove-nonce' ); ?>
								</td>
								<td>
									<a target="_blank" href="
									<?php
									if ( isset( $offine_giftcard_redeem_link ['shop_url'] ) && '' !== $offine_giftcard_redeem_link['shop_url'] ) {
										echo esc_attr( $offine_giftcard_redeem_link['shop_url'] );  }
									?>
										" class= "mwb_gw_open_redeem_link"><?php esc_html_e( 'Open Shop', 'woocommerce_gift_cards_lite' ); ?></a>
									</td>

								</tr>
								<?php if ( isset( $offine_giftcard_redeem_link['license'] ) && '' == $offine_giftcard_redeem_link['license'] ) { ?>
									<tr>
										<td colspan="2">
											<?php esc_html_e( 'This is your limited  account so please purchase the pro and update the details .', 'woocommerce_gift_cards_lite' ); ?>								
										</td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
						<p><b>
							<?php esc_html_e( 'To use redeem link as it is, follow the steps below', 'woocommerce_gift_cards_lite' ); ?></b></p>
							<ol>
								<li><?php esc_html_e( 'Click on Open Shop button and login using the credentials provided in the received email', 'woocommerce_gift_cards_lite' ); ?></li>
								<li><?php esc_html_e( 'Start Scan/Fetch and Redeem/Recharge', 'woocommerce_gift_cards_lite' ); ?></li>
							</ol>

							<p><b><?php esc_html_e( 'To use the redeem link on the web store follow the steps below', 'woocommerce_gift_cards_lite' ); ?></b></p>
							<ol>
								<li><?php esc_html_e( 'Create a page', 'woocommerce_gift_cards_lite' ); ?></li>
								<li><?php esc_html_e( 'Copy the embed link and paste it in the created page', 'woocommerce_gift_cards_lite' ); ?></li>
								<li><?php esc_html_e( 'Login using the credentials given in the received email', 'woocommerce_gift_cards_lite' ); ?></li>
								<li><?php esc_html_e( 'Start Scan/Fetch and Redeem/Recharge', 'woocommerce_gift_cards_lite' ); ?></li>
							</ol>

							<p><b><?php esc_html_e( 'To use the redeem link on this POS system, follow the steps below', 'woocommerce_gift_cards_lite' ); ?></b></p>
							<ol>
								<li><?php esc_html_e( 'Copy the embed link and paste it on any page at POS', 'woocommerce_gift_cards_lite' ); ?></li>
								<li><?php esc_html_e( 'Login using the credentials given in the received email', 'woocommerce_gift_cards_lite' ); ?></li>
								<li><?php esc_html_e( 'Start Scan/Fetch and Redeem/Recharge', 'woocommerce_gift_cards_lite' ); ?></li>
							</ol>

						</div>
					<?php	} ?>

					<div class="mwb_wgm_video_wrapper">
						<h3><?php esc_html_e( 'See it in Action', 'woocommerce_gift_cards_lite' ); ?></h3>
						<iframe height="411" src="https://www.youtube.com/embed/H1cYF4F5JA8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
					</div>
		
	</div>


	<div class="mwb_redeem_registraion_div" style="display:none;">
		<div class=" mwb_gw_general_setting">
			<table class="form-table">

				<tbody>			
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wcgw_plugin_enable"><?php esc_html_e( 'Email', 'woocommerce_gift_cards_lite' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$attribut_description = __( 'Enter the email for account creation', 'woocommerce_gift_cards_lite' );/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
							echo wc_help_tip( $attribut_description ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
							?>
							<label for="wcgw_plugin_enable">
								<input type="email" name="wcgm_offine_redeem_email" id="wcgm_offine_redeem_email" class="input-text" value="<?php echo esc_attr( $mwb_current_user->user_email ); ?> ">
							</label>						
						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wcgw_plugin_enable"><?php esc_html_e( 'Name', 'woocommerce_gift_cards_lite' ); ?></label>
						</th>
						<td class="forminp forminp-text">
							<?php
							$attribut_description = __( 'Enter the name for account creation', 'woocommerce_gift_cards_lite' );/* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
							echo wc_help_tip( $attribut_description ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
							?>
							<label for="wcgw_plugin_enable">
								<input type="text" name="wcgm_offine_redeem_name" id="wcgm_offine_redeem_name" class="input-text" value="<?php echo esc_attr( $mwb_current_user->display_name ); ?> ">
							</label>						
						</td>
					</tr>			

					<tr valign="top">
						
						<td class="forminp forminp-text text-center" colspan="2">

							<label for="wcgw_plugin_enable">
								<input type="submit" name="wcgm_generate_offine_redeem_url" id="wcgm_generate_offine_redeem_url" class="input-text" value = 'Generate Link'>
								<?php wp_nonce_field( 'mwb-redeem-nonce', 'mwb-redeem-nonce' ); ?>
							</label>						
						</td>
					</tr>				
				</tbody>				
			</table>
			<span class="mwb-redeem-pop-close"><i class="fas fa-times"></i></span>
		</div>
	</div>			
</div>
