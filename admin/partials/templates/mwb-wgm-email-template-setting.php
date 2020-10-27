<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
require_once MWB_WGC_DIRPATH . 'admin/partials/templates/mwb_wgm_settings/mwb-wgm-mail-template-settings-array.php';
$flag = false;
$current_tab = 'mwb_wgm_mail_setting';
if ( isset( $_POST['mwb_wgm_save_mail'] ) ) {
	if ( isset( $_REQUEST['mwb-wgc-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['mwb-wgc-nonce'] ) ), 'mwb-wgc-nonce' ) ) { // WPCS: input var ok, sanitization ok.
		unset( $_POST['mwb_wgm_save_mail'] );
		$postdata = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );
		if ( 'mwb_wgm_mail_setting' == $current_tab ) {
			$mail_settings_array = array();
			if ( isset( $postdata ) && is_array( $postdata ) && ! empty( $postdata ) ) {
				foreach ( $postdata as $key => $value ) {
					$mail_settings_array[ $key ] = $value;
				}
			}
			if ( is_array( $mail_settings_array ) && ! empty( $mail_settings_array ) ) {
				update_option( 'mwb_wgm_mail_settings', $mail_settings_array );
			}
		}
		$flag = true;
	}
}
if ( $flag ) {
	$settings_obj->mwb_wgm_settings_saved();
}
?>
<?php $mail_settings = get_option( 'mwb_wgm_mail_settings', array() ); ?>
<h3 class="mwb_wgm_overview_heading"><?php esc_html_e( 'Email Settings', 'woo-gift-cards-lite' ); ?></h3>
<div class="mwb_wgm_table_wrapper">	
	<div class="mwb_table">
		<table class="form-table mwb_wgm_general_setting">
			<tbody>
				<?php
				$settings_obj->mwb_wgm_generate_common_settings( $mwb_wgm_mail_template_settings['top'], $mail_settings );
				?>
			</tbody>
		</table>
	</div>
</div>
<h3 id="mwb_wgm_mail_setting" class="mwb_wgm_mail_setting_tab"><?php esc_html_e( 'Mail Setting', 'woo-gift-cards-lite' ); ?></h3>
<div id="mwb_wgm_mail_setting_wrapper" class="mwb_wgm_table_wrapper">
	<table class="form-table mwb_wgm_general_setting">	
		<tbody>
			<?php
				$settings_obj->mwb_wgm_generate_common_settings( $mwb_wgm_mail_template_settings['middle'], $mail_settings );
			?>
		</tbody>
	</table>
</div>
<?php
do_action( 'mwb_wgm_addtional_mail_settings', $mwb_wgm_mail_template_settings, $mail_settings );
$settings_obj->mwb_wgm_save_button_html( 'mwb_wgm_save_mail' );
?>
<div class="clear"></div>
	
