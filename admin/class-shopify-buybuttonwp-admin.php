<?php
/**
 * The admin-specific functionality.
 *
 * Admin Script hook initialize and filter.
 *
 * @package    Shopify_Buybuttonwp
 * @subpackage Shopify_Buybuttonwp/admin
 */
class Shopify_Buybuttonwp_Admin {

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
	 * The path of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $path    The current path of this plugin.
	 */
	private $path;

	/**
	 * The valid admin page slugs.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    $valid_page_slugs    The valid admin page.
	 */
	private $valid_page_slugs = array();

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $path, $options ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->path = $path;
		$this->options = $options;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook_suffix ) {
		if ( ( ( current_user_can('edit_posts') || current_user_can('edit_pages') ) && isset( get_current_screen()->id ) && get_current_screen()->base == 'post' ) || ( isset( $_GET['page'] ) && in_array( $_GET['page'], $this->valid_page_slugs ) ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/shopify-buybuttonwp-admin.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Add Inline styles.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function inline_styles() {
		echo '<style>.toplevel_page_shopify-buybuttonwp .wp-menu-image img { width: 18px; }</style>';	
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if ( ( ( current_user_can('edit_posts') || current_user_can('edit_pages') ) && isset( get_current_screen()->id ) && get_current_screen()->base == 'post' ) || ( isset( $_GET['page'] ) && in_array( $_GET['page'], $this->valid_page_slugs ) ) ) {
			wp_enqueue_media();
			wp_enqueue_script( 'colorpicker-js', plugin_dir_url( __FILE__ ) . 'js/jquery.minicolors.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/shopify-buybuttonwp-admin.js', array( 'jquery' ), $this->version, true );
		}
	}

	/**
	 * Add a button next to media library.
	 * 
	 * @since 1.0.0
	 */
	public function media_buttons( $editor_id ) {
		if ( current_user_can('edit_posts') || current_user_can('edit_pages') ) {
			echo sprintf( '<button class="button js--open-shopify-buy-btn" data-editor="%s">%s</button>', $editor_id, esc_html__( 'Add Product', 'shopify-buybuttonwp' ) );
		}
	}

	/**
	 * Include the javascript template.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function print_media_templates() {
		if ( current_user_can('edit_posts') || current_user_can('edit_pages') ) {
			include_once( sprintf( 'partials/%s-tmpl-content.php', 'shopify-buybuttonwp' ) );
		}
	}

	/**
	 * Plugin updater init.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function plugin_updater_init() {
		if ( is_admin() && current_user_can( 'update_plugins' ) ) {
			if ( ! function_exists( 'get_plugin_data') ) {
				include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$plugin_basename = plugin_basename( $this->path );
			$plugin_data = get_plugin_data( plugin_dir_path( $this->path ) . basename( $plugin_basename ) );
			if ( ! class_exists( 'Themify_Plugin_Updater' ) ) {
				include_once plugin_dir_path( $this->path ) . 'updater/class-themify-plugin-updater.php';
			}
			include_once plugin_dir_path( $this->path ) . 'includes/class-shopify-buybuttonwp-updater.php';
			$updater = new Shopify_Buybuttonwp_Updater( array(
				'base_name'   => $plugin_basename,
				'plugin_data' => $plugin_data,
				'base_path'   => plugin_dir_path( $this->path ),
				'base_url'    => plugin_dir_url( $this->path ),
				'admin_page'  => 'shopify-buybuttonwp-update-check',
			), $plugin_data['Version'] );
		}
	}

	/**
	 * Register Admin Menus.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function register_admin_menu() {
		global $submenu;

		add_menu_page( 
			esc_html__( 'About', 'shopify-buybuttonwp' ), 
			esc_html__( 'Shopify Buy Button' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'page_callback' ),
			plugin_dir_url( $this->path ) . 'admin/img/shopify_icon.png'
		);

		add_submenu_page( $this->plugin_name, 
			esc_html__( 'Appearance', 'shopify-buybuttonwp' ), 
			esc_html__( 'Appearance', 'shopify-buybuttonwp' ),
			'manage_options',
			$this->plugin_name . '-appearance',
			array( $this, 'page_callback' )
		);

		add_submenu_page( $this->plugin_name, 
			esc_html__( 'Update', 'shopify-buybuttonwp' ), 
			esc_html__( 'Update', 'shopify-buybuttonwp' ),
			'manage_options',
			$this->plugin_name . '-update-check',
			array( $this, 'page_callback' )
		);

		$submenu[ $this->plugin_name ][0][0] = esc_html__( 'About', 'shopify-buybuttonwp' );

		$this->set_valid_page_slugs();
	}

	/**
	 * Register plugin settings.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function register_settings() {
		//register our settings
		register_setting( $this->plugin_name . '_appearance', $this->plugin_name . '_appearance' );
	}

	/**
	 * Set valid admin page slugs.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function set_valid_page_slugs(){
		global $submenu;

		if( !isset( $submenu[ $this->plugin_name ] ) ) return;

		$page_list = $submenu[ $this->plugin_name ];

		$this->valid_page_slugs = array();

		foreach( $page_list as $sub_menu_page ){

			// Make sure that the slug is valid
			if( !isset( $sub_menu_page[2] ) ) continue;

			// Load up the valid pages
			$this->valid_page_slugs[] = $sub_menu_page[2];
		}
	}

	/**
	 * Page callback.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function page_callback() {
		// Make sure we have a 'page' query to look at
		if( ! isset( $_GET['page'] ) ) wp_die( esc_html__( 'No page argument has been set.' , 'shopify-buybuttonwp' ) );

		// Set the current page if the 'page' query exists
		$current_page = $_GET['page'];

		// Check the current page against valid pages
		if( ! in_array( $current_page , $this->valid_page_slugs ) ) wp_die( esc_html__( 'Invalid page slug' , 'shopify-buybuttonwp' ) );

		// Set the page slug
		$page_slug = str_replace( $this->plugin_name, '' , $current_page );

		include_once( sprintf( 'partials/shopify-buybuttonwp%s.php', esc_attr( $page_slug ) ) );
	}

	/**
	 * Redirect plugin to about page when activated.
	 * 
	 * @since 1.0.0
	 * @access public
	 */
	public function redirect_to_about_page() {
		// Bail if no activation redirect
		if ( ! get_transient( '_shopify_buybuttonwp_welcome_activation_redirect' ) )
			return;

		// Delete the redirect transient
		delete_transient( '_shopify_buybuttonwp_welcome_activation_redirect' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
			return;

		wp_safe_redirect( admin_url( 'admin.php?page=' . $this->plugin_name ) );
	}

}