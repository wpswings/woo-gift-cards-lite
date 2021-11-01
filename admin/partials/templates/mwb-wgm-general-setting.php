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
require_once MWB_WGC_DIRPATH . 'admin/partials/templates/mwb_wgm_settings/mwb-wgm-general-settings-array.php';
$current_tab = 'mwb_wgm_general_setting';
$flag = false;
if ( isset( $_POST ['mwb_wgm_save_general'] ) ) {
	if ( isset( $_REQUEST['mwb-wgc-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mwb-wgc-nonce'] ) ), 'mwb-wgc-nonce' ) ) {
		unset( $_POST['mwb_wgm_save_general'] );
		$postdata = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
		$general_settings_array = array();
		if ( 'mwb_wgm_general_setting' == $current_tab ) {
			if ( isset( $postdata ) && is_array( $postdata ) && ! empty( $postdata ) ) {
				foreach ( $postdata as $key => $value ) {
					$general_settings_array[ $key ] = $value;
				}
			}
			if ( is_array( $general_settings_array ) && ! empty( $general_settings_array ) ) {
				$delivery_setting = get_option( 'mwb_wgm_delivery_settings', array() );
				if ( empty( $delivery_setting ) ) {
					$delivery_setting['mwb_wgm_send_giftcard'] = 'Mail to recipient';
					update_option( 'mwb_wgm_delivery_settings', $delivery_setting );
				}
				update_option( 'mwb_wgm_general_settings', $general_settings_array );
			}
		}
		$flag = true;
	}
}
if ( $flag ) {
	$settings_obj->mwb_wgm_settings_saved();
}
?>
<?php $general_settings = get_option( 'mwb_wgm_general_settings', array() ); ?>
<h3 class="mwb_wgm_overview_heading"><?php esc_html_e( 'General Settings', 'woo-gift-cards-lite' ); ?></h3>
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
</div>
<?php
$settings_obj->mwb_wgm_save_button_html( 'mwb_wgm_save_general' );
?>
<div class="clear"></div>
