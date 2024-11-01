<?php

/**
 * Fired during plugin activation
 *
 * @link       https://a17lab.com
 * @since      1.0.0
 *
 * @package    Wpstickybar
 * @subpackage Wpstickybar/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpstickybar
 * @subpackage Wpstickybar/includes
 * @author     A17Lab <contact@a17lab.com>
 */
class Wpstickybar_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		global $wpdb;
		global $tableprefix;
		$tableprefix = $wpdb->prefix ;
		$option = serialize(' varchar(255)'); 
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		// create stickybar banner table
			$stickybar_banner = $tableprefix . 'stickybar_banner';
			$sql_stickybar_banner = "CREATE TABLE IF NOT EXISTS " . $stickybar_banner ."(
				`banner_id` bigint(9) unsigned NOT NULL auto_increment,
				`title` varchar(255) NOT NULL default 'Demo banner',
				`promotion_text` text,
				`button_text` varchar(255) NOT NULL default 'Call action text',
				`button_url` varchar(255) NOT NULL default ' ' ,
				`background_img` varchar(255),
				`text_color` varchar(255) default '#FFF',
				`background_color` varchar(255) default '#5a48dd',
				`text_time_color` varchar(255) default '#FFF',
				`number_time_color` varchar(255) default '#FFF',
				`background_time_color` varchar(255) default '#000',
				`enable_timmer` BOOLEAN default '0',
				`date_type` varchar(255),
				`relative_day` varchar(255) default '0',
				`relative_hour` varchar(255) default '0',
				`relative_minutes` varchar(255) default '0',
				`relative_seconds` varchar(255) default '0',
				`static_date` datetime,
				`display_banner` BOOLEAN default '0',
				`banner_position`varchar(255),
				`on_mac` BOOLEAN default '0',
				`on_mobile`BOOLEAN default '0',
				`on_page` BOOLEAN default '0',
				`on_post` BOOLEAN default '0',
				`seconds_before` int (15)  NOT NULL default '0',
				`other_options` text,
				`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  			`updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`banner_id`)
			)".$charset_collate .";";
			dbDelta($sql_stickybar_banner);

		// create stickybar stats table
			$stickybar_stats = $tableprefix . 'stickybar_stats';
			$sql_stickybar_stats = "CREATE TABLE IF NOT EXISTS  " . $stickybar_stats . " (
				`stastics_id` bigint(9) unsigned NOT NULL auto_increment,
				`banner_id` bigint(9)  NOT NULL,
				`clicks` int (15)  NOT NULL default '0',
				`impressions` int(15)  NOT NULL default '0',
				`the_time` int(25) NOT NULL default '0',
				PRIMARY KEY (`stastics_id`),
				INDEX `banner_id`(`banner_id`)
			) ". $charset_collate .";";
			dbDelta($sql_stickybar_stats);
			// $wpdb->query("INSERT INTO  `{$stickybar_stats}` (`id`, `banner`, `group`, `thetime`, `clicks`, `impressions`) VALUES ('1','1','1','1','1','1') ");
	}

}
