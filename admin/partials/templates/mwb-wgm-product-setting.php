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
 * Product Settings Template
 */
$flag = false;
$current_tab = 'mwb_wgm_product_setting';
if ( isset( $_POST['mwb_wgm_save_product'] ) ) {
	unset( $_POST['mwb_wgm_save_product'] );
	if ( isset( $_REQUEST['mwb-wgc-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mwb-wgc-nonce'] ) ), 'mwb-wgc-nonce' ) ) { // WPCS: input var ok, sanitization ok.
		if ( 'mwb_wgm_product_setting' == $current_tab ) {
			$product_settings_array = array();
			$postdata = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
			if ( isset( $postdata ) && is_array( $postdata ) && ! empty( $postdata ) ) {
				foreach ( $postdata as $key => $value ) {
					$product_settings_array[ $key ] = $value;
				}
			}
			if ( is_array( $product_settings_array ) && ! empty( $product_settings_array ) ) {
				update_option( 'mwb_wgm_product_settings', $product_settings_array );
			}
		}
		$flag = true;
	}
}
require_once MWB_WGC_DIRPATH . 'admin/partials/templates/mwb_wgm_settings/mwb-wgm-product-settings-array.php';
if ( $flag ) {
	$settings_obj->mwb_wgm_settings_saved();
}
?>
<?php $product_settings = get_option( 'mwb_wgm_product_settings', array() ); ?>
<h3 class="mwb_wgm_overview_heading"><?php esc_html_e( 'Product Settings', 'woo-gift-cards-lite' ); ?></h3>
<div class="mwb_wgm_table_wrapper">
	<table class="form-table mwb_wgm_product_setting">
		<tbody>
			<?php
				$settings_obj->mwb_wgm_generate_common_settings( $mwb_wgm_product_settings, $product_settings );
			?>
		</tbody>
	</table>
</div>
<?php
$settings_obj->mwb_wgm_save_button_html( 'mwb_wgm_save_product' );
?>
<div class="clear"></div>
