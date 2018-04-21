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

$generaltab = "";
$producttab = "";
$tab = "";
$emailtab = "";
$offlinetab = "";
$exporttab = "";
$additionaltab = "";
$discounttab = "";
$thankyouordertab = "";
$deliverytab = "";
$qrbartab = "";
$exportcoupontabactive = false;
$generaltabactive = false;
$producttabactive = false;
$emailtabactive = false;
$offlinepaymenttabactive = false;
$additionalsettingtabactive = false;
$discounttabactive = false;
$thankyouordertabactive = false;
$deliverytabactive = false;
$qrbartabactive = false;

if(isset($_GET['tab']) && !empty($_GET['tab']))
{
	$tab = sanitize_text_field( $_GET['tab'] );
	if($tab == 'general-setting'){
		$generaltab = "nav-tab-active";
		$generaltabactive = true;
	}	
	if($tab == 'product-setting'){
		$producttab = "nav-tab-active";
		$producttabactive = true;
	}
	if($tab == 'email-setting'){
		$emailtab = "nav-tab-active";
		$emailtabactive = true;
	}
	if($tab == 'offline-giftcard'){
		$offlinetab = "nav-tab-active";
		$offlinepaymenttabactive = true;
	}
	if($tab == 'export-coupon'){
		$exporttab = "nav-tab-active";
		$exportcoupontabactive = true;
	}
	if($tab == 'other-additional-setting'){
		$additionaltab = "nav-tab-active";
		$additionalsettingtabactive = true;
	}
	if($tab == 'discount-tab'){
		$discounttab = "nav-tab-active";
		$discounttabactive = true;
	}
	if($tab == 'thankyou-tab'){
		$thankyouordertab = "nav-tab-active";
		$thankyouordertabactive = true;
	}
	if($tab == 'qr-barcode-tab'){
		$qrbartab = "nav-tab-active";
		$qrbartabactive = true;
	}
	if($tab == 'delivery-tab'){
		$deliverytab = "nav-tab-active";
		$deliverytabactive = true;
	}
	do_action('mwb_wgm_setting_tab_active');
}	
if(empty($tab)){
	$generaltab = "nav-tab-active";
	$generaltabactive = true;
}
?>

<div class="wrap woocommerce" id="mwb_wgm_setting_wrapper">
	<form enctype="multipart/form-data" action="" id="mainform" method="post">
		<h1 class="mwb_wgm_setting_title"><?php _e('Giftcard Settings', 'woocommerce_gift_cards_lite')?></h1>
		<br/>
		<input type="hidden" name="mwb-wgc-nonce" value="<?php echo wp_create_nonce('mwb-wgc-nonce'); ?>">
		<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
			<a class="nav-tab <?php echo $generaltab;?>" href="?page=mwb-wgc-setting-lite&tab=general-setting"><?php _e('General', 'woocommerce_gift_cards_lite');?></a>
			<a class="nav-tab <?php echo $producttab;?>" href="?page=mwb-wgc-setting-lite&tab=product-setting"><?php _e('Products', 'woocommerce_gift_cards_lite');?></a>
			<a class="nav-tab <?php echo $emailtab;?>" href="?page=mwb-wgc-setting-lite&tab=email-setting"><?php _e('Email Template', 'woocommerce_gift_cards_lite');?></a>
			<a class="nav-tab <?php echo $offlinetab;?>" href="?page=mwb-wgc-setting-lite&tab=offline-giftcard"><?php _e('Offline Giftcard', 'woocommerce_gift_cards_lite');?></a>
			<a class="nav-tab <?php echo $exporttab;?>" href="?page=mwb-wgc-setting-lite&tab=export-coupon"><?php _e('Import/Export', 'woocommerce_gift_cards_lite');?></a>
			<a class="nav-tab <?php echo $additionaltab;?>" href="?page=mwb-wgc-setting-lite&tab=other-additional-setting"><?php _e('Other Setting', 'woocommerce_gift_cards_lite');?></a>
			<a class="nav-tab <?php echo $discounttab;?>" href="?page=mwb-wgc-setting-lite&tab=discount-tab"><?php _e('Discount', 'woocommerce_gift_cards_lite');?></a>
			<a class="nav-tab <?php echo $thankyouordertab;?>" href="?page=mwb-wgc-setting-lite&tab=thankyou-tab"><?php _e('Thank You Order', 'woocommerce_gift_cards_lite');?></a>
			<a class="nav-tab <?php echo $qrbartab;?>" href="?page=mwb-wgc-setting-lite&tab=qr-barcode-tab"><?php _e('QRCode/BarCode', 'woocommerce_gift_cards_lite');?></a>	
			<a class="nav-tab <?php echo $deliverytab;?>" href="?page=mwb-wgc-setting-lite&tab=delivery-tab"><?php _e('Delivery Method', 'woocommerce_gift_cards_lite');?></a>		
			<?php 
			do_action('mwb_wgm_setting_tab');
			?>	
		</nav>
		<?php 
		if($generaltabactive == true){	
			include_once 'templates/mwb_wgc_general_setting.php';
		}	
		if($producttabactive == true){	
			include_once 'templates/mwb_wgc_pro_version.php';
		}
		if($emailtabactive == true){	
			include_once 'templates/mwb_wgc_pro_version.php';
		}
		if($offlinepaymenttabactive == true){	
			include_once 'templates/mwb_wgc_pro_version.php';
		}
		if($exportcoupontabactive == true){	
			include_once 'templates/mwb_wgc_pro_version.php';
		}
		if($additionalsettingtabactive == true){	
			include_once 'templates/mwb_wgc_pro_version.php';
		}
		if($discounttabactive == true){	
			include_once 'templates/mwb_wgc_pro_version.php';
		}
		if($thankyouordertabactive == true){	
			include_once 'templates/mwb_wgc_pro_version.php';
		}
		if($qrbartabactive == true){	
			include_once 'templates/mwb_wgc_pro_version.php';
		}
		if($deliverytabactive == true){
			include_once 'templates/mwb_wgc_pro_version.php';
		}
		?>
	</form>
</div>
