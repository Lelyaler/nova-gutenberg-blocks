<?php
/**
 * Plugin Name:       Nova Gutenberg Blocks
 * Plugin URI:        https://github.com/Lelyaler/nova-gutenberg-blocks
 * Description:       Next-generation interactive Gutenberg blocks for WordPress built with Block API v3, dynamic SSR rendering, accessible client-side hydration, and automated Schema.org JSON-LD rich snippets.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      8.0
 * Author:            Lelyaler
 * Author URI:        https://lelyaler.github.io/Portfolio/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       nova-blocks
 * Domain Path:       /languages
 */

namespace NovaBlocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NOVA_BLOCKS_VERSION', '1.0.0' );
define( 'NOVA_BLOCKS_FILE', __FILE__ );
define( 'NOVA_BLOCKS_DIR', plugin_dir_path( __FILE__ ) );
define( 'NOVA_BLOCKS_URL', plugin_dir_url( __FILE__ ) );

require_once NOVA_BLOCKS_DIR . 'includes/class-schema-generator.php';
require_once NOVA_BLOCKS_DIR . 'includes/class-block-registry.php';
require_once NOVA_BLOCKS_DIR . 'includes/class-plugin.php';

/**
 * Bootstrap the plugin singleton.
 */
function run_nova_blocks(): Plugin {
	return Plugin::instance();
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\\run_nova_blocks' );
