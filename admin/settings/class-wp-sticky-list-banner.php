<?php


/**
 * Admin Part of Plugin, dashboard and options.
 *
 * @package    stickybar
 * @subpackage stickybar/admin
 */
class Wpstickybar_Settings extends Wpstickybar_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0 
	 * @access   private
	 * @var      string    $wpsticky    The ID of this plugin.
	 */
	private $wpsticky;

	 

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $wpsticky       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $wpsticky ) {

		$this->id    = 'general';
		$this->label = __( 'General Settings', 'woocommerce' );
		$this->wpsticky = $wpsticky;
		$this->plugin_settings_tabs['haha'] = 'bbb';
	}

	// add_action('test','DBwp_stickybar_banner');

	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function display_options_section( $params ) {

		echo '<p>' . $params['title'] . '</p>';

	} // display_options_section();


	
}
