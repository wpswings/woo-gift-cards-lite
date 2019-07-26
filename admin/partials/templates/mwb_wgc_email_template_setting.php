<?php 
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once( MWB_WGC_DIRPATH.'admin/partials/templates/mwb_wgm_settings/mwb_wgm_mail_template_settings_array.php' ) ;

/*if(isset($_POST['mwb_wgm_email_setting_save']))
{
	unset($_POST['mwb_wgm_email_setting_save']);	
	do_action('mwb_wgm_email_setting_save');
	
	$postdata = $_POST;
	foreach($postdata as $key=>$data)
	{			
		update_option($key, $data);
	}
	
}*/
	/*$mwb_wgm_other_setting_upload_logo = get_option("mwb_wgm_other_setting_upload_logo", false);
	$giftcard_giftcard_subject = get_option("mwb_wgm_other_setting_giftcard_subject", false);
	$giftcard_giftcard_subject = stripcslashes($giftcard_giftcard_subject);
	$custom_giftcard_html_string = get_option("mwb_wgm_other_setting_giftcard_html", false);
	$custom_giftcard_html = stripcslashes($custom_giftcard_html_string);*/
	?>
<h3 class="mwb_wgm_overview_heading"><?php _e('Email Settings', 'giftware')?></h3>
<div class="mwb_table">
<div id="mwb_gw_manage_template_wrapper">
<table class="form-table mwb_gw_other_setting">
	<tbody>
		<?
		foreach( $mwb_wgm_mail_template_settings  as $key=> $value){
			?>
			<tr valign="top">			
				<th scope="row" class="titledesc">
					<label for=<?php echo $value['id']?> ><?php echo $value['label']?></label>
				</th>
				<td class="forminp forminp-text">
					<?php
					echo wc_help_tip( $value['attribute_description'] );
					?>
					<label for = <?php echo $value['id']?>>
						<?php 
						if($value['type'] == 'number'){
							$this->mwb_wgm_generate_input_number_html( $value);
						}
						elseif($value['type'] == 'text'){
							$this->mwb_wgm_generate_input_text_html( $value);
						}
						elseif($value['type'] == 'textWithButton'){
							$this->mwb_wgm_generate_input_text_with_button_html( $value);
						}?>								
					</label>						
				</td>
			</tr>
		<?php
		}
		?>

		
	</table>
</div>
<?php
$this->mwb_wgm_save_button_html("mwb_wgm_email_setting_save");
?>
	