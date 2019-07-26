<?php
/**
 * Exit if accessed directly
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( !class_exists( 'mwb_gw_shipping_Manager' ) )
{

	/**
	 * This is class for managing front end giftcard functionality
	 *
	 * @name    mwb_gw_shipping_Manager
	 * @category Class
	 * @author   makewebbetter <webmaster@makewebbetter.com>
	 */
	
	class mwb_gw_shipping_Manager{
	
		/**
		 * This is construct of class where all action and filter is defined
		 * 
		 * @name __construct
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function __construct( ) 
		{
			 add_action('plugins_loaded',array($this,'mwb_gw_shipping_load_woocommerce'));    
        }
        /**
		 * This is function of class where shipping addon is checked
		 * 
		 * @name mwb_gw_shipping_load_woocommerce
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
        function mwb_gw_shipping_load_woocommerce()
        {
            if(function_exists('WC'))
            { 
                $this->add_hooks_and_filters();               
            }
        }
        /**
		 * This is function of class where all action and filter is defined
		 * 
		 * @name add_hooks_and_filters
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		public function add_hooks_and_filters()
		{
			add_action('woocommerce_after_calculate_totals', array($this, 'mwb_apply_coupon_on_cart_total') );			
		}
		/**
		* This is function of class where coupon on shipping is applied
		* 
		* @name add_hooks_and_filters
		* @author makewebbetter<webmaster@makewebbetter.com>
		* @link http://www.makewebbetter.com/
		*/
		public function mwb_apply_coupon_on_cart_total($cart)
		{
			$gift_cart_ship = get_option("mwb_gw_general_cart_shipping_enable", "off");
			if($gift_cart_ship == "on")
			{
				
				$mwb_cart_discount = $cart->discount_cart;
				if ( 'incl' === WC()->cart->tax_display_cart ) {
	               if(isset( $cart->discount_cart_tax ) && $cart->discount_cart_tax != null) {
	                   $mwb_cart_discount+= $cart->discount_cart_tax;
	               }
	           }
	           $applied_coupons = $cart->get_applied_coupons();

				if( is_array( $applied_coupons ) && !empty( $applied_coupons ) )
				{
					$mwb_coupon_arr = array();
					foreach( $applied_coupons as $key => $code )
					{	
						$the_coupon = new WC_Coupon( $code );
						$coupon_id = $the_coupon->get_id();
						if( isset( $coupon_id ) && !empty( $coupon_id ) )
						{
							$coupon_type = get_post_meta($coupon_id,'discount_type',true);
							if(isset($coupon_type) && $coupon_type == 'fixed_cart'){

								$mwb_coupon_total = $this->mwb_gw_coupons_total($cart->get_coupons());

								$mwb_gw_coupon_amount_left = $mwb_coupon_total - $mwb_cart_discount;
								$total_shipping_tax = $cart->shipping_total + $cart->shipping_tax_total;
								if($mwb_gw_coupon_amount_left > 0 && !empty($cart->shipping_total) ){

									if($mwb_gw_coupon_amount_left >= $total_shipping_tax){
										$cart->discount_cart += $total_shipping_tax;
										$this->mwb_adjust_coupon_amount($total_shipping_tax);

										$cart->total -= $total_shipping_tax;
										return $cart;
									}
									elseif($mwb_gw_coupon_amount_left < $total_shipping_tax ){

										$cart->discount_cart += $mwb_gw_coupon_amount_left;
										$this->mwb_adjust_coupon_amount($mwb_gw_coupon_amount_left);

										
										$cart->total -= $mwb_gw_coupon_amount_left;
										return $cart;
									}
								}
								else
								{
									return $cart;
								}
							}
							else
							{
								return $cart;
							}
						}
						else
						{
							return $cart;
						}
					}
				}
				else
				{
					return $cart;
				}
			}
			else
			{
				return $cart;
			}
		}
		 /**
		 * This is used to adjust the coupon price
		 * 
		 * @name mwb_adjust_coupon_amount
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		function mwb_adjust_coupon_amount($more_amount){
			$coupons = WC()->cart->get_coupons();
			$woo_ver = WC()->version;
			if($woo_ver < '3.0.0'){
				foreach($coupons as $coupon)
				{
					$mwb_already_applied_coupon_amount = isset(WC()->cart->coupon_discount_amounts[$coupon->code]) ? round( WC()->cart->coupon_discount_amounts[$coupon->code] ) : 0;
					if( $mwb_already_applied_coupon_amount < $coupon->coupon_amount ){
						$remaining_coupon_amount = $coupon->coupon_amount - $mwb_already_applied_coupon_amount;
						if($more_amount <= $remaining_coupon_amount){
							WC()->cart->coupon_discount_amounts[$coupon->code] = (isset(WC()->cart->coupon_discount_amounts[$coupon->code]) ? WC()->cart->coupon_discount_amounts[$coupon->code] : 0 ) + $more_amount;
							$more_amount = 0;
						}elseif($more_amount > $remaining_coupon_amount){
							$more_amount = $more_amount - $remaining_coupon_amount;
							WC()->cart->coupon_discount_amounts[$coupon->code] += $remaining_coupon_amount;
						}
					}
					if($more_amount == 0){
						break;
					}
				}
			}
			else
			{
				foreach($coupons as $coupon){
					$mwb_already_applied_coupon_amount = isset(WC()->cart->coupon_discount_amounts[$coupon->get_code()]) ? round( WC()->cart->coupon_discount_amounts[$coupon->get_code()] ) : 0;
					if( $mwb_already_applied_coupon_amount < $coupon->get_amount() ){
						$remaining_coupon_amount = $coupon->get_amount() - $mwb_already_applied_coupon_amount;
						if($more_amount <= $remaining_coupon_amount){
							WC()->cart->coupon_discount_amounts[$coupon->get_code()] = (isset(WC()->cart->coupon_discount_amounts[$coupon->get_code()]) ? WC()->cart->coupon_discount_amounts[$coupon->get_code()] : 0 ) + $more_amount;
							$more_amount = 0;
						}elseif($more_amount > $remaining_coupon_amount){
							$more_amount = $more_amount - $remaining_coupon_amount;
							WC()->cart->coupon_discount_amounts[$coupon->get_code()] += $remaining_coupon_amount;
						}
					}
					if($more_amount == 0){
						break;
					}
				}
			}
			
		}
		/**
		 * This is used to get all coupon total amount
		 * 
		 * @name mwb_gw_coupons_total
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link http://www.makewebbetter.com/
		 */
		
	    function mwb_gw_coupons_total($mwb_coupon){
			$mwb_coupon_total = 0;

			foreach($mwb_coupon as $coupon)
			{
				$woo_ver = WC()->version;
				if($woo_ver < '3.0.0')
				{
					$mwb_coupon_total = $mwb_coupon_total + $coupon->coupon_amount;
				}
				else
				{
					$mwb_coupon_total = $mwb_coupon_total + $coupon->get_amount();
				}
			}
			return $mwb_coupon_total;
	    }
		 	
	}
	new mwb_gw_shipping_Manager;
}
