<?php
/**
 * The public-facing functionality of the plugin.
 *
 * Define public functionality.
 *
 * @since      1.0.0
 * @package    Shopify_Buybuttonwp
 * @subpackage Shopify_Buybuttonwp/public
 */
class Shopify_Buybuttonwp_Public {

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
		if( current_user_can('edit_posts') || current_user_can('edit_pages') ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'admin/css/shopify-buybuttonwp-admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( current_user_can('edit_posts') || current_user_can('edit_pages') ) {
			wp_enqueue_script( 'colorpicker-js', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/jquery.minicolors.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/'.$this->plugin_name.'-admin.js', array( 'jquery' ), $this->version, true );
		}
	}

	/**
	 * Themify Builder Compatibility script.
	 * 
	 * @access public
	 */
	public function themify_builder_editor_on_load() {
		?>
		<script type="text/javascript">
			var sbb_timeout;
			jQuery('body').on('builder_load_module_partial builder_toggle_frontend', function(){
				clearTimeout(sbb_timeout);
				sbb_timeout = setTimeout(function(){
					var onload_func = function(){
						jQuery('.js-shopify-buybuttonwp').find('iframe').remove();
						BuyButtonUIAdapter.init();
					};

					if ( 'object' !== typeof BuyButtonUIAdapter ) {
						var head = document.getElementsByTagName('head')[0],
							script = document.createElement('script');
						script.type = 'text/javascript';
						script.id = 'ShopifyEmbedScript';
						script.onload = onload_func;
						script.src = 'https://widgets.shopifyapps.com/assets/widgets/embed/client.js';
						head.appendChild(script);
					} else {
						onload_func();
					}
					
				}, 800);
			});
		</script>
		<?php
	}
}