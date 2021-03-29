<?php

/**
 * Fired during plugin activation
 *
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Easy_Rents
 * @subpackage Easy_Rents/includes
 * @author     Junayed <devjoo.contact@gmail.com>
 */
class Easy_Rents_Activator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		/**
		 * Application table create
		 * @package Application Table
		 */
		global $wpdb;

		$applications = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}easy_rents_applications ( `ID` INT NOT NULL AUTO_INCREMENT , `driver_id` INT NOT NULL , `customer_id` INT NOT NULL , `post_id` INT NOT NULL , `price` FLOAT NOT NULL ,`commrate` INT NOT NULL , `net_price` INT NOT NULL ,`payment` INT(11) NOT NULL,`apply_date` DATETIME NOT NULL,`payment_date` DATETIME NOT NULL,`finished_date` DATETIME NOT NULL, `status` INT NOT NULL ,`transaction_num` VARCHAR(100) NOT NULL, `create_at` DATETIME NOT NULL, PRIMARY KEY (`ID`)) ENGINE = InnoDB";

		$prelocations = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}easy_rents_prelocations ( `ID` INT NOT NULL AUTO_INCREMENT , `state` VARCHAR(500) NOT NULL, `district` VARCHAR(500) NOT NULL, `union` VARCHAR(500) NOT NULL, `create_at` DATETIME NOT NULL, PRIMARY KEY (`ID`)) ENGINE = InnoDB";

		$ertrips = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}easy_rents_trips ( `ID` INT NOT NULL AUTO_INCREMENT , `user_id` INT NOT NULL , `post_id` INT NOT NULL,`location_1` VARCHAR(255) NOT NULL, `location_2` VARCHAR(255) NOT NULL,`location_3` VARCHAR(255) NOT NULL,`unload_loc` VARCHAR(255) NOT NULL,`goods_type` VARCHAR(50) NOT NULL,`weight` INT NOT NULL,`laborer` INT NOT NULL,`truck_type` VARCHAR(50) NOT NULL,`load_time` VARCHAR(255) NOT NULL,`job_status` VARCHAR(255) NOT NULL, `create_at` DATETIME NOT NULL, PRIMARY KEY (`ID`)) ENGINE = InnoDB";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($applications);
		dbDelta($prelocations);
		dbDelta($ertrips);
	}

}