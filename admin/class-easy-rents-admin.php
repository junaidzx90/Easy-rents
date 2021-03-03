<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/admin
 * @author     Junayed <devjoo.contact@gmail.com>
 */
class Easy_Rents_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/easy-rents-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/easy-rents-admin.js', array( 'jquery' ), $this->version, false );

	}

	// General settings
	function easy_rents_setup(){
		if(is_admin(  )){
			add_menu_page( //Main menu register
				"ER Settings", //page_title
				"ER Settings", //menu title
				"manage_options", //capability
				"er-settings", //menu_slug
				array($this,"er_settings_cb"), //callback function
				"",
				65
			);
		}
	}

	// Register settings
	function er_page_settings_register(){
		add_settings_section( 'er_settings_section', 'Easy Rents Settings', '', 'er-settings' );
		// All trips page
		add_settings_field( 'trips_page', 'Trips page', array($this,'er_trips_page_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'trips_page');

		// Add new trip page
		add_settings_field( 'add_trip_page', 'Add trip page', array($this,'er_add_trip_page_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'add_trip_page');

		// Profile page
		add_settings_field( 'profile_page', 'Profile page', array($this,'er_profile_page_cb'), 'er-settings', 'er_settings_section');
		register_setting( 'er_settings_section', 'profile_page');
	}

	// All trips page calback
	function er_trips_page_cb(){
		echo '<select name="trips_page">';
		if(get_option('trips_page') != ""){
			echo '<option selected>';
			echo	__(get_option('trips_page'),'easy-rents');
			echo '</option>';
		}else{
			echo '<option selected> Select a page </option>';
		}

		$posts = get_posts(['post_type' => 'page','post_status' => 'published']);
		if($posts){
			foreach($posts as $post){
				echo '<option value="'.sanitize_text_field( $post->post_title ).'">';
				echo __($post->post_title, 'easy-rents');
				echo '</option>';
			}
		}
		echo '</select><br><br>';
	}

	// Add new trip page calback
	function er_add_trip_page_cb(){
		echo '<select name="add_trip_page">';
		if(get_option('add_trip_page') != ""){
			echo '<option selected>';
			echo	__(get_option('add_trip_page'),'easy-rents');
			echo '</option>';
		}else{
			echo '<option selected> Select a page </option>';
		}

		$posts = get_posts(['post_type' => 'page','post_status' => 'published']);
		if($posts){
			foreach($posts as $post){
				echo '<option value="'.sanitize_text_field( $post->post_title ).'">';
				echo __($post->post_title, 'easy-rents');
				echo '</option>';
			}
		}
		echo '</select><br><br>';
	}

	// Profile page calback
	function er_profile_page_cb(){
		echo '<select name="profile_page">';
		if(get_option('profile_page') != ""){
			echo '<option selected>';
			echo	__(get_option('profile_page'),'easy-rents');
			echo '</option>';
		}else{
			echo '<option selected> Select a page </option>';
		}

		$posts = get_posts(['post_type' => 'page','post_status' => 'published']);
		if($posts){
			foreach($posts as $post){
				echo '<option value="'.sanitize_text_field( $post->post_title ).'">';
				echo __($post->post_title, 'easy-rents');
				echo '</option>';
			}
		}
		echo '</select><br>';
	}

	//webclass general settings
	function er_settings_cb(){
		require_once plugin_dir_path( __FILE__ ).'partials/easy-rents-admin-display.php';
	}
}
