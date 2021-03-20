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

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($applications);
	}

}
