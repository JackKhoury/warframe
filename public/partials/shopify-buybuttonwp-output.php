<?php
/**
 * Buy button output html.
 *
 * @since      1.0.0
 * @package    Shopify_Buybuttonwp
 * @subpackage Shopify_Buybuttonwp/public/partials
 */
$markup_string = <<<"EOT"
	<div class="%s" 
		data-shop="%s"
		data-embed_type="%s"
		data-%s_handle="%s"
		data-has_image="%s"
		data-display_size="compact"
		data-redirect_to="%s"
		data-buy_button_text="%s"
		data-buy_button_out_of_stock_text="%s"
		data-buy_button_product_unavailable_text="%s"
		data-button_background_color="%s"
		data-button_text_color="%s"
		data-background_color="%s"
		data-product_modal="%s"
		data-product_title_color="000000"
		data-next_page_button_text="%s">
	</div>
	<script type="text/javascript"> document.getElementById('ShopifyEmbedScript') || document.write('<script type="text/javascript" src="https://widgets.shopifyapps.com/assets/widgets/embed/client.js" id="ShopifyEmbedScript"><\/script>'); </script>
EOT;

	$cart_markup = <<<"EOT"
	<div
		data-shop="%s"
		data-accent_color="000000"
		data-button_background_color="%s"
		data-button_text_color="%s"
		data-background_color="%s"
		data-cart_button_text="%s"
		data-cart_title="%s"
		data-cart_total_text="%s"
		data-checkout_button_text="%s"
		data-discount_notice_text="%s"
		data-embed_type="cart"
		data-empty_cart_text="%s"
		data-sticky="true"
		data-text_color="000000">
	</div>
EOT;

$final_markup = sprintf($markup_string,
	'shopify-buybuttonwp js-shopify-buybuttonwp',
	$atts['shop'],
	$atts['embed_type'],
	$atts['embed_type'],
	$atts['handle'],
	$atts['has_image'],
	$atts['redirect_to'],
	$atts['buy_button_text'],
	esc_html__( 'Out of Stock', 'shopify-buybuttonwp' ),
	esc_html__( 'Unavailable', 'shopify-buybuttonwp' ),
	$atts['button_background_color'],
	$atts['button_text_color'],
	$atts['background_color'],
	$atts['has_modal'],
	esc_html__( 'Next Page', 'shopify-buybuttonwp' )).
	sprintf($cart_markup,
		$atts['shop'],
		$atts['button_background_color'],
		$atts['button_text_color'],
		$atts['background_color'],
		esc_html__( 'Cart', 'shopify-buybuttonwp' ),
		$atts['cart_title'],
		esc_html__( 'Total', 'shopify-buybuttonwp' ),
		esc_html__( 'Checkout', 'shopify-buybuttonwp' ),
		esc_html__( 'Shipping and discount codes are added at checkout.', 'shopify-buybuttonwp' ),
		esc_html__( 'Your cart is empty.', 'shopify-buybuttonwp' ));
echo $final_markup;