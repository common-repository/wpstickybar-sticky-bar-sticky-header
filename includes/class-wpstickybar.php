<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://a17lab.com
 * @since      1.0.0
 *
 * @package    Wpstickybar
 * @subpackage Wpstickybar/includes
 */

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
 * @package    Wpstickybar
 * @subpackage Wpstickybar/includes
 * @author     A17Lab <contact@a17lab.com>
 */
class Wpstickybar {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wpstickybar_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WPSTICKYBAR_VERSION' ) ) {
			$this->version = WPSTICKYBAR_VERSION;
		} else {
			$this->version = '1.0.0';
		}		
		$this->plugin_name = 'wpstickybar';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wpstickybar_Loader. Orchestrates the hooks of the plugin.
	 * - Wpstickybar_i18n. Defines internationalization functionality.
	 * - Wpstickybar_Admin. Defines all hooks for the admin area.
	 * - Wpstickybar_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpstickybar-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpstickybar-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpstickybar-admin.php';

		/**
		 * The class responsible for defining all settings that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/settings/class-wp-sticky-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpstickybar-public.php';

		$this->loader = new Wpstickybar_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wpstickybar_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wpstickybar_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Wpstickybar_Admin($this->get_plugin_name(), $this->get_version());

		$settings_init_general = new Wpstickybar_Settings($this->get_plugin_name());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$this->loader->add_action('admin_init', $settings_init_general, 'settings_api_init');

		$this->loader->add_action('admin_menu', $plugin_admin, 'wpstickybar_admin_menu');
		$this->loader->add_action('admin_menu', $plugin_admin, 'wpstickybar_sub_menu');

		if(isset($_POST['banner_submit']) && $_POST['form_type'] == 'add') {
			$this->loader->add_action('init', $plugin_admin, 'new_banner_insert_input');
		}
		
		if(isset($_POST['banner_submit']) && $_POST['form_type'] == 'edit') { 
			$this->loader->add_action('init', $plugin_admin, 'edit_banner_update_input');
		}

		$this->loader->add_action('admin_init', $plugin_admin, 'redirect_feedback_page');
		$this->loader->add_action('wp_ajax_delete_banner', $plugin_admin, 'delete_banner_callback');		
		
		$this->loader->add_action('rest_api_init', $plugin_admin, 'wpstickybar_register_rest_route');

		$this->loader->add_action('admin_footer', $plugin_admin, 'wpstickybar_attach_feedback_modal');

		// $this->loader->add_action( 'admin_notices', $plugin_admin, 'wpstickybar_display_admin_notice' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wpstickybar_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_footer', $plugin_public, 'front_end');
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'wp_ajax_stickybar_display', $plugin_public, 'stickybar_display_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_stickybar_display', $plugin_public, 'stickybar_display_callback' );

		$this->loader->add_action( 'wp_ajax_stickybar_click', $plugin_public, 'stickybar_click_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_stickybar_click', $plugin_public, 'stickybar_click_callback' );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
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
	 * @return    Wpstickybar_Loader    Orchestrates the hooks of the plugin.
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

}
