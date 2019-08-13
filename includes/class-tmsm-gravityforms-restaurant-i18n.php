<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.github.com/nicomollet/
 * @since      1.0.0
 *
 * @package    Tmsm_Gravityforms_Restaurant
 * @subpackage Tmsm_Gravityforms_Restaurant/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Tmsm_Gravityforms_Restaurant
 * @subpackage Tmsm_Gravityforms_Restaurant/includes
 * @author     Nico Mollet <nico.mollet@gmail.com>
 */
class Tmsm_Gravityforms_Restaurant_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'tmsm-gravityforms-restaurant',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
