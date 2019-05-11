<?php
/**
 * Plugin Name:       Shopify Buy Button for WP
 * Plugin URI:        https://themify.me/shopify-buy-button
 * Description:       This plugin allows you to insert Shopify Buy Button easier with the shortcode generator on the editor.
 * Version:           1.0.2
 * Author:            Themify
 * Author URI:        https://themify.me/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       shopify-buybuttonwp
 * Domain Path:       /languages
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
function activate_shopify_buybuttonwp() {
	// Add the transient to redirect
	set_transient( '_shopify_buybuttonwp_welcome_activation_redirect', true, 30 );
}
register_activation_hook( __FILE__, 'activate_shopify_buybuttonwp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-shopify-buybuttonwp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_shopify_buybuttonwp() {
	$plugin = new Shopify_Buybuttonwp( __FILE__ );
	$plugin->run();
}
add_filter( 'plugin_row_meta', 'shopify_buybuttonwp_plugin_meta', 10, 2 );
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'shopify_buybuttonwp_action_links' );
function shopify_buybuttonwp_plugin_meta( $links, $file ) {
	if ( plugin_basename( __FILE__ ) == $file ) {
		$row_meta = array(
		  'changelogs'    => '<a href="' . esc_url( 'https://themify.me/changelogs/' ) . basename( dirname( $file ) ) .'.txt" target="_blank" aria-label="' . esc_attr__( 'Plugin Changelogs', 'shopify-buybuttonwp' ) . '">' . esc_html__( 'View Changelogs', 'shopify-buybuttonwp' ) . '</a>'
		);
 
		return array_merge( $links, $row_meta );
	}
	return (array) $links;
}
function shopify_buybuttonwp_action_links( $links ) {
	if ( is_plugin_active( 'themify-updater/themify-updater.php' ) ) {
		$tlinks = array(
		 '<a href="' . admin_url( 'index.php?page=themify-license' ) . '">'.__('Themify License', 'shopify-buybuttonwp') .'</a>',
		 );
	} else {
		$tlinks = array(
		 '<a href="' . esc_url('https://themify.me/docs/themify-updater-documentation') . '">'. __('Themify Updater', 'shopify-buybuttonwp') .'</a>',
		 );
	}
	return array_merge( $links, $tlinks );
}
run_shopify_buybuttonwp();