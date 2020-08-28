<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.github.com/nicomollet/
 * @since      1.0.0
 *
 * @package    Tmsm_Gravityforms_Restaurant
 * @subpackage Tmsm_Gravityforms_Restaurant/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Tmsm_Gravityforms_Restaurant
 * @subpackage Tmsm_Gravityforms_Restaurant/public
 * @author     Nico Mollet <nico.mollet@gmail.com>
 */
class Tmsm_Gravityforms_Restaurant_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tmsm-gravityforms-restaurant-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tmsm-gravityforms-restaurant-public.js', array( 'jquery' ), $this->version, true );

	}


	/**
	 * Populate Hour Slots in tmsm-gravityforms-restaurant-hourslots field
	 *
	 * @param array $form
	 *
	 * @return mixed
	 */
	function populate_hourslots( $form ) {

		if(!is_admin()){

			$hourslots_field_id = null;
			$date_field_id = null;
			$date_format = __('mm/dd/yyyy', 'tmsm-gravityforms-restaurant');

			if ( strpos( 'tmsm-gravityforms-restaurant-form', $form['cssClass'] ) !== false ) {
				foreach ( $form['fields'] as &$field ) {

					if ( strpos( $field->cssClass, 'tmsm-gravityforms-restaurant-date' ) !== false ) {

						$date_field_id = $field->id;

					}

					if ( strpos( $field->cssClass, 'tmsm-gravityforms-restaurant-hourslots' ) !== false ) {

						$hourslots_field_id = $field->id;

						$options = get_option( 'tmsm_gravityforms_restaurant_settings' );

						$hour_slots = esc_html( $options['hour_slots'] );
						$hour_slots_array = explode( PHP_EOL, $hour_slots );

						$choices = array();

						foreach ( $hour_slots_array as $hour_slot ) {
							$hour_slot = trim($hour_slot);
							if(strlen($hour_slot) === 4){
								$hour = substr($hour_slot, 0, 2);
								$time = substr($hour_slot, 2, 2);
								$date = date('Y-m-d') . ' '.$hour.':'.$time.':00';
								$choices[] = array( 'text' => mysql2date( __('g:i A', 'tmsm-gravityforms-restaurant'), $date ), 'value' => $hour_slot );
							}

						}

						$field->placeholder     = __('Pick an Hour Slot', 'tmsm-gravityforms-restaurant');
						$field->choices     = $choices;
					}

					if ( strpos( $field->cssClass, 'tmsm-gravityforms-restaurant-result' ) !== false ) {
						$field->content = self::is_restaurant_available($date_field_id, $hourslots_field_id, $date_format);
						$field->defaultValue = self::is_restaurant_available($date_field_id, $hourslots_field_id, $date_format);
					}

				}

			}
		}

		return $form;
	}


	/**
	 * Is restaurant available
	 *
	 * @param $date_field_id string
	 * @param $hourslots_field_id string
	 * @param $date_format string
	 *
	 * @return string
	 */
	function is_restaurant_available($date_field_id, $hourslots_field_id, $date_format){

		$date_picked = null;
		$hourslot_picked = null;
		$hour_picked = null;
		$time_picked = null;
		$output = __('The form has not been correctly filled.', 'tmsm-gravityforms-restaurant');

		if(!empty($date_field_id)){
			$date_picked = rgpost('input_'.$date_field_id);

			$date_picked = preg_replace('/[a-z]/i', '', $date_picked);
			$date_picked = str_replace(' ', '', $date_picked);

		}
		if(!empty($hourslots_field_id)){
			$hourslot_picked = rgpost('input_'.$hourslots_field_id);
			$hour_picked = substr($hourslot_picked, 0, 2);
			$time_picked = substr($hourslot_picked, 2, 2);
		}

		$available = true;

		$day = null;
		$month = null;
		$year = null;
		if(!empty($date_format) && !empty($date_picked)){
			$day_pos = strpos($date_format, 'd');
			$day = substr($date_picked, $day_pos, 2);

			$month_pos = strpos($date_format, 'm');
			$month = substr($date_picked, $month_pos, 2);

			$year_pos = strpos($date_format, 'y');
			$year = substr($date_picked, $year_pos, 4);

		}

		$options = get_option( 'tmsm_gravityforms_restaurant_settings' );
		$hour_slots = esc_html( $options['hour_slots'] );
		$hour_slots_array = explode( PHP_EOL, $hour_slots );
		$hour_slots_array = array_map('trim', $hour_slots_array);

		if(!empty($day) && !empty($month) && !empty($year) && !empty($hourslot_picked)){
			$restaurantclosed_posts = get_posts( [
				'posts_per_page' => - 1,
				'post_type'      => 'restaurant-closed',
				'post_status'    => [ 'publish', 'future' ],
				'day'    => $day,
				'monthnum'    => $month,
				'year'    => $year,
			] );

			foreach ($restaurantclosed_posts as $restaurantclosed_post){

				if(strpos($restaurantclosed_post->post_content, $hourslot_picked) !== false){

					$available = false;

					$hour_slots_closed_array = explode( ', ', $restaurantclosed_post->post_content );
					$hour_slots_closed_array = array_map('trim', $hour_slots_closed_array);
					$choices = array();

					$hour_slots_diff_array = array_diff($hour_slots_array, $hour_slots_closed_array );

					// Other Hour Slots Closed of the same day
					foreach ( $hour_slots_diff_array as $hour_slot ) {
						if(strlen($hour_slot) === 4 && $hour_slot !== $hourslot_picked){
							$hour = substr($hour_slot, 0, 2);
							$time = substr($hour_slot, 2, 2);
							$date = date('Y-m-d') . ' '.$hour.':'.$time.':00';
							$choices[] = mysql2date( __('g:i A', 'tmsm-gravityforms-restaurant'), $date );
						}
					}

					$output = '<div class="tmsm-gravityforms-restaurant-full"><h3>'.__('Restaurant is full', 'tmsm-gravityforms-restaurant').'</h3>';
					$output .= '<p>';
					$output .= __('Unfortunately, the hour slot you requested is not available.', 'tmsm-gravityforms-restaurant');
					$output .= '<br>';

					if(count($choices) > 0){
						$output .= sprintf(__('Otherwise, there are other slots available: %s', 'tmsm-gravityforms-restaurant'), join(', ', $choices));
					}
					else{
						$output .= __('No other slots are available this day, please pick another day.', 'tmsm-gravityforms-restaurant');
					}
					$output .= '</p></div>';
				}
			}
		}

		if($available === true && $hour_picked && $time_picked){
			$output = '<div class="tmsm-gravityforms-restaurant-available"><h3>'.__('Available', 'tmsm-gravityforms-restaurant').'</h3>';
			$output .= '<p>';
			$date = date('Y-m-d') . ' '.$hour_picked.':'.$time_picked.':00';
			$output .= sprintf( __('The hour slot %s at %s is available.', 'tmsm-gravityforms-restaurant'), esc_html($date_picked), mysql2date( __('g:i A', 'tmsm-gravityforms-restaurant'), $date ));
			$output .= '</p></div>';
		}

		return $output;
	}

}
