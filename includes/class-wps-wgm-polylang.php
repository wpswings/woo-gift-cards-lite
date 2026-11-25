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
