<?php 
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$saved = false;
$mwb_down = false;
if(isset($_POST['mwb_gw_shipping_setting_save']))
{

	unset($_POST['mwb_gw_shipping_setting_save']);
	$mwb_gw_email_to_recipient = isset($_POST['mwb_gw_email_to_recipient']) ? 1 : 0;
	$mwb_gw_shipping = isset($_POST['mwb_gw_shipping']) ? 1 : 0;
	$mwb_gw_downloadable = isset($_POST['mwb_gw_downloadable']) ? 1 : 0;

	$mwb_gw_customer_selection = array(
							'Email_to_recipient' => $mwb_gw_email_to_recipient,
							'Downloadable' =>$mwb_gw_downloadable,
							'Shipping' =>$mwb_gw_shipping
									);

	if(!isset($_POST['mwb_gw_send_giftcard']))
	{	
		$_POST['mwb_gw_send_giftcard'] = 'normal_mail';
	}
	elseif(isset($_POST['mwb_gw_send_giftcard']) && $_POST['mwb_gw_send_giftcard'] == 'normal_mail' )
	{
		$_POST['mwb_gw_send_giftcard'] = 'normal_mail';
	}
	elseif(isset($_POST['mwb_gw_send_giftcard']) && $_POST['mwb_gw_send_giftcard'] == 'download' )
	{
		$_POST['mwb_gw_send_giftcard'] = 'download';
	}
	elseif(isset($_POST['mwb_gw_send_giftcard']) && $_POST['mwb_gw_send_giftcard'] == 'shipping' )
	{
		$_POST['mwb_gw_send_giftcard'] = 'shipping';
	}
	elseif(isset($_POST['mwb_gw_send_giftcard']) && $_POST['mwb_gw_send_giftcard'] == 'customer_choose' )
	{
		$_POST['mwb_gw_send_giftcard'] = 'customer_choose';
	}
	if(!isset($_POST['mwb_gw_other_setting_giftcard_subject_shipping']))
	{
		$_POST['mwb_gw_other_setting_giftcard_subject_shipping'] = '';
	}
	if(!isset($_POST['mwb_gw_general_cart_shipping_enable']))
	{
		$_POST['mwb_gw_general_cart_shipping_enable'] = 'off';
	}
	$postdata = $_POST;
	
	foreach($postdata as $key=>$data)
	{	
		update_option($key, $data);
		if(!$mwb_down){
			$saved = true;
		}
			
	}
	$mwb_gw_method_enable = get_option("mwb_gw_send_giftcard", false);

	if( $mwb_gw_method_enable == 'customer_choose' ){

		if( $mwb_gw_email_to_recipient == '0' && $mwb_gw_shipping == '0' && $mwb_gw_downloadable == '0')
		{
			$mwb_gw_customer_selection = array(
							'Email_to_recipient'=> '1',
							'Downloadable'=>'0',
							'Shipping'	=> '0'
									);
		}
		update_option('mwb_gw_customer_selection',$mwb_gw_customer_selection);
	}
	else
	{
		$mwb_gw_customer_selection = array(
							'Email_to_recipient'=> '0',
							'Downloadable'=>'0',
							'Shipping'	=> '0'
									);
		update_option('mwb_gw_customer_selection',$mwb_gw_customer_selection);
	}

}
if($saved){
	?>
	<div class="notice notice-success is-dismissible"> 
		<p><strong><?php _e('Settings saved',mwb_gw_SD_DOM); ?></strong></p>
		<button type="button" class="notice-dismiss">
			<span class="screen-reader-text"><?php _e('Dismiss this notice',mwb_gw_SD_DOM); ?></span>
		</button>
	</div>
	<?php
}

$gift_cart_ship = get_option("mwb_gw_general_cart_shipping_enable", false);
$mwb_gw_method_enable = get_option("mwb_gw_send_giftcard", 'normal_mail');

$mwb_gw_customer_selection = get_option('mwb_gw_customer_selection',false);

?>
<h3 class="mwb_wgm_overview_heading"><?php _e('Shipping Settings', 'giftware')?></h3>
<div class="mwb_table">
	<?php include(MWB_WGC_DIRPATH.'Shipping/includes/mwb_wgm_ delivery_settings_array .php');?>
<table class="mwb_shippingaddon form-table mwb_gw_general_setting">
	<tbody>
	<?
	foreach( $delivery_setting as $key => $value){
		?>
		<tr valign="top">		
			<th scope="row" class="titledesc">
				<label for= <?php echo $value['id']?><?php echo $value['label']?>></label>
			</th>
			<td class="forminp forminp-text">
				<?php 
				echo wc_help_tip( $value['attribute_description'] );
				?>
				<label for = <?php echo $vlaue['id'];?>>
					<input type="radio" <?php echo ($mwb_gw_method_enable == 'normal_mail')?"checked='checked'":""?> name="mwb_gw_send_giftcard" value="normal_mail"class="mwb_gw_send_giftcard" id= <?php echo $value['id'];?>><?php _e('Enable Email To Recipient.',mwb_gw_SD_DOM);?>
				</label>						
			</td>
		</tr>
		<?php
	}
	?>			
	</tbody>
</table>
</div>
<p class="submit">
	<input type="submit" value="<?php _e('Save changes', mwb_gw_SD_DOM); ?>" class="button-primary woocommerce-save-button" name="mwb_gw_shipping_setting_save" id="mwb_gw_shipping_setting_save">
</p>
<div class="clear"></div>
