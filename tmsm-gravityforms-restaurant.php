<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.github.com/nicomollet/
 * @since             1.0.0
 * @package           Tmsm_Gravityforms_Restaurant
 *
 * @wordpress-plugin
 * Plugin Name:       TMSM Gravity Forms Restaurant
 * Plugin URI:        https://www.github.com/thermesmarins/tmsm-gravityforms-restaurant/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.4
 * Author:            Nico Mollet
 * Author URI:        https://www.github.com/nicomollet/
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       tmsm-gravityforms-restaurant
 * Domain Path:       /languages
 * Github Plugin URI: https://github.com/thermesmarins/tmsm-gravityforms-restaurant/
 * Github Branch:     master
 * Requires PHP:      5.6
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TMSM_GRAVITYFORMS_RESTAURANT_VERSION', '1.0.4' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tmsm-gravityforms-restaurant-activator.php
 */
function activate_tmsm_gravityforms_restaurant() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-gravityforms-restaurant-activator.php';
	Tmsm_Gravityforms_Restaurant_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tmsm-gravityforms-restaurant-deactivator.php
 */
function deactivate_tmsm_gravityforms_restaurant() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-gravityforms-restaurant-deactivator.php';
	Tmsm_Gravityforms_Restaurant_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_tmsm_gravityforms_restaurant' );
register_deactivation_hook( __FILE__, 'deactivate_tmsm_gravityforms_restaurant' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-tmsm-gravityforms-restaurant.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tmsm_gravityforms_restaurant() {

	$plugin = new Tmsm_Gravityforms_Restaurant();
	$plugin->run();

}
run_tmsm_gravityforms_restaurant();
