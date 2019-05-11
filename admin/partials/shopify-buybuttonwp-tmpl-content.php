<?php
/**
 * Provide javascript template for Shopify Buy Button
 *
 * @since      1.0.0
 *
 * @package    Shopify_Buybuttonwp
 * @subpackage Shopify_Buybuttonwp/admin/partials
 */
?>
<script type="text/html" id="tmpl-shopify-buybuttonwp-modal-content">
	<div class="shopify-picker-iframe">
		<iframe width='100%' height='590' src='https://widgets.shopifyapps.com/embed_admin/embed_collections/picker/?ref=themify'></iframe>
	</div>
	<div id="themify_sbb_options_appearance" class="shopify-shortcode-generator themify_sbb_module_settings sbb_appearance" style="display:none;">
		<div class="themify_sbb_appearance_inner">

			<form id="ShopifyCustomizationContainer" action="#">
				<input type="hidden" name="sbb_resource_type" value="" id="sbb_resource_type">
				<input type="hidden" name="sbb_resource_handle" value="" id="sbb_resource_handle">
				<input type="hidden" name="sbb_shop_domain" value="" id="sbb_shop_domain">

				<div class="themify_sbb_appearance_field">
					<div class="themify_sbb_appearance_label">
						<?php esc_html_e( 'Display', 'shopify-buybuttonwp' ); ?>
					</div>
					<div class="themify_sbb_appearance_input">
						<label for="sbb_has_image">
						  <input id="sbb_has_image" type="radio" value="1" checked="checked" name="sbb_has_image">
							<?php esc_html_e( 'Product image, price and button', 'shopify-buybuttonwp' );?>
						</label>
						
						<br>

						<label for="sbb_no_image">
							<input id="sbb_no_image" type="radio" value="0" name="sbb_has_image">
							<?php esc_html_e( 'Buy button only', 'shopify-buybuttonwp' );?>
						</label>
					</div>								
				</div>

				<div class="themify_sbb_appearance_field">
					<div class="themify_sbb_appearance_label">
						<?php esc_html_e( 'Button color', 'shopify-buybuttonwp' ); ?>
					</div>
					<div class="themify_sbb_appearance_input">
						<input id="sbb_button_background_color" class="themify_sbb_ColorSelectInput" type="text" name="sbb_button_background_color" size="7">
					</div>								
				</div>

				<div class="themify_sbb_appearance_field">
					<div class="themify_sbb_appearance_label">
						<?php esc_html_e( 'Button text color', 'shopify-buybuttonwp' ); ?>
					</div>
					<div class="themify_sbb_appearance_input">
						<input id="sbb_button_text_color" class="themify_sbb_ColorSelectInput" type="text" name="sbb_button_text_color" size="7">
					</div>								
				</div>

				<div class="themify_sbb_appearance_field">
					<div class="themify_sbb_appearance_label">
						<?php esc_html_e( 'Background', 'shopify-buybuttonwp' ); ?>
					</div>
					<div class="themify_sbb_appearance_input">
						<input id="sbb_background_color" class="themify_sbb_ColorSelectInput" type="text" name="sbb_background_color" size="7">
					</div>								
				</div>

				<div class="themify_sbb_appearance_field">
					<label for="sbb_redirect_to" class="themify_sbb_appearance_label">
						<?php esc_html_e( 'Button Link To', 'shopify-buybuttonwp' );?>
					</label>
					<div class="themify_sbb_appearance_input">
						<select id="sbb_redirect_to" name="sbb_redirect_to">
							<option selected="selected" value="checkout"><?php esc_html_e( 'Checkout', 'shopify-buybuttonwp' );?></option>
							<option value="cart"><?php esc_html_e( 'Cart', 'shopify-buybuttonwp' );?></option>
							<option value="modal"><?php esc_html_e( 'Product Modal', 'shopify-buybuttonwp' );?></option>
						</select>
					</div>								
				</div>

				<div class="themify_sbb_appearance_field">
					<label for="sbb_buy_button_text" class="themify_sbb_appearance_label">
						<?php esc_html_e( 'Button Text', 'shopify-buybuttonwp' );?>
					</label>
					<div class="themify_sbb_appearance_input">
						<input value="" id="sbb_buy_button_text" class="themify_sbb_CartInput" type="text" name="sbb_buy_button_text">
						<p><?php echo sprintf( __( 'Leave options blank to have global settings from <a href="%s" target="_blank">Appearance</a>', 'shopify-buybuttonwp' ), admin_url( 'admin.php?page=shopify-buybuttonwp-appearance' ) ); ?></p>
					</div>								
				</div>

				<div class="themify_sbb_insert_button_field">
					<a href="#" class="themify_sbb_back_select js--select-shopify-product">
						<?php esc_html_e( 'Back to select', 'shopify-buybuttonwp' );?>
					</a>
					<button class="button button-primary js--shopify-generate-shortcode-btn themify_sbb_insert_button"><?php esc_html_e( 'Insert Shortcode', 'shopify-buybuttonwp' );?></button>
				</div>

			</form>

		</div>
	</div>
</script>
<script type="text/html" id="tmpl-shopify-buybuttonwp-modal-title">
	<div class="media-modal-title">
		<h1><?php esc_html_e( 'Shopify Buy Button', 'shopify-buybuttonwp' ); ?></h1>
	</div>
</script>