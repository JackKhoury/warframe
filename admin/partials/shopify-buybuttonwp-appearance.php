<?php
$field = $this->plugin_name . '_appearance';
$options = $this->options;
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Appearance', 'shopify-buybuttonwp' ); ?></h1>

	<form action="options.php" method="post">
		<?php
			settings_fields( $this->plugin_name . '_appearance' );
			do_settings_sections( $this->plugin_name . '_appearance' ); ?>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Cart Title', 'shopify-buybuttonwp' ); ?></th>
					<td><input type="text" name="<?php echo esc_attr( $field . '[cart_title]' );?>" value="<?php echo esc_attr( $options['cart_title'] ); ?>"></td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Buy Button Text', 'shopify-buybuttonwp' );?></th>
					<td><input type="text" name="<?php echo esc_attr( $field . '[buy_button_text]' );?>" value="<?php echo esc_attr( $options['buy_button_text'] ); ?>"></td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Button Background', 'shopify-buybuttonwp' );?></th>
					<td><input type="text" name="<?php echo esc_attr( $field . '[buy_button_background_color]' );?>" value="<?php echo esc_attr( $options['buy_button_background_color'] ); ?>" class="shopify-buybuttonwp-minicolors"></td>
				</tr>
				
				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Button Text Color', 'shopify-buybuttonwp' );?></th>
					<td><input type="text" name="<?php echo esc_attr( $field . '[buy_button_text_color]' );?>" value="<?php echo esc_attr( $options['buy_button_text_color'] ); ?>" class="shopify-buybuttonwp-minicolors"></td>
				</tr>

				<tr valign="top">
					<th scope="row"><?php esc_html_e( 'Container Background', 'shopify-buybuttonwp' );?></th>
					<td><input type="text" name="<?php echo esc_attr( $field . '[container_background]' );?>" value="<?php echo esc_attr( $options['container_background'] ); ?>" class="shopify-buybuttonwp-minicolors"></td>
				</tr>

			</table>
		
		<?php submit_button(); ?>
	</form>
</div>