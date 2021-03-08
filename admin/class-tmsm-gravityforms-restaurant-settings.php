<?php

/**
 * The settings of the plugin.
 *
 * @link https://github.com/karannagupta/nds-admin-form-demo
 * @package    Tmsm_Gravityforms_Restaurant
 * @subpackage Tmsm_Gravityforms_Restaurant/admin
 * @author     Nico Mollet <nico.mollet@gmail.com>
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class Tmsm_Gravityforms_Restaurant_Settings {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * This function introduces the theme options into the 'Appearance' menu and into a top-level
	 * 'WPPB Demo' menu.
	 */
	public function setup_plugin_options_menu() {

		// Create a special menu item
		add_menu_page(
			__( 'Restaurant', 'tmsm-gravityforms-restaurant' ),  // The title to be displayed in the browser window for this page.
			__( 'Restaurant', 'tmsm-gravityforms-restaurant' ),  // The text to be displayed for this menu item
			'manage_restaurant',                           // Which type of users can see this menu item
			'tmsm-gravityforms-restaurant',            // The unique ID - that is, the slug - for this menu item
			array( $this, 'render_settings_page_content' ),     // The name of the function to call when rendering this menu's page
			'dashicons-calendar-alt'
		);

	}

	/**
	 * Provides default values for the Input Options.
	 *
	 * @return array
	 */
	public function default_input_options() {

		$defaults = array(
			'input_example'		=>	'default input example',
			'textarea_example'	=>	'',
			'checkbox_example'	=>	'',
			'radio_example'		=>	'2',
			'time_options'		=>	'default'
		);

		return $defaults;

	}

	/**
	 * Provides default values for the Input Options.
	 *
	 * @return array
	 */
	public function default_settings() {

		$defaults = array(
			'hour_slots'		=>	'1200
1300
1400
1900
2000
2100',
		);

		return $defaults;

	}

	/**
	 * Renders a simple page to display for the theme menu defined above.
	 *
	 * @param $active_tab string
	 */
	public function render_settings_page_content( $active_tab = '' ) {
		?>
		<div class="wrap">

			<h2><?php _e( 'Close the restaurant', 'tmsm-gravityforms-restaurant' ); ?></h2>
			<?php settings_errors(); ?>

			<?php
			if( isset( $_GET[ 'tab' ] ) ) {
				$active_tab = sanitize_text_field( wp_unslash($_GET[ 'tab' ]) );
			} else {
				$active_tab = 'form';
			} ?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=tmsm-gravityforms-restaurant&tab=form" class="nav-tab <?php echo $active_tab === 'form' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Add Dates', 'tmsm-gravityforms-restaurant' ); ?></a>

				<a href="?page=tmsm-gravityforms-restaurant&tab=roomservice" class="nav-tab <?php echo $active_tab === 'roomservice' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Room Service', 'tmsm-gravityforms-restaurant' ); ?></a>

				<a href="?page=tmsm-gravityforms-restaurant&tab=settings" class="nav-tab <?php echo $active_tab === 'settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Settings', 'tmsm-gravityforms-restaurant' ); ?></a>

			</h2>


			<?php if( $active_tab == 'settings' ) { ?>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'tmsm_gravityforms_restaurant_settings' );
				do_settings_sections( 'tmsm_gravityforms_restaurant_settings' );
				submit_button();
				?>
			</form>
			<?php } elseif( $active_tab == 'roomservice' ) { ?>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'tmsm_gravityforms_restaurant_roomservice' );
				do_settings_sections( 'tmsm_gravityforms_restaurant_roomservice' );
				submit_button();
				?>
			</form>
			<?php } else { ?>

				<div id="col-container">
					<div id="col-left">
					<div class="col-wrap">
						<?php include_once( 'partials/tmsm-gravityforms-restaurant-admin-form.php' ); ?>
					</div>
					</div>
					<div id="col-right">
						<div class="col-wrap">
							<?php tmsm_gravityforms_restaurant_list_table_render(); ?>
						</div>

					</div>
				</div>
			<?php
			} ?>



		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * Settings Page callback
	 *
	 */
	public function settings_callback() {
		$options = get_option('tmsm_gravityforms_restaurant_settings');
		echo '<p>' . __( 'Customize your settings', 'tmsm-gravityforms-restaurant' ) . '</p>';
	} // end general_options_callback



	/**
	 * Initializes settings
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_settings() {
		//delete_option('tmsm_gravityforms_restaurant_settings');
		if( false == get_option( 'tmsm_gravityforms_restaurant_settings' ) ) {
			$default_array = $this->default_settings();
			update_option( 'tmsm_gravityforms_restaurant_settings', $default_array );
		} // end if

		add_settings_section(
			'settings',
			__( 'Settings', 'tmsm-gravityforms-restaurant' ),
			array( $this, 'settings_callback'),
			'tmsm_gravityforms_restaurant_settings'
		);

		add_settings_field(
			'hour_slots',
			__( 'Hour Slots', 'tmsm-gravityforms-restaurant' ),
			array( $this, 'hourslots_callback'),
			'tmsm_gravityforms_restaurant_settings',
			'settings'
		);

		register_setting(
			'tmsm_gravityforms_restaurant_settings',
			'tmsm_gravityforms_restaurant_settings',
			array( $this, 'validate_settings')
		);

		add_settings_section(
			'roomservice',
			__( 'RoomService', 'tmsm-gravityforms-restaurant' ),
			null,
			'tmsm_gravityforms_restaurant_roomservice'
		);

		add_settings_field(
			'menu',
			__( 'Menu', 'tmsm-gravityforms-restaurant' ),
			array( $this, 'menu_callback'),
			'tmsm_gravityforms_restaurant_roomservice',
			'roomservice'
		);

		register_setting(
			'tmsm_gravityforms_restaurant_roomservice',
			'tmsm_gravityforms_restaurant_roomservice',
			array( $this, 'validate_settings')
		);

	}

	public function hourslots_callback() {

		$options = get_option( 'tmsm_gravityforms_restaurant_settings' );

		// Render the output
		echo '<textarea name="tmsm_gravityforms_restaurant_settings[hour_slots]" rows="15" cols="50">' . esc_html($options['hour_slots'] ) . '</textarea>';

	} // end hourslots_callback


	public function menu_callback() {

		$options = get_option( 'tmsm_gravityforms_restaurant_roomservice' );

		// Render the output
		echo '<textarea name="tmsm_gravityforms_restaurant_roomservice[menu]" rows="15" cols="50">' . esc_html($options['menu'] ) . '</textarea>';

	} // end menu_callback




	public function validate_settings( $input ) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if( isset( $input[$key] ) ) {

				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[$key] = sanitize_textarea_field( $input[ $key ] );

			} // end if

		} // end foreach

		return $output;

	} // end validate_settings




}