<div class="form-wrap">
	<h3><?php
		$options = get_option( 'tmsm_gravityforms_restaurant_settings' );
		echo sprintf(__( 'Close the restaurant %s on specific date and time', 'tmsm-gravityforms-restaurant' ), esc_html($options['restaurant_name']) ?? '');
		?></h3>

	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="tmsm_gravityforms_restaurant_form">

		<input type="hidden" name="action" value="tmsm_gravityforms_restaurant_form">
		<?php wp_nonce_field( 'tmsm_gravityforms_restaurant_form_nonce' ); ?>

		<div class="form-field">
			<label for="<?php echo $this->plugin_name; ?>-date"> <?php _e( 'Date', 'tmsm-gravityforms-restaurant' ); ?> </label>
			<input required id="<?php echo $this->plugin_name; ?>-date" type="date" name="date" value="" placeholder="<?php _e( 'Date',
				$this->plugin_name ); ?>" />
		</div>

		<div class="form-field">
			<fieldset>
				<legend><?php _e( 'Times', 'tmsm-gravityforms-restaurant' ); ?></legend>

				<?php
				$options    = get_option( 'tmsm_gravityforms_restaurant_settings' );
				$hour_slots = explode( PHP_EOL, $options['hour_slots'] );
				foreach ( $hour_slots as $hour_slot ) {
					echo '<label><input name="hour_slots[]" type="checkbox" value="' . esc_attr( $hour_slot ) . '"> ' . esc_html( $hour_slot )
					     . '</label>';
				}
				?>
			</fieldset>
		</div>

		<?php submit_button( __( 'Close restaurant for this date and times', 'tmsm-gravityforms-restaurant' ), 'primary', 'restaurant-closed-submit' ); ?>
	</form>
	<div id="nds_form_feedback"></div>

</div>
