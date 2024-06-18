<?php
/**
 * Exit if accessed directly
 *
 * @package    woo-gift-cards-lite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wps_wgm_content_template_pro_tag">
	<h3 class="wps_wgm_overview_heading wps_wgm_heading">Customizable Gift Cards</h3>
	<div class="wps_wgm_table_wrapper">
		<div class="wps_table">
			<div style="display: none;" class="loading-style-bg" id="wps_uwgc_loader">
				<img src="<?php echo esc_attr( WPS_WGC_URL ); ?> assets/images/loading.gif">
			</div>
			<table class="form-table wps_wgm_general_setting wp-list-table striped">
				<tbody>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_customizable_enable">Enable</label>
						</th>
						<td class="forminp forminp-text">
							<?php
                                $attribute_description = __( 'Check this box to enable Woocommerce Customizable Gift Card', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_customizable_enable">
								<input type="checkbox" name="wps_wgm_customizable_enable"
									id="wps_wgm_customizable_enable" class="input-text"> Enable Customizable Gift Card
							</label>

						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_default_enable">Allow default template images</label>
						</th>
						<td class="forminp forminp-text">
							<?php
                                $attribute_description = __( 'Check this box to allow only show default template images.', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_default_enable">
								<input value="default_img" type="radio" name="wps_wgm_image_enable_test" checked="checked"
									id="wps_wgm_default_enable_test" class="input-text"> Check this box to allow default
								template images </label>

						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_upload_enable">Allow uploaded template images</label>
						</th>
						<td class="forminp forminp-text">
							<?php
                                $attribute_description = __( 'Check this box to allow upload all template images.', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_upload_enable">
								<input value="upload_img" type="radio" name="wps_wgm_image_enable"
									id="wps_wgm_upload_enable" class="input-text"> Check this box to upload images
							</label>

						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_default_and_upload_enable">Allow Uploaded images and retain default
								images</label>
						</th>
						<td class="forminp forminp-text">
							<?php
                                $attribute_description = __( 'Allow Uploaded images and retain default images', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_default_and_upload_enable">
								<input value="upload_and_default_img" type="radio" name="wps_wgm_image_enable"
									id="wps_wgm_default_and_upload_enable" class="input-text"> Check this box to upload
								images and retain default images </label>

						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_customize_email_template_image">Upload images for email template</label>
						</th>
						<td class="forminp forminp-text">
							<div id="browse_img_section">
								<div class="wps_upload_email_template_div">
									<?php
                                $attribute_description = __( 'Upload the image which is used for email template.', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_customize_default_giftcard">
									</label>
									<input type="button" class="wps_wgm_customize_email_template_image button"
										value="Upload">

								</div>
								<p class="description">Note: Suggested Dimension is (600*400)</p>
							</div>
							<input type="hidden" value="0">

						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="">Upload Default Gift Card image</label>
						</th>
						<td class="forminp forminp-text">
							<?php
                                $attribute_description = __( 'Upload the image which is used as default image on your Email Template.', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <label for="wps_wgm_customize_default_giftcard">
							</label>
							<input class="wps_wgm_customize_default_giftcard button" type="button"
								value="Upload Default Image">

							<p
								class="wps_wgm_custamize_upload_giftcard_image_value wps_wgm_new_woo_ver_style_text wps_ml-35">
								Note: Suggested Dimension is (600*400)</p>

						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_custom_giftcard">Create another Gift Card</label>
						</th>
						<td class="forminp forminp-text">
							<?php
                                $attribute_description = __( 'Click this box to create new Customizable Gift Card.', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?> <input type="button" name=""
								class="button-primary" id="wps_wgm_custom_giftcard" value="Create Gift Card">
							<p class="wps_ml-35">If you have deleted your customizable Gift Card, you can create another
								one!</p>

						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_custom_giftcard_bg_color">Select Color for background</label>
						</th>
						<td class="forminp forminp-text">
							<?php
                                $attribute_description = __( 'You can also choose the color for your background.', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?>
							<div class="wp-picker-container"><button type="button" class="button wp-color-result"
									aria-expanded="false"><span class="wp-color-result-text">Select
										Color</span></button><span class="wp-picker-input-wrap hidden"><label for="wps_wgm_custom_giftcard_bg_color">
										<input type="text" style="" value="" name="wps_wgm_custom_giftcard_bg_color"
											id="wps_wgm_custom_giftcard_bg_color"
											class="my-color-field wp-color-picker"> </label><input type="button"
										class="button button-small wp-picker-clear" value="Clear"
										aria-label="Clear color"></span>
								<div class="wp-picker-holder">
									<div class="iris-picker iris-border"
										style="display: none; width: 255px; height: 202.125px; padding-bottom: 23.2209px;">
										<div class="iris-picker-inner">
											<div class="iris-square" style="width: 182.125px; height: 182.125px;"><a
													class="iris-square-value ui-draggable ui-draggable-handle" href="#"
													style="left: 0px; top: 182.125px;"><span
														class="iris-square-handle ui-slider-handle"></span></a>
												<div class="iris-square-inner iris-square-horiz"
													style="background-image: -webkit-linear-gradient(left, rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255));">
												</div>
												<div class="iris-square-inner iris-square-vert"
													style="background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgb(0, 0, 0));">
												</div>
											</div>
											<div class="iris-slider iris-strip"
												style="height: 205.346px; width: 28.2px; background-image: -webkit-linear-gradient(top, rgb(0, 0, 0), rgb(0, 0, 0));">
												<div
													class="iris-slider-offset ui-slider ui-corner-all ui-slider-vertical ui-widget ui-widget-content">
													<span tabindex="0"
														class="ui-slider-handle ui-corner-all ui-state-default"
														style="bottom: 0%;"></span>
												</div>
											</div>
										</div>
										<div class="iris-palette-container"><a class="iris-palette" tabindex="0"
												style="background-color: rgb(0, 0, 0); height: 19.5784px; width: 19.5784px; margin-left: 0px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(255, 255, 255); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(221, 51, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(221, 153, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(238, 238, 34); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(129, 215, 66); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(30, 115, 190); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(130, 36, 227); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a>
										</div>
									</div>
								</div>
							</div>

						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_custom_giftcard_middle_color">Select Color for middle section
								template</label>
						</th>
						<td class="forminp forminp-text">
							<?php
                                $attribute_description = __( 'You can also choose the color for your middle section.', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?>
							<div class="wp-picker-container"><button type="button" class="button wp-color-result"
									aria-expanded="false"><span class="wp-color-result-text">Select
										Color</span></button><span class="wp-picker-input-wrap hidden"><label for="wps_wgm_custom_giftcard_middle_color">
										<input type="text" style="" value="" name="wps_wgm_custom_giftcard_middle_color"
											id="wps_wgm_custom_giftcard_middle_color"
											class="my-color-field wp-color-picker"> </label><input type="button"
										class="button button-small wp-picker-clear" value="Clear"
										aria-label="Clear color"></span>
								<div class="wp-picker-holder">
									<div class="iris-picker iris-border"
										style="display: none; width: 255px; height: 202.125px; padding-bottom: 23.2209px;">
										<div class="iris-picker-inner">
											<div class="iris-square" style="width: 182.125px; height: 182.125px;"><a
													class="iris-square-value ui-draggable ui-draggable-handle" href="#"
													style="left: 0px; top: 182.125px;"><span
														class="iris-square-handle ui-slider-handle"></span></a>
												<div class="iris-square-inner iris-square-horiz"
													style="background-image: -webkit-linear-gradient(left, rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255));">
												</div>
												<div class="iris-square-inner iris-square-vert"
													style="background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgb(0, 0, 0));">
												</div>
											</div>
											<div class="iris-slider iris-strip"
												style="height: 205.346px; width: 28.2px; background-image: -webkit-linear-gradient(top, rgb(0, 0, 0), rgb(0, 0, 0));">
												<div
													class="iris-slider-offset ui-slider ui-corner-all ui-slider-vertical ui-widget ui-widget-content">
													<span tabindex="0"
														class="ui-slider-handle ui-corner-all ui-state-default"
														style="bottom: 0%;"></span>
												</div>
											</div>
										</div>
										<div class="iris-palette-container"><a class="iris-palette" tabindex="0"
												style="background-color: rgb(0, 0, 0); height: 19.5784px; width: 19.5784px; margin-left: 0px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(255, 255, 255); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(221, 51, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(221, 153, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(238, 238, 34); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(129, 215, 66); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(30, 115, 190); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(130, 36, 227); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a>
										</div>
									</div>
								</div>
							</div>

						</td>
					</tr>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="wps_wgm_custom_giftcard_desclaimer_color">Select Color for disclaimer section
								template</label>
						</th>
						<td class="forminp forminp-text">
							<?php
                                $attribute_description = __( 'You can also choose the color for your disclaimer section.', 'woo-gift-cards-lite' );
                                echo wp_kses_post( wc_help_tip( $attribute_description ) );
                            ?>
							<div class="wp-picker-container"><button type="button" class="button wp-color-result"
									aria-expanded="false"><span class="wp-color-result-text">Select
										Color</span></button><span class="wp-picker-input-wrap hidden"><label for="wps_wgm_custom_giftcard_desclaimer_color">
										<input type="text" style="" value=""
											name="wps_wgm_custom_giftcard_desclaimer_color"
											id="wps_wgm_custom_giftcard_desclaimer_color"
											class="my-color-field wp-color-picker"> </label><input type="button"
										class="button button-small wp-picker-clear" value="Clear"
										aria-label="Clear color"></span>
								<div class="wp-picker-holder">
									<div class="iris-picker iris-border"
										style="display: none; width: 255px; height: 202.125px; padding-bottom: 23.2209px;">
										<div class="iris-picker-inner">
											<div class="iris-square" style="width: 182.125px; height: 182.125px;"><a
													class="iris-square-value ui-draggable ui-draggable-handle" href="#"
													style="left: 0px; top: 182.125px;"><span
														class="iris-square-handle ui-slider-handle"></span></a>
												<div class="iris-square-inner iris-square-horiz"
													style="background-image: -webkit-linear-gradient(left, rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255));">
												</div>
												<div class="iris-square-inner iris-square-vert"
													style="background-image: -webkit-linear-gradient(top, rgba(0, 0, 0, 0), rgb(0, 0, 0));">
												</div>
											</div>
											<div class="iris-slider iris-strip"
												style="height: 205.346px; width: 28.2px; background-image: -webkit-linear-gradient(top, rgb(0, 0, 0), rgb(0, 0, 0));">
												<div
													class="iris-slider-offset ui-slider ui-corner-all ui-slider-vertical ui-widget ui-widget-content">
													<span tabindex="0"
														class="ui-slider-handle ui-corner-all ui-state-default"
														style="bottom: 0%;"></span>
												</div>
											</div>
										</div>
										<div class="iris-palette-container"><a class="iris-palette" tabindex="0"
												style="background-color: rgb(0, 0, 0); height: 19.5784px; width: 19.5784px; margin-left: 0px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(255, 255, 255); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(221, 51, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(221, 153, 51); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(238, 238, 34); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(129, 215, 66); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(30, 115, 190); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a><a
												class="iris-palette" tabindex="0"
												style="background-color: rgb(130, 36, 227); height: 19.5784px; width: 19.5784px; margin-left: 3.6425px;"></a>
										</div>
									</div>
								</div>
							</div>

						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<p class="submit">
		<input type="submit" value="Save changes" class="wps_wgm_save_button" name="wps_uwgc_save_customizable"
			id="wps_uwgc_save_customizable">
	</p>
	<div class="clear"></div>
</div>
