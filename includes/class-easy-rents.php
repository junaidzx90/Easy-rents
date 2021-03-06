<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/includes
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
 * @package    Easy_Rents
 * @subpackage Easy_Rents/includes
 * @author     Junayed <devjoo.contact@gmail.com>
 */
class Easy_Rents {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Easy_Rents_Loader    $loader    Maintains and registers all hooks for the plugin.
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


	protected $er_pre;

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
		if ( defined( 'EASY_RENTS_VERSION' ) ) {
			$this->version = EASY_RENTS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'easy-rents';
		$this->er_pre = 'er_';

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
	 * - Easy_Rents_Loader. Orchestrates the hooks of the plugin.
	 * - Easy_Rents_i18n. Defines internationalization functionality.
	 * - Easy_Rents_Admin. Defines all hooks for the admin area.
	 * - Easy_Rents_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-easy-rents-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-easy-rents-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-easy-rents-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-easy-rents-public.php';

		$this->loader = new Easy_Rents_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Easy_Rents_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Easy_Rents_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Easy_Rents_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// disable wp backend
		$this->loader->add_action( 'init', $plugin_admin, 'disable_backend_access' );
		// Redirect after login
		$this->loader->add_action( 'login_redirect', $plugin_admin, 'er_login_redirects', 10, 3 );
		// Logout redirects
		$this->loader->add_action('wp_logout', $plugin_admin, 'er_logout_redirects', 10, 3);
		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'easy_rents_setup' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'er_page_settings_register' );
		$this->loader->add_action( 'init', $plugin_admin, 'the_car_type_taxonomy' );
		$this->loader->add_action( 'init', $plugin_admin, 'er_job_post' );

		$this->loader->add_action('truckstype_add_form_fields', $plugin_admin, 'add_term_image');
		$this->loader->add_action('created_truckstype', $plugin_admin,'save_term_image');
		
