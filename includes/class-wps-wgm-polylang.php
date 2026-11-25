<?php
/**
 * Polylang for WooCommerce compatibility helper.
 *
 * One place for every Polylang call the gift card plugins make. Org and Pro
 * call these static methods instead of the pll_* API directly. When Polylang
 * or Polylang for WooCommerce is not active, every method falls back to a
 * no-op so callers need no checks of their own.
 *
 * @link       https://wpswings.com/
 * @since      3.3.0
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Thin wrapper around the Polylang / Polylang for WooCommerce API.
 *
 * @package    woo-gift-cards-lite
 * @subpackage woo-gift-cards-lite/includes
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Wps_Wgm_Polylang {

	/**
	 * Order meta holding the language slug the customer ordered in.
	 */
	const ORDER_LANG_META = '_wps_wgm_order_lang';

	/**
	 * Languages saved by switch_lang(), restored in reverse order.
	 *
	 * @var array
	 */
	private static $lang_stack = array();

	/**
	 * Register hooks. Safe to call more than once.
	 *
	 * @return void
	 */
	public static function init() {
		static $done = false;
		if ( $done ) {
			return;
		}
		$done = true;

		// Classic checkout: the order object is saved right after this hook.
		add_action( 'woocommerce_checkout_create_order', array( __CLASS__, 'save_order_lang' ), 10, 1 );
		// Block (Store API) checkout.
		add_action( 'woocommerce_store_api_checkout_update_order_meta', array( __CLASS__, 'save_order_lang_and_persist' ), 10, 1 );

		// Settings strings: list them in Languages > Translations, and translate them when read.
		add_action( 'init', array( __CLASS__, 'register_setting_strings' ), 20 );
		add_action( 'init', array( __CLASS__, 'add_setting_filters' ), 20 );

		// Gift card products: carry gift card settings and the product type over to translations.
		add_filter( 'pll_copy_post_metas', array( __CLASS__, 'copy_product_metas' ), 10, 3 );
		add_filter( 'pll_copy_taxonomies', array( __CLASS__, 'copy_product_type' ), 10, 3 );

		// Gift card designs (giftcard post type) can be translated.
		add_filter( 'pll_get_post_types', array( __CLASS__, 'add_template_post_type' ), 10, 2 );

		// Gift card coupons: product/category restrictions apply to every language version.
		// WooCommerce runs these only in 'view' context, so saved values are never changed.
		add_filter( 'woocommerce_coupon_get_product_ids', array( __CLASS__, 'expand_coupon_product_ids' ), 10, 2 );
		add_filter( 'woocommerce_coupon_get_excluded_product_ids', array( __CLASS__, 'expand_coupon_product_ids' ), 10, 2 );
		add_filter( 'woocommerce_coupon_get_product_categories', array( __CLASS__, 'expand_coupon_term_ids' ), 10, 2 );
		add_filter( 'woocommerce_coupon_get_excluded_product_categories', array( __CLASS__, 'expand_coupon_term_ids' ), 10, 2 );
	}

	/**
	 * Whether a coupon is a gift card, remembered per request.
	 *
	 * @param WC_Coupon $coupon Coupon.
	 * @return bool
	 */
	private static function is_gift_card_coupon( $coupon ) {
		static $cache = array();

		$coupon_id = $coupon instanceof WC_Coupon ? (int) $coupon->get_id() : 0;
		if ( ! $coupon_id || ! class_exists( 'Woocommerce_Gift_Cards_Common_Function' ) ) {
			return false;
		}
		if ( ! isset( $cache[ $coupon_id ] ) ) {
			static $common = null;
			if ( null === $common ) {
				$common = new Woocommerce_Gift_Cards_Common_Function();
			}
			$cache[ $coupon_id ] = (bool) $common->wps_wgm_is_giftcard_coupon( $coupon );
		}
		return $cache[ $coupon_id ];
	}

	/**
	 * Add every translation of a gift card coupon's restricted (or excluded) products.
	 *
	 * @param int[]     $ids    Product IDs.
	 * @param WC_Coupon $coupon Coupon.
	 * @return int[]
	 */
	public static function expand_coupon_product_ids( $ids, $coupon ) {
		if ( empty( $ids ) || ! is_array( $ids ) || ! self::is_active() || ! self::is_gift_card_coupon( $coupon ) ) {
			return $ids;
		}

		$all = array();
		foreach ( $ids as $id ) {
			$all = array_merge( $all, self::get_all_translation_ids( $id ) );
		}
		return array_values( array_unique( array_map( 'absint', $all ) ) );
	}

	/**
	 * Add every translation of a gift card coupon's restricted (or excluded) product categories.
	 *
	 * @param int[]     $ids    Product category term IDs.
	 * @param WC_Coupon $coupon Coupon.
	 * @return int[]
	 */
	public static function expand_coupon_term_ids( $ids, $coupon ) {
		if ( empty( $ids ) || ! is_array( $ids ) || ! self::is_active() || ! self::is_gift_card_coupon( $coupon ) ) {
			return $ids;
		}

		$all = array();
		foreach ( $ids as $id ) {
			$all = array_merge( $all, self::get_all_term_translation_ids( $id ) );
		}
		return array_values( array_unique( array_map( 'absint', $all ) ) );
	}

	/**
	 * IDs of a term and all its translations.
	 *
	 * @param int $term_id Term ID.
	 * @return int[] Always contains $term_id itself.
	 */
	public static function get_all_term_translation_ids( $term_id ) {
		$term_id = absint( $term_id );
		if ( ! $term_id ) {
			return array();
		}
		if ( ! self::is_active() || ! function_exists( 'pll_get_term_translations' ) ) {
			return array( $term_id );
		}

		$ids   = array_map( 'absint', array_values( (array) pll_get_term_translations( $term_id ) ) );
		$ids[] = $term_id;

		return array_values( array_unique( array_filter( $ids ) ) );
	}

	/**
	 * Make the gift card design post type translatable in Polylang.
	 *
	 * @param array $post_types Post types Polylang translates.
	 * @param bool  $is_settings Whether Polylang is building its settings list.
	 * @return array
	 */
	public static function add_template_post_type( $post_types, $is_settings ) {
		unset( $is_settings );
		$post_types['giftcard'] = 'giftcard';
		return $post_types;
	}

	/**
	 * ID of a gift card design in the given (default: current) language.
	 *
	 * Designs are stored by their original ID in product meta and settings; this
	 * swaps in the translated design at render time. Returns the ID unchanged when
	 * there is no translation, or when it is not a valid ID.
	 *
	 * @param mixed  $template_id Design post ID.
	 * @param string $lang        Language slug.
	 * @return mixed
	 */
	public static function get_translated_template_id( $template_id, $lang = '' ) {
		if ( ! is_numeric( $template_id ) || (int) $template_id <= 0 || ! self::is_active() ) {
			return $template_id;
		}
		return self::get_translated_id( (int) $template_id, $lang );
	}

	/**
	 * Gift card product meta keys that every translation of a product shares.
	 *
	 * Pro adds its own keys through the filter.
	 *
	 * @return string[]
	 */
	public static function get_product_meta_keys() {
		$keys = array(
			'wps_wgm_pricing',
			'wps_wgm_pricing_details',
		);

		/**
		 * Filter the gift card product meta keys Polylang copies to, and keeps in sync
		 * across, a product's translations.
		 *
		 * @since 3.3.0
		 * @param string[] $keys Meta keys.
		 */
		return array_values( array_unique( (array) apply_filters( 'wps_wgm_polylang_product_meta_keys', $keys ) ) );
	}

	/**
	 * Add gift card meta to the keys Polylang copies when a product is translated
	 * (and syncs, when meta synchronization is on).
	 *
	 * Keys the source product does not have are skipped by Polylang, so this is
	 * safe for products that are not gift cards.
	 *
	 * @param string[] $metas Meta keys Polylang will copy.
	 * @param bool     $sync  True when synchronizing, false when creating a translation.
	 * @param int      $from  Source post ID.
	 * @return string[]
	 */
	public static function copy_product_metas( $metas, $sync, $from ) {
		if ( 'product' !== get_post_type( $from ) ) {
			return $metas;
		}
		return array_values( array_unique( array_merge( (array) $metas, self::get_product_meta_keys() ) ) );
	}

	/**
	 * Make sure the product type (e.g. Gift Card) is copied to product translations.
	 * Polylang for WooCommerce normally does this; this keeps it working if it does not.
	 *
	 * @param string[] $taxonomies Taxonomies Polylang will copy.
	 * @param bool     $sync       True when synchronizing, false when creating a translation.
	 * @param int      $from       Source post ID.
	 * @return string[]
	 */
	public static function copy_product_type( $taxonomies, $sync, $from ) {
		if ( 'product' !== get_post_type( $from ) ) {
			return $taxonomies;
		}
		$taxonomies = (array) $taxonomies;
		if ( ! in_array( 'product_type', $taxonomies, true ) ) {
			$taxonomies[] = 'product_type';
		}
		return $taxonomies;
	}

	/**
	 * Customer-facing settings strings that can be translated.
	 *
	 * Format: option name => array( setting key => multiline? ). Pro adds its own
	 * keys through the filter.
	 *
	 * @return array
	 */
	public static function get_translatable_settings() {
		$settings = array(
			'wps_wgm_mail_settings' => array(
				'wps_wgm_mail_setting_giftcard_subject' => false,
				'wps_wgm_mail_setting_default_message'  => true,
				'wps_wgm_mail_setting_disclaimer'       => true,
				'wps_wgm_reminder_mail_subject'         => false,
				'wps_wgm_reminder_mail_body'            => true,
			),
		);

		/**
		 * Filter the settings strings that Polylang can translate.
		 *
		 * @since 3.3.0
		 * @param array $settings Option name => array( setting key => multiline? ).
		 */
		return (array) apply_filters( 'wps_wgm_polylang_translatable_settings', $settings );
	}

	/**
	 * Register the saved settings strings with Polylang so merchants can translate them.
	 * Polylang only needs this in the admin; it does not store the list.
	 *
	 * @return void
	 */
	public static function register_setting_strings() {
		if ( ! is_admin() || ! self::is_active() || ! function_exists( 'pll_register_string' ) ) {
			return;
		}

		foreach ( self::get_translatable_settings() as $option_name => $keys ) {
			$values = get_option( $option_name, array() );
			if ( ! is_array( $values ) ) {
				continue;
			}
			foreach ( (array) $keys as $key => $multiline ) {
				if ( isset( $values[ $key ] ) && is_string( $values[ $key ] ) && '' !== trim( $values[ $key ] ) ) {
					pll_register_string( $key, $values[ $key ], 'Gift Cards', (bool) $multiline );
				}
			}
		}
	}

	/**
	 * Hook the translation filter onto every option that has translatable keys.
	 *
	 * @return void
	 */
	public static function add_setting_filters() {
		if ( ! self::is_active() ) {
			return;
		}
		foreach ( array_keys( self::get_translatable_settings() ) as $option_name ) {
			add_filter( 'option_' . $option_name, array( __CLASS__, 'translate_setting_option' ), 10, 2 );
		}
	}

	/**
	 * Replace translatable settings strings with their translation in the current language.
	 *
	 * Translates on the front end, and in the admin only while an email is being built
	 * (inside switch_lang()). Settings screens always see the original text, so saving
	 * them can never overwrite it with a translation.
	 *
	 * @param mixed  $value       Option value.
	 * @param string $option_name Option name.
	 * @return mixed
	 */
	public static function translate_setting_option( $value, $option_name ) {
		if ( ! is_array( $value ) || ! self::should_translate_settings() ) {
			return $value;
		}

		$lang     = self::get_current_lang();
		$settings = self::get_translatable_settings();
		if ( ! self::is_valid_lang( $lang ) || empty( $settings[ $option_name ] ) ) {
			return $value;
		}

		foreach ( array_keys( (array) $settings[ $option_name ] ) as $key ) {
			if ( isset( $value[ $key ] ) && is_string( $value[ $key ] ) && '' !== $value[ $key ] ) {
				$value[ $key ] = pll_translate_string( $value[ $key ], $lang );
			}
		}

		return $value;
	}

	/**
	 * Whether settings strings should be translated in this request.
	 *
	 * @return bool
	 */
	private static function should_translate_settings() {
		return self::is_switched() || ! is_admin();
	}

	/**
	 * Whether switch_lang() has switched the language and not been restored yet.
	 *
	 * @return bool
	 */
	public static function is_switched() {
		foreach ( self::$lang_stack as $entry ) {
			if ( ! empty( $entry['switched'] ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Store the checkout language on the order. Classic checkout saves the order itself.
	 *
	 * @param WC_Order $order Order being created.
	 * @return bool Whether a language was set.
	 */
	public static function save_order_lang( $order ) {
		if ( ! $order instanceof WC_Order || ! self::is_active() ) {
			return false;
		}

		$lang = self::get_current_lang();
		if ( ! self::is_valid_lang( $lang ) ) {
			return false;
		}

		$order->update_meta_data( self::ORDER_LANG_META, $lang );
		return true;
	}

	/**
	 * Store the checkout language on a block checkout order and save it.
	 *
	 * @param WC_Order $order Order being placed.
	 * @return void
	 */
	public static function save_order_lang_and_persist( $order ) {
		if ( self::save_order_lang( $order ) ) {
			$order->save_meta_data();
		}
	}

	/**
	 * Whether Polylang and Polylang for WooCommerce are both active.
	 *
	 * @return bool
	 */
	public static function is_active() {
		return function_exists( 'pll_current_language' )
			&& function_exists( 'PLL' )
			&& ( defined( 'PLLWC_VERSION' ) || function_exists( 'PLLWC' ) );
	}

	/**
	 * Slug of the site's default language, or '' when inactive.
	 *
	 * @return string
	 */
	public static function get_default_lang() {
		if ( ! self::is_active() ) {
			return '';
		}
		$lang = pll_default_language();
		return is_string( $lang ) ? $lang : '';
	}

	/**
	 * Slug of the language of the current request, or '' when none is set.
	 *
	 * @return string
	 */
	public static function get_current_lang() {
		if ( ! self::is_active() ) {
			return '';
		}
		$lang = pll_current_language();
		return is_string( $lang ) ? $lang : '';
	}

	/**
	 * Whether a slug belongs to a configured Polylang language.
	 *
	 * @param string $lang Language slug.
	 * @return bool
	 */
	public static function is_valid_lang( $lang ) {
		if ( ! self::is_active() || ! is_string( $lang ) || '' === $lang ) {
			return false;
		}
		return in_array( $lang, (array) pll_languages_list(), true );
	}

	/**
	 * Language the customer placed the order in.
	 *
	 * Checks our own order meta first, then Polylang for WooCommerce, then the
	 * site default.
	 *
	 * @param WC_Order|int $order Order object or ID.
	 * @return string Language slug, or '' when inactive.
	 */
	public static function get_order_lang( $order ) {
		if ( ! self::is_active() ) {
			return '';
		}

		$order = is_numeric( $order ) ? wc_get_order( $order ) : $order;
		if ( ! $order instanceof WC_Order ) {
			return self::get_default_lang();
		}

		$lang = $order->get_meta( self::ORDER_LANG_META, true );
		if ( self::is_valid_lang( $lang ) ) {
			return $lang;
		}

		// Polylang for WooCommerce keeps the order language in its own data store (HPOS safe).
		if ( class_exists( 'PLLWC_Data_Store' ) && method_exists( 'PLLWC_Data_Store', 'load' ) ) {
			try {
				$store = PLLWC_Data_Store::load( 'order_language' );
				if ( $store && method_exists( $store, 'get_language' ) ) {
					$lang = $store->get_language( $order->get_id() );
					if ( self::is_valid_lang( $lang ) ) {
						return $lang;
					}
				}
			} catch ( Exception $e ) {
				// Fall through to the next source.
				unset( $e );
			}
		}

		$lang = pll_get_post_language( $order->get_id() );
		if ( self::is_valid_lang( $lang ) ) {
			return $lang;
		}

		return self::get_default_lang();
	}

	/**
	 * ID of a post's translation in the given language.
	 *
	 * Returns the original ID when inactive, when the language is empty, or when
	 * no translation exists, so callers can always use the result.
	 *
	 * @param int    $post_id Post ID (product, gift card template, ...).
	 * @param string $lang    Language slug. Defaults to the current language.
	 * @return int
	 */
	public static function get_translated_id( $post_id, $lang = '' ) {
		$post_id = absint( $post_id );
		if ( ! $post_id || ! self::is_active() ) {
			return $post_id;
		}

		$lang = '' !== $lang ? $lang : self::get_current_lang();
		if ( ! self::is_valid_lang( $lang ) ) {
			return $post_id;
		}

		$translated = pll_get_post( $post_id, $lang );
		return $translated ? absint( $translated ) : $post_id;
	}

	/**
	 * IDs of a post and all its translations.
	 *
	 * @param int $post_id Post ID.
	 * @return int[] Always contains $post_id itself.
	 */
	public static function get_all_translation_ids( $post_id ) {
		$post_id = absint( $post_id );
		if ( ! $post_id ) {
			return array();
		}
		if ( ! self::is_active() ) {
			return array( $post_id );
		}

		$ids   = array_map( 'absint', array_values( (array) pll_get_post_translations( $post_id ) ) );
		$ids[] = $post_id;

		return array_values( array_unique( array_filter( $ids ) ) );
	}

	/**
	 * Switch Polylang's language and the WordPress locale.
	 *
	 * Always pair with restore_lang(), even when this returns false.
	 *
	 * @param string $lang Language slug.
	 * @return bool Whether the language was switched.
	 */
	public static function switch_lang( $lang ) {
		$previous = self::is_active() && isset( PLL()->curlang ) ? PLL()->curlang : null;

		if ( ! self::is_valid_lang( $lang ) || empty( PLL()->model ) ) {
			self::$lang_stack[] = array(
				'switched' => false,
				'language' => $previous,
			);
			return false;
		}

		$language = PLL()->model->get_language( $lang );
		if ( ! $language ) {
			self::$lang_stack[] = array(
				'switched' => false,
				'language' => $previous,
			);
			return false;
		}

		PLL()->curlang   = $language;
		$locale_switched = switch_to_locale( $language->locale );

		self::$lang_stack[] = array(
			'switched' => true,
			'language' => $previous,
			'locale'   => $locale_switched,
		);

		return true;
	}

	/**
	 * Undo the most recent switch_lang() call.
	 *
	 * @return void
	 */
	public static function restore_lang() {
		$entry = array_pop( self::$lang_stack );
		if ( ! $entry || empty( $entry['switched'] ) ) {
			return;
		}

		if ( function_exists( 'PLL' ) ) {
			PLL()->curlang = $entry['language'];
		}
		if ( ! empty( $entry['locale'] ) ) {
			restore_previous_locale();
		}
	}
}
