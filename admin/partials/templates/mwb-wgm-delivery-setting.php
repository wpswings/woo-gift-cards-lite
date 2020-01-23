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
 * General Settings Template
 */
require_once MWB_WGC_DIRPATH . 'admin/partials/templates/mwb_wgm_settings/mwb-wgm-delivery-settings-array.php';
$flag = false;
$current_tab = 'mwb_wgm_delivery_setting';
if ( isset( $_POST['mwb_wgm_save_delivery'] ) ) {
	if ( isset( $_REQUEST['mwb-wgc-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mwb-wgc-nonce'] ) ), 'mwb-wgc-nonce' ) ) { // WPCS: input var ok, sanitization ok.
		unset( $_POST['mwb_wgm_save_delivery'] );
		$postdata = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
		$delivery_settings_array = array();
		if ( 'mwb_wgm_delivery_setting' == $current_tab ) {
			if ( isset( $postdata ) && is_array( $postdata ) && ! empty( $postdata ) ) {
				foreach ( $postdata as $key => $value ) {
					$delivery_settings_array[ $key ] = $value;
				}
			}
			if ( is_array( $delivery_settings_array ) && ! empty( $delivery_settings_array ) ) {
				update_option( 'mwb_wgm_delivery_settings', $delivery_settings_array );
			}
		}
		$flag = true;
	}
}

if ( $flag ) {
	$settings_obj->mwb_wgm_settings_saved();
}
?>
<?php $delivery_settings = get_option( 'mwb_wgm_delivery_settings', true ); ?>
<?php
if ( ! is_array( $delivery_settings ) ) :
	$delivery_settings = array();
endif;
?>
<h3 class="mwb_wgm_overview_heading"><?php esc_html_e( 'Delivery Settings', 'woocommerce_gift_cards_lite' ); ?></h3>
<div class="mwb_wgm_table_wrapper">	
	<div class="mwb_table">
		<table class="form-table mwb_wgm_general_setting">
			<tbody>
				<?php
				$settings_obj->mwb_wgm_generate_common_settings( $mwb_wgm_delivery_settings, $delivery_settings );
				?>
			</tbody>
		</table>
	</div>
</div>
<?php
$settings_obj->mwb_wgm_save_button_html( 'mwb_wgm_save_delivery' );
?>
<div class="clear"></div>
