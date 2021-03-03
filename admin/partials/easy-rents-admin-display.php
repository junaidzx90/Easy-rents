<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/admin/partials
 */
?>
<?php $admin_this = new Easy_Rents( ); ?>
<?php 
wp_enqueue_style( $admin_this->get_plugin_name() );
wp_enqueue_script( $admin_this->get_plugin_name() );
?>
<?php
// Getting page identifyer
echo '<h1> Settings </h1> <hr>';
echo '<form action="options.php" method="post">';
settings_fields( 'er_settings_section' );
do_settings_fields( 'er-settings', 'er_settings_section' );
submit_button();
echo '</form>';