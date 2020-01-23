<?php
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( isset( $_POST['mwb_wgm_other_setting_save'] ) ) {
	if ( isset( $_REQUEST['mwb-wgc-nonce'] ) && wp_verify_nonce( sanitize_key( wp_unslash( $_REQUEST['mwb-wgc-nonce'] ) ), 'mwb-wgc-nonce' ) ) {
		unset( $_POST['mwb_wgm_other_setting_save'] );
		do_action( 'mwb_wgm_other_setting_save' );

		$safe_settings = array();

		$safe_settings['mwb_wgm_other_setting_upload_logo'] = isset( $_POST['mwb_wgm_other_setting_upload_logo'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_wgm_other_setting_upload_logo'] ) ) : '';

		$safe_settings['mwb_wgm_other_setting_giftcard_subject'] = isset( $_POST['mwb_wgm_other_setting_giftcard_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['mwb_wgm_other_setting_giftcard_subject'] ) ) : '';

		$safe_settings['mwb_wgm_other_setting_giftcard_html'] = isset( $_POST['mwb_wgm_other_setting_giftcard_html'] ) ? wp_kses_post( wp_unslash( $_POST['mwb_wgm_other_setting_giftcard_html'] ) ) : '';

		foreach ( $safe_settings as $key => $value ) {
			update_option( $key, $value );
		}

		?>
		<div class="notice notice-success is-dismissible"> 
			<p><strong><?php esc_html_e( 'Settings saved', 'woocommerce_gift_cards_lite' ); ?></strong></p>
			<button type="button" class="notice-dismiss">
				<span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice', 'woocommerce_gift_cards_lite' ); ?></span>
			</button>
		</div>
		<?php
	}
}
$mwb_wgm_other_setting_upload_logo = get_option( 'mwb_wgm_other_setting_upload_logo', false );
$giftcard_giftcard_subject = get_option( 'mwb_wgm_other_setting_giftcard_subject', false );
$giftcard_giftcard_subject = stripcslashes( $giftcard_giftcard_subject );
$custom_giftcard_html_string = get_option( 'mwb_wgm_other_setting_giftcard_html', false );
$custom_giftcard_html = stripcslashes( $custom_giftcard_html_string );
?>
<h3 class="mwb_wgm_overview_heading"><?php esc_html_e( 'Create Custom Email Template', 'woocommerce_gift_cards_lite' ); ?></h3>
<div class="mwb_wgm_table_wrapper">
	<div id="mwb_wgm_manage_template_wrapper">
		<div class="postbox" id="mwb_wgm_mail_instruction" style="display: block;">
			<h3 class="hndle"><span><?php esc_html_e( 'Instruction for using Shortcode', 'woocommerce_gift_cards_lite' ); ?></span></h3>
			<div class="inside">
				<table  class="form-table">
					<tr>
						<th><?php esc_html_e( 'SHORTCODE', 'woocommerce_gift_cards_lite' ); ?></th>
						<th><?php esc_html_e( 'DESCRIPTION.', 'woocommerce_gift_cards_lite' ); ?></th>			
					</tr>
					<tr>
						<td>[LOGO]</td>
						<td><?php esc_html_e( 'Replace with logo of company on email template.', 'woocommerce_gift_cards_lite' ); ?></td>			
					</tr>
					<tr>
						<td>[TO]</td>
						<td><?php esc_html_e( 'Replace with email of user to which giftcard send.', 'woocommerce_gift_cards_lite' ); ?></td>
					</tr>
					<tr>
						<td>[FROM]</td>
						<td><?php esc_html_e( 'Replace with email/name of the user who send the giftcard.', 'woocommerce_gift_cards_lite' ); ?></td>
					</tr>
					<tr>
						<td>[MESSAGE]</td>
						<td><?php esc_html_e( 'Replace with Message of user who send the giftcard.', 'woocommerce_gift_cards_lite' ); ?></td>
					</tr>
					<tr>
						<td>[AMOUNT]</td>
						<td><?php esc_html_e( 'Replace with Giftcard Amount.', 'woocommerce_gift_cards_lite' ); ?></td>
					</tr>
					<tr>
						<td>[COUPON]</td>
						<td><?php esc_html_e( 'Replace with Giftcard Coupon Code.', 'woocommerce_gift_cards_lite' ); ?></td>
					</tr>
					<tr>
						<td>[DEFAULTEVENT]</td>
						<td><?php esc_html_e( 'Replace with Default event image set on Setting.', 'woocommerce_gift_cards_lite' ); ?></td>
					</tr>
					<tr>
						<td>[EXPIRYDATE]</td>
						<td><?php esc_html_e( 'Replace with Giftcard Expiry Date.', 'woocommerce_gift_cards_lite' ); ?></td>
					</tr>				
					<?php
					do_action( 'mwb_wgm_custom_shortcode' );
					?>
				</table>
			</div>
		</div>
		<table class="form-table mwb_wgm_other_setting">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wgm_other_setting_upload_logo"><?php esc_html_e( 'Upload Default Logo', 'woocommerce_gift_cards_lite' ); ?></label>
					</th>
					<td class="forminp forminp-text">
						<?php
						$attribute_description = __( 'Upload the image which is used as logo on your Email Template.', 'woocommerce_gift_cards_lite' );
						echo wp_kses_post( wc_help_tip( $attribute_description ) );

						?>
						<input type="text" readonly class="mwb_wgm_other_setting_upload_logo_value mwb_wgm_new_woo_ver_style_text" id="mwb_wgm_other_setting_upload_logo" name="mwb_wgm_other_setting_upload_logo" value="<?php echo esc_attr( $mwb_wgm_other_setting_upload_logo ); ?>"/>
						<input class="mwb_wgm_other_setting_upload_logo button"  type="button" value="<?php esc_attr_e( 'Upload Logo', 'woocommerce_gift_cards_lite' ); ?>" />

						<p id="mwb_wgm_other_setting_remove_logo">
							<span class="mwb_wgm_other_setting_remove_logo">
								<img src="" width="100px" height="100px" id="mwb_wgm_other_setting_upload_image">
								<span class="mwb_wgm_other_setting_remove_logo_span">X</span>
							</span>
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wgm_other_setting_giftcard_subject"><?php esc_html_e( 'Giftcard Email Subject', 'woocommerce_gift_cards_lite' ); ?></label>
					</th>
					<td class="forminp forminp-text">
						<label for="mwb_wgm_other_setting_giftcard_subject">
							<?php
							$attribute_description = __( 'Email Subject for Giftcard Mail.', 'woocommerce_gift_cards_lite' );
							echo wp_kses_post( wc_help_tip( $attribute_description ) );
							?>
							<input type="text" class= "mwb_wgm_new_woo_ver_style_text" value="<?php echo esc_attr( $giftcard_giftcard_subject ); ?>" name="mwb_wgm_other_setting_giftcard_subject" class="mwb_wgm_other_setting_giftcard_subject" id="mwb_wgm_other_setting_giftcard_subject">

						</label>
						<p class="description"><?php esc_html_e( 'Use [BUYEREMAILADDRESS] shortcode as buyer email address to be placed dynamically.', 'woocommerce_gift_cards_lite' ); ?></p>					
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="mwb_wgm_other_setting_giftcard_html"><?php esc_html_e( 'Custom HTML', 'woocommerce_gift_cards_lite' ); ?></label>
					</th>
					<td class="forminp forminp-text">
						<?php
						$attribute_description = __( 'Write html for giftcard Template.', 'woocommerce_gift_cards_lite' );
						echo wp_kses_post( wc_help_tip( $attribute_description ) );
						?>
						<label for="mwb_wgm_other_setting_giftcard_html">
							<?php
							if ( empty( $custom_giftcard_html ) ) {
								$custom_giftcard_html = '<table class="email-container" style="margin: auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center">
								<tbody>
								<tr>
								<td style="text-align: center; background: #0e0149;">
								<p style="color: #0e0149; font-size: 25px; font-family: sans-serif; margin: 20px; text-align: left;"><strong>[LOGO]</strong></p>
								</td>
								</tr>
								</tbody>
								</table>
								<table class="email-container" style="margin: auto;" border="0" width="600" cellspacing="0" cellpadding="0" align="center">
								<tbody>
								<tr>
								<td style="padding-bottom: 0px;" bgcolor="#f6f6f6"></td>
								</tr>
								<tr>
								<td style="padding: 19px 30px; text-align: center; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; color: #555555;" bgcolor="#d6ccfd"></td>
								</tr>
								<tr style="background-color: #0e0149;">
								<td style="color: #fff; font-size: 20px; letter-spacing: 0px; margin: 0; text-transform: uppercase; background-color: #0e0149; padding: 20px 10px; line-height: 0;">
								<p style="border: 2px dashed #ffffff; color: #fff; font-size: 20px; letter-spacing: 0px; padding: 30px 10px; line-height: 30px; margin: 0; text-transform: uppercase; background-color: #0e0149; text-align: center;">Coupon Code<span style="display: block; font-size: 25px;">[COUPON]</span><span style="display: block;">Ed:[EXPIRYDATE]</span></p>
								</td>
								</tr>
								<tr>
								<td dir="ltr" style="padding-bottom: 34px;" align="center" valign="top" bgcolor="#d7ceff" width="100%">
								<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
								<tbody>
								<tr>
								<td class="stack-column-center" style="vertical-align: top;" width="50%">
								<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
								<tbody>
								<tr>
								<td dir="ltr" style="padding: 15px 25px 0;" valign="top">[DEFAULTEVENT]</td>
								</tr>
								</tbody>
								</table>
								</td>
								<td class="stack-column-center" style="vertical-align: top;" width="50%">
								<table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
								<tbody>
								<tr>
								<td class="center-on-narrow" dir="ltr" style="font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff; padding: 15px; text-align: left;" valign="top">
								<p style="font-size: 15px; line-height: 24px; text-align: justify; color: #535151; min-height: 150px; white-space: pre-line;">[MESSAGE]</p>
								</td>
								</tr>
								<tr>
								<td class="mail-content" style="word-wrap: break-word; font-family: sans-serif; padding: 6px 15px;"><span style="color: #535151; font-size: 15px; float: left; vertical-align: top; display-inline: block;width: 60px;">From- </span> <span style="color: #535151; font-size: 14px; vertical-align: top; display: inline-block; float: left;">[FROM]</span></td>
								</tr>
								<tr>
								<td style="word-wrap: break-word; font-family: sans-serif; padding: 6px 15px;"><span style="color: #535151; font-size: 15px; float: left;width: 60px; display: inline-block; vertical-align: top;">To- </span> <span style="color: #535151; font-size: 14px; float: left; vertical-align: top;">[TO]</span></td>
								</tr>
								<tr>
								<td style="padding: 5px 15px; word-wrap: break-word;"><span style="color: #0e0149; font-size: 23.96px; vertical-align: top;"><strong>[AMOUNT]/-</strong> </span></td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								<tr>
								<td bgcolor="#0e0149">
								<table border="0" width="100%" cellspacing="0" cellpadding="0">
								<tbody>
								<tr>
								<td style="padding: 20px 30px; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #ffffff;">
								<p style="font-weight: bold; text-align: center;"></p>
								</td>
								</tr>
								</tbody>
								</table>
								</td>
								</tr>
								</tbody>
								</table>';

							}
							$content = stripcslashes( $custom_giftcard_html );
							$editor_id = 'mwb_wgm_other_setting_giftcard_html';
							$settings = array(
								'media_buttons'    => false,
								'drag_drop_upload' => true,
								'dfw'              => true,
								'teeny'            => true,
								'editor_height'    => 200,
								'editor_class'     => 'mwb_wgm_new_woo_ver_style_textarea',
								'textarea_name'    => 'mwb_wgm_other_setting_giftcard_html',
							);
							wp_editor( $content, $editor_id, $settings );
							?>
						</label>						
					</td>
				</tr>		
			</tbody>
		</table>
	</div>	
</div>
<p class="submit mwb_wgm_email_template">
	<input type="submit" value="<?php esc_attr_e( 'Save changes', 'woocommerce_gift_cards_lite' ); ?>" class="button-primary woocommerce-save-button" name="mwb_wgm_other_setting_save">
</p>
<div class="clear"></div>
