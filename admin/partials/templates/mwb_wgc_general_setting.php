<?php 
/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once(MWB_WGC_DIRPATH.'admin/partials/templates/mwb_wgm_settings/mwb_wgm_general_settings_array.php' );
if( isset( $_POST['mwb_wgm_general_setting_save'] ) )
{
	if( wp_verify_nonce( $_REQUEST['mwb-wgc-nonce'], 'mwb-wgc-nonce' ) ){
		unset($_POST['mwb_wgm_general_setting_save']);
		foreach( $mwb_wgm_general_setting as $key => $data ){
			if (array_key_exists('default', $data)) {
				if( !isset( $_POST [ $data['id'] ] ) ){
					$_POST[$data['id']] = $data['default'] ;
				}
			}
		}
		do_action('mwb_wgm_general_setting_save');
		$postdata = $_POST;
		foreach($postdata as $key => $data)
		{
			if(isset( $data ) && $data != null)
			{
				$data = sanitize_text_field($data);
				update_option($key, $data);		
			}
			elseif ($data == null) {
				delete_option($key, $data);
			}		
		}
		$this->mwb_wgm_success_notice_html();	
	}
}
?>
<h3 class="mwb_wgm_overview_heading"><?php _e('General Settings', 'woocommerce_gift_cards_lite')?></h3>
<div class="mwb_wgm_table_wrapper">	
	<div class="mwb_table">
		<table class="form-table mwb_wgm_general_setting">
			<tbody>
				<?
				foreach( $mwb_wgm_general_setting  as $key=> $value){
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
								<?php if($value['type'] == 'checkbox'){
									$this->mwb_wgm_generate_checkbox_html( $value);
								}
								elseif($value['type'] == 'number'){
									$this->mwb_wgm_generate_input_number_html( $value);
								}
								elseif($value['type'] == 'text'){
									$this->mwb_wgm_generate_input_text_html( $value);
								}?>								
							</label>						
						</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<?php
	do_action('mwb_gw_general_setting');
	?>	
</div>
<div class="clear"></div>
<?php
$this->mwb_wgm_save_button_html("mwb_wgm_general_setting_save");
?>