		$this->loader->add_action('truckstype_edit_form_fields', $plugin_admin,'edit_image_upload');
		$this->loader->add_action('edited_truckstype', $plugin_admin,'update_image_upload');

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'load_media');
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'add_script' );
		// Custom wplist table column
		$this->loader->add_action('manage_jobs_posts_custom_column', $plugin_admin, 'wp_list_table_column_view',10,2);

		// Send sms to driver for payment
		$this->loader->add_action("wp_ajax_send_sms_forpayment", $plugin_admin, "send_sms_forpayment");
		$this->loader->add_action("wp_ajax_nopriv_send_sms_forpayment", $plugin_admin, "send_sms_forpayment");

		//Adding location
		$this->loader->add_action("wp_ajax_addNewLocation", $plugin_admin, "addNewLocation");
		$this->loader->add_action("wp_ajax_nopriv_addNewLocation", $plugin_admin, "addNewLocation");
		//Get districts
		$this->loader->add_action("wp_ajax_get_districts_under_division", $plugin_admin, "get_districts_under_division");
		$this->loader->add_action("wp_ajax_nopriv_get_districts_under_division", $plugin_admin, "get_districts_under_division");
		//Get p_stations
		$this->loader->add_action("wp_ajax_get_p_stations_under_districts", $plugin_admin, "get_p_stations_under_districts");
		$this->loader->add_action("wp_ajax_nopriv_get_p_stations_under_districts", $plugin_admin, "get_p_stations_under_districts");
		//get refresh data
		$this->loader->add_action("wp_ajax_get_all_table_data_for_refresh", $plugin_admin, "get_all_table_data_for_refresh");
		$this->loader->add_action("wp_ajax_nopriv_get_all_table_data_for_refresh", $plugin_admin, "get_all_table_data_for_refresh");
		//Delete location data
		$this->loader->add_action("wp_ajax_delete_easy_rents_location", $plugin_admin, "delete_easy_rents_location");
		$this->loader->add_action("wp_ajax_nopriv_delete_easy_rents_location", $plugin_admin, "delete_easy_rents_location");

		// check_register_user_existing
		$this->loader->add_action("wp_ajax_check_register_user_existing", $plugin_admin, "check_register_user_existing");
		$this->loader->add_action("wp_ajax_nopriv_check_register_user_existing", $plugin_admin, "check_register_user_existing");
		// register access
		$this->loader->add_action("wp_ajax_register_access_need", $plugin_admin, "register_access_need");
		$this->loader->add_action("wp_ajax_nopriv_register_access_need", $plugin_admin, "register_access_need");
		// er_user_login_process
		$this->loader->add_action("wp_ajax_er_user_login_process", $plugin_admin, "er_user_login_process");
		$this->loader->add_action("wp_ajax_nopriv_er_user_login_process", $plugin_admin, "er_user_login_process");
		// er_reset_user_password
		$this->loader->add_action("wp_ajax_er_reset_user_password", $plugin_admin, "er_reset_user_password");
		$this->loader->add_action("wp_ajax_nopriv_er_reset_user_password", $plugin_admin, "er_reset_user_password");
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Easy_Rents_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'delete_post', $plugin_public, 'job_post_delete', 10 );
		$this->loader->add_action( 'wp_trash_post', $plugin_public, 'job_post_delete', 10 );
		// Adminbar showing
		$this->loader->add_action( 'init', $plugin_public, 'er_admin_bar_showing', 10 );

		// Removemyreq
		$this->loader->add_action("wp_ajax_remove_jobfromcart", $plugin_public, "remove_jobfromcart");
		$this->loader->add_action("wp_ajax_nopriv_remove_jobfromcart", $plugin_public, "remove_jobfromcart");
		
		// ignorerequest
		$this->loader->add_action("wp_ajax_ignorerequest", $plugin_public, "ignorerequest");
		$this->loader->add_action("wp_ajax_nopriv_ignorerequest", $plugin_public, "ignorerequest");

		// acceptrequest
		$this->loader->add_action("wp_ajax_acceptrequest", $plugin_public, "acceptrequest");
		$this->loader->add_action("wp_ajax_nopriv_acceptrequest", $plugin_public, "acceptrequest");

		// request for finished
		$this->loader->add_action("wp_ajax_requestforfinishedjob", $plugin_public, "requestforfinishedjob");
		$this->loader->add_action("wp_ajax_nopriv_requestforfinishedjob", $plugin_public, "requestforfinishedjob");
		// finished confirm
		$this->loader->add_action("wp_ajax_finishedconfirmed", $plugin_public, "finishedconfirmed");
		$this->loader->add_action("wp_ajax_nopriv_finishedconfirmed", $plugin_public, "finishedconfirmed");

		//Get districts
		$this->loader->add_action("wp_ajax_pbget_districts_under_division", $plugin_public, "pbget_districts_under_division");
		$this->loader->add_action("wp_ajax_nopriv_pbget_districts_under_division", $plugin_public, "pbget_districts_under_division");
		//Get p_stations
		$this->loader->add_action("wp_ajax_pbget_p_stations_under_districts", $plugin_public, "pbget_p_stations_under_districts");
		$this->loader->add_action("wp_ajax_nopriv_pbget_p_stations_under_districts", $plugin_public, "pbget_p_stations_under_districts");
		//Create Job
		$this->loader->add_action("wp_ajax_er_create_job", $plugin_public, "er_create_job");
		$this->loader->add_action("wp_ajax_nopriv_er_create_job", $plugin_public, "er_create_job");
		//Update user info
		$this->loader->add_action("wp_ajax_update_user_info", $plugin_public, "update_user_info");
		$this->loader->add_action("wp_ajax_nopriv_update_user_info", $plugin_public, "update_user_info");
		//Refer code check
		$this->loader->add_action("wp_ajax_er_refer_code_check", $plugin_public, "er_refer_code_check");
		$this->loader->add_action("wp_ajax_nopriv_er_refer_code_check", $plugin_public, "er_refer_code_check");
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
	 * The customer unique invoice number
	 */
	public function get_invoice_id($userid) {
		return $this->er_pre.$userid.rand(0,99).$userid.rand(99,9999);
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Easy_Rents_Loader    Orchestrates the hooks of the plugin.
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
