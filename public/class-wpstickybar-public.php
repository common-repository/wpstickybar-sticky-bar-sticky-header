<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://a17lab.com
 * @since      1.0.0
 *
 * @package    Wpstickybar
 * @subpackage Wpstickybar/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wpstickybar
 * @subpackage Wpstickybar/public
 * @author     A17Lab <contact@a17lab.com>
 */
class Wpstickybar_Public {

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
	 * Including front end
	 *
	 * @since    1.0.0
	 */
	public function front_end() {
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/wpstickybar-public-display.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpstickybar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpstickybar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpstickybar-public.css', array(), $this->version, 'all' );		

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpstickybar_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpstickybar_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpstickybar-public.js', array( 'jquery' ), $this->version, false );

	}

	public function stickybar_click_callback() {
		global $wpdb;
		global $tableprefix;
		$tableprefix = $wpdb->prefix;
		$stickybar_stats = $tableprefix . 'stickybar_stats';
		$meta = $_POST['update'];
		$meta = base64_decode($meta);
		$banner_id_stat = $_POST['banner_id'];
		$meta = esc_attr($meta);
		$current_date = date('Y-m-d'); ## Get current date
		$timestamp = strtotime($current_date);
		$check_timestamp = $wpdb->query("SELECT * FROM `{$stickybar_stats}` WHERE `the_time` = $timestamp and  `banner_id` = $banner_id_stat");
		if ($check_timestamp) {
			$wpdb->query("UPDATE `{$stickybar_stats}` SET `clicks` = `clicks` + 1 WHERE `banner_id` = $banner_id_stat and `the_time` = $timestamp  ");
		} else {
			$wpdb->query("INSERT INTO `{$stickybar_stats}` ( `banner_id`, `clicks`, `the_time`)  VALUES ($banner_id_stat,'1','$timestamp')");
		}
		// $wpdb->query("UPDATE `{$stickybar_stats}` SET `clicks` = `clicks` + 1 WHERE `banner_id` = $banner_id_stat ");
	}

	function stickybar_display_callback() {
		global $wpdb;
		global $tableprefix;
		$tableprefix = $wpdb->prefix;
		$stickybar_stats = $tableprefix . 'stickybar_stats';
		$meta = $_POST['update'];
		$meta = base64_decode($meta);
		$meta = esc_attr($meta);
		$banner_id_stat = $_POST['banner_id'];
		// $currentTime =  date_i18n( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
		$current_date = date('Y-m-d'); ## Get current date
		// lay epoch time cua luc bat dau ngay hien tai 20/10/2022 0:0:0
		$timestamp = strtotime($current_date);
		// $test = '1666260752';
		// check neu trong db da co id = $banner_id va thetime = epochtime thi update
		// if($wpdb->query("SELECT "))
		$check_timestamp = $wpdb->query("SELECT * FROM `{$stickybar_stats}` WHERE `the_time` = $timestamp	 and  `banner_id` = $banner_id_stat");
		// var_dump($check_timestamp);
		// die;
		if ($check_timestamp) {
			$wpdb->query("UPDATE `{$stickybar_stats}` SET `impressions` = `impressions` + 1 WHERE `banner_id` = $banner_id_stat and `the_time` = $timestamp  ");
		} else {
			$wpdb->query("INSERT INTO `{$stickybar_stats}` ( `banner_id`, `impressions`, `the_time`)  VALUES ($banner_id_stat,'1','$timestamp')");
		}

	}

}
