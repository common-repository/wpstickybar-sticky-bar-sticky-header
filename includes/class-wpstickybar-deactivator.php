<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://a17lab.com
 * @since      1.0.0
 *
 * @package    Wpstickybar
 * @subpackage Wpstickybar/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wpstickybar
 * @subpackage Wpstickybar/includes
 * @author     A17Lab <contact@a17lab.com>
 */
class Wpstickybar_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {		
		set_transient('wpstickybar_show_survey', 1, 60 * 60);
	}

}
