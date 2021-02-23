<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * General Settings Template
 */
require_once MWB_WGC_DIRPATH . 'admin/partials/templates/mwb_wgm_settings/mwb-wgm-other-settings-array.php';
$flag = false;
$current_tab = 'mwb_wgm_other_setting';
if ( isset( $_POST['mwb_wgm_save_other'] ) ) {
	if ( isset( $_REQUEST['mwb-wgc-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mwb-wgc-nonce'] ) ), 'mwb-wgc-nonce' ) ) { // WPCS: input var ok, sanitization ok.
		unset( $_POST['mwb_wgm_save_other'] );
		if ( 'mwb_wgm_other_setting' == $current_tab ) {
			$other_settings_array = array();
			$postdata = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
			if ( isset( $postdata ) && is_array( $postdata ) && ! empty( $postdata ) ) {
				foreach ( $postdata as $key => $value ) {
					$other_settings_array[ $key ] = $value;
				}
			}
			if ( is_array( $other_settings_array ) && ! empty( $other_settings_array ) ) {
				update_option( 'mwb_wgm_other_settings', $other_settings_array );
			}
		}
		$flag = true;
	}
}
if ( $flag ) {
	$settings_obj->mwb_wgm_settings_saved();
}
?>
<?php $other_settings = get_option( 'mwb_wgm_other_settings', array() ); ?>
<h3 class="mwb_wgm_overview_heading"><?php esc_html_e( 'Other Settings', 'woo-gift-cards-lite' ); ?></h3>
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
$settings_obj->mwb_wgm_save_button_html( 'mwb_wgm_save_other' );
?>
<div class="clear"></div>
