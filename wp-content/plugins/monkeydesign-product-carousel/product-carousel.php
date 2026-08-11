<?php
/**
 * Plugin Name: MonkeyDesign Product Carousel for Elementor
 * Plugin URI:  https://monkeydesign.hu/en/products/elementor-addons/monkeydesign-product-carousel
 * Description: WooCommerce product carousel/slider widget for Elementor — powerful query engine, flexible card builder.
 * Version:     1.0.5
 * Author:      MonkeyDesign
 * Author URI:  https://monkeydesign.hu
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: monkeydesign-product-carousel
 * Domain Path: /languages
 * Requires PHP: 7.4
 * Requires at least: 6.0
 * Requires Plugins: woocommerce, elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Freemius: if premium plugin is activating alongside free, hand off.
if ( function_exists( 'mdpc_fs' ) ) {
	mdpc_fs()->set_basename( false, __FILE__ );
	return;
}

define( 'MDPC_VERSION',      '1.0.5' );
define( 'MDPC_PLUGIN_FILE',  __FILE__ );
define( 'MDPC_PLUGIN_DIR',   plugin_dir_path( __FILE__ ) );
define( 'MDPC_PLUGIN_URL',   plugin_dir_url( __FILE__ ) );
define( 'MDPC_FREEMIUS_ID',  '32205' );
define( 'MDPC_FREEMIUS_KEY', 'pk_bfb883cabce4ddf9645852a07f1c2' );

require_once MDPC_PLUGIN_DIR . 'includes/class-freemius.php';
mdpc_fs();
do_action( 'mdpc_fs_loaded' );

add_action( 'plugins_loaded', function (): void {
	if ( ! class_exists( '\Elementor\Plugin' ) || ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	require_once MDPC_PLUGIN_DIR . 'includes/class-plugin.php';
	MDPC_Plugin::instance();
} );
