<?php 
/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * General Settings Template
 */
require_once MWB_WGC_DIRPATH.'admin/partials/templates/mwb_wgm_settings/mwb_wgm_general_settings_array.php' ;
$current_tab = "mwb_wgm_general_setting";
$flag = false;
if( isset( $_POST ['mwb_wgm_save_general'] ) )
{
	if( wp_verify_nonce( $_REQUEST['mwb-wgc-nonce'], 'mwb-wgc-nonce' ) ){		
		unset( $_POST['mwb_wgm_save_general'] );		
		$postdata = $settings_obj->mwb_wgm_sanitize_settings_data( $_POST, $mwb_wgm_general_setting );	
		$general_settings_array = array();
		if($current_tab == "mwb_wgm_general_setting" ) {
			if(isset($postdata) && is_array( $postdata ) && !empty( $postdata ) ){
				foreach($postdata as $key => $value){	
					$general_settings_array[$key] = $value;
				}
			}
			if (is_array($general_settings_array) && !empty($general_settings_array)) {
				$delivery_setting = get_option('mwb_wgm_delivery_settings', array());
				if( empty( $delivery_setting ) ) {
					$delivery_setting['mwb_wgm_send_giftcard'] = 'Mail_To_Recipient';
					update_option('mwb_wgm_delivery_settings', $delivery_setting);
				}
				update_option('mwb_wgm_general_settings',$general_settings_array);
			}
		}
		$flag = true;
	}
}
if($flag){	
	$settings_obj->mwb_wgm_settings_saved();
}
?>
<?php $general_settings = get_option('mwb_wgm_general_settings',true); ?>
<?php if(!is_array($general_settings)): $general_settings = array(); endif;?>
<h3 class="mwb_wgm_overview_heading"><?php _e('General Settings', MWB_WGM_DOMAIN)?></h3>
<div class="mwb_wgm_table_wrapper">	
	<div class="mwb_table">
		<table class="form-table mwb_wgm_general_setting">
			<tbody>
				<?php
				$settings_obj->mwb_wgm_generate_common_settings( $mwb_wgm_general_setting, $general_settings );
				?>
			</tbody>
		</table>
	</div>
	<?php
	do_action('mwb_gw_general_setting');
	?>	
</div>
<?php
$settings_obj->mwb_wgm_save_button_html("mwb_wgm_save_general");
?>
<div class="clear"></div>
