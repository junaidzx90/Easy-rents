<?php
ob_start();
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              example.com
 * @since             0.1
 * @package           Easy_Rents
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Rents
 * Plugin URI:        example.com/easy-rents
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           0.3
 * Author:            Junayed
 * Author URI:        example.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       easy-rents
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 0.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'EASY_RENTS_VERSION', '0.3' );
define( 'ER_PATH', plugin_dir_path( __FILE__ ) );
define( 'ER_URL', plugin_dir_url( __FILE__ ) );

// include update manager
update_manager();
function update_manager(){
	if(file_exists(plugin_dir_path( __FILE__ ) . 'includes/update_manager/plugin-update-checker.php')){
		require_once plugin_dir_path( __FILE__ ) . 'includes/update_manager/plugin-update-checker.php';
		$update = Puc_v4p10_Factory::buildUpdateChecker( 'https://updates.easeare.com/plugins/easy-rents/controller.json', __FILE__ );
	}
	return $update;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-easy-rents-activator.php
 */
function activate_easy_rents() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-rents-activator.php';
	Easy_Rents_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-easy-rents-deactivator.php
 */
function deactivate_easy_rents() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-easy-rents-deactivator.php';
	Easy_Rents_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_easy_rents' );
register_deactivation_hook( __FILE__, 'deactivate_easy_rents' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-easy-rents.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1
 */
function run_easy_rents() {

	$plugin = new Easy_Rents();
	$plugin->run();

}
run_easy_rents();