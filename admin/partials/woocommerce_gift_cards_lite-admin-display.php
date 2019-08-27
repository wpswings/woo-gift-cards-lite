<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_gift_cards_lite
 * @subpackage Woocommerce_gift_cards_lite/admin/partials
 */
/*if ( ! defined( 'ABSPATH' ) ) {
	exit;
}*/
/*  create the settings tabs*/
$mwb_wgm_setting_tab = array(
						'overview_setting'=>array(
							                'title' => __( 'OverView', MWB_WGM_DOMAIN ),
							                'file_path'=>MWB_WGC_DIRPATH.'admin/partials/templates/mwb_wgc_overview_setting.php',
							           		 ),
						'general_setting'=>array(
							                'title' => __( 'General', MWB_WGM_DOMAIN ),
							                'file_path'=>MWB_WGC_DIRPATH.'admin/partials/templates/mwb_wgc_general_setting.php',
							           		 ),
						'product_setting'=>array(
							                'title' => __( 'Product', MWB_WGM_DOMAIN ),
							                'file_path'=>MWB_WGC_DIRPATH.'admin/partials/templates/mwb_wgc_product_setting.php',
							           		 ),
						'email_setting'=>array(
							                'title' => __( 'Email Template', MWB_WGM_DOMAIN ),
							                'file_path'=>MWB_WGC_DIRPATH.'admin/partials/templates/mwb_wgc_email_template_setting.php',
							           		 ),
						'delivery_method'=>array(
							                'title' => __( 'Delivery Method', MWB_WGM_DOMAIN ),
							                'file_path'=>MWB_WGC_DIRPATH.'admin/partials/templates/mwb_wgc_delivery_setting.php',
							           		 ),
						'other_setting'=>array(
							                'title' => __( 'Other Settings', MWB_WGM_DOMAIN ),
							                'file_path'=>MWB_WGC_DIRPATH.'admin/partials/templates/mwb_wgc_other_setting.php',
							            	),
					);
		$mwb_wgm_setting_tab= apply_filters( 'mwb_wgm_add_gift_card_setting_tab_before', $mwb_wgm_setting_tab );
		$mwb_wgm_setting_tab['redeem_tab'] = array(
							                'title' => __( 'Gift Card Redeem', MWB_WGM_DOMAIN ),
							                'file_path'=>MWB_WGC_DIRPATH.'admin/partials/templates/redeem-giftware-settings.php',
										);
		$mwb_wgm_setting_tab= apply_filters( 'mwb_wgm_add_gift_card_setting_tab_after', $mwb_wgm_setting_tab );
do_action('mwb_uwgc_show_notice');
?>
<div class="wrap woocommerce" id="mwb_wgm_setting_wrapper">
	<div style="display: none;" class="loading-style-bg" id="mwb_wgm_loader">
		<img src="<?php echo MWB_WGC_URL;?>assets/images/loading.gif">
	</div>
	<form enctype="multipart/form-data" action="" id="mainform" method="post">
		<div class="mwb_wgm_header">
			<div class="mwb_wgm_header_content_left">
				<div>
					<h3 class="mwb_wgm_setting_title"><?php _e('GiftCard Settings', MWB_WGM_DOMAIN)?></h3>
				</div>
			</div>
			<div class="mwb_wgm_header_content_right">
				<ul>
					<?php 
					if( mwb_uwgc_pro_active() ){
					?>
					<li><a href="?page=mwb-wgc-setting-lite&tab=redeem_tab" class="mwb_wgm_redeem_link"><span>New</span> Giftcard Redeem Feature</a></li>
					<li><a href="https://makewebbetter.com/contact-us/" target="_blank">
					<span class="dashicons dashicons-phone"></span></a>
					</li>
					<li><a href="https://docs.makewebbetter.com/giftware-woocommerce-gift-cards/" target="_blank">
						<span class="dashicons dashicons-media-document"></span></a>
					</li>	
					<?php		
					}
					else{
						?>
						<li><a href="?page=mwb-wgc-setting-lite&tab=redeem_tab" class="mwb_wgm_redeem_link"><span>New</span> Giftcard Redeem Feature</a></li>
						<li><a href="https://makewebbetter.com/contact-us/" target="_blank">
							<span class="dashicons dashicons-phone"></span>
							</a>
						</li>
						<li><a href="https://docs.makewebbetter.com/woocommerce-gift-cards-lite/" target="_blank">
							<span class="dashicons dashicons-media-document"></span>
							</a>
						</li>
						<li class="mwb_wgm_header_menu_button">
							<a  href="https://makewebbetter.com/product/giftware-woocommerce-gift-cards/?utm_source=mwb-giftcard-org&utm_medium=mwb-org&utm_campaign=giftcard-org" class="" title="" target="_blank">GO PRO NOW</a>
						</li>	
					<?php			
					}
					?>
				</ul>
			</div>
		</div>
	<input type="hidden" name="mwb-wgc-nonce" value="<?php echo wp_create_nonce('mwb-wgc-nonce'); ?>">
	<div class="mwb_wgm_main_template">
		<div class="mwb_wgm_body_template">
			<div class="mwb_wgm_mobile_nav">
				<span class="dashicons dashicons-menu"></span>
			</div>
			<div class="mwb_wgm_navigator_template">
				<div class="mwb_wgm-navigations">
					<?php
					if (!empty($mwb_wgm_setting_tab) && is_array($mwb_wgm_setting_tab)) {
						foreach ($mwb_wgm_setting_tab as $key => $mwb_tab) {
							if (isset($_GET['tab']) &&  $_GET['tab']== $key ) { 
								?>
								<div class="mwb_wgm_tabs">
									<a class="mwb_gw_nav_tab nav-tab nav-tab-active " href="?page=mwb-wgc-setting-lite&tab=<?php echo $key;?>"><?php echo $mwb_tab['title'];?></a>
								</div>
								<?php 
							}
							else  { 
								if(empty($_GET['tab']) && $key =='overview_setting'){  
									?>
									<div class="mwb_wgm_tabs">
										<a class="mwb_gw_nav_tab nav-tab nav-tab-active" href="?page=mwb-wgc-setting-lite&tab=<?php echo $key;?>"><?php echo $mwb_tab['title'];?></a>
									</div>
									<?php	
								}
								else{ 
									?>			
									<div class="mwb_wgm_tabs">
										<a class="mwb_gw_nav_tab nav-tab " href="?page=mwb-wgc-setting-lite&tab=<?php echo $key;?>"><?php echo $mwb_tab['title'];?></a>
									</div>
									<?php
								}
							}
						}
					}
					?>	
				</div>
			</div>
			<?php 
				if (!empty($mwb_wgm_setting_tab) && is_array($mwb_wgm_setting_tab)) {
					
					foreach ($mwb_wgm_setting_tab as $key => $mwb_file) {
						if (isset($_GET['tab']) &&  $_GET['tab']== $key ) {
								$include_tab = $mwb_file['file_path'];
								?>
								<div class="mwb_wgm_content_template">
									<?php  include_once $include_tab;?>
								</div>
								<?php
							}
							elseif(empty($_GET['tab']) && $key == 'overview_setting'){
								?>
								<div class="mwb_wgm_content_template">
									<?php include_once $mwb_file['file_path']; ?>
								</div>
								<?php
								break;
							}
					}
				}
			 ?>
		</div>
	</div>
	</form>
</div>