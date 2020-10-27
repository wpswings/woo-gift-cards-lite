<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/includes
 * @author     makewebbetter <ticket@makewebbetter.com>
 */
class Woocommerce_gift_cards_lite_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 * @name load_plugin_textdomain
	 * @author makewebbetter<ticket@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woo-gift-cards-lite',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
