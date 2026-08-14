<?php
/**
 * PHPUnit Bootstrap File
 *
 * @package Woo_Gift_Cards_Lite
 */

// Define test environment constants
define( 'WGC_LITE_PLUGIN_DIR', dirname( __DIR__ ) );
define( 'WGC_LITE_TESTS_DIR', __DIR__ );

// Load WordPress test environment if available
if ( file_exists( '/tmp/wordpress-tests-lib/includes/functions.php' ) ) {
	require_once '/tmp/wordpress-tests-lib/includes/functions.php';

	function _manually_load_plugin() {
		require WGC_LITE_PLUGIN_DIR . '/woocommerce_gift_cards_lite.php';
	}
	tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

	require '/tmp/wordpress-tests-lib/includes/bootstrap.php';
} else {
	// Minimal WordPress mock for basic tests
	if ( ! defined( 'ABSPATH' ) ) {
		define( 'ABSPATH', '/tmp/' );
	}

	// Mock WordPress functions
	if ( ! function_exists( 'esc_html' ) ) {
		function esc_html( $text ) {
			return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
		}
	}

	if ( ! function_exists( 'sanitize_text_field' ) ) {
		function sanitize_text_field( $str ) {
			return strip_tags( $str );
		}
	}

	if ( ! function_exists( 'absint' ) ) {
		function absint( $maybeint ) {
			return abs( intval( $maybeint ) );
		}
	}

	if ( ! function_exists( 'intval' ) ) {
		// PHP's native intval should exist, but just in case
	}

	if ( ! class_exists( 'wpdb' ) ) {
		class wpdb {
			public $prefix = 'wp_';
			public $posts = 'wp_posts';
			public $postmeta = 'wp_postmeta';

			public function prepare( $query, ...$args ) {
				// Simple prepare simulation
				foreach ( $args as $arg ) {
					$query = preg_replace( '/%[sd]/', $this->esc_like( $arg ), $query, 1 );
				}
				return $query;
			}

			public function get_results( $query, $output = OBJECT ) {
				return array();
			}

			private function esc_like( $text ) {
				return addcslashes( $text, '_%\\' );
			}
		}
	}

	// Define constants
	if ( ! defined( 'OBJECT' ) ) {
		define( 'OBJECT', 'OBJECT' );
	}
	if ( ! defined( 'ARRAY_A' ) ) {
		define( 'ARRAY_A', 'ARRAY_A' );
	}
}
