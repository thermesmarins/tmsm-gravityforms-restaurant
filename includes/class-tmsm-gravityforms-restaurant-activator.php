<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.github.com/nicomollet/
 * @since      1.0.0
 *
 * @package    Tmsm_Gravityforms_Restaurant
 * @subpackage Tmsm_Gravityforms_Restaurant/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Tmsm_Gravityforms_Restaurant
 * @subpackage Tmsm_Gravityforms_Restaurant/includes
 * @author     Nico Mollet <nico.mollet@gmail.com>
 */
class Tmsm_Gravityforms_Restaurant_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		/**
		 * Custom Post Types
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-tmsm-gravityforms-restaurant-posttypes.php';
		$plugin_posttypes = new Tmsm_Gravityforms_Restaurant_Posttypes();

		/**
		 * The problem with the initial activation code is that when the activation hook runs, it's after the init hook has run,
		 * so hooking into init from the activation hook won't do anything.
		 * You don't need to register the CPT within the activation function unless you need rewrite rules to be added
		 * via flush_rewrite_rules() on activation. In that case, you'll want to register the CPT normally, via the
		 * loader on the init hook, and also re-register it within the activation function and
		 * call flush_rewrite_rules() to add the CPT rewrite rules.
		 *
		 * @link https://github.com/DevinVinson/WordPress-Plugin-Boilerplate/issues/261
		 */
		$plugin_posttypes->create_custom_post_type();

	}

}
