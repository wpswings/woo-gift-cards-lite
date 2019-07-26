<?php

/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( !class_exists( 'mwb_gw_shipping_Card_Product' ) )
{

	/**
	 * This is class for managing order status and other functionalities .
	 *
	 * @name    mwb_gw_shipping_Card_Product
	 * @category Class
	 * @author   makewebbetter <webmaster@makewebbetter.com>
	 */
	
	class mwb_gw_shipping_Card_Product{
	
		/**
		 * This is construct of class where all action and filter is defined
		 * 
		 * @name __construct
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function __construct( ) 
		{
			$this->shippingtab = "";
			$this->shippingtabactive = false;
			$this->licensetab = "";
			$this->licensetabactive = false;
			add_action('mwb_gw_setting_tab',array($this,'mwb_gw_shipping_setting_tab'));
			add_action('mwb_gw_setting_tab_active',array($this,'mwb_gw_shipping_setting_tab_active'));
			add_action('mwb_gw_setting_tab_html',array($this,'mwb_gw_shipping_setting_tab_html'));
			//add_filter('mwb_gw_shipping_feature',array($this,'mwb_gw_shipping_disable'));
			add_action( 'admin_enqueue_scripts', array($this, "mwb_gw_shipping_enqueue_scripts"), 10, 1);
		}
		/**
		 * This is function where scripts are enqueued
		 * 
		 * @name mwb_gw_shipping_enqueue_scripts
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function mwb_gw_shipping_enqueue_scripts(){
			$screen = get_current_screen();
			if(isset($screen->id))
			{
				$pagescreen = $screen->id;
				if($pagescreen == 'woocommerce_page_mwb-gw-setting' && isset($_GET['tab']) && $_GET['tab'] == 'shipping')
				{
					wp_register_script("mwb_gw_shipping", mwb_gw_SD_URL."assets/js/giftware-shipping.js");
					wp_enqueue_script('mwb_gw_shipping' );
				}
			}
		}
		
		/**
		 * This is function is used to add shipping section in settings
		 * 
		 * @name mwb_gw_shipping_setting_tab
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function mwb_gw_shipping_setting_tab(){
		$callname_lic = MWB_GW_Card_Product::$lic_callback_function;
	    $callname_lic_initial = MWB_GW_Card_Product::$lic_ini_callback_function;
		$day_count = MWB_GW_Card_Product::$callname_lic_initial();
		?>
		<a class="nav-tab <?php echo $this->shippingtab;?>" href="?page=mwb-gw-setting&tab=shipping"><?php _e('Delivery Method', '');?></a>
		<?php if(  0 <= $day_count && ! MWB_GW_Card_Product::$callname_lic() ){?>
			<a class="nav-tab <?php echo $this->licensetab;?>" href="?page=mwb-gw-setting&tab=validate_license"><?php _e('Add License', MWB_WGC_DIRPATH);?></a>
		<?php }
				
			
		}
		/**
		 * This is function is used to add active class to shipping section in settings
		 * 
		 * @name mwb_gw_shipping_setting_tab_active
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function mwb_gw_shipping_setting_tab_active(){
			if(isset($_GET['tab']) && !empty($_GET['tab']))
			{
				$tab = $_GET['tab'];
				if($tab == 'shipping'){
					$this->shippingtab = "nav-tab-active";
					$this->shippingtabactive = true;
				}
				if($tab == 'validate_license'){
					$this->licensetab = "nav-tab-active";
					$this->licensetabactive = true;
				}
			}
		}
		/**
		 * This is function is used to add template to shipping section in settings
		 * 
		 * @name mwb_gw_shipping_setting_tab_html
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function mwb_gw_shipping_setting_tab_html(){
				$callname_lic = MWB_GW_Card_Product::$lic_callback_function;
			if($this->shippingtabactive == true){
				include_once MWB_WGC_DIRPATH.'/admin/shipping-setting.php';
			}
			if($this->licensetabactive == true && ! MWB_GW_Card_Product::$callname_lic() ){
				include_once MWB_WGC_DIRPATH.'/admin/license-setting.php';
			}
		}
	}
	new mwb_gw_shipping_Card_Product;
}