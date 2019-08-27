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
require_once MWB_WGC_DIRPATH.'admin/partials/templates/mwb_wgm_settings/mwb_wgm_other_settings_array.php' ;
$flag = false;
$current_tab = "mwb_wgm_other_setting";
if(isset($_POST['mwb_wgm_save_other']))
{
	if( wp_verify_nonce( $_REQUEST['mwb-wgc-nonce'], 'mwb-wgc-nonce' ) ){
		unset( $_POST['mwb_wgm_save_other'] );	
		if($current_tab == "mwb_wgm_other_setting" ) {
			$other_settings_array = array();
			$postdata = $settings_obj->mwb_wgm_sanitize_settings_data( $_POST, $mwb_wgm_other_setting );
			if(isset($postdata) && is_array( $postdata ) && !empty( $postdata ) ){
				foreach($postdata as $key => $value){	
					$other_settings_array[$key] = $value;
				}
			}
			if (is_array($other_settings_array) && !empty($other_settings_array)) {
				update_option('mwb_wgm_other_settings',$other_settings_array);
			}
		}
		$flag = true;
	}
}
if($flag){	
	$settings_obj->mwb_wgm_settings_saved();
}
?>
<?php $other_settings = get_option('mwb_wgm_other_settings',true); ?>
<?php if(!is_array($other_settings)): $other_settings = array(); endif;?>
<h3 class="mwb_wgm_overview_heading"><?php _e('Other Settings', MWB_WGM_DOMAIN )?></h3>
<div class="mwb_wgm_table_wrapper">	
	<div class="mwb_table">
		<table class="form-table mwb_wgm_general_setting">
			<tbody>
				<?php
				$settings_obj->mwb_wgm_generate_common_settings( $mwb_wgm_other_setting, $other_settings );
				?>
			</tbody>
		</table>
	</div>	
</div>
<?php
$settings_obj->mwb_wgm_save_button_html("mwb_wgm_save_other");
?>
<div class="clear"></div>
