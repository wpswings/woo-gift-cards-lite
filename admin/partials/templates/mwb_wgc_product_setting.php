<?php 
/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * Product Settings Template
 */
$flag = false;
$current_tab = "mwb_wgm_product_setting"; 
if(isset($_POST['mwb_wgm_save_product']))
{
	unset( $_POST['mwb_wgm_save_product'] );
	if( wp_verify_nonce( $_REQUEST['mwb-wgc-nonce'], 'mwb-wgc-nonce' ) )
	{
		if($current_tab == "mwb_wgm_product_setting" ) 
		{	
			$product_settings_array = array();			
			$postdata = $_POST;
			if(isset($postdata) && is_array( $postdata ) && !empty( $postdata ) ){
				foreach($postdata as $key => $value){	
					$product_settings_array[$key] = $value;
				}
			}
			if (is_array($product_settings_array) && !empty($product_settings_array)) {
				update_option('mwb_wgm_product_settings',$product_settings_array);
			}
		}
		$flag = true;
		
	}
}
require_once MWB_WGC_DIRPATH.'admin/partials/templates/mwb_wgm_settings/mwb_wgm_product_settings_array.php' ;
if($flag){	
	$settings_obj->mwb_wgm_settings_saved();
}
?>
<?php $product_settings = get_option('mwb_wgm_product_settings',true); ?>
<?php if(!is_array($product_settings)): $product_settings = array(); endif;?>
<h2 class="mwb_wgm_overview_heading"><?php _e('Product Settings', MWB_WGM_DOMAIN )?></h2>
<div class="mwb_wgm_table_wrapper">
	<table class="form-table mwb_wgm_product_setting">
		<tbody>
			<?php
				$settings_obj->mwb_wgm_generate_common_settings( $mwb_wgm_product_settings, $product_settings );
				?>
			<?php 
			?>
		</tbody>
	</table>
</div>
<?php
$settings_obj->mwb_wgm_save_button_html("mwb_wgm_save_product");
?>
<div class="clear"></div>
