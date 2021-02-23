<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/includes/extra-templates
 */

$screen = get_current_screen();
$is_valid = in_array( $screen->id, apply_filters( 'mwb_helper_valid_frontend_screens', array() ) );
if ( ! $is_valid ) {
	return false;
}

$form_fields = apply_filters( 'mwb_on_boarding_form_fields', array() );
if ( ! empty( $form_fields ) ) : ?>
	<div style="display: none;" class="loading-style-bg" id="mwb_wgm_loader">
		<img src="<?php echo esc_url( MWB_WGC_URL . 'assets/images/loading.gif' ); ?>">
	</div>
	<div class="mwb-onboarding-section">
		<div class="mwb-on-boarding-wrapper-background">
			<div class="mwb-on-boarding-wrapper">
				<div class="mwb-on-boarding-close-btn">
					<a href="javascript:void(0);">
						<span class="close-form">x</span>
					</a>
				</div>
				<h3 class="mwb-on-boarding-heading"><?php esc_html_e( 'Welcome to MakeWebBetter', 'woo-gift-cards-lite' ); ?></h3>
				<p class="mwb-on-boarding-desc"><?php esc_html_e( 'We love making new friends! Subscribe below and we promise to keep you up-to-date with our latest new plugins, updates, awesome deals and a few special offers.', 'woo-gift-cards-lite' ); ?></p>
				<form action="#" method="post" class="mwb-on-boarding-form">
					<?php foreach ( $form_fields as $key => $field_attr ) : ?>
						<?php $this->render_field_html( $field_attr ); ?>
					<?php endforeach; ?>
					<div class="mwb-on-boarding-form-btn__wrapper">
						<div class="mwb-on-boarding-form-submit mwb-on-boarding-form-verify ">
						<input type="submit" class="mwb-on-boarding-submit mwb-on-boarding-verify " value="Send Us">
					</div>
					<div class="mwb-on-boarding-form-no_thanks">
						<a href="javascript:void(0);" class="mwb-on-boarding-no_thanks"><?php esc_html_e( 'Skip For Now', 'woo-gift-cards-lite' ); ?></a>
					</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php endif; ?>
