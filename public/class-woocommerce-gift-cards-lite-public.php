<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/public
 * @author     WP Swings <webmaster@wpswings.com>
 */
use Automattic\WooCommerce\Utilities\OrderUtil;
use Automattic\WooCommerce\Blocks\Utils\CartCheckoutUtils;
/**
 * Public class .
 */
class Woocommerce_Gift_Cards_Lite_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The common function array.
	 *
	 * @since    1.0.0
	 * @var      string    $wps_common_fun    wps_common_fun.
	 */
	public $wps_common_fun;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		require_once WPS_WGC_DIRPATH . 'includes/class-woocommerce-gift-cards-common-function.php';
		$this->wps_common_fun = new Woocommerce_Gift_Cards_Common_Function();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Gift_Cards_Lite_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Gift_Cards_Lite_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce_gift_cards_lite-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'thickbox' );
		$other_settings = get_option( 'wps_wgm_other_settings', array() );
		$wps_new_layout = $this->wps_common_fun->wps_wgm_get_template_data( $other_settings, 'wps_wgm_new_gift_card_page_layout' );

		if ( 'on' == $wps_new_layout && ! wps_uwgc_pro_active() ) {

			wp_enqueue_style( $this->plugin_name . 'single-page', plugin_dir_url( __FILE__ ) . 'css/woocommerce_gift_cards_lite-single-page.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Function is used to compatible with price based on country.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_price_based_country_giftcard()
	 * @param      array $array The array of product type.
	 * @return  $array
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_price_based_country_giftcard( $array ) {
		$array[] = 'wgm_gift_card';
		return $array;
	}
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$mail_settings           = get_option( 'wps_wgm_mail_settings', array() );
		$giftcard_message_length = $this->wps_common_fun->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_giftcard_message_length' );
		if ( '' === $giftcard_message_length ) {
			$giftcard_message_length = 300;
		}

		$wps_wgm = array(
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'wps_gc_nonce'   => wp_create_nonce( 'wps-gc-verify-nonce' ),
			'pricing_type'   => array(),
			'product_id'     => 0,
			/* translators: %s: seconds */
			'price_field'    => sprintf( __( 'Price: %sField is empty', 'woo-gift-cards-lite' ), '</b>' ),
			/* translators: %s: seconds */
			'to_empty'       => sprintf( __( 'Recipient Email: %sField is empty.', 'woo-gift-cards-lite' ), '</b>' ),
			/* translators: %s: seconds */
			'to_empty_name'  => sprintf( __( 'To: Name Field is empty.', 'woo-gift-cards-lite' ), '</b>' ),
			/* translators: %s: seconds */
			'to_invalid'     => sprintf( __( 'Recipient Email: Invalid email format.', 'woo-gift-cards-lite' ), '</b>' ),
			/* translators: %s: seconds */
			'from_empty'     => sprintf( __( 'From: %sField is empty.', 'woo-gift-cards-lite' ), '</b>' ),
			/* translators: %s: seconds */
			'msg_empty'      => sprintf( __( 'Message: %sField is empty.', 'woo-gift-cards-lite' ), '</b>' ),
			/* translators: %s: seconds */
			'msg_length_err' => sprintf( __( 'Message: %1$sMessage length cannot exceed %2$s characters.', 'woo-gift-cards-lite' ), '</b>', $giftcard_message_length ),
			'msg_length'     => $giftcard_message_length,
			/* translators: %s: seconds */
			'price_range'    => sprintf( __( 'Price Range: %sPlease enter price within Range.', 'woo-gift-cards-lite' ), '</b>' ),
			'min_user_price' => sprintf( __( 'Gift Card price should not be less than the minimum amount.', 'woo-gift-cards-lite' ), '</b>' ),
			'recipient_name' => sprintf( __( ' recipient names should be entered seperated by comma', 'woo-gift-cards-lite' ), '</b>' ),
			'is_pro_active'  => wps_uwgc_pro_active(),
		);
		global $post;
		$post_id = isset( $post->ID ) ? $post->ID : '';
		$page_content = '';
		if ( ! empty( $post_id ) ) {
			$page         = get_post( $post->ID );
			$page_content = ! empty( $page->post_content ) ? $page->post_content : '';
		}
		if ( is_product() || str_contains( $page_content, 'product_page id' ) ) {

			if ( str_contains( $page_content, 'product_page id' ) ) {
				$content = $post->post_content;
				if ( isset( ( explode( '=', explode( ']', $content )[0] ) )[1] ) ) {
					$array = ( explode( '=', explode( ']', $content )[0] ) )[1];
					$product_id = intval( explode( '"', $array )[1] );
				} else {
					$product_id    = $post->ID;
				}
			} else {
				$product_id    = $post->ID;
			}
			$product_types = wp_get_object_terms( $product_id, 'product_type' );
			if ( isset( $product_types[0] ) ) {
				$product_type       = $product_types[0]->slug;
				$sell_as_a_giftcard = get_post_meta( $product_id, '_sell_as_a_giftcard' );
				if ( 'wgm_gift_card' === $product_type || ( isset( $sell_as_a_giftcard[0] ) && 'yes' === $sell_as_a_giftcard[0] ) ) {
					// for price based on country.
					if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
						$wps_wgm_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
						if ( wcpbc_the_zone() !== null && wcpbc_the_zone() ) {
							if ( isset( $wps_wgm_pricing['type'] ) ) {
								$product_pricing_type = $wps_wgm_pricing['type'];
								if ( 'wps_wgm_range_price' === $product_pricing_type ) {
									$from_price              = $wps_wgm_pricing['from'];
									$to_price                = $wps_wgm_pricing['to'];
									$from_price              = wcpbc_the_zone()->get_exchange_rate_price( $from_price );
									$to_price                = wcpbc_the_zone()->get_exchange_rate_price( $to_price );
									$wps_wgm_pricing['from'] = $from_price;
									$wps_wgm_pricing['to']   = $to_price;
								}
							}
						}
					} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
						$wps_wgm_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
						if ( isset( $wps_wgm_pricing['type'] ) ) {
							$product_pricing_type = $wps_wgm_pricing['type'];
							if ( 'wps_wgm_range_price' === $product_pricing_type ) {
								$from_price              = $wps_wgm_pricing['from'];
								$to_price                = $wps_wgm_pricing['to'];
								$from_price              = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $from_price );
								$to_price                = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $to_price );
								$wps_wgm_pricing['from'] = $from_price;
								$wps_wgm_pricing['to']   = $to_price;
							}
						}
					} else {
						$wps_wgm_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
						$is_customizable = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );
						$genaral_settings = get_option( 'wps_wgm_general_settings', array() );
						$enable_sent_multiple_gc = $this->wps_common_fun->wps_wgm_get_template_data( $genaral_settings, 'wps_wgm_general_setting_enable_sent_multiple_giftcard' );
						$is_imported_product = get_post_meta( $product_id, 'is_imported', true );
					}

					$wps_wgm['pricing_type'] = $wps_wgm_pricing;
					$wps_wgm['product_id']   = $product_id;
					$wps_wgm['is_customizable'] = $is_customizable;
					$wps_wgm['enable_sent_multiple_gc'] = $enable_sent_multiple_gc;
					$wps_wgm['is_imported'] = $is_imported_product;
					$wps_wgm['decimal_separator'] = get_option( 'woocommerce_price_decimal_sep' );
					wp_enqueue_script( 'thickbox' );
					$wps_wgm['wps_wgm_nonce'] = wp_create_nonce( 'wps-wgc-verify-nonce' );
					wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce_gift_cards_lite-public.js', array( 'jquery' ), $this->version, true );
					wp_localize_script( $this->plugin_name, 'wps_wgm', $wps_wgm );
					wp_enqueue_script( $this->plugin_name );
				}
			}
		}

		if ( str_contains( $page_content, 'wps_check_your_gift_card_balance' ) || is_page( 'my-account' ) ) {
			$wps_wgm_check_balance = array(
				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
				'wps_nonce_check' => wp_create_nonce( 'wps-wgc-verify-nonce-check' ),
				'empty_msg'       => esc_html__( 'Fields cannot be empty!', 'woo-gift-cards-lite' ), // PAR Compatibility.
			);

			wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce_gifr_cards_lite_check_balance.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( $this->plugin_name, 'wps_wgm_check_balance', $wps_wgm_check_balance );
			wp_enqueue_script( $this->plugin_name );
		}
	}
	/**
	 * Function to display the cart html.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_before_add_to_cart_button().
	 * @param object $wps_product Object of product.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_before_add_to_cart_button( $wps_product ) {

		if ( '' === $wps_product ) {
			global $product;
			$product_id = $product->get_id();
		} else {
			$product_id = $wps_product;
		}

		$product_types = wp_get_object_terms( $product_id, 'product_type' );
		if ( ! empty( $product_types ) ) {
			$product_type  = $product_types[0]->slug;
		} else {
			$product_type = '';
		}

		if ( 'wgm_gift_card' === $product_type ) {
			$wps_cart_html = $this->wps_wgm_before_cart_data( $wps_product );
		} else {
			$wps_cart_html = apply_filters( 'wps_wgm_enable_sell_as_a_gc', $wps_product );
		}
		$allowed_tags = $this->wps_common_fun->wps_allowed_html_tags();
		// @codingStandardsIgnoreStart.
		echo wp_kses( $wps_cart_html, $allowed_tags );
		// @codingStandardsIgnoreEnd.
	}

	/**
	 * Returns cart html to display.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_before_cart_data().
	 * @param object $wps_product Object of product.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_before_cart_data( $wps_product ) {
		if ( '' === $wps_product ) {
			global $product;
			$product = $product;
		} else {
			$product = $wps_product;
		}
		if ( isset( $product ) && ! empty( $product ) ) {
			$wps_wgc_enable = wps_wgm_giftcard_enable();
			if ( $wps_wgc_enable ) {
				$product_id = $product->get_id();
				if ( isset( $product_id ) && ! empty( $product_id ) ) {
					$product_types = wp_get_object_terms( $product_id, 'product_type' );
					$product_type  = $product_types[0]->slug;
					if ( 'wgm_gift_card' === $product_type ) {
						$cart_html              = '';
						$wps_additional_section = '';
						$product_pricing        = get_post_meta( $product_id, 'wps_wgm_pricing', true );
						if ( isset( $product_pricing ) && ! empty( $product_pricing ) ) {

							$other_settings = get_option( 'wps_wgm_other_settings', array() );

							$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
							$use_new_page_layout = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_new_gift_card_page_layout' );
							if ( 'on' == $use_new_page_layout ) {
								$cart_html .= '<div class="wps_wgm_added_wrapper wps-gc_lay-2">';
							} else {
								$cart_html .= '<div class="wps_wgm_added_wrapper">';
							}

							wp_nonce_field( 'wps_wgm_single_nonce', 'wps_wgm_single_nonce_field' );
							$genaral_settings = get_option( 'wps_wgm_general_settings', array() );
							$is_group_product = get_post_meta( $product_id, 'wps_wgm_is_group_gift', true );
							$enable_sent_multiple_gc = $this->wps_common_fun->wps_wgm_get_template_data( $genaral_settings, 'wps_wgm_general_setting_enable_sent_multiple_giftcard' );

							$is_imported_product = get_post_meta( $product_id, 'is_imported', true );
							if ( isset( $product_pricing['type'] ) ) {
								$product_pricing_type = $product_pricing['type'];
								if ( 'wps_wgm_range_price' === $product_pricing_type ) {
									$default_price  = $product_pricing['default_price'];
									$from_price     = $product_pricing['from'];
									$to_price       = $product_pricing['to'];
									$text_box_price = ( $default_price >= $from_price && $default_price <= $to_price ) ? $default_price : $from_price;
										// hooks for discount features.
									do_action( 'wps_wgm_range_price_discount', $product, $product_pricing, $text_box_price );

									if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
										if ( wcpbc_the_zone() !== null && wcpbc_the_zone() ) {
											$default_price = wcpbc_the_zone()->get_exchange_rate_price( $default_price );
											$to_price      = wcpbc_the_zone()->get_exchange_rate_price( $to_price );
											$from_price    = wcpbc_the_zone()->get_exchange_rate_price( $from_price );
										}
										$wps_new_price = ( $default_price >= $from_price && $default_price <= $to_price ) ? $default_price : $from_price;
										$cart_html    .= '<p class="wps_wgm_section selected_price_type">
											<label>' . __( 'Enter Price Within Above Range', 'woo-gift-cards-lite' ) . '</label>	
											<input type="text" class="input-text wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" value="' . $wps_new_price . '" max="' . $to_price . '" min="' . $from_price . '">
											</p>';
									} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
										$default_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $default_price );
										$to_price      = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $to_price );
										$from_price    = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $from_price );
										$wps_new_price = ( $default_price >= $from_price && $default_price <= $to_price ) ? $default_price : $from_price;
										$cart_html    .= '<p class="wps_wgm_section selected_price_type">
											<label>' . __( 'Enter Price Within Above Range', 'woo-gift-cards-lite' ) . '</label>	
											<input type="text" class="input-text wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" value="' . $wps_new_price . '" max="' . $to_price . '" min="' . $from_price . '">
											</p>';
									} else {
										$wps_new_price = ( $default_price >= $from_price && $default_price <= $to_price ) ? $default_price : $from_price;
										$cart_html    .= '<p class="wps_wgm_section selected_price_type">
											<label>' . __( 'Enter Price Within Above Range', 'woo-gift-cards-lite' ) . '</label>	
											<input type="text" class="input-text wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" value="' . $wps_new_price . '" max="' . $to_price . '" min="' . $from_price . '">
											</p>';
									}
								}
								if ( 'wps_wgm_default_price' === $product_pricing_type ) {
									$default_price = $product_pricing['default_price'];
									$cart_html    .= '<input type="hidden" class="wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" value="' . $default_price . '">';
										// hooks for discount features.
									do_action( 'wps_wgm_default_price_discount', $product, $product_pricing );
								}
								if ( 'wps_wgm_selected_price' === $product_pricing_type ) {
									$default_price  = $product_pricing['default_price'];
									$selected_price = $product_pricing['price'];
									if ( ! empty( $selected_price ) ) {
										$label           = __( 'Choose Gift Card Selected Price ', 'woo-gift-cards-lite' );
										$cart_html      .= '<p class="wps_wgm_section selected_price_type">
													<label class="wps_wgc_label">' . $label . '</label><br/>';
										$selected_prices = explode( '|', $selected_price );
										if ( isset( $selected_prices ) && ! empty( $selected_prices ) ) {
											$cart_html .= '<select name="wps_wgm_price" class="wps_wgm_price" id="wps_wgm_price" >';
											foreach ( $selected_prices as $price ) {
												if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {

													if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
														$default_price = wcpbc_the_zone()->get_exchange_rate_price( $default_price );
														$prices        = wcpbc_the_zone()->get_exchange_rate_price( $price );
														if ( $prices === $default_price ) {
															$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $prices ) . '</option>';
														} else {
															$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $prices ) . '</option>';
														}
													} else {
														if ( $price == $default_price ) {
															$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $price ) . '</option>';
														} else {
															$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $price ) . '</option>';
														}
													}
												} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
													$default_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $default_price );
													$prices        = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $price );
													if ( $prices === $default_price ) {
														$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $prices ) . '</option>';
													} else {
														$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $prices ) . '</option>';
													}
												} else {
													$decimal_separator = get_option( 'woocommerce_price_decimal_sep' );
													$prices = floatval( str_replace( $decimal_separator, '.', $price ) );
													$prices = ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) ?
													wc_get_price_including_tax(
														$product,
														array(
															'qty'   => 1,
															'price' => $prices,
														)
													) :
													wc_get_price_excluding_tax(
														$product,
														array(
															'qty'   => 1,
															'price' => $prices,
														)
													);

													if ( $price == $default_price ) {
														$cart_html .= '<option  value="' . $price . '" selected>' . wc_price( $prices ) . '</option>';
													} else {
														$cart_html .= '<option  value="' . $price . '">' . wc_price( $prices ) . '</option>';
													}
												}
											}
											$cart_html .= '</select>';
										}
											$cart_html .= '</p>';
									}
								}
								if ( 'wps_wgm_user_price' === $product_pricing_type ) {
									$default_price  = $product_pricing['default_price'];
									$min_user_price = $product_pricing['min_user_price'];
										// hooks for discount features.
									do_action( 'wps_wgm_user_price_discount', $product, $product_pricing );
										// price based on country.
									if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
										$default_price = $product_pricing['default_price'];
										if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
											$default_price = wcpbc_the_zone()->get_exchange_rate_price( $default_price );
										}
										$cart_html .= '<p class="wps_wgm_section selected_price_type"">
											<label class="wps_wgc_label">' . __( 'Enter Gift Card Price ', 'woo-gift-cards-lite' ) . '</label>	
											<input type="text" class="wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" min="1" value = ' . $default_price . '>
											<span class="wps_wgm_min_user_price">' . __( 'Minimum Price is : ', 'woo-gift-cards-lite' ) . $min_user_price . '</span></p>';
									} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
										$default_price = $product_pricing['default_price'];
										$default_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $default_price );
										$cart_html .= '<p class="wps_wgm_section selected_price_type"">
											<label class="wps_wgc_label">' . __( 'Enter Gift Card Price ', 'woo-gift-cards-lite' ) . '</label>	
											<input type="text" class="wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" min="1" value = ' . $default_price . '>
											<span class="wps_wgm_min_user_price">' . __( 'Minimum Price is : ', 'woo-gift-cards-lite' ) . $min_user_price . '</span></p>';
									} else {
										$cart_html .= '<p class="wps_wgm_section selected_price_type"">
											<label class="wps_wgc_label">' . __( 'Enter Gift Card Price ', 'woo-gift-cards-lite' ) . '</label>	
											<input type="text" class="wps_wgm_price" id="wps_wgm_price" name="wps_wgm_price" min="1" value = ' . $default_price . '>
											<span class="wps_wgm_min_user_price">' . __( 'Minimum Price is : ', 'woo-gift-cards-lite' ) . $min_user_price . '</span></p>';
									}
								}
								if ( 'wps_wgm_variable_price' === $product_pricing_type ) {
									?>
									<?php
									$variation_amount = $product_pricing['wps_wgm_variation_price'];
									$varable_text     = $product_pricing['wps_wgm_variation_text'];

									if ( isset( $variation_amount ) && is_array( $variation_amount ) && ! empty( $variation_amount ) ) {
										$wps_price = ( '' != $variation_amount[0] ) ? $variation_amount[0] : 0;
										if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
											if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
												$wps_price = wcpbc_the_zone()->get_exchange_rate_price( $wps_price );
											}
										}
										$decimal_separator = get_option( 'woocommerce_price_decimal_sep' );
										$wps_price         = floatval( str_replace( $decimal_separator, '.', $wps_price ) );
										?>
									<p class="wps_wgm_section">
										<span id="wps_wgm_text" class="wps_variable_currency"><?php wc_price( $wps_price ); ?></span>
									</p>
									<p class="wps_wgm_section">
										<select name="wps_wgm_price" class="wps_wgm_price" id="wps_wgm_price">
											<?php
											foreach ( $variation_amount as $key => $value ) {
												if ( isset( $value ) && ! empty( $value ) ) {
													?>
													<option value="<?php echo esc_html( $value ); ?>"><?php echo esc_html( $varable_text[ $key ] ); ?></option>
													<?php
												}
											}
											?>
										</select>
										<input type="hidden" id="wps_wgm_variable_price_description" name="wps_wgm_variable_price_description" value="<?php echo isset( $varable_text[0] ) ? esc_html( $varable_text[0] ) : ''; ?>">
									</p>
										<?php
									}
								}
								$cart_html .= apply_filters( 'wps_wgm_add_price_types', $wps_additional_section, $product, $product_pricing );
							}
							// new layout setting ///////////////////////////////.
							$other_settings = get_option( 'wps_wgm_other_settings', array() );
							$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
							$use_new_page_layout = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_new_gift_card_page_layout' );

							if ( 'on' == $use_new_page_layout ) {
								if ( '' !== apply_filters( 'wps_wgm_display_thumbnail', $wps_additional_section, $product_id ) ) {
									$cart_html .= apply_filters( 'wps_wgm_display_thumbnail', $wps_additional_section, $product_id )['html'];
									$choosed_temp = apply_filters( 'wps_wgm_display_thumbnail', $wps_additional_section, $product_id )['choosen_temp_id'];
								}
							}
							// new layout setting ///////////////////////////////.
							$other_settings = get_option( 'wps_wgm_other_settings', array() );
							$wps_wgm_preview_disable = $this->wps_common_fun->wps_wgm_get_template_data( $other_settings, 'wps_wgm_additional_preview_disable' );

							if ( '' == $use_new_page_layout ) {
								$cart_html .= apply_filters( 'wps_wgm_select_date', $wps_additional_section, $product_id );
								$cart_html .= '<p class="wps_wgm_section wps_from">
									<label class="wps_wgc_label">' . __( 'From', 'woo-gift-cards-lite' ) . '</label>	
									<input type="text"  name="wps_wgm_from_name" id="wps_wgm_from_name" class="wps_wgm_from_name" placeholder="' . __( 'Enter the sender name', 'woo-gift-cards-lite' ) . '" required="required">
									</p>';
							}
							if ( 'on' == $use_new_page_layout ) {
								$cart_html .= "<div class='wps_wgm_new_layout_for_from_select' >";
								$cart_html .= apply_filters( 'wps_wgm_select_date', $wps_additional_section, $product_id );
								$cart_html .= '<p class="wps_wgm_section wps_from">
									<label class="wps_wgc_label">' . __( 'From', 'woo-gift-cards-lite' ) . '</label>	
									<input type="text"  name="wps_wgm_from_name" id="wps_wgm_from_name" class="wps_wgm_from_name" placeholder="' . __( 'Enter the sender name', 'woo-gift-cards-lite' ) . '" required="required">
									</p>';
								$cart_html .= '</div>';

							}
							$mail_settings = get_option( 'wps_wgm_mail_settings', array() );
							$default_giftcard_message = $this->wps_common_fun->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_default_message' );
							$cart_html .= '<p class="wps_wgm_section wps_message">
							<label class="wps_wgc_label">' . __( 'Gift Message ', 'woo-gift-cards-lite' ) . '</label>	
							<textarea name="wps_wgm_message" id="wps_wgm_message" class="wps_wgm_message">' . $default_giftcard_message . '</textarea>';
							$giftcard_message_length = $this->wps_common_fun->wps_wgm_get_template_data( $mail_settings, 'wps_wgm_mail_setting_giftcard_message_length' );
							if ( '' == $giftcard_message_length ) {
								$giftcard_message_length = 300;
							}
							$cart_html .= '<span class = "wps_wgm_message_length" >';
							$cart_html .= __( 'Characters: ( ', 'woo-gift-cards-lite' ) . '<span class="wps_box_char">0</span>/' . $giftcard_message_length . ')</span>							
							</p>';
							$cart_html .= apply_filters( 'wps_wgm_add_notiication_section', $wps_additional_section, $product_id );
							$delivery_settings = get_option( 'wps_wgm_delivery_settings', true );
							$wps_wgm_delivery_setting_method = $this->wps_common_fun->wps_wgm_get_template_data( $delivery_settings, 'wps_wgm_send_giftcard' );
							if ( ! wps_uwgc_pro_active() ) {
								if ( 'customer_choose' == $wps_wgm_delivery_setting_method || 'shipping' == $wps_wgm_delivery_setting_method ) {
									$wps_wgm_delivery_setting_method = 'Mail to recipient';
								}
							}
								$cart_html .= '<div class="wps_wgm_section wps_delivery_method">';
									$cart_html .= '<label class = "wps_wgc_label">' . __( 'Delivery Method', 'woo-gift-cards-lite' ) . '</label>';
							if ( ( isset( $wps_wgm_delivery_setting_method ) && 'Mail to recipient' == $wps_wgm_delivery_setting_method ) || ( '' == $wps_wgm_delivery_setting_method ) ) {
								$html = ( wps_uwgc_pro_active() && 'on' === $enable_sent_multiple_gc && 'yes' !== $is_group_product && 'yes' !== $is_imported_product ) ? '<span class= "wps_wgm_msg_info_multiple_email">' . __( 'Separate multiple addresses with a comma', 'woo-gift-cards-lite' ) . '</span>' : '';
								$cart_html .= '<div class="wps_wgm_delivery_method ">
											<input type="radio" name="wps_wgm_send_giftcard" value="Mail to recipient" class="wps_wgm_send_giftcard" checked="checked" id="wps_wgm_to_email_send" >
											<span class="wps_wgm_method">' . __( 'Mail To Recipient', 'woo-gift-cards-lite' ) . '</span>
											<div class="wps_wgm_delivery_via_email">
												<input type="text"  name="wps_wgm_to_email" id="wps_wgm_to_email" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Email', 'woo-gift-cards-lite' ) . '">
												' . $html . '
												<input type="text"  name="wps_wgm_to_name_optional" id="wps_wgm_to_name_optional" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Name', 'woo-gift-cards-lite' ) . '">
												<span class= "wps_wgm_msg_info">' . __( 'We will send it to the recipient\'s email address', 'woo-gift-cards-lite' ) . '</span>
												<span class= "wps_wgm_msg_info_multiple_name"></span>
											</div>
										</div>';
							}
							if ( isset( $wps_wgm_delivery_setting_method ) && 'Downloadable' == $wps_wgm_delivery_setting_method ) {
								$cart_html .= '<div class="wps_wgm_delivery_method">
											<input type="radio" name="wps_wgm_send_giftcard" value="Downloadable" class="wps_wgm_send_giftcard" checked="checked" id="wps_wgm_send_giftcard_download">
											<span class="wps_wgm_method">' . __( 'You Print & Give To Recipient', 'woo-gift-cards-lite' ) . '</span>
											<div class="wps_wgm_delivery_via_buyer">
												<input type="text"  name="wps_wgm_to_email_name" id="wps_wgm_to_download" class="wps_wgm_to_email" placeholder="' . __( 'Enter the Recipient Name', 'woo-gift-cards-lite' ) . '">
												<span class= "wps_wgm_msg_info">' . __( 'After Checkout, you can print your gift card', 'woo-gift-cards-lite' ) . '</span>
											</div>
										</div>';
							}
							$cart_html .= apply_filters( 'wps_wgm_add_delivery_method', $wps_additional_section, $product_id );
							$cart_html .= '</div>';
							$cart_html .= apply_filters( 'wps_wgm_add_section_after_delivery', $wps_additional_section, $product_id );
							$wps_wgm_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
							if ( array_key_exists( 'template', $wps_wgm_pricing ) ) {
								$templateid = $wps_wgm_pricing['template'];
							} else {
								$templateid = $this->wps_common_fun->wps_get_org_selected_template();
							}
							$choosed_temp = '';
							if ( ! wps_uwgc_pro_active() ) {
								if ( '1' < count( $templateid ) ) {
									$wps_get_pro_templates = get_option( 'wps_uwgc_templateid', array() );
									if ( ! empty( $wps_get_pro_templates ) ) {
										$wps_get_lite_temp = array_diff( $templateid, $wps_get_pro_templates );
										if ( ! empty( $wps_get_lite_temp ) ) {
											$wps_index = array_keys( $wps_get_lite_temp )[0];
											if ( 0 !== count( $wps_get_lite_temp ) ) {
												$choosed_temp = $wps_get_lite_temp[ $wps_index ];
											}
										} else {
											$args = array(
												'post_type' => 'giftcard',
												'posts_per_page' => -1,
											);
											$loop = new WP_Query( $args );
											$template = array();
											foreach ( $loop->posts as $key => $value ) {
												$template_id = $value->ID;
												$template_title = $value->post_title;
												$template[ $template_id ] = $template_title;
											}
											if ( ! empty( $template ) ) {
												$wps_get_lite_temp = array_diff( array_keys( $template ), $wps_get_pro_templates );
												$wps_index = array_keys( $wps_get_lite_temp )[0];
												if ( 0 !== count( $wps_get_lite_temp ) ) {
													$choosed_temp = $wps_get_lite_temp[ $wps_index ];
												}
											}
										}
									} else {
										$choosed_temp = $templateid[0];
									}
								} else {
									$choosed_temp = $templateid[0];
								}
							}
							// old layout setting //////////////////////////////////////.

							if ( '' == $use_new_page_layout ) {
								if ( '' !== apply_filters( 'wps_wgm_display_thumbnail', $wps_additional_section, $product_id ) ) {
									$cart_html .= apply_filters( 'wps_wgm_display_thumbnail', $wps_additional_section, $product_id )['html'];
									$choosed_temp = apply_filters( 'wps_wgm_display_thumbnail', $wps_additional_section, $product_id )['choosen_temp_id'];
								}
							}
							// old layout setting //////////////////////////////////////.
							$cart_html .= '<input name="add-to-cart" value="' . $product_id . '" type="hidden" class="wps_wgm_hidden_pro_id">';
							if ( is_array( $templateid ) && ! empty( $templateid ) ) {
								$cart_html .= '<input name="wps_wgm_selected_temp" id="wps_wgm_selected_temp" value="' . $choosed_temp . '" type="hidden">';
							}
							$other_settings = get_option( 'wps_wgm_other_settings', array() );
							$wps_wgm_preview_disable = $this->wps_common_fun->wps_wgm_get_template_data( $other_settings, 'wps_wgm_additional_preview_disable' );

							if ( empty( $wps_wgm_preview_disable ) ) {
								$cart_html .= '<span class="mwg_wgm_preview_email"><a id="mwg_wgm_preview_email" href="javascript:void(0);">' . __( 'PREVIEW', 'woo-gift-cards-lite' ) . '</a></span>';
							}
							$cart_html .= apply_filters( 'wps_wgm_after_preview_section', $wps_additional_section, $product_id );
							$cart_html .= '</div>';
						}
						return $cart_html;
					}
				}
			}
		}
	}

	/**
	 * Adds the meta data into the Cart Item.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_add_cart_item_data()
	 * @param   array $the_cart_data  The array of Cart Data.
	 * @param   array $product_id  product Id.
	 * @param   array $variation_id  variation_id.
	 * @return  $the_cart_data
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_add_cart_item_data( $the_cart_data, $product_id, $variation_id ) {

		$wps_wgc_enable = wps_wgm_giftcard_enable();

		if ( $wps_wgc_enable ) {
			$product_types = wp_get_object_terms( $product_id, 'product_type' );
			if ( isset( $product_types[0] ) ) {
				$product_type = $product_types[0]->slug;
				if ( 'wgm_gift_card' === $product_type || ( isset( $_POST['wps_gift_this_product'] ) && 'on' === $_POST['wps_gift_this_product'] ) ) {
					$wps_field_nonce = isset( $_POST['wps_wgm_single_nonce_field'] ) ? stripcslashes( sanitize_text_field( wp_unslash( $_POST['wps_wgm_single_nonce_field'] ) ) ) : '';
					if ( ! wp_verify_nonce( $wps_field_nonce, 'wps_wgm_single_nonce' ) ) {
						echo '';
					} else {

						// for price based on country.
						if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
							if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
								$product_pricing      = ! empty( get_post_meta( $product_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $product_id, 'wps_wgm_pricing', true ) : get_post_meta( $product_id, 'wps_wgm_pricing_details', true );
								$product_pricing_type = $product_pricing['type'];
								if ( isset( $_POST['wps_wgm_price'] ) && ! empty( $_POST['wps_wgm_price'] ) ) {
									if ( 'wps_wgm_range_price' == $product_pricing_type || 'wps_wgm_user_price' == $product_pricing_type ) {
										$exchange_rate = wcpbc_the_zone()->get_exchange_rate();
										$wps_price = sanitize_text_field( wp_unslash( $_POST['wps_wgm_price'] ) );
										$_POST['wps_wgm_price'] = floatval( $wps_price / $exchange_rate );
									}
								}
							}
						} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_to_base_currency' ) ) {
							$product_pricing      = ! empty( get_post_meta( $product_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $product_id, 'wps_wgm_pricing', true ) : get_post_meta( $product_id, 'wps_wgm_pricing_details', true );
							$product_pricing_type = $product_pricing['type'];
							$is_customizable      = get_post_meta( $product_id, 'woocommerce_customizable_giftware', true );
							if ( isset( $_POST['wps_wgm_price'] ) && ! empty( $_POST['wps_wgm_price'] ) ) {
								if ( 'wps_wgm_range_price' == $product_pricing_type || 'wps_wgm_user_price' == $product_pricing_type ) {
									$_POST['wps_wgm_price'] = wps_mmcsfw_admin_fetch_currency_rates_to_base_currency( '', sanitize_text_field( wp_unslash( $_POST['wps_wgm_price'] ) ) );
								} elseif ( 'yes' === $is_customizable && 'wps_wgm_selected_price' == $product_pricing_type ) {
									$_POST['wps_wgm_price'] = wps_mmcsfw_admin_fetch_currency_rates_to_base_currency( '', sanitize_text_field( wp_unslash( $_POST['wps_wgm_price'] ) ) );
								}
							}
						}
						if ( isset( $_POST['wps_wgm_send_giftcard'] ) && ! empty( $_POST['wps_wgm_send_giftcard'] ) ) {
							$product_pricing = ! empty( get_post_meta( $product_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $product_id, 'wps_wgm_pricing', true ) : get_post_meta( $product_id, 'wps_wgm_pricing_details', true );
							if ( isset( $product_pricing ) && ! empty( $product_pricing ) ) {

								if ( isset( $_POST['wps_wgm_to_email'] ) && ! empty( $_POST['wps_wgm_to_email'] ) ) {

									$item_meta['wps_wgm_to_email'] = sanitize_text_field( wp_unslash( $_POST['wps_wgm_to_email'] ) );
								}
								if ( isset( $_POST['wps_wgm_to_email_name'] ) && ! empty( $_POST['wps_wgm_to_email_name'] ) ) {

									$item_meta['wps_wgm_to_email'] = sanitize_text_field( wp_unslash( $_POST['wps_wgm_to_email_name'] ) );
								}
								if ( isset( $_POST['wps_wgm_from_name'] ) && ! empty( $_POST['wps_wgm_from_name'] ) ) {

									$item_meta['wps_wgm_from_name'] = sanitize_text_field( wp_unslash( $_POST['wps_wgm_from_name'] ) );
								}
								if ( isset( $_POST['wps_wgm_message'] ) && ! empty( $_POST['wps_wgm_message'] ) ) {
									$item_meta['wps_wgm_message'] = sanitize_text_field( wp_unslash( $_POST['wps_wgm_message'] ) );
								}
								if ( isset( $_POST['wps_wgm_variable_price_description'] ) && ! empty( $_POST['wps_wgm_variable_price_description'] ) ) {
									$item_meta['wps_wgm_variable_price_description'] = sanitize_text_field( wp_unslash( $_POST['wps_wgm_variable_price_description'] ) );
								}
								if ( isset( $_POST['wps_wgm_send_giftcard'] ) && ! empty( $_POST['wps_wgm_send_giftcard'] ) ) {
									$item_meta['delivery_method'] = sanitize_text_field( wp_unslash( $_POST['wps_wgm_send_giftcard'] ) );
								}
								if ( isset( $_POST['wps_wgm_price'] ) ) {

									$wps_wgm_price = sanitize_text_field( wp_unslash( $_POST['wps_wgm_price'] ) );

									if ( isset( $product_pricing['type'] ) && 'wps_wgm_default_price' == $product_pricing['type'] ) {
										if ( isset( $product_pricing['default_price'] ) && $product_pricing['default_price'] == $wps_wgm_price ) {
											$item_meta['wps_wgm_price'] = $wps_wgm_price;
										} elseif ( isset( $product_type ) && 'variable' == $product_type ) {
											$item_meta['wps_wgm_price'] = $wps_wgm_price;
										} else {
											$item_meta['wps_wgm_price'] = $product_pricing['default_price'];
										}
									} else if ( isset( $product_pricing['type'] ) && 'wps_wgm_selected_price' == $product_pricing['type'] ) {

										$price = $product_pricing['price'];
										$price = explode( '|', $price );
										if ( isset( $price ) && is_array( $price ) ) {
											if ( in_array( $wps_wgm_price, $price ) ) {
												$item_meta['wps_wgm_price'] = $wps_wgm_price;
											} else {
												$item_meta['wps_wgm_price'] = $product_pricing['default_price'];
											}
										}
									} else if ( isset( $product_pricing['type'] ) && 'wps_wgm_range_price' == $product_pricing['type'] ) {

										if ( $wps_wgm_price > $product_pricing['to'] || $wps_wgm_price < $product_pricing['from'] ) {
											$item_meta['wps_wgm_price'] = $product_pricing['default_price'];
										} else {
											$item_meta['wps_wgm_price'] = $wps_wgm_price;
										}
									} else {
										$item_meta['wps_wgm_price'] = $wps_wgm_price;
									}
								}
								if ( isset( $_POST['wps_wgm_selected_temp'] ) ) {
									$item_meta['wps_wgm_selected_temp'] = sanitize_text_field( wp_unslash( $_POST['wps_wgm_selected_temp'] ) );
								}
								$item_meta = apply_filters( 'wps_wgm_add_cart_item_data', $item_meta, $the_cart_data, $product_id, $variation_id );
								$the_cart_data ['product_meta'] = array( 'meta_data' => $item_meta );
							}
						}
					}
				}
			}
		}
		if ( get_option( 'wps_gccoupon_rechargeable_product_id' ) == $product_id ) {
			$recharge_code = WC()->session->get( 'gc_recharge_code' );
			$item_meta['recharge_coupon_key_field'] = $recharge_code;
			$the_cart_data['product_meta'] = array( 'meta_data' => $item_meta );
		}
		if ( get_option( 'contributor_product_id' ) == $product_id ) {
			$recharge_code = WC()->session->get( 'gc_recharge_code' );
			if ( isset( $_POST['wps_main_order_id'] ) ) {
				$item_meta['wps_main_order_id'] = sanitize_text_field( wp_unslash( $_POST['wps_main_order_id'] ) );
			}
			if ( isset( $_POST['wps_main_prod_id'] ) ) {
				$item_meta['wps_main_prod_id'] = sanitize_text_field( wp_unslash( $_POST['wps_main_prod_id'] ) );
			}

			$the_cart_data['product_meta'] = array( 'meta_data' => $item_meta );
		}
		return $the_cart_data;
	}
	/**
	 * Nonce verified fucntion.
	 *
	 * @throws Exception If order is invalid.
	 * @param  array $cart_item_key cart_item_key.
	 * @param  int   $product_id product_id.
	 */
	public function wps_nonce_not_verify_add_to_cart( $cart_item_key, $product_id ) {

		$product_types = wp_get_object_terms( $product_id, 'product_type' );

		if ( isset( $product_types[0] ) ) {
			$product_type = $product_types[0]->slug;
			if ( 'wgm_gift_card' === $product_type || ( isset( $_POST['wps_gift_this_product'] ) && 'on' === $_POST['wps_gift_this_product'] ) ) {
				$wps_field_nonce = isset( $_POST['wps_wgm_single_nonce_field'] ) ? stripcslashes( sanitize_text_field( wp_unslash( $_POST['wps_wgm_single_nonce_field'] ) ) ) : '';
				if ( ! wp_verify_nonce( $wps_field_nonce, 'wps_wgm_single_nonce' ) ) {
					throw new Exception( esc_html__( 'Sorry, your nonce not verify. Please try again !', 'woo-gift-cards-lite' ) );

				}
			}
		}
	}

	/**
	 * List out the Meta Data into the Cart Items.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_get_item_data()
	 * @param   array $item_meta  New Item Meta.
	 * @param   array $existing_item_meta  existing_item_meta.
	 * @return  $item_meta
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_get_item_data( $item_meta, $existing_item_meta ) {

		$wps_wgc_enable = wps_wgm_giftcard_enable();
		if ( $wps_wgc_enable ) {
			if ( isset( $existing_item_meta ['product_meta']['meta_data'] ) ) {
				foreach ( $existing_item_meta['product_meta'] ['meta_data'] as $key => $val ) {

					if ( 'wps_wgm_to_email' == $key ) {
						$item_meta [] = array(
							'name' => esc_html__( 'To', 'woo-gift-cards-lite' ),
							'value' => stripslashes( $val ),
						);
					}
					if ( 'wps_wgm_from_name' == $key ) {
						$item_meta [] = array(
							'name' => esc_html__( 'From', 'woo-gift-cards-lite' ),
							'value' => stripslashes( $val ),
						);
					}
					if ( 'wps_wgm_message' == $key ) {
						$item_meta [] = array(
							'name' => esc_html__( 'Gift Message', 'woo-gift-cards-lite' ),
							'value' => stripslashes( $val ),
						);
					}
					if ( 'delivery_method' == $key ) {
						if ( 'Mail to recipient' == $val ) {
							$val = __( 'Mail to recipient', 'woo-gift-cards-lite' );

						} else if ( 'Downloadable' == $val ) {
							$val = __( 'Downloadable', 'woo-gift-cards-lite' );
						} else if ( 'shipping' == $val ) {
							$val = __( 'shipping', 'woo-gift-cards-lite' );

						} else {
							$val = $val;
						}
						$item_meta [] = array(
							'name' => esc_html__( 'Delivery Method', 'woo-gift-cards-lite' ),
							'value' => stripslashes( $val ),
						);
					}
					if ( 'recharge_coupon_key_field' == $key ) {
						$item_meta[] = array(
							'key' => __( 'Coupon code', 'woo-gift-cards-lite' ),
							'value' => stripslashes( $val ),
						);
					}
					if ( 'wps_main_order_id' == $key ) {
						$item_meta[] = array(
							'key' => __( 'Original order', 'woo-gift-cards-lite' ),
							'value' => stripslashes( $val ),
						);
					}
					if ( 'wps_main_prod_id' == $key ) {
						$item_meta[] = array(
							'key' => __( 'Original product', 'woo-gift-cards-lite' ),
							'value' => stripslashes( $val ),
						);
					}
					$item_meta = apply_filters( 'wps_wgm_get_item_meta', $item_meta, $key, $val );
				}
			}
		}

		return $item_meta;
	}

	/**
	 * Set the Gift Card Price into Cart.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_before_calculate_totals()
	 * @param object $cart  Cart Data.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_before_calculate_totals( $cart ) {
		$wps_wgc_enable = wps_wgm_giftcard_enable();
		if ( $wps_wgc_enable ) {
			if ( isset( $cart ) && ! empty( $cart ) ) {
				foreach ( $cart->cart_contents as $key => $value ) {
					$product_id = $value['product_id'];
					$pro_quant = $value['quantity'];
					if ( isset( $value['product_meta']['meta_data'] ) ) {
						if ( isset( $value['product_meta']['meta_data']['wps_wgm_price'] ) ) {
							$gift_price = $value['product_meta']['meta_data']['wps_wgm_price'];

							if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
								if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
									$gift_price = wcpbc_the_zone()->get_exchange_rate_price( $gift_price );
								}
							}
							$gift_price = apply_filters( 'wps_wgm_before_calculate_totals', $gift_price, $value );
							$value['data']->set_price( $gift_price );
						}
					}
				}
			}
		}

	}

	/**
	 * Displays the Different Price type for Gift Cards into single product page
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_get_price_html()
	 * @param string $price_html price.
	 * @param object $product product.
	 * @return $price_html.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_get_price_html( $price_html, $product ) {
		$wps_wgc_enable = wps_wgm_giftcard_enable();
		if ( $wps_wgc_enable ) {
			$product_id = $product->get_id();
			if ( isset( $product_id ) ) {
				$product_types = wp_get_object_terms( $product_id, 'product_type' );
				if ( isset( $product_types[0] ) ) {
					$product_type = $product_types[0]->slug;
					if ( 'wgm_gift_card' == $product_type ) {
						$product_pricing = ! empty( get_post_meta( $product_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $product_id, 'wps_wgm_pricing', true ) : get_post_meta( $product_id, 'wps_wgm_pricing_details', true );
						if ( isset( $product_pricing ) && ! empty( $product_pricing ) ) {
							if ( isset( $product_pricing['type'] ) ) {
								$product_pricing_type = $product_pricing['type'];
								$decimal_separator = get_option( 'woocommerce_price_decimal_sep' );
								if ( 'wps_wgm_default_price' == $product_pricing_type ) {
									$new_price = '';
									$default_price = $product_pricing['default_price'];
									if ( ! is_admin() && function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
										$default_price = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $default_price );
										$price_html    = wps_mmcsfw_get_custom_currency_symbol( '' ) . $default_price;
									} else {
										$price_html = $price_html;
									}
								}
								if ( 'wps_wgm_range_price' == $product_pricing_type ) {
									$price_html = '';
									$from_price = $product_pricing['from'];
									$from_price = floatval( str_replace( $decimal_separator, '.', $from_price ) );
									$to_price = $product_pricing['to'];
									$to_price = floatval( str_replace( $decimal_separator, '.', $to_price ) );

									// price based on country.
									if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
										if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
											$from_price = wcpbc_the_zone()->get_exchange_rate_price( $from_price );
											$to_price = wcpbc_the_zone()->get_exchange_rate_price( $to_price );
										}
										$price_html .= '<ins><span class="woocommerce-Price-amount amount">' . wc_price( $from_price ) . ' - ' . wc_price( $to_price ) . '</span></ins>';
									} elseif ( ! is_admin() && function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
										$from_price  = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $from_price );
										$to_price    = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $to_price );
										$price_html .= '<ins><span class="woocommerce-Price-amount amount">' . wps_mmcsfw_get_custom_currency_symbol( '' ) . ( $from_price ) . ' - ' . wps_mmcsfw_get_custom_currency_symbol( '' ) . ( $to_price ) . '</span></ins>';
									} else {
										$price_html .= '<ins><span class="woocommerce-Price-amount amount">' . wc_price( $from_price ) . ' - ' . wc_price( $to_price ) . '</span></ins>';
									}
								}
								if ( 'wps_wgm_selected_price' == $product_pricing_type ) {
									$selected_price = $product_pricing['price'];
									if ( ! empty( $selected_price ) ) {
										$selected_prices = explode( '|', $selected_price );
										$selected_prices = str_replace( $decimal_separator, '.', $selected_prices );
										if ( isset( $selected_prices ) && ! empty( $selected_prices ) ) {
											$price_html = '';
											$price_html .= '<ins><span class="woocommerce-Price-amount amount">';
											$last_range = end( $selected_prices );
											// price based on country.
											if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {

												if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
													$last_range         = wcpbc_the_zone()->get_exchange_rate_price( $last_range );
													$selected_prices[0] = wcpbc_the_zone()->get_exchange_rate_price( $selected_prices[0] );
												}
												$price_html .= wc_price( $selected_prices[0] ) . '-' . wc_price( $last_range ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
											} elseif ( ! is_admin() && function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
												$last_range         = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $last_range );
												$selected_prices[0] = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $selected_prices[0] );
												$price_html        .= wps_mmcsfw_get_custom_currency_symbol( '' ) . ( $selected_prices[0] ) . '-' . wps_mmcsfw_get_custom_currency_symbol( '' ) . ( $last_range ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
											} else {
												$selected_prices[0] = ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) ?
												wc_get_price_including_tax(
													$product,
													array(
														'qty'   => 1,
														'price' => $selected_prices[0],
													)
												) :
												wc_get_price_excluding_tax(
													$product,
													array(
														'qty'   => 1,
														'price' => $selected_prices[0],
													)
												);

												$last_range = ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) ?
												wc_get_price_including_tax(
													$product,
													array(
														'qty'   => 1,
														'price' => $last_range,
													)
												) :
												wc_get_price_excluding_tax(
													$product,
													array(
														'qty'   => 1,
														'price' => $last_range,
													)
												);

												$price_html .= wc_price( $selected_prices[0] ) . '-' . wc_price( $last_range ); /* phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped */
											}
											$price_html .= '</span></ins>';
										}
									}
								}
								if ( 'wps_wgm_user_price' === $product_pricing_type ) {
									$price_html = apply_filters( 'wps_wgm_user_price_text', '' );
								}
								if ( 'wps_wgm_variable_price' === $product_pricing_type ) {
									$wps_variation_price = $product_pricing['wps_wgm_variation_price'];
									$decimal_separator   = get_option( 'woocommerce_price_decimal_sep' );
									foreach ( $wps_variation_price as $key => $value ) {
										$value                       = floatval( str_replace( $decimal_separator, '.', $value ) );
										$wps_variation_price[ $key ] = $value;
									}
									if ( isset( $wps_variation_price ) && ! empty( $wps_variation_price ) && is_array( $wps_variation_price ) ) {
										$start_price = min( $wps_variation_price );
										$end_price   = ( max( $wps_variation_price ) == '' ) ? $start_price : max( $wps_variation_price );
										if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
											if ( wcpbc_the_zone() !== null && wcpbc_the_zone() ) {
												$start_price = wcpbc_the_zone()->get_exchange_rate_price( $start_price );
												$end_price   = wcpbc_the_zone()->get_exchange_rate_price( $end_price );
											}
										}
										$start_price = floatval( str_replace( $decimal_separator, '.', $start_price ) );
										$end_price   = floatval( str_replace( $decimal_separator, '.', $end_price ) );

										$start_price = ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) ?
										wc_get_price_including_tax(
											$product,
											array(
												'qty'   => 1,
												'price' => $start_price,
											)
										) :
										wc_get_price_excluding_tax(
											$product,
											array(
												'qty'   => 1,
												'price' => $start_price,
											)
										);

										$end_price = ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) ?
										wc_get_price_including_tax(
											$product,
											array(
												'qty'   => 1,
												'price' => $end_price,
											)
										) :
										wc_get_price_excluding_tax(
											$product,
											array(
												'qty'   => 1,
												'price' => $end_price,
											)
										);

										$price_html  = '<span>' . wc_price( $start_price ) . ' - ' . wc_price( $end_price ) . '</span>';
									}
								}
								if ( 'wps_wgm_selected_with_price_range' === $product_pricing_type ) {
									$price_html = '';
									$from_price = $product_pricing['from'];
									$to_price = $product_pricing['to'];
									// price based on country.
									if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
										if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
											$from_price = wcpbc_the_zone()->get_exchange_rate_price( $from_price );
											$to_price = wcpbc_the_zone()->get_exchange_rate_price( $to_price );
										}
										$price_html .= '<ins><span class="woocommerce-Price-amount amount">' . wc_price( $from_price ) . ' - ' . wc_price( $to_price ) . '</span></ins>';
									} elseif ( ! is_admin() && function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
										$from_price  = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $from_price );
										$to_price    = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $to_price );
										$price_html .= '<ins><span class="woocommerce-Price-amount amount">' . wps_mmcsfw_get_custom_currency_symbol( '' ) . ( $from_price ) . ' - ' . wps_mmcsfw_get_custom_currency_symbol( '' ) . ( $to_price ) . '</span></ins>';
									} else {
										$decimal_separator   = get_option( 'woocommerce_price_decimal_sep' );
										$from_price = floatval( str_replace( $decimal_separator, '.', $from_price ) );
										$to_price   = floatval( str_replace( $decimal_separator, '.', $to_price ) );
										$price_html .= '<ins><span class="woocommerce-Price-amount amount">' . wc_price( $from_price ) . ' - ' . wc_price( $to_price ) . '</span></ins>';
									}
								}
							}
						}
						$price_html = apply_filters( 'wps_wgm_pricing_html', $price_html, $product, $product_pricing );
					}
				}
			}
		}
		$product_id_to_hide = get_option( 'contributor_product_id' ); // Replace with your actual product ID.

		// Group Gifting contri product price hide ///////.
		if ( $product->get_id() == $product_id_to_hide ) {

			$price = ''; // Empty string will hide the price.
			return $price;
		}
		return $price_html;
	}

	/**
	 * Handles Coupon Generation process on the order Status Changed process.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_order_status_changed()
	 * @param int    $order_id order Id.
	 * @param string $old_status old status.
	 * @param string $new_status new status.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_order_status_changed( $order_id, $old_status, $new_status ) {

		$order = new WC_Order( $order_id );
		$items = $order->get_items();
		$coupon_amount_deducted = wps_wgm_hpos_get_meta_data( $order_id, 'wps_wgm_coupon_amount_deducted', true );
		if ( 'deduct' !== $coupon_amount_deducted ) {
			foreach ( $order->get_items( 'coupon' ) as $item_id => $item ) {
				$this->wps_wgm_woocommerce_new_order_item( $item_id, $item, $order_id );
				wps_wgm_hpos_update_meta_data( $order_id, 'wps_wgm_coupon_amount_deducted', 'deduct' );
			}
		}

		foreach ( $items as $item ) {

			$product_id = $item['product_id'];

			$prod_name  = $item['name'];
			if ( get_option( 'wps_gccoupon_rechargeable_product_id' ) == $product_id ) {
				$this->wps_add_fund_to_existing_coupon( $order_id, $old_status, $new_status );
			}
		}

		$wps_wgm_mail_template_data = array();
		$wps_wgc_enable = wps_wgm_giftcard_enable();
		if ( $wps_wgc_enable ) {
			if ( $old_status != $new_status ) {
				if ( 'completed' == $new_status || 'processing' == $new_status ) {
					$is_gift_card = false;
					$datecheck = true;
					$order = wc_get_order( $order_id );

					foreach ( $order->get_items() as $item_id => $item ) {
						$product = $item->get_product();
						// for contribution product.
						$ord_product_id = $item->get_product_id();
						$conti_prod_id  = get_option( 'contributor_product_id' );
						if ( $ord_product_id == $conti_prod_id ) {
							$alredaygenerated     = wps_wgm_hpos_get_meta_data( $order_id, 'wps_wgm_order_giftcard', true );
							$alreadygenrated_old = wps_wgm_hpos_get_meta_data( $order_id, 'wps_gw_order_giftcard', true );
							if ( 'send' !== $alredaygenerated || 'send' !== $alreadygenrated_old ) {
								$this->wps_wgm_gc_send_payment_link_to_contributors( $order_id, $old_status, $new_status );
							}
						}
						$wps_gift_product = apply_filters( 'wps_wgm_update_item_meta_as_a_gift', $item, $item_id, $order_id );

						if ( ( isset( $product ) && ! empty( $product ) && is_object( $product ) && $product->is_type( 'wgm_gift_card' ) ) || 'on' === $wps_gift_product ) {
							$is_gift_card = true;
						}
					}
					if ( ! $is_gift_card ) {
						return;
					}

					$mailalreadysend     = wps_wgm_hpos_get_meta_data( $order_id, 'wps_wgm_order_giftcard', true );
					$mailalreadysend_old = wps_wgm_hpos_get_meta_data( $order_id, 'wps_gw_order_giftcard', true );
					if ( 'send' == $mailalreadysend || 'send' == $mailalreadysend_old ) {
						return;
					} else {
						$general_setting = get_option( 'wps_wgm_general_settings', array() );
						$giftcard_selected_date = $this->wps_common_fun->wps_wgm_get_template_data( $general_setting, 'wps_wgm_general_setting_enable_selected_date' );
						if ( 'on' == $giftcard_selected_date ) {
							wps_wgm_hpos_update_meta_data( $order_id, 'wps_wgm_order_giftcard', 'notsend' );
						}
					}

					$gift_msg = '';
					$to = '';
					$from = '';
					$gift_order = false;
					$selected_template = '';
					$original_price = 0;
					$variable_price_description = 0;
					$delivery_method = '';
					$order = wc_get_order( $order_id );
					foreach ( $order->get_items() as $item_id => $item ) {
						$mailsend = false;
						$to_name = '';
						$woo_ver = WC()->version;
						$gift_img_name = '';
						$item_quantity = wc_get_order_item_meta( $item_id, '_qty', true );
						$product = $item->get_product();
						$pro_id = $product->get_id();
						$item_meta_data = $item->get_meta_data();
						$gift_date_check = false;
						$gift_date = '';
						$original_price = 0;

						foreach ( $item_meta_data as $key => $value ) {
							if ( isset( $value->key ) && 'To' == $value->key && ! empty( $value->value ) ) {
								$mailsend = true;
								$to = $value->value;
							}
							if ( isset( $value->key ) && 'From' == $value->key && ! empty( $value->value ) ) {
								$mailsend = true;
								$from = $value->value;
							}
							if ( isset( $value->key ) && 'Message' == $value->key && ! empty( $value->value ) ) {
								$mailsend = true;
								$gift_msg = $value->value;
							}
							if ( isset( $value->key ) && 'Delivery Method' == $value->key && ! empty( $value->value ) ) {
								$mailsend = true;
								$delivery_method = $value->value;
							}
							if ( isset( $value->key ) && 'Original Price' == $value->key && ! empty( $value->value ) ) {
								$mailsend = true;
								$original_price = $value->value;
							}
							if ( isset( $value->key ) && 'Selected Template' == $value->key && ! empty( $value->value ) ) {
								$mailsend = true;
								$selected_template = $value->value;
							}
							if ( isset( $value->key ) && 'Variable Price Description' == $value->key && ! empty( $value->value ) ) {
								$mailsend = true;
								$variable_price_description = $value->value;
							}
							if ( isset( $value->key ) && ! empty( $value->value ) ) {
								do_action( 'wps_wgm_add_additional_meta', $key, $value );
							}
							if ( isset( $value->key ) && 'Contributor' == $value->key && ! empty( $value->value ) ) {

								$pro_id = $item->get_id();
								wps_wgm_hpos_update_meta_data( $order_id, 'gifting#order', $order_id );

								$order_subtotal = $order->get_subtotal(); // Subtotal including taxes and discounts.

								$group_gift_amt = intval( $order_subtotal );
								$values = explode( ',', $value->value );

								foreach ( $values  as $value->value ) {
											$conti_prod_id = get_option( 'contributor_product_id' );
											$conti_prod_link = get_permalink( $conti_prod_id );

											$subject = 'Contribute your share';
											$message = "Hello,\n\n";
											$message .= "You are receiving this email to contribute for this  group gift product: : $prod_name.\n";

											$message .= $conti_prod_link . '?order_id=' . $order_id . '&prod_id=' . $pro_id;
											$copy_link = $conti_prod_link . '?order_id=' . $order_id . '&prod_id=' . $pro_id;
											$whatsapp_url = 'https://api.whatsapp.com/send?';
											$whatsapp_url .= 'text=' . urlencode( $copy_link );
											wps_wgm_hpos_update_meta_data( $order_id, 'share#link', $whatsapp_url );
											wps_wgm_hpos_update_meta_data( $order_id, 'copy#link', $copy_link );
											wc_mail( $value->value, $subject, $message );

								}
								wps_wgm_hpos_update_meta_data( $order_id, 'suborder#amttotal', $group_gift_amt );
							}
						}
						$wps_wgm_mail_template_data = array(
							'to' => $to,
							'from' => $from,
							'gift_msg' => $gift_msg,
							'delivery_method' => $delivery_method,
							'original_price' => $original_price,
							'selected_template' => $selected_template,
							'mail_send' => $mailsend,
							'product_id' => $pro_id,
							'item_id'   => $item_id,
							'item_quantity' => $item_quantity,
							'datecheck' => $datecheck,
							'variable_price_description' => $variable_price_description,
						);
						wps_wgm_hpos_update_meta_data( $order_id, 'temp_item_id', $item_id );
						$wps_wgm_mail_template_data = apply_filters( 'wps_wgm_mail_templates_data_set', $wps_wgm_mail_template_data, $order->get_items(), $order_id );

						if ( isset( $wps_wgm_mail_template_data['datecheck'] ) && ! $wps_wgm_mail_template_data['datecheck'] ) {
							continue;
						}
						if ( isset( $wps_wgm_mail_template_data['mail_send'] ) && $wps_wgm_mail_template_data['mail_send'] ) {
							$gift_order = true;
							$inc_tax_status = get_option( 'woocommerce_prices_include_tax', false );
							if ( 'yes' == $inc_tax_status ) {
								$inc_tax_status = true;
							} else {
								$inc_tax_status = false;
							}
							$couponamont = $original_price;

							$wps_wgm_lite = true;
							$wps_wgm_lite = apply_filters( 'wps_wgm_check_coupon_creation_mails', $wps_wgm_mail_template_data, $order_id, $item, $wps_wgm_lite );

							if ( $wps_wgm_lite ) {
								$general_setting = get_option( 'wps_wgm_general_settings', array() );
								$giftcard_coupon_length = $this->wps_common_fun->wps_wgm_get_template_data( $general_setting, 'wps_wgm_general_setting_giftcard_coupon_length' );
								if ( '' == $giftcard_coupon_length ) {
									$giftcard_coupon_length = 5;
								}
								if ( ! empty( $to ) && ( 'Mail to recipient' == $delivery_method ) ) {
									$recipients = preg_split( '/[\s,]+/', $to, -1, PREG_SPLIT_NO_EMPTY );
								}
								for ( $i = 1; $i <= $item_quantity; $i++ ) {
									if ( isset( $recipients ) && ! empty( $recipients ) ) {
										$to = trim( array_shift( $recipients ) );
									}

									$gift_couponnumber = wps_wgm_coupon_generator( $giftcard_coupon_length );

									if ( $this->wps_common_fun->wps_wgm_create_gift_coupon( $gift_couponnumber, $couponamont, $order_id, $item['product_id'], $to ) ) {
										$todaydate = date_i18n( 'Y-m-d' );

																				$local_expiry_day = get_post_meta( $pro_id, 'wps_wgm_local_setting_giftcard_expiry', true );
										if ( empty( $local_expiry_day ) || 0 == $local_expiry_day ) {
											$expiry_date = $this->wps_common_fun->wps_wgm_get_template_data( $general_setting, 'wps_wgm_general_setting_giftcard_expiry' );
										} else {
											$expiry_date = $local_expiry_day;
										}

										$expirydate_format = $this->wps_common_fun->wps_wgm_check_expiry_date( $expiry_date );
										$wps_wgm_common_arr['order_id'] = $order_id;
										$wps_wgm_common_arr['product_id'] = $item['product_id'];
										$wps_wgm_common_arr['to'] = $to;
										$wps_wgm_common_arr['from'] = $from;
										$wps_wgm_common_arr['gift_couponnumber'] = $gift_couponnumber;
										$wps_wgm_common_arr['gift_msg'] = $gift_msg;
										$wps_wgm_common_arr['expirydate_format'] = $expirydate_format;
										$wps_wgm_common_arr['couponamont'] = $couponamont;
										$wps_wgm_common_arr['selected_template'] = isset( $selected_template ) ? $selected_template : '';
										$wps_wgm_common_arr['delivery_method'] = $delivery_method;
										$wps_wgm_common_arr['variable_price_description'] = $variable_price_description;
										$wps_wgm_common_arr['item_id'] = $item_id;
										$wps_wgm_common_arr = apply_filters( 'wps_wgm_common_arr_data', $wps_wgm_common_arr, $item, $order );

										$wps_wgm_coupon_code = $this->wps_common_fun->wps_wgm_common_functionality( $wps_wgm_common_arr, $order );
									}
								}
							}
						}
					}

					if ( $gift_order && isset( $wps_wgm_mail_template_data['datecheck'] ) && $wps_wgm_mail_template_data['datecheck'] ) {
						wps_wgm_hpos_update_meta_data( $order_id, 'wps_wgm_order_giftcard', 'send' );
					}
					do_action( 'wps_wgm_action_on_order_status_changed', $order_id, $old_status, $new_status, $wps_wgm_mail_template_data );
				}
			}
		}

	}
	/**
	 * Payment link to contributors.
	 *
	 * @param int    $order_id order_id .
	 * @param string $old_status $old_status.
	 * @param string $new_status $new_status.
	 */
	public function wps_wgm_gc_send_payment_link_to_contributors( $order_id, $old_status, $new_status ) {

		$order = wc_get_order( $order_id );

		foreach ( $order->get_items() as $items => $item_key ) {

			foreach ( $item_key->get_meta_data() as $item_id => $item ) {

				if ( 'Main Product id' == $item->key ) {
					$main_prod_id  = $item->value;
				}
				if ( 'Main order id' == $item->key ) {
					$main_ord_id  = $item->value;
					$m_key = 'suborder#' . $main_ord_id;

					wps_wgm_hpos_update_meta_data( $order_id, $m_key, $main_ord_id );
					//
					// Get the order object.
					$current_ord = wc_get_order( $main_ord_id );
					$order = wc_get_order();

					$meta_key = 'suborder#' . $main_ord_id;
					if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
						// HPOS Enabled.
						$orders = wc_get_orders(
							array(
								'limit'       => -1,
								'status'      => array_keys( wc_get_order_statuses() ),
								'meta_key'    => $meta_key, // Replace with your custom field key.
								'type'        => wc_get_order_types(),
							)
						);
					} else {
						$args = array(
							'post_type'      => 'shop_order',
							'post_status'    => 'any',
							'posts_per_page' => -1,
							'meta_key'       => $meta_key, // Replace with your custom field key.
						);
						$orders = get_posts( $args );
					}
					$main_pord_ord = wc_get_order( $main_ord_id );
					$main_prod_amt = intval( $main_pord_ord->get_subtotal() );

					$total_suborder_amout = 0;
					 // Loop through the orders and display relevant data.

					foreach ( $orders as $order ) {
						if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
							$order_sub_id = $order->get_id();
						} else {
							$order_sub_id = $order->ID;
						}
						$ord = wc_get_order( $order_sub_id );
						$product_amount = $ord->get_subtotal();
						if ( $product_amount ) {

							$total_suborder_amout = $total_suborder_amout + $product_amount;
						}
					}
					wps_wgm_hpos_update_meta_data( $main_ord_id, 'suborder#amttotal', intval( $total_suborder_amout ) + intval( $main_prod_amt ) );

				}
			}
		}

	}

	/**
	 * Adding fund to copon .
	 *
	 * @param int    $order_id order Id.
	 * @param string $old_status old status.
	 * @param string $new_status new status.
	 */
	public function wps_add_fund_to_existing_coupon( $order_id, $old_status, $new_status ) {

		if ( $old_status != $new_status ) {

			if ( 'completed' == $new_status || 'processing' == $new_status ) {
				$order = wc_get_order( $order_id );

				foreach ( $order->get_items() as $item => $value ) {
					foreach ( $value->get_meta_data() as $key => $data ) {
						$coupon_code = $data->value;

						// Get the coupon object.
						$coupon = new WC_Coupon( $coupon_code );

						// Check if the coupon exists.
						if ( $coupon->is_valid() ) {

							$total = $order->get_subtotal() + $coupon->get_amount();
							$coupon->set_amount( $total );
							// Save the changes.
							$coupon->save();
						} else {
							/* translators: %s: coupon_code */
							echo esc_html( sprintf( __( 'Coupon %s is not available.', 'woo-gift-cards-lite' ), $coupon_code ) );
						}
					}
				}
			}
		}
	}
	/**
	 * Hide coupon feilds from cart page if only giftcard products are there
	 *
	 * @since 1.0.0
	 * @name wps_wgm_hidding_coupon_field_on_cart
	 * @param bool $enabled boolean return.
	 * @return $enabled.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_hidding_coupon_field_on_cart( $enabled ) {
		$bool = false;
		$bool2 = false;
		$is_checkout = false;
		if ( is_cart() || is_checkout() ) {
			if ( ! empty( WC()->cart ) ) {
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product = wc_get_product( $cart_item['product_id'] );
					if ( $_product->is_type( 'wgm_gift_card' ) ) {
						$bool = true;
					} else {
						$bool2 = true;
					}
				}
			}
		}
		if ( $bool && is_cart() && ! $bool2 ) {
			$enabled = false;
		} elseif ( ! $bool && $bool2 && is_cart() ) {
			$enabled = true;
		} elseif ( $bool && $bool2 ) {
			$enabled = true;
		} elseif ( $bool && is_checkout() && ! $bool2 ) {
			$enabled = false;
		} elseif ( ! $bool && $bool2 && is_checkout() ) {
			$enabled = true;
		}
		return $enabled;
	}

	/**
	 * Hide order meta fields from thankyou page and email template.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_hide_order_metafields()
	 * @param array $formatted_meta formatted_meta.
	 * @return $temp_metas.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_hide_order_metafields( $formatted_meta ) {
		$temp_metas = array();
		if ( isset( $formatted_meta ) && ! empty( $formatted_meta ) && is_array( $formatted_meta ) ) {
			foreach ( $formatted_meta as $key => $meta ) {
				if ( isset( $meta->key ) && ! in_array( $meta->key, array( 'Original Price', 'Selected Template', 'Variable Price Description' ) ) ) {

					$temp_metas[ $key ] = $meta;
				}
			}
			$temp_metas = apply_filters( 'wps_wgm_hide_order_metafields', $temp_metas, $formatted_meta );
			return $temp_metas;
		} else {
			return $formatted_meta;
		}
	}

	/**
	 * Adds the Order-item-meta inside the each gift card orders.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_checkout_create_order_line_item()
	 * @param object $item item object.
	 * @param string $cart_key cart key.
	 * @param array  $values cart values.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_checkout_create_order_line_item( $item, $cart_key, $values ) {

		$wps_wgc_enable = wps_wgm_giftcard_enable();

		if ( $wps_wgc_enable ) {
			if ( isset( $values ['product_meta'] ) ) {
				foreach ( $values ['product_meta'] ['meta_data'] as $key => $val ) {
					$order_val = stripslashes( $val );

					if ( $val ) {
						if ( 'wps_wgm_to_email' == $key ) {
							$item->add_meta_data( 'To', $order_val );
						}
						if ( 'wps_wgm_from_name' == $key ) {
							$item->add_meta_data( 'From', $order_val );
						}
						if ( 'wps_wgm_message' == $key ) {
							$item->add_meta_data( 'Message', $order_val );
						}
						if ( 'wps_wgm_price' == $key ) {
							$item->add_meta_data( 'Original Price', $order_val );
						}
						if ( 'delivery_method' == $key ) {
							$item->add_meta_data( 'Delivery Method', $order_val );
						}
						if ( 'wps_wgm_selected_temp' == $key ) {
							$item->add_meta_data( 'Selected Template', $order_val );
						}
						if ( 'wps_wgm_variable_price_description' == $key ) {
							$item->add_meta_data( 'Variable Price Description', $order_val );
						}
						if ( 'recharge_coupon_key_field' == $key ) {
							$item->add_meta_data( 'Coupon code', $order_val );
						}
						if ( 'wps_main_order_id' == $key ) {
							$item->add_meta_data( 'Main order id', $order_val );
						}
						if ( 'wps_main_prod_id' == $key ) {
							$item->add_meta_data( 'Main Product id', $order_val );
						}
						do_action( 'wps_wgm_checkout_create_order_line_item', $item, $key, $order_val );
					}
				}
			}
		}
	}

	/**
	 * Removes Add to cart button and Adds View Card Button.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_loop_add_to_cart_link()
	 * @param string $link add link.
	 * @param object $product product.
	 * @return $link.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_loop_add_to_cart_link( $link, $product ) {
		$wps_wgc_enable = wps_wgm_giftcard_enable();
		if ( $wps_wgc_enable ) {
			$product_id = $product->get_id();
			if ( isset( $product_id ) ) {
				$product_types = wp_get_object_terms( $product_id, 'product_type' );
				if ( isset( $product_types[0] ) ) {
					$product_type = $product_types[0]->slug;
					if ( 'wgm_gift_card' == $product_type ) {
						$product_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
						if ( isset( $product_pricing ) && ! empty( $product_pricing ) ) {
							$link = sprintf(
								'<a rel="nofollow" href="%s" class="%s">%s</a>',
								esc_url( get_the_permalink() ),
								esc_attr( isset( $class ) ? $class : 'button wps_gc_button' ),
								esc_html( apply_filters( 'wps_wgm_view_card_text', __( 'VIEW CARD', 'woo-gift-cards-lite' ) ) )
							);
						}
						$link = apply_filters( 'wps_wgm_loop_add_to_cart_link', $link, $product );
					}
				}
			}
		}
		return $link;
	}

	/**
	 * Enable the Taxes for Gift Card if the required setting is enabled
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_product_is_taxable()
	 * @param bool   $taxable taxable.
	 * @param object $product product.
	 * @return $taxable.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_product_is_taxable( $taxable, $product ) {
		$wps_wgc_enable = wps_wgm_giftcard_enable();
		if ( $wps_wgc_enable ) {
			$genaral_settings = get_option( 'wps_wgm_general_settings', array() );
			$giftcard_tax_cal_enable = $this->wps_common_fun->wps_wgm_get_template_data( $genaral_settings, 'wps_wgm_general_setting_tax_cal_enable' );
			if ( ! isset( $giftcard_tax_cal_enable ) || empty( $giftcard_tax_cal_enable ) ) {
				$giftcard_tax_cal_enable = 'off';
			}
			if ( 'off' == $giftcard_tax_cal_enable ) {
				$product_id = $product->get_id();
				$product_types = wp_get_object_terms( $product_id, 'product_type' );
				if ( isset( $product_types[0] ) ) {
					$product_type = $product_types[0]->slug;
					if ( 'wgm_gift_card' == $product_type ) {
						$taxable = false;
					}
				}
			}
		}
		return $taxable;
	}

	/**
	 * Set the error notice div on single product page
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_before_main_content()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_before_main_content() {
		global $post;
		if ( isset( $post->ID ) ) {
			$product_id = $post->ID;
			$product_types = wp_get_object_terms( $product_id, 'product_type' );
			$sell_as_a_giftcard = get_post_meta( $product_id, '_sell_as_a_giftcard' );
			if ( isset( $product_types[0] ) ) {
				$product_type = $product_types[0]->slug;
				if ( 'wgm_gift_card' === $product_type || isset( $sell_as_a_giftcard[0] ) && 'yes' === $sell_as_a_giftcard[0] ) {
					?>
					<div class="woocommerce-error" id="wps_wgm_error_notice" style="display:none;"></div>
					<?php
				}
			}
		}
	}

	/**
	 * Show/Hide Gift Card product from shop page depending upon the required setting.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_product_query()
	 * @param object $query query.
	 * @param object $query_object query object.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_product_query( $query, $query_object ) {
		$wps_wgc_enable = wps_wgm_giftcard_enable();
		if ( $wps_wgc_enable ) {
			$genaral_settings = get_option( 'wps_wgm_general_settings', array() );
			$giftcard_shop_page = $this->wps_common_fun->wps_wgm_get_template_data( $genaral_settings, 'wps_wgm_general_setting_shop_page_enable' );
			if ( ! isset( $giftcard_shop_page ) || empty( $giftcard_shop_page ) ) {
				$giftcard_shop_page = 'off';
			}
			if ( 'on' != $giftcard_shop_page ) {
				if ( is_shop() ) {
					$args = array(
						'post_type' => 'product',
						'posts_per_page' => -1,
						'meta_key' => 'wps_wgm_pricing',
					);
					$gift_products = array();
					$loop = new WP_Query( $args );
					if ( $loop->have_posts() ) :
						while ( $loop->have_posts() ) :
							$loop->the_post();
							global $product;
							$product_id = $loop->post->ID;
							$product_types = wp_get_object_terms( $product_id, 'product_type' );
							if ( isset( $product_types[0] ) ) {
								$product_type = $product_types[0]->slug;
								if ( 'wgm_gift_card' == $product_type ) {
									$gift_products[] = $product_id;
								}
							}
						endwhile;
					endif;
					$query->set( 'post__not_in', $gift_products );
				}
			}
		}
	}

	/**
	 * Adjust the Gift Card Amount, when it has been applied to any product for getting discount
	 *
	 * @since 1.0.0
	 * @name wps_wgm_woocommerce_new_order_item()
	 * @param  int    $item_id item_id.
	 * @param  object $item item.
	 * @param  int    $order_id order_id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_woocommerce_new_order_item( $item_id, $item, $order_id ) {

		if ( get_class( $item ) == 'WC_Order_Item_Coupon' ) {

			$coupon_code = $item->get_code();
			$the_coupon = new WC_Coupon( $coupon_code );
			$coupon_id = $the_coupon->get_id();
			if ( isset( $coupon_id ) ) {
				$rate = 1;
				// price based on country.
				if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {

					if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {

						$rate = wcpbc_the_zone()->get_exchange_rate();
					}
				}
				$giftcardcoupon = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true );
				if ( ! empty( $giftcardcoupon ) ) {

					if ( apply_filters( 'wps_wgm_subscription_renewal_order_coupon', false, $order_id, $the_coupon ) ) {
						return;
					}
					$wps_wgm_discount     = $item->get_discount();
					$wps_wgm_discount_tax = $item->get_discount_tax();
					$amount               = get_post_meta( $coupon_id, 'coupon_amount', true );
					$decimal_separator    = get_option( 'woocommerce_price_decimal_sep' );
					$amount               = floatval( str_replace( $decimal_separator, '.', $amount ) );

					$total_discount = $this->wps_common_fun->wps_wgm_calculate_coupon_discount( $wps_wgm_discount, $wps_wgm_discount_tax );
					$total_discount = $total_discount / $rate;

					if ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_to_base_currency' ) ) {
						$from_currency  = wps_wgm_hpos_get_meta_data( $order_id, '_order_currency', true );
						$total_discount = wps_mmcsfw_admin_fetch_currency_rates_to_base_currency( $from_currency, $total_discount );
					}

					if ( $amount < $total_discount ) {
						$remaining_amount = 0;
					} else {
						$remaining_amount = $amount - $total_discount;
						$remaining_amount = round( $remaining_amount, 2 );
					}
					update_post_meta( $coupon_id, 'coupon_amount', $remaining_amount );
					do_action( 'wps_wgm_send_mail_remaining_amount', $coupon_id, $remaining_amount );
				} else {
					do_action( 'wps_wgm_offline_giftcard_coupon', $coupon_id, $item );
				}
				do_action( 'wps_wgm_coupon_reporting_with_order', $coupon_id, $item, $total_discount, $remaining_amount );
			}
		}
	}

	/**
	 * Disable the Shipping fee if there is only Gift Card Product
	 *
	 * @since 1.0.0
	 * @name wps_wgm_wc_shipping_enabled()
	 * @param bool $enable enable.
	 * @return $enable.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_wc_shipping_enabled( $enable ) {
		$wps_wgc_enable = wps_wgm_giftcard_enable();

		if ( CartCheckoutUtils::is_cart_block_default() || CartCheckoutUtils::is_checkout_block_default() ) {
			if ( ( true ) && $wps_wgc_enable ) {
				global $woocommerce;
				$gift_bool = false;
				$other_bool = false;
				$gift_bool_ship = false;
				if ( isset( WC()->cart ) && ! empty( WC()->cart ) ) {

					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

						$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
						$product_types = wp_get_object_terms( $product_id, 'product_type' );
						if ( isset( $product_types[0] ) ) {
							$product_type = $product_types[0]->slug;
							if ( isset( $cart_item['product_meta'] ) ) {
								if ( 'wgm_gift_card' == $product_type || ( isset( $cart_item['product_meta']['meta_data']['sell_as_a_gc'] ) && 'on' === $cart_item['product_meta']['meta_data']['sell_as_a_gc'] ) ) {
									if ( 'Mail to recipient' == $cart_item['product_meta']['meta_data']['delivery_method'] || 'Downloadable' == $cart_item['product_meta']['meta_data']['delivery_method'] ) {
										$gift_bool = true;
									} elseif ( 'shipping' == $cart_item['product_meta']['meta_data']['delivery_method'] ) {
										$gift_bool_ship = true;
									}
								} else if ( ! $cart_item['data']->is_virtual() ) {
									$other_bool = true;
								}
							} else if ( ! $cart_item['data']->is_virtual() ) {
								$other_bool = true;
							}
						}
					}

					if ( $gift_bool && ! $gift_bool_ship && ! $other_bool ) {
						$enable = false;
					} else {
						$enable = true;
					}
				}
			}
		} else {
			if ( ( is_cart() || is_checkout() ) && $wps_wgc_enable ) {
				global $woocommerce;
				$gift_bool = false;
				$other_bool = false;
				$gift_bool_ship = false;
				if ( isset( WC()->cart ) && ! empty( WC()->cart ) ) {
					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
						$product_types = wp_get_object_terms( $product_id, 'product_type' );
						if ( isset( $product_types[0] ) ) {
							$product_type = $product_types[0]->slug;
							if ( isset( $cart_item['product_meta'] ) ) {
								if ( 'wgm_gift_card' == $product_type || ( isset( $cart_item['product_meta']['meta_data']['sell_as_a_gc'] ) && 'on' === $cart_item['product_meta']['meta_data']['sell_as_a_gc'] ) ) {
									if ( 'Mail to recipient' == $cart_item['product_meta']['meta_data']['delivery_method'] || 'Downloadable' == $cart_item['product_meta']['meta_data']['delivery_method'] ) {
										$gift_bool = true;
									} elseif ( 'shipping' == $cart_item['product_meta']['meta_data']['delivery_method'] ) {
										$gift_bool_ship = true;
									}
								} else if ( ! $cart_item['data']->is_virtual() ) {
									$other_bool = true;
								}
							} else if ( ! $cart_item['data']->is_virtual() ) {
								$other_bool = true;
							}
						}
					}
					if ( $gift_bool && ! $gift_bool_ship && ! $other_bool ) {
						$enable = false;
					} else {
						$enable = true;
					}
				}
			}
		}
		return $enable;
	}

	/**
	 * Create the Thickbox Query
	 *
	 * @since 1.0.0
	 * @name wps_wgm_preview_thickbox_rqst
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_preview_thickbox_rqst() {
		check_ajax_referer( 'wps-wgc-verify-nonce', 'wps_nonce' );
		$_POST['wps_wgc_preview_email'] = 'wps_wgm_single_page_popup';
		$_POST['tempId'] = isset( $_POST['tempId'] ) ? stripcslashes( sanitize_text_field( wp_unslash( $_POST['tempId'] ) ) ) : '';
		$_POST = apply_filters( 'wps_wgm_upload_giftcard_image', $_POST );
		$_POST['message'] = isset( $_POST['message'] ) ? stripcslashes( sanitize_text_field( wp_unslash( $_POST['message'] ) ) ) : '';
		$_POST['width'] = '630';
		$_POST['height'] = '530';
		$_POST['TB_iframe'] = true;
		$query = http_build_query( wp_unslash( $_POST ) );
		$ajax_url = home_url( "?$query" );
		echo esc_attr( $ajax_url );
		wp_die();
	}
	/**
	 * Show the Preview Email Template for SIngle Product Page inside the thickbox
	 *
	 * @since 1.0.0
	 * @name wps_wgc_preview_email
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_preview_email_on_single_page() {
		$secure_nonce      = wp_create_nonce( 'wps-gc-auth-nonce' );
		$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-gc-auth-nonce' );
		if ( ! $id_nonce_verified ) {
				wp_die( esc_html__( 'Nonce Not verified', 'woo-gift-cards-lite' ) );
		}
		if ( isset( $_GET['wps_wgc_preview_email'] ) && 'wps_wgm_single_page_popup' == $_GET['wps_wgc_preview_email'] ) {

			$product_id                     = isset( $_GET['product_id'] ) ? sanitize_text_field( wp_unslash( $_GET['product_id'] ) ) : '';
			$product_pricing                = ! empty( get_post_meta( $product_id, 'wps_wgm_pricing', true ) ) ? get_post_meta( $product_id, 'wps_wgm_pricing', true ) : get_post_meta( $product_id, 'wps_wgm_pricing_details', true );
			$product_pricing_type           = $product_pricing['type'];
			$general_setting                = get_option( 'wps_wgm_general_settings', array() );
			$giftcard_coupon_length_display = $this->wps_common_fun->wps_wgm_get_template_data( $general_setting, 'wps_wgm_general_setting_giftcard_coupon_length' );
			if ( '' == $giftcard_coupon_length_display ) {
				$giftcard_coupon_length_display = 5;
			}
			$password = '';
			for ( $i = 0;$i < $giftcard_coupon_length_display;$i++ ) {
				$password .= 'x';
			}
			$giftcard_prefix = $general_setting['wps_wgm_general_setting_giftcard_prefix'];
			$coupon = $giftcard_prefix . $password;

			$local_expiry_day = get_post_meta( $product_id, 'wps_wgm_local_setting_giftcard_expiry', true );
			if ( empty( $local_expiry_day ) || 0 == $local_expiry_day ) {
				$expiry_date = $general_setting['wps_wgm_general_setting_giftcard_expiry'];
			} else {
				$expiry_date = $local_expiry_day;
			}

			$is_imported_product = get_post_meta( $product_id, 'is_imported', true );
			if ( isset( $is_imported_product ) && 'yes' === $is_imported_product ) {
				$expiry_date = get_post_meta( $product_id, 'expiry_after_days', true );
			}

			$expirydate_format = $this->wps_common_fun->wps_wgm_check_expiry_date( $expiry_date );
			$wps_temp_id = isset( $_GET['tempId'] ) ? sanitize_text_field( wp_unslash( $_GET['tempId'] ) ) : '';
			if ( array_key_exists( 'template', $product_pricing ) ) {
				$templateid = $product_pricing['template'];
			} else {
				$templateid = $this->wps_common_fun->wps_get_org_selected_template();
			}
			if ( is_array( $templateid ) && array_key_exists( 0, $templateid ) ) {
				$temp = $templateid[0];
			} else {
				$temp = $templateid;
			}

			if ( isset( $_GET['delivery_method'] ) ) {
				if ( 'Mail to recipient' == $_GET['delivery_method'] ) {
					$h = __( 'Mail to recipient', 'woo-gift-cards-lite' );
				} else if ( 'Downloadable' == $_GET['delivery_method'] ) {
					$h = __( 'Downloadable', 'woo-gift-cards-lite' );
				} else if ( 'shipping' == $_GET['delivery_method'] ) {
					$h = __( 'shipping', 'woo-gift-cards-lite' );

				} else {
					$h = sanitize_text_field( wp_unslash( $_GET['delivery_method'] ) );

				}
			}

			$args['to'] = isset( $_GET['to'] ) ? sanitize_text_field( wp_unslash( $_GET['to'] ) ) : '';
			$args['from'] = isset( $_GET['from'] ) ? sanitize_text_field( wp_unslash( $_GET['from'] ) ) : '';
			$args['message'] = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '';
			$args['coupon'] = apply_filters( 'wps_wgm_qrcode_coupon', $coupon );
			$args['expirydate'] = $expirydate_format;
			$args['delivery_method'] = isset( $_GET['delivery_method'] ) ? sanitize_text_field( wp_unslash( $h ) ) : '';
			$args['variable_price_description'] = isset( $_GET['variable_price_desc'] ) ? sanitize_text_field( wp_unslash( $_GET['variable_price_desc'] ) ) : '';
			$args = apply_filters( 'wps_wgm_add_preview_template_fields', $args );

			if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {  // Added for price based on country.
				if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
					if ( isset( $product_pricing_type ) && 'wps_wgm_range_price' == $product_pricing_type ) {
						$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
					} elseif ( isset( $product_pricing_type ) && 'wps_wgm_user_price' == $product_pricing_type ) {
						$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
					} else {
						$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
						$amt = wcpbc_the_zone()->get_exchange_rate_price( $amt );
					}
					$args['amount'] = wc_price( $amt );
				} else {
					$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
					$args['amount'] = wc_price( $amt );
				}
			} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
				if ( isset( $product_pricing_type ) && 'wps_wgm_range_price' == $product_pricing_type ) {
					$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
				} elseif ( isset( $product_pricing_type ) && 'wps_wgm_user_price' == $product_pricing_type ) {
					$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
				} else {
					$amt = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
					$amt = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $amt );
				}
				$args['amount'] = wps_mmcsfw_get_custom_currency_symbol( '' ) . ( $amt );
			} else {
				$amt               = isset( $_GET['price'] ) ? sanitize_text_field( wp_unslash( $_GET['price'] ) ) : '';
				$decimal_separator = get_option( 'woocommerce_price_decimal_sep' );
				$amt               = floatval( str_replace( $decimal_separator, '.', $amt ) );
				$args['amount']    = wc_price( $amt );
			}
			$args['templateid'] = isset( $wps_temp_id ) && ! empty( $wps_temp_id ) ? $wps_temp_id : $temp;
			$args['product_id'] = $product_id;
			$message = $this->wps_common_fun->wps_wgm_create_gift_template( $args );
			if ( wps_uwgc_pro_active() ) {
					do_action( 'preview_email_template_for_pro', $message );
			} else {
				$allowed_tags = $this->wps_common_fun->wps_allowed_html_tags();
				// @codingStandardsIgnoreStart.
				echo wp_kses( $message, $allowed_tags );
				die();
				// @codingStandardsIgnoreEnd.
			}
		}

	}

	/**
	 * Need to remove hold coupon time.
	 *
	 * @since 1.0.0
	 * @name wps_wgm_apply_already_created_giftcard_coupons
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_wgm_apply_already_created_giftcard_coupons() {
		return false;
	}

	/**
	 * Compatible with flatsome minicart price issue
	 *
	 * @since 2.0.6
	 * @param string $html html.
	 * @param string $cart_item cart_item.
	 * @param string $cart_item_key cart_item_key.
	 * @name wps_mini_cart_product_price
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.wpswings.com/
	 */
	public function wps_mini_cart_product_price( $html, $cart_item, $cart_item_key ) {
		if ( isset( $cart_item['product_meta']['meta_data']['wps_wgm_price'] ) && ! empty( $cart_item['product_meta']['meta_data']['wps_wgm_price'] ) ) {
			$product_price = $cart_item['product_meta']['meta_data']['wps_wgm_price'];
			$html          = apply_filters( 'wps_wgm_updated_minicart_price', $product_price, $cart_item, $cart_item_key );
			if ( ! wps_uwgc_pro_active() ) {
				if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
					if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
						$html = wcpbc_the_zone()->get_exchange_rate_price( $html );
					}
				} elseif ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_from_base_currency' ) ) {
					$html = wps_mmcsfw_admin_fetch_currency_rates_from_base_currency( '', $html );
				}
				$html = str_replace( ',', '.', $html );
				$html = wc_price( $html );
			}
		}
		return $html;
	}

	/**
	 * Comapatibilty with currency switcher.
	 *
	 * @param string $custom_product_type custom_product_type.
	 * @param int    $product_id id.
	 * @return $product_type
	 */
	public function wps_currency_switcher_get_custom_product_type( $custom_product_type, $product_id ) {
		$product_pricing = get_post_meta( $product_id, 'wps_wgm_pricing', true );
		if ( ! empty( $product_pricing ) ) {
			$product_pricing_type = $product_pricing['type'];
			if ( ! empty( $product_pricing_type ) ) {
				if ( 'wps_wgm_default_price' === $product_pricing_type ) {
					return 'simple';
				} else {
					return 'variable';
				}
			} else {
				return '';
			}
		}
	}

	/**
	 * This function is used to manage coupon amount when order status will be cancelled or failed.
	 *
	 * @param int    $order_id id.
	 * @param string $old_status old status.
	 * @param string $new_status new status.
	 * @return void
	 */
	public function wps_wgm_manage_coupon_amount_on_refund( $order_id, $old_status, $new_status ) {
		$order       = new WC_Order( $order_id );
		$coupon_code = $order->get_coupon_codes();

		if ( ! empty( $coupon_code ) ) {
			$the_coupon = new WC_Coupon( $coupon_code[0] );
			$coupon_id  = $the_coupon->get_id();
			$orderid    = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true );
			if ( isset( $orderid ) && ! empty( $orderid ) ) {
				if ( apply_filters( 'wps_wgm_subscription_renewal_order_coupon', false, $order_id, $the_coupon ) ) {
					return;
				}
				if ( ! wps_wgm_hpos_get_meta_data( $order_id, 'coupon_used', true ) ) {
					$coupon_used = 'yes';
					wps_wgm_hpos_update_meta_data( $order_id, 'coupon_used', $coupon_used );

				} else {
					$coupon_used = wps_wgm_hpos_get_meta_data( $order_id, 'coupon_used', true );
				}

				if ( ( 'cancelled' == $new_status || 'failed' == $new_status ) && 'yes' == $coupon_used ) {

					$amount         = get_post_meta( $coupon_id, 'coupon_amount', true );
					$total_discount = $order->get_discount_total();
					if ( wc_prices_include_tax() ) {
						$total_discount = $total_discount + $order->get_discount_tax();
					}

					if ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_to_base_currency' ) ) {
						$from_currency  = wps_wgm_hpos_get_meta_data( $order_id, '_order_currency', true );
						$total_discount = wps_mmcsfw_admin_fetch_currency_rates_to_base_currency( $from_currency, $total_discount );
					}

					$remaining_amount = $amount + $total_discount;
					$remaining_amount = round( $remaining_amount, 2 );
					update_post_meta( $coupon_id, 'coupon_amount', $remaining_amount );
					$coupon_used = 'no';
					wps_wgm_hpos_update_meta_data( $order_id, 'coupon_used', $coupon_used );

				} elseif ( ( 'pending' == $new_status || 'processing' == $new_status || 'on-hold' == $new_status || 'completed' == $new_status ) && 'no' == $coupon_used ) {
					$amount         = get_post_meta( $coupon_id, 'coupon_amount', true );
					$total_discount = $order->get_discount_total();
					if ( wc_prices_include_tax() ) {
						$total_discount = $total_discount + $order->get_discount_tax();
					}

					if ( function_exists( 'wps_mmcsfw_admin_fetch_currency_rates_to_base_currency' ) ) {
						$from_currency  = wps_wgm_hpos_get_meta_data( $order_id, '_order_currency', true );
						$total_discount = wps_mmcsfw_admin_fetch_currency_rates_to_base_currency( $from_currency, $total_discount );
					}

					if ( $amount < $total_discount ) {
						$remaining_amount = 0;
					} else {
						$remaining_amount = $amount - $total_discount;
						$remaining_amount = round( $remaining_amount, 2 );
					}
					update_post_meta( $coupon_id, 'coupon_amount', $remaining_amount );
					$coupon_used = 'yes';
					wps_wgm_hpos_update_meta_data( $order_id, 'coupon_used', $coupon_used );
				}
			}
		}
	}

	/**
	 * Add endpoint for wallet plugin.
	 *
	 * @return void
	 */
	public function wps_wgm_add_wallet_register_endpoint() {
		add_rewrite_endpoint( 'wallet-giftcard', EP_PERMALINK | EP_PAGES );
	}

	/**
	 * Add Tab in wallet plugin for recharge wallet by coupon.
	 *
	 * @param array $wallet_tabs wallet tabs.
	 * @return array $wallet_tabs
	 */
	public function wps_wgm_add_wallet_tabs( $wallet_tabs ) {
		$giftcard_url                   = wc_get_endpoint_url( 'wps-wallet', 'wallet-giftcard' );
		$wallet_tabs['wallet_giftcard'] = array(
			'title'     => esc_html__( 'Recharge via Gift Card', 'woo-gift-cards-lite' ),
			'url'       => $giftcard_url,
			'icon'      => '<svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
			<rect x="3" y="8" width="28" height="21" rx="1.5" stroke="#1D201F" stroke-width="2.5" stroke-linejoin="round"/>
			<circle cx="14.1304" cy="5" r="3" stroke="#1D201F" stroke-width="2.5"/>
			<path d="M23.913 5C23.913 6.65685 22.5699 8 20.913 8C19.2562 8 17.913 6.65685 17.913 5C17.913 3.34315 19.2562 2 20.913 2C22.5699 2 23.913 3.34315 23.913 5Z" stroke="#1D201F" stroke-width="2.5"/>
			<path d="M17.913 6.52173L10.913 13.5217" stroke="#1D201F" stroke-width="2.5" stroke-linecap="round"/>
			<path d="M16.7826 6.52173L23.7826 13.5217" stroke="#1D201F" stroke-width="2.5" stroke-linecap="round"/>
			<path d="M1.99994 23.3478H30.826" stroke="#1D201F" stroke-width="2.5"/>
			</svg>',
			'file-path' => plugin_dir_path( __FILE__ ) . 'partials/wallet-system-for-woocommerce-wallet-giftcard.php',
		);
		return $wallet_tabs;
	}

	/**
	 * Recharge wallet via giftcard.
	 *
	 * @return void.
	 */
	public function wps_recharge_wallet_via_giftcard() {
		check_ajax_referer( 'wps-wgc-verify-nonce', 'wps_wgm_nonce' );
		$wps_giftcard_code      = ( ! empty( sanitize_text_field( wp_unslash( $_POST['wps_gc_code'] ) ) ) ? sanitize_text_field( wp_unslash( $_POST['wps_gc_code'] ) ) : '' );
		$coupon                 = new WC_Coupon( $wps_giftcard_code );
		$coupon_id              = $coupon->get_id();
		$wallet_payment_gateway = new Wallet_System_For_Woocommerce();

		if ( $coupon->get_id() !== 0 ) {
			$coupon_amount = $coupon->get_amount();
			$expiry_date_timestamp = $coupon->get_date_expires();

			if ( empty( $expiry_date_timestamp ) ) {
				$expirydiff = 1;
			} else {
				$expiry_date_timestamp = strtotime( $expiry_date_timestamp );
				$timestamp = strtotime( gmdate( 'Y-m-d' ) );
				$expirydiff = $expiry_date_timestamp - $timestamp;
			}

			if ( ! empty( $coupon_amount ) && 0 < $expirydiff ) {
				$coupon->set_amount( 0 );
				$user_id = get_current_user_id();
				$coupon->save();

				$walletamount  = get_user_meta( $user_id, 'wps_wallet', true );
				$walletamount  = empty( $walletamount ) ? 0 : $walletamount;
				$walletamount += $coupon_amount;
				update_user_meta( $user_id, 'wps_wallet', $walletamount );
				if ( '' !== $coupon_id && 0 !== $coupon_id ) {

					$coupon_usage_count = get_post_meta( $coupon_id, 'usage_count', true );
					$coupon_usage_count = ++$coupon_usage_count;
					update_post_meta( $coupon_id, 'usage_count', $coupon_usage_count );
				}
								$wallet_user       = get_user_by( 'id', $user_id );
				$send_email_enable = get_option( 'wps_wsfw_enable_email_notification_for_wallet_update', '' );

				if ( isset( $send_email_enable ) && 'on' === $send_email_enable ) {
					$user_name  = $wallet_user->first_name . ' ' . $wallet_user->last_name;
					$mail_text  = sprintf( 'Hello %s,<br/>', $user_name );
					$mail_text .= __( 'Wallet credited by ', 'woo-gift-cards-lite' ) . wc_price( $coupon_amount, array( 'currency' => get_woocommerce_currency() ) ) . __( ' through Gift Card', 'woo-gift-cards-lite' );
					$to         = $wallet_user->user_email;
					$from       = get_option( 'admin_email' );
					$subject    = __( 'Wallet updating notification', 'woo-gift-cards-lite' );
					$headers    = 'MIME-Version: 1.0' . "\r\n";
					$headers   .= 'Content-Type: text/html;  charset=UTF-8' . "\r\n";
					$headers   .= 'From: ' . $from . "\r\n" .
						'Reply-To: ' . $to . "\r\n";

					$wallet_payment_gateway->send_mail_on_wallet_updation( $to, $subject, $mail_text, $headers );

				}

				$transaction_type = __( 'Wallet credited through Gift Card Code : ', 'woo-gift-cards-lite' ) . $wps_giftcard_code;
				$transaction_data = array(
					'user_id'          => $user_id,
					'amount'           => $coupon_amount,
					'currency'         => get_woocommerce_currency(),
					'payment_method'   => 'Gift Card Redeem',
					'transaction_type' => htmlentities( $transaction_type ),
				);

				$wallet_payment_gateway->insert_transaction_data_in_table( $transaction_data );

				$response = array(
					'status'  => 'success',
					'message' => $coupon_amount,
				);
			} else {
				$response = array(
					'status'  => 'failed',
					'message' => __( 'Coupon Already used or expired', 'woo-gift-cards-lite' ),
				);
			}
		} else {
			$response = array(
				'status'  => 'failed',
				'message' => __( 'Enter Valid Gift Card Code', 'woo-gift-cards-lite' ),
			);
		}
		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * This function is to append variable price in front end.
	 *
	 * @return void
	 */
	public function wps_wgm_append_variable_price() {
		check_ajax_referer( 'wps-wgc-verify-nonce', 'wps_nonce' );
		$response['result'] = false;
		$wps_wgm_price      = isset( $_POST['wps_wgm_price'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wgm_price'] ) ) : '';
		$product_id         = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		$product            = wc_get_product( $product_id );
		$decimal_separator  = get_option( 'woocommerce_price_decimal_sep' );
		$wps_wgm_price      = floatval( str_replace( $decimal_separator, '.', $wps_wgm_price ) );

		if ( isset( $wps_wgm_price ) && ! empty( $wps_wgm_price ) ) {
			// for price based on country.
			if ( class_exists( 'WCPBC_Pricing_Zone' ) ) {
				if ( wcpbc_the_zone() != null && wcpbc_the_zone() ) {
					$wps_wgm_price = wcpbc_the_zone()->get_exchange_rate_price( $wps_wgm_price );
				}
			}
			$wps_wgm_price = ( 'incl' === get_option( 'woocommerce_tax_display_shop' ) ) ?
			wc_get_price_including_tax(
				$product,
				array(
					'qty'   => 1,
					'price' => $wps_wgm_price,
				)
			) :
			wc_get_price_excluding_tax(
				$product,
				array(
					'qty'   => 1,
					'price' => $wps_wgm_price,
				)
			);

			$response['result'] = true;
			$response['new_price'] = wc_price( $wps_wgm_price );
			echo wp_json_encode( $response );
			wp_die();
		} else {
			echo wp_json_encode( $response );
			wp_die();
		}
	}




	/**
	 * This is function is used to create shortcode to check giftcard balance.
	 *
	 * @name wps_uwgc_add_short_code_giftcard_balance
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_add_short_code_giftcard_balance_org() {
		add_shortcode( 'wps_check_your_gift_card_balance', array( $this, 'wps_uwgc_gift_card_balance_org' ) );
	}

	/**
	 * This is function is used to display giftcard remaining balance.
	 *
	 * @name wps_uwgc_gift_card_balance
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_gift_card_balance_org() {
		$html = '<div class="wps_gift_card_balance_wrapper">';

		$html .= '<div class="gift_card_balance_email"><label>' . __( 'Enter Recipient Email', 'woo-gift-cards-lite' ) . '</label>	<input type="email" id="gift_card_balance_email" class="wps_gift_balance" placeholder="' . __( 'Enter Recipient Email/Name or Sender Email.', 'woo-gift-cards-lite' ) . '" required="required"></div>';
		$html .= '<div class="gift_card_code"><label>' . __( 'Enter Gift Card Code', 'woo-gift-cards-lite' ) . '</label>	<input type="text" id="gift_card_code" class="wps_gift_balance" placeholder="' . __( 'Enter Gift Card Code', 'woo-gift-cards-lite' ) . '" required="required"></div>';
		$html .= '<p class="wps_check_balance"><input class="button wps_check_balance" type="button" id="wps_check_balance" value="' . __( 'Check Balance', 'woo-gift-cards-lite' ) . '"><span id="wps_notification"></span></p></div>';
		$html .= '<div style="display: none;" class="loading-style-bg" id="wps_wgm_loader"><img src="' . WPS_WGC_URL . 'assets/images/loading.gif"></div></div>';
		return $html;
	}

	/**
	 * This is function is used to check giftcard balance on Ajax Call.
	 *
	 * @name wps_uwgc_check_gift_balance
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link http://www.wpswings.com/
	 */
	public function wps_uwgc_check_gift_balance_org() {

		check_ajax_referer( 'wps-wgc-verify-nonce-check', 'wps_wgm_nonce_check' );
		$response['result'] = false;
		$response['message'] = __( 'Balance cannot be checked yet, Please Try again later!', 'woo-gift-cards-lite' );
		$wps_check_email = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		$coupon = isset( $_POST['coupon'] ) ? sanitize_text_field( wp_unslash( $_POST['coupon'] ) ) : '';
		if ( isset( $coupon ) && ! empty( $coupon ) && isset( $wps_check_email ) && ! empty( $wps_check_email ) ) {
			$the_coupon = new WC_Coupon( $coupon );
			if ( isset( $the_coupon ) && ! empty( $coupon ) ) {
				$coupon_id = $the_coupon->get_id();
				if ( isset( $coupon_id ) && ! empty( $coupon_id ) && 0 != $coupon_id ) {
					$left_amount = $the_coupon->get_amount();
					$coupon_type = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_unique', true );
					$user_email = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon_mail_to', true );
					if ( 'offline' === $coupon_type ) {
						if ( isset( $user_email ) && ! empty( $user_email ) ) {
							if ( $user_email == $wps_check_email ) {
								$html = '<div class="amount_wrapper">' . __( 'Balance: ', 'woo-gift-cards-lite' ) . wc_price( $left_amount ) . '</div>';
								$response['result'] = true;
								$response['html'] = $html;
								$response['message'] = __( 'Data Match Successfully!!', 'woo-gift-cards-lite' );
							} else {
								$response['result'] = false;
								$response['message'] = __( 'Recipient Email should be correct!!', 'woo-gift-cards-lite' );
							}
						}
					} elseif ( 'online' === $coupon_type ) {
						$order_id = get_post_meta( $coupon_id, 'wps_wgm_giftcard_coupon', true );
						if ( isset( $order_id ) && ! empty( $order_id ) ) {
							$order = wc_get_order( $order_id );
							$sender_email = $order->get_billing_email();
							$user_id = $order->get_user_id();
							$wps_user_name = get_userdata( $user_id );
							$wps_sender_name = $wps_user_name->first_name . ' ' . $wps_user_name->last_name;
							if ( ( isset( $user_email ) && ! empty( $user_email ) ) || ( isset( $sender_email ) && ! empty( $sender_email ) ) ) {
								if ( $user_email == $wps_check_email ) {
									$html = '<div class="amount_wrapper">' . __( 'Balance: ', 'woo-gift-cards-lite' ) . wc_price( $left_amount ) . '</div>';
									$response['result'] = true;
									$response['html'] = $html;
									$response['message'] = __( 'Data Match Successfully!!', 'woo-gift-cards-lite' );
								} elseif ( $sender_email == $wps_check_email ) {
									$html = '<div class="amount_wrapper">' . __( 'Balance: ', 'woo-gift-cards-lite' ) . wc_price( $left_amount ) . '</div>';
									$response['result'] = true;
									$response['html'] = $html;
									$response['message'] = __( 'Data Match Successfully!!', 'woo-gift-cards-lite' );
								} elseif ( $wps_sender_name == $wps_check_email ) {
									$html = '<div class="amount_wrapper">' . __( 'Balance: ', 'woo-gift-cards-lite' ) . wc_price( $left_amount ) . '</div>';
									$response['result'] = true;
									$response['html'] = $html;
									$response['message'] = __( 'Data Match Successfully!!', 'woo-gift-cards-lite' );
								} else {
									$response['result'] = false;
									$response['message'] = __( 'Recipient Email or Sender Email|Name should be correct!!', 'woo-gift-cards-lite' );
								}
							}
						}
					} else {
						$response['result'] = false;
						$response['message'] = __( 'Balance cannot be checked for this coupon.', 'woo-gift-cards-lite' );
					}
				} else {
					$response['result'] = false;
					$response['message'] = __( 'Coupon is Invalid!', 'woo-gift-cards-lite' );
				}
			}
		} else {
			$response['result'] = false;
			$response['message'] = __( 'Fields cannot be empty!', 'woo-gift-cards-lite' );
		}
		echo wp_json_encode( $response );
		wp_die();

	}

	/**
	 * Preview thumbnail forlayout 2 function.
	 */
	public function wps_wgm_preview_below_thumbnail() {

		global $product;
		$other_settings = get_option( 'wps_wgm_other_settings', array() );
		$wps_public_obj = new Woocommerce_Gift_Cards_Common_Function();
		$use_new_page_layout = $wps_public_obj->wps_wgm_get_template_data( $other_settings, 'wps_wgm_new_gift_card_page_layout' );

		$product_settings   = get_option( 'wps_wgm_product_settings', array() );
		$disable_from_field = $wps_public_obj->wps_wgm_get_template_data( $product_settings, 'wps_wgm_from_field' );
		$disable_message_field = $wps_public_obj->wps_wgm_get_template_data( $product_settings, 'wps_wgm_message_field' );
		$disable_to_email_field = $wps_public_obj->wps_wgm_get_template_data( $product_settings, 'wps_wgm_to_email_field' );

		if ( 'on' == $use_new_page_layout ) {
			$prod_id = wc_get_product( $product->get_id() );

			$prod_type = $prod_id->get_type();
			$prod_title = $prod_id->get_title();

			$html = '';

			if ( 'wgm_gift_card' == $prod_type ) {

				$html .= '<div class="wps_wgm_wrapper_for_preview"> 
					  <h2>' . $prod_title . '<span>' . get_woocommerce_currency_symbol() . '<span id="wps_wgm_price_preview"></span></span></h2>';
					  if ( 'on' != $disable_from_field ) {
						$html .= '<p >' . __( 'From', 'woo-gift-cards-lite' ) . ' : <span class="wps_text_style" id="wps_from_preview"> ' . __( 'xyz test', 'woo-gift-cards-lite' ) . '</span> </p>';
					  }
					  if ( 'on' != $disable_to_email_field ) {
						$html .= '<p >' . __( 'To', 'woo-gift-cards-lite' ) . ' : <span class="wps_text_style" id="wps_to_preview">' . __( 'xyz@gmail.com', 'woo-gift-cards-lite' ) . '</span></p>';
					  }
					  if ( 'on' != $disable_message_field ) {
					  	$html .= '<p >' . __( 'Message', 'woo-gift-cards-lite' ) . ' : <span class="wps_text_style" id="wps_message_preview">' . __( 'Write your message gift card receiver', 'woo-gift-cards-lite' ) . '</span></p>';
					  }
				$html .='</div>';

			}
			echo wp_kses_post( $html );
		}

	}

	// PAR Compatibility.

	/**
	 * This function is used to display coupon redeem option on My Account page.
	 *
	 * @param  int $user_id user_id.
	 * @return void
	 */
	public function wps_wgm_coupon_redeem_option( $user_id ) {

		$wps_wgm_other_settings                    = get_option( 'wps_wgm_other_settings', array() );
		$wps_wgm_other_settings                    = ! empty( $wps_wgm_other_settings ) && is_array( $wps_wgm_other_settings ) ? $wps_wgm_other_settings : array();
		$wps_wgm_enable_coupon_conversion_settings = ! empty( $wps_wgm_other_settings['wps_wgm_enable_coupon_conversion_settings'] ) ? $wps_wgm_other_settings['wps_wgm_enable_coupon_conversion_settings'] : 'off';
		$wps_wgm_enter_points_rate                 = ! empty( $wps_wgm_other_settings['wps_wgm_enter_points_rate'] ) ? $wps_wgm_other_settings['wps_wgm_enter_points_rate'] : 1;
		$wps_wgm_enter_price_rate                  = ! empty( $wps_wgm_other_settings['wps_wgm_enter_price_rate'] ) ? $wps_wgm_other_settings['wps_wgm_enter_price_rate'] : 1;
		if ( 'on' === $wps_wgm_enable_coupon_conversion_settings ) {
			?>
			<div class="wps_wpr_wallet_conversion_wrap wps_wpr_main_section_all_wrap">
				<p class="wps_wpr_heading"><?php echo esc_html__( 'Convert gift coupons into points.', 'woo-gift-cards-lite' ); ?></p>
				<fieldset class="wps_wpr_each_section">
					<p>
						<?php echo esc_html__( 'Coupon Redeem rate : ', 'woo-gift-cards-lite' ); ?>
						<?php echo wp_kses( wc_price( $wps_wgm_enter_price_rate ), $this->wps_common_fun->wps_allowed_html_tags() ) . ' = ' . esc_html( $wps_wgm_enter_points_rate ) . esc_html__( ' Points', 'woo-gift-cards-lite' ); ?>
					</p>
					<form id="points_wallet" enctype="multipart/form-data" action="" method="post">
						<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
							<label for="wps_custom_wallet_text">
								<?php esc_html_e( 'Enter your Coupon :', 'woo-gift-cards-lite' ); ?>
							</label>
						</p>
						<p class="wps-wpr_enter-points-wrap">
							<input type="text" placeholder="<?php esc_html_e( 'Enter Coupon', 'woo-gift-cards-lite' ); ?>" class="woocommerce-Input woocommerce-Input--number input-number" id="wps_wgm_coupon_redeem_value" style="width: 160px;">
							<input type="button" id= "wps_wgm_redeem_coupon" class="button" value="<?php esc_html_e( 'Redeem Coupon', 'woo-gift-cards-lite' ); ?>" data-id="<?php echo esc_html( $user_id ); ?>">
							<img class="wps_wgm_coupon_redeem_loader" src="<?php echo esc_html( WPS_WGC_URL . 'assets/images/loading.gif' ); ?>" width="30">
						</p>
						<div id="wps_wgm_coupon_redeem_notify"></div>
					</form>
				</fieldset>
			</div>
			<?php
		}
	}

	/**
	 * This function is used to redeem coupon amount.
	 *
	 * @return void
	 */
	public function wps_wgm_redeem_gift_card_coupon() {
		check_ajax_referer( 'wps-wgc-verify-nonce-check', 'wps_wgm_nonce_check' );

		$user_id     = get_current_user_id();
		$coupon_code = ! empty( $_POST['coupon_code'] ) ? sanitize_text_field( wp_unslash( $_POST['coupon_code'] ) ) : '';
		$response    = array();
		if ( ! empty( $coupon_code ) && ! empty( $user_id ) ) {

			$coupon = new WC_Coupon( $coupon_code );
			if ( ! empty( $coupon ) && is_object( $coupon ) ) {

				$coupon_id = $coupon->get_id();
				if ( ! empty( $coupon_id ) ) {

					$coupon_amount = $coupon->get_amount();
					if ( $coupon->is_valid() ) {
						if ( $coupon_amount > 0 ) {

							$this->wps_wgm_updating_par_points( $user_id, $coupon_amount, $coupon );
							$coupon->set_amount( 0 );
							$coupon->save();
							do_action( 'wps_wgm_send_mail_remaining_amount', $coupon_id, 0 );
							$response['result'] = true;
							$response['msg']    = esc_html__( 'Coupon redeem successfully...', 'woo-gift-cards-lite' );
						} else {

							$response['result'] = false;
							$response['msg']    = esc_html__( 'The coupon has already been redeemed', 'woo-gift-cards-lite' );
						}
					} else {
						$response['result'] = false;
						$response['msg']    = esc_html__( 'Expired Coupon', 'woo-gift-cards-lite' );
					}
				} else {

					$response['result'] = false;
					$response['msg']    = esc_html__( 'Invalid Coupon', 'woo-gift-cards-lite' );
				}
			} else {

				$response['result'] = false;
				$response['msg']    = esc_html__( 'Invalid Coupon', 'woo-gift-cards-lite' );
			}
		} else {

			$response['result'] = false;
			$response['msg']    = esc_html__( 'Please enter values', 'woo-gift-cards-lite' );
		}
		wp_send_json( $response );
		wp_die();
	}

	/**
	 * This function is used to rewards points to user.
	 *
	 * @param int    $user_id       user_id.
	 * @param int    $coupon_amount coupon_amount.
	 * @param object $coupon        coupon.
	 * @return void
	 */
	public function wps_wgm_updating_par_points( $user_id, $coupon_amount, $coupon ) {

		// get conversion rate.
		$wps_wgm_other_settings    = get_option( 'wps_wgm_other_settings', array() );
		$wps_wgm_other_settings    = ! empty( $wps_wgm_other_settings ) && is_array( $wps_wgm_other_settings ) ? $wps_wgm_other_settings : array();
		$wps_wgm_enter_points_rate = ! empty( $wps_wgm_other_settings['wps_wgm_enter_points_rate'] ) ? $wps_wgm_other_settings['wps_wgm_enter_points_rate'] : 1;
		$wps_wgm_enter_price_rate  = ! empty( $wps_wgm_other_settings['wps_wgm_enter_price_rate'] ) ? $wps_wgm_other_settings['wps_wgm_enter_price_rate'] : 1;

		// get points and details.
		$get_points            = get_user_meta( $user_id, 'wps_wpr_points', true );
		$get_points            = ! empty( $get_points ) ? $get_points : 0;
		$coupon_redeem_details = get_user_meta( $user_id, 'points_details', true );
		$coupon_redeem_details = ! empty( $coupon_redeem_details ) && is_array( $coupon_redeem_details ) ? $coupon_redeem_details : array();
		$calculated_points     = (int) ( $coupon_amount * $wps_wgm_enter_points_rate / $wps_wgm_enter_price_rate );
		$updated_points        = (int) $get_points + $calculated_points;

		if ( isset( $coupon_redeem_details['gift_coupon_redeem_details'] ) && ! empty( $coupon_redeem_details['gift_coupon_redeem_details'] ) ) {

			$arr = array(
				'date'                       => date_i18n( 'Y-m-d h:i:sa' ),
				'gift_coupon_redeem_details' => $calculated_points,
				'coupon_name'                => $coupon->get_code(),
			);
			$coupon_redeem_details['gift_coupon_redeem_details'][] = $arr;
		} else {

			$arr = array(
				'date'                       => date_i18n( 'Y-m-d h:i:sa' ),
				'gift_coupon_redeem_details' => $calculated_points,
				'coupon_name'                => $coupon->get_code(),
			);
			$coupon_redeem_details['gift_coupon_redeem_details'][] = $arr;
		}

		// updating values.
		update_user_meta( $user_id, 'wps_wpr_points', $updated_points );
		update_user_meta( $user_id, 'points_details', $coupon_redeem_details );

		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
        $wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_notification_setting_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_notification_setting_enable'] ) : 0;

		if ( 1 == $wps_wpr_notificatin_enable ) {
			$user = get_user_by( 'id', $user_id );
			$subject = 'Updated Points Notification';
			$message = 'Your coupon has been successfully redeemed, ' . $calculated_points . ' points are successfully added to your account. Your Points are updated to ' . $updated_points;
			
			wc_mail( $user->user_email, $subject, $message );
		}
		
	}

	/**
	 * This function is used to create coupon redeem log on user end.
	 *
	 * @param  array $point_log point_log.
	 * @return void
	 */
	public function wps_wgm_user_end_points_log( $point_log ) {

		if ( array_key_exists( 'gift_coupon_redeem_details', $point_log ) ) {
			?>
			<div class="wps_wpr_slide_toggle">
				<p class="wps_wpr_view_log_notice wps_wpr_common_slider"><?php esc_html_e( 'Gift Coupon Redeem Points', 'woo-gift-cards-lite' ); ?><a class ="wps_wpr_open_toggle"  href="javascript:;"></a></p>
				<div class="wps_wpr_points_view">
					<table class="wps_wpr_common_table">
						<thead>
							<tr>
								<th class="wps-wpr-view-log-Date">
									<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date', 'woo-gift-cards-lite' ); ?></span>
								</th>
								<th class="wps-wpr-view-log-Status">
									<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'woo-gift-cards-lite' ); ?></span>
								</th>
								<th class="wps-wpr-view-log-Status">
									<span class="wps_wpr_nobr"><?php echo esc_html__( 'Coupon Name', 'woo-gift-cards-lite' ); ?></span>
								</th>
							</tr>
						</thead>
						<?php
						foreach ( $point_log['gift_coupon_redeem_details'] as $key => $value ) {
							?>
							<tr>
								<td><?php echo esc_html( wps_wpr_set_the_wordpress_date_format( $value['date'] ) ); ?></td>
								<td><?php echo '+' . esc_html( $value['gift_coupon_redeem_details'] ); ?></td>
								<td><?php echo esc_html( $value['coupon_name'] ); ?></td>
							</tr>
							<?php
						}
						?>
					</table>
				</div>
			</div>
			<?php
		}
	}

}
