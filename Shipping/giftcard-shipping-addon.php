<?php
/**
 * Exit if accessed directly
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	define('mwb_wgm_SD_DOM', 'giftware');
	define('mwb_wgm_SD_DIRPATH', plugin_dir_path( __FILE__ ));
	define('mwb_wgm_SD_URL', plugin_dir_url( __FILE__ ));
	// define('mwb_gw_SD_HOME_URL', home_url());
	include_once mwb_wgm_SD_DIRPATH.'/includes/woocommerce-shipping-addon-class.php';
	include_once mwb_wgm_SD_DIRPATH.'/public/mwb-wgm-shipping-public-manager.php';
?>