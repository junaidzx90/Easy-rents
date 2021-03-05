<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       example.com
 * @since      1.0.0
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Easy_Rents
 * @subpackage Easy_Rents/public
 * @author     Junayed <devjoo.contact@gmail.com>
 */
class Easy_Rents_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// easy rents add filters
		$this->er_add_fileters();
		
	}

	// easy rents add filters
	function er_add_fileters(){
		// webclass Load page template
		add_filter ('theme_page_templates', array($this,'webclass_templates'));
		add_filter( 'template_include', array($this,'wp_page_attributes' ));
		// webclass archive page include for projects
		add_filter('template_include', array($this,'projects_template'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		// Profile style
		wp_register_style( 'er_profile_style', plugin_dir_url( __FILE__ ) . 'css/easy-rents-profile.css', array(), $this->version, 'all' );
		// addnewjob style
		wp_register_style( 'er_addjob_style', plugin_dir_url( __FILE__ ) . 'css/easy-rents-addjob.css', array(), $this->version, 'all' );
		// addnewjob style
		wp_register_style( 'er_jobs_style', plugin_dir_url( __FILE__ ) . 'css/easy-rents-jobs.css', array(), $this->version, 'all' );
		// select2 style
		wp_register_style( 'select2', plugin_dir_url( __FILE__ ) . 'css/select2.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// Profile script
		wp_register_script( 'er_profile_script', plugin_dir_url( __FILE__ ) . 'js/easy-rents-profile.js', array( 'jquery' ), $this->version, true );
		
		// addjob script
		wp_register_script( 'er_addjob_script', plugin_dir_url( __FILE__ ) . 'js/easy-rents-addjob.js', array( 'jquery' ), $this->version, true );

		// addjob script
		wp_register_script( 'er_jobs_script', plugin_dir_url( __FILE__ ) . 'js/easy-rents-jobs.js', array( 'jquery' ), $this->version, true );

		// select2 script
		wp_register_script( 'select2', plugin_dir_url( __FILE__ ) . 'js/select2.min.js', array( 'jquery' ), $this->version, true );

	}

	
	

	/**
	 * Define template name
	 */
	function webclass_templates ($templates) {
		$templates['er_jobs.php'] = 'All jobs';
		$templates['er_addjob.php'] = 'Add job';
		$templates['er_profile.php'] = 'Profile';
		
		return $templates;
	}
	

	// Set custom page attributes
	function wp_page_attributes( $template ) {
		if(  get_page_template_slug() === 'er_jobs.php' ) {

			if ( $theme_file = locate_template( array( 'er_jobs.php' ) ) ) {
				$template = $theme_file;
			} else {
				$template = ER_PATH . 'public/partials/er_jobs.php';
			}
		}
		
		if(  get_page_template_slug() === 'er_addjob.php' ) {

			if ( $theme_file = locate_template( array( 'er_addjob.php' ) ) ) {
				$template = $theme_file;
			} else {
				$template = ER_PATH . 'public/partials/er_addjob.php';
			}
		}
		
		if(  get_page_template_slug() === 'er_profile.php' ) {

			if ( $theme_file = locate_template( array( 'er_profile.php' ) ) ) {
				$template = $theme_file;
			} else {
				$template = ER_PATH . 'public/partials/er_profile.php';
			}
		}

		if($template == '') {
			throw new \Exception('No template found');
		}

		return $template;
	}

	// Define custom post template with single page
	function projects_template( $template ) {
		
		// For custom archive page
		if ( is_post_type_archive('jobs') ) {
			$theme_files = array('er_jobs.php', '/partials/er_jobs.php');
			$exists_in_theme = locate_template($theme_files, false);
			if ( $exists_in_theme != '' ) {
				return $exists_in_theme;
			} else {
				return  dirname( __FILE__ ) . '/partials/er_jobs.php';
			}
		}

			// For custom single post
		if ( is_singular('jobs') ) {
			$theme_post = array('page-job.php', '/partials/page-job.php');
			$exists_in_theme_pst = locate_template($theme_post, false);
			if ( $exists_in_theme_pst != '' ) {
				return $exists_in_theme_pst;
			} else {
				return  dirname( __FILE__ ) . '/partials/page-job.php';
			}
		}
		return $template;
	}
}
