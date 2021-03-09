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
		
		$this->loader->add_action("wp_ajax_remove_jobfromcart", $plugin_public, "remove_jobfromcart");
		$this->loader->add_action("wp_ajax_nopriv_remove_jobfromcart", $plugin_public, "remove_jobfromcart");
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
