<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.github.com/nicomollet/
 * @since      1.0.0
 *
 * @package    Tmsm_Gravityforms_Restaurant
 * @subpackage Tmsm_Gravityforms_Restaurant/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tmsm_Gravityforms_Restaurant
 * @subpackage Tmsm_Gravityforms_Restaurant/admin
 * @author     Nico Mollet <nico.mollet@gmail.com>
 */
class Tmsm_Gravityforms_Restaurant_Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tmsm-gravityforms-restaurant-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tmsm-gravityforms-restaurant-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 *
	 * @since    1.0.0
	 */
	public function the_form_response() {
		if( isset( $_POST['restaurant-closed-submit'] ) && wp_verify_nonce( $_POST['_wpnonce'], 'tmsm_gravityforms_restaurant_form_nonce') ) {
			/*$nds_user_meta_key = sanitize_key( $_POST['nds']['user_meta_key'] );
			$nds_user_meta_value = sanitize_text_field( $_POST['nds']['user_meta_value'] );
			$nds_user =  get_user_by( 'login',  $_POST['nds']['user_select'] );
			$nds_user_id = absint( $nds_user->ID ) ;*/

			$date = isset($_POST['date']) ? sanitize_text_field( wp_unslash( $_POST['date'] ) ) : null;
			$hour_slots_posted = isset($_POST['hour_slots']) ? $_POST['hour_slots'] : array();
			$hour_slots_array = array();
			if ( is_array( $hour_slots_posted ) ) {
				foreach ( $hour_slots_posted as $hour_slot_key => $hour_slot_value ) {
					$hour_slots_array[$hour_slot_key] = sanitize_text_field( wp_unslash( $hour_slot_value ) );
				}
			}
			$hour_slots = join(', ', $hour_slots_array);

			if(!empty($date) && !empty($hour_slots)){
				$insert_post = wp_insert_post([
					'post_title' => $date,
					'post_name' => 'restaurant-closed-'.$date,
					'post_date' => $date,
					'post_content' => $hour_slots,
					'post_status' => 'publish',
					'post_type' => 'restaurant-closed',
				]);

			}
			//add_user_meta( $nds_user_id, $nds_user_meta_key, $nds_user_meta_value );

			$admin_notice = "success";
			$this->custom_redirect( $admin_notice, $_POST );
			exit;
		}
		else {
			wp_die( __( 'Invalid nonce specified', $this->plugin_name ), __( 'Error', $this->plugin_name ), array(
				'response' 	=> 403,
				'back_link' => 'admin.php?page=' . $this->plugin_name,
			) );
		}
	}
	/**
	 * Redirect
	 *
	 * @since    1.0.0
	 */
	public function custom_redirect( $admin_notice, $response ) {
		wp_redirect( esc_url_raw( add_query_arg( array(
			'tmsm_gravityforms_restaurant_form_notice' => $admin_notice,
			//'tmsm_gravityforms_restaurant_form_response' => $response,
		),
			admin_url('admin.php?page='. $this->plugin_name )
		) ) );
	}
	/**
	 * Print Admin Notices
	 *
	 * @since    1.0.0
	 */
	public function print_plugin_admin_notices() {
		if ( isset( $_REQUEST['tmsm_gravityforms_restaurant_form_notice'] ) ) {
			if( $_REQUEST['tmsm_gravityforms_restaurant_form_notice'] === "success") {
				$html =	'<div class="notice notice-success is-dismissible"> 
							<p><strong>'.__('Item was added', 'tmsm-gravityforms-restaurant').'</strong></p>';
				//$html .= '<pre>' . htmlspecialchars( print_r( $_REQUEST['tmsm_gravityforms_restaurant_form_response'], true) ) . '</pre>';
				$html .= '</div>';
				echo $html;
			}

		}
		else {
			return;
		}
	}
}
