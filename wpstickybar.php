<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://a17lab.com
 * @since             1.3.0
 * @package           Wpstickybar
 *
 * @wordpress-plugin
 * Plugin Name:       WpStickyBar - Sticky Bar, Sticky Header
 * Plugin URI:        https://wpstickybar.com
 * Description:       WP Sticky Bar help you launch a hero bars that nudge visitors into action. It makes a hero bar that always stays on top of your pages.
 * Version:           2.1.0
 * Author:            A17Lab
 * Author URI:        https://a17lab.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpstickybar
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 2.1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPSTICKYBAR_VERSION', '2.1.0' );

define( 'WPSTICKYBAR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpstickybar-activator.php
 */
function activate_wpstickybar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpstickybar-activator.php';
	Wpstickybar_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpstickybar-deactivator.php
 */
function deactivate_wpstickybar() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpstickybar-deactivator.php';
	Wpstickybar_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpstickybar' );
register_deactivation_hook( __FILE__, 'deactivate_wpstickybar' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpstickybar.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpstickybar() {

	$plugin = new Wpstickybar();
	$plugin->run();

}
run_wpstickybar();
