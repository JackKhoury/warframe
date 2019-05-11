<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Shopify_Buybuttonwp
 * @subpackage Shopify_Buybuttonwp/includes
 */
class Shopify_Buybuttonwp {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Shopify_Buybuttonwp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The current path of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $path    The current path of the plugin.
	 */
	protected $path;

	/**
	 * Current plugin options.
	 * 
	 * @since 1.0.0
	 * @access protected
	 * @var array $options current plugin options.
	 */ 
	protected $options;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $path ) {
		$this->plugin_name = 'shopify-buybuttonwp';
		$this->path = $path;
		$this->version = $this->get_plugin_version();
		$this->options = $this->get_plugin_options();

		$this->load_dependencies();
		$this->set_locale();
		$this->define_shortcodes();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Shopify_Buybuttonwp_Loader. Orchestrates the hooks of the plugin.
	 * - Shopify_Buybuttonwp_i18n. Defines internationalization functionality.
	 * - Shopify_Buybuttonwp_Admin. Defines all hooks for the admin area.
	 * - Shopify_Buybuttonwp_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shopify-buybuttonwp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-shopify-buybuttonwp-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-shopify-buybuttonwp-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-shopify-buybuttonwp-public.php';

		$this->loader = new Shopify_Buybuttonwp_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Shopify_Buybuttonwp_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Shopify_Buybuttonwp_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register shortcodes.
	 * 
	 * @since 1.0.0
	 * @access private
	 */
	private function define_shortcodes() {
		add_shortcode( 'shopify_buy_button', array( $this, 'shopify_buy_button_sc' ) );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Shopify_Buybuttonwp_Admin( $this->get_plugin_name(), $this->get_version(), $this->get_path(), $this->get_plugin_options() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'media_buttons', $plugin_admin, 'media_buttons' );
		$this->loader->add_action( 'print_media_templates', $plugin_admin, 'print_media_templates' );

		$this->loader->add_action( 'init', $plugin_admin, 'plugin_updater_init' );

		// Admin options page
		$this->loader->add_action( 'admin_head', $plugin_admin, 'inline_styles' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'redirect_to_about_page' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Shopify_Buybuttonwp_Public( $this->get_plugin_name(), $this->get_version() );

		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		//$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'themify_builder_editor_on_load' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Shopify_Buybuttonwp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the path of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The path of the plugin.
	 */
	public function get_path() {
		return $this->path;
	}

	/**
	 * Get plugin version.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @return string
	 */
	public function get_plugin_version() {
        $plugin_data = get_file_data( $this->get_path(), array( 'ver' => 'Version' ) );
        return $plugin_data['ver'];
    }

    /**
     * Get plugin options.
     * 
     * @since 1.0.0
     * @access public
     * @return array
     */
    public function get_plugin_options() {
    	return get_option( $this->get_plugin_name() . '_appearance', apply_filters( 'shopify_buybuttonwp_default_appearance', array(
    		'cart_title' => esc_html__( 'Your Cart', 'shopify-buybuttonwp' ),
			'buy_button_text' => esc_html__( 'Buy Now', 'shopify-buybuttonwp' ),
			'buy_button_background_color' => '7db461',
			'buy_button_text_color' => 'ffffff',
			'container_background' => 'ffffff'
    	) ) );
    }

	/**
	 * Shopify shortcode.
	 * 
	 * @since 1.0.0
	 * @access public
	 * @param array $atts 
	 * @return string
	 */
	public function shopify_buy_button_sc( $atts ) {
		/*
		[shopify_buy_button shop="undefined" 
		embed_type="undefined" 
		handle="undefined" 
		has_image="true" 
		has_modal="false" 
		redirect_to="undefined" 
		buy_button_text="undefined" 
		button_background_color="undefined"
		button_text_color="undefined" 
		background_color="undefined"]
		*/
		$atts = shortcode_atts( array(
			'shop' => '',
			'embed_type' => '',
			'handle' => '',
			'has_image' => '',
			'has_modal' => '',
			'redirect_to' => '',
			'buy_button_text' => $this->options['buy_button_text'],
			'button_background_color' => $this->options['buy_button_background_color'],
			'button_text_color' => $this->options['buy_button_text_color'],
			'background_color' => $this->options['container_background'],
			'cart_title' => $this->options['cart_title']
		), $atts );

		ob_start();
		include plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/shopify-buybuttonwp-output.php';
		return ob_get_clean();
	}
}