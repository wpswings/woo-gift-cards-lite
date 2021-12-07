<?php
/**
 * Exit if accessed directly
 *
 * @package Wallet_System_For_Woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class='content active'>
	<form method="post" action="" id="mwb_wallet_transfer_form">
		<p class="mwb-wallet-field-container form-row form-row-wide">
			<label for="mwb_giftcard_code"><?php echo esc_html__( 'Enter Gift Card Code : ', 'woo-gift-cards-lite' ); ?></label>
			<input type="text" id="mwb_giftcard_code" name="mwb_giftcard_code" required>
		</p>
		<p class="error"></p>
		<p class="success"></p>
		<p class="mwb-wallet-field-container form-row">
			<input type="button" class="mwb-btn__filled button" id="mwb_recharge_wallet_giftcard" name="mwb_recharge_wallet_giftcard" value="<?php esc_html_e( 'Proceed', 'woo-gift-cards-lite' ); ?>">
		</p>
	</form>
</div>
<?php
$mwb_wgm = array(
	'ajaxurl'       => admin_url( 'admin-ajax.php' ),
	'mwb_wgm_nonce' => wp_create_nonce( 'mwb-wgc-verify-nonce' ),
	'mwb_currency'  => get_woocommerce_currency_symbol(),
);
wp_enqueue_script( 'mwb-wallet-giftcard', plugin_dir_url( __FILE__ ) . '../js/woocommerce_gift_cards_lite-public.js', array( 'jquery' ), $this->version, true );
wp_localize_script( 'mwb-wallet-giftcard', 'mwb_wgm', $mwb_wgm );
?>
