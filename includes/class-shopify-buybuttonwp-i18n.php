<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for the plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 *
 * @package    Shopify_Buybuttonwp
 * @subpackage Shopify_Buybuttonwp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Shopify_Buybuttonwp
 * @subpackage Shopify_Buybuttonwp/includes
 */
class Shopify_Buybuttonwp_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'shopify-buybuttonwp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